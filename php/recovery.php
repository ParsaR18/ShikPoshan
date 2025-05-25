<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config.php';

$message = '';
$message_type = '';
$show_reset_form = false;
$token_valid = false;
$post_processed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_processed = true;
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        error_log("DB Connect Error: " . $conn->connect_error);
        $message = "خطای سیستمی، لطفاً بعداً تلاش کنید.";
        $message_type = 'error';
    } else {
        if (isset($_POST['request_recovery']) && isset($_POST['email'])) {
            $email = trim($_POST['email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = "فرمت ایمیل نامعتبر است.";
                $message_type = 'error';
            } else {
                $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
                if ($stmt) {
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows === 1) {
                        $token = bin2hex(random_bytes(32));
                        $expires = time() + (15 * 60);

                        $stmt_token = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, FROM_UNIXTIME(?)) ON DUPLICATE KEY UPDATE token = VALUES(token), expires_at = VALUES(expires_at)");
                        if ($stmt_token) {
                            $stmt_token->bind_param("ssi", $email, $token, $expires);
                            if ($stmt_token->execute()) {
                                $reset_link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?token=" . $token;
                                error_log("Password reset link for $email: $reset_link");

                                $message = "لینک بازیابی رمز عبور به کنسول خطا لاگ شد (در صورت وجود حساب کاربری با این ایمیل). لینک تا 15 دقیقه معتبر است.";
                                $message_type = 'success';
                            } else {
                                $message = "خطا در ایجاد توکن بازیابی.";
                                $message_type = 'error';
                                error_log("Token insert error: " . $stmt_token->error);
                            }
                            $stmt_token->close();
                        } else {
                            $message = "خطا در آماده‌سازی درخواست بازیابی.";
                            $message_type = 'error';
                            error_log("Token prepare error: " . $conn->error);
                        }
                    } else {
                        $message = "اگر ایمیل وارد شده در سیستم ما موجود باشد، لینک بازیابی به کنسول خطا لاگ خواهد شد.";
                        $message_type = 'info';
                    }
                    $stmt->close();
                } else {
                    $message = "خطا در بررسی ایمیل.";
                    $message_type = 'error';
                    error_log("Email check prepare error: " . $conn->error);
                }
            }
        } elseif (isset($_POST['reset_password']) && isset($_POST['token']) && isset($_POST['new_password']) && isset($_POST['confirm_new_password'])) {
            $token = $_POST['token'];
            $new_password = $_POST['new_password'];
            $confirm_new_password = $_POST['confirm_new_password'];
            $token_valid = true;
            $show_reset_form = true;


            if (empty($new_password) || empty($confirm_new_password)) {
                $message = "لطفاً رمز عبور جدید و تکرار آن را وارد کنید.";
                $message_type = 'error';
            } elseif (strlen($new_password) < 8) {
                $message = "رمز عبور جدید باید حداقل 8 کاراکتر باشد.";
                $message_type = 'error';
            } elseif ($new_password !== $confirm_new_password) {
                $message = "رمز عبور جدید و تکرار آن با هم مطابقت ندارند.";
                $message_type = 'error';
            } else {
                $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
                if ($stmt) {
                    $stmt->bind_param("s", $token);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows === 1) {
                        $row = $result->fetch_assoc();
                        $email_to_update = $row['email'];
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                        $stmt_update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
                        if ($stmt_update) {
                            $stmt_update->bind_param("ss", $hashed_password, $email_to_update);
                            if ($stmt_update->execute()) {
                                $stmt_delete_token = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
                                if ($stmt_delete_token) {
                                    $stmt_delete_token->bind_param("s", $email_to_update);
                                    $stmt_delete_token->execute();
                                    $stmt_delete_token->close();
                                }
                                $message = "رمز عبور شما با موفقیت تغییر یافت. اکنون می‌توانید با رمز جدید وارد شوید.";
                                $message_type = 'success';
                                $show_reset_form = false;
                                $token_valid = false;
                            } else {
                                $message = "خطا در به‌روزرسانی رمز عبور.";
                                $message_type = 'error';
                                error_log("Pass update error: " . $stmt_update->error);
                            }
                            $stmt_update->close();
                        } else {
                            $message = "خطا در آماده‌سازی به‌روزرسانی رمز عبور.";
                            $message_type = 'error';
                            error_log("Pass update prepare error: " . $conn->error);
                        }
                    } else {
                        $message = "توکن بازیابی نامعتبر است یا منقضی شده. لطفاً دوباره درخواست دهید.";
                        $message_type = 'error';
                        $show_reset_form = false;
                        $token_valid = false;
                    }
                    $stmt->close();
                } else {
                    $message = "خطا در اعتبارسنجی توکن.";
                    $message_type = 'error';
                    error_log("Token check prepare error: " . $conn->error);
                }
            }
        }
        if (isset($conn)) {
            $conn->close();
        }
    }
} elseif (isset($_GET['token'])) {
    $token_from_get = $_GET['token'];
    $conn_check = new mysqli($host, $db_username, $db_password, $database);
    if ($conn_check->connect_error) {
        error_log("DB Connect Error (token check): " . $conn_check->connect_error);
        $message = "خطای سیستمی، لطفاً بعداً تلاش کنید.";
        $message_type = 'error';
    } else {
        $stmt_check_get = $conn_check->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
        if ($stmt_check_get) {
            $stmt_check_get->bind_param("s", $token_from_get);
            $stmt_check_get->execute();
            $result_get = $stmt_check_get->get_result();
            if ($result_get->num_rows === 1) {
                $show_reset_form = true;
                $token_valid = true;
            } else {
                $message = "توکن بازیابی نامعتبر است یا منقضی شده. لطفاً دوباره درخواست دهید.";
                $message_type = 'error';
                $show_reset_form = false;
            }
            $stmt_check_get->close();
        } else {
            $message = "خطای سیستمی در بررسی توکن.";
            $message_type = 'error';
            error_log("Token GET check prepare error: " . $conn_check->error);
        }
        if (isset($conn_check)) {
            $conn_check->close();
        }
    }
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/recoveryphp.css">
    <script src="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/ionicons.js"></script>
    <link rel="icon" type="image/png" href="../images/logos/hat.png">
    <title>بازیابی رمز عبور</title>
</head>

<body class="page-recovery">
    <section id="sec">
        <h1 id="h1">بازیابی رمز عبور</h1>
        <?php
        if (!empty($message)) {
            $message_div_class = ($message_type === 'success') ? 'success' : (($message_type === 'error') ? 'error' : 'info');
            echo "<div class='message " . $message_div_class . "'>" . htmlspecialchars($message) . "</div>";
        }
        ?>

        <?php if ($show_reset_form && $token_valid): ?>
            <form id="resetPasswordForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? $_POST['token'] ?? ''); ?>">
                <div>
                    <label for="new_password">رمز عبور جدید:</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div>
                    <label for="confirm_new_password">تکرار رمز عبور جدید:</label>
                    <input type="password" id="confirm_new_password" name="confirm_new_password" required>
                </div>
                <button type="submit" name="reset_password">ثبت رمز عبور جدید</button>
            </form>
        <?php elseif (!$post_processed && !$token_valid && empty($message) || ($message_type !== 'success' && !$show_reset_form)): ?>
            <form id="requestRecoveryForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <p>لطفاً ایمیل حساب کاربری خود را برای دریافت لینک بازیابی رمز عبور (که در کنسول خطا نمایش داده می‌شود) وارد کنید.</p>
                <div>
                    <label for="email">ایمیل:</label>
                    <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <button type="submit" name="request_recovery">ارسال لینک بازیابی</button>
            </form>
        <?php endif; ?>
        <br>
        <button type="button" onclick="window.location.href='login.php';">بازگشت به صفحه ورود</button>
    </section>
    <script src="../js/recovery.js"></script>
</body>

</html>
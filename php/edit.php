<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$current_email = '';
$message = '';
$message_type = '';

$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    $message = "خطایی در سیستم رخ داده است. لطفاً بعداً تلاش کنید.";
    $message_type = 'error';
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_email = trim($_POST['email']);
        $current_password_for_email_change = $_POST['current_password_for_email_change'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_new_password = $_POST['confirm_new_password'] ?? '';
        $current_password_for_password_change = $_POST['current_password_for_password_change'] ?? '';
        $action_taken = false;

        $stmt_user = $conn->prepare("SELECT email, password FROM users WHERE id = ?");
        if (!$stmt_user) {
            error_log("Prepare failed (user select): (" . $conn->errno . ") " . $conn->error);
            $message = "خطا در بارگذاری اطلاعات کاربر.";
            $message_type = 'error';
        } else {
            $stmt_user->bind_param("i", $user_id);
            $stmt_user->execute();
            $result_user = $stmt_user->get_result();
            $user_data = $result_user->fetch_assoc();
            $current_email = $user_data['email'];
            $current_hashed_password = $user_data['password'];
            $stmt_user->close();

            if (!empty($new_email) && $new_email !== $current_email) {
                if (empty($current_password_for_email_change)) {
                    $message = "برای تغییر ایمیل، لطفاً رمز عبور فعلی خود را وارد کنید.";
                    $message_type = 'error';
                } elseif (!password_verify($current_password_for_email_change, $current_hashed_password)) {
                    $message = "رمز عبور فعلی برای تغییر ایمیل نادرست است.";
                    $message_type = 'error';
                } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                    $message = "فرمت ایمیل جدید نامعتبر است.";
                    $message_type = 'error';
                } else {
                    $stmt_check_email = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                    if ($stmt_check_email) {
                        $stmt_check_email->bind_param("si", $new_email, $user_id);
                        $stmt_check_email->execute();
                        $stmt_check_email->store_result();
                        if ($stmt_check_email->num_rows > 0) {
                            $message = "ایمیل جدید قبلاً توسط کاربر دیگری ثبت شده است.";
                            $message_type = 'error';
                        } else {
                            $stmt_update_email = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
                            if ($stmt_update_email) {
                                $stmt_update_email->bind_param("si", $new_email, $user_id);
                                if ($stmt_update_email->execute()) {
                                    $_SESSION['email'] = $new_email;
                                    $current_email = $new_email;
                                    $message .= "ایمیل با موفقیت به‌روزرسانی شد. ";
                                    $message_type = 'success';
                                    $action_taken = true;
                                } else {
                                    $message .= "خطا در به‌روزرسانی ایمیل. ";
                                    $message_type = 'error';
                                }
                                $stmt_update_email->close();
                            } else {
                                error_log("Prepare failed (email update): (" . $conn->errno . ") " . $conn->error);
                            }
                        }
                        $stmt_check_email->close();
                    } else {
                        error_log("Prepare failed (email check): (" . $conn->errno . ") " . $conn->error);
                    }
                }
            }

            if (!empty($new_password)) {
                if (empty($current_password_for_password_change)) {
                    $message .= "برای تغییر رمز عبور، لطفاً رمز عبور فعلی خود را وارد کنید. ";
                    $message_type = empty($message) ? 'error' : $message_type;
                } elseif (!password_verify($current_password_for_password_change, $current_hashed_password)) {
                    $message .= "رمز عبور فعلی برای تغییر رمز عبور نادرست است. ";
                    $message_type = empty($message) ? 'error' : $message_type;
                } elseif (strlen($new_password) < 8) {
                    $message .= "رمز عبور جدید باید حداقل 8 کاراکتر باشد. ";
                    $message_type = empty($message) ? 'error' : $message_type;
                } elseif ($new_password !== $confirm_new_password) {
                    $message .= "رمز عبور جدید و تکرار آن با هم مطابقت ندارند. ";
                    $message_type = empty($message) ? 'error' : $message_type;
                } else {
                    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt_update_password = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                    if ($stmt_update_password) {
                        $stmt_update_password->bind_param("si", $new_hashed_password, $user_id);
                        if ($stmt_update_password->execute()) {
                            $message .= "رمز عبور با موفقیت به‌روزرسانی شد.";
                            $message_type = ($message_type === 'error' && $action_taken) ? 'error' : 'success';
                            $action_taken = true;
                        } else {
                            $message .= "خطا در به‌روزرسانی رمز عبور.";
                            $message_type = 'error';
                        }
                        $stmt_update_password->close();
                    } else {
                        error_log("Prepare failed (password update): (" . $conn->errno . ") " . $conn->error);
                    }
                }
            }
            if (!$action_taken && $_SERVER["REQUEST_METHOD"] == "POST" && empty($message)) {
                $message = "هیچ تغییری برای ذخیره وجود ندارد یا اطلاعاتی وارد نشده است.";
                $message_type = 'info';
            }
        }
    } else {
        $stmt_user = $conn->prepare("SELECT email FROM users WHERE id = ?");
        if ($stmt_user) {
            $stmt_user->bind_param("i", $user_id);
            $stmt_user->execute();
            $result_user = $stmt_user->get_result();
            if ($result_user->num_rows === 1) {
                $user_data = $result_user->fetch_assoc();
                $current_email = $user_data['email'];
            } else {
                $message = "کاربر یافت نشد.";
                $message_type = 'error';
            }
            $stmt_user->close();
        } else {
            error_log("Prepare failed (initial user load): (" . $conn->errno . ") " . $conn->error);
            $message = "خطا در بارگذاری اطلاعات کاربر.";
            $message_type = 'error';
        }
    }
    if ($conn) {
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/user_edit.css">
    <script src="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/ionicons.js"></script>
    <link rel="icon" type="image/png" href="../images/logos/hat.png">
    <title>ویرایش اطلاعات</title>
</head>

<body class="page-edit">
    <header class="page-header-transparent">
        <div class="logo-title">
            <img src="../images/logos/hat.png" alt="لوگو شیک پوشان" class="logo">
            <h1>شیک پوشان</h1>
        </div>
        <nav>
            <a href="dashboard.php"><ion-icon name="arrow-back-outline"></ion-icon> بازگشت به داشبورد</a>
            <a href="logout.php"><ion-icon name="log-out-outline"></ion-icon> خروج</a>
        </nav>
    </header>

    <main class="edit-glass-panel">
        <h2>ویرایش اطلاعات حساب کاربری</h2>

        <?php
        if (!empty($message)) {
            $message_div_class = 'form-message ';
            if ($message_type === 'success') $message_div_class .= 'success';
            elseif ($message_type === 'error') $message_div_class .= 'error';
            else $message_div_class .= 'info';
            echo "<div class='" . $message_div_class . "'>" . htmlspecialchars($message) . "</div>";
        }
        ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="editForm">
            <fieldset>
                <legend>تغییر ایمیل</legend>
                <div class="form-group">
                    <label for="current_email_display">ایمیل فعلی:</label>
                    <input type="email" id="current_email_display" name="current_email_display_field" value="<?php echo htmlspecialchars($current_email); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="email">ایمیل جدید (اختیاری):</label>
                    <input type="email" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="current_password_for_email_change">رمز عبور فعلی (برای تغییر ایمیل):</label>
                    <input type="password" id="current_password_for_email_change" name="current_password_for_email_change">
                </div>
            </fieldset>

            <fieldset>
                <legend>تغییر رمز عبور</legend>
                <div class="form-group">
                    <label for="new_password">رمز عبور جدید (اختیاری):</label>
                    <input type="password" id="new_password" name="new_password">
                </div>
                <div class="form-group">
                    <label for="confirm_new_password">تکرار رمز عبور جدید:</label>
                    <input type="password" id="confirm_new_password" name="confirm_new_password">
                </div>
                <div class="form-group">
                    <label for="current_password_for_password_change">رمز عبور فعلی (برای تغییر رمز عبور):</label>
                    <input type="password" id="current_password_for_password_change" name="current_password_for_password_change">
                </div>
            </fieldset>

            <button type="submit" class="form-button">ذخیره تغییرات</button>
        </form>
    </main>

    <footer class="page-footer-transparent">
        <p>&copy; <?php echo date("Y"); ?> شیک پوشان. تمام حقوق محفوظ است.</p>
    </footer>
    <script src="../js/edit_validation.js"></script>
</body>

</html>
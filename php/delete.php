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
$message = '';
$message_type = '';
$confirmation_step = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error);
        $message = "خطایی در سیستم رخ داده است. لطفاً بعداً تلاش کنید.";
        $message_type = 'error';
    } else {
        if (isset($_POST['confirm_delete']) && isset($_POST['password'])) {
            $password_attempt = $_POST['password'];

            $stmt_user = $conn->prepare("SELECT password FROM users WHERE id = ?");
            if ($stmt_user) {
                $stmt_user->bind_param("i", $user_id);
                $stmt_user->execute();
                $result_user = $stmt_user->get_result();
                $user = $result_user->fetch_assoc();
                $stmt_user->close();

                if ($user && password_verify($password_attempt, $user['password'])) {
                    $stmt_delete = $conn->prepare("DELETE FROM users WHERE id = ?");
                    if ($stmt_delete) {
                        $stmt_delete->bind_param("i", $user_id);
                        if ($stmt_delete->execute()) {
                            $_SESSION = array();
                            if (ini_get("session.use_cookies")) {
                                $params = session_get_cookie_params();
                                setcookie(
                                    session_name(),
                                    '',
                                    time() - 42000,
                                    $params["path"],
                                    $params["domain"],
                                    $params["secure"],
                                    $params["httponly"]
                                );
                            }
                            session_destroy();
                            header("Location: ../index.php?message=account_deleted_successfully");
                            exit();
                        } else {
                            error_log("Error deleting user: " . $stmt_delete->error);
                            $message = "خطا در حذف حساب کاربری.";
                            $message_type = 'error';
                        }
                        $stmt_delete->close();
                    } else {
                        error_log("Prepare failed (delete user): " . $conn->error);
                        $message = "خطا در آماده‌سازی عملیات حذف.";
                        $message_type = 'error';
                    }
                } else {
                    $message = "رمز عبور وارد شده برای تایید حذف نادرست است.";
                    $message_type = 'error';
                    $confirmation_step = true;
                }
            } else {
                error_log("Prepare failed (fetch user for delete): " . $conn->error);
                $message = "خطا در بررسی اطلاعات کاربری.";
                $message_type = 'error';
                $confirmation_step = true;
            }
        } elseif (isset($_POST['action']) && $_POST['action'] === 'request_delete') {
            $confirmation_step = true;
            $message = "آیا از حذف حساب کاربری خود اطمینان دارید؟ این عمل غیرقابل بازگشت است. برای تایید، رمز عبور خود را وارد کنید.";
            $message_type = 'warning';
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/user_delete.css">
    <script src="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/ionicons.js"></script>
    <link rel="icon" type="image/png" href="../images/logos/hat.png">
    <title>حذف حساب کاربری</title>
</head>

<body class="page-delete">
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

    <main class="delete-glass-panel">
        <h2>حذف حساب کاربری</h2>

        <?php
        if (!empty($message)) {
            $message_div_class = 'form-message ';
            if ($message_type === 'success') $message_div_class .= 'success';
            elseif ($message_type === 'error') $message_div_class .= 'error';
            elseif ($message_type === 'warning') $message_div_class .= 'warning';
            else $message_div_class .= 'info';
            echo "<div class='" . $message_div_class . "'>" . htmlspecialchars($message) . "</div>";
        }
        ?>

        <?php if ($confirmation_step): ?>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="confirmDeleteForm">
                <div class="form-group">
                    <label for="password">رمز عبور برای تایید:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <input type="hidden" name="confirm_delete" value="true">
                <button type="submit" class="form-button confirm">تایید نهایی و حذف حساب</button>
                <a href="dashboard.php" class="form-button cancel">انصراف</a>
            </form>
        <?php else: ?>
            <p class="confirmation-text">با کلیک بر روی دکمه زیر، درخواست حذف حساب کاربری خود را آغاز می‌کنید. این عمل غیر قابل بازگشت است.</p>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="action" value="request_delete">
                <button type="submit" class="form-button initiate">درخواست حذف حساب کاربری</button>
            </form>
        <?php endif; ?>
    </main>

    <footer class="page-footer-transparent">
        <p>&copy; <?php echo date("Y"); ?> شیک پوشان. تمام حقوق محفوظ است.</p>
    </footer>
    <script src="../js/delete_validation.js"></script>
</body>

</html>
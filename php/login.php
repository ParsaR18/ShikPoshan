<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$login_error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password'])) {
    $email_attempt = trim($_POST["email"]);
    $password_attempt = $_POST["password"];

    if (empty($email_attempt) || empty($password_attempt)) {
        $login_error_message = "<div class='error'>ایمیل و رمز عبور نمی‌توانند خالی باشند.</div>";
    } else {
        $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        if ($conn->connect_error) {
            error_log("Database connection failed: " . $conn->connect_error);
            $login_error_message = "<div class='error'>خطایی در سیستم رخ داده است. لطفاً بعداً تلاش کنید.</div>";
        } else {
            $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
            if ($stmt) {
                $stmt->bind_param("s", $email_attempt);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password_attempt, $user['password'])) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['email'] = $user['email'];
                        session_regenerate_id(true);
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        $login_error_message = "<div class='error'>ایمیل یا رمز عبور اشتباه است.</div>";
                    }
                } else {
                    $login_error_message = "<div class='error'>ایمیل یا رمز عبور اشتباه است.</div>";
                }
                $stmt->close();
            } else {
                error_log("Error preparing login statement: " . $conn->error);
                $login_error_message = "<div class='error'>خطایی در پردازش اطلاعات رخ داده است.</div>";
            }
            $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/loginphp.css">
    <script src="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/ionicons.js"></script>
    <link rel="icon" type="image/png" href="../images/logos/hat.png">
    <title>ورود</title>
</head>

<body class="page-login">
    <section id="sec">
        <form id="loginForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h1 id="h1">اطلاعات ورودی</h1>

            <?php
            if (!empty($login_error_message)) {
                echo $login_error_message;
            }
            ?>

            <div>
                <label for="email">ایمیل:</label>
                <input type="email" id="email" name="email"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>
            <div>
                <label for="password">رمز عبور:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">ورود</button>
            <br>
            <button type="button" onclick="window.location.href='../index.php';">بازگشت به صفحه اصلی</button>
            <p class="center-text">حساب کاربری ندارید؟ <a href="register.php">ثبت نام کنید</a></p>
            <p class="center-text"><a href="recovery.php">رمز عبور خود را فراموش کرده‌اید؟</a></p>
        </form>
    </section>
    <script src="../js/login.js"></script>
</body>

</html>
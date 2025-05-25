<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/user_dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/ionicons.js"></script>
    <link rel="icon" type="image/png" href="../images/logos/hat.png">
    <title>داشبورد کاربر</title>
</head>

<body class="page-dashboard">
    <header class="page-header-transparent">
        <div class="logo-title">
            <img src="../images/logos/hat.png" alt="لوگو شیک پوشان" class="logo">
            <h1>شیک پوشان</h1>
        </div>
        <nav>
            <a href="../index.php"><ion-icon name="home-outline"></ion-icon> صفحه اصلی</a>
            <a href="../upload/main.php"><ion-icon name="cloud-upload-outline"></ion-icon> آپلود</a>
            <a href="logout.php"><ion-icon name="log-out-outline"></ion-icon> خروج</a>
        </nav>
    </header>

    <main class="dashboard-glass-panel">
        <h1>خوش آمدید، <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'کاربر'; ?>!</h1>
        <p>اینجا داشبورد کاربری شماست. از طریق لینک‌های زیر می‌توانید اطلاعات حساب خود را مدیریت کنید.</p>
        <nav class="dashboard-nav-buttons">
            <a href="edit.php" class="glass-button"><ion-icon name="create-outline"></ion-icon> ویرایش اطلاعات</a>
            <a href="delete.php" class="glass-button delete"><ion-icon name="trash-outline"></ion-icon> حذف حساب کاربری</a>
        </nav>
    </main>

    <footer class="page-footer-transparent">
        <p>&copy; <?php echo date("Y"); ?> شیک پوشان. تمام حقوق محفوظ است.</p>
    </footer>
</body>

</html>
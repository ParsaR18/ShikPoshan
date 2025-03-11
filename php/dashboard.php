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

<body>
    <?php
    session_start();
    if (!isset($_SESSION['email'])) {
        header("Location: login.php");
        exit();
    }
    ?>
    <section>
        <h1 id="h1">خوش آمدید، <?php echo $_SESSION['email']; ?></h1>
        <a href="edit.php">ویرایش اطلاعات</a>
        <a href="delete.php">حذف حساب کاربری</a>
        <a href="logout.php">خروج</a>

    </section>
</body>

</html>
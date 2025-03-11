<?php
session_start();

$host = "localhost";
$db_username = "php";
$db_password = "Z4a)CGXs)]i.VJFw";
$database = "project";

$conn = new mysqli($host, $db_username, $db_password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $sql = "SELECT username, password FROM logininfo WHERE username='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $message = "<div class='div1'>ایمیل: " . $row['username'] . "<br>رمز عبور: " . $row['password'] . "</div>";
    } else {
        $message = "<div class='div1'>ایمیل وارد شده وجود ندارد.</div>";
    }
}

$conn->close();
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

<body>
    <section>
        <h1 class="h1">اطلاعات ورود</h1>
        <form id="form" action="" method="post">
        <?php if (!empty($message))
            echo $message; ?>
            <div><a href="../login/recovery.html"><button type="button">بازگشت</button></a></div>
        </form>
    </section>
</body>

</html>
<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$db_username = "root";
$db_password = "";
$database = "project";

$conn = new mysqli($host, $db_username, $db_password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if (isset($_POST['selected_email']) && isset($_POST['new_password'])) {
    $selected_email = $_POST['selected_email'];
    $new_password = $_POST['new_password'];

    $sql = "UPDATE logininfo SET password='$new_password' WHERE username='$selected_email'";
    if ($conn->query($sql) === TRUE) {
        $message = "<div id='div1' class='success'>رمز عبور با موفقیت ویرایش شد.</div>";
    } else {
        $message = "<div id='div1' class='error'>خطا در ویرایش رمز عبور: " . $conn->error . "</div>";
    }
}

$sql = "SELECT username FROM logininfo";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/user_edit.css">
    <script src="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/ionicons.js"></script>
    <link rel="icon" type="image/png" href="../images/logos/hat.png">
    <title>ویرایش اطلاعات کاربری</title>
</head>

<body>
    <div id="boody">
        <div id="Sction">
            <section id="sec">
                <h1>ویرایش رمز عبور</h1>
                <h3>انتخاب ایمیل: </h3>
                <form id="editForm" method="post" action="edit.php">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<label>";
                            echo "<input type='radio' name='selected_email' value='" . $row['username'] . "'>";
                            echo "<span> " . $row['username'] . " </span>";
                            echo "</label>";
                        }
                    }
                    ?>
                    <br>
                    <div id="inputbxx">
                        <ion-icon name="key-outline"></ion-icon>
                        <input id="input" class="inputbox" type="password" name="new_password"
                            placeholder="رمز عبور جدید">
                    </div>
                    <button type="submit">ویرایش</button>
                </form>
                <a href="dashboard.php">بازگشت به داشبورد</a>
            </section>
        </div>
        <?php
        echo $message;
        ?>
        <script src="../js/edit_validation.js"></script>
    </div>
</body>

</html>

<?php
$conn->close();
?>
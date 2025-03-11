<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$db_username = "php";
$db_password = "Z4a)CGXs)]i.VJFw";
$database = "project";

$conn = new mysqli($host, $db_username, $db_password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if (isset($_POST['selected_email'])) {
    $selected_email = $_POST['selected_email'];

    $sql = "DELETE FROM logininfo WHERE username='$selected_email'";
    if ($conn->query($sql) === TRUE) {
        $message = "<div id='div1' class='success'>کاربر با موفقیت حذف شد.</div>";
    } else {
        $message = "<div id='div1' class='error'>خطا در حذف کاربر: " . $conn->error . "</div>";
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
    <link rel="stylesheet" href="../styles/user_delete.css">
    <script src="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/ionicons.js"></script>
    <link rel="icon" type="image/png" href="../images/logos/hat.png">
    <title>حذف کاربر</title>
</head>

<body>
    <div id="boody">
        <div id="Sction">
            <section id="sec">
                <h1>حذف کاربر</h1>
                <h3>انتخاب ایمیل: </h3>
                <form id="deleteForm" method="post" action="delete.php">
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
                    <button type="submit">حذف</button>
                </form>
                <a href="dashboard.php">بازگشت به داشبورد</a>
            </section>
        </div>
        <?php
        echo $message;
        ?>
        <script src="../js/delete_validation.js"></script>
    </div>
</body>

</html>

<?php
$conn->close();
?>
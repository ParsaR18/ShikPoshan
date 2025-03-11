<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/registerphp.css">
    <script src="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/ionicons.js"></script>
    <link rel="icon" type="image/png" href="../images/logos/hat.png">
    <title>ثبت نام</title>
</head>

<body>
    <section id="sec">
        <form id="form" action="../login/register.html">
            <h1 id="h1">ثبت نام</h1>
            <?php
            if (
                isset($_POST['email']) &&
                isset($_POST['password']) &&
                isset($_POST['repeatpassword'])
            ) {
                $username = $_POST["email"];
                $password = $_POST["password"];
                $repeatpassword = $_POST["repeatpassword"];

                if ($password !== $repeatpassword) {
                    echo "<div class='error'>رمز عبور و تکرار آن با هم مطابقت ندارند.</div>";
                } else {

                    $host = "localhost";
                    $db_username = "php";
                    $db_password = "Z4a)CGXs)]i.VJFw";
                    $database = "project";

                    $conn = new mysqli($host, $db_username, $db_password, $database);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "INSERT INTO logininfo (username, password) VALUES ('$username', '$password')";

                    if ($conn->query($sql) === TRUE) {
                        echo "<div id='div1' class='success'>ثبت نام با موفقیت انجام شد.</div>";
                    } else {
                        echo "<div id='div1' class='error'>خطا در اجرای کوئری: " . $conn->error . "</div>";
                    }

                    $conn->close();
                }
            } else {
                echo "<div class='error'>لطفاً تمامی فیلدها را پر کنید.</div>";
            }
            ?>
            <button>بازگشت</button>
        </form>
    </section>
</body>

</html>
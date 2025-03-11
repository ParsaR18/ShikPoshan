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

<body>
    <section id="sec">
        <form action="../login/login.html">
            <h1 id="h1">اطلاعات ورودی</h1>
            <?php
            if (isset($_POST['email']) && isset($_POST['password'])) {
                $email = $_POST["email"];
                $password = $_POST["password"];

                $host = "localhost";
                $db_username = "root";
                $db_password = "";
                $database = "project";

                $conn = new mysqli($host, $db_username, $db_password, $database);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM logininfo WHERE username = '$email' AND password = '$password'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    session_start();
                    $_SESSION['email'] = $email;
                    header("Location: dashboard.php");
                    exit();
                } else {
                    echo "<div id='div1' class='error'>نام کاربری یا رمز عبور اشتباه است.</div>";
                }

                $conn->close();
            }
            ?>
            <button>بازگشت</button>
        </form>

    </section>
</body>

</html>
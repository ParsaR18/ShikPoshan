<!DOCTYPE html>
<html dir="rtl" lang="Fa-IR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/uploaded.css">
    <title>ارسالی</title>
</head>

<body>
    <section id="sec">
        <form>
            <div class="container">
                <h1 id="h1">ارسالی</h1>

                <?php
                define('MAX_FILE_SIZE', 500000);
                $allowed_file_types = ['jpg', 'png', 'jpeg', 'gif'];

                $target_dir = "image/";
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                try {
                    if (isset($_POST["submit"])) {
                        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                        if ($check === false) {
                            echo "<div class='messageerror'>پرونده تصویر نیست.</div>";
                            $uploadOk = 0;
                        }
                    }

                    if (file_exists($target_file)) {
                        echo "<div class='messageerror'>با عرض پوزش، پرونده از قبل وجود دارد.</div>";
                        $uploadOk = 0;
                    }

                    if ($_FILES["fileToUpload"]["size"] > MAX_FILE_SIZE) {
                        echo "<div class='messageerror'>با عرض پوزش، پرونده شما بسیار بزرگ است.</div>";
                        $uploadOk = 0;
                    }

                    if (!in_array($imageFileType, $allowed_file_types)) {
                        echo "<div class='messageerror'>با عرض پوزش، فقط پرونده‌های JPG، JPEG، PNG و GIF مجاز هستند.</div>";
                        $uploadOk = 0;
                    }

                    if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        echo "<div class='messageerror'>با عرض پوزش، خطایی در بارگذاری پرونده شما رخ داد.</div>";
                    } else {
                        echo "<div class='messagesuccess'>پرونده " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " بارگذاری شده است. <a href='display.php?image=" . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . "'>مشاهده تصویر</a></div>";
                    }
                } catch (Exception $e) {
                    echo "<div class='messageerror'>Caught exception: " . $e->getMessage() . "</div>";
                }
                ?>
            </div>
            <button type="button" onclick="window.location.href='../upload/main.php';">بازگشت</button>
        </form>
    </section>
</body>

</html>
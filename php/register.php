<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config.php';

$registration_message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST['email']) &&
        isset($_POST['password']) &&
        isset($_POST['repeatpassword']) &&
        isset($_POST['tos_agree']) && $_POST['tos_agree'] === 'yes'
    ) {
        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        $repeatpassword = $_POST["repeatpassword"];

        if (empty($email) || empty($password) || empty($repeatpassword)) {
            $registration_message = "لطفاً تمامی فیلدها را پر کنید.";
            $message_type = 'error';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $registration_message = "فرمت ایمیل نامعتبر است.";
            $message_type = 'error';
        } elseif (strlen($password) < 8) {
            $registration_message = "رمز عبور باید حداقل 8 کاراکتر باشد.";
            $message_type = 'error';
        } elseif ($password !== $repeatpassword) {
            $registration_message = "رمز عبور و تکرار آن با هم مطابقت ندارند.";
            $message_type = 'error';
        } else {
            $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

            if ($conn->connect_error) {
                error_log("Database connection failed: " . $conn->connect_error);
                $registration_message = "خطایی در سیستم رخ داده است. لطفاً بعداً تلاش کنید.";
                $message_type = 'error';
            } else {
                $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
                if ($stmt_check) {
                    $stmt_check->bind_param("s", $email);
                    $stmt_check->execute();
                    $stmt_check->store_result();

                    if ($stmt_check->num_rows > 0) {
                        $registration_message = "این ایمیل قبلاً ثبت نام شده است.";
                        $message_type = 'error';
                    } else {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $stmt_insert = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
                        if ($stmt_insert) {
                            $stmt_insert->bind_param("ss", $email, $hashed_password);
                            if ($stmt_insert->execute()) {
                                $registration_message = "ثبت نام با موفقیت انجام شد. اکنون می‌توانید وارد شوید.";
                                $message_type = 'success';
                            } else {
                                error_log("Error executing user insert: " . $stmt_insert->error);
                                $registration_message = "خطا در ثبت نام. لطفاً دوباره تلاش کنید.";
                                $message_type = 'error';
                            }
                            $stmt_insert->close();
                        } else {
                            error_log("Error preparing user insert statement: " . $conn->error);
                            $registration_message = "خطایی در سیستم رخ داده است (کد خطا: R102).";
                            $message_type = 'error';
                        }
                    }
                    $stmt_check->close();
                } else {
                    error_log("Error preparing email check statement: " . $conn->error);
                    $registration_message = "خطایی در سیستم رخ داده است (کد خطا: R101).";
                    $message_type = 'error';
                }
                $conn->close();
            }
        }
    } elseif (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['repeatpassword']) && (!isset($_POST['tos_agree']) || $_POST['tos_agree'] !== 'yes')) {
        $registration_message = "برای ثبت نام باید با قوانین و مقررات موافقت کنید.";
        $message_type = 'error';
    } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $registration_message = "لطفاً تمامی فیلدهای الزامی را پر کنید و با قوانین موافقت نمایید.";
        $message_type = 'error';
    }
}
?>
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

<body class="page-register">
    <section id="sec">
        <form id="registerForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h1 id="h1">ثبت نام</h1>

            <?php
            if (!empty($registration_message)) {
                $message_div_class = ($message_type === 'success') ? 'success' : 'error';
                echo "<div class='message " . $message_div_class . "'>" . htmlspecialchars($registration_message) . "</div>";
            }
            ?>

            <div>
                <label for="email">ایمیل:</label>
                <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>
            <div>
                <label for="password">رمز عبور:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <label for="repeatpassword">تکرار رمز عبور:</label>
                <input type="password" id="repeatpassword" name="repeatpassword" required>
            </div>
            <div class="tos-agreement">
                <input type="checkbox" id="tos_agree" name="tos_agree" value="yes">
                <label for="tos_agree">من <a href="#tosModal" class="tos-modal-trigger">قوانین و مقررات</a> را خوانده و با آن موافقم.</label>
            </div>

            <button type="submit">ثبت نام</button>
            <br>
            <button type="button" onclick="window.location.href='../index.php';">بازگشت به صفحه اصلی</button>
            <p class="center-text">قبلاً حساب کاربری ساخته‌اید؟ <a href="login.php">وارد شوید</a></p>
        </form>
    </section>

    <div id="tosModal" class="modal-overlay">
        <div class="modal-content">
            <a href="#" class="modal-close">&times;</a>
            <h2>قوانین و مقررات استفاده از وب‌سایت شیک پوشان</h2>
            <section>
                <h3>مقدمه</h3>
                <p>به شیک پوشان خوش آمدید! استفاده شما از این وب‌سایت به منزله پذیرش کامل تمامی شرایط و ضوابط مندرج در این صفحه می‌باشد. اگر با بخشی از این قوانین موافق نیستید، لطفاً از وب‌سایت استفاده نکنید.</p>
            </section>
            <section>
                <h3>حساب کاربری</h3>
                <p>برای استفاده از برخی امکانات وب‌سایت، ممکن است نیاز به ایجاد حساب کاربری داشته باشید. شما مسئول حفظ محرمانگی اطلاعات حساب کاربری خود، از جمله رمز عبور، هستید. تمامی فعالیت‌هایی که تحت حساب کاربری شما انجام می‌شود به عهده شما خواهد بود.</p>
                <p>شما موافقت می‌کنید که اطلاعات دقیق و به‌روزی را هنگام ثبت‌نام ارائه دهید.</p>
            </section>
            <section>
                <h3>حریم خصوصی</h3>
                <p>سیاست حفظ حریم خصوصی ما، که بخشی از این قوانین و مقررات است، نحوه جمع‌آوری، استفاده و محافظت از اطلاعات شخصی شما را تشریح می‌کند. لطفاً صفحه سیاست حفظ حریم خصوصی ما را (در صورت وجود لینک جداگانه یا در همین بخش) مطالعه فرمایید.</p>
            </section>
            <section>
                <h3>مالکیت معنوی</h3>
                <p>تمامی محتوای موجود در این وب‌سایت، از جمله متون، گرافیک‌ها، لوگوها، تصاویر، کلیپ‌های صوتی و تصویری، گردآوری داده‌ها و نرم‌افزارها، متعلق به شیک پوشان یا تامین‌کنندگان محتوای آن بوده و تحت حمایت قوانین کپی‌رایت جمهوری اسلامی ایران و بین‌المللی قرار دارد.</p>
            </section>
            <section>
                <h3>محدودیت‌های استفاده</h3>
                <p>شما مجاز به استفاده از این وب‌سایت تنها برای اهداف قانونی و به شیوه‌ای هستید که حقوق دیگران را نقض نکند یا استفاده و بهره‌مندی دیگران از وب‌سایت را محدود یا مختل نسازد.</p>
            </section>
            <section>
                <h3>تغییرات در قوانین و مقررات</h3>
                <p>شیک پوشان این حق را برای خود محفوظ می‌دارد که در هر زمان، این قوانین و مقررات را تغییر دهد. نسخه به‌روز شده قوانین در همین صفحه منتشر خواهد شد و ادامه استفاده شما از وب‌سایت پس از اعمال تغییرات، به منزله پذیرش آن‌ها خواهد بود.</p>
            </section>
            <div class="modal-actions">
                <a href="#" class="modal-close-button">متوجه شدم و می‌بندم</a>
            </div>
        </div>
    </div>

    <script src="../js/register.js"></script>
</body>

</html>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$messages = [];

if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';

    if (!defined('DB_HOST') || !defined('DB_USERNAME') || !defined('DB_PASSWORD') || !defined('DB_NAME')) {
        $messages[] = ['type' => 'error', 'text' => 'خطا: ثابت‌های پیکربندی پایگاه داده (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME) در فایل config.php تعریف نشده‌اند.'];
    } else {
        $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD);

        if ($conn->connect_error) {
            $messages[] = ['type' => 'error', 'text' => 'اتصال به سرور MySQL ناموفق بود: ' . $conn->connect_error];
        } else {
            $sqlCreateDB = "CREATE DATABASE IF NOT EXISTS `" . $conn->real_escape_string(DB_NAME) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
            if ($conn->query($sqlCreateDB) === TRUE) {
                $messages[] = ['type' => 'success', 'text' => "پایگاه داده '" . htmlspecialchars(DB_NAME) . "' با موفقیت ایجاد شد یا از قبل موجود بود."];
            } else {
                $messages[] = ['type' => 'error', 'text' => "خطا در ایجاد پایگاه داده '" . htmlspecialchars(DB_NAME) . "': " . $conn->error];
            }

            $conn->select_db(DB_NAME);
            if ($conn->error) {
                $messages[] = ['type' => 'error', 'text' => "خطا در انتخاب پایگاه داده '" . htmlspecialchars(DB_NAME) . "': " . $conn->error];
            } else {
                $sqlUsersTable = "CREATE TABLE IF NOT EXISTS `users` (
                  `id` INT AUTO_INCREMENT PRIMARY KEY,
                  `email` VARCHAR(191) NOT NULL UNIQUE,
                  `password` VARCHAR(255) NOT NULL,
                  `username` VARCHAR(50) DEFAULT NULL,
                  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

                if ($conn->query($sqlUsersTable) === TRUE) {
                    $messages[] = ['type' => 'success', 'text' => "جدول 'users' با موفقیت ایجاد شد یا از قبل موجود بود."];
                } else {
                    $messages[] = ['type' => 'error', 'text' => "خطا در ایجاد جدول 'users': " . $conn->error];
                }

                $sqlPasswordResetsTable = "CREATE TABLE IF NOT EXISTS `password_resets` (
                  `email` VARCHAR(191) NOT NULL,
                  `token` VARCHAR(64) NOT NULL PRIMARY KEY,
                  `expires_at` DATETIME NOT NULL,
                  KEY `idx_password_resets_email` (`email`),
                  KEY `idx_password_resets_expires_at` (`expires_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

                if ($conn->query($sqlPasswordResetsTable) === TRUE) {
                    $messages[] = ['type' => 'success', 'text' => "جدول 'password_resets' با موفقیت ایجاد شد یا از قبل موجود بود."];
                } else {
                    $messages[] = ['type' => 'error', 'text' => "خطا در ایجاد جدول 'password_resets': " . $conn->error];
                }

                $checkFKExists = $conn->query("
                    SELECT CONSTRAINT_NAME 
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = '" . $conn->real_escape_string(DB_NAME) . "' 
                    AND TABLE_NAME = 'password_resets' 
                    AND CONSTRAINT_NAME = 'fk_password_resets_email_users'
                ");

                if ($checkFKExists && $checkFKExists->num_rows == 0) {
                    $sqlAlterPasswordResetsAddFK = "ALTER TABLE `password_resets` 
                        ADD CONSTRAINT `fk_password_resets_email_users` 
                        FOREIGN KEY (`email`) REFERENCES `users`(`email`) 
                        ON DELETE CASCADE ON UPDATE CASCADE;";
                    if ($conn->query($sqlAlterPasswordResetsAddFK) === TRUE) {
                        $messages[] = ['type' => 'success', 'text' => "کلید خارجی به جدول 'password_resets' با موفقیت اضافه شد."];
                    } else {
                        $messages[] = ['type' => 'error', 'text' => "خطا در اضافه کردن کلید خارجی به 'password_resets': " . $conn->error];
                    }
                } elseif ($checkFKExists && $checkFKExists->num_rows > 0) {
                    $messages[] = ['type' => 'info', 'text' => "کلید خارجی در جدول 'password_resets' از قبل موجود است."];
                } else if ($checkFKExists === false) {
                    $messages[] = ['type' => 'error', 'text' => "خطا در بررسی وجود کلید خارجی: " . $conn->error];
                }
            }
            $conn->close();
        }
    }
} else {
    $messages[] = ['type' => 'error', 'text' => 'خطا: فایل config.php یافت نشد. لطفاً ابتدا فایل config.php.example را به config.php کپی کرده و اطلاعات پایگاه داده خود را در آن وارد کنید.'];
}
?>
<!DOCTYPE html>
<html lang="fa-IR" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>راه‌اندازی پایگاه داده شیک پوشان</title>
    <link rel="icon" type="image/png" href="./images/logos/hat.png">
    <script src="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/ionicons.js"></script>
    <style>
        :root {
            --font-primary: Vazir, Arial, sans-serif;
            --text-light: #fff;
            --glass-bg: rgba(0, 0, 0, 0.35);
            --glass-border: rgba(255, 255, 255, 0.25);
            --glass-blur: 15px;
            --border-radius: 15px;
            --bold-font-weight: 700;
            --link-color: #bfe8ff;

            --message-success-bg: rgba(40, 167, 69, 0.25);
            --message-success-text: #e6ffe6;
            --message-success-border: rgba(40, 167, 69, 0.5);
            --message-error-bg: rgba(220, 53, 69, 0.25);
            --message-error-text: #ffe6e6;
            --message-error-border: rgba(220, 53, 69, 0.5);
            --message-info-bg: rgba(23, 162, 184, 0.25);
            --message-info-text: #e6f7ff;
            --message-info-border: rgba(23, 162, 184, 0.5);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-primary);
            color: var(--text-light);
            margin: 0;
            direction: rtl;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-image: url(./images/loginbg.png);
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            padding: 20px;
            font-weight: var(--bold-font-weight);
        }

        .glass-panel {
            width: 100%;
            max-width: 700px;
            margin: 2rem auto;
            padding: 2.5rem;
            background-color: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius);
            backdrop-filter: blur(var(--glass-blur));
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .glass-panel h1 {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            text-align: center;
            border-bottom: 1px solid var(--glass-border);
            padding-bottom: 1rem;
        }

        .messages-list {
            list-style: none;
            padding: 0;
        }

        .message {
            padding: 0.8rem 1rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 10px;
            text-align: right;
            font-size: 0.95rem;
        }

        .message.success {
            background-color: var(--message-success-bg);
            color: var(--message-success-text);
            border-color: var(--message-success-border);
        }

        .message.error {
            background-color: var(--message-error-bg);
            color: var(--message-error-text);
            border-color: var(--message-error-border);
        }

        .message.info {
            background-color: var(--message-info-bg);
            color: var(--message-info-text);
            border-color: var(--message-info-border);
        }

        .actions {
            margin-top: 2rem;
            text-align: center;
        }

        .actions a {
            color: var(--link-color);
            text-decoration: none;
            font-size: 1rem;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--glass-border);
            transition: background-color 0.3s ease;
            display: inline-flex;
            align-items: center;
        }

        .actions a ion-icon {
            margin-left: 8px;
            font-size: 1.2em;
        }

        .actions a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body>
    <main class="glass-panel">
        <h1>نتیجه راه‌اندازی پایگاه داده</h1>
        <?php if (!empty($messages)): ?>
            <ul class="messages-list">
                <?php foreach ($messages as $msg_item): ?>
                    <li class="message <?php echo htmlspecialchars($msg_item['type']); ?>">
                        <?php echo htmlspecialchars($msg_item['text']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="message info">عملیاتی برای نمایش پیام انجام نشد.</p>
        <?php endif; ?>
        <div class="actions">
            <a href="index.php"><ion-icon name="home-outline"></ion-icon> بازگشت به صفحه اصلی</a>
        </div>
    </main>
</body>

</html>
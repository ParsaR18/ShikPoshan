<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$delete_message = '';
$delete_message_type = '';
if (isset($_SESSION['delete_message'])) {
    $delete_message = $_SESSION['delete_message'];
    $delete_message_type = $_SESSION['delete_status'] ?? 'info';
    unset($_SESSION['delete_message']);
    unset($_SESSION['delete_status']);
}
?>
<!DOCTYPE html>
<html lang="fa-IR" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/uploaded.css">
    <link rel="icon" type="image/png" href="../images/logos/hat.png">
    <script src="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/ionicons.js"></script>
    <title>فایل‌های آپلود شده</title>
</head>

<body class="page-uploaded">
    <header class="page-header-transparent">
        <div class="logo-title">
            <img src="../images/logos/hat.png" alt="لوگو شیک پوشان" class="logo">
            <h1>شیک پوشان</h1>
        </div>
        <nav>
            <a href="main.php"><ion-icon name="arrow-back-outline"></ion-icon> بازگشت به آپلود</a>
            <a href="../index.php"><ion-icon name="home-outline"></ion-icon> صفحه اصلی</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="../php/dashboard.php"><ion-icon name="speedometer-outline"></ion-icon> داشبورد</a>
            <?php endif; ?>
        </nav>
    </header>

    <main class="uploaded-glass-panel">
        <h1 id="page-title">گالری تصاویر آپلود شده</h1>

        <?php
        if (!empty($delete_message)) {
            $message_div_class = 'form-message ';
            if ($delete_message_type === 'success') $message_div_class .= 'success';
            elseif ($delete_message_type === 'error') $message_div_class .= 'error';
            else $message_div_class .= 'info';
            echo "<div class='" . $message_div_class . "'>" . htmlspecialchars($delete_message) . "</div>";
        }
        ?>

        <div class="image-list">
            <?php
            $upload_dir = "uploads/";
            $image_found = false;
            $counter = 0;
            if (is_dir($upload_dir)) {
                $files = glob($upload_dir . "*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}", GLOB_BRACE);
                if ($files && count($files) > 0) {
                    array_multisort(array_map('filemtime', $files), SORT_DESC, $files);

                    foreach ($files as $filePath) {
                        $counter++;
                        $file = basename($filePath);
                        $image_found = true;
                        echo "<div class='image-entry'>";
                        echo "  <form action='delete_image.php' method='post' class='delete-form' onsubmit='return confirm(\"آیا از حذف تصویر " . htmlspecialchars($file) . " اطمینان دارید؟ این عمل غیرقابل بازگشت است.\");'>";
                        echo "      <input type='hidden' name='filename_to_delete' value='" . htmlspecialchars($file) . "'>";
                        echo "      <button type='submit' class='delete-button'>";
                        echo "          <ion-icon name='trash-outline'></ion-icon> حذف";
                        echo "      </button>";
                        echo "  </form>";
                        echo "  <div class='image-info-ltr'>";
                        echo "      <span class='image-number'>" . $counter . ".</span>";
                        echo "      <a href='" . htmlspecialchars($filePath) . "' target='_blank' class='thumbnail-link' title='" . htmlspecialchars($file) . "'>";
                        echo "          <img src='" . htmlspecialchars($filePath) . "' alt='" . htmlspecialchars($file) . "' class='thumbnail'>";
                        echo "      </a>";
                        echo "      <span class='image-filename'>" . htmlspecialchars($file) . "</span>";
                        echo "  </div>";
                        echo "</div>";
                    }
                }
            }
            if (!$image_found) {
                echo "<p class='no-images-message'>هیچ تصویری برای نمایش یافت نشد یا پوشه آپلود خالی است.</p>";
            }
            ?>
        </div>
    </main>

    <footer class="page-footer-transparent">
        <p>&copy; <?php echo date("Y"); ?> شیک پوشان. تمام حقوق محفوظ است.</p>
    </footer>
</body>

</html>
<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$upload_message = '';
$message_type = '';
if (isset($_SESSION['upload_message'])) {
  $upload_message = $_SESSION['upload_message'];
  $message_type = $_SESSION['upload_status'] ?? 'info';
  unset($_SESSION['upload_message']);
  unset($_SESSION['upload_status']);
}
?>
<!DOCTYPE html>
<html lang="fa-IR" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/upload.css">
  <link rel="icon" type="image/png" href="../images/logos/hat.png">
  <script src="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/ionicons.js"></script>
  <title>آپلود فایل</title>
</head>

<body class="page-upload">
  <header class="page-header-transparent">
    <div class="logo-title">
      <img src="../images/logos/hat.png" alt="لوگو شیک پوشان" class="logo">
      <h1>شیک پوشان</h1>
    </div>
    <nav>
      <a href="../index.php"><ion-icon name="home-outline"></ion-icon> صفحه اصلی</a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="../php/dashboard.php"><ion-icon name="speedometer-outline"></ion-icon> داشبورد</a>
        <a href="../php/logout.php"><ion-icon name="log-out-outline"></ion-icon> خروج</a>
      <?php else: ?>
        <a href="../php/login.php"><ion-icon name="log-in-outline"></ion-icon> ورود</a>
      <?php endif; ?>
    </nav>
  </header>

  <main class="upload-glass-panel">
    <h1 id="form-title">آپلود تصویر محصول</h1>

    <?php
    if (!empty($upload_message)) {
      $message_div_class = 'form-message ';
      if ($message_type === 'success') $message_div_class .= 'success';
      elseif ($message_type === 'error') $message_div_class .= 'error';
      else $message_div_class .= 'info';
      echo "<div class='" . $message_div_class . "'>" . htmlspecialchars($upload_message) . "</div>";
    }
    ?>

    <form action="upload.php" method="post" enctype="multipart/form-data" id="uploadForm">
      <div class="form-group">
        <label for="fileToUpload">فایل مورد نظر را انتخاب کنید:</label>
        <input type="file" name="fileToUpload" id="fileToUpload" required>
        <small>فقط فایل‌های JPG, JPEG, PNG, GIF تا حجم 5 مگابایت مجاز هستند.</small>
      </div>
      <div class="form-group">
        <label for="productName">نام محصول (اختیاری):</label>
        <input type="text" name="productName" id="productName" placeholder="مثال: پیراهن تابستانه">
      </div>
      <button type="submit" value="Upload Image" name="submit" class="form-button">
        <ion-icon name="cloud-upload-outline"></ion-icon> آپلود تصویر
      </button>
    </form>
    <div class="actions-link">
      <a href="display.php" class="view-uploads-link">
        <ion-icon name="eye-outline"></ion-icon> مشاهده فایل‌های آپلود شده
      </a>
    </div>
  </main>

  <footer class="page-footer-transparent">
    <p>&copy; <?php echo date("Y"); ?> شیک پوشان. تمام حقوق محفوظ است.</p>
  </footer>
  <script>
    document.getElementById('uploadForm')?.addEventListener('submit', function(e) {
      const fileInput = document.getElementById('fileToUpload');
      const file = fileInput.files[0];
      const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
      let errorMsg = '';

      if (!file) {
        errorMsg = 'لطفا یک فایل را برای آپلود انتخاب کنید.';
      } else if (!allowedTypes.includes(file.type)) {
        errorMsg = 'نوع فایل انتخاب شده مجاز نیست. فقط JPG, PNG, GIF.';
      } else if (file.size > 5 * 1024 * 1024) {
        errorMsg = 'حجم فایل نباید بیشتر از 5 مگابایت باشد.';
      }

      const existingError = document.querySelector('.form-message.client-error');
      if (existingError) existingError.remove();

      if (errorMsg) {
        e.preventDefault();
        const errorDiv = document.createElement('div');
        errorDiv.className = 'form-message error client-error';
        errorDiv.textContent = errorMsg;
        this.parentNode.insertBefore(errorDiv, this);
        fileInput.value = '';
      }
    });
  </script>
</body>

</html>
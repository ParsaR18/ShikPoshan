<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$upload_dir = "uploads/";
$message = "";
$uploadOk = 0;
$uploaded_filename = "";


if (!is_dir($upload_dir)) {
    if (!mkdir($upload_dir, 0755, true)) {
        $message = "خطا: امکان ایجاد پوشه آپلود وجود ندارد.";
        $_SESSION['upload_message'] = $message;
        $_SESSION['upload_status'] = 'error';
        header("Location: main.php");
        exit();
    }
}

if (isset($_POST["submit"]) && isset($_FILES["fileToUpload"])) {
    if ($_FILES["fileToUpload"]["error"] === UPLOAD_ERR_OK) {
        $original_filename = basename($_FILES["fileToUpload"]["name"]);
        $file_tmp_name = $_FILES["fileToUpload"]["tmp_name"];
        $file_size = $_FILES["fileToUpload"]["size"];
        $file_type = mime_content_type($file_tmp_name);
        $file_extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $max_file_size = 5 * 1024 * 1024;

        if (!in_array($file_type, $allowed_mime_types) || !in_array($file_extension, $allowed_extensions)) {
            $message = "فایل انتخاب شده تصویر نیست یا فرمت آن مجاز نمی‌باشد (فقط JPG, PNG, GIF).";
        } elseif ($file_size > $max_file_size) {
            $message = "متاسفانه حجم فایل شما بیش از حد مجاز است (حداکثر 5 مگابایت).";
        } else {
            $productName = isset($_POST['productName']) ? trim($_POST['productName']) : '';
            $safe_product_name_part = '';
            if (!empty($productName)) {
                $safe_product_name_part = preg_replace("/[^a-zA-Z0-9_-]/", "", str_replace(" ", "_", $productName));
                $safe_product_name_part = substr($safe_product_name_part, 0, 50);
                $safe_product_name_part .= '_';
            }

            $unique_id = uniqid('', true);
            $uploaded_filename = $safe_product_name_part . md5($original_filename . $unique_id) . '.' . $file_extension;
            $target_file_path = $upload_dir . $uploaded_filename;

            if (move_uploaded_file($file_tmp_name, $target_file_path)) {
                $message = "فایل " . htmlspecialchars($original_filename) . " (با نام " . htmlspecialchars($uploaded_filename) . ") با موفقیت آپلود شد.";
                $uploadOk = 1;
            } else {
                $message = "متاسفانه هنگام آپلود فایل شما خطایی رخ داد. بررسی کنید که پوشه آپلود قابل نوشتن باشد.";
            }
        }
    } elseif ($_FILES["fileToUpload"]["error"] === UPLOAD_ERR_NO_FILE) {
        $message = "فایلی برای آپلود انتخاب نشده است.";
    } elseif ($_FILES["fileToUpload"]["error"] === UPLOAD_ERR_INI_SIZE || $_FILES["fileToUpload"]["error"] === UPLOAD_ERR_FORM_SIZE) {
        $message = "حجم فایل بیش از حد مجاز است.";
    } else {
        $message = "خطای ناشناخته‌ای هنگام آپلود رخ داد. کد خطا: " . $_FILES["fileToUpload"]["error"];
    }
} else {
    $message = "درخواست آپلود نامعتبر است.";
}

$_SESSION['upload_message'] = $message;
$_SESSION['upload_status'] = ($uploadOk === 1) ? 'success' : 'error';
header("Location: main.php");
exit();

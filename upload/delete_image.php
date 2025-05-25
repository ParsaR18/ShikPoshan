<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['delete_message'] = "شما اجازه انجام این عملیات را ندارید.";
    $_SESSION['delete_status'] = 'error';
    header("Location: display.php");
    exit();
}

$upload_dir = "uploads/";
$message = '';
$status = 'error';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filename_to_delete'])) {

    $filename = basename($_POST['filename_to_delete']);

    if (empty($filename) || strpos($filename, '..') !== false || !preg_match('/^[a-zA-Z0-9_.\-]+\.(jpg|jpeg|png|gif)$/i', $filename)) {
        $message = "نام فایل نامعتبر است.";
    } else {
        $filepath = $upload_dir . $filename;
        if (file_exists($filepath)) {
            if (is_writable($filepath)) {
                if (unlink($filepath)) {
                    $message = "فایل '" . htmlspecialchars($filename) . "' با موفقیت حذف شد.";
                    $status = 'success';
                } else {
                    $message = "خطا در حذف فایل '" . htmlspecialchars($filename) . "'.";
                    error_log("Failed to delete file: " . $filepath);
                }
            } else {
                $message = "خطا: فایل '" . htmlspecialchars($filename) . "' قابل نوشتن (حذف) نیست. مجوزها را بررسی کنید.";
                error_log("File not writable (cannot delete): " . $filepath);
            }
        } else {
            $message = "خطا: فایل '" . htmlspecialchars($filename) . "' یافت نشد.";
        }
    }
} else {
    $message = "درخواست نامعتبر برای حذف فایل.";
}

$_SESSION['delete_message'] = $message;
$_SESSION['delete_status'] = $status;
header("Location: display.php");
exit();

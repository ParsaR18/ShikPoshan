<?php
$target_dir = "image/";
if (isset($_GET['image'])) {
    $image_name = $_GET['image'];
    $target_file = $target_dir . $image_name;

    if (file_exists($target_file)) {
        $image_info = getimagesize($target_file);
        header('Content-Type: ' . $image_info['mime']);
        readfile($target_file);
    } else {
        echo "پرونده مورد نظر یافت نشد.";
    }
} else {
    echo "پرونده‌ای برای نمایش وجود ندارد.";
}
?>
<!DOCTYPE html>
<html lang="fa-IR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/upload.css">
  <link rel="icon" type="image/png" href="../images/logos/hat.png">
  <title>ارسال عکس</title>
</head>

<body>
  <section id="sec">
    <h1 id="h1">ارسال عکس</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
      <input type="file" name="fileToUpload" id="fileToUpload">
      <input type="submit" value="بارگذاری فایل" id="upload" name="submit">
      <button type="button" onclick="window.location.href='../index.php';">بازگشت</button>
  </section>
  </form>
</body>

</html>
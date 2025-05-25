<!DOCTYPE html>
<html lang="fa-IR" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شیک پوشان</title>
    <link rel="icon" type="image/png" href="./images/logos/hat.png">
    <link rel="stylesheet" href="./styles/style.css">
    <script src="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/ionicons.js"></script>
</head>

<body>
    <header>
        <div class="navbar">
            <h1 id="title">شیک پوشان</h1>
            <div class="menu">
                <a href="./upload/main.php" aria-label="Upload"><ion-icon name="cloud-upload"></ion-icon></a>
                <a href="./php/login.php" aria-label="Login/Profile"><ion-icon name="person"></ion-icon></a>
                <a href="./html/products.html" aria-label="Cart"><ion-icon name="cart"></ion-icon></a>
            </div>
        </div>
    </header>

    <main>
        <section class="slideshow-container">
            <div class="mySlides fade">
                <img src="./images/ShikPoshan.png" alt="به مجموعه بزرگ شیک پوشان خوش آمدید">
                <div class="text">به مجموعه بزرگ شیک پوشان خوش آمدید!</div>
            </div>
            <div class="mySlides fade">
                <img src="./images/31off.png" alt="جشنواره 31% تخفیف ویژه">
                <div class="text">جشنواره 31% تخفیف ویژه ما را از دست ندهید!</div>
            </div>
            <div class="mySlides fade">
                <img src="./images/NimBoot.png" alt="کفش چرمی مدل آلفامن">
                <div class="text">با کفش چرمی مدل آلفامن، باکلاس بودن را امروز تجربه کنید!</div>
            </div>
            <a class="next" id="slideNextBtn">&#10094;</a>
            <a class="prev" id="slidePrevBtn">&#10095;</a>
        </section>

        <div style="text-align:center" class="slide-dots">
            <span class="dot" data-slideindex="1"></span>
            <span class="dot" data-slideindex="2"></span>
            <span class="dot" data-slideindex="3"></span>
        </div>

        <section class="grid-container">
            <article class="grid-item" id="wls">
                <h2>کفش چرم زنانه</h2>
                <a class="read-more-link" href="#">ادامه مطلب</a>
            </article>
            <article class="grid-item" id="nawb">
                <h2>کیف های دستی</h2>
                <a class="read-more-link" href="#">ادامه مطلب</a>
            </article>
            <article class="grid-item" id="ms">
                <h2>کت های مردانه</h2>
                <a class="read-more-link" href="./html/products.html">ادامه مطلب</a>
            </article>
        </section>

        <section class="model-feature">
            <div class="content" id="nowmood">
                <h2>مد های حال حاضر</h2>
                <a class="read-more-link" href="#">ادامه مطلب</a>
            </div>
        </section>

        <section class="clothing-feature">
            <div class="content" id="np">
                <h2>محصولات جدید</h2>
                <a class="read-more-link" href="./html/products.html">ادامه مطلب</a>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-column help">
            <h3 id="fonth3">فروشگاه شیک پوشان</h3>
            <ul>
                <li><ion-icon name="map-outline"></ion-icon> نشانی: <span class="footer-text"> یزد، صفائیه، چهارراه اطلسی</span></li>
                <li><ion-icon name="call-outline"></ion-icon> تلفن: <a href="tel:+989906677990" class="footer-link"> 09906677990</a></li>
                <li><ion-icon name="globe-outline"></ion-icon> سایت: <a href="https://ShikPoshan.Netlify.app" class="footer-link" target="_blank" rel="noopener noreferrer">shikposhan.netlify.app</a></li>
                <li><ion-icon name="mail-outline"></ion-icon> ایمیل: <a href="mailto:atarayeshgahproject@gmail.com" class="footer-link">atarayeshgahproject@gmail.com</a></li>
            </ul>
        </div>
        <div class="footer-column follow">
            <h3 id="fh3">ما را دنبال کنید!</h3>
            <ul>
                <li><a href="https://www.instagram.com/example" class="footer-link social-link" target="_blank" rel="noopener noreferrer"><ion-icon name="logo-instagram"></ion-icon> اینستاگرام </a></li>
                <li><a href="https://t.me/TryHardNigga" class="footer-link social-link" target="_blank" rel="noopener noreferrer"><ion-icon name="send-outline"></ion-icon> تلگرام </a></li>
                <li><a href="https://twitter.com/elonmusk" class="footer-link social-link" target="_blank" rel="noopener noreferrer"><ion-icon name="logo-twitter"></ion-icon> توییتر </a></li>
            </ul>
        </div>
    </footer>
    <div class="copyright-footer" dir="auto">
        &copy; <?php echo date("Y"); ?> By Parsa Rezaei. All Rights Reserved. :)
    </div>

    <script type="text/javascript" async src="https://tenor.com/embed.js"></script>
    <script src="./js/slide.js"></script>
</body>

</html>
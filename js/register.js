document.querySelector('form').addEventListener('submit', function (event) {

    var email = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    var repeatpassword = document.getElementById('repeatpassword').value;
    var agree = document.getElementById('tost').checked;

    if (email === '' || password === '' || repeatpassword === '') {
        alert('تمامی فیلدها باید پر شوند');
        event.preventDefault();
        return false;
    }

    if (email.length > 74) {
        alert('طول ایمیل نمی‌تواند بیشتر از 74 کاراکتر باشد');
        event.preventDefault();
        return false;
    }

    if (password.length > 16) {
        alert('طول رمز عبور نمی‌تواند بیشتر از 16 کاراکتر باشد');
        event.preventDefault();
        return false;
    }

    if (password !== repeatpassword) {
        alert('رمزهای عبور مطابقت ندارند');
        event.preventDefault();
        return false;
    }

    if (!agree) {
        alert('برای ادامه، باید با شرایط و ضوابط موافقت کنید');
        event.preventDefault();
        return false;
    }
});

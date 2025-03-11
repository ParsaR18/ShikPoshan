document.querySelector('form').addEventListener('submit', function (event) {
    var email = document.querySelector('input[type="email"]').value;
    var password = document.querySelector('input[type="password"]').value;

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

    if (email === '' || password === '') {
        alert('تمامی فیلدها باید پر شوند');
        event.preventDefault();
    }
});

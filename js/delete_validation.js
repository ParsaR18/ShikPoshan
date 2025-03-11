document.getElementById('deleteForm').addEventListener('submit', function(event) {
    var radios = document.getElementsByName('selected_email');
    var emailSelected = false;

    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            emailSelected = true;
            break;
        }
    }

    if (!emailSelected) {
        event.preventDefault();
        alert('لطفاً یک ایمیل را انتخاب کنید.');
    }
});
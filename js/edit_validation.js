document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('editForm').addEventListener('submit', function(event) {
        const selectedEmail = document.querySelector('input[name="selected_email"]:checked');
        const newPassword = document.getElementById('input').value;

        if (!selectedEmail) {
            alert('لطفاً یک کاربر را انتخاب کنید.');
            event.preventDefault();
            return false;
        }

        if (!newPassword) {
            alert('لطفاً رمز عبور جدید را وارد کنید.');
            event.preventDefault();
            return false;
        }
    });
});
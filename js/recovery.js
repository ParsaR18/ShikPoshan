document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("form");
    const emailInput = document.getElementById("email");

    form.addEventListener("submit", function(event) {
        if (emailInput.value.trim() === "") {
            event.preventDefault();
            alert("لطفاً ایمیل خود را وارد کنید.");
        }
    });
});

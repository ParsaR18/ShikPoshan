(function () {
  const loginForm = document.getElementById("loginForm");

  if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
      const emailInput = document.getElementById("email");
      const passwordInput = document.getElementById("password");
      let isValid = true;

      clearErrorMessages(loginForm);

      if (!emailInput.value.trim()) {
        displayErrorMessage(emailInput, "ایمیل نمی‌تواند خالی باشد.");
        isValid = false;
      } else if (!isValidEmail(emailInput.value.trim())) {
        displayErrorMessage(emailInput, "فرمت ایمیل نامعتبر است.");
        isValid = false;
      }

      if (!passwordInput.value) {
        displayErrorMessage(passwordInput, "رمز عبور نمی‌تواند خالی باشد.");
        isValid = false;
      }

      if (!isValid) {
        event.preventDefault();
      }
    });
  }

  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  function displayErrorMessage(inputElement, message) {
    const formGroup = inputElement.closest("div");
    if (formGroup) {
      const errorElement = document.createElement("p");
      errorElement.className = "form-input-error";
      errorElement.textContent = message;
      errorElement.style.color = "red";
      errorElement.style.fontSize = "0.8rem";
      errorElement.style.marginTop = "4px";
      formGroup.appendChild(errorElement);
      inputElement.classList.add("input-error-border");
    }
  }

  function clearErrorMessages(form) {
    form.querySelectorAll(".form-input-error").forEach((el) => el.remove());
    form
      .querySelectorAll(".input-error-border")
      .forEach((el) => el.classList.remove("input-error-border"));
  }
})();

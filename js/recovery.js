(function () {
  const requestRecoveryForm = document.getElementById("requestRecoveryForm");
  const resetPasswordForm = document.getElementById("resetPasswordForm");

  if (requestRecoveryForm) {
    requestRecoveryForm.addEventListener("submit", function (event) {
      const emailInput = document.getElementById("email");
      let isValid = true;
      clearErrorMessages(requestRecoveryForm);

      if (!emailInput.value.trim()) {
        displayErrorMessage(emailInput, "ایمیل نمی‌تواند خالی باشد.");
        isValid = false;
      } else if (!isValidEmail(emailInput.value.trim())) {
        displayErrorMessage(emailInput, "فرمت ایمیل نامعتبر است.");
        isValid = false;
      }
      if (!isValid) event.preventDefault();
    });
  }

  if (resetPasswordForm) {
    resetPasswordForm.addEventListener("submit", function (event) {
      const newPasswordInput = document.getElementById("new_password");
      const confirmNewPasswordInput = document.getElementById(
        "confirm_new_password"
      );
      let isValid = true;
      clearErrorMessages(resetPasswordForm);

      if (!newPasswordInput.value) {
        displayErrorMessage(
          newPasswordInput,
          "رمز عبور جدید نمی‌تواند خالی باشد."
        );
        isValid = false;
      } else if (newPasswordInput.value.length < 8) {
        displayErrorMessage(
          newPasswordInput,
          "رمز عبور جدید باید حداقل ۸ کاراکتر باشد."
        );
        isValid = false;
      }

      if (!confirmNewPasswordInput.value) {
        displayErrorMessage(
          confirmNewPasswordInput,
          "تکرار رمز عبور جدید نمی‌تواند خالی باشد."
        );
        isValid = false;
      } else if (
        newPasswordInput.value &&
        confirmNewPasswordInput.value !== newPasswordInput.value
      ) {
        displayErrorMessage(
          confirmNewPasswordInput,
          "رمز عبور جدید و تکرار آن با هم مطابقت ندارند."
        );
        isValid = false;
      }
      if (!isValid) event.preventDefault();
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

(function () {
  const registerForm = document.getElementById("registerForm");

  if (registerForm) {
    registerForm.addEventListener("submit", function (event) {
      const emailInput = document.getElementById("email");
      const passwordInput = document.getElementById("password");
      const repeatPasswordInput = document.getElementById("repeatpassword");
      const tosCheckbox = document.getElementById("tos_agree");
      let isValid = true;

      clearErrorMessages(registerForm);

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
      } else if (passwordInput.value.length < 8) {
        displayErrorMessage(
          passwordInput,
          "رمز عبور باید حداقل ۸ کاراکتر باشد."
        );
        isValid = false;
      }

      if (!repeatPasswordInput.value) {
        displayErrorMessage(
          repeatPasswordInput,
          "تکرار رمز عبور نمی‌تواند خالی باشد."
        );
        isValid = false;
      } else if (
        passwordInput.value &&
        repeatPasswordInput.value !== passwordInput.value
      ) {
        displayErrorMessage(
          repeatPasswordInput,
          "رمز عبور و تکرار آن با هم مطابقت ندارند."
        );
        isValid = false;
      }

      if (!tosCheckbox.checked) {
        displayErrorMessage(
          tosCheckbox,
          "شما باید با قوانین و مقررات موافقت کنید.",
          true
        );
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

  function displayErrorMessage(inputElement, message, isCheckbox = false) {
    const formGroup = isCheckbox
      ? inputElement.closest(".tos-agreement")
      : inputElement.closest("div");
    if (formGroup) {
      let errorElement = formGroup.querySelector(".form-input-error");
      if (!errorElement) {
        errorElement = document.createElement("p");
        errorElement.className = "form-input-error";
        errorElement.style.color = "orange";
        errorElement.style.fontSize = "0.8rem";
        errorElement.style.marginTop = "4px";
        if (isCheckbox) {
          formGroup.appendChild(errorElement);
        } else {
          formGroup.appendChild(errorElement);
        }
      }
      errorElement.textContent = message;
      if (!isCheckbox) {
        inputElement.classList.add("input-error-border");
      }
    }
  }

  function clearErrorMessages(form) {
    form.querySelectorAll(".form-input-error").forEach((el) => el.remove());
    form
      .querySelectorAll(".input-error-border")
      .forEach((el) => el.classList.remove("input-error-border"));
  }
})();

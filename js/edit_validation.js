(function () {
  const editForm = document.getElementById("editForm");

  if (editForm) {
    editForm.addEventListener("submit", function (event) {
      const newEmailInput = document.getElementById("email");
      const currentPasswordForEmail = document.getElementById(
        "current_password_for_email_change"
      );
      const newPasswordInput = document.getElementById("new_password");
      const confirmNewPasswordInput = document.getElementById(
        "confirm_new_password"
      );
      const currentPasswordForPassword = document.getElementById(
        "current_password_for_password_change"
      );
      let isValid = true;

      clearErrorMessages(editForm);

      if (newEmailInput.value.trim()) {
        if (!currentPasswordForEmail.value) {
          displayErrorMessage(
            currentPasswordForEmail,
            "برای تغییر ایمیل، رمز عبور فعلی الزامی است."
          );
          isValid = false;
        }
        if (!isValidEmail(newEmailInput.value.trim())) {
          displayErrorMessage(newEmailInput, "فرمت ایمیل جدید نامعتبر است.");
          isValid = false;
        }
      }

      if (newPasswordInput.value.trim()) {
        if (!currentPasswordForPassword.value) {
          displayErrorMessage(
            currentPasswordForPassword,
            "برای تغییر رمز عبور، رمز عبور فعلی الزامی است."
          );
          isValid = false;
        }
        if (newPasswordInput.value.length < 8) {
          displayErrorMessage(
            newPasswordInput,
            "رمز عبور جدید باید حداقل ۸ کاراکتر باشد."
          );
          isValid = false;
        }
        if (newPasswordInput.value !== confirmNewPasswordInput.value) {
          displayErrorMessage(
            confirmNewPasswordInput,
            "رمز عبور جدید و تکرار آن با هم مطابقت ندارند."
          );
          isValid = false;
        }
      } else if (
        confirmNewPasswordInput.value.trim() &&
        !newPasswordInput.value.trim()
      ) {
        displayErrorMessage(
          newPasswordInput,
          "ابتدا رمز عبور جدید را وارد کنید."
        );
        isValid = false;
      }

      if (
        newEmailInput.value.trim() &&
        !currentPasswordForEmail.value.trim() &&
        !newPasswordInput.value.trim() &&
        !currentPasswordForPassword.value.trim()
      ) {
        displayErrorMessage(
          currentPasswordForEmail,
          "برای تغییر ایمیل، رمز عبور فعلی الزامی است."
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

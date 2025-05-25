(function () {
  const confirmDeleteForm = document.getElementById("confirmDeleteForm");

  if (confirmDeleteForm) {
    confirmDeleteForm.addEventListener("submit", function (event) {
      const passwordInput = document.getElementById("password");
      let isValid = true;

      clearErrorMessages(confirmDeleteForm);

      if (!passwordInput.value) {
        displayErrorMessage(
          passwordInput,
          "رمز عبور برای تایید حذف الزامی است."
        );
        isValid = false;
      }

      if (!isValid) {
        event.preventDefault();
      }
    });
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

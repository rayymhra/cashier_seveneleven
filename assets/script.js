// show password
const passwordField = document.getElementById("password");
const showPasswordCheckbox = document.getElementById("show-password");

showPasswordCheckbox.addEventListener("change", function () {
  passwordField.type = this.checked ? "text" : "password";
});

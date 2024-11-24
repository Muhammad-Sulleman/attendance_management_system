document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("loginForm");
  const registerForm = document.getElementById("registerForm");
  const showLogin = document.getElementById("showLogin");
  const showRegister = document.getElementById("showRegister");

  showLogin.addEventListener("click", () => {
    loginForm.classList.add("active");
    registerForm.classList.remove("active");
  });

  showRegister.addEventListener("click", () => {
    registerForm.classList.add("active");
    loginForm.classList.remove("active");
  });
});

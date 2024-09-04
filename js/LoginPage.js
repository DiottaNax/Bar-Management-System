document.addEventListener("DOMContentLoaded", (event) => {
  event.preventDefault();
  const mainLogoContainer = document.querySelector(".logo-container");
  const formContainer = document.getElementById("form-container");
  const cardLogo = document.querySelector(".card-logo img");
  const loginResult = document.getElementById("login-result");

  // Block of code for the initial animation
  setTimeout(() => {
    mainLogoContainer.style.display = "none";
    formContainer.style.display = "block";
    cardLogo.style.display = "block";

    setTimeout(() => {
      formContainer.style.opacity = "1";
    }, 50);
  }, 3000);
  // End of code for the animation

  const loginForm = document.getElementById("login-form");

  loginForm.addEventListener("submit", (event) => {
    event.preventDefault();
    const loginRequest = new FormData(loginForm);

    // Try to login the user
    axios.post("./api/process_login.php", loginRequest).then((response) => {
      console.log(response.data.message);
      console.log(response.data["success"]);
      // If the login is successful, redirect to the home page
      if (response.data["success"]) {
        loginResult.style.color = 'green';
        setTimeout(() => (window.location.href = "index.php"), 500);
      } else {
        loginResult.style.color = 'red';
      }
      loginResult.innerText = response.data["message"];
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const nav = document.querySelector("nav");
  const optChoice = nav.getAttribute("opt-choice");

  if (optChoice) {
    const matchingElements = nav.querySelectorAll(
      `[opt="${optChoice}"] .nav-link`
    );
    matchingElements.forEach((element) => {
      element.classList.add("active");
    });
  }
});

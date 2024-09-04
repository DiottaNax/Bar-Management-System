document.addEventListener("DOMContentLoaded", function () {
  const toastLiveExample = document.getElementById("recipe-toast");
  const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);

  // Select all buttons with type="toast-trigger"
  const toastTriggers = document.querySelectorAll('a[type="toast-trigger"]');

  toastTriggers.forEach((trigger) => {
    trigger.addEventListener("click", function (event) {
      event.preventDefault();

      const prodId = this.getAttribute("prod-id");
        const prodName = this.getAttribute("prod-name");
        const toastBody = document.querySelector("#recipe-toast .toast-body");

      // Get recipe with a request to the appropriate api
      axios.get("./api/get_recipe.php?prodId=" + prodId).then((response) => {
        console.log(response.data);

          if (response.data['numIngredients'] > 0) {
            // Transform the recipe string into a list using the comma as separator
            const recipeString = response.data["recipe"];
            const recipeItems = recipeString.split(", ");

            // Create a HTML unordered list for the recipe
            const ul = document.createElement("ul");
            recipeItems.forEach((item) => {
              const li = document.createElement("li");
              li.textContent = item;
              ul.appendChild(li);
            });

            // Insert the list into the toast body
            toastBody.innerHTML = ""; // Clear the previous content
            toastBody.appendChild(ul);
          } else {
            toastBody.innerHTML = "No recipe available yet.";
          }
        

        // Update the toast title
        document.querySelector("#recipe-toast strong").textContent =
          "Recipe for " + prodName;
      });

      toastBootstrap.show();
    });
  });
});

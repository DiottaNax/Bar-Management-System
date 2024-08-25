document.addEventListener("DOMContentLoaded", function () {
  const toastLiveExample = document.getElementById("recipe-toast");
  const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);

  // Seleziona tutti i bottoni con type="toast-trigger"
  const toastTriggers = document.querySelectorAll('a[type="toast-trigger"]');

  toastTriggers.forEach((trigger) => {
    trigger.addEventListener("click", function (event) {
      event.preventDefault(); // Previene il comportamento predefinito del link

      const prodId = this.getAttribute("prod-id");
        const prodName = this.getAttribute("prod-name");
        const toastBody = document.querySelector("#recipe-toast .toast-body");

      // get recipe calling the appropriate api
      axios.get("./api/get_recipe.php?prodId=" + prodId).then((response) => {
        console.log(response.data);

          if (response.data['numIngredients'] > 0) {
            // Trasforma la stringa della ricetta in una lista
            const recipeString = response.data["recipe"];
            const recipeItems = recipeString.split(", ");

            // Crea una lista non ordinata
            const ul = document.createElement("ul");
            recipeItems.forEach((item) => {
              const li = document.createElement("li");
              li.textContent = item;
              ul.appendChild(li);
            });

            // Inserisci la lista nel toast body
            toastBody.innerHTML = ""; // Pulisce il contenuto precedente
            toastBody.appendChild(ul);
          } else {
            toastBody.innerHTML = "No recipe available yet."; // Pulisce il contenuto precedente
          }
        

        // Aggiorna il titolo del toast
        document.querySelector("#recipe-toast strong").textContent =
          "Recipe for " + prodName;
      });

      toastBootstrap.show();
    });
  });
});

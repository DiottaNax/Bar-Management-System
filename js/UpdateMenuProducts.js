document.addEventListener("DOMContentLoaded", () => {
  document
    .getElementById("saveNewProduct")
    .addEventListener("click", function () {
      const formData = new FormData(document.getElementById("addProductForm"));
      const fileInput = document.getElementById("newProductImage");
      const file = fileInput.files[0].name;
      if (file) {
        formData.set("imgFile", file);
      }

      const productData = Object.fromEntries(formData.entries());
      console.log("Sending: " + JSON.stringify(productData));

      // Try to add a new product to the database when the user clicks the submit button
      fetch("./api/update_menu_products.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(productData),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            location.reload(); // Reload the page to show the new product
          } else {
            alert("Failed to add product: " + data.message);
          }
        })
        .catch((error) => console.error("Error:", error));
    });

  document
    .getElementById("saveEditProduct")
    .addEventListener("click", function () {
      const formData = new FormData(document.getElementById("editProductForm"));
      const fileInput = document.getElementById("editProductImage");
      
      // If the user has selected a new image, set the file name to the form data
      if (fileInput.files[0]) {
        const file = fileInput.files[0].name;
        if (file) {
          formData.set("imgFile", file); // The file is supposed to exist in the "resources/img" folder
        }
      } else {
        // If the user has not selected a new image, delete the imgFile entry
        formData.delete("imgFile");
      }

      const productData = Object.fromEntries(formData.entries());
      const productId = this.getAttribute("data-product-id");

      // Try to update the product info in the database when the user clicks the submit button
      // Only the fields that are filled are sent to the database
      fetch("./api/update_menu_products.php", {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ prodId: productId, ...productData }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            location.reload(); // Reload the page to show the updated product
          } else {
            alert("Failed to update product: " + data.message);
          }
        })
        .catch((error) => console.error("Error:", error));
    });

  // Delete Product
  document.querySelectorAll(".delete-product").forEach((button) => {
    button.addEventListener("click", function () {
      if (confirm("Are you sure you want to delete this product?")) {
        const productId = this.getAttribute("data-product-id");

        // Try to delete the product from the database when the user clicks the delete button
        fetch("./api/update_menu_products.php?prodId=" + productId, {
          method: "DELETE",
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              location.reload(); // Reload the page to remove the deleted product
            } else {
              alert("Failed to delete product: " + data.message);
            }
          })
          .catch((error) => console.error("Error:", error));
      }
    });
  });
});

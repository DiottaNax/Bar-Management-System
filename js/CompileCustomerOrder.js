document.addEventListener("DOMContentLoaded", function () {

  // Function to render the order table
  function updateOrderTable() {
    let html = "";
    orderItems.forEach((item, index) => {
      html += `
                <tr>
                    <td>${item.prodId}</td>
                    <td>${item.name}</td>
                    <td>$${item.price}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm quantity-input" value="${
                          item.quantity
                        }" min="1" data-index="${index}">
                    </td>
                    <td>
                        <ul class="list-unstyled mb-0">
                            ${item.variations
                              .map(
                                (v) =>
                                  `<li>${v.name} (+$${v.additionalPrice.toFixed(
                                    2
                                  )})</li>`
                              )
                              .join("")}
                        </ul>
                        <button class="btn btn-sm btn-primary add-variation" data-index="${index}">Add Variations</button>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-danger remove-item" data-index="${index}">Remove</button>
                    </td>
                </tr>
            `;
    });
    $("#orderItems").html(html);

    console.log(orderItems);
  }

  // Show add product modal on button click
  $("#addProductBtn").click(function () {
    $("#productResults").empty();
    $("#productSearch").val("");
    $("#addProductModal").modal("show");
  });

  // Display search results in the modal
  $("#productSearch").on("input", function () {
    const search = $(this).val();
    if (search.length > 2) {
      // HTTP request to the appropriate API
      $.get("./api/search_menu_product.php", { q: search }, function (data) {
        let html = "";
        console.log(data);
        // For each product result in the data, create a new row in the table
        data.forEach((product) => {
          html += `<div class="product-item mt-2">
                            ${product.name} - $${product.price}
                            <button class="btn btn-sm btn-primary add-to-order" data-id="${product.prodId}" data-name="${product.name}" data-price="${product.price}">Add</button>
                        </div>`;
        });
        $("#productResults").html(html);
      });
    }
  });

  // Add product to order
  $(document).on("click", ".add-to-order", function () {
    console.log("ProdId: " + parseInt($(this).data("id")));
    // Create a new product object with the data from the button
    const product = {
      prodId: parseInt($(this).data("id")),
      name: $(this).data("name"),
      basePrice: parseFloat($(this).data("price")),
      price: parseFloat($(this).data("price")),
      quantity: 1,
      variations: [],
    };
    // Add the product to the order items array
    orderItems.push(product);
    // Update the order table and render it
    updateOrderTable();
    $("#addProductModal").modal("hide");
  });

  // Add Variation
  $(document).on("click", ".add-variation", function () {
    const index = $(this).data("index"); // Get the index of the product in the order items array
    $("#addVariationModal").data("item-index", index).modal("show");
  });

  // Display variation search results in the modal
  $("#variationSearch").on("input", function () {
    const search = $(this).val();
    if (search.length > 2) {
      // HTTP request to the appropriate API
      $.get("./api/search_variation.php", { q: search }, function (data) {
        let html = "";
        // For each variation result in the data, create a new row in the table to display the variation
        data.forEach((variation) => {
          html += `<div class="variation-item mt-2">
                    ${variation.additionalRequest}: +$${
            variation.additionalPrice ?? "0.00"
          }
                    <button class="btn btn-sm btn-primary add-variation-to-product" 
                        data-id="${variation.variationId}" 
                        data-name="${variation.additionalRequest}" 
                        data-price="${
                          variation.additionalPrice ?? 0
                        }">Add</button>
                </div>`;
        });
        $("#variationResults").html(html);
      });
    }
  });

  // Add variation to product
  $(document).on("click", ".add-variation-to-product", function () {
    const index = $("#addVariationModal").data("item-index"); // Get the index of the product in the order items array
    const variation = {
      variationId: $(this).data("id"),
      name: $(this).data("name"),
      additionalPrice: parseFloat($(this).data("price") || 0),
    };
    orderItems[index].variations.push(variation);
    updateItemPrice(index);
    updateOrderTable();
    $("#addVariationModal").modal("hide");
  });

  // Add variation price to product price
  function updateItemPrice(index) {
    let basePrice = parseFloat(orderItems[index].basePrice);
    orderItems[index].variations.forEach((variation) => {
      basePrice += variation.additionalPrice;
    });
    orderItems[index].price = basePrice.toFixed(2);
  }

  // Update Quantity
  $(document).on("change", ".quantity-input", function () {
    const index = $(this).data("index");
    orderItems[index].quantity = parseInt($(this).val());
  });

  // Remove Item
  $(document).on("click", ".remove-item", function () {
    const index = $(this).data("index");
    orderItems.splice(index, 1);
    updateOrderTable();
  });

  // Send Order
  $("#sendOrderBtn").click(function () {
    const orderData = orderItems.map((item) => ({
      prodId: item.prodId,
      quantity: item.quantity,
      variations: item.variations.map((v) => v.variationId),
    }));

    console.log("Trying to send order: " + orderData);

    // Send order details to the appropriate API
    $.ajax({
      url: "./api/new_customer_order.php",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({
        waiterId: waiterId,
        tableId: tableId,
        orderItems: orderData,
      }),
      success: (response) => {
        response = JSON.parse(response);
        if (response.success) {
          orderItems = [];
          updateOrderTable();
          window.location.href = "./home.php?opt=customer-orders";
        } else if (response.error) {
          alert("Failed to send order: " + response.error);
        } else {
          // Log unexpected response
          console.log("Unexpected response:", response);
          alert(
            "An unexpected response was received: " +
              (typeof response === "object"
                ? JSON.stringify(response)
                : response)
          );
        }
      },
      error: () => {
        let errorMessage = "An error occurred while sending the order";
        alert(errorMessage);
        console.log(errorMessage);
      },
    });
  });
});

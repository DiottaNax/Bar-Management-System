document.addEventListener("DOMContentLoaded", function () {
  function updateOrderTable() {
    let html = "";
    orderItems.forEach((item, index) => {
      html += `
            <tr>
                <td>${item.prodId}</td>
                <td>${item.name}</td>
                <td>$${item.price}</td>
                <td><input type="number" class="form-control quantity-input" value="${
                  item.quantity
                }" min="1" data-index="${index}"></td>
                <td>
                    <ul>
                        ${item.variations
                          .map(
                            (v) =>
                              `<li>${v.name} (+$${v.additionalPrice.toFixed(
                                2
                              )})</li>`
                          )
                          .join("")}
                    </ul>
                    <button class="btn btn-sm btn-secondary add-variation" data-index="${index}">Add Variations</button>
                </td>
                <td><button class="btn btn-sm btn-danger remove-item" data-index="${index}">Remove</button></td>
            </tr>
        `;
    });
    $("#orderItems").html(html);

    console.log(orderItems);
  }

  // Add Product
  $("#addProductBtn").click(function () {
    $("#addProductModal").modal("show");
  });

  $("#productSearch").on("input", function () {
    const search = $(this).val();
    if (search.length > 2) {
      $.get("./api/search_menu_product.php", { q: search }, function (data) {
        let html = "";
        console.log(data);
        data.forEach((product) => {
          html += `<div class="product-item">
                            ${product.name} - $${product.price}
                            <button class="btn btn-sm btn-primary add-to-order" data-id="${product.prodId}" data-name="${product.name}" data-price="${product.price}">Add</button>
                        </div>`;
        });
        $("#productResults").html(html);
      });
    }
  });

  $(document).on("click", ".add-to-order", function () {
    console.log("ProdId: " + parseInt($(this).data("id")));
    const product = {
      prodId: parseInt($(this).data("id")),
      name: $(this).data("name"),
      basePrice: parseFloat($(this).data("price")),
      price: parseFloat($(this).data("price")),
      quantity: 1,
      variations: [],
    };
    orderItems.push(product);
    updateOrderTable();
    $("#addProductModal").modal("hide");
  });

  // Add Variation
  $(document).on("click", ".add-variation", function () {
    const index = $(this).data("index");
    $("#addVariationModal").data("item-index", index).modal("show");
  });

  $("#variationSearch").on("input", function () {
    const search = $(this).val();
    if (search.length > 2) {
      $.get("./api/search_variation.php", { q: search }, function (data) {
        let html = "";
        data.forEach((variation) => {
          html += `<div class="variation-item">
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

  $(document).on("click", ".add-variation-to-product", function () {
    const index = $("#addVariationModal").data("item-index");
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
          // Gestisci la risposta inattesa
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

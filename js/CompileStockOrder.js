const suppliers = new Map();

// Function to update the supplier of an item in the order
function updateItemSupplier(event) {
  console.log("\nChanging supplier\n");
  const select = event.target;
  const prodIndex = parseInt(select.getAttribute("data-index"), 10);
  const selectedOption = select.options[select.selectedIndex];
  const newSupplierIndex = parseInt(
    selectedOption.getAttribute("data-supplier-index"),
    10
  );
  const suppliersForItem = suppliers.get(orderItems[prodIndex].prodId);
  orderItems[prodIndex].companyName =
    suppliersForItem[newSupplierIndex].companyName;
  orderItems[prodIndex].cost = suppliersForItem[newSupplierIndex].cost;
}

document.addEventListener("DOMContentLoaded", function () {
  
  // Function to update the total cost of the order
  function updateTotal() {
    console.log("Updating total");
    const total = orderItems
      .map((e) => e.cost * e.quantity)
      .reduce((accumulator, currentValue) => accumulator + currentValue, 0);
    $("#estimatedCost").text("Estimated Cost:  $" + total.toFixed(2));

    return total;
  }

  // Function to update and display the order table
  function updateOrderTable() {
    let html = "";
    orderItems.forEach((item, index) => {
      html += `
                <tr>
                    <td>#${item.prodId}</td>
                    <td>${item.name}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm quantity-input" value="${
                          item.quantity
                        }" min="1" data-index="${index}" style="max-width: 150px">
                    </td>
                    <td>
                      <select class="form-select form-select-sm mb-3" aria-label="Company selector" data-index="${index}"">
                        <option selected>${item.companyName}</option>
                        <!-- Display all suppliers for the product -->
                        ${suppliers
                          .get(item.prodId)
                          .map(
                            (s, supIndex) =>
                              `<option value="${
                                s.companyName
                              }" data-supplier-index="${supIndex}">${
                                s.companyName
                              } - $${s.cost.toFixed(2)} </option>`
                          )
                          .join("")}
                      </select>
                    </td>
                    <td>$${item.cost.toFixed(2)}</td>
                    <td>
                        <button class="btn btn-sm btn-danger remove-item" data-index="${index}">Remove</button>
                    </td>
                </tr>
            `;
    });
    $("#orderItems").html(html);

    // Add an event listener for all supplier selectors
    document.querySelectorAll(".form-select").forEach((select) => {
      // Update the supplier of the item and the order table when the supplier selector input changes
      select.addEventListener("change", (event) => { updateItemSupplier(event); updateOrderTable(); });
    });

    updateTotal();
  }

  // Add Product
  $("#addProductBtn").click(function () {
    $("#productResults").empty();
    $("#productSearch").val("");
    $("#addProductModal").modal("show");
  });

  $("#productSearch").on("input", function () {
    const search = $(this).val();
    if (search.length > 2) {
      $.get("./api/search_stocked_product.php", { q: search }, function (data) {
        let html = "";
        console.log(data);
        data.forEach((product) => {
          html += `<div class="product-item me-3 mt-2">
                            ${product.name}
                            <button class="btn btn-sm btn-primary add-to-order ms-3" data-id="${product.prodId}" data-name="${product.name}">Add to Order</button>
                    </div>`;
        });
        $("#productResults").html(html);
      });
    }
  });

  // Add product to order and update the order table
  $(document).on("click", ".add-to-order", function () {
    const prodId = parseInt($(this).data("id"));
    const prodName = $(this).data("name");

    // Get the suppliers for the product from the API
    $.get("./api/get_suppliers.php", { prodId: prodId }, function (data) {
      data = JSON.parse(data);

      if (data.length > 0) {
        // Set the available suppliers for the product in the suppliers map: prodId -> [available suppliers]
        suppliers.set(prodId, data);
        const suppliersForProd = suppliers.get(prodId);
        firstSupplier = suppliersForProd[0]; // Set the first supplier as the default supplier (it is the most economic)

        console.log("ProdId: " + prodId); // Debug
        const product = {
          prodId: prodId,
          name: prodName,
          companyName: firstSupplier.companyName,
          cost: parseFloat(firstSupplier.cost),
          quantity: 1,
        };

        // Add the product to the order items array
        orderItems.push(product);
        // Update the order table
        updateOrderTable();
        $("#addProductModal").modal("hide");
      } else {
        alert("There's no supplier available for this product!");
      }
    });
  });

  // Update Quantity on change event listened
  $(document).on("change", ".quantity-input", function () {
    const index = $(this).data("index");
    orderItems[index].quantity = parseInt($(this).val());
    updateTotal();
  });

  // Remove Item on click in the remove button
  $(document).on("click", ".remove-item", function () {
    const index = $(this).data("index");
    orderItems.splice(index, 1);
    updateOrderTable();
  });

  // Send Order on click in the send button
  $("#generateOrderBtn").click(function () {
    const orderData = orderItems.map((item) => ({
      prodId: item.prodId,
      quantity: item.quantity,
      supplierName: item.companyName
    }));

    console.log("Trying to send order: " + orderData);

    // Try to add the order to the Database with the correct request to the API
    $.ajax({
      url: "./api/new_stock_order.php",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({
        storekeeperId: storekeeperId,
        estimatedCost: updateTotal(), // Ensure the cost is updated
        orderItems: orderData,
      }),
      success: (response) => {
        response = JSON.parse(response);
        if (response.success) {
          orderItems = [];
          updateOrderTable();
          window.location.href = "./home.php?opt=stock-orders"; // Redirect to the stock orders page
        } else if (response.error) {
          alert("Failed to send order: " + response.error);
        } else {
          // Log the unexpected response
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

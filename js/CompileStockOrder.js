const suppliers = new Map();

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

  function updateTotal() {
    console.log("Updating total");
    const total = orderItems
      .map((e) => e.cost * e.quantity)
      .reduce((accumulator, currentValue) => accumulator + currentValue, 0);
    $("#estimatedCost").text("Estimated Cost:  $" + total.toFixed(2));

    return total;
  }

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
          html += `<div class="product-item me-3">
                            ${product.name}
                            <button class="btn btn-sm btn-primary add-to-order ms-3" data-id="${product.prodId}" data-name="${product.name}">Add to Order</button>
                    </div>`;
        });
        $("#productResults").html(html);
      });
    }
  });

  $(document).on("click", ".add-to-order", function () {
    const prodId = parseInt($(this).data("id"));
    const prodName = $(this).data("name");

    $.get("./api/get_suppliers.php", { prodId: prodId }, function (data) {
      data = JSON.parse(data);

      if (data.length > 0) {
        suppliers.set(prodId, data);
        const suppliersForProd = suppliers.get(prodId);
        firstSupplier = suppliersForProd[0];

        console.log("ProdId: " + prodId);
        const product = {
          prodId: prodId,
          name: prodName,
          companyName: firstSupplier.companyName,
          cost: parseFloat(firstSupplier.cost),
          quantity: 1,
        };

        orderItems.push(product);
        updateOrderTable();
        $("#addProductModal").modal("hide");
      } else {
        alert("There's no supplier available for this product!");
      }
    });
  });

  // Update Quantity
  $(document).on("change", ".quantity-input", function () {
    const index = $(this).data("index");
    orderItems[index].quantity = parseInt($(this).val());
    updateTotal();
  });

  // Remove Item
  $(document).on("click", ".remove-item", function () {
    const index = $(this).data("index");
    orderItems.splice(index, 1);
    updateOrderTable();
  });

  // Send Order
  $("#generateOrderBtn").click(function () {
    const orderData = orderItems.map((item) => ({
      prodId: item.prodId,
      quantity: item.quantity,
      supplierName: item.companyName
    }));

    console.log("Trying to send order: " + orderData);

    $.ajax({
      url: "./api/new_stock_order.php",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({
        storekeeperId: storekeeperId,
        estimatedCost: updateTotal(),
        orderItems: orderData,
      }),
      success: (response) => {
        response = JSON.parse(response);
        if (response.success) {
          orderItems = [];
          updateOrderTable();
          window.location.href = "./home.php?opt=stock-orders";
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

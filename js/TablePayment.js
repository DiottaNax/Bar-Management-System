document.addEventListener("DOMContentLoaded", () => {
  let total = 0;

  // Update the total amount of the receipt to emit
  function updateTotal() {
    total = 0;
    $(".quantity-to-pay").each(function () {
      total += parseFloat($(this).val()) * parseFloat($(this).data("price"));
    });
    $("#totalAmount").text(total.toFixed(2));
    $("#givenMoney").attr("min", total);
    $("#givenMoney").val(total.toFixed(2));
  }

  // Update the total amount of the receipt when the quantity of a product to pay is changed
  $(".quantity-to-pay").on("input", () => {
    updateTotal();
    $("#givenMoney").trigger("input");
  });

  // Show or hide the cash details based on the payment method
  $('input[name="paymentMethod"]').change(function () {
    if (this.value === "cash") {
      $("#cashDetails").show();
    } else {
      $("#cashDetails").hide();
    }
  });

  // Update the change amount when the given money is changed
  $("#givenMoney").on("input", function () {
    const given = parseFloat($(this).val()) || 0;
    const change = given - total;
    $("#changeAmount").val(change >= 0 ? change.toFixed(2) : "0.00");
  });

  // Check if the given money is enough when the user is not focused on the given money input anymore
  $("#givenMoney").on("focusout", function () {
    const given = parseFloat($(this).val()) || 0; // Get the given money value or 0 if the input is empty
    if (given < total) {
      alert("Given money must be >= than total"); // Alert the user if the given money is less than the total
      $("#givenMoney").val(total.toFixed(2));
    }
  });

  // Try to add the payment to the database on the button to register the payment is clicked
  $("#registerPayment").click(function () {
    const paymentMethod = $('input[name="paymentMethod"]:checked').val();
    const givenMoney =
      paymentMethod === "cash" ? parseFloat($("#givenMoney").val()) : null;
    const changeAmount =
      paymentMethod === "cash" ? parseFloat($("#changeAmount").val()) : null;

    const paidProducts = [];
    // Get the products that the user has selected to pay
    $(".quantity-to-pay").each(function () {
      const quantity = parseInt($(this).val());
      if (quantity > 0) {
        paidProducts.push({
          orderedProdId: $(this).data("ordered-prod-id"),
          menuProdId: $(this).data("menu-prod-id"),
          quantity: quantity,
        });
      }
    });

    // Create the payment data to send to the database
    const paymentData = {
      total: total,
      paymentMethod: paymentMethod,
      givenMoney: givenMoney,
      changeAmount: changeAmount,
      tableId: tableId,
      paidProducts: paidProducts,
    };

    console.log("Payment data:", paymentData);

    // If the user has selected at least one product to pay, try to register the payment
    if (paidProducts.length > 0)
      $.post("./api/register_payment.php", paymentData, function (response) {
        console.log(response);
        location.reload();
      });
    else alert("No product to pay selected");
  });
});

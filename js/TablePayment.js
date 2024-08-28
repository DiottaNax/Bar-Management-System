document.addEventListener("DOMContentLoaded", () => {
    let total = 0;

    function updateTotal() {
        total = 0;
        $('.quantity-to-pay').each(function () {
            total += parseFloat($(this).val()) * parseFloat($(this).data('price'));
        });
        $('#totalAmount').text(total.toFixed(2));
        $("#givenMoney").attr('min', total);
        $("#givenMoney").val(total.toFixed(2));
    }

    $('.quantity-to-pay').on('input', () => {
        updateTotal();
        $('#givenMoney').trigger('input');
    });

    $('input[name="paymentMethod"]').change(function () {
        if (this.value === 'cash') {
            $('#cashDetails').show();
        } else {
            $('#cashDetails').hide();
        }
    });

    $('#givenMoney').on('input', function () {
        const given = parseFloat($(this).val()) || 0;
        const change = given - total;
        $('#changeAmount').val(change >= 0 ? change.toFixed(2) : '0.00');
    });

    $('#givenMoney').on('focusout', function () {
        console.log("no focus");
         const given = parseFloat($(this).val()) || 0;
        if (given < total) {
          alert("Given money must be >= than total");
          $("#givenMoney").val(total.toFixed(2));
        }
    });

    $('#registerPayment').click(function () {
        const paymentMethod = $('input[name="paymentMethod"]:checked').val();
        const givenMoney = paymentMethod === 'cash' ? parseFloat($('#givenMoney').val()) : null;
        const changeAmount = paymentMethod === 'cash' ? parseFloat($('#changeAmount').val()) : null;

        const paidProducts = [];
        $('.quantity-to-pay').each(function () {
            const quantity = parseInt($(this).val());
            if (quantity > 0) {
                paidProducts.push({
                    orderedProdId: $(this).data('ordered-prod-id'),
                    menuProdId: $(this).data('menu-prod-id'),
                    quantity: quantity
                });
            }
        });

        const paymentData = {
            total: total,
            paymentMethod: paymentMethod,
            givenMoney: givenMoney,
            changeAmount: changeAmount,
            tableId: tableId,
            paidProducts: paidProducts
        };

        console.log('Payment data:', paymentData);

        if (paidProducts.length > 0)
            $.post('./api/register_payment.php', paymentData, function (response) {
                console.log(response);
                location.reload();
            }
            );
        else
            alert("No product to pay selected");
    });
});
        
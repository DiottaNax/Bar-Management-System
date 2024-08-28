<?php

include_once "./db-config.php";

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    http_response_code(405);
    die('Method Not Allowed');
}

// Verifichiamo che i dati necessari siano presenti
if (!isset($_GET['tableId'])) {
    http_response_code(400);
    die('Missing required data');
}

unset($tableId);
$table = $dbh->getTable($_GET['tableId']);

$unpaidProducts = $dbh->getUnpaidProducts($table['tableId']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>const tableId = <?php echo $table['tableId'] ?>;</script>
    <script src="./js/TablePayment.js"></script>
</head>

<body>
    <div class="container mt-4">
        <h1 class="mb-4">Payment for Table: <?php echo htmlspecialchars($table['name']); ?></h1>

        <?php if (!empty($unpaidProducts)): ?>
            <table class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity Unpaid</th>
                        <th>Price</th>
                        <th>Quantity to Pay</th>
                    </tr>
                </thead>
                <tbody id="unpaidItems">
                    <?php foreach ($unpaidProducts as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['prodName']); ?>
                                <?php if (!empty($product['variations'])): ?>
                                    <ul>
                                        <?php foreach ($product['variations'] as $variation): ?>
                                            <small class="text-muted">
                                                <li>
                                                    <?php echo htmlspecialchars($product['variations'][0]['name']); ?>
                                                </li>
                                            </small>
                                        <?php endforeach; ?>
                                    </ul>

                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($product['quantity'] - $product['numPaid']); ?></td>
                            <td>$<?php echo number_format($product['finalPrice'], 2); ?></td>
                            <td>
                                <input type="number" class="form-control form-control-sm quantity-to-pay" min="0"
                                    max="<?php echo htmlspecialchars($product['quantity'] - $product['numPaid']); ?>" value="0"
                                    data-price="<?php echo $product['finalPrice']; ?>"
                                    data-ordered-prod-id="<?php echo $product['orderedProdId']; ?>"
                                    data-menu-prod-id="<?php echo $product['menuProdId']; ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="mt-4 text-end">
                <h3>Total: $<span id="totalAmount">0.00</span></h3>
            </div>

            <div class="mt-4">
                <h4>Payment Method:</h4>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paymentMethod" id="electronic" value="electronic"
                        checked>
                    <label class="form-check-label" for="electronic">
                        Electronic Payment
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paymentMethod" id="cash" value="cash">
                    <label class="form-check-label" for="cash">
                        Cash Payment
                    </label>
                </div>
            </div>
            
            <div id="cashDetails" class="mt-3 row" style="display: none;">
                <div class="mb-3" style="max-width: 150px;">
                    <label for="givenMoney" class="form-label">Amount Given:</label>
                    <input type="number" class="form-control" id="givenMoney" min="0" step="0.5">
                </div>
                <div class="mb-3" style="max-width: 150px;">
                    <label for="changeAmount" class="form-label">Change:</label>
                    <input type="text" class="form-control" id="changeAmount" readonly>
                </div>
            </div>

            <button id="registerPayment" class="btn btn-primary mt-3 mb-5">Register Payment</button>
        <?php else: ?>
            <h2 class="mt-5 pt-5" style="color: green">All products in the table are paid!</h2>
        <?php endif; ?>
    </div>
</body>

</html>
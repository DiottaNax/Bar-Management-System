<?php
require_once './db-config.php';

$tableId = $_GET['tableId'] ?? null;
$table = $dbh->getTable($tableId);
$orderNum = $_GET['orderNum'] ?? null;
$orderItems = $dbh->getCustomerOrderItems($orderNum, $tableId);

if (!$tableId || !$orderNum) {
    die("Order number and table ID are required.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Order #<?php echo htmlspecialchars($orderNum); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1>Order #<?php echo htmlspecialchars($orderNum); ?> - Table <?php echo htmlspecialchars($table['name']); ?></h1>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Final Price</th>
                    <th>Variations</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="orderItems">
                <!-- Loop through the order items and display them in the table -->
                <?php foreach ($orderItems as $item): ?>
                    <!-- Display each order item in a row -->
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td><?php echo number_format($item['finalPrice'], 2); ?></td>
                        <td>
                            <?php if (!empty($item['variations'])): ?>
                                    <ul class="list-unstyled mb-0">
                                        <!-- Loop through the variations and display them as an unordered list -->
                                        <?php foreach ($item['variations'] as $variation): ?>
                                            <li><?php echo htmlspecialchars($variation['name']); ?> 
                                                (+<?php echo number_format($variation['additionalPrice'], 2); ?>)
                                            </li>
                                    <?php endforeach; ?>
                                    </ul>
                            <?php else: ?>
                                    No variations
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-item disabled" data-id="<?php echo $item['orderedProdId']; ?>">Edit</button>
                            <button class="btn btn-sm btn-danger delete-item disabled" data-id="<?php echo $item['orderedProdId']; ?>">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>
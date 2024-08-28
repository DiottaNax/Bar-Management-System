<?php require_once './db-config.php'; /* Determine which orders to show based on GET parameter, default to "to prepare" */

$orderStatus = $_GET['status'] ?? 'to_prepare';
switch ($orderStatus) {
    case 'in_preparation':
        $orders = $dbh->getCustomerOrders(1, 0);
        break;
    case 'delivered':
        $orders = $dbh->getCustomerOrders(1, 1);
        break;
    case 'to_prepare':
    default:
        $orders = $dbh->getCustomerOrders(0, 0);
        break;
}
?>

<main class="container mt-5 mb-5">
    <h1 class="mb-4">Customer Orders</h1>

    <div class="row mb-4">
        <div class="col-md-6">
            <label for="order-status" class="form-label">Filter Orders:</label>
            <select id="order-status" class="form-select">
                <option value="to_prepare" <?php echo $orderStatus == 'to_prepare' ? 'selected' : ''; ?>><a
                        href="./home.php?opt=customer-orders&status=to_prepare">To Prepare</a>
                </option>
                <option value="in_preparation" <?php echo $orderStatus == 'in_preparation' ? 'selected' : ''; ?>><a
                        href="./home.php?opt=customer-orders&status=in_preparation">In
                        Preparation</a></option>
                <option value="delivered" <?php echo $orderStatus == 'delivered' ? 'selected' : ''; ?>><a
                        href="./home.php?opt=customer-orders&status=delivered">Delivered</a></option>
            </select>
        </div>
    </div>

    <div id="orders-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($orders as $order): ?>
            <div class="col">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Order #<?php echo htmlspecialchars($order['numOfDay']); ?></h5>
                        <div class="card-text px-3">
                            <div class="row mt-2 mb-1">
                                <div class="col">
                                    <h6 class="d-inline">Table: </h6>
                                    <span><?php echo htmlspecialchars($order['tableName']); ?></span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col">
                                    <h6 class="d-inline">Time: </h6>
                                    <span><?php echo date('F j, Y, g:i a', strtotime($order['timestamp'])); ?></span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col">
                                    <h6 class="d-inline">Status: </h6>
                                    <span>
                                        <?php
                                        if ($order['delivered']) {
                                            echo 'Delivered';
                                        } elseif ($order['inPreparation']) {
                                            echo 'In Preparation';
                                        } else {
                                            echo 'To Prepare';
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <h6 class="d-inline">Waiter: </h6>
                                    <span><?php echo htmlspecialchars($order['waiterName'] . " " . $order['waiterSurname']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <?php if (!$order['delivered']): ?>
                                <?php if (!$order['inPreparation']): ?>
                                    <a class="btn btn-primary me-2"
                                        href="./view-customer-order.php?orderNum=<?php echo $order['orderNum'] ?>&tableId=<?php echo $order['tableId'] ?>"
                                        target="_blank">Open Order</a>
                                    <a href="#" class="btn btn-success me-2" data-order-id="<?php echo $order['orderNum']; ?>">Start
                                        Preparation</a>
                                <?php else: ?>
                                    <a href="#" class="btn btn-success" data-order-id="<?php echo $order['orderNum']; ?>">Mark
                                        as
                                        Delivered</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusSelect = document.getElementById('order-status');
        statusSelect.addEventListener('change', function () {
            window.location.href = './home.php?opt=customer-orders&status=' + this.value;
        });
    });
</script>
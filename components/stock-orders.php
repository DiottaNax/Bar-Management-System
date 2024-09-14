<?php
if (!isset($_SESSION['employeeId'])) {
    die("Errore: Accesso non autorizzato. Effettua il login.");
}

$orders = $dbh->getStockOrders();
?>

<!-- Page to view stock orders -->
<main class="container mt-5 mb-5">
    <h1 class="mb-4">Stock Orders</h1>

    <!-- Filter orders based on status -->
    <div class="row mb-4">
        <div class="col-md-6">
            <label for="order-status" class="form-label">Filter Orders:</label>
            <select id="order-status" class="form-select" disabled>
                <option value="All" selected>All</option>
                <option value="Sent">Sent</option>
                <option value="Not sent yet">Not sent yet</option>
                </option>
            </select>
        </div>
    </div>

    <div id="orders-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <!-- Loop through the orders and display them in cards -->
        <?php foreach ($orders as $order): ?>
            <div class="col">
                <div class="card h-100">
                    <div class="card-header text-center">
                        <!-- Card header with order id -->
                        <h5 class="card-title">Order #<?php echo htmlspecialchars($order['orderId']); ?></h5>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <!-- Card body with order details -->
                        <div class="card-text px-3">
                            <div class="row mt-2 mb-1">
                                <div class="col">
                                    <h6 class="d-inline">Estimated cost: $</h6>
                                    <span><?php echo htmlspecialchars($order['estimatedCost']); ?></span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col">
                                    <h6 class="d-inline">Date: </h6>
                                    <!-- Format the date and time: day month, year, hour:minute am/pm -->
                                    <span><?php echo date('F j, Y, g:i a', strtotime($order['creationTimestamp'])); ?></span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col">
                                    <h6 class="d-inline">Status: </h6>
                                    <span>
                                        <?php
                                        // If the order is sent, display "Sent", otherwise display "Not sent yet"
                                        if ($order['sent']) {
                                            echo 'Sent';
                                        } else {
                                            echo 'Not sent yet';
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <h6 class="d-inline">Storekeeper: </h6>
                                    <!-- Display the storekeeper's name and surname -->
                                    <span><?php echo htmlspecialchars($order['storekeeperName'] . " " . $order['storekeeperSurname']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-auto card-footer text-center">
                        <a class="btn btn-primary me-2 disabled" href="#" target="_blank" role="button" disabled>Open Order</a>
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

<!-- Side menu -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="top-menu" aria-labelledby="offcanvasTopLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasTopLabel">Option Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="list-group">
            <a href="./compile-stock-order.php" class="list-group-item list-group-item-primary list-group-item-action">
                <img src="./resources/svg/clipboard-plus.svg" alt="New Stock Order" class="me-1 mb-1">
                New Stock Order
            </a>
        </div>
        <div class="mt-auto">
            <hr>
            <button class="btn btn-danger w-100 py-2" onclick='window.location.href = "./api/logout.php"'><img
                    src="./resources/svg/logout.svg" alt="Logout" class="mb-1 me-2">Logout</button>
        </div>
    </div>
</div>

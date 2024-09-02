<?php
require_once './db-config.php';

$orders = $dbh->getStockOrders();
?>

<main class="container mt-5 mb-5">
    <h1 class="mb-4">Customer Orders</h1>

    <div class="row mb-4">
        <div class="col-md-6">
            <label for="order-status" class="form-label">Filter Orders:</label>
            <select id="order-status" class="form-select" disabled>
                <option value="All" selected><a href="./home.php?opt=stock-orders&status=all">All</a></option>
                <option value="Sent"><a href="./home.php?opt=customer-orders&status=sent">Sent</a></option>
                <option value="Not sent yet"><a href="./home.php?opt=customer-orders&status=not_sent">Not sent yet</a>
                </option>
            </select>
        </div>
    </div>

    <div id="orders-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($orders as $order): ?>
            <div class="col">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Order #<?php echo htmlspecialchars($order['orderId']); ?></h5>
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
                                    <span><?php echo date('F j, Y, g:i a', strtotime($order['creationTimestamp'])); ?></span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col">
                                    <h6 class="d-inline">Status: </h6>
                                    <span>
                                        <?php
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
                                    <span><?php echo htmlspecialchars($order['storekeeperName'] . " " . $order['storekeeperSurname']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <a class="btn btn-primary me-2" href="#" target="_blank" disabled>Open Order</a>
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

<div class="offcanvas offcanvas-start" tabindex="-1" id="top-menu" aria-labelledby="offcanvasTopLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasTopLabel">Option Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-primary list-group-item-action" data-bs-toggle="modal"
                data-bs-target="#addProductModal">
                Add New Product
            </a>
        </div>
        <div class="mt-auto">
            <hr>
            <button class="btn btn-danger w-100" onclick='window.location.href = "./api/logout.php"'><img
                    src="./resources/svg/logout.svg" alt="Logout"> Logout</button>
        </div>
    </div>
</div>
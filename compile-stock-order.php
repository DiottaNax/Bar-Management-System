<?php
require_once './db-config.php';

$storekeeperId = $_SESSION['employeeId'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Stock Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        const storekeeperId = <?php echo json_encode(intval($storekeeperId)); ?>;
        let orderItems = [];
    </script>
    <script src="./js/CompileStockOrder.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1>New Stock Order</h1>

        <div class="container mt-4">
            <table class="table table-striped table-hover" id="orderItemsTable">
                <thead class="table-primary">
                    <tr>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Company Name</th>
                        <th>Cost</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="orderItems">
                    <!-- Order items will be dynamically added here -->
                </tbody>
            </table>
        </div>
            <h4 id="estimatedCost" class="justify-content-end text-end mx-5 my-3"></h4>

        <div class="row mt-3">
            <button class="btn btn-primary col mx-5" id="addProductBtn">Add Product</button>
            <button class="btn btn-success col mx-5" id="generateOrderBtn">Generate Order</button>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" id="productSearch" placeholder="Search products..."
                        autocomplete="off">
                    <div id="productResults" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
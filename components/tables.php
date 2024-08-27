<main class="container mt-5">
    <h1 class="mb-4">Table Management</h1>

    <div class="row mb-4">
        <div class="col-md-6">
            <label for="date-selector" class="form-label">Select Date:</label>
            <input type="date" id="date-selector" class="form-control" value="<?php echo date('Y-m-d'); ?>">
        </div>
    </div>

    <div id="table-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php
        $tables = $dbh->getTables(); // Using today's date by default
        foreach ($tables as $table):
            ?>
            <div class="col">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">#<?php echo $table['numOfDay'] ?>  Table <?php echo htmlspecialchars($table['name']); ?></h5>
                        <p class="card-text">Seats: <?php echo htmlspecialchars($table['seats']); ?></p>
                        <p class="card-text">Created:
                            <?php echo date('F j, Y, g:i a', strtotime($table['creationTimestamp'])); ?></p>
                        <div class="mt-auto">
                            <a href="./compile-customer-order.php?tableId=<?php echo $table['tableId']; ?>" class="btn btn-primary me-2" data-table-id="<?php echo $table['tableId']; ?>">Add
                                Products</a>
                            <a href="#" class="btn btn-success" data-table-id="<?php echo $table['tableId']; ?>">Pay</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<!-- Code for the side menu -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="top-menu" aria-labelledby="offcanvasTopLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasTopLabel">Option Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a id="new-table-link" href="#" data-bs-toggle="modal" data-bs-target="#addTableModal">Add new table</a>
            </li>
        </ul>
        <div class="mt-auto">
            <hr>
            <button class="btn btn-danger w-100" onclick='window.location.href = "./api/logout.php"'><img
                    src="./resources/svg/logout.svg" alt="Logout"> Logout</button>
        </div>
    </div>
</div>

<!-- Modal for adding a new table -->
<div class="modal fade" id="addTableModal" tabindex="-1" aria-labelledby="addTableModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTableModalLabel">Add New Table</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="add-table-form">
                    <div class="mb-3">
                        <label for="table-name" class="form-label">Table Name</label>
                        <input type="text" class="form-control" id="table-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="table-seats" class="form-label">Number of Seats</label>
                        <input type="number" class="form-control" id="table-seats" name="seats" required min="1">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Table</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="./js/Tables.js"></script>

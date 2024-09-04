<?php
if (!isset($_SESSION['employeeId'])) {
    die("Errore: Accesso non autorizzato. Effettua il login.");
}
?>

<!-- Page to view tables -->
<main class="container mt-5">
    <h1 class="mb-4">Table Management</h1>

    <?php
    // Get date if passed as GET, or else get current date
    $date = $_GET['date'] ?? date('Y-m-d');

    // Control that the date format is correct
    $dateObj = DateTime::createFromFormat('Y-m-d', $date);
    if (!$dateObj || $dateObj->format('Y-m-d') !== $date) {
        $date = date('Y-m-d'); // If not, use the current date
    }
    ?>


    <div class="row mb-4">
        <div class="col-md-6">
            <label for="date-selector" class="form-label">Select Date:</label>
            <input type="date" id="date-selector" class="form-control" style="max-width: 300px;" value="<?php echo $date; ?>">
        </div>
    </div>

    <!-- Display tables -->
    <div id="table-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php
        $tables = $dbh->getTables($date); // Get tables in the selected date

        // Loop through the tables and display them in cards
        foreach ($tables as $table):
            ?>
            <div class="col">
                <div class="card h-100">
                    <!-- Card header with table name and number(based on the day, it does not correspond to the tableId) -->
                    <div class="card-header py-2 text-center">
                        <h5 class="card-title pt-2">#<?php echo $table['numOfDay'] ?> Table
                            <?php echo htmlspecialchars($table['name']); ?>
                        </h5>
                    </div>
                    <div class="card-body d-flex flex-column mt-2 mx-2 px-3">
                        <!-- Card body with table seats and creation date -->
                        <p class="card-text"><strong>Seats:</strong> <?php echo htmlspecialchars($table['seats']); ?></p>
                        <p class="card-text"><strong>Created:</strong>
                            <?php echo date('F j, Y, g:i a', strtotime($table['creationTimestamp'])); ?>
                        </p>
                    </div>
                    <div class="card-footer mt-2 text-center">
                        <!-- Card footer with links/buttons to add products and view unpaid products -->
                        <a target="_blank"
                            href="./compile-customer-order.php?tableId=<?php echo $table['tableId']; ?>&date=<?php echo $date; ?>"
                            class="btn btn-primary me-2" data-table-id="<?php echo $table['tableId']; ?>">Add
                            Products</a>
                        <a target="_blank"
                            href="./view-unpaid-products.php?tableId=<?php echo $table['tableId']; ?>&date=<?php echo $date; ?>"
                            class="btn btn-success" data-table-id="<?php echo $table['tableId']; ?>">Pay</a>
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
        <div class="list-group">
            <a class="list-group-item list-group-item-primary list-group-item-action" id="new-table-link" href="#"
                data-bs-toggle="modal" data-bs-target="#addTableModal">Add new table</a>
        </div>
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
                        <input type="text" class="form-control" id="table-name" name="name" autocomplete="off" required>
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
<main class="container mt-5">
    <h1 class="mb-4">Reservations</h1>

    <?php
    // Determina la data da utilizzare
    $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

    // Valida la data
    $dateObj = DateTime::createFromFormat('Y-m-d', $date);
    if (!$dateObj || $dateObj->format('Y-m-d') !== $date) {
        $date = date('Y-m-d'); // Se la data non è valida, usa la data odierna
    }
    ?>

    <div class="row mb-4">
        <div class="col-md-6">
            <label for="date-selector" class="form-label">Select Date:</label>
            <input type="date" id="date-selector" class="form-control" style="max-width: 300px;"
                value="<?php echo $date; ?>">
        </div>
    </div>

    <div id="reservations-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php
        $reservations = $dbh->getReservationsOnDay($date); // Passa la data selezionata

        if(empty($reservations)): ?>
        <div class="col-12 justify-content-center text-center">
            <h2>No reservation on this day!</h2>
        </div>

        <?php
        else:?>

        <?php
        foreach ($reservations as $reservation):
            ?>
            <div class="col">
                <div class="card h-100">
                    <div class="card-header py-2 text-center">
                        <h5 class="card-title pt-2">
                            <?php echo htmlspecialchars($reservation['clientName']); ?>
                        </h5>
                    </div>
                    <div class="card-body d-flex flex-column mt-2 mx-2 px-3">
                        <p class="card-text"><strong>Cell number: </strong> <?php echo htmlspecialchars($reservation['cellNumber']); ?></p>
                        <p class="card-text"><strong>For: </strong> <?php echo htmlspecialchars($reservation['seats']); echo $reservation['seats'] == 1 ? " person" :" people"; ?></p>
                        <p class="card-text"><strong>At: </strong>
                            <?php echo date('F j, g:i a', strtotime($reservation['dateAndTime'])); ?>
                        </p>
                        <p><strong>Table: </strong><?php echo htmlspecialchars($reservation['tableName'] ?? "No table reserved yet"); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; endif; ?>
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
            <a class="list-group-item list-group-item-dark list-group-item-action" id="new-table-link" href="#"
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

<script>const dateSelector = document.getElementById("date-selector");

  dateSelector.addEventListener("change", function () {
    window.location.href = "./home.php?opt=reservations&date=" + this.value;
  });</script>

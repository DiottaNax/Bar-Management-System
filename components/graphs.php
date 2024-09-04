<?php 
if (!isset($_SESSION['employeeId'])) {
    die("Errore: Accesso non autorizzato. Effettua il login.");
}
?>

<main class="container mt-5">
    <h1 class="mb-4">Financial Dashboard</h1>

    <!-- Menu to choose start and end dates -->
    <div class="row mb-4">
        <div class="col-md-3">
            <label for="start-date" class="form-label">Start Date:</label>
            <input type="date" id="start-date" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="end-date" class="form-label">End Date:</label>
            <input type="date" id="end-date" class="form-control">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button id="update-charts" class="btn btn-primary">Update Chart</button>
        </div>
    </div>

    <!-- Graphs container -->
    <div class="row">
        <div class="col my-5">
            <canvas id="salesChart"></canvas> <!-- It will be dinamically updated by js -->
        </div>
        <div class="col my-5">
            <canvas id="servicesChart"></canvas> <!-- It will be dinamically updated by js -->
        </div>
    </div>
</main>

<!-- Side menu, with logout button -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="top-menu" aria-labelledby="offcanvasTopLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasTopLabel">Option Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="list-group">
        </div>
        <div class="mt-auto">
            <hr>
            <button class="btn btn-danger w-100" onclick='window.location.href = "./api/logout.php"'><img
                    src="./resources/svg/logout.svg" alt="Logout"> Logout</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="./js/DrawFinancialGraphs.js"></script>

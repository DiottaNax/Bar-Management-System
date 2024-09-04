<?php
if (!isset($_SESSION['employeeId'])) {
    die("Errore: Accesso non autorizzato. Effettua il login.");
}

if (!isset($_GET['type']) || ($_GET['type'] != "menu" && ($_GET['type'] != "stocked")))
    $_GET['type'] = "menu";
?>

<!-- Option menu, to include in every product sub-page -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="top-menu" aria-labelledby="offcanvasTopLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasTopLabel">Option Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="list-group">
            <!-- The sub-page should make this button work -->
            <a href="#" class="list-group-item list-group-item-primary list-group-item-action" data-bs-toggle="modal"
                data-bs-target="#addProductModal">
                Add New Product
            </a>
        </div>
        
        <!-- Logout button -->
        <div class="mt-auto">
            <hr>
            <button class="btn btn-danger w-100" onclick='window.location.href = "./api/logout.php"'><img
                    src="./resources/svg/logout.svg" alt="Logout"> Logout</button>
        </div>
    </div>
</div>

<!-- Adding the specific products' category sub-page -->
<main class="container mt-5"><?php include_once "./components/" . $_GET['type'] . "-products.php"; ?></main>

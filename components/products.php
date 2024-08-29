<?php

if(!isset($_GET['type']) || ($_GET['type'] != "menu" && ($_GET['type'] != "stocked")))
    $_GET['type'] = "menu";
?>

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
            <button class="btn btn-danger w-100" onclick='window.location.href = "./api/logout.php"'><img src="./resources/svg/logout.svg" alt="Logout"> Logout</button>
        </div>
    </div>
</div>

<main class="container mt-5"><?php include_once "./components/" . $_GET['type'] . "-products.php"; ?></main>


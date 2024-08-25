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
        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <?php echo $_GET['type'] ?? ''; ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="./home.php?opt=products&type=menu">menu</a></li>
                        <li><a class="dropdown-item" href="./home.php?opt=products&type=stocked">stocked up</a></li>
                    </ul>
                </div>
            </li>
        </ul>
        <div class="mt-auto">
            <hr>
            <button class="btn btn-danger w-100" onclick='window.location.href = "./api/logout.php"'><img src="./resources/svg/logout.svg" alt="Logout"> Logout</button>
        </div>
    </div>
</div>

<p><?php include_once "./components/" . $_GET['type'] . "-products.php"; ?></p>


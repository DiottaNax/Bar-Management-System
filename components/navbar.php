<!-- Navbar with option menu and other links, to include in every page -->

<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark" opt-choice="<?php echo $_GET['opt'] ?? ''; ?>">
    <!-- It's used to set the active link in the menu -->
    <div class="container-fluid">
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav nav-underline">
                <li class="nav-item">
                    <!-- Button to show the option menu, the offcanvas should be defined in every page that includes this navbar -->
                    <button class="btn" data-bs-toggle="offcanvas" data-bs-target="#top-menu" aria-controls="top-menu">
                        <img src="./resources/svg/menu.svg" alt="Option Menu" width="30" height="30">
                    </button>
                </li>

                <!-- Links to other pages -->
                <li class="nav-item" opt="tables">
                    <a class="nav-link" href="./home.php?opt=tables">Tables</a>
                </li>
                <li class="nav-item" opt="reservations">
                    <a class="nav-link" href="./home.php?opt=reservations">Reservations</a>
                </li>
                <li class="nav-item" opt="customer-orders">
                    <a class="nav-link" href="./home.php?opt=customer-orders">Customer Orders</a>
                </li>
                <li class="nav-item" opt="products">
                    <a class="nav-link" href="./home.php?opt=products">Products</a>
                </li>

                <!-- Display more options based on the user's roles -->
                <?php if ($_SESSION['isStorekeeper'] || $_SESSION['isAdmin']): ?>
                    <li class="nav-item" opt="stock-orders">
                        <a class="nav-link" href="./home.php?opt=stock-orders">Stock Orders</a>
                    </li>
                <?php endif; ?>

                <?php if ($_SESSION['isAdmin']): ?>
                    <li class="nav-item" opt="graphs">
                        <a class="nav-link" href="./home.php?opt=graphs">Graphs</a>
                    </li>
                    <li class="nav-item" opt="employees">
                        <a class="nav-link" href="./home.php?opt=employees">Employees</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script src="./js/Navbar.js"></script> <!-- Include to make the navbar work properly -->

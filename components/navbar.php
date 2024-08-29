<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark"
    opt-choice="<?php echo isset($_GET['opt']) ? $_GET['opt'] : ''; ?>">
    <div class="container-fluid">
        <!--<a class="navbar-brand" href="#">
            <img src="./svg/logo.svg" alt="Menu" width="30" height="30">
        </a>-->
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav nav-underline">
                <li class="nav-item">
                    <button class="btn" data-bs-toggle="offcanvas" data-bs-target="#top-menu" aria-controls="top-menu">
                        <img src="./resources/svg/menu.svg" alt="Option Menu" width="30" height="30">
                    </button>
                </li>
                <li class="nav-item" opt="tables">
                    <a class="nav-link" href="./home.php?opt=tables">Tables</a>
                </li>
                <li class="nav-item" opt="customer-orders">
                    <a class="nav-link" href="./home.php?opt=customer-orders">Customer Orders</a>
                </li>
                <li class="nav-item" opt="products">
                    <a class="nav-link" href="./home.php?opt=products">Products</a>
                </li>
                <li class="nav-item" opt="stock-orders">
                    <a class="nav-link" href="./home.php?opt=stock-orders">Stock Orders</a>
                </li>
                <li class="nav-item" opt="graphs">
                    <a class="nav-link" href="./home.php?opt=graphs">Graphs</a>
                </li>
                <li class="nav-item" opt="profile">
                    <a class="nav-link" href="./home.php?opt=employees">Employees</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

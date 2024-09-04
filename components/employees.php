<?php
if (!isset($_SESSION['employeeId'])) {
    die("Errore: Accesso non autorizzato. Effettua il login.");
}

$employees = $dbh->getEmployees();
?>

<main class="container mt-4">
    <h1 class="mb-4">Employees Management</h1>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <!-- Loop through all employees and display them in cards -->
        <?php foreach ($employees as $employee): ?>
            <div class="col">
                <div class="card h-100">
                    <div class="card-header text-center">
                        <h5 class="card-title my-2">
                            <!-- Display employee's name and surname in the card title -->
                            <?php echo htmlspecialchars($employee['name'] . ' ' . $employee['surname']); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="card-text m px-3" style="height: 200px; overflow-y: auto;">
                            <ul class="list-unstyled">
                                <li class="mb-2"><strong>Email:</strong> <?php echo htmlspecialchars($employee['email']); ?>
                                </li>
                                <li class="mb-2"><strong>CF:</strong> <?php echo htmlspecialchars($employee['cf']); ?></li>
                                <li class="mb-2"><strong>Birthday:</strong>
                                    <?php echo htmlspecialchars(date('j F Y', strtotime($employee['birthday']))); ?></li>
                                <li class="mb-2"><strong>Hiring Date:</strong>
                                    <?php echo htmlspecialchars(date('j F Y', strtotime($employee['hiringDate']))); ?>
                                </li>
                                <li class="mb-2"><strong>Roles:</strong> 
                                    <?php
                                    // Create an array with all roles as string
                                    $roles = [];
                                    if ($employee['isWaiter'])
                                        $roles[] = 'Waiter';
                                    if ($employee['isStorekeeper'])
                                        $roles[] = 'Storekeeper';
                                    if ($employee['isKitchenStaff'])
                                        $roles[] = 'Kitchen Staff';
                                    if ($employee['isAdmin'])
                                        $roles[] = 'Admin';
                                    echo htmlspecialchars(implode(', ', $roles)); // Create a string with all roles from the array
                                    ?>
                                </li>
                                <li class="mb-2"><strong>Address:</strong> <?php
                                $address = []; // Create an array with all the non-null employee's house address
                                if ($employee['streetName'])
                                    $address[] = $employee['streetName'];
                                if ($employee['streetNumber'])
                                    $address[] = $employee['streetNumber'];
                                if ($employee['city'])
                                    $address[] = $employee['city'];
                                if ($employee['zipCode'])
                                    $address[] = $employee['zipCode'];
                                echo htmlspecialchars(implode(', ', $address)); 
                                ?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button class="btn btn-primary btn-sm me-2 px-3" data-bs-toggle="modal"
                            data-bs-target="#modifyEmployeeModal"
                            onclick="setEmployeeId(<?php echo $employee['employeeId']; ?>)"><img class="mb-1" src="./resources/svg/modify.svg" alt="modify icon">  Modify </button>
                        <button class="btn btn-danger btn-sm px-3"
                            onclick="deleteEmployee(<?php echo $employee['employeeId']; ?>)"><img class="mb-1" src="./resources/svg/delete.svg" alt="delete icon">  Delete </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<!-- Offcanva for the side menu -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="top-menu" aria-labelledby="offcanvasTopLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasTopLabel">Option Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="list-group">
                <a href="#" class="list-group-item list-group-item-primary list-group-item-action" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Add New
                    Employee</a>
        </div>
        <div class="mt-auto">
            <hr>
            <button class="btn btn-danger w-100" onclick='window.location.href = "./api/logout.php"'><img
                    src="./resources/svg/logout.svg" alt="Logout"> Logout</button>
        </div>
    </div>
</div>

<!-- ******************************************** -->
<!-- * Modals to update and add employees       * -->
<!-- ******************************************** -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEmployeeForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="cf" class="form-label">CF</label>
                        <input type="text" class="form-control" id="cf" name="cf" maxlength="16" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="surname" class="form-label">Surname</label>
                        <input type="text" class="form-control" id="surname" name="surname" required>
                    </div>
                    <div class="mb-3">
                        <label for="birthday" class="form-label">Birthday</label>
                        <input type="date" class="form-control" id="birthday" name="birthday" required>
                    </div>
                    <div class="mb-3">
                        <label for="hiringDate" class="form-label">Hiring Date</label>
                        <input type="date" class="form-control" id="hiringDate" name="hiringDate" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="isWaiter" name="isWaiter">
                        <label class="form-check-label" for="isWaiter">Waiter</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="isStorekeeper" name="isStorekeeper">
                        <label class="form-check-label" for="isStorekeeper">Storekeeper</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="isKitchenStaff" name="isKitchenStaff">
                        <label class="form-check-label" for="isKitchenStaff">Kitchen Staff</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="isAdmin" name="isAdmin">
                        <label class="form-check-label" for="isAdmin">Admin</label>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City (optional)</label>
                        <input type="text" class="form-control" id="city" name="city">
                    </div>
                    <div class="mb-3">
                        <label for="zipCode" class="form-label">Zip Code (optional)</label>
                        <input type="text" class="form-control" id="zipCode" name="zipCode" maxlength="5">
                    </div>
                    <div class="mb-3">
                        <label for="streetName" class="form-label">Street Name (optional)</label>
                        <input type="text" class="form-control" id="streetName" name="streetName">
                    </div>
                    <div class="mb-3">
                        <label for="streetNumber" class="form-label">Street Number (optional)</label>
                        <input type="text" class="form-control" id="streetNumber" name="streetNumber">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addEmployee()">Add Employee</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modifyEmployeeModal" tabindex="-1" aria-labelledby="modifyEmployeeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modifyEmployeeModalLabel">Modify Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modifyEmployeeForm">
                    <input type="hidden" id="modifyEmployeeId" name="employeeId">
                    <div class="mb-3">
                        <label for="modifyEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="modifyEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="modifyPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="modifyPassword" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="modifyCf" class="form-label">CF</label>
                        <input type="text" class="form-control" id="modifyCf" name="cf" maxlength="16" required>
                    </div>
                    <div class="mb-3">
                        <label for="modifyName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="modifyName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="modifySurname" class="form-label">Surname</label>
                        <input type="text" class="form-control" id="modifySurname" name="surname" required>
                    </div>
                    <div class="mb-3">
                        <label for="modifyBirthday" class="form-label">Birthday</label>
                        <input type="date" class="form-control" id="modifyBirthday" name="birthday" required>
                    </div>
                    <div class="mb-3">
                        <label for="modifyHiringDate" class="form-label">Hiring Date</label>
                        <input type="date" class="form-control" id="modifyHiringDate" name="hiringDate" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="modifyIsWaiter" name="isWaiter">
                        <label class="form-check-label" for="modifyIsWaiter">Waiter</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="modifyIsStorekeeper" name="isStorekeeper">
                        <label class="form-check-label" for="modifyIsStorekeeper">Storekeeper</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="IsKitchenStaff" name="isKitchenStaff">
                        <label class="form-check-label" for="modifyIsKitchenStaff">Kitchen Staff</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="modifyIsAdmin" name="isAdmin">
                        <label class="form-check-label" for="modifyIsAdmin">Admin</label>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City (optional)</label>
                        <input type="text" class="form-control" id="modifyCity" name="city">
                    </div>
                    <div class="mb-3">
                        <label for="modifyZipCode" class="form-label">Zip Code (optional)</label>
                        <input type="text" class="form-control" id="modifyZipCode" name="zipCode" maxlength="5">
                    </div>
                    <div class="mb-3">
                        <label for="modifyStreetName" class="form-label">Street Name (optional)</label>
                        <input type="text" class="form-control" id="modifyStreetName" name="streetName">
                    </div>
                    <div class="mb-3">
                        <label for="modifyStreetNumber" class="form-label">Street Number (optional)</label>
                        <input type="text" class="form-control" id="modifyStreetNumber" name="streetNumber">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateEmployee()">Update Employee</button>
            </div>
        </div>
    </div>
</div>

<script src="./js/UpdateEmployees.js"></script>
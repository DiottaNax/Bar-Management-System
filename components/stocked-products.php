<?php
if (!isset($_SESSION['employeeId'])) {
    die("Errore: Accesso non autorizzato. Effettua il login.");
}

$products = $dbh->getAllStockedProducts();
?>

<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h1>Stocked Up Products</h1>
    </div>
    <div class="col-md-6 text-md-end">
        <label for="product-type" class="form-label me-2">View:</label>
        <select id="product-type" class="form-select d-inline-block w-auto" onchange="window.location.href=this.value;">
            <option value="./home.php?opt=products&type=menu" <?php echo $_GET['type'] == 'menu' ? 'selected' : ''; ?>>
                Menu</option>
            <option value="./home.php?opt=products&type=stocked" <?php echo $_GET['type'] == 'stocked' ? 'selected' : ''; ?>>Stocked Up</option>
        </select>
    </div>
</div>


<div class="container px-2 mb-5">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
        <?php foreach ($products as $product): ?>
            <div class="col">
                <div class="card h-100" style="max-width: 540px;">
                    <div class="row g-0 h-100">
                        <div class="col-md-4">
                            <img src="./resources/img/<?php echo $product['imgFile'] ?>"
                                class="img-fluid rounded-start h-100" alt="<?php echo $product['name'] ?> image"
                                style="object-fit: cover;">
                        </div>
                        <div class="col-md-8 d-flex flex-column">
                            <div class="card-body d-flex flex-column h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title mb-0"><?php echo $product['name'] ?></h5>
                                    <h6 class="card-subtitle text-muted mb-0"></h6>
                                </div>
                                <div class="mb-2">
                                    <span
                                        class="badge rounded-pill bg-primary me-1"><?php echo $product['category'] ?></span>
                                    <?php if (isset($product['subcategory'])): ?>
                                        <span class="badge rounded-pill bg-info"><?php echo $product['subcategory'] ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="card-text flex-grow-1" style="overflow-y: auto;">
                                    <p>Stock Availability: <?php echo $product['availability'] ?></p>
                                </div>

                                <div class="d-flex align-items-center mt-3">
                                    <a class="btn btn-warning edit-product px-3" href="#"
                                        data-product-id="<?php echo $product['prodId']; ?>" data-bs-toggle="modal"
                                        data-bs-target="#editProductModal"
                                        onclick='$("#saveEditProduct").attr("data-product-id", <?php echo $product["prodId"]; ?>)'>
                                        <img class="mb-1 me-2" src="./resources/svg/modify-black.svg"
                                            alt="Modify icon">Modify
                                    </a>
                                    <a class="btn btn-danger delete-product ms-3 px-3" href="#" style="color: black;"
                                        data-product-id="<?php echo $product['prodId']; ?>">
                                        <img class="mb-1 me-2" src="./resources/svg/delete-black.svg"
                                            alt="Delete icon">Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addProductForm">
                    <div class="mb-3">
                        <label for="newProductName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="newProductName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="newProductCategory" class="form-label">Category</label>
                        <input type="text" class="form-control" id="newProductCategory" name="category" required>
                    </div>
                    <div class="mb-3">
                        <label for="newProductSubcategory" class="form-label">Subcategory</label>
                        <input type="text" class="form-control" id="newPoductSubcategory" name="subcategory" required>
                    </div>
                    <div class="mb-3">
                        <label for="newProductAvailability" class="form-label">Availability</label>
                        <input type="number" class="form-control" id="newProductAvailability" step="1"
                            name="availability" required>
                    </div>
                    <div class="mb-3">
                        <label for="newProductImage" class="form-label">Image File</label>
                        <input type="file" class="form-control" id="newProductImage" name="imgFile">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveNewProduct">Save Product</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    <div class="mb-3">
                        <label for="editProductName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editProductName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProductCategory" class="form-label">Category</label>
                        <input type="text" class="form-control" id="editProductCategory" name="category" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProductSubcategory" class="form-label">Subcategory</label>
                        <input type="text" class="form-control" id="editPoductSubcategory" name="subcategory" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProductAvailability" class="form-label">Availability</label>
                        <input type="number" class="form-control" id="editProductAvailability" min="0" step="1"
                            name="availability" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProductImage" class="form-label">Image File</label>
                        <input type="file" class="form-control" id="editProductImage" name="imgFile">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveEditProduct">Save Modifications</button>
            </div>
        </div>
    </div>
</div>

<script src="./js/UpdateStockedProducts.js"></script>

<script>
    document.getElementById('product-type').addEventListener('change', function () {
        window.location.href = this.value;
    });
</script>
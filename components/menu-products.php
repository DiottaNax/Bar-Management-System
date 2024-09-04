<?php
if (!isset($_SESSION['employeeId'])) {
    die("Errore: Accesso non autorizzato. Effettua il login.");
}
include_once "./components/recipe-toast.php";
$products = $dbh->getAllMenuProducts();
?>


<div class="container mt-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1>Menu Products</h1>
        </div>
        <!-- Select category -->
        <div class="col-md-6 text-md-end">
            <label for="product-type" class="form-label me-2">View:</label>
            <select id="product-type" class="form-select d-inline-block w-auto"
                onchange="window.location.href=this.value;">
                <option value="./home.php?opt=products&type=menu" <?php echo $_GET['type'] == 'menu' ? 'selected' : ''; ?>>Menu</option>
                <option value="./home.php?opt=products&type=stocked" <?php echo $_GET['type'] == 'stocked' ? 'selected' : ''; ?>>Stocked Up</option>
            </select>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center mb-5">
        <!-- Loop through products and display them in a card -->
        <?php foreach ($products as $product): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="row g-0 h-100">
                        <!-- Product image, get images from resources/img/ -->
                        <div class="col-md-4">
                            <img src="./resources/img/<?php echo $product['imgFile'] ?>"
                                class="img-fluid rounded-start h-100" alt="<?php echo $product['name'] ?> image"
                                style="object-fit: cover;">
                        </div>
                        <!-- Product details -->
                        <div class="col-md-8 d-flex flex-column">
                            <div class="card-body d-flex flex-column h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title mb-0"><?php echo $product['name'] ?></h5>
                                    <h6 class="card-subtitle text-muted mb-0"><?php echo "{$product['price']}€" ?></h6>
                                </div>
                                <div class="mb-2">
                                    <span class="badge bg-primary me-1"><?php echo $product['category'] ?></span>
                                    <?php if (isset($product['subcategory'])): ?>
                                        <span class="badge bg-info"><?php echo $product['subcategory'] ?></span>
                                    <?php endif; ?>
                                </div>
                                <p class="card-text flex-grow-1 mb-3" style="overflow-y: auto;">
                                    <?php echo $product['description'] ?>
                                </p>

                                <div class="d-flex align-items-center mt-3">
                                    <!-- Recipe button -->
                                    <a href="#" type="toast-trigger" class="btn btn-primary"
                                        prod-id="<?php echo $product['prodId'] ?>"
                                        prod-name="<?php echo $product['name'] ?>">
                                        <img class="mb-1 pe-1" src="./resources/svg/document.svg" alt="Recipe icon">Recipe
                                    </a>
                                    <div class="dropdown ms-1 dropend">
                                        <!-- Edit button -->
                                        <button class="btn btn-warning dropdown-toggle mx-2" type="button"
                                            id="dropdownMenuButton-<?php echo $product['prodId']; ?>"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <img class="mb-1 pe-1" src="./resources/svg/menu-black.svg" alt="Menu icon">Edit
                                        </button>
                                        <ul class="dropdown-menu"
                                            aria-labelledby="dropdownMenuButton-<?php echo $product['prodId']; ?>">
                                            <!-- Edit product -->
                                            <li>
                                                <a class="dropdown-item edit-product" href="#"
                                                    data-product-id="<?php echo $product['prodId']; ?>"
                                                    data-bs-toggle="modal" data-bs-target="#editProductModal"
                                                    onclick='$("#saveEditProduct").attr("data-product-id", <?php echo $product["prodId"]; ?>)'>
                                                    <img class="mb-1 me-2" src="./resources/svg/modify-black.svg"
                                                        alt="Modify icon"> Modify
                                                </a>
                                            </li>
                                            <li>
                                                <!-- Delete product -->
                                                <a class="dropdown-item delete-product" href="#" style="color: red;"
                                                    data-product-id="<?php echo $product['prodId']; ?>">
                                                    <img class="mb-1 me-2" src="./resources/svg/delete-red.svg"
                                                        alt="Delete icon"> Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<!-- Add Product Modal, with appropriate form -->
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
                        <label for="newProductDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="newProductDescription" name="description"
                            required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="newProductPrice" class="form-label">Price</label>
                        <input type="number" class="form-control" id="newProductPrice" step="0.50" name="price"
                            required>
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

<!-- Edit Product Modal with appropriate form -->
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
                        <label for="editProductDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editProductDescription" name="description"
                            required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editProductPrice" class="form-label">Price</label>
                        <input type="number" class="form-control" id="editProductPrice" step="0.50" name="price"
                            required>
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

<!-- Script to update the menu products -->
<script src="./js/UpdateMenuProducts.js"></script>

<!-- Script to update the products visualization -->
<script>
    document.getElementById('product-type').addEventListener('change', function () {
        window.location.href = this.value;
    });
</script>
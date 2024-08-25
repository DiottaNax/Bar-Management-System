<?php
include_once "./components/recipe-toast.php";
$products = $dbh->getAllMenuProducts();
?>

<div class="container px-2">
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
                                    <h6 class="card-subtitle text-muted mb-0"><?php echo "{$product['price']}€" ?></h6>
                                </div>
                                <div class="mb-2">
                                    <span
                                        class="badge rounded-pill text-bg-primary me-1"><?php echo $product['category'] ?></span>
                                    <?php if (isset($product['subcategory'])): ?>
                                        <span
                                            class="badge rounded-pill text-bg-info"><?php echo $product['subcategory'] ?></span>
                                    <?php endif; ?>
                                </div>
                                <p class="card-text flex-grow-1" style="overflow-y: auto;">
                                    <?php echo $product['description'] ?>
                                </p>
                                <a href="#" type="toast-trigger" class="btn btn-primary mt-auto"
                                    prod-id="<?php echo $product['prodId'] ?>"
                                    prod-name=" <?php echo $product['name'] ?>">View Recipe</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
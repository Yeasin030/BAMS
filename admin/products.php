<?php
session_start();
include_once 'assets/db/bdconfig.php';

if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $company_id = (int) ($_SESSION['company_id'] ?? 1);
    $product_name = $_POST['product_name'];
    $product_code = $_POST['product_code'];
    $purchase_price = $_POST['purchase_price'];
    $selling_price = $_POST['selling_price'];
    $current_stock = $_POST['current_stock'];
    $status = $_POST['status'];


    $conn->query("INSERT INTO products (company_id, product_name, product_code, purchase_price, selling_price, current_stock, status)
        VALUES ('$company_id', '$product_name', '$product_code', '$purchase_price', '$selling_price', '$current_stock', '$status')");

    if ($conn->affected_rows) {
        header('Location: ../admin/products.php');
        exit;
    };
}

$company_id = (int) ($_SESSION['company_id'] ?? 1);
$products = $conn->query("SELECT * FROM products WHERE company_id = '$company_id'");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products | BizLedger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/dashboard-shared.css" rel="stylesheet">
    <link href="assets/css/inventory.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary dashboard-page inventory-page">
    <div class="container-fluid px-0">
        <div class="row min-vh-100">
            <div class="col-lg-3 col-xl-2 p-0 bg-dark">
                <?php include 'include/adminsidebar.php'; ?>
            </div>
            <main class="col-12 col-lg-9 col-xl-10 p-0">
                <?php include 'include/adminheadbar.php'; ?>
                <div class="p-3 p-md-4 p-xl-5">
                    <nav class="inventory-breadcrumb mb-3" aria-label="Breadcrumb">
                        <a href="dashboard_admin.php" class="text-decoration-none">Dashboard</a>
                        <i class="bi bi-chevron-right mx-2" aria-hidden="true"></i>
                        <span aria-current="page">Products</span>
                    </nav>

                    <section class="inventory-hero rounded-4 shadow-sm text-white p-4 p-lg-5 mb-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                            <div>
                                <span class="badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 mb-3">
                                    <i class="bi bi-box-seam me-1" aria-hidden="true"></i> Product catalogue
                                </span>
                                <h1 class="h2 fw-bold mb-2">Products</h1>
                                <p class="mb-0 text-white-50">Organize your products, pricing, and availability in one place.</p>
                            </div>
                            <button class="btn btn-light text-primary fw-semibold px-3 py-2 text-nowrap" type="button" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Add product
                            </button>
                        </div>
                    </section>

                    <div class="alert inventory-preview-note rounded-3 d-flex align-items-start gap-2 mb-4" role="note">
                        <i class="bi bi-info-circle-fill mt-1" aria-hidden="true"></i>
                        <div><strong>UI preview:</strong> The products and form below are sample content only; changes are not saved.</div>
                    </div>

                    <section class="row g-3 mb-4" aria-label="Product summary">
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4">
                                    <span class="inventory-stat-icon rounded-3 bg-primary-subtle text-primary"><i class="bi bi-boxes" aria-hidden="true"></i></span>
                                    <div>
                                        <div class="small text-secondary">Total products</div>
                                        <div class="h4 fw-bold mb-0">128</div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4">
                                    <span class="inventory-stat-icon rounded-3 bg-success-subtle text-success"><i class="bi bi-check2-circle" aria-hidden="true"></i></span>
                                    <div>
                                        <div class="small text-secondary">Active products</div>
                                        <div class="h4 fw-bold mb-0">116</div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4">
                                    <span class="inventory-stat-icon rounded-3 bg-warning-subtle text-warning-emphasis"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></span>
                                    <div>
                                        <div class="small text-secondary">Low stock</div>
                                        <div class="h4 fw-bold mb-0">8</div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4">
                                    <span class="inventory-stat-icon rounded-3 bg-info-subtle text-info-emphasis"><i class="bi bi-currency-dollar" aria-hidden="true"></i></span>
                                    <div>
                                        <div class="small text-secondary">Stock value</div>
                                        <div class="h4 fw-bold mb-0">$24,860</div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </section>

                    <section class="card inventory-panel rounded-4 overflow-hidden" id="product-catalog" aria-labelledby="product-list-title">
                        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3 p-3 p-md-4 border-bottom">
                            <div>
                                <h2 class="h5 fw-bold mb-1" id="product-list-title">Product catalogue</h2>
                                <p class="small text-secondary mb-0">Browse and manage the items your business sells</p>
                            </div>
                            <div class="d-flex flex-column flex-sm-row gap-2 inventory-filters">
                                <label class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-search" aria-hidden="true"></i></span>
                                    <input class="form-control" type="search" placeholder="Search products" aria-label="Search products">
                                </label>
                                <select class="form-select" aria-label="Filter products by status">
                                    <option selected>All statuses</option>
                                    <option>Active</option>
                                    <option>Inactive</option>
                                </select>
                                <button class="btn btn-outline-secondary text-nowrap" type="button"><i class="bi bi-download me-1" aria-hidden="true"></i>Export</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle inventory-table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-4 py-3">Product</th>
                                        <th scope="col" class="py-3">Code</th>
                                        <th scope="col" class="py-3 text-end">Purchase price</th>
                                        <th scope="col" class="py-3 text-end">Selling price</th>
                                        <th scope="col" class="py-3 text-end">In stock</th>
                                        <th scope="col" class="py-3">Status</th>
                                        <th scope="col" class="pe-4 py-3 text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $products->fetch_assoc()) { ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-3"><span class="product-avatar bg-primary-subtle text-primary"><i class="bi bi-box-seam" aria-hidden="true"></i></span>
                                                    <div class="fw-semibold"><?= htmlspecialchars($row['product_name']) ?></div>
                                                </div>
                                            </td>
                                            <td class="text-secondary"><?= htmlspecialchars($row['product_code']) ?></td>
                                            <td class="text-end">$<?= number_format($row['purchase_price'], 2) ?></td>
                                            <td class="text-end fw-semibold">$<?= number_format($row['selling_price'], 2) ?></td>
                                            <td class="text-end fw-semibold"><?= $row['current_stock'] ?></td>
                                            <td>
                                                <?php if ($row['status'] == 1) { ?>
                                                    <span class="badge rounded-pill bg-success-subtle text-success-emphasis">Active</span>
                                                <?php } else { ?>
                                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis">Inactive</span>
                                                <?php } ?>
                                            </td>
                                            <td class="pe-4 text-end"><button class="btn btn-sm btn-light text-secondary" type="button" aria-label="More actions"><i class="bi bi-three-dots" aria-hidden="true"></i></button></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 p-3 p-md-4 border-top">
                            <span class="small text-secondary">Showing 1–5 of 128 products</span>
                            <nav aria-label="Product pages">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous page"><span aria-hidden="true">&laquo;</span></a></li>
                                    <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#" aria-label="Next page"><span aria-hidden="true">&raquo;</span></a></li>
                                </ul>
                            </nav>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-bottom-0 px-4 pt-4">
                    <div>
                        <h2 class="modal-title h5 fw-bold" id="addProductModalLabel">Add a product</h2>
                        <p class="small text-secondary mb-0">Enter the basic product details.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4">

                    <form class="inventory-form" method="post">
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label" for="productName">Product name <span class="text-danger">*</span></label>
                                <input class="form-control" id="productName" name="product_name" type="text" maxlength="150" placeholder="e.g. Wireless keyboard" required>
                                <div class="form-text">Maximum 150 characters.</div>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label" for="productCode">Product code <span class="text-danger">*</span></label>
                                <input class="form-control" id="productCode" name="product_code" type="text" maxlength="50" placeholder="e.g. PRD-1006" required>
                                <div class="form-text">Must be unique within your company.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="purchasePrice">Purchase price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input class="form-control" id="purchasePrice" name="purchase_price" type="number" min="0" step="0.01" value="0.00" aria-describedby="purchasePriceHelp">
                                </div>
                                <div class="form-text" id="purchasePriceHelp">Enter a non-negative amount, up to 2 decimal places.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="sellingPrice">Selling price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input class="form-control" id="sellingPrice" name="selling_price" type="number" min="0" step="0.01" value="0.00" aria-describedby="sellingPriceHelp">
                                </div>
                                <div class="form-text" id="sellingPriceHelp">Enter a non-negative amount, up to 2 decimal places.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="currentStock">Current stock</label>
                                <input class="form-control" id="currentStock" name="current_stock" type="number" min="0" step="0.01" value="0.00" aria-describedby="currentStockHelp">
                                <div class="form-text" id="currentStockHelp">Quantity on hand; decimals are allowed.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="productStatus">Status</label>
                                <select class="form-select" id="productStatus" name="status">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary" type="submit" name="submit"><i class="bi bi-check2 me-1" aria-hidden="true"></i>Save product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
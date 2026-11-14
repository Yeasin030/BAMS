<?php
session_start();
include_once 'assets/db/bdconfig.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit;
}

if (($_SESSION['role'] ?? '') != 'admin') {
    header("Location: unauthorize.php");
    exit;
}

$company_id = (int) ($_SESSION['company_id'] ?? 1);

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $product_id = $_POST['product_id'];
    $adjustment_type = $_POST['adjustment_type'];
    $quantity = $_POST['quantity'];
    $adjustment_date = $_POST['adjustment_date'];

    if($adjustment_type == 'increase'){
        $signed_quantity = $quantity;
    } else {
        $signed_quantity = -$quantity;
    }

    $conn -> query("UPDATE products SET current_stock = current_stock + '$signed_quantity' WHERE product_id = '$product_id' AND company_id = '$company_id'");

    if($conn -> affected_rows){
        $conn -> query("INSERT INTO stock_movements (company_id, product_id, movement_type, quantity, reference_type, movement_date)
        VALUES ('$company_id', '$product_id', 'ADJUSTMENT', '$signed_quantity', 'Manual adjustment', '$adjustment_date')");

        header('Location: ../admin/stock.php');
        exit;
    };

}

$stocks = $conn -> query("SELECT * FROM products WHERE company_id = '$company_id' ORDER BY current_stock ASC");
$product_list = $conn -> query("SELECT * FROM products WHERE company_id = '$company_id' ORDER BY product_name ASC");
$movements = $conn -> query("SELECT m.*, p.product_name, p.product_code FROM stock_movements m JOIN products p ON p.product_id = m.product_id WHERE m.company_id = '$company_id' ORDER BY m.movement_date DESC, m.movement_id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stock Levels | BizLedger</title>
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
                        <a href="products.php" class="text-decoration-none">Products</a>
                        <i class="bi bi-chevron-right mx-2" aria-hidden="true"></i>
                        <span aria-current="page">Stock levels</span>
                    </nav>

                    <section class="inventory-hero stock-hero rounded-4 shadow-sm text-white p-4 p-lg-5 mb-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                            <div>
                                <span class="badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 mb-3">
                                    <i class="bi bi-boxes me-1" aria-hidden="true"></i> Inventory overview
                                </span>
                                <h1 class="h2 fw-bold mb-2">Stock levels</h1>
                                <p class="mb-0 text-white-50">Monitor on-hand quantities and spot products that need attention.</p>
                            </div>
                            <button class="btn btn-light text-primary fw-semibold px-3 py-2 text-nowrap" type="button" data-bs-toggle="modal" data-bs-target="#stockAdjustmentModal">
                                <i class="bi bi-arrow-left-right me-1" aria-hidden="true"></i>Record adjustment
                            </button>
                        </div>
                    </section>

                    <div class="alert inventory-preview-note rounded-3 d-flex align-items-start gap-2 mb-4" role="note">
                        <i class="bi bi-info-circle-fill mt-1" aria-hidden="true"></i>
                        <div><strong>UI preview:</strong> Summary cards, stock movements and the adjustment form are sample content only; adjustments are not saved.</div>
                    </div>

                    <section class="row g-3 mb-4" aria-label="Stock summary">
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-primary-subtle text-primary"><i class="bi bi-boxes" aria-hidden="true"></i></span>
                                    <div>
                                        <div class="small text-secondary">Units in stock</div>
                                        <div class="h4 fw-bold mb-0">2,486</div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-success-subtle text-success"><i class="bi bi-currency-dollar" aria-hidden="true"></i></span>
                                    <div>
                                        <div class="small text-secondary">Inventory value</div>
                                        <div class="h4 fw-bold mb-0">$24,860</div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-warning-subtle text-warning-emphasis"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></span>
                                    <div>
                                        <div class="small text-secondary">Low-stock items</div>
                                        <div class="h4 fw-bold mb-0">8</div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-danger-subtle text-danger"><i class="bi bi-x-circle" aria-hidden="true"></i></span>
                                    <div>
                                        <div class="small text-secondary">Out of stock</div>
                                        <div class="h4 fw-bold mb-0">3</div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </section>

                    <section class="card inventory-panel rounded-4 overflow-hidden mb-4" aria-labelledby="stock-levels-title">
                        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3 p-3 p-md-4 border-bottom">
                            <div>
                                <h2 class="h5 fw-bold mb-1" id="stock-levels-title">Current stock</h2>
                                <p class="small text-secondary mb-0">On-hand quantities compared with reorder levels</p>
                            </div>
                            <div class="d-flex flex-column flex-sm-row gap-2 inventory-filters">
                                <label class="input-group"><span class="input-group-text bg-white"><i class="bi bi-search" aria-hidden="true"></i></span><input class="form-control" type="search" placeholder="Search products" aria-label="Search stock"></label>
                                <select class="form-select" aria-label="Filter stock status">
                                    <option selected>All stock levels</option>
                                    <option>Low stock</option>
                                    <option>Out of stock</option>
                                    <option>In stock</option>
                                </select>
                                <button class="btn btn-outline-secondary text-nowrap" type="button"><i class="bi bi-download me-1" aria-hidden="true"></i>Export</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle inventory-table stock-table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-4 py-3">Product</th>
                                        <th scope="col" class="py-3">SKU</th>
                                        <th scope="col" class="py-3">Stock level</th>
                                        <th scope="col" class="py-3 text-end">On hand</th>
                                        <th scope="col" class="pe-4 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $stocks->fetch_assoc()) {
                                        $stock = (float) $row['current_stock'];
                                        if ($stock <= 0) {
                                            $bar = 'bg-danger';
                                            $label = 'Restock required';
                                            $badge = 'bg-danger-subtle text-danger-emphasis';
                                            $text = 'Out of stock';
                                        } elseif ($stock <= 10) {
                                            $bar = 'bg-warning';
                                            $label = 'Reorder soon';
                                            $badge = 'bg-warning-subtle text-warning-emphasis';
                                            $text = 'Low stock';
                                        } else {
                                            $bar = 'bg-success';
                                            $label = 'Healthy';
                                            $badge = 'bg-success-subtle text-success-emphasis';
                                            $text = 'In stock';
                                        }
                                        $percent = min(100, $stock);
                                    ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-3"><span class="product-avatar bg-primary-subtle text-primary"><i class="bi bi-box-seam" aria-hidden="true"></i></span>
                                                    <div class="fw-semibold"><?= htmlspecialchars($row['product_name']) ?></div>
                                                </div>
                                            </td>
                                            <td class="text-secondary"><?= htmlspecialchars($row['product_code']) ?></td>
                                            <td>
                                                <div class="stock-meter">
                                                    <div class="progress" role="progressbar" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100">
                                                        <div class="progress-bar <?= $bar ?>" style="width: <?= $percent ?>%"></div>
                                                    </div><small class="text-secondary"><?= $label ?></small>
                                                </div>
                                            </td>
                                            <td class="text-end fw-semibold"><?= $stock ?> <span class="small text-secondary fw-normal">pcs</span></td>
                                            <td class="pe-4"><span class="badge rounded-pill <?= $badge ?>"><?= $text ?></span></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 p-3 p-md-4 border-top">
                            <span class="small text-secondary">Showing 1–5 of 128 products</span>
                            <nav aria-label="Stock pages">
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

                   <section class="card inventory-panel rounded-4 overflow-hidden" aria-labelledby="movement-history-title">
                        <div class="p-3 p-md-4 border-bottom">
                            <h2 class="h5 fw-bold mb-1" id="movement-history-title">Recent stock movements</h2>
                            <p class="small text-secondary mb-0">A quick view of recent stock changes</p>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle inventory-table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-4 py-3">Product</th>
                                        <th scope="col" class="py-3">Movement</th>
                                        <th scope="col" class="py-3">Reference</th>
                                        <th scope="col" class="py-3">Date</th>
                                        <th scope="col" class="pe-4 py-3 text-end">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($m = $movements -> fetch_assoc()){
                                        $qty = (float) $m['quantity'];

                                        if($m['movement_type'] == 'IN'){
                                            $icon = 'movement-in'; $arrow = 'bi-arrow-down-left'; $badge = 'bg-success-subtle text-success-emphasis'; $text = 'Stock in';
                                            $qty_class = 'text-success'; $qty_text = '+' . $qty;
                                        } elseif($m['movement_type'] == 'OUT'){
                                            $icon = 'movement-out'; $arrow = 'bi-arrow-up-right'; $badge = 'bg-danger-subtle text-danger-emphasis'; $text = 'Stock out';
                                            $qty_class = 'text-danger'; $qty_text = '−' . abs($qty);
                                        } else {
                                            $icon = 'movement-adjust'; $arrow = 'bi-sliders'; $badge = 'bg-primary-subtle text-primary-emphasis'; $text = 'Adjustment';
                                            $qty_class = 'text-primary'; $qty_text = ($qty >= 0 ? '+' : '−') . abs($qty);
                                        }

                                        $reference = $m['reference_type'];
                                        if($m['reference_id']){
                                            $reference .= ' · ' . $m['reference_id'];
                                        }
                                    ?>
                                    <tr>
                                        <td class="ps-4 fw-semibold"><?= htmlspecialchars($m['product_name']) ?> <span class="d-block small text-secondary fw-normal"><?= htmlspecialchars($m['product_code']) ?></span></td>
                                        <td><span class="movement-icon <?= $icon ?>"><i class="bi <?= $arrow ?>" aria-hidden="true"></i></span><span class="badge rounded-pill <?= $badge ?> ms-2"><?= $text ?></span></td>
                                        <td class="text-secondary"><?= htmlspecialchars($reference ?? '') ?></td>
                                        <td class="text-secondary text-nowrap"><?= date('M d, Y', strtotime($m['movement_date'])) ?></td>
                                        <td class="pe-4 text-end fw-semibold <?= $qty_class ?>"><?= $qty_text ?> pcs</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3 p-md-4 border-top text-end"><a class="small fw-semibold text-decoration-none" href="#movement-history-title">View all movements <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i></a></div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="stockAdjustmentModal" tabindex="-1" aria-labelledby="stockAdjustmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-bottom-0 px-4 pt-4">
                    <div>
                        <h2 class="modal-title h5 fw-bold" id="stockAdjustmentModalLabel">Record stock adjustment</h2>
                        <p class="small text-secondary mb-0">Update stock to reflect a count or correction.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <form class="inventory-form" method="post">
                        <div class="mb-3"><label class="form-label" for="adjustmentProduct">Product</label>
                            <select class="form-select" id="adjustmentProduct" name="product_id" required>
                                <option value="" selected disabled>Select a product</option>
                                <?php while ($p = $product_list->fetch_assoc()) { ?>
                                    <option value="<?= $p['product_id'] ?>"><?= htmlspecialchars($p['product_name']) ?> · <?= htmlspecialchars($p['product_code']) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <fieldset class="mb-3">
                            <legend class="form-label">Adjustment type</legend>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check"><input class="form-check-input" type="radio" name="adjustment_type" value="increase" id="stockIncrease" checked><label class="form-check-label" for="stockIncrease">Add stock</label></div>
                                <div class="form-check"><input class="form-check-input" type="radio" name="adjustment_type" value="decrease" id="stockDecrease"><label class="form-check-label" for="stockDecrease">Remove stock</label></div>
                            </div>
                        </fieldset>
                        <div class="row g-3">
                            <div class="col-sm-6"><label class="form-label" for="adjustmentQuantity">Quantity</label><input class="form-control" id="adjustmentQuantity" name="quantity" type="number" min="1" placeholder="e.g. 5" required></div>
                            <div class="col-sm-6"><label class="form-label" for="adjustmentDate">Date</label><input class="form-control" id="adjustmentDate" name="adjustment_date" type="date" value="2026-11-14"></div>
                        </div>
                        <div class="mt-3"><label class="form-label" for="adjustmentNote">Reason <span class="text-secondary fw-normal">(optional)</span></label><textarea class="form-control" id="adjustmentNote" name="reason" rows="2" placeholder="Add a note for this adjustment"></textarea></div>
                        <div class="d-flex justify-content-end gap-2 mt-4"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary" type="submit" name="submit"><i class="bi bi-check2 me-1" aria-hidden="true"></i>Save adjustment</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
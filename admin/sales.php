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

$error = '';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $customer_id = $_POST['customer_id'];
    $sale_date = $_POST['sale_date'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $unit_price = $_POST['unit_price'];
    $discount = $_POST['discount'];

    $item_total = $quantity * $unit_price;
    $total_amount = $item_total - $discount;

    $stock_check = $conn -> query("SELECT current_stock FROM products WHERE product_id = '$product_id' AND company_id = '$company_id'") -> fetch_assoc();

    if(!$stock_check || $stock_check['current_stock'] < $quantity){
        $error = "Not enough stock for this product.";
    } elseif($discount > $item_total){
        $error = "Discount cannot be more than the item total.";
    } else {

        $temp_invoice = uniqid('TMP-');

        $conn -> query("INSERT INTO sales (company_id, invoice_number, customer_id, sale_date, subtotal, discount, total_amount, status)
        VALUES ('$company_id', '$temp_invoice', '$customer_id', '$sale_date', '$item_total', '$discount', '$total_amount', 'POSTED')");

        $sale_id = $conn -> insert_id;

        if($sale_id){
            $invoice_number = 'INV-' . date('Y', strtotime($sale_date)) . '-' . str_pad($sale_id, 4, '0', STR_PAD_LEFT);
            $conn -> query("UPDATE sales SET invoice_number = '$invoice_number' WHERE sale_id = '$sale_id'");

            $conn -> query("INSERT INTO sales_items (sale_id, product_id, quantity, unit_price, total)
            VALUES ('$sale_id', '$product_id', '$quantity', '$unit_price', '$item_total')");

            $conn -> query("UPDATE products SET current_stock = current_stock - '$quantity' WHERE product_id = '$product_id' AND company_id = '$company_id'");

            $conn -> query("INSERT INTO stock_movements (company_id, product_id, movement_type, quantity, reference_type, reference_id, movement_date)
            VALUES ('$company_id', '$product_id', 'OUT', '$quantity', 'Sale', '$sale_id', '$sale_date')");

            header('Location: ../admin/sales.php');
            exit;
        }

    }

}

$sales = $conn -> query("SELECT s.*, c.customer_name, (SELECT COUNT(*) FROM sales_items si WHERE si.sale_id = s.sale_id) AS items
FROM sales s LEFT JOIN customers c ON c.customer_id = s.customer_id
WHERE s.company_id = '$company_id' ORDER BY s.sale_date DESC, s.sale_id DESC");

$customer_list = $conn -> query("SELECT * FROM customers WHERE company_id = '$company_id' AND status = 1 ORDER BY customer_name ASC");
$product_list = $conn -> query("SELECT * FROM products WHERE company_id = '$company_id' AND status = 1 ORDER BY product_name ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales | BizLedger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/dashboard-shared.css" rel="stylesheet">
    <link href="assets/css/inventory.css" rel="stylesheet">
    <link href="assets/css/sales-invoices.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary dashboard-page inventory-page commerce-page">
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
                        <span aria-current="page">Sales</span>
                    </nav>

                    <?php if($error){ ?>
                        <div class="alert alert-danger rounded-3 mb-4"><?= htmlspecialchars($error) ?></div>
                    <?php } ?>

                    <section class="inventory-hero commerce-hero sales-hero rounded-4 shadow-sm text-white p-4 p-lg-5 mb-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                            <div>
                                <span class="badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 mb-3"><i class="bi bi-graph-up-arrow me-1" aria-hidden="true"></i> Sales overview</span>
                                <h1 class="h2 fw-bold mb-2">Sales</h1>
                                <p class="mb-0 text-white-50">Track customer orders, revenue, and payment progress.</p>
                            </div>
                            <button class="btn btn-light text-primary fw-semibold px-3 py-2 text-nowrap" type="button" data-bs-toggle="modal" data-bs-target="#newSaleModal">
                                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>New sale
                            </button>
                        </div>
                    </section>

                    <section class="row g-3 mb-4" aria-label="Sales summary">
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-primary-subtle text-primary"><i class="bi bi-receipt" aria-hidden="true"></i></span><div><div class="small text-secondary">Sales this month</div><div class="h4 fw-bold mb-0">$48,290</div><div class="small text-success mt-1"><i class="bi bi-arrow-up-right" aria-hidden="true"></i> 12.8% from last month</div></div></div></article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-info-subtle text-info-emphasis"><i class="bi bi-cart-check" aria-hidden="true"></i></span><div><div class="small text-secondary">Total orders</div><div class="h4 fw-bold mb-0">124</div><div class="small text-secondary mt-1">This month</div></div></div></article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-success-subtle text-success"><i class="bi bi-check2-circle" aria-hidden="true"></i></span><div><div class="small text-secondary">Collected</div><div class="h4 fw-bold text-success mb-0">$36,740</div><div class="small text-secondary mt-1">76% of sales</div></div></div></article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-warning-subtle text-warning-emphasis"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span><div><div class="small text-secondary">Outstanding</div><div class="h4 fw-bold text-warning-emphasis mb-0">$11,550</div><div class="small text-secondary mt-1">Across 18 invoices</div></div></div></article>
                        </div>
                    </section>

                    <section class="card inventory-panel rounded-4 overflow-hidden" aria-labelledby="sales-list-title">
                        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3 p-3 p-md-4 border-bottom">
                            <div><h2 class="h5 fw-bold mb-1" id="sales-list-title">Recent sales</h2><p class="small text-secondary mb-0">Review orders and their payment status</p></div>
                            <div class="d-flex flex-column flex-sm-row gap-2 inventory-filters">
                                <label class="input-group"><span class="input-group-text bg-white"><i class="bi bi-search" aria-hidden="true"></i></span><input class="form-control" type="search" placeholder="Search sales" aria-label="Search sales"></label>
                                <select class="form-select" aria-label="Filter sales by status"><option selected>All statuses</option><option>Paid</option><option>Partially paid</option><option>Posted</option><option>Draft</option></select>
                                <button class="btn btn-outline-secondary text-nowrap" type="button"><i class="bi bi-calendar3 me-1" aria-hidden="true"></i>Date range</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle inventory-table commerce-table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-4 py-3">Invoice</th>
                                        <th scope="col" class="py-3">Customer</th>
                                        <th scope="col" class="py-3">Date</th>
                                        <th scope="col" class="py-3 text-center">Items</th>
                                        <th scope="col" class="pe-4 py-3 text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $sales -> fetch_assoc()){ ?>
                                    <tr>
                                        <td class="ps-4"><div class="fw-semibold"><?= htmlspecialchars($row['invoice_number']) ?></div><div class="small text-secondary">Sale #<?= $row['sale_id'] ?></div></td>
                                        <td><div class="customer-cell"><span class="customer-avatar bg-primary-subtle text-primary"><?= htmlspecialchars(strtoupper(substr($row['customer_name'] ?? '?', 0, 2))) ?></span><span class="fw-medium"><?= htmlspecialchars($row['customer_name'] ?? 'Unknown') ?></span></div></td>
                                        <td class="text-secondary text-nowrap"><?= date('M d, Y', strtotime($row['sale_date'])) ?></td>
                                        <td class="text-center"><?= $row['items'] ?></td>
                                        <td class="pe-4 text-end fw-semibold">$<?= number_format($row['total_amount'], 2) ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 p-3 p-md-4 border-top">
                            <span class="small text-secondary">Showing 1–5 of 124 sales</span>
                            <nav aria-label="Sales pages"><ul class="pagination pagination-sm mb-0"><li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous page"><span aria-hidden="true">&laquo;</span></a></li><li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li><li class="page-item"><a class="page-link" href="#">2</a></li><li class="page-item"><a class="page-link" href="#">3</a></li><li class="page-item"><a class="page-link" href="#" aria-label="Next page"><span aria-hidden="true">&raquo;</span></a></li></ul></nav>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="newSaleModal" tabindex="-1" aria-labelledby="newSaleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-bottom-0 px-4 pt-4"><div><h2 class="modal-title h5 fw-bold" id="newSaleModalLabel">Create a sale</h2><p class="small text-secondary mb-0">Add customer and item details for this sale.</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body px-4 pb-4">
                    <form class="commerce-form" method="post">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label" for="saleCustomer">Customer</label>
                                <select class="form-select" id="saleCustomer" name="customer_id" required>
                                    <option value="" selected disabled>Select a customer</option>
                                    <?php while($c = $customer_list -> fetch_assoc()){ ?>
                                        <option value="<?= $c['customer_id'] ?>"><?= htmlspecialchars($c['customer_name']) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6"><label class="form-label" for="saleDate">Sale date</label><input class="form-control" id="saleDate" name="sale_date" type="date" value="<?= date('Y-m-d') ?>" required></div>
                            <div class="col-md-8"><label class="form-label" for="saleProduct">Product</label>
                                <select class="form-select" id="saleProduct" name="product_id" required>
                                    <option value="" selected disabled>Select a product</option>
                                    <?php while($p = $product_list -> fetch_assoc()){ ?>
                                        <option value="<?= $p['product_id'] ?>"><?= htmlspecialchars($p['product_name']) ?> · <?= htmlspecialchars($p['product_code']) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-4"><label class="form-label" for="saleQuantity">Quantity</label><input class="form-control" id="saleQuantity" name="quantity" type="number" min="1" value="1" required></div>
                            <div class="col-md-6"><label class="form-label" for="salePrice">Unit price</label><div class="input-group"><span class="input-group-text">$</span><input class="form-control" id="salePrice" name="unit_price" type="number" min="0" step="0.01" placeholder="0.00" required></div></div>
                            <div class="col-md-6"><label class="form-label" for="saleDiscount">Discount</label><div class="input-group"><span class="input-group-text">$</span><input class="form-control" id="saleDiscount" name="discount" type="number" min="0" step="0.01" value="0.00"></div></div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary" type="submit" name="submit"><i class="bi bi-check2 me-1" aria-hidden="true"></i>Save sale</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
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

    $customer_name = $_POST['customer_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $status = $_POST['status'];

    $conn -> query("INSERT INTO customers (company_id, customer_name, phone, email, address, status)
    VALUES ('$company_id', '$customer_name', '$phone', '$email', '$address', '$status')");

    if($conn -> affected_rows){
        header('Location: ../admin/customers.php');
        exit;
    };

}

$customers = $conn -> query("SELECT c.*,
(SELECT COUNT(*) FROM sales s WHERE s.customer_id = c.customer_id) AS orders,
(SELECT COALESCE(SUM(s.total_amount), 0) FROM sales s WHERE s.customer_id = c.customer_id) AS lifetime_value,
(SELECT MAX(s.sale_date) FROM sales s WHERE s.customer_id = c.customer_id) AS last_order
FROM customers c WHERE c.company_id = '$company_id' ORDER BY c.customer_name ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customers | BizLedger</title>
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
                        <a href="sales.php" class="text-decoration-none">Sales</a>
                        <i class="bi bi-chevron-right mx-2" aria-hidden="true"></i>
                        <span aria-current="page">Customers</span>
                    </nav>

                    <section class="inventory-hero commerce-hero customer-hero rounded-4 shadow-sm text-white p-4 p-lg-5 mb-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                            <div>
                                <span class="badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 mb-3"><i class="bi bi-people me-1" aria-hidden="true"></i> Customer directory</span>
                                <h1 class="h2 fw-bold mb-2">Customers</h1>
                                <p class="mb-0 text-white-50">Keep customer contact details and relationships organized.</p>
                            </div>
                            <button class="btn btn-light text-primary fw-semibold px-3 py-2 text-nowrap" type="button" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                <i class="bi bi-person-plus me-1" aria-hidden="true"></i>Add customer
                            </button>
                        </div>
                    </section>

                    <section class="row g-3 mb-4" aria-label="Customer summary">
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-primary-subtle text-primary"><i class="bi bi-people" aria-hidden="true"></i></span><div><div class="small text-secondary">Total customers</div><div class="h4 fw-bold mb-0">248</div><div class="small text-secondary mt-1">Across your business</div></div></div></article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-success-subtle text-success"><i class="bi bi-person-check" aria-hidden="true"></i></span><div><div class="small text-secondary">Active customers</div><div class="h4 fw-bold mb-0">236</div><div class="small text-secondary mt-1">95% of customers</div></div></div></article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-info-subtle text-info-emphasis"><i class="bi bi-person-plus" aria-hidden="true"></i></span><div><div class="small text-secondary">Added this month</div><div class="h4 fw-bold mb-0">18</div><div class="small text-success mt-1"><i class="bi bi-arrow-up-right" aria-hidden="true"></i> 6 more than last month</div></div></div></article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-warning-subtle text-warning-emphasis"><i class="bi bi-arrow-repeat" aria-hidden="true"></i></span><div><div class="small text-secondary">Returning customers</div><div class="h4 fw-bold mb-0">82%</div><div class="small text-secondary mt-1">Purchased more than once</div></div></div></article>
                        </div>
                    </section>

                    <section class="card inventory-panel rounded-4 overflow-hidden" aria-labelledby="customer-list-title">
                        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3 p-3 p-md-4 border-bottom">
                            <div><h2 class="h5 fw-bold mb-1" id="customer-list-title">Customer directory</h2><p class="small text-secondary mb-0">Contact details and recent activity</p></div>
                            <div class="d-flex flex-column flex-sm-row gap-2 inventory-filters">
                                <label class="input-group"><span class="input-group-text bg-white"><i class="bi bi-search" aria-hidden="true"></i></span><input class="form-control" type="search" placeholder="Search customers" aria-label="Search customers"></label>
                                <select class="form-select" aria-label="Filter customers by status"><option selected>All statuses</option><option>Active</option><option>Inactive</option></select>
                                <button class="btn btn-outline-secondary text-nowrap" type="button"><i class="bi bi-download me-1" aria-hidden="true"></i>Export</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle inventory-table commerce-table customer-table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-4 py-3">Customer</th>
                                        <th scope="col" class="py-3">Phone</th>
                                        <th scope="col" class="py-3">Address</th>
                                        <th scope="col" class="py-3 text-center">Orders</th>
                                        <th scope="col" class="py-3 text-end">Lifetime value</th>
                                        <th scope="col" class="py-3">Last order</th>
                                        <th scope="col" class="py-3">Status</th>
                                        <th scope="col" class="pe-4 py-3 text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $customers -> fetch_assoc()){ ?>
                                    <tr>
                                        <td class="ps-4"><div class="customer-cell"><span class="customer-avatar bg-primary-subtle text-primary"><?= htmlspecialchars(strtoupper(substr($row['customer_name'], 0, 2))) ?></span><div><div class="fw-semibold"><?= htmlspecialchars($row['customer_name']) ?></div><div class="small text-secondary"><?= htmlspecialchars($row['email'] ?? '') ?></div></div></div></td>
                                        <td class="text-secondary text-nowrap"><?= htmlspecialchars($row['phone'] ?? '') ?></td>
                                        <td class="text-secondary customer-address"><?= htmlspecialchars($row['address'] ?? '') ?></td>
                                        <td class="text-center"><?= $row['orders'] ?></td>
                                        <td class="text-end fw-semibold">$<?= number_format($row['lifetime_value'], 2) ?></td>
                                        <td class="text-secondary text-nowrap"><?= $row['last_order'] ? date('M d, Y', strtotime($row['last_order'])) : '-' ?></td>
                                        <td>
                                            <?php if($row['status'] == 1){ ?>
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
                            <span class="small text-secondary">Showing 1–5 of 248 customers</span>
                            <nav aria-label="Customer pages"><ul class="pagination pagination-sm mb-0"><li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous page"><span aria-hidden="true">&laquo;</span></a></li><li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li><li class="page-item"><a class="page-link" href="#">2</a></li><li class="page-item"><a class="page-link" href="#">3</a></li><li class="page-item"><a class="page-link" href="#" aria-label="Next page"><span aria-hidden="true">&raquo;</span></a></li></ul></nav>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-bottom-0 px-4 pt-4"><div><h2 class="modal-title h5 fw-bold" id="addCustomerModalLabel">Add a customer</h2><p class="small text-secondary mb-0">Save the customer's contact information.</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body px-4 pb-4">
                    <form class="commerce-form" method="post">
                        <div class="row g-3">
                            <div class="col-md-7"><label class="form-label" for="customerName">Customer name <span class="text-danger">*</span></label><input class="form-control" id="customerName" name="customer_name" type="text" maxlength="150" placeholder="e.g. Ayesha Malik" required></div>
                            <div class="col-md-5"><label class="form-label" for="customerPhone">Phone</label><input class="form-control" id="customerPhone" name="phone" type="tel" maxlength="30" placeholder="+880 1XXX-XXXXXX"></div>
                            <div class="col-md-6"><label class="form-label" for="customerEmail">Email</label><input class="form-control" id="customerEmail" name="email" type="email" maxlength="150" placeholder="name@example.com"></div>
                            <div class="col-md-6"><label class="form-label" for="customerStatus">Status</label><select class="form-select" id="customerStatus" name="status"><option value="1" selected>Active</option><option value="0">Inactive</option></select></div>
                            <div class="col-12"><label class="form-label" for="customerAddress">Address</label><textarea class="form-control" id="customerAddress" name="address" rows="3" placeholder="Street, area, city"></textarea></div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary" type="submit" name="submit"><i class="bi bi-person-plus me-1" aria-hidden="true"></i>Save customer</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
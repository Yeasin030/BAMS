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

$invoices = $conn->query("SELECT s.*, c.customer_name
FROM sales s LEFT JOIN customers c ON c.customer_id = s.customer_id
WHERE s.company_id = '$company_id' ORDER BY s.sale_date DESC, s.sale_id DESC");

$summary = $conn->query("SELECT
COUNT(*) AS total_count,
COALESCE(SUM(CASE WHEN status = 'PAID' THEN total_amount END), 0) AS paid_amount,
SUM(status = 'PAID') AS paid_count,
COALESCE(SUM(CASE WHEN status IN ('POSTED', 'PARTIAL') THEN total_amount END), 0) AS awaiting_amount,
SUM(status IN ('POSTED', 'PARTIAL')) AS awaiting_count,
COALESCE(SUM(CASE WHEN status = 'DRAFT' THEN total_amount END), 0) AS draft_amount,
SUM(status = 'DRAFT') AS draft_count
FROM sales WHERE company_id = '$company_id'")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoices | BizLedger</title>
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
                        <span aria-current="page">Invoices</span>
                    </nav>

                    <section class="inventory-hero commerce-hero invoice-hero rounded-4 shadow-sm text-white p-4 p-lg-5 mb-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                            <div>
                                <span class="badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 mb-3"><i class="bi bi-file-earmark-text me-1" aria-hidden="true"></i> Billing workspace</span>
                                <h1 class="h2 fw-bold mb-2">Invoices</h1>
                                <p class="mb-0 text-white-50">Keep an eye on payments and outstanding balances.</p>
                            </div>
                            <a class="btn btn-light text-primary fw-semibold px-3 py-2 text-nowrap" href="sales.php">
                                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Create invoice
                            </a>
                        </div>
                    </section>

                    <section class="row g-3 mb-4" aria-label="Invoice summary">
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-primary-subtle text-primary"><i class="bi bi-files" aria-hidden="true"></i></span>
                                    <div>
                                        <div class="small text-secondary">Total invoices</div>
                                        <div class="h4 fw-bold mb-0"><?= (int) $summary['total_count'] ?></div>
                                        <div class="small text-secondary mt-1">All time</div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-success-subtle text-success"><i class="bi bi-check-circle" aria-hidden="true"></i></span>
                                    <div>
                                        <div class="small text-secondary">Paid</div>
                                        <div class="h4 fw-bold text-success mb-0">$<?= number_format($summary['paid_amount'], 2) ?></div>
                                        <div class="small text-secondary mt-1"><?= (int) $summary['paid_count'] ?> invoices</div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-warning-subtle text-warning-emphasis"><i class="bi bi-clock-history" aria-hidden="true"></i></span>
                                    <div>
                                        <div class="small text-secondary">Awaiting payment</div>
                                        <div class="h4 fw-bold text-warning-emphasis mb-0">$<?= number_format($summary['awaiting_amount'], 2) ?></div>
                                        <div class="small text-secondary mt-1"><?= (int) $summary['awaiting_count'] ?> invoices</div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-secondary-subtle text-secondary-emphasis"><i class="bi bi-pencil-square" aria-hidden="true"></i></span>
                                    <div>
                                        <div class="small text-secondary">Draft</div>
                                        <div class="h4 fw-bold mb-0">$<?= number_format($summary['draft_amount'], 2) ?></div>
                                        <div class="small text-secondary mt-1"><?= (int) $summary['draft_count'] ?> invoices</div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </section>

                    <section class="card inventory-panel rounded-4 overflow-hidden" aria-labelledby="invoice-list-title">
                        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3 p-3 p-md-4 border-bottom">
                            <div>
                                <h2 class="h5 fw-bold mb-1" id="invoice-list-title">Invoice register</h2>
                                <p class="small text-secondary mb-0">Search invoices and check each payment status</p>
                            </div>
                            <div class="d-flex flex-column flex-sm-row gap-2 inventory-filters">
                                <label class="input-group"><span class="input-group-text bg-white"><i class="bi bi-search" aria-hidden="true"></i></span><input class="form-control" type="search" placeholder="Invoice or customer" aria-label="Search invoices"></label>
                                <select class="form-select" aria-label="Filter invoices by status">
                                    <option selected>All statuses</option>
                                    <option>Paid</option>
                                    <option>Partial</option>
                                    <option>Posted</option>
                                    <option>Draft</option>
                                    <option>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle inventory-table commerce-table invoice-table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-4 py-3">Invoice</th>
                                        <th scope="col" class="py-3">Customer</th>
                                        <th scope="col" class="py-3">Issue date</th>
                                        <th scope="col" class="py-3 text-end">Total</th>
                                        <th scope="col" class="pe-4 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $invoices->fetch_assoc()) {
                                        if ($row['status'] == 'PAID') {
                                            $badge = 'bg-success-subtle text-success-emphasis';
                                            $text = 'Paid';
                                        } elseif ($row['status'] == 'PARTIAL') {
                                            $badge = 'bg-warning-subtle text-warning-emphasis';
                                            $text = 'Partial';
                                        } elseif ($row['status'] == 'POSTED') {
                                            $badge = 'bg-primary-subtle text-primary-emphasis';
                                            $text = 'Posted';
                                        } elseif ($row['status'] == 'CANCEL') {
                                            $badge = 'bg-danger-subtle text-danger-emphasis';
                                            $text = 'Cancelled';
                                        } else {
                                            $badge = 'bg-secondary-subtle text-secondary-emphasis';
                                            $text = 'Draft';
                                        }
                                    ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-semibold"><?= htmlspecialchars($row['invoice_number']) ?></div>
                                                <div class="small text-secondary">Sale #<?= $row['sale_id'] ?></div>
                                            </td>
                                            <td>
                                                <div class="customer-cell"><span class="customer-avatar bg-primary-subtle text-primary"><?= htmlspecialchars(strtoupper(substr($row['customer_name'] ?? '?', 0, 2))) ?></span><span class="fw-medium"><?= htmlspecialchars($row['customer_name'] ?? 'Unknown') ?></span></div>
                                            </td>
                                            <td class="text-secondary text-nowrap"><?= date('M d, Y', strtotime($row['sale_date'])) ?></td>
                                            <td class="text-end fw-semibold">$<?= number_format($row['total_amount'], 2) ?></td>
                                            <td class="pe-4"><span class="badge rounded-pill <?= $badge ?>"><?= $text ?></span></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 p-3 p-md-4 border-top">
                            <span class="small text-secondary">Showing <?= (int) $summary['total_count'] ?> invoices</span>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
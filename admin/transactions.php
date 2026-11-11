<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit;
}

if (($_SESSION['role'] ?? '') != 'admin') {
    header("Location: unauthorize.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Transactions | BizLedger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/dashboard-shared.css" rel="stylesheet">
    <link href="assets/css/transactions.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary dashboard-page transaction-page">
    <div class="container-fluid px-0">
        <div class="row min-vh-100">
            <div class="col-lg-3 col-xl-2 p-0 bg-dark">
                <?php include 'include/adminsidebar.php'; ?>
            </div>
            <main class="col-12 col-lg-9 col-xl-10 p-0">
                <?php include 'include/adminheadbar.php'; ?>
                <div class="p-3 p-md-4 p-xl-5">
                    <section class="transaction-hero rounded-4 shadow-sm text-white p-4 p-lg-5 mb-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                            <div>
                                <span class="badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 mb-3">
                                    <i class="bi bi-arrow-left-right me-1" aria-hidden="true"></i> Financial activity
                                </span>
                                <h1 class="h2 fw-bold mb-2">All transactions</h1>
                                <p class="mb-0 text-white-50">Review and keep track of money moving through your business.</p>
                            </div>
                            <a class="btn btn-light text-primary fw-semibold px-3 py-2 text-nowrap" href="add_transaction.php">
                                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Add transaction
                            </a>
                        </div>
                    </section>

                    <div class="alert alert-info border-0 rounded-3 d-flex align-items-start gap-2 mb-4" role="note">
                        <i class="bi bi-info-circle-fill mt-1" aria-hidden="true"></i>
                        <div><strong>UI preview:</strong> These example transactions are for display only and are not connected to saved records.</div>
                    </div>

                    <section class="row g-3 mb-4" aria-label="Transaction summary">
                        <div class="col-12 col-sm-6 col-xl-4">
                            <article class="card transaction-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4">
                                    <span class="transaction-stat-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary fs-4"><i class="bi bi-receipt" aria-hidden="true"></i></span>
                                    <div><div class="small text-secondary">Transactions this month</div><div class="h4 fw-bold mb-0">24</div></div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-4">
                            <article class="card transaction-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4">
                                    <span class="transaction-stat-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-success-subtle text-success fs-4"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i></span>
                                    <div><div class="small text-secondary">Money in</div><div class="h4 fw-bold text-success mb-0">$12,840.00</div></div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-4">
                            <article class="card transaction-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4">
                                    <span class="transaction-stat-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-danger-subtle text-danger fs-4"><i class="bi bi-graph-down-arrow" aria-hidden="true"></i></span>
                                    <div><div class="small text-secondary">Money out</div><div class="h4 fw-bold text-danger mb-0">$4,265.50</div></div>
                                </div>
                            </article>
                        </div>
                    </section>

                    <section class="card transaction-panel rounded-4 overflow-hidden" aria-labelledby="transaction-list-title">
                        <div class="card-body p-0">
                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 p-3 p-md-4 border-bottom">
                                <div>
                                    <h2 class="h5 fw-bold mb-1" id="transaction-list-title">Transaction history</h2>
                                    <p class="small text-secondary mb-0">A quick overview of recent income and expenses</p>
                                </div>
                                <div class="d-flex flex-column flex-sm-row gap-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-search" aria-hidden="true"></i></span>
                                        <input class="form-control" type="search" placeholder="Search transactions" aria-label="Search transactions">
                                    </div>
                                    <select class="form-select" aria-label="Filter by transaction type">
                                        <option selected>All types</option>
                                        <option>Income</option>
                                        <option>Expense</option>
                                    </select>
                                    <button class="btn btn-outline-secondary text-nowrap" type="button" aria-label="Choose date range">
                                        <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>Date
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle transaction-table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="ps-4 py-3 small text-uppercase text-secondary">Transaction</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary">Category</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary">Date</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary">Type</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary text-end">Amount</th>
                                            <th scope="col" class="pe-4 py-3 small text-uppercase text-secondary text-end">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="ps-4 py-3"><div class="fw-semibold">Website project payment</div><div class="small text-secondary">TRX-2025-024</div></td>
                                            <td class="text-secondary">Client payment</td>
                                            <td class="text-secondary text-nowrap">Nov 10, 2026</td>
                                            <td><span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2">Income</span></td>
                                            <td class="text-end fw-semibold text-success">+$2,400.00</td>
                                            <td class="pe-4 text-end"><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Completed</span></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4 py-3"><div class="fw-semibold">Office supplies</div><div class="small text-secondary">TRX-2025-023</div></td>
                                            <td class="text-secondary">Office expenses</td>
                                            <td class="text-secondary text-nowrap">Nov 08, 2026</td>
                                            <td><span class="badge rounded-pill bg-danger-subtle text-danger-emphasis px-3 py-2">Expense</span></td>
                                            <td class="text-end fw-semibold text-danger">−$185.50</td>
                                            <td class="pe-4 text-end"><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Completed</span></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4 py-3"><div class="fw-semibold">Monthly hosting plan</div><div class="small text-secondary">TRX-2025-022</div></td>
                                            <td class="text-secondary">Software &amp; services</td>
                                            <td class="text-secondary text-nowrap">Nov 06, 2026</td>
                                            <td><span class="badge rounded-pill bg-danger-subtle text-danger-emphasis px-3 py-2">Expense</span></td>
                                            <td class="text-end fw-semibold text-danger">−$49.00</td>
                                            <td class="pe-4 text-end"><span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Pending</span></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4 py-3"><div class="fw-semibold">Consulting services</div><div class="small text-secondary">TRX-2025-021</div></td>
                                            <td class="text-secondary">Service income</td>
                                            <td class="text-secondary text-nowrap">Nov 04, 2026</td>
                                            <td><span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2">Income</span></td>
                                            <td class="text-end fw-semibold text-success">+$875.00</td>
                                            <td class="pe-4 text-end"><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Completed</span></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4 py-3"><div class="fw-semibold">Team lunch</div><div class="small text-secondary">TRX-2025-020</div></td>
                                            <td class="text-secondary">Meals</td>
                                            <td class="text-secondary text-nowrap">Nov 02, 2026</td>
                                            <td><span class="badge rounded-pill bg-danger-subtle text-danger-emphasis px-3 py-2">Expense</span></td>
                                            <td class="text-end fw-semibold text-danger">−$126.75</td>
                                            <td class="pe-4 text-end"><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Completed</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 p-3 p-md-4 border-top">
                                <span class="small text-secondary">Showing 1–5 of 24 transactions</span>
                                <nav aria-label="Transaction pages">
                                    <ul class="pagination pagination-sm mb-0">
                                        <li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous page"><span aria-hidden="true">&laquo;</span></a></li>
                                        <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#" aria-label="Next page"><span aria-hidden="true">&raquo;</span></a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

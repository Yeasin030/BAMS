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
    <title>Chart of Accounts | BizLedger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/dashboard-shared.css" rel="stylesheet">
    <link href="assets/css/accounts.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary dashboard-page account-page">
    <div class="container-fluid px-0">
        <div class="row min-vh-100">
            <div class="col-lg-3 col-xl-2 p-0 bg-dark">
                <?php include 'include/adminsidebar.php'; ?>
            </div>
            <main class="col-12 col-lg-9 col-xl-10 p-0">
                <?php include 'include/adminheadbar.php'; ?>
                <div class="p-3 p-md-4 p-xl-5">
                    <section class="account-hero rounded-4 shadow-sm text-white p-4 p-lg-5 mb-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                            <div>
                                <span class="badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 mb-3">
                                    <i class="bi bi-diagram-3 me-1" aria-hidden="true"></i> Account structure
                                </span>
                                <h1 class="h2 fw-bold mb-2">Chart of accounts</h1>
                                <p class="mb-0 text-white-50">Organize the accounts used to track your business finances.</p>
                            </div>
                            <button class="btn btn-light text-primary fw-semibold px-3 py-2 text-nowrap" type="button">
                                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Add account
                            </button>
                        </div>
                    </section>

                    <div class="alert alert-info border-0 rounded-3 d-flex align-items-start gap-2 mb-4" role="note">
                        <i class="bi bi-info-circle-fill mt-1" aria-hidden="true"></i>
                        <div><strong>UI preview:</strong> The account list and balances below are examples and are not connected to saved records.</div>
                    </div>

                    <section class="row g-3 mb-4" aria-label="Account summary">
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card account-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4">
                                    <span class="account-stat-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary fs-4"><i class="bi bi-diagram-3" aria-hidden="true"></i></span>
                                    <div><div class="small text-secondary">Total accounts</div><div class="h4 fw-bold mb-0">18</div></div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card account-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4">
                                    <span class="account-stat-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-success-subtle text-success fs-4"><i class="bi bi-safe" aria-hidden="true"></i></span>
                                    <div><div class="small text-secondary">Assets</div><div class="h4 fw-bold mb-0">5 accounts</div></div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card account-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4">
                                    <span class="account-stat-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-warning-subtle text-warning fs-4"><i class="bi bi-credit-card" aria-hidden="true"></i></span>
                                    <div><div class="small text-secondary">Liabilities</div><div class="h4 fw-bold mb-0">3 accounts</div></div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card account-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4">
                                    <span class="account-stat-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-info-subtle text-info fs-4"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i></span>
                                    <div><div class="small text-secondary">Income &amp; expenses</div><div class="h4 fw-bold mb-0">10 accounts</div></div>
                                </div>
                            </article>
                        </div>
                    </section>

                    <section class="card account-panel rounded-4 overflow-hidden" aria-labelledby="accounts-title">
                        <div class="card-body p-0">
                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 p-3 p-md-4 border-bottom">
                                <div>
                                    <h2 class="h5 fw-bold mb-1" id="accounts-title">Your accounts</h2>
                                    <p class="small text-secondary mb-0">Browse accounts by type and view their opening balances</p>
                                </div>
                                <div class="account-toolbar d-flex flex-column flex-sm-row gap-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-search" aria-hidden="true"></i></span>
                                        <input class="form-control" type="search" placeholder="Search accounts" aria-label="Search accounts">
                                    </div>
                                    <select class="form-select" aria-label="Filter by account type">
                                        <option selected>All account types</option>
                                        <option>Assets</option>
                                        <option>Liabilities</option>
                                        <option>Equity</option>
                                        <option>Revenue</option>
                                        <option>Expenses</option>
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle account-table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="ps-4 py-3 small text-uppercase text-secondary">Account</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary">Type</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary text-end">Opening balance</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary">Status</th>
                                            <th scope="col" class="pe-4 py-3 small text-uppercase text-secondary text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="table-light">
                                            <td class="ps-4 py-3" colspan="5"><span class="account-type-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-success-subtle text-success me-2"><i class="bi bi-safe" aria-hidden="true"></i></span><span class="fw-bold">Assets</span><span class="small text-secondary ms-2">5 accounts</span></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4 py-3"><span class="fw-semibold">1000 · Cash and cash equivalents</span></td>
                                            <td><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Asset</span></td>
                                            <td class="text-end fw-semibold">$8,500.00</td>
                                            <td><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Active</span></td>
                                            <td class="pe-4 text-end"><a class="btn btn-sm btn-light text-primary" href="ledgers.php" aria-label="View Cash and cash equivalents ledger" title="View ledger"><i class="bi bi-journal-text" aria-hidden="true"></i></a><button class="btn btn-sm btn-light text-secondary ms-1" type="button" aria-label="More actions" title="More actions"><i class="bi bi-three-dots" aria-hidden="true"></i></button></td>
                                        </tr>
                                        <tr>
                                            <td class="account-child py-3"><span class="text-secondary me-2">↳</span><span class="fw-semibold">1010 · Business checking</span></td>
                                            <td><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Asset</span></td>
                                            <td class="text-end fw-semibold">$6,250.00</td>
                                            <td><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Active</span></td>
                                            <td class="pe-4 text-end"><a class="btn btn-sm btn-light text-primary" href="ledgers.php" aria-label="View Business checking ledger" title="View ledger"><i class="bi bi-journal-text" aria-hidden="true"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td class="account-child py-3"><span class="text-secondary me-2">↳</span><span class="fw-semibold">1020 · Petty cash</span></td>
                                            <td><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Asset</span></td>
                                            <td class="text-end fw-semibold">$2,250.00</td>
                                            <td><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Active</span></td>
                                            <td class="pe-4 text-end"><a class="btn btn-sm btn-light text-primary" href="ledgers.php" aria-label="View Petty cash ledger" title="View ledger"><i class="bi bi-journal-text" aria-hidden="true"></i></a></td>
                                        </tr>
                                        <tr class="table-light">
                                            <td class="ps-4 py-3" colspan="5"><span class="account-type-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-warning-subtle text-warning me-2"><i class="bi bi-credit-card" aria-hidden="true"></i></span><span class="fw-bold">Liabilities</span><span class="small text-secondary ms-2">3 accounts</span></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4 py-3"><span class="fw-semibold">2000 · Accounts payable</span></td>
                                            <td><span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Liability</span></td>
                                            <td class="text-end fw-semibold">$1,840.00</td>
                                            <td><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Active</span></td>
                                            <td class="pe-4 text-end"><a class="btn btn-sm btn-light text-primary" href="ledgers.php" aria-label="View Accounts payable ledger" title="View ledger"><i class="bi bi-journal-text" aria-hidden="true"></i></a></td>
                                        </tr>
                                        <tr class="table-light">
                                            <td class="ps-4 py-3" colspan="5"><span class="account-type-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary me-2"><i class="bi bi-pie-chart" aria-hidden="true"></i></span><span class="fw-bold">Equity</span><span class="small text-secondary ms-2">2 accounts</span></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4 py-3"><span class="fw-semibold">3000 · Owner's equity</span></td>
                                            <td><span class="badge rounded-pill bg-primary-subtle text-primary-emphasis">Equity</span></td>
                                            <td class="text-end fw-semibold">$12,000.00</td>
                                            <td><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Active</span></td>
                                            <td class="pe-4 text-end"><a class="btn btn-sm btn-light text-primary" href="ledgers.php" aria-label="View Owner's equity ledger" title="View ledger"><i class="bi bi-journal-text" aria-hidden="true"></i></a></td>
                                        </tr>
                                        <tr class="table-light">
                                            <td class="ps-4 py-3" colspan="5"><span class="account-type-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-info-subtle text-info me-2"><i class="bi bi-cash-stack" aria-hidden="true"></i></span><span class="fw-bold">Revenue</span><span class="small text-secondary ms-2">4 accounts</span></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4 py-3"><span class="fw-semibold">4000 · Service revenue</span></td>
                                            <td><span class="badge rounded-pill bg-info-subtle text-info-emphasis">Revenue</span></td>
                                            <td class="text-end fw-semibold">$0.00</td>
                                            <td><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Active</span></td>
                                            <td class="pe-4 text-end"><a class="btn btn-sm btn-light text-primary" href="ledgers.php" aria-label="View Service revenue ledger" title="View ledger"><i class="bi bi-journal-text" aria-hidden="true"></i></a></td>
                                        </tr>
                                        <tr class="table-light">
                                            <td class="ps-4 py-3" colspan="5"><span class="account-type-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-danger-subtle text-danger me-2"><i class="bi bi-receipt" aria-hidden="true"></i></span><span class="fw-bold">Expenses</span><span class="small text-secondary ms-2">4 accounts</span></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4 py-3"><span class="fw-semibold">5000 · Office expenses</span></td>
                                            <td><span class="badge rounded-pill bg-danger-subtle text-danger-emphasis">Expense</span></td>
                                            <td class="text-end fw-semibold">$0.00</td>
                                            <td><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Active</span></td>
                                            <td class="pe-4 text-end"><a class="btn btn-sm btn-light text-primary" href="ledgers.php" aria-label="View Office expenses ledger" title="View ledger"><i class="bi bi-journal-text" aria-hidden="true"></i></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 p-3 p-md-4 border-top">
                                <span class="small text-secondary">Showing 1–8 of 18 accounts</span>
                                <nav aria-label="Account pages">
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

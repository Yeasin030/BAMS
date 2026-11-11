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
    <title>Ledgers | BizLedger</title>
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
                                    <i class="bi bi-journal-text me-1" aria-hidden="true"></i> General ledger
                                </span>
                                <h1 class="h2 fw-bold mb-2">Ledgers</h1>
                                <p class="mb-0 text-white-50">Review account activity, debits, credits, and running balances.</p>
                            </div>
                            <a class="btn btn-light text-primary fw-semibold px-3 py-2 text-nowrap" href="chart_of_accounts.php">
                                <i class="bi bi-diagram-3 me-1" aria-hidden="true"></i>Chart of accounts
                            </a>
                        </div>
                    </section>

                    <div class="alert alert-info border-0 rounded-3 d-flex align-items-start gap-2 mb-4" role="note">
                        <i class="bi bi-info-circle-fill mt-1" aria-hidden="true"></i>
                        <div><strong>UI preview:</strong> These ledger entries and balances are examples and are not connected to saved records.</div>
                    </div>

                    <section class="card account-panel rounded-4 mb-4" aria-labelledby="ledger-account-title">
                        <div class="card-body p-3 p-md-4">
                            <div class="row g-3 align-items-end">
                                <div class="col-12 col-lg-5">
                                    <label class="form-label fw-semibold" for="ledger-account">Account</label>
                                    <select class="form-select form-select-lg" id="ledger-account">
                                        <option>1010 · Business checking</option>
                                        <option>1020 · Petty cash</option>
                                        <option>2000 · Accounts payable</option>
                                        <option>3000 · Owner's equity</option>
                                        <option>4000 · Service revenue</option>
                                        <option>5000 · Office expenses</option>
                                    </select>
                                </div>
                                <div class="col-6 col-lg-2">
                                    <label class="form-label fw-semibold" for="ledger-from">From</label>
                                    <input class="form-control form-control-lg" type="date" id="ledger-from" value="2026-11-01">
                                </div>
                                <div class="col-6 col-lg-2">
                                    <label class="form-label fw-semibold" for="ledger-to">To</label>
                                    <input class="form-control form-control-lg" type="date" id="ledger-to" value="2026-11-11">
                                </div>
                                <div class="col-12 col-lg-3 d-flex gap-2">
                                    <button class="btn btn-primary btn-lg flex-grow-1" type="button"><i class="bi bi-funnel me-1" aria-hidden="true"></i>Apply</button>
                                    <button class="btn btn-outline-secondary btn-lg" type="button" aria-label="Print ledger"><i class="bi bi-printer" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="row g-3 mb-4" aria-label="Ledger balance summary">
                        <div class="col-12 col-md-4">
                            <article class="card account-stat rounded-4 h-100">
                                <div class="card-body p-4">
                                    <div class="small text-secondary mb-1">Opening balance</div>
                                    <div class="h4 fw-bold mb-0">$6,250.00</div>
                                    <div class="small text-secondary mt-2"><i class="bi bi-calendar3 me-1" aria-hidden="true"></i>As of Nov 1, 2026</div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-md-4">
                            <article class="card account-stat rounded-4 h-100">
                                <div class="card-body p-4">
                                    <div class="small text-secondary mb-1">Debits</div>
                                    <div class="h4 fw-bold text-success mb-0">$3,775.00</div>
                                    <div class="small text-secondary mt-2">2 entries in this period</div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-md-4">
                            <article class="card account-stat rounded-4 h-100">
                                <div class="card-body p-4">
                                    <div class="small text-secondary mb-1">Closing balance</div>
                                    <div class="h4 fw-bold text-primary mb-0">$4,425.00</div>
                                    <div class="small text-secondary mt-2">As of Nov 11, 2026</div>
                                </div>
                            </article>
                        </div>
                    </section>

                    <section class="card account-panel rounded-4 overflow-hidden" aria-labelledby="ledger-title">
                        <div class="card-body p-0">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 p-3 p-md-4 border-bottom">
                                <div>
                                    <h2 class="h5 fw-bold mb-1" id="ledger-title">1010 · Business checking</h2>
                                    <p class="small text-secondary mb-0">Account ledger · November 1–11, 2026</p>
                                </div>
                                <span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2"><i class="bi bi-check-circle me-1" aria-hidden="true"></i>Active account</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle account-table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="ps-4 py-3 small text-uppercase text-secondary">Date</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary">Reference</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary">Description</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary text-end">Debit</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary text-end">Credit</th>
                                            <th scope="col" class="pe-4 py-3 small text-uppercase text-secondary text-end">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="table-light">
                                            <td class="ps-4 py-3 text-nowrap">Nov 01, 2026</td>
                                            <td colspan="4" class="fw-semibold">Opening balance</td>
                                            <td class="pe-4 text-end fw-semibold">$6,250.00</td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4 py-3 text-nowrap">Nov 03, 2026</td>
                                            <td><a href="#" class="text-decoration-none fw-semibold">JE-2026-118</a></td>
                                            <td><div class="fw-semibold">Client payment received</div><div class="small text-secondary">Website project milestone</div></td>
                                            <td class="text-end text-success">$2,400.00</td>
                                            <td class="text-end text-secondary">—</td>
                                            <td class="pe-4 text-end fw-semibold">$8,650.00</td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4 py-3 text-nowrap">Nov 06, 2026</td>
                                            <td><a href="#" class="text-decoration-none fw-semibold">JE-2026-121</a></td>
                                            <td><div class="fw-semibold">Office supplies purchase</div><div class="small text-secondary">Paper and printer supplies</div></td>
                                            <td class="text-end text-secondary">—</td>
                                            <td class="text-end text-danger">$185.50</td>
                                            <td class="pe-4 text-end fw-semibold">$8,464.50</td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4 py-3 text-nowrap">Nov 08, 2026</td>
                                            <td><a href="#" class="text-decoration-none fw-semibold">JE-2026-124</a></td>
                                            <td><div class="fw-semibold">Monthly hosting plan</div><div class="small text-secondary">Cloud infrastructure subscription</div></td>
                                            <td class="text-end text-secondary">—</td>
                                            <td class="text-end text-danger">$49.00</td>
                                            <td class="pe-4 text-end fw-semibold">$8,415.50</td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4 py-3 text-nowrap">Nov 10, 2026</td>
                                            <td><a href="#" class="text-decoration-none fw-semibold">JE-2026-129</a></td>
                                            <td><div class="fw-semibold">Contractor payment</div><div class="small text-secondary">Design support — November</div></td>
                                            <td class="text-end text-secondary">—</td>
                                            <td class="text-end text-danger">$3,990.50</td>
                                            <td class="pe-4 text-end fw-semibold">$4,425.00</td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th class="ps-4 py-3" colspan="3">Period totals</th>
                                            <th class="text-end text-success">$2,400.00</th>
                                            <th class="text-end text-danger">$4,225.00</th>
                                            <th class="pe-4 text-end">$4,425.00</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 p-3 p-md-4 border-top">
                                <span class="small text-secondary">Showing 4 ledger entries</span>
                                <a class="small text-decoration-none" href="chart_of_accounts.php"><i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Back to chart of accounts</a>
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

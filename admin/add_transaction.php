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
    <title>Add Transaction | BizLedger</title>
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
                                    <i class="bi bi-plus-circle me-1" aria-hidden="true"></i> Record activity
                                </span>
                                <h1 class="h2 fw-bold mb-2">Add transaction</h1>
                                <p class="mb-0 text-white-50">Enter the details below to record income or an expense.</p>
                            </div>
                            <a class="btn btn-light text-primary fw-semibold px-3 py-2 text-nowrap" href="transactions.php">
                                <i class="bi bi-list-ul me-1" aria-hidden="true"></i>View all transactions
                            </a>
                        </div>
                    </section>

                    <div class="alert alert-info border-0 rounded-3 d-flex align-items-start gap-2 mb-4" role="note">
                        <i class="bi bi-info-circle-fill mt-1" aria-hidden="true"></i>
                        <div><strong>UI preview:</strong> This form is for interface review only; submitting does not save a transaction.</div>
                    </div>

                    <div class="row g-4">
                        <div class="col-12 col-xl-8">
                            <section class="card transaction-panel rounded-4" aria-labelledby="transaction-details-title">
                                <div class="card-body p-4 p-lg-5">
                                    <div class="mb-4">
                                        <h2 class="h5 fw-bold mb-1" id="transaction-details-title">Transaction details</h2>
                                        <p class="small text-secondary mb-0">Fields marked with <span class="text-danger">*</span> are required.</p>
                                    </div>
                                    <form class="transaction-form">
                                        <div class="row g-3">
                                            <fieldset class="col-12">
                                                <legend class="form-label">Transaction type <span class="text-danger">*</span></legend>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <input class="btn-check" type="radio" name="type" id="type-income" autocomplete="off" checked>
                                                        <label class="btn btn-outline-success w-100 py-3" for="type-income"><i class="bi bi-arrow-down-left-circle me-1" aria-hidden="true"></i>Income</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <input class="btn-check" type="radio" name="type" id="type-expense" autocomplete="off">
                                                        <label class="btn btn-outline-danger w-100 py-3" for="type-expense"><i class="bi bi-arrow-up-right-circle me-1" aria-hidden="true"></i>Expense</label>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="transaction-date">Date <span class="text-danger">*</span></label>
                                                <input class="form-control" type="date" id="transaction-date" name="date" value="2026-11-11" required>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="transaction-amount">Amount <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input class="form-control" type="number" id="transaction-amount" name="amount" min="0.01" step="0.01" placeholder="0.00" required>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="transaction-category">Category <span class="text-danger">*</span></label>
                                                <select class="form-select" id="transaction-category" name="category" required>
                                                    <option value="" selected disabled>Select a category</option>
                                                    <option>Sales income</option>
                                                    <option>Service income</option>
                                                    <option>Office expenses</option>
                                                    <option>Travel</option>
                                                    <option>Software &amp; services</option>
                                                    <option>Other</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="payment-account">Payment account <span class="text-danger">*</span></label>
                                                <select class="form-select" id="payment-account" name="account" required>
                                                    <option value="" selected disabled>Select an account</option>
                                                    <option>Business checking</option>
                                                    <option>Cash</option>
                                                    <option>Business credit card</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label" for="transaction-description">Description <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" id="transaction-description" name="description" placeholder="What was this transaction for?" required>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label" for="transaction-notes">Notes <span class="text-secondary fw-normal">(optional)</span></label>
                                                <textarea class="form-control" id="transaction-notes" name="notes" placeholder="Add any useful details or a reference number"></textarea>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column-reverse flex-sm-row justify-content-end gap-2 mt-4 pt-3 border-top">
                                            <a class="btn btn-light border px-4" href="transactions.php">Cancel</a>
                                            <button class="btn btn-primary px-4" type="button">
                                                <i class="bi bi-check2 me-1" aria-hidden="true"></i>Save transaction
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </section>
                        </div>
                        <div class="col-12 col-xl-4">
                            <aside class="card transaction-panel rounded-4 mb-4" aria-labelledby="help-title">
                                <div class="card-body p-4">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary fs-4 mb-3" style="width: 2.75rem; height: 2.75rem"><i class="bi bi-lightbulb" aria-hidden="true"></i></span>
                                    <h2 class="h6 fw-bold" id="help-title">A few helpful tips</h2>
                                    <ul class="small text-secondary ps-3 mb-0">
                                        <li class="mb-2">Choose income for money received and expense for money spent.</li>
                                        <li class="mb-2">Use a clear description so the transaction is easy to recognize later.</li>
                                        <li>Pick the account used to receive or pay the money.</li>
                                    </ul>
                                </div>
                            </aside>
                            <aside class="card transaction-panel rounded-4">
                                <div class="card-body p-4">
                                    <h2 class="h6 fw-bold mb-2"><i class="bi bi-shield-check text-success me-2" aria-hidden="true"></i>Keep your records clear</h2>
                                    <p class="small text-secondary mb-0">Accurate dates, categories, and descriptions help make reports easier to understand.</p>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

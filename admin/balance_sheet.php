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

function money_value($conn, $sql){
    $row = $conn -> query($sql) -> fetch_assoc();
    return (float) ($row['v'] ?? 0);
}

function share($part, $whole){
    if($whole <= 0){
        return 0;
    }
    return max(0, min(100, round(($part / $whole) * 100)));
}

// Report date
$date = $_GET['date'] ?? date('Y-m-d');
if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || strtotime($date) === false){
    $date = date('Y-m-d');
}

// Assets: cash & bank (accounts used for incomes, expenses and payments)
$cash_accounts = "SELECT account_id FROM incomes WHERE company_id = '$company_id'
UNION SELECT account_id FROM expenses WHERE company_id = '$company_id'
UNION SELECT account_id FROM payments WHERE company_id = '$company_id'";

$cash_opening = money_value($conn, "SELECT COALESCE(SUM(CASE WHEN opening_balance_side = 'DEBIT' THEN opening_balance ELSE -opening_balance END), 0) AS v FROM accounts WHERE company_id = '$company_id' AND account_id IN ($cash_accounts)");
$cash_income = money_value($conn, "SELECT COALESCE(SUM(amount), 0) AS v FROM incomes WHERE company_id = '$company_id' AND income_date <= '$date'");
$cash_expense = money_value($conn, "SELECT COALESCE(SUM(amount), 0) AS v FROM expenses WHERE company_id = '$company_id' AND expense_date <= '$date'");
$cash_receipts = money_value($conn, "SELECT COALESCE(SUM(amount), 0) AS v FROM payments WHERE company_id = '$company_id' AND payment_type = 'RECEIPT' AND payment_date <= '$date'");
$cash_payments = money_value($conn, "SELECT COALESCE(SUM(amount), 0) AS v FROM payments WHERE company_id = '$company_id' AND payment_type = 'PAYMENT' AND payment_date <= '$date'");
$cash_bank = $cash_opening + $cash_income - $cash_expense + $cash_receipts - $cash_payments;

// Assets: accounts receivable (unpaid sales)
$receivable = money_value($conn, "SELECT COALESCE(SUM(s.total_amount - COALESCE((SELECT SUM(pa.allocated_amount) FROM payment_allocations pa JOIN payments p ON p.payment_id = pa.payment_id WHERE pa.invoice_type = 'SALE' AND pa.invoice_id = s.sale_id AND p.payment_date <= '$date'), 0)), 0) AS v
FROM sales s WHERE s.company_id = '$company_id' AND s.status NOT IN ('DRAFT', 'CANCEL') AND s.sale_date <= '$date'");

// Assets: inventory (current stock at purchase price)
$inventory = money_value($conn, "SELECT COALESCE(SUM(current_stock * purchase_price), 0) AS v FROM products WHERE company_id = '$company_id'");

$total_assets = $cash_bank + $receivable + $inventory;

// Liabilities: accounts payable (unpaid purchases)
$payable = money_value($conn, "SELECT COALESCE(SUM(pu.total_amount - COALESCE((SELECT SUM(pa.allocated_amount) FROM payment_allocations pa JOIN payments p ON p.payment_id = pa.payment_id WHERE pa.invoice_type = 'PURCHASE' AND pa.invoice_id = pu.purchase_id AND p.payment_date <= '$date'), 0)), 0) AS v
FROM purchases pu WHERE pu.company_id = '$company_id' AND pu.status NOT IN ('DRAFT', 'CANCEL') AND pu.purchase_date <= '$date'");

$total_liabilities = $payable;

// Equity
$owner_capital = money_value($conn, "SELECT COALESCE(SUM(CASE WHEN opening_balance_side = 'CREDIT' THEN opening_balance ELSE -opening_balance END), 0) AS v FROM accounts WHERE company_id = '$company_id' AND account_categorie = 'EQUITY'");

$sales_revenue = money_value($conn, "SELECT COALESCE(SUM(total_amount), 0) AS v FROM sales WHERE company_id = '$company_id' AND status <> 'CANCEL' AND sale_date <= '$date'");
$cogs = money_value($conn, "SELECT COALESCE(SUM(si.quantity * p.purchase_price), 0) AS v FROM sales_items si JOIN sales s ON s.sale_id = si.sale_id JOIN products p ON p.product_id = si.product_id WHERE s.company_id = '$company_id' AND s.status <> 'CANCEL' AND s.sale_date <= '$date'");
$retained_earnings = $sales_revenue + $cash_income - $cogs - $cash_expense;

$total_equity = $total_assets - $total_liabilities;
$other_equity = $total_equity - $owner_capital - $retained_earnings;
$is_reconciled = abs($other_equity) < 0.01;

$total_liabilities_equity = $total_liabilities + $total_equity;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Balance Sheet | BizLedger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/dashboard-shared.css" rel="stylesheet">
    <link href="assets/css/financial-reports.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary dashboard-page financial-report-page">
    <div class="container-fluid px-0">
        <div class="row min-vh-100">
            <div class="col-lg-3 col-xl-2 p-0 bg-dark">
                <?php include 'include/adminsidebar.php'; ?>
            </div>
            <main class="col-12 col-lg-9 col-xl-10 p-0">
                <?php include 'include/adminheadbar.php'; ?>
                <div class="p-3 p-md-4 p-xl-5">
                    <nav class="financial-breadcrumb mb-3" aria-label="Breadcrumb">
                        <a href="dashboard_admin.php" class="text-decoration-none">Dashboard</a>
                        <i class="bi bi-chevron-right mx-2" aria-hidden="true"></i>
                        <a href="profit_loss.php" class="text-decoration-none">Reports</a>
                        <i class="bi bi-chevron-right mx-2" aria-hidden="true"></i>
                        <span aria-current="page">Balance Sheet</span>
                    </nav>

                    <section class="financial-hero balance-hero rounded-4 shadow-sm text-white p-4 p-lg-5 mb-4">
                        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-4">
                            <div>
                                <span class="badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 mb-3"><i class="bi bi-columns-gap me-1" aria-hidden="true"></i> Financial position</span>
                                <h1 class="h2 fw-bold mb-2">Balance Sheet</h1>
                                <p class="mb-0 text-white-50">See what your business owns and owes at a specific point in time.</p>
                            </div>
                            <form method="get" class="d-flex flex-column flex-sm-row gap-2">
                                <label class="visually-hidden" for="balanceDate">Report date</label>
                                <input class="form-control" id="balanceDate" name="date" type="date" value="<?= $date ?>" aria-label="Report date" onchange="this.form.submit()">
                                <button class="btn btn-light text-primary fw-semibold text-nowrap" type="button"><i class="bi bi-download me-1" aria-hidden="true"></i>Export</button>
                            </form>
                        </div>
                    </section>

                    <section class="row g-3 mb-4" aria-label="Balance sheet summary">
                        <div class="col-12 col-sm-6 col-xl-4"><article class="card financial-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="financial-stat-icon bg-primary-subtle text-primary"><i class="bi bi-safe2" aria-hidden="true"></i></span><div><div class="small text-secondary">Total assets</div><div class="h4 fw-bold mb-0">$<?= number_format($total_assets, 2) ?></div><div class="small text-secondary mt-1">What the business owns</div></div></div></article></div>
                        <div class="col-12 col-sm-6 col-xl-4"><article class="card financial-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="financial-stat-icon bg-warning-subtle text-warning-emphasis"><i class="bi bi-credit-card" aria-hidden="true"></i></span><div><div class="small text-secondary">Total liabilities</div><div class="h4 fw-bold mb-0">$<?= number_format($total_liabilities, 2) ?></div><div class="small text-secondary mt-1">What the business owes</div></div></div></article></div>
                        <div class="col-12 col-sm-6 col-xl-4"><article class="card financial-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="financial-stat-icon bg-success-subtle text-success"><i class="bi bi-person-vcard" aria-hidden="true"></i></span><div><div class="small text-secondary">Total equity</div><div class="h4 fw-bold mb-0">$<?= number_format($total_equity, 2) ?></div><div class="small text-secondary mt-1">Assets less liabilities</div></div></div></article></div>
                    </section>

                    <section class="card financial-panel rounded-4 overflow-hidden mb-4" aria-labelledby="balance-equation-title">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 p-3 p-md-4 border-bottom">
                            <div><h2 class="h5 fw-bold mb-1" id="balance-equation-title">Accounting equation</h2><p class="small text-secondary mb-0">As of <?= date('F j, Y', strtotime($date)) ?></p></div>
                            <?php if($is_reconciled){ ?>
                                <span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2"><i class="bi bi-check-circle-fill me-1" aria-hidden="true"></i>Balanced</span>
                            <?php } else { ?>
                                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis px-3 py-2"><i class="bi bi-exclamation-triangle-fill me-1" aria-hidden="true"></i>Unreconciled</span>
                            <?php } ?>
                        </div>
                        <div class="balance-equation d-flex flex-column flex-lg-row align-items-center justify-content-center gap-3 p-4">
                            <div class="equation-block"><span class="small text-secondary">Assets</span><strong>$<?= number_format($total_assets) ?></strong></div>
                            <span class="equation-symbol" aria-hidden="true">=</span>
                            <div class="equation-block"><span class="small text-secondary">Liabilities</span><strong>$<?= number_format($total_liabilities) ?></strong></div>
                            <span class="equation-symbol" aria-hidden="true">+</span>
                            <div class="equation-block"><span class="small text-secondary">Equity</span><strong>$<?= number_format($total_equity) ?></strong></div>
                        </div>
                    </section>

                    <div class="row g-4 align-items-stretch">
                        <div class="col-12 col-xl-6">
                            <section class="card financial-panel rounded-4 h-100 overflow-hidden" aria-labelledby="assets-title">
                                <div class="p-3 p-md-4 border-bottom"><div class="d-flex justify-content-between align-items-center"><div><h2 class="h5 fw-bold mb-1" id="assets-title">Assets</h2><p class="small text-secondary mb-0">Resources owned by the business</p></div><span class="badge rounded-pill bg-primary-subtle text-primary-emphasis">3 accounts</span></div></div>
                                <div class="card-body p-3 p-md-4">
                                    <div class="balance-account"><div class="d-flex justify-content-between gap-3"><span><i class="bi bi-cash-stack text-success me-2" aria-hidden="true"></i>Cash &amp; bank</span><strong>$<?= number_format($cash_bank, 2) ?></strong></div><div class="progress mt-2" role="progressbar" aria-label="Cash and bank share of assets" aria-valuenow="<?= share($cash_bank, $total_assets) ?>" aria-valuemin="0" aria-valuemax="100"><div class="progress-bar bg-primary" style="width: <?= share($cash_bank, $total_assets) ?>%"></div></div><small class="text-secondary"><?= share($cash_bank, $total_assets) ?>% of total assets</small></div>
                                    <div class="balance-account"><div class="d-flex justify-content-between gap-3"><span><i class="bi bi-person-lines-fill text-info me-2" aria-hidden="true"></i>Accounts receivable</span><strong>$<?= number_format($receivable, 2) ?></strong></div><div class="progress mt-2" role="progressbar" aria-label="Accounts receivable share of assets" aria-valuenow="<?= share($receivable, $total_assets) ?>" aria-valuemin="0" aria-valuemax="100"><div class="progress-bar bg-info" style="width: <?= share($receivable, $total_assets) ?>%"></div></div><small class="text-secondary"><?= share($receivable, $total_assets) ?>% of total assets</small></div>
                                    <div class="balance-account"><div class="d-flex justify-content-between gap-3"><span><i class="bi bi-box-seam text-warning me-2" aria-hidden="true"></i>Inventory</span><strong>$<?= number_format($inventory, 2) ?></strong></div><div class="progress mt-2" role="progressbar" aria-label="Inventory share of assets" aria-valuenow="<?= share($inventory, $total_assets) ?>" aria-valuemin="0" aria-valuemax="100"><div class="progress-bar bg-warning" style="width: <?= share($inventory, $total_assets) ?>%"></div></div><small class="text-secondary"><?= share($inventory, $total_assets) ?>% of total assets</small></div>
                                </div>
                                <div class="d-flex justify-content-between p-3 p-md-4 border-top fw-bold"><span>Total assets</span><span>$<?= number_format($total_assets, 2) ?></span></div>
                            </section>
                        </div>
                        <div class="col-12 col-xl-6">
                            <section class="card financial-panel rounded-4 h-100 overflow-hidden" aria-labelledby="liabilities-equity-title">
                                <div class="p-3 p-md-4 border-bottom"><div class="d-flex justify-content-between align-items-center"><div><h2 class="h5 fw-bold mb-1" id="liabilities-equity-title">Liabilities &amp; equity</h2><p class="small text-secondary mb-0">Obligations and owner value</p></div><?php if($is_reconciled){ ?><span class="badge rounded-pill bg-success-subtle text-success-emphasis">Balanced</span><?php } else { ?><span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Unreconciled</span><?php } ?></div></div>
                                <div class="card-body p-3 p-md-4">
                                    <h3 class="small fw-bold text-uppercase text-secondary mb-3">Liabilities</h3>
                                    <div class="balance-account"><div class="d-flex justify-content-between gap-3"><span><i class="bi bi-receipt text-warning me-2" aria-hidden="true"></i>Accounts payable</span><strong>$<?= number_format($payable, 2) ?></strong></div><div class="progress mt-2" role="progressbar" aria-label="Accounts payable share of liabilities and equity" aria-valuenow="<?= share($payable, $total_liabilities_equity) ?>" aria-valuemin="0" aria-valuemax="100"><div class="progress-bar bg-warning" style="width: <?= share($payable, $total_liabilities_equity) ?>%"></div></div></div>
                                    <div class="d-flex justify-content-between border-top pt-3 mt-3 mb-4 fw-semibold"><span>Total liabilities</span><span>$<?= number_format($total_liabilities, 2) ?></span></div>
                                    <h3 class="small fw-bold text-uppercase text-secondary mb-3">Equity</h3>
                                    <div class="balance-account"><div class="d-flex justify-content-between gap-3"><span><i class="bi bi-person-badge text-primary me-2" aria-hidden="true"></i>Owner capital</span><strong>$<?= number_format($owner_capital, 2) ?></strong></div></div>
                                    <div class="balance-account"><div class="d-flex justify-content-between gap-3"><span><i class="bi bi-graph-up-arrow text-success me-2" aria-hidden="true"></i>Retained earnings</span><strong>$<?= number_format($retained_earnings, 2) ?></strong></div></div>
                                    <?php if(!$is_reconciled){ ?>
                                    <div class="balance-account"><div class="d-flex justify-content-between gap-3"><span><i class="bi bi-question-circle text-secondary me-2" aria-hidden="true"></i>Unreconciled difference</span><strong>$<?= number_format($other_equity, 2) ?></strong></div></div>
                                    <?php } ?>
                                    <div class="d-flex justify-content-between border-top pt-3 mt-3 fw-semibold"><span>Total equity</span><span>$<?= number_format($total_equity, 2) ?></span></div>
                                </div>
                                <div class="d-flex justify-content-between p-3 p-md-4 border-top fw-bold"><span>Total liabilities &amp; equity</span><span>$<?= number_format($total_liabilities_equity, 2) ?></span></div>
                            </section>
                        </div>
                    </div>
                    <?php if($is_reconciled){ ?>
                    <div class="alert balance-check-note rounded-3 d-flex align-items-start gap-2 mt-4 mb-0" role="status"><i class="bi bi-check-circle-fill mt-1" aria-hidden="true"></i><div><strong>Balance check passed.</strong> Total assets equal total liabilities plus equity.</div></div>
                    <?php } else { ?>
                    <div class="alert alert-warning rounded-3 d-flex align-items-start gap-2 mt-4 mb-0" role="status"><i class="bi bi-exclamation-triangle-fill mt-1" aria-hidden="true"></i><div><strong>Unreconciled difference of $<?= number_format(abs($other_equity), 2) ?>.</strong> Owner capital and retained earnings do not fully explain equity, usually because some transactions are not recorded in the books yet (for example purchases, opening balances or journal entries).</div></div>
                    <?php } ?>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
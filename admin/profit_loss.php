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

function pl_totals($conn, $company_id, $from, $to){
    $r = [];
    $r['sales'] = (float) $conn -> query("SELECT COALESCE(SUM(total_amount), 0) AS v FROM sales WHERE company_id = '$company_id' AND status <> 'CANCELLED' AND sale_date BETWEEN '$from' AND '$to'") -> fetch_assoc()['v'];
    $r['other_income'] = (float) $conn -> query("SELECT COALESCE(SUM(amount), 0) AS v FROM incomes WHERE company_id = '$company_id' AND income_date BETWEEN '$from' AND '$to'") -> fetch_assoc()['v'];
    $r['cogs'] = (float) $conn -> query("SELECT COALESCE(SUM(si.quantity * p.purchase_price), 0) AS v FROM sales_items si JOIN sales s ON s.sale_id = si.sale_id JOIN products p ON p.product_id = si.product_id WHERE s.company_id = '$company_id' AND s.status <> 'CANCELLED' AND s.sale_date BETWEEN '$from' AND '$to'") -> fetch_assoc()['v'];
    $r['opex'] = (float) $conn -> query("SELECT COALESCE(SUM(amount), 0) AS v FROM expenses WHERE company_id = '$company_id' AND expense_date BETWEEN '$from' AND '$to'") -> fetch_assoc()['v'];
    $r['revenue'] = $r['sales'] + $r['other_income'];
    $r['gross'] = $r['revenue'] - $r['cogs'];
    $r['net'] = $r['gross'] - $r['opex'];
    return $r;
}

function short_money($v){
    if($v >= 1000){
        return '$' . round($v / 1000, 1) . 'k';
    }
    return '$' . round($v);
}

// Reporting period
$period = $_GET['period'] ?? 'this_month';
$today = date('Y-m-d');

if($period == 'last_month'){
    $from = date('Y-m-01', strtotime('first day of last month'));
    $to = date('Y-m-t', strtotime('first day of last month'));
    $period_label = date('F Y', strtotime($from));
} elseif($period == 'quarter'){
    $quarter = ceil(date('n') / 3);
    $from = date('Y') . '-' . str_pad(($quarter - 1) * 3 + 1, 2, '0', STR_PAD_LEFT) . '-01';
    $to = $today;
    $period_label = 'Q' . $quarter . ' ' . date('Y');
} elseif($period == 'ytd'){
    $from = date('Y-01-01');
    $to = $today;
    $period_label = 'Year to date';
} else {
    $period = 'this_month';
    $from = date('Y-m-01');
    $to = $today;
    $period_label = date('F 1', strtotime($from)) . '–' . date('j, Y', strtotime($to));
}

$pl_now = pl_totals($conn, $company_id, $from, $to);

// Previous period of the same length
$days = (int) ((strtotime($to) - strtotime($from)) / 86400) + 1;
$prev_to = date('Y-m-d', strtotime($from . ' -1 day'));
$prev_from = date('Y-m-d', strtotime($prev_to . ' -' . ($days - 1) . ' day'));
$pl_prev = pl_totals($conn, $company_id, $prev_from, $prev_to);

$revenue_change = $pl_prev['revenue'] > 0 ? (($pl_now['revenue'] - $pl_prev['revenue']) / $pl_prev['revenue']) * 100 : null;
$gross_margin = $pl_now['revenue'] > 0 ? ($pl_now['gross'] / $pl_now['revenue']) * 100 : 0;
$opex_percent = $pl_now['revenue'] > 0 ? ($pl_now['opex'] / $pl_now['revenue']) * 100 : 0;
$net_margin = $pl_now['revenue'] > 0 ? ($pl_now['net'] / $pl_now['revenue']) * 100 : 0;
$ring_percent = max(0, min(100, $net_margin));
$total_expenses = $pl_now['cogs'] + $pl_now['opex'];

// Operating expenses by category
$expense_rows = $conn -> query("SELECT COALESCE(ec.category_name, 'Uncategorized') AS category_name, SUM(e.amount) AS total
FROM expenses e LEFT JOIN expense_categories ec ON ec.expense_category_id = e.expense_category_id
WHERE e.company_id = '$company_id' AND e.expense_date BETWEEN '$from' AND '$to'
GROUP BY category_name ORDER BY total DESC");

// Last six months trend
$months = [];
$chart_max = 0;
for($i = 5; $i >= 0; $i--){
    $month_start = strtotime(date('Y-m-01') . " -$i month");
    $m = pl_totals($conn, $company_id, date('Y-m-01', $month_start), date('Y-m-t', $month_start));
    $m['label'] = date('M', $month_start);
    $months[] = $m;
    if($m['revenue'] > $chart_max){
        $chart_max = $m['revenue'];
    }
}
if($chart_max <= 0){
    $chart_max = 1000;
}
$step = pow(10, floor(log10($chart_max)));
$chart_top = ceil($chart_max / $step) * $step;
$trend_label = date('F', strtotime(date('Y-m-01') . ' -5 month')) . '–' . date('F Y');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profit &amp; Loss | BizLedger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/dashboard-shared.css" rel="stylesheet">
    <link href="assets/css/financial-reports.css" rel="stylesheet">
    <link href="assets/css/glassmorphism.css" rel="stylesheet">
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
                        <span aria-current="page">Profit &amp; Loss</span>
                    </nav>

                    <section class="financial-hero profit-hero rounded-4 shadow-sm text-white p-4 p-lg-5 mb-4">
                        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-4">
                            <div>
                                <span class="badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 mb-3"><i class="bi bi-graph-up-arrow me-1" aria-hidden="true"></i> Income statement</span>
                                <h1 class="h2 fw-bold mb-2">Profit &amp; Loss</h1>
                                <p class="mb-0 text-white-50">Understand how income and expenses shape your business performance.</p>
                            </div>
                            <form method="get" class="d-flex flex-column flex-sm-row gap-2">
                                <label class="visually-hidden" for="profitPeriod">Reporting period</label>
                                <select class="form-select" id="profitPeriod" name="period" aria-label="Reporting period" onchange="this.form.submit()">
                                    <option value="this_month" <?= $period == 'this_month' ? 'selected' : '' ?>><?= date('F 1', strtotime(date('Y-m-01'))) ?>–<?= date('j, Y') ?></option>
                                    <option value="last_month" <?= $period == 'last_month' ? 'selected' : '' ?>><?= date('F Y', strtotime('first day of last month')) ?></option>
                                    <option value="quarter" <?= $period == 'quarter' ? 'selected' : '' ?>>Q<?= ceil(date('n') / 3) ?> <?= date('Y') ?></option>
                                    <option value="ytd" <?= $period == 'ytd' ? 'selected' : '' ?>>Year to date</option>
                                </select>
                                <button class="btn btn-light text-primary fw-semibold text-nowrap" type="button"><i class="bi bi-download me-1" aria-hidden="true"></i>Export</button>
                            </form>
                        </div>
                    </section>

                    <section class="row g-3 mb-4" aria-label="Profit and loss summary">
                        <div class="col-12 col-sm-6 col-xl-3"><article class="card financial-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="financial-stat-icon bg-success-subtle text-success"><i class="bi bi-arrow-down-left" aria-hidden="true"></i></span><div><div class="small text-secondary">Total revenue</div><div class="h4 fw-bold mb-0">$<?= number_format($pl_now['revenue'], 2) ?></div>
                            <?php if($revenue_change === null){ ?>
                                <div class="small text-secondary mt-1">No previous period data</div>
                            <?php } elseif($revenue_change >= 0){ ?>
                                <div class="small text-success mt-1"><i class="bi bi-arrow-up-right me-1" aria-hidden="true"></i><?= number_format($revenue_change, 1) ?>% vs previous period</div>
                            <?php } else { ?>
                                <div class="small text-danger mt-1"><i class="bi bi-arrow-down-right me-1" aria-hidden="true"></i><?= number_format(abs($revenue_change), 1) ?>% vs previous period</div>
                            <?php } ?>
                        </div></div></article></div>
                        <div class="col-12 col-sm-6 col-xl-3"><article class="card financial-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="financial-stat-icon bg-info-subtle text-info-emphasis"><i class="bi bi-box-seam" aria-hidden="true"></i></span><div><div class="small text-secondary">Gross profit</div><div class="h4 fw-bold mb-0">$<?= number_format($pl_now['gross'], 2) ?></div><div class="small text-secondary mt-1"><?= number_format($gross_margin, 1) ?>% gross margin</div></div></div></article></div>
                        <div class="col-12 col-sm-6 col-xl-3"><article class="card financial-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="financial-stat-icon bg-danger-subtle text-danger"><i class="bi bi-arrow-up-right" aria-hidden="true"></i></span><div><div class="small text-secondary">Operating expenses</div><div class="h4 fw-bold mb-0">$<?= number_format($pl_now['opex'], 2) ?></div><div class="small text-secondary mt-1"><?= number_format($opex_percent, 1) ?>% of revenue</div></div></div></article></div>
                        <div class="col-12 col-sm-6 col-xl-3"><article class="card financial-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="financial-stat-icon bg-primary-subtle text-primary"><i class="bi bi-trophy" aria-hidden="true"></i></span><div><div class="small text-secondary">Net profit</div><div class="h4 fw-bold <?= $pl_now['net'] >= 0 ? 'text-primary' : 'text-danger' ?> mb-0">$<?= number_format($pl_now['net'], 2) ?></div><div class="small <?= $net_margin >= 0 ? 'text-success' : 'text-danger' ?> mt-1"><?= number_format($net_margin, 1) ?>% net margin</div></div></div></article></div>
                    </section>

                    <div class="row g-4 align-items-stretch mb-4">
                        <div class="col-12 col-xl-8">
                            <section class="card financial-panel rounded-4 h-100" aria-labelledby="profit-trend-title">
                                <div class="card-body p-4">
                                    <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mb-4">
                                        <div><h2 class="h5 fw-bold mb-1" id="profit-trend-title">Revenue &amp; profit trend</h2><p class="small text-secondary mb-0">Monthly performance, <?= $trend_label ?></p></div>
                                        <div class="d-flex gap-3 small"><span><i class="bi bi-circle-fill text-primary me-1" aria-hidden="true"></i>Revenue</span><span><i class="bi bi-circle-fill text-success me-1" aria-hidden="true"></i>Net profit</span></div>
                                    </div>
                                    <div class="financial-chart" role="img" aria-label="Monthly revenue and net profit bar chart">
                                        <div class="financial-chart-scale small text-secondary"><span><?= short_money($chart_top) ?></span><span><?= short_money($chart_top * 0.75) ?></span><span><?= short_money($chart_top * 0.5) ?></span><span><?= short_money($chart_top * 0.25) ?></span><span>$0</span></div>
                                        <div class="financial-chart-plot">
                                            <div class="financial-chart-grid"><span></span><span></span><span></span><span></span><span></span></div>
                                            <div class="financial-chart-columns">
                                                <?php foreach($months as $m){
                                                    $revenue_height = round(($m['revenue'] / $chart_top) * 100, 1);
                                                    $profit_height = round((max(0, $m['net']) / $chart_top) * 100, 1);
                                                ?>
                                                <div class="financial-chart-month"><div class="financial-bar-pair"><span class="financial-bar revenue-bar" style="height: <?= $revenue_height ?>%"></span><span class="financial-bar profit-bar" style="height: <?= $profit_height ?>%"></span></div><span class="small text-secondary"><?= $m['label'] ?></span></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="col-12 col-xl-4">
                            <section class="card financial-panel h-100 rounded-4" aria-labelledby="margin-title">
                                <div class="card-body p-4">
                                    <h2 class="h5 fw-bold mb-1" id="margin-title">Profit margin</h2>
                                    <p class="small text-secondary mb-4">Performance for this period</p>
                                    <div class="margin-ring mx-auto mb-4" style="background: conic-gradient(#198754 <?= $ring_percent ?>%, #e9ecef 0);" role="img" aria-label="Net profit margin <?= number_format($net_margin, 1) ?> percent"><div><strong><?= number_format($net_margin, 1) ?>%</strong><span>Net margin</span></div></div>
                                    <div class="d-flex justify-content-between small mb-2"><span class="text-secondary">Revenue</span><strong>$<?= number_format($pl_now['revenue'], 2) ?></strong></div>
                                    <div class="d-flex justify-content-between small mb-2"><span class="text-secondary">Expenses</span><strong>$<?= number_format($total_expenses, 2) ?></strong></div>
                                    <div class="d-flex justify-content-between small border-top pt-3 mt-3"><span class="fw-semibold">Net profit</span><strong class="<?= $pl_now['net'] >= 0 ? 'text-success' : 'text-danger' ?>">$<?= number_format($pl_now['net'], 2) ?></strong></div>
                                </div>
                            </section>
                        </div>
                    </div>

                    <section class="card financial-panel rounded-4 overflow-hidden" aria-labelledby="statement-title">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 p-3 p-md-4 border-bottom">
                            <div><h2 class="h5 fw-bold mb-1" id="statement-title">Profit &amp; Loss statement</h2><p class="small text-secondary mb-0">For <?= $period_label ?></p></div>
                            <?php if($pl_now['net'] >= 0){ ?>
                                <span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2"><i class="bi bi-check-circle me-1" aria-hidden="true"></i>Net profit $<?= number_format($pl_now['net'], 2) ?></span>
                            <?php } else { ?>
                                <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis px-3 py-2"><i class="bi bi-exclamation-circle me-1" aria-hidden="true"></i>Net loss $<?= number_format(abs($pl_now['net']), 2) ?></span>
                            <?php } ?>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle financial-table mb-0">
                                <thead class="table-light"><tr><th scope="col" class="ps-4 py-3">Account</th><th scope="col" class="pe-4 py-3 text-end">Amount</th></tr></thead>
                                <tbody>
                                    <tr class="statement-section"><th scope="row" class="ps-4">Revenue</th><td class="pe-4"></td></tr>
                                    <tr><td class="ps-5">Sales revenue</td><td class="pe-4 text-end">$<?= number_format($pl_now['sales'], 2) ?></td></tr>
                                    <tr><td class="ps-5">Other income</td><td class="pe-4 text-end">$<?= number_format($pl_now['other_income'], 2) ?></td></tr>
                                    <tr class="statement-subtotal"><th scope="row" class="ps-4">Total revenue</th><td class="pe-4 text-end">$<?= number_format($pl_now['revenue'], 2) ?></td></tr>
                                    <tr class="statement-section"><th scope="row" class="ps-4">Cost of goods sold</th><td class="pe-4"></td></tr>
                                    <tr><td class="ps-5">Product costs</td><td class="pe-4 text-end">$<?= number_format($pl_now['cogs'], 2) ?></td></tr>
                                    <tr class="statement-subtotal"><th scope="row" class="ps-4">Gross profit</th><td class="pe-4 text-end">$<?= number_format($pl_now['gross'], 2) ?></td></tr>
                                    <tr class="statement-section"><th scope="row" class="ps-4">Operating expenses</th><td class="pe-4"></td></tr>
                                    <?php while($e = $expense_rows -> fetch_assoc()){ ?>
                                    <tr><td class="ps-5"><?= htmlspecialchars($e['category_name']) ?></td><td class="pe-4 text-end">$<?= number_format($e['total'], 2) ?></td></tr>
                                    <?php } ?>
                                    <tr class="statement-subtotal"><th scope="row" class="ps-4">Total operating expenses</th><td class="pe-4 text-end">$<?= number_format($pl_now['opex'], 2) ?></td></tr>
                                    <tr class="statement-total"><th scope="row" class="ps-4">Net profit</th><td class="pe-4 text-end">$<?= number_format($pl_now['net'], 2) ?></td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 p-3 p-md-4 border-top small text-secondary"><span>All values shown in USD</span><span>Generated on <?= date('F j, Y') ?></span></div>
                    </section>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
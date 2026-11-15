<?php
session_start();
include_once 'assets/db/bdconfig.php';
date_default_timezone_set('Asia/Dhaka');

if (!isset($_SESSION['email'])) {
  header("Location: ../index.php");
  exit;
}

if ($_SESSION['role'] != 'admin') {
  header("Location: unauthorize.php");
  exit;
}

$company_id = (int) ($_SESSION['company_id'] ?? 1);

function pl_totals($conn, $company_id, $from, $to)
{
  $r = [];
  $r['sales'] = (float) $conn->query("SELECT COALESCE(SUM(total_amount), 0) AS v FROM sales WHERE company_id = '$company_id' AND status <> 'CANCELLED' AND sale_date BETWEEN '$from' AND '$to'")->fetch_assoc()['v'];
  $r['other_income'] = (float) $conn->query("SELECT COALESCE(SUM(amount), 0) AS v FROM incomes WHERE company_id = '$company_id' AND income_date BETWEEN '$from' AND '$to'")->fetch_assoc()['v'];
  $r['cogs'] = (float) $conn->query("SELECT COALESCE(SUM(si.quantity * p.purchase_price), 0) AS v FROM sales_items si JOIN sales s ON s.sale_id = si.sale_id JOIN products p ON p.product_id = si.product_id WHERE s.company_id = '$company_id' AND s.status <> 'CANCELLED' AND s.sale_date BETWEEN '$from' AND '$to'")->fetch_assoc()['v'];
  $r['opex'] = (float) $conn->query("SELECT COALESCE(SUM(amount), 0) AS v FROM expenses WHERE company_id = '$company_id' AND expense_date BETWEEN '$from' AND '$to'")->fetch_assoc()['v'];
  $r['revenue'] = $r['sales'] + $r['other_income'];
  $r['expenses'] = $r['cogs'] + $r['opex'];
  $r['net'] = $r['revenue'] - $r['expenses'];
  return $r;
}

function money_value($conn, $sql)
{
  $row = $conn->query($sql)->fetch_assoc();
  return (float) ($row['v'] ?? 0);
}

function bdt($n)
{
  $n = round($n);
  $neg = $n < 0;
  $s = (string) abs($n);
  if (strlen($s) > 3) {
    $last = substr($s, -3);
    $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', substr($s, 0, -3));
    $s = $rest . ',' . $last;
  }
  return ($neg ? '-' : '') . '৳ ' . $s;
}

function change_note($cur, $prev, $higher_is_good = true)
{
  if ($prev <= 0) {
    return 'No data for last month';
  }
  $pct = (($cur - $prev) / $prev) * 100;
  $good = ($pct >= 0) == $higher_is_good;
  $class = $good ? 'up' : 'down';
  $icon = $pct >= 0 ? 'arrow-up-right' : 'arrow-down-right';
  return '<span class="' . $class . '"><i class="bi bi-' . $icon . '"></i> ' . number_format(abs($pct), 1) . '%</span> vs last month';
}

// This month so far vs the same days last month
$today = date('Y-m-d');
$month_start = date('Y-m-01');
$last_month_start = date('Y-m-01', strtotime('first day of last month'));
$last_month_days = (int) date('t', strtotime($last_month_start));
$last_month_end = date('Y-m-', strtotime($last_month_start)) . str_pad(min((int) date('j'), $last_month_days), 2, '0', STR_PAD_LEFT);

$pl_now = pl_totals($conn, $company_id, $month_start, $today);
$pl_prev = pl_totals($conn, $company_id, $last_month_start, $last_month_end);

// Bank balance (cash and bank accounts, all time)
$cash_accounts = "SELECT account_id FROM incomes WHERE company_id = '$company_id'
UNION SELECT account_id FROM expenses WHERE company_id = '$company_id'
UNION SELECT account_id FROM payments WHERE company_id = '$company_id'";
$cash_account_count = (int) $conn->query("SELECT COUNT(*) AS v FROM ($cash_accounts) t")->fetch_assoc()['v'];
$cash_opening = money_value($conn, "SELECT COALESCE(SUM(CASE WHEN opening_balance_side = 'DEBIT' THEN opening_balance ELSE -opening_balance END), 0) AS v FROM accounts WHERE company_id = '$company_id' AND account_id IN ($cash_accounts)");
$cash_income = money_value($conn, "SELECT COALESCE(SUM(amount), 0) AS v FROM incomes WHERE company_id = '$company_id' AND income_date <= '$today'");
$cash_expense = money_value($conn, "SELECT COALESCE(SUM(amount), 0) AS v FROM expenses WHERE company_id = '$company_id' AND expense_date <= '$today'");
$cash_receipts = money_value($conn, "SELECT COALESCE(SUM(amount), 0) AS v FROM payments WHERE company_id = '$company_id' AND payment_type = 'RECEIPT' AND payment_date <= '$today'");
$cash_payments = money_value($conn, "SELECT COALESCE(SUM(amount), 0) AS v FROM payments WHERE company_id = '$company_id' AND payment_type = 'PAYMENT' AND payment_date <= '$today'");
$bank_balance = $cash_opening + $cash_income - $cash_expense + $cash_receipts - $cash_payments;

// Last 12 months for the chart (shown in lakh)
$chart_labels = [];
$chart_income = [];
$chart_expenses = [];
for ($i = 11; $i >= 0; $i--) {
  $ts = strtotime(date('Y-m-01') . " -$i month");
  $m = pl_totals($conn, $company_id, date('Y-m-01', $ts), date('Y-m-t', $ts));
  $chart_labels[] = date('M Y', $ts);
  $chart_income[] = round($m['revenue'] / 100000, 2);
  $chart_expenses[] = round($m['expenses'] / 100000, 2);
}

// Expense by category (this month)
$colors = ['#4388ee', '#43c99b', '#ffa43b', '#9257ed', '#9aa7bd'];
$category_rows = $conn->query("SELECT COALESCE(ec.category_name, 'Uncategorized') AS name, SUM(e.amount) AS total
FROM expenses e LEFT JOIN expense_categories ec ON ec.expense_category_id = e.expense_category_id
WHERE e.company_id = '$company_id' AND e.expense_date BETWEEN '$month_start' AND '$today'
GROUP BY name ORDER BY total DESC");

$categories = [];
$category_total = 0;
while ($c = $category_rows->fetch_assoc()) {
  $categories[] = ['name' => $c['name'], 'total' => (float) $c['total']];
  $category_total += (float) $c['total'];
}
if (count($categories) > 5) {
  $other = 0;
  foreach (array_slice($categories, 4) as $c) {
    $other += $c['total'];
  }
  $categories = array_slice($categories, 0, 4);
  $categories[] = ['name' => 'Other', 'total' => $other];
}
$donut_labels = [];
$donut_data = [];
$donut_colors = [];
foreach ($categories as $i => $c) {
  $donut_labels[] = $c['name'];
  $donut_data[] = round($c['total'], 2);
  $donut_colors[] = $colors[$i];
}
if (!$categories) {
  $donut_labels = ['No expenses'];
  $donut_data = [1];
  $donut_colors = ['#e4eaf5'];
}

// Recent transactions
$transactions = $conn->query("(SELECT i.income_date AS d, COALESCE(NULLIF(i.description, ''), ic.category_name) AS descr, 'Income' AS t, i.amount AS amt, 'Completed' AS st, i.created_at AS c
FROM incomes i LEFT JOIN income_categories ic ON ic.income_category_id = i.income_category_id WHERE i.company_id = '$company_id')
UNION ALL
(SELECT e.expense_date, COALESCE(NULLIF(e.description, ''), ec.category_name), 'Expense', e.amount, 'Completed', e.created_at
FROM expenses e LEFT JOIN expense_categories ec ON ec.expense_category_id = e.expense_category_id WHERE e.company_id = '$company_id')
UNION ALL
(SELECT s.sale_date, CONCAT('Sales Invoice ', s.invoice_number), 'Income', s.total_amount, s.status, s.created_at
FROM sales s WHERE s.company_id = '$company_id' AND s.status NOT IN ('DRAFT', 'CANCELLED'))
ORDER BY d DESC, c DESC LIMIT 5");

$hour = (int) date('G');
if ($hour < 12) {
  $greeting = 'Good Morning';
} elseif ($hour < 18) {
  $greeting = 'Good Afternoon';
} else {
  $greeting = 'Good Evening';
}
$admin_name = $_SESSION['name'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BizLedger Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/dashboard-shared.css" rel="stylesheet">
  <link href="assets/css/glassmorphism.css" rel="stylesheet">
  <style>
    :root {
      --blue: #246bfa;
      --ink: #10234b;
      --muted: #71809e;
      --line: #e4eaf5;
      --page: #f5f7fc
    }

    * {
      box-sizing: border-box
    }

    body {
      margin: 0;
      background: var(--page);
      font-family: Inter, system-ui, -apple-system, "Segoe UI", sans-serif;
      color: var(--ink)
    }

    .dashboard-body {
      width: 100%;
      padding: 24px 26px 30px
    }

    .welcome-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 16px;
      margin-bottom: 22px
    }

    .eyebrow {
      display: inline-block;
      background: #e2edff;
      color: #1e5bd5;
      font-size: 11px;
      font-weight: 700;
      padding: 5px 10px;
      border-radius: 7px;
      margin-bottom: 5px
    }

    .welcome-row h1 {
      font-size: clamp(22px, 2vw, 30px);
      font-weight: 750;
      letter-spacing: -.7px;
      margin: 0 0 4px
    }

    .welcome-row p {
      font-size: 13px;
      color: var(--muted);
      margin: 0
    }

    .date-btn {
      border: 1px solid var(--line);
      background: #fff;
      border-radius: 9px;
      padding: 9px 13px;
      font-size: 12px;
      color: #25395f;
      white-space: nowrap
    }

    .stat-card,
    .panel,
    .promo-card {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: 14px;
      box-shadow: 0 2px 7px rgba(28, 55, 100, .035)
    }

    .stat-card {
      height: 100%;
      min-height: 112px;
      padding: 16px 17px;
      position: relative;
      overflow: hidden
    }

    .stat-top {
      display: flex;
      align-items: center;
      gap: 12px;
      position: relative;
      z-index: 1
    }

    .stat-icon {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      display: grid;
      place-items: center;
      font-size: 20px;
      flex: 0 0 auto
    }

    .stat-label {
      font-size: 12px;
      color: #526486;
      margin-bottom: 3px
    }

    .stat-value {
      font-size: clamp(19px, 1.8vw, 25px);
      font-weight: 750;
      letter-spacing: -.5px;
      white-space: nowrap
    }

    .stat-note {
      font-size: 10px;
      color: var(--muted);
      margin: 6px 0 0 55px;
      position: relative;
      z-index: 1
    }

    .up {
      color: #08a66a;
      font-weight: 700
    }

    .down {
      color: #ed4c55;
      font-weight: 700
    }

    .stat-wave {
      position: absolute;
      bottom: -1px;
      left: 0;
      width: 100%;
      height: 35px;
      opacity: .16
    }

    .blue {
      background: #e4efff;
      color: #2470f7
    }

    .green {
      background: #def7eb;
      color: #0aa66a
    }

    .purple {
      background: #efe5ff;
      color: #8a43ee
    }

    .orange {
      background: #fff0dc;
      color: #f28a16
    }

    .panel {
      padding: 16px 17px;
      height: 100%
    }

    .panel-title {
      font-size: 15px;
      font-weight: 750;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 9px
    }

    .panel-title i {
      font-size: 17px;
      color: var(--blue)
    }

    .period-group .btn {
      font-size: 10px;
      padding: 5px 11px;
      border-color: #dce4f2;
      color: #334666
    }

    .period-group .btn.active {
      background: var(--blue);
      border-color: var(--blue);
      color: #fff
    }

    .chart-wrap {
      height: 205px;
      margin-top: 12px;
      position: relative
    }

    .donut-wrap {
      height: 190px;
      max-width: 215px;
      margin: auto
    }

    .expense-legend {
      font-size: 12px
    }

    .legend-dot {
      width: 9px;
      height: 9px;
      border-radius: 50%;
      display: inline-block;
      margin-right: 8px
    }

    .table {
      --bs-table-bg: transparent;
      margin-bottom: 0;
      vertical-align: middle
    }

    .table thead th {
      font-size: 10px;
      color: #617291;
      font-weight: 700;
      background: #f6f8fc;
      border-bottom: 1px solid #e9eef7;
      padding: 9px 8px;
      white-space: nowrap
    }

    .table tbody td {
      font-size: 11px;
      color: #273b60;
      padding: 9px 8px;
      border-color: #edf1f7;
      white-space: nowrap
    }

    .type-badge,
    .status-badge {
      font-size: 9px;
      font-weight: 700;
      border-radius: 20px;
      padding: 4px 9px;
      display: inline-block
    }

    .type-income {
      background: #def7eb;
      color: #07945d
    }

    .type-expense {
      background: #e4efff;
      color: #2164e8
    }

    .status-badge {
      background: #def7eb;
      color: #078d58
    }

    .quick-action {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 7px;
      min-height: 68px;
      border-radius: 10px;
      text-decoration: none;
      font-size: 10px;
      font-weight: 650;
      border: 1px solid transparent
    }

    .quick-action i {
      font-size: 18px
    }

    .quick-blue {
      background: #eaf1ff;
      color: #2166e9;
      border-color: #e1eaff
    }

    .quick-green {
      background: #e9f8f0;
      color: #07945d;
      border-color: #e0f2e8
    }

    .quick-purple {
      background: #f1eaff;
      color: #8746e8;
      border-color: #eee3ff
    }

    .quick-orange {
      background: #fff2e3;
      color: #e8790b;
      border-color: #ffead2
    }

    .promo-card {
      height: 100%;
      padding: 17px;
      background: linear-gradient(145deg, #e5efff, #d7e5ff);
      display: flex;
      flex-direction: column;
      justify-content: center;
      overflow: hidden
    }

    .promo-art {
      font-size: 35px;
      color: #2775f5;
      text-align: right;
      line-height: 1;
      margin-bottom: 12px
    }

    .promo-card h3 {
      font-size: 15px;
      font-weight: 750;
      margin: 0 0 7px
    }

    .promo-card p {
      font-size: 11px;
      line-height: 1.5;
      color: #40577d;
      margin: 0 0 12px
    }

    .promo-card .btn {
      align-self: flex-start;
      background: var(--blue);
      color: white;
      border: 0;
      border-radius: 7px;
      font-size: 10px;
      padding: 7px 11px
    }

    @media(max-width:767.98px) {
      .dashboard-body {
        padding: 16px 12px
      }

      .welcome-row {
        align-items: flex-start;
        flex-direction: column
      }

      .date-btn {
        align-self: flex-start
      }

      .chart-wrap {
        height: 190px
      }

      .panel {
        padding: 14px
      }

      .table-responsive {
        margin: 0 -4px
      }

      .promo-card {
        min-height: 180px
      }
    }
  </style>

</head>

<body class="bg-body-tertiary dashboard-page">
  <div class="container-fluid px-0">
    <div class="row min-vh-100">
      <div class="col-lg-3 col-xl-2 p-0 bg-dark">
        <?php include 'include/adminsidebar.php'; ?>
      </div>
      <main class="col-12 col-lg-9 col-xl-10 p-0">
        <?php include 'include/adminheadbar.php'; ?>

        <div class="dashboard-body">
          <section class="welcome-row">
            <div><span class="eyebrow">Welcome back!</span>
              <h1><?= $greeting ?>, <?= htmlspecialchars($admin_name) ?>!</h1>
              <p>Here's what's happening with your business today.</p>
            </div>
            <button class="date-btn"><i class="bi bi-calendar3 me-2"></i><?= date('F j, Y') ?></button>
          </section>

          <section class="row g-3 mb-3">
            <div class="col-12 col-sm-6 col-xl-3">
              <article class="stat-card">
                <div class="stat-top">
                  <div class="stat-icon blue"><i class="bi bi-bar-chart-fill"></i></div>
                  <div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value"><?= bdt($pl_now['revenue']) ?></div>
                  </div>
                </div>
                <div class="stat-note"><?= change_note($pl_now['revenue'], $pl_prev['revenue']) ?></div><svg class="stat-wave" viewBox="0 0 300 40" preserveAspectRatio="none">
                  <path d="M0 27 C35 4 48 35 83 22 S130 8 160 25 S215 32 245 12 S275 30 300 8 V40 H0Z" fill="#2673fa" />
                </svg>
              </article>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
              <article class="stat-card">
                <div class="stat-top">
                  <div class="stat-icon green"><i class="bi bi-wallet2"></i></div>
                  <div>
                    <div class="stat-label">Total Expenses</div>
                    <div class="stat-value"><?= bdt($pl_now['expenses']) ?></div>
                  </div>
                </div>
                <div class="stat-note"><?= change_note($pl_now['expenses'], $pl_prev['expenses'], false) ?></div><svg class="stat-wave" viewBox="0 0 300 40" preserveAspectRatio="none">
                  <path d="M0 27 C35 4 48 35 83 22 S130 8 160 25 S215 32 245 12 S275 30 300 8 V40 H0Z" fill="#13b981" />
                </svg>
              </article>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
              <article class="stat-card">
                <div class="stat-top">
                  <div class="stat-icon purple"><i class="bi bi-graph-up-arrow"></i></div>
                  <div>
                    <div class="stat-label">Net Profit</div>
                    <div class="stat-value"><?= bdt($pl_now['net']) ?></div>
                  </div>
                </div>
                <div class="stat-note"><?= change_note($pl_now['net'], $pl_prev['net']) ?></div><svg class="stat-wave" viewBox="0 0 300 40" preserveAspectRatio="none">
                  <path d="M0 27 C35 4 48 35 83 22 S130 8 160 25 S215 32 245 12 S275 30 300 8 V40 H0Z" fill="#9b5cf6" />
                </svg>
              </article>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
              <article class="stat-card">
                <div class="stat-top">
                  <div class="stat-icon orange"><i class="bi bi-bank"></i></div>
                  <div>
                    <div class="stat-label">Bank Balance</div>
                    <div class="stat-value"><?= bdt($bank_balance) ?></div>
                  </div>
                </div>
                <div class="stat-note">Across <?= $cash_account_count ?> account<?= $cash_account_count == 1 ? '' : 's' ?></div><svg class="stat-wave" viewBox="0 0 300 40" preserveAspectRatio="none">
                  <path d="M0 27 C35 4 48 35 83 22 S130 8 160 25 S215 32 245 12 S275 30 300 8 V40 H0Z" fill="#f59e0b" />
                </svg>
              </article>
            </div>
          </section>

          <section class="row g-3 mb-3">
            <div class="col-12 col-xl-7">
              <article class="panel">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                  <h2 class="panel-title"><i class="bi bi-bar-chart-line-fill"></i>Financial Overview</h2>
                  <div class="btn-group period-group" role="group"><button class="btn active" type="button" data-months="6">6 Months</button><button class="btn" type="button" data-months="12">1 Year</button></div>
                </div>
                <div class="chart-wrap"><canvas id="financialChart"></canvas></div>
              </article>
            </div>
            <div class="col-12 col-xl-5">
              <article class="panel">
                <h2 class="panel-title mb-3"><i class="bi bi-pie-chart-fill"></i>Expense by Category</h2>
                <div class="row align-items-center">
                  <div class="col-6">
                    <div class="donut-wrap"><canvas id="expenseChart"></canvas></div>
                  </div>
                  <div class="col-6">
                    <?php if ($categories) {
                      foreach ($categories as $i => $c) { ?>
                        <div class="expense-legend d-flex justify-content-between mb-3"><span><i class="legend-dot" style="background:<?= $colors[$i] ?>"></i><?= htmlspecialchars($c['name']) ?></span><b><?= number_format(($c['total'] / $category_total) * 100, 1) ?>%</b></div>
                      <?php }
                    } else { ?>
                      <div class="expense-legend text-secondary">No expenses recorded this month.</div>
                    <?php } ?>
                  </div>
                </div>
              </article>
            </div>
          </section>

          <section class="row g-3">
            <div class="col-12 col-xl-6">
              <article class="panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h2 class="panel-title"><i class="bi bi-arrow-left-right"></i>Recent Transactions</h2><a href="invoices.php" class="text-primary text-decoration-none fw-semibold" style="font-size:11px">View All <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php while ($t = $transactions->fetch_assoc()) {
                        $status_text = ucfirst(strtolower($t['st']));
                      ?>
                        <tr>
                          <td><?= date('M j, Y', strtotime($t['d'])) ?></td>
                          <td><?= htmlspecialchars($t['descr'] ?? '') ?></td>
                          <td><span class="type-badge <?= $t['t'] == 'Income' ? 'type-income' : 'type-expense' ?>"><?= $t['t'] ?></span></td>
                          <td><?= bdt($t['amt']) ?></td>
                          <td><span class="status-badge"><?= $status_text ?></span></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </article>
            </div>
            <div class="col-12 col-md-7 col-xl-3">
              <article class="panel">
                <h2 class="panel-title mb-3"><i class="bi bi-lightning-charge-fill"></i>Quick Actions</h2>
                <div class="row g-2">
                  <div class="col-6"><a href="sales.php" class="quick-action quick-blue"><i class="bi bi-arrow-left-right"></i>Add Transaction</a></div>
                  <div class="col-6"><a href="profit_loss.php" class="quick-action quick-green"><i class="bi bi-file-earmark-bar-graph"></i>Generate Report</a></div>
                  <div class="col-6"><a href="./employlist.php" class="quick-action quick-purple"><i class="bi bi-people"></i>Manage Users</a></div>
                  <div class="col-6"><a href="products.php" class="quick-action quick-orange"><i class="bi bi-box-seam"></i>Manage Products</a></div>
                </div>
              </article>
            </div>
            <div class="col-12 col-md-5 col-xl-3">
              <article class="promo-card">
                <div class="promo-art"><i class="bi bi-graph-up-arrow"></i> <i class="bi bi-calculator"></i></div>
                <h3>Grow Your Business</h3>
                <p>With BizLedger, manage your accounts smarter and easier.</p><a href="profit_loss.php" class="btn">View Reports <i class="bi bi-arrow-right ms-1"></i></a>
              </article>
            </div>
          </section>
        </div>
      </main>
      <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
      <script>
        const allLabels = <?= json_encode($chart_labels) ?>;
        const allIncome = <?= json_encode($chart_income) ?>;
        const allExpenses = <?= json_encode($chart_expenses) ?>;

        const financialCtx = document.getElementById('financialChart');
        const financialChart = new Chart(financialCtx, {
          type: 'line',
          data: {
            labels: allLabels.slice(-6),
            datasets: [{
              label: 'Income',
              data: allIncome.slice(-6),
              borderColor: '#2673fa',
              backgroundColor: 'rgba(38,115,250,.10)',
              fill: true,
              tension: .35,
              pointRadius: 3,
              borderWidth: 2
            }, {
              label: 'Expenses',
              data: allExpenses.slice(-6),
              borderColor: '#ff922e',
              backgroundColor: 'rgba(255,146,46,.08)',
              fill: true,
              tension: .35,
              pointRadius: 3,
              borderWidth: 2
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: 'top',
                labels: {
                  usePointStyle: true,
                  boxWidth: 6,
                  font: {
                    size: 10
                  }
                }
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                grid: {
                  color: '#edf1f7'
                },
                ticks: {
                  font: {
                    size: 9
                  },
                  callback: v => v + 'L'
                }
              },
              x: {
                grid: {
                  color: '#edf1f7'
                },
                ticks: {
                  font: {
                    size: 9
                  }
                }
              }
            }
          }
        });

        document.querySelectorAll('.period-group .btn').forEach(function (btn) {
          btn.addEventListener('click', function () {
            const months = parseInt(btn.dataset.months);
            document.querySelectorAll('.period-group .btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            financialChart.data.labels = allLabels.slice(-months);
            financialChart.data.datasets[0].data = allIncome.slice(-months);
            financialChart.data.datasets[1].data = allExpenses.slice(-months);
            financialChart.update();
          });
        });

        const expenseCtx = document.getElementById('expenseChart');
        new Chart(expenseCtx, {
          type: 'doughnut',
          data: {
            labels: <?= json_encode($donut_labels) ?>,
            datasets: [{
              data: <?= json_encode($donut_data) ?>,
              backgroundColor: <?= json_encode($donut_colors) ?>,
              borderWidth: 0,
              hoverOffset: 4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
              legend: {
                display: false
              }
            }
          }
        });
      </script>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/adminscript.js"></script>
</body>

</html>
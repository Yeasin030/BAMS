<?php
    session_start();
    if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    }

    if ($_SESSION['role'] != 'admin') {
    header("Location: /admin/unauthorize.php");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>BizLedger Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="assets/css/adminstyle.css" rel="stylesheet">
</head>

<body>
<div class="layout">

    <?php include 'include/adminsidebar.php'; ?>
  <main class="main">

    <?php include 'include/adminheadbar.php'; ?>
    
    <div class="content">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
        <div><span class="wb">Welcome back!</span><h2 id="greet">Good Morning, Admin!</h2><p class="text-secondary m-0">Here's what's happening with your business today.</p></div>
        <div class="datebox"><i class="bi bi-calendar3"></i><span id="today">November 6, 2026</span><i class="bi bi-chevron-down small"></i></div>
      </div>

      <div class="row g-3 mb-3" id="kpis"></div>

      <div class="row g-3 mb-3">
        <div class="col-xl-8"><div class="cardx h-100">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="ctitle"><i class="bi bi-bar-chart-fill text-primary"></i>Financial Overview</h6>
            <div class="seg" id="seg"><button class="on" data-r="6">6 Months</button><button data-r="12">1 Year</button><button data-r="c">Custom</button></div>
          </div>
          <div class="chart-box"><canvas id="line"></canvas></div>
        </div></div>
        <div class="col-xl-4"><div class="cardx h-100">
          <h6 class="ctitle mb-3"><i class="bi bi-pie-chart-fill text-primary"></i>Expense by Category</h6>
          <div class="row align-items-center g-2">
            <div class="col-sm-6"><div class="donut"><canvas id="donut"></canvas><div class="donut-c"><b>৳ 3.12L</b><span>Total Expenses</span></div></div></div>
            <div class="col-sm-6"><ul class="legend" id="legend"></ul></div>
          </div>
        </div></div>
      </div>

      <div class="row g-3">
        <div class="col-xl-6"><div class="cardx h-100">
          <div class="d-flex justify-content-between"><h6 class="ctitle"><i class="bi bi-arrow-left-right text-primary"></i>Recent Transactions</h6><a href="#" class="viewall">View All <i class="bi bi-arrow-right"></i></a></div>
          <div class="table-responsive mt-2"><table class="table align-middle tx">
            <thead><tr><th>Date</th><th>Description</th><th>Type</th><th>Amount</th><th>Status</th></tr></thead><tbody id="txBody"></tbody></table></div>
        </div></div>
        <div class="col-md-7 col-xl-3"><div class="cardx h-100">
          <h6 class="ctitle mb-3"><i class="bi bi-lightning-charge"></i>Quick Actions</h6>
          <div class="row g-2" id="actions"></div>
        </div></div>
        <div class="col-md-5 col-xl-3"><div class="promo h-100">
          <i class="bi bi-graph-up-arrow art"></i>
          <h6>Grow Your Business</h6><p>With BizLedger, manage your accounts smarter and easier.</p>
          <a href="#" class="btn btn-primary btn-sm">View Reports <i class="bi bi-arrow-right"></i></a>
        </div></div>
      </div>
    </div>
  </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="assets/js/adminscript.js"></script>
</body>

</html>

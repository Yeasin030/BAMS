<?php
    session_start();
    if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    }

    if ($_SESSION['role'] != 'accountant') {
    header("Location: /admin/unauthorize.php");
    }
  

?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>BizLedger – Accounting Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="assets/css/accountstyle.css" rel="stylesheet">
</head>

<body>
<div class="layout">

  <!-- Sidebar -->
   <?php include 'include/accountendheadbar.php'; ?>

  <!-- Main -->
  <main class="main">
    <!-- Headbar -->
    <?php include 'include/accountendsidebar.php'; ?>
  
    
    <section class="welcome">
      <div class="w-icon"><i class="bi bi-calculator"></i></div>
      <div><h4>Welcome back, Rafiul Islam!</h4><p>Here's your accounting overview for this month.</p></div>
      <div class="dropdown ms-auto">
        <button class="btn btn-white shadow-sm dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-calendar3 me-2"></i><span id="periodLabel">This Month</span></button>
        <ul class="dropdown-menu dropdown-menu-end period">
          <li><a class="dropdown-item" href="#">Today</a></li><li><a class="dropdown-item" href="#">This Week</a></li>
          <li><a class="dropdown-item" href="#">This Month</a></li><li><a class="dropdown-item" href="#">This Year</a></li>
        </ul>
      </div>
    </section>

    <!-- KPIs -->
    <div class="row g-3 mb-3" id="kpis"></div>

    <div class="row g-3 mb-3">
      <div class="col-xl-7">
        <div class="cardx h-100">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="ctitle"><i class="bi bi-bar-chart-fill text-primary"></i>Financial Overview</h6>
            <select class="form-select form-select-sm w-auto" id="rangeSel"><option>Monthly</option><option>Quarterly</option></select>
          </div>
          <div class="chart-box"><canvas id="lineChart"></canvas></div>
        </div>
      </div>
      <div class="col-xl-5">
        <div class="cardx h-100">
          <h6 class="ctitle mb-3"><i class="bi bi-lightning-charge"></i>Quick Actions</h6>
          <div class="row g-3" id="actions"></div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-xl-7">
        <div class="cardx h-100">
          <div class="d-flex justify-content-between mb-2">
            <h6 class="ctitle"><i class="bi bi-clock-history"></i>Recent Transactions</h6>
            <a href="#" class="small fw-semibold text-decoration-none">View All <i class="bi bi-arrow-right"></i></a>
          </div>
          <div class="table-responsive">
            <table class="table align-middle tx">
              <thead><tr><th>Date</th><th>Description</th><th>Category</th><th class="text-end">Amount</th><th class="text-center">Type</th><th class="text-center">Status</th></tr></thead>
              <tbody id="txBody"></tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-xl-5">
        <div class="cardx h-100">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="ctitle"><i class="bi bi-bar-chart-fill text-primary"></i>Account Summary</h6>
            <select class="form-select form-select-sm w-auto"><option>This Year</option><option>This Month</option></select>
          </div>
          <div class="row align-items-center">
            <div class="col-sm-6"><div class="donut"><canvas id="donut"></canvas><div class="donut-c"><b>৳ 4,80,000</b><span>Total</span></div></div></div>
            <div class="col-sm-6"><ul class="legend" id="legend"></ul></div>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="assets/js/accoountscript.js"></script>
</body>

</html>

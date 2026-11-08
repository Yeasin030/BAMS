<?php
    session_start();
    if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    }

    if ($_SESSION['role'] != 'accountant') {
    header("Location: unauthorize.php");
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
</head>

<body class="bg-body-tertiary">
<div class="container-fluid px-0">
<div class="row min-vh-100">
  <div class="col-lg-3 col-xl-2 p-0 bg-dark">
    <!-- Sidebar -->
    <?php include 'include/accountendsidebar.php'; ?>
  </div>
  <main class="col-12 col-lg-9 col-xl-10 p-0">
    <!-- Headbar -->
      <?php include 'include/accountendheadbar.php'; ?>
  
    <div class="p-3 p-md-4 p-xl-5">
    <section class="d-flex flex-wrap align-items-center gap-3 p-4 p-lg-5 mb-4 bg-white border rounded-4 shadow-sm">
      <div class="d-flex align-items-center justify-content-center rounded-4 bg-primary-subtle text-primary p-3 fs-3"><i class="bi bi-calculator"></i></div>
      <div>
        <div class="text-uppercase small fw-semibold text-primary mb-1">Accounting overview</div>
        <h1 class="h3 fw-bold mb-1">Welcome back, <?= htmlspecialchars($_SESSION['name'] ?? 'Accountant') ?>!</h1>
        <p class="text-secondary mb-0">Here’s your accounting overview for this month.</p>
      </div>
      <div class="dropdown ms-lg-auto">
        <button class="btn btn-light border shadow-sm dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-calendar3 me-2 text-primary"></i><span id="periodLabel">This Month</span></button>
        <ul class="dropdown-menu dropdown-menu-end period">
          <li><a class="dropdown-item" href="#">Today</a></li><li><a class="dropdown-item" href="#">This Week</a></li>
          <li><a class="dropdown-item" href="#">This Month</a></li><li><a class="dropdown-item" href="#">This Year</a></li>
        </ul>
      </div>
    </section>

    <!-- KPIs -->
    <div class="row g-3 mb-4" id="kpis"></div>

    <div class="row g-3 g-xl-4 mb-4">
      <div class="col-xl-7">
        <div class="card h-100 border-0 rounded-4 shadow-sm"><div class="card-body p-4">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div><h2 class="h5 fw-bold mb-1">Financial overview</h2><p class="small text-secondary mb-0">Income and expenses over time</p></div>
            <select class="form-select form-select-sm w-auto rounded-3" id="rangeSel" aria-label="Chart time range"><option>Monthly</option><option>Quarterly</option></select>
          </div>
          <div class="ratio ratio-21x9"><canvas id="lineChart" aria-label="Income and expenses chart"></canvas></div>
          </div>
        </div>
      </div>
      <div class="col-xl-5">
        <div class="card h-100 border-0 rounded-4 shadow-sm"><div class="card-body p-4">
          <div class="mb-3"><h2 class="h5 fw-bold mb-1">Quick actions</h2><p class="small text-secondary mb-0">Shortcuts to common tasks</p></div>
          <div class="row g-3" id="actions"></div>
        </div></div>
      </div>
    </div>

    <div class="row g-3 g-xl-4">
      <div class="col-xl-7">
        <div class="card h-100 border-0 rounded-4 shadow-sm"><div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div><h2 class="h5 fw-bold mb-1">Recent transactions</h2><p class="small text-secondary mb-0">Latest activity in your account</p></div>
            <a href="#" class="btn btn-sm btn-outline-primary rounded-3">View all <i class="bi bi-arrow-right ms-1"></i></a>
          </div>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light"><tr><th class="small text-secondary fw-semibold">Date</th><th class="small text-secondary fw-semibold">Description</th><th class="small text-secondary fw-semibold">Category</th><th class="small text-secondary fw-semibold text-end">Amount</th><th class="small text-secondary fw-semibold text-center">Type</th><th class="small text-secondary fw-semibold text-center">Status</th></tr></thead>
              <tbody id="txBody"></tbody>
            </table>
          </div></div>
        </div>
      </div>
      <div class="col-xl-5">
        <div class="card h-100 border-0 rounded-4 shadow-sm"><div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
            <div><h2 class="h5 fw-bold mb-1">Account summary</h2><p class="small text-secondary mb-0">Income and expenses breakdown</p></div>
            <select class="form-select form-select-sm w-auto rounded-3" aria-label="Summary time range"><option>This Year</option><option>This Month</option></select>
          </div>
          <div class="row align-items-center">
            <div class="col-sm-6"><div class="ratio ratio-1x1"><canvas id="donut" aria-label="Account summary chart"></canvas><div class="position-absolute top-50 start-50 translate-middle text-center pe-none"><b class="fs-5">৳ 4,80,000</b><span class="d-block small text-secondary">Total</span></div></div></div>
            <div class="col-sm-6"><ul class="list-unstyled mb-0" id="legend"></ul></div>
          </div>
        </div></div>
      </div>
    </div>
    </div>
  </main>
</div>
  </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="assets/js/accoountscript.js"></script>
</body>

</html>

<?php
    session_start();
    if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit;
    }

    if ($_SESSION['role'] != 'accountant') {
    header("Location: unauthorize.php");
    exit;
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
<link href="assets/css/dashboard-shared.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary dashboard-page">
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
    <section class="dashboard-hero rounded-4 shadow-sm text-white p-4 p-lg-5 mb-4">
      <div class="dashboard-hero-content d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
        <div>
          <span class="badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 mb-3"><i class="bi bi-calculator me-1" aria-hidden="true"></i> Accounting overview</span>
          <h1 class="h2 fw-bold mb-2">Welcome back, <?= htmlspecialchars($_SESSION['name'] ?? 'Accountant') ?>!</h1>
          <p class="mb-0 text-white-50">Your financial activity and account performance at a glance.</p>
        </div>
        <span class="badge rounded-pill bg-white bg-opacity-10 border border-white border-opacity-25 text-white px-3 py-2"><i class="bi bi-calendar3 me-2" aria-hidden="true"></i>This month</span>
      </div>
    </section>

    <section aria-label="Accounting performance" class="mb-4">
      <div class="mb-3"><h2 class="h5 dashboard-section-title fw-bold mb-1">Business performance</h2><p class="small dashboard-subtitle mb-0">Key figures at a glance</p></div>
    <div class="row g-3 mb-4" id="kpis"></div>
    </section>

    <div class="row g-3 g-xl-4 mb-4">
      <div class="col-xl-7">
        <div class="card dashboard-card h-100 rounded-4 shadow-sm"><div class="card-body p-4">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div><h2 class="h5 dashboard-section-title fw-bold mb-1">Financial overview</h2><p class="small dashboard-subtitle mb-0">Income and expenses over time</p></div>
            <select class="form-select form-select-sm w-auto rounded-3" id="rangeSel" aria-label="Chart time range"><option>Monthly</option><option>Quarterly</option></select>
          </div>
          <div class="ratio ratio-21x9"><canvas id="lineChart" role="img" aria-label="Income and expenses chart"></canvas></div>
          </div>
        </div>
      </div>
      <div class="col-xl-5">
        <div class="card dashboard-card h-100 rounded-4 shadow-sm"><div class="card-body p-4">
          <div class="mb-3"><h2 class="h5 dashboard-section-title fw-bold mb-1">Accounting tools</h2><p class="small dashboard-subtitle mb-0">Available accounting modules</p></div>
          <div class="row g-3" id="actions"></div>
        </div></div>
      </div>
    </div>

    <div class="row g-3 g-xl-4">
      <div class="col-xl-7">
        <div class="card dashboard-card h-100 rounded-4 shadow-sm"><div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div><h2 class="h5 dashboard-section-title fw-bold mb-1">Recent transactions</h2><p class="small dashboard-subtitle mb-0">Latest activity in your account</p></div>
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
        <div class="card dashboard-card h-100 rounded-4 shadow-sm"><div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
            <div><h2 class="h5 dashboard-section-title fw-bold mb-1">Account summary</h2><p class="small dashboard-subtitle mb-0">Income and expenses breakdown</p></div>
            <span class="badge bg-primary-subtle text-primary-emphasis">This year</span>
          </div>
          <div class="row align-items-center">
            <div class="col-sm-6"><div class="ratio ratio-1x1"><canvas id="donut" role="img" aria-label="Account summary chart"></canvas><div class="position-absolute top-50 start-50 translate-middle text-center pe-none"><b class="fs-5">৳ 4,80,000</b><span class="d-block small text-secondary">Total</span></div></div></div>
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

<?php
    session_start();
    if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    }

    if ($_SESSION['role'] != 'admin') {
    header("Location: unauthorize.php");
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
</head>

<body class="bg-body-tertiary">
<div class="container-fluid px-0">
<div class="row min-vh-100">
  <div class="col-lg-3 col-xl-2 p-0 bg-dark">
    
  <?php include 'include/adminsidebar.php'; ?>
</div>
  <main class="col-12 col-lg-9 col-xl-10 p-0">

    <?php include 'include/adminheadbar.php'; ?>
    
    <div class="p-3 p-md-4 p-xl-5">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
        <div><span class="badge bg-primary-subtle text-primary-emphasis">Admin dashboard</span><h1 class="h2 fw-bold mt-2 mb-1" id="greet">Good Morning, Admin!</h1><p class="text-secondary m-0">Here's what's happening with your business today.</p></div>
        <div class="d-flex align-items-center gap-2 bg-white border rounded-3 shadow-sm px-3 py-2"><i class="bi bi-calendar3 text-primary"></i><span id="today">November 6, 2026</span></div>
      </div>

      <div class="row g-3 mb-4" id="kpis"></div>

      <div class="row g-3 g-xl-4 mb-4">
        <div class="col-xl-8"><div class="card h-100 border-0 rounded-4 shadow-sm">
          <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div><h2 class="h5 fw-bold mb-1">Financial overview</h2><p class="small text-secondary mb-0">Track revenue and expenses</p></div>
            <div class="btn-group btn-group-sm" id="seg"><button class="btn btn-primary" data-r="6">6 Months</button><button class="btn btn-outline-primary" data-r="12">1 Year</button><button class="btn btn-outline-primary" data-r="c">Custom</button></div>
          </div>
          <div class="ratio ratio-21x9"><canvas id="line" aria-label="Financial overview chart"></canvas></div>
          </div>
        </div></div>
        <div class="col-xl-4"><div class="card h-100 border-0 rounded-4 shadow-sm"><div class="card-body p-4">
          <div class="mb-3"><h2 class="h5 fw-bold mb-1">Expense by category</h2><p class="small text-secondary mb-0">This month’s breakdown</p></div>
          <div class="row align-items-center g-2">
            <div class="col-sm-6"><div class="ratio ratio-1x1"><canvas id="donut"></canvas><div class="position-absolute top-50 start-50 translate-middle text-center pe-none"><b>৳ 3.12L</b><span class="d-block small text-secondary">Total Expenses</span></div></div></div>
            <div class="col-sm-6"><ul class="list-unstyled mb-0" id="legend"></ul></div>
          </div>
        </div></div></div>
      </div>

      <div class="row g-3 g-xl-4">
        <div class="col-xl-6"><div class="card h-100 border-0 rounded-4 shadow-sm"><div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center mb-3"><div><h2 class="h5 fw-bold mb-1">Recent transactions</h2><p class="small text-secondary mb-0">The latest business activity</p></div><a href="#" class="btn btn-sm btn-outline-primary rounded-3">View all <i class="bi bi-arrow-right ms-1"></i></a></div>
          <div class="table-responsive"><table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th class="small text-secondary">Date</th><th class="small text-secondary">Description</th><th class="small text-secondary">Type</th><th class="small text-secondary">Amount</th><th class="small text-secondary">Status</th></tr></thead><tbody id="txBody"></tbody></table></div>
        </div></div></div>
        <div class="col-md-7 col-xl-3"><div class="card h-100 border-0 rounded-4 shadow-sm"><div class="card-body p-4">
          <div class="mb-3"><h2 class="h5 fw-bold mb-1">Quick actions</h2><p class="small text-secondary mb-0">Common tasks</p></div>
          <div class="row g-2" id="actions"></div>
        </div></div></div>
        <div class="col-md-5 col-xl-3"><div class="card h-100 border-0 rounded-4 bg-primary-subtle">
          <div class="card-body d-flex flex-column justify-content-end p-4">
          <i class="bi bi-graph-up-arrow display-5 text-primary mb-auto"></i>
          <h2 class="h6 fw-bold">Grow Your Business</h2><p class="small">With BizLedger, manage your accounts smarter and easier.</p>
          <a href="#" class="btn btn-primary btn-sm">View Reports <i class="bi bi-arrow-right"></i></a>
          </div>
        </div></div>
      </div>
    </div>
  </main>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="assets/js/adminscript.js"></script>
</body>

</html>

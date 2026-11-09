<?php
    session_start();
    if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit;
    }

    if ($_SESSION['role'] != 'employees') {
    header("Location: unauthorize.php");
    exit;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>BizLedger Employee Portal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="assets/css/dashboard-shared.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary dashboard-page">
<div class="container-fluid px-0">
<div class="row min-vh-100">
    <div class="col-lg-3 col-xl-2 p-0 bg-dark">
      <!-- Sidebar -->
        <?php include 'include/employsidebar.php'; ?>
    </div>
    <main class="col-12 col-lg-9 col-xl-10 p-0">
        <!-- Header -->
        <?php include_once 'include/employheadbar.php'; ?>

    <div class="p-3 p-md-4 p-xl-5">
    <section class="dashboard-hero rounded-4 shadow-sm text-white p-4 p-lg-5 mb-4">
      <div class="dashboard-hero-content d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
        <div><span class="badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 mb-3">Employee portal</span><h1 class="h2 fw-bold mb-2">Welcome back, <?= htmlspecialchars($_SESSION['name'] ?? 'Employee') ?>!</h1><p class="mb-0 text-white-50">Your profile and latest company updates at a glance.</p></div>
        <i class="bi bi-laptop display-5 text-white-50" aria-hidden="true"></i>
      </div>
    </section>

    <div class="row g-3 mb-3">
      <div class="col-xl-8">
        <div class="card dashboard-card h-100 rounded-4 shadow-sm"><div class="card-body p-4">
          <h2 class="h5 dashboard-section-title fw-bold"><i class="bi bi-people-fill text-primary me-2"></i>Employee profile</h2><hr class="mt-3 mb-0">
          <div class="row g-0">
            <div class="col-md-8 d-flex flex-column flex-sm-row align-items-center align-items-sm-start gap-4 pt-3 pe-3">
              <div class="position-relative flex-shrink-0">
                <div class="ratio ratio-1x1 rounded-circle overflow-hidden bg-primary-subtle text-primary d-flex align-items-center justify-content-center fs-2" style="width: 9rem"><div class="d-flex align-items-center justify-content-center"><i class="bi bi-person-fill" aria-hidden="true"></i></div></div>
              </div>
              <div class="flex-grow-1 min-w-0">
                <div class="d-flex align-items-center gap-2 mb-3"><h3 class="h5 m-0 fw-bold"><?= htmlspecialchars($_SESSION['name'] ?? 'Employee') ?></h3><span class="badge text-bg-primary"><?= htmlspecialchars($_SESSION['role'] ?? 'employees') ?></span></div>
                
                <dl class="mb-0" id="info"></dl>
                <script>
                  window.employeeProfile = <?= json_encode(
                      ['email' => $_SESSION['email'] ?? '', 'role' => $_SESSION['role'] ?? ''],
                      JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
                  ) ?>;
                </script>
              </div>
            </div>
            <div class="col-md-4 border-top border-md-top-0 border-md-start ps-md-3 pt-3">
              <h6 class="fw-bold mb-3">Quick Info</h6>
              <div id="quick"></div>
            </div></div>
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="card dashboard-card h-100 rounded-4 shadow-sm"><div class="card-body p-4">
          <h2 class="h5 dashboard-section-title fw-bold"><i class="bi bi-lightning-charge text-primary me-2"></i>Employee services</h2><hr class="mt-3">
          <div class="row g-3" id="actions"></div>
        </div></div>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-xl-8">
        <div class="card dashboard-card h-100 rounded-4 shadow-sm"><div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center"><h2 class="h5 dashboard-section-title fw-bold"><i class="bi bi-clock-history text-primary me-2"></i>Recent activity</h2></div>
          <hr class="mt-3">
          <ul class="list-group list-group-flush" id="activity"></ul>
        </div></div>
      </div>
      <div class="col-xl-4">
        <div class="card dashboard-card h-100 rounded-4 shadow-sm"><div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center"><h2 class="h5 dashboard-section-title fw-bold"><i class="bi bi-megaphone-fill text-primary me-2"></i>Company notices</h2></div>
          <hr class="mt-3">
          <ul class="list-group list-group-flush" id="notices"></ul>
        </div></div>
      </div>
    </div>
    </div>
  </main>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/employeesscript.js"></script>
</body>

</html>

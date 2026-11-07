<?php
    session_start();
    if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    }

    if ($_SESSION['role'] != 'employees') {
    header("Location: /admin/unauthorize.php");
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
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="assets/css/employeesstyle.css" rel="stylesheet">
</head>

<body>
<div class="layout">
    <!-- Sidebar -->
    <?php include ("include/employsidebar.php");?>
    
    <main class="main">
        <!-- Header -->

    <?php include_once ("include/employheadbar.php"); ?>

    <section class="hero">
      <div><h3>Welcome back, Md. Tanvir Hasan! 👋</h3><p>Stay productive and keep up with your work details.</p></div>
      <i class="bi bi-laptop hero-art"></i>
    </section>

    <div class="row g-3 mb-3">
      <div class="col-xl-8">
        <div class="cardx h-100">
          <h6 class="ctitle"><i class="bi bi-people-fill text-primary"></i>Employee Profile</h6><hr class="mt-3 mb-0">
          <div class="row g-0">
            <div class="col-md-8 d-flex gap-4 pt-3 pe-3">
              <div class="photo"><div class="ph">TH</div><button class="edit" id="editBtn" title="Edit photo"><i class="bi bi-pencil"></i></button></div>
              <div class="flex-grow-1 min-w-0">
                <div class="d-flex align-items-center gap-2 mb-3"><h5 class="m-0 fw-bold">Md. Tanvir Hasan</h5><span class="tag">Employee</span></div>
                <dl class="info" id="info"></dl>
              </div>
            </div>
            <div class="col-md-4 qi">
              <h6 class="fw-bold mb-3">Quick Info</h6>
              <div id="quick"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="cardx h-100">
          <h6 class="ctitle"><i class="bi bi-lightning-charge"></i>Quick Actions</h6><hr class="mt-3">
          <div class="row g-3" id="actions"></div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-xl-8">
        <div class="cardx h-100">
          <div class="d-flex justify-content-between"><h6 class="ctitle"><i class="bi bi-clock-history"></i>Recent Activity</h6><a href="#" class="viewall">View All <i class="bi bi-arrow-right"></i></a></div>
          <hr class="mt-3">
          <ul class="timeline" id="activity"></ul>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="cardx h-100">
          <div class="d-flex justify-content-between"><h6 class="ctitle"><i class="bi bi-megaphone-fill"></i>Company Notices</h6><a href="#" class="viewall">View All <i class="bi bi-arrow-right"></i></a></div>
          <hr class="mt-3">
          <ul class="notices" id="notices"></ul>
        </div>
      </div>
    </div>
  </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/employeesscript.js"></script>
</body>

</html>

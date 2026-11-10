<?php
session_start();
include_once 'assets/db/bdconfig.php';

if (($_SESSION['role'] ?? '') !== 'admin') {
  header('Location: login.php');
  exit;
}

if (empty($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$msg = '';
$msgType = 'info';
$allowedRoles = ['admin', 'accountant', 'employees'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
    $msg = "Invalid request. Please try again.";
    $msgType = 'danger';
  } else {
    $companyId = (int) ($_SESSION['company_id'] ?? 1);
    $name   = trim($_POST['employee_name']);
    $email  = trim($_POST['email']);
    $hash   = sha1($_POST['password']);
    $role   = $_POST['role'] ?? '';
    $phone  = trim($_POST['phone']);
    $desig  = trim($_POST['designation']);
    $jdate  = $_POST['joining_date'] ?: null;
    $salary = (float) ($_POST['basic_salary'] ?: 0);

    if (!in_array($role, $allowedRoles, true)) {
      $msg = "Invalid role selected.";
      $msgType = 'danger';
    } else {
      try {
        
        $stmt = $conn->prepare(
          "INSERT INTO employees
             (company_id, employee_name, phone, designation, joining_date, basic_salary, status,
              login_email, login_password, login_role)
           VALUES (?, ?, ?, ?, ?, ?, 1, ?, ?, ?)"
        );
        $stmt->bind_param(
          "issssdsss",
          $companyId, $name, $phone, $desig, $jdate, $salary,
          $email, $hash, $role
        );
        $stmt->execute();
        $stmt->close();

        $msg = "Employee added successfully";
        $msgType = 'success';
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
      } catch (mysqli_sql_exception $e) {
        $msg = ($e->getCode() == 1062) ? "This email already exists" : "Error: " . $e->getMessage();
        $msgType = 'danger';
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Employee | BizLedger</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

  <div class="container-fluid">
    <div class="row min-vh-100">
      <div class="col-lg-3 col-xl-2 p-0"><?php include 'include/adminsidebar.php'; ?></div>

      <div class="col-12 col-lg-9 col-xl-10 p-0">

        <?php include 'include/adminheadbar.php'; ?>

        <main class="p-3 p-md-4 p-xl-5">

          <!-- Hero -->
          <div class="bg-primary-subtle border-bottom"></div>

          <?php if ($msg): ?>
            <div class="alert alert-<?= $msgType ?> alert-dismissible fade show rounded-4 shadow-sm" role="alert">
              <?= htmlspecialchars($msg) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <section class="card dashboard-card rounded-4 shadow-sm" aria-labelledby="employee-form-title">
            <div class="card-body p-0">

              <div class="d-flex align-items-center gap-3 p-3 p-md-4 border-bottom">
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary flex-shrink-0" style="width: 2.75rem; height: 2.75rem">
                  <i class="bi bi-person-plus fs-5" aria-hidden="true"></i>
                </span>
                <div>
                  <h2 class="h5 dashboard-section-title fw-bold mb-1" id="employee-form-title">Employee details</h2>
                  <p class="small dashboard-subtitle mb-0">Enter the required account and job information.</p>
                </div>
              </div>

              <form method="post" class="p-3 p-md-4">
                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

                <!-- Account information -->
                <div class="mb-4">
                  <h3 class="h6 text-uppercase text-secondary fw-semibold small mb-3">
                    <i class="bi bi-shield-lock me-1" aria-hidden="true"></i>Account information
                  </h3>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label fw-semibold" for="employee_name">Full name</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-person"></i></span>
                        <input id="employee_name" name="employee_name" class="form-control" placeholder="e.g. Alex Morgan" required>
                      </div>
                    </div>
                    
                    <div class="col-md-6">
                      <label class="form-label fw-semibold" for="email">Email (login)</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-envelope"></i></span>
                        <input id="email" type="email" name="email" class="form-control" placeholder="name@company.com" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-semibold" for="password">Password</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-key"></i></span>
                        <input id="password" type="password" name="password" class="form-control" placeholder="Password" required>
                        <button class="btn btn-light border" type="button" id="togglePassword" aria-label="Show or hide password">
                          <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-semibold" for="role">Role</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-person-badge"></i></span>
                        <select id="role" name="role" class="form-select" required>
                          <option value="" disabled selected>Select role</option>
                          <?php foreach ($allowedRoles as $r): ?>
                            <option value="<?= htmlspecialchars($r) ?>"><?= ucfirst(htmlspecialchars($r)) ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <hr class="text-secondary-emphasis opacity-25">

                <!-- Job information -->
                <div class="mb-2 mt-4">
                  <h3 class="h6 text-uppercase text-secondary fw-semibold small mb-3">
                    <i class="bi bi-briefcase me-1" aria-hidden="true"></i>Job information
                  </h3>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label fw-semibold" for="phone">Phone</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-telephone"></i></span>
                        <input id="phone" name="phone" class="form-control" placeholder="Phone">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-semibold" for="designation">Designation</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-award"></i></span>
                        <input id="designation" name="designation" class="form-control" placeholder="Designation">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-semibold" for="joining_date">Joining date</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-calendar-event"></i></span>
                        <input id="joining_date" type="date" name="joining_date" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-semibold" for="basic_salary">Basic salary</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-cash-coin"></i></span>
                        <input id="basic_salary" type="number" step="0.01" name="basic_salary" class="form-control" placeholder="Basic salary">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
                  <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-person-plus me-1" aria-hidden="true"></i>Add employee
                  </button>
                  <a href="dashboard_admin.php" class="btn btn-light border px-4">Cancel</a>
                </div>
              </form>

            </div>
          </section>

        </main>

        
      </div>
    </div>
  </div>
<script>
          document.getElementById('togglePassword').addEventListener('click', function() {
            var input = document.getElementById('password');
            var icon = document.getElementById('togglePasswordIcon');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
          });
        </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
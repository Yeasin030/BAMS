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
        $companyId = $_SESSION['company_id'] ?? 1;
        $name   = trim($_POST['employee_name']);
        $email  = trim($_POST['email']);
        $hash   = sha1($_POST['password']);
        $role   = $_POST['role'] ?? '';
        $phone  = $_POST['phone'];
        $desig  = $_POST['designation'];
        $jdate  = $_POST['joining_date'] ?: null;
        $salary = $_POST['basic_salary'] ?: 0;

        if (!in_array($role, $allowedRoles, true)) {
            $msg = "Invalid role selected.";
            $msgType = 'danger';
        } else {
            try {
                $conn->begin_transaction();

                $stmt = $conn->prepare(
                    "INSERT INTO users (company_id, name, email, password, role, status)
                     VALUES (?, ?, ?, ?, ?, 1)"
                );
                $stmt->bind_param("issss", $companyId, $name, $email, $hash, $role);
                $stmt->execute();
                $userId = $conn->insert_id;
                $stmt = $conn->prepare(
                    "INSERT INTO employees
                     (company_id, user_id, employee_name, phone, designation, joining_date, basic_salary, status)
                     VALUES (?, ?, ?, ?, ?, ?, ?, 1)"
                );
                $stmt->bind_param("iissssd", $companyId, $userId, $name, $phone, $desig, $jdate, $salary);
                $stmt->execute();

                $conn->commit();
                $msg = "Employee added successfully";
                $msgType = 'success';
            } catch (mysqli_sql_exception $e) {
                $conn->rollback();
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

    <div class="mb-4">
      <span class="badge bg-primary-subtle text-primary-emphasis mb-2">Users &amp; roles</span>
      <h1 class="h3 fw-bold mb-1">Add employee</h1>
      <p class="text-secondary mb-0">Create an employee profile and login account.</p>
    </div>

    <?php if ($msg): ?>
      <div class="alert alert-<?= $msgType ?> alert-dismissible fade show">
        <?= htmlspecialchars($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <div class="card border-0 rounded-4 shadow-sm">
      <div class="card-body p-4 p-lg-5">
        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
          <div class="rounded-3 bg-primary-subtle text-primary p-3 fs-4"><i class="bi bi-person-plus"></i></div>
          <div><h2 class="h5 fw-bold mb-1">Employee details</h2><p class="small text-secondary mb-0">Enter the required account and job information.</p></div>
        </div>
        <form method="post" class="row g-3">
          <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

          <div class="col-md-6">
            <label class="form-label fw-semibold">Full name</label>
            <input name="employee_name" class="form-control" placeholder="e.g. Alex Morgan" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Email (login)</label>
            <input type="email" name="email" class="form-control" placeholder="name@company.com" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Role</label>
            <select name="role" class="form-select" required>
              <option value="" disabled selected>Select role</option>
              <?php foreach ($allowedRoles as $r): ?>
                <option value="<?= $r ?>"><?= ucfirst($r) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Phone</label>
            <input name="phone" class="form-control" placeholder="Phone">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Designation</label>
            <input name="designation" class="form-control" placeholder="Designation">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-semibold">Joining date</label>
            <input type="date" name="joining_date" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-semibold">Basic salary</label>
            <input type="number" step="0.01" name="basic_salary" class="form-control" placeholder="Basic salary">
          </div>

          <div class="col-12 d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
            <button class="btn btn-primary px-4"><i class="bi bi-person-plus me-1"></i>Add employee</button>
            <a href="dashboard_admin.php" class="btn btn-light border px-4">Cancel</a>
          </div>
        </form>
      </div>
    </div>

  </main>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
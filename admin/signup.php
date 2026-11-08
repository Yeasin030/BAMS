<?php
session_start();
include_once 'assets/db/bdconfig.php';


$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyId = $_SESSION['company_id'] ?? 1;
    $name   = trim($_POST['employee_name']);
    $email  = trim($_POST['email']);
    $hash = sha1($_POST['password']);
    $phone  = $_POST['phone'];
    $desig  = $_POST['designation'];
    $jdate  = $_POST['joining_date'] ?: null;
    $salary = $_POST['basic_salary'] ?: 0;

    try {
        $conn->begin_transaction();

        // 1) users টেবিল (লগইন)
        $stmt = $conn->prepare(
            "INSERT INTO users (company_id, name, email, password, role, status)
             VALUES (?, ?, ?, ?, 'employees', 1)"
        );
        $stmt->bind_param("isss", $companyId, $name, $email, $hash);
        $stmt->execute();
        $userId = $conn->insert_id;

        // 2) employees টেবিল
        $stmt = $conn->prepare(
            "INSERT INTO employees
             (company_id, user_id, employee_name, phone, designation, joining_date, basic_salary, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, 1)"
        );
        $stmt->bind_param("iissssd", $companyId, $userId, $name, $phone, $desig, $jdate, $salary);
        $stmt->execute();

        $conn->commit();
        $msg = "Employee added successfully";
    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        $msg = ($e->getCode() == 1062) ? "This email already exists" : "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add employee | BizLedger</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary">
<main class="container py-4 py-md-5">
  <div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
      <header class="text-center mb-4">
        <span class="d-inline-flex rounded-3 bg-primary-subtle text-primary p-3 fs-4 mb-3"><i class="bi bi-person-plus"></i></span>
        <h1 class="h3 fw-bold mb-2">Create an employee account</h1>
        <p class="text-secondary mb-0">Add employee details and create their BizLedger login.</p>
      </header>

      <?php if ($msg): ?><div class="alert alert-info"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

      <section class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4 p-md-5">
          <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
            <span class="rounded-3 bg-primary-subtle text-primary p-3"><i class="bi bi-person-vcard fs-4"></i></span>
            <div><h2 class="h5 fw-bold mb-1">Employee information</h2><p class="small text-secondary mb-0">Complete the details below to continue.</p></div>
          </div>
          <form method="post" class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="employee_name">Full name</label>
              <input id="employee_name" name="employee_name" class="form-control" placeholder="Full name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="email">Email (login)</label>
              <input id="email" type="email" name="email" class="form-control" placeholder="name@company.com" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="password">Password</label>
              <input id="password" type="password" name="password" class="form-control" placeholder="Create a password" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="phone">Phone</label>
              <input id="phone" name="phone" class="form-control" placeholder="Phone number">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="designation">Designation</label>
              <input id="designation" name="designation" class="form-control" placeholder="Job title">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold" for="joining_date">Joining date</label>
              <input id="joining_date" type="date" name="joining_date" class="form-control">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold" for="basic_salary">Basic salary</label>
              <input id="basic_salary" type="number" step="0.01" name="basic_salary" class="form-control" placeholder="0.00">
            </div>
            <div class="col-12 d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
              <button class="btn btn-primary px-4"><i class="bi bi-person-plus me-1"></i>Add employee</button>
              <a href="../index.php" class="btn btn-light border px-4">Back to sign in</a>
            </div>
          </form>
        </div>
      </section>
      <p class="text-center small text-secondary mt-4 mb-0"><i class="bi bi-shield-check me-1"></i>BizLedger account management</p>
    </div>
  </div>
</main>
</body>
</html>
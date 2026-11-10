<?php
session_start();
include_once 'assets/db/bdconfig.php';

if (($_SESSION['role'] ?? '') !== 'admin') {
  header('Location: login.php');
  exit;
}
 if($_SERVER["REQUEST_METHOD"] == "POST"){
          
        $company_id = (int) ($_SESSION['company_id'] ?? 1);
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $temporary_password = sha1($_POST['temporary_password']);
        $designation = $_POST['designation'];
        $department = $_POST['department'];
        $joining_date = $_POST['joining_date'];
        $basic_salary = $_POST['basic_salary'];
        $role = $_POST['role'];

        
        $conn -> query("INSERT INTO employees (company_id, employee_name, phone, designation, department, joining_date, basic_salary, status, login_email, login_password, login_role)
        VALUES ('$company_id', '$name', '$phone', '$designation', '$department', '$joining_date', '$basic_salary', 1, '$email', '$temporary_password', '$role')");

        if($conn -> affected_rows){
            header('Location: ../admin/employlist.php');
            exit;
        };

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

                <!-- Account information -->
                <div class="mb-4">
                  <h3 class="h6 text-uppercase text-secondary fw-semibold small mb-3">
                    <i class="bi bi-shield-lock me-1" aria-hidden="true"></i>Account information
                  </h3>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label fw-semibold" for="name">Full name</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-person"></i></span>
                        <input id="name" name="name" class="form-control" placeholder="e.g. Alex Morgan" required>
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
                      <label class="form-label fw-semibold" for="temporary_password">Password</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-key"></i></span>
                        <input id="temporary_password" type="password" name="temporary_password" class="form-control" placeholder="Password" required>
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
                          <option value="admin">Admin</option>
                          <option value="accountant">Accountant</option>
                          <option value="employees">Employee</option>
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
                      <label class="form-label fw-semibold" for="department">Department</label>
                      <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="bi bi-building"></i></span>
                        <input id="department" name="department" class="form-control" placeholder="Department">
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('togglePassword').addEventListener('click', function () {
      var input = document.getElementById('temporary_password');
      var icon = document.getElementById('togglePasswordIcon');
      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
    });
  </script>
</body>

</html>
<?php
session_start();
include_once 'assets/db/bdconfig.php';

if (($_SESSION['role'] ?? '') !== 'admin') {
  header('Location: login.php');
  exit;
}

$company_id = (int) ($_SESSION['company_id'] ?? 1);
$error = '';
$success = '';

if(isset($_GET['role_added'])){
    $success = "Role added successfully.";
}

// Add the default roles the first time this page is opened
$role_count = $conn -> query("SELECT COUNT(*) AS total FROM roles WHERE company_id = '$company_id'") -> fetch_assoc()['total'];
if($role_count == 0){
    $conn -> query("INSERT INTO roles (company_id, role_name) VALUES ('$company_id', 'admin'), ('$company_id', 'accountant'), ('$company_id', 'employees')");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['add_role'])){

        $role_name = trim($_POST['role_name'] ?? '');

        if($role_name === ''){
            $error = "Please enter a role name.";
        } elseif(mb_strlen($role_name) > 80){
            $error = "Role name can be 80 characters at most.";
        } else {
            $safe_role_name = $conn -> real_escape_string($role_name);
            $role_exists = $conn -> query("SELECT role_id FROM roles WHERE company_id = '$company_id' AND role_name = '$safe_role_name'");

            if($role_exists -> num_rows > 0){
                $error = "This role already exists.";
            } else {
                $conn -> query("INSERT INTO roles (company_id, role_name) VALUES ('$company_id', '$safe_role_name')");

                if($conn -> affected_rows){
                    header('Location: permissions.php?role_added=1');
                    exit;
                }
            }
        }

    } else {

        $name = $conn -> real_escape_string(trim($_POST['name']));
        $email = $conn -> real_escape_string(trim($_POST['email']));
        $phone = $conn -> real_escape_string(trim($_POST['phone']));
        $temporary_password = sha1($_POST['temporary_password']);
        $designation = $conn -> real_escape_string(trim($_POST['designation']));
        $department = $conn -> real_escape_string(trim($_POST['department']));
        $joining_date = ($_POST['joining_date'] ?? '') !== '' ? "'" . $conn -> real_escape_string($_POST['joining_date']) . "'" : "NULL";
        $basic_salary = (float) ($_POST['basic_salary'] ?? 0);

        $selected_role_id = (int) ($_POST['role'] ?? 0);
        $selected_role = $conn -> query("SELECT role_id, role_name FROM roles WHERE role_id = '$selected_role_id' AND company_id = '$company_id'") -> fetch_assoc();
        $email_check = $conn -> query("SELECT user_id FROM users WHERE email = '$email'");

        if(!$selected_role){
            $error = "Please select a valid role.";
        } elseif($email_check -> num_rows > 0){
            $error = "This email is already used by another user.";
        } else {

            // Role name (used only for employees.login_role)
            $login_role = strtolower($selected_role['role_name']);
            if(!in_array($login_role, ['admin', 'accountant', 'employees'])){
                $login_role = 'employees';
            }

            // users table has no "role" column - the role is saved in user_roles below
            $conn -> query("INSERT INTO users (company_id, name, email, password, status)
            VALUES ('$company_id', '$name', '$email', '$temporary_password', 1)");

            $user_id = $conn -> insert_id;

            if($user_id){

                $conn -> query("INSERT INTO employees (company_id, user_id, employee_name, phone, designation, department, joining_date, basic_salary, status, login_email, login_password, login_role)
                VALUES ('$company_id', '$user_id', '$name', '$phone', '$designation', '$department', $joining_date, '$basic_salary', 1, '$email', '$temporary_password', '$login_role')");

                $employee_saved = $conn -> affected_rows;

                $role_id = (int) $selected_role['role_id'];
                $conn -> query("INSERT INTO user_roles (user_id, role_id) VALUES ('$user_id', '$role_id')");

                if($employee_saved){
                    header('Location: ../admin/employlist.php');
                    exit;
                }
            }

        }
    }

}

// Roles list with the number of users in each role (counted through user_roles)
$roles = [];
$role_result = $conn -> query("SELECT r.role_id, r.role_name,
(SELECT COUNT(*)
   FROM user_roles ur
   JOIN users u ON u.user_id = ur.user_id
  WHERE ur.role_id = r.role_id
    AND u.company_id = r.company_id) AS user_count
FROM roles r
WHERE r.company_id = '$company_id'
ORDER BY r.role_name ASC");
while($r = $role_result -> fetch_assoc()){
    $roles[] = $r;
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
  <link href="assets/css/glassmorphism.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary dashboard-page">

  <div class="container-fluid">
    <div class="row min-vh-100">
      <div class="col-lg-3 col-xl-2 p-0"><?php include 'include/adminsidebar.php'; ?></div>

      <div class="col-12 col-lg-9 col-xl-10 p-0">

        <?php include 'include/adminheadbar.php'; ?>

        <main class="p-3 p-md-4 p-xl-5">

          <?php if($error){ ?>
            <div class="alert alert-danger rounded-3 mb-4"><?= htmlspecialchars($error) ?></div>
          <?php } ?>
          <?php if($success){ ?>
            <div class="alert alert-success rounded-3 mb-4"><?= htmlspecialchars($success) ?></div>
          <?php } ?>

          <div class="row g-4 align-items-start">
          <div class="col-12 col-xl-8">

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
                          <?php foreach($roles as $r){ ?>
                            <option value="<?= (int) $r['role_id'] ?>"><?= htmlspecialchars($r['role_name']) ?></option>
                          <?php } ?>
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

          </div>

          <div class="col-12 col-xl-4">
            <section class="card dashboard-card rounded-4 shadow-sm" aria-labelledby="roles-title">
              <div class="card-body p-0">
                <div class="d-flex align-items-center justify-content-between gap-3 p-3 p-md-4 border-bottom">
                  <div class="d-flex align-items-center gap-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary flex-shrink-0" style="width: 2.75rem; height: 2.75rem">
                      <i class="bi bi-person-badge fs-5" aria-hidden="true"></i>
                    </span>
                    <div>
                      <h2 class="h5 dashboard-section-title fw-bold mb-1" id="roles-title">Roles</h2>
                      <p class="small dashboard-subtitle mb-0">Roles available for employees</p>
                    </div>
                  </div>
                  <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2"><?= count($roles) ?> total</span>
                </div>

                <ul class="list-group list-group-flush">
                  <?php foreach($roles as $r){ ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                      <span class="fw-semibold"><i class="bi bi-person-badge text-secondary me-2" aria-hidden="true"></i><?= htmlspecialchars($r['role_name']) ?></span>
                      <span class="small text-secondary"><?= (int) $r['user_count'] ?> user<?= $r['user_count'] == 1 ? '' : 's' ?></span>
                    </li>
                  <?php } ?>
                </ul>

                <form method="post" class="p-3 p-md-4 border-top">
                  <label class="form-label fw-semibold" for="role_name">Add a new role</label>
                  <div class="input-group">
                    <input id="role_name" name="role_name" class="form-control" placeholder="e.g. Manager" maxlength="80" required>
                    <button class="btn btn-primary" type="submit" name="add_role"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Add</button>
                  </div>
                  <div class="form-text">New roles appear in the Role list of the employee form.</div>
                </form>
              </div>
            </section>
          </div>

          </div>

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
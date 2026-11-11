<?php
session_start();
include_once 'assets/db/bdconfig.php';
 

    
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit;
}


if (($_SESSION['role'] ?? '') != 'admin') {
    header("Location: unauthorize.php");
    exit;
}

 
    // display data
    $id = $_GET['id'] ?? 0;
    
    // Updat Opration
   if($_SERVER['REQUEST_METHOD']=='POST'){
    // Data recive from entry from
        $name = $_POST['employee_name'];
        $email = $_POST['login_email'];
        $phone = $_POST['phone'];
        $designation = $_POST['designation'];
        $department = $_POST['department'];
        $joining_date = $_POST['joining_date'];
        $basic_salary = $_POST['basic_salary'];
        $role = $_POST['login_role'];
        $allowed_roles = ['admin', 'accountant', 'employees'];
            if(!in_array($role, $allowed_roles, true)){
            die("Invalid role: " . htmlspecialchars($role));
    }
    
    //updat query
   
    $conn->query("UPDATE employees SET employee_name ='$name', login_email ='$email', phone ='$phone', designation ='$designation',
     department ='$department', joining_date ='$joining_date', basic_salary ='$basic_salary', login_role ='$role' 
    WHERE employee_id ='$id'");
    header("Location: employlist.php?id=$id");


    if($conn->affected_rows){
        echo "<div class='massage'>Updat Complaterd</div>";
    };
   }
        $data = $conn->query("SELECT * FROM employees WHERE employee_id = '$id' ");
        $row = $data->fetch_object();
    

    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Employee | BizLedger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/css/dashboard-shared.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary dashboard-page">
    <div class="container-fluid px-0">
        <div class="row min-vh-100">
            <div class="col-lg-3 col-xl-2 p-0 bg-dark">
                <?php include 'include/adminsidebar.php'; ?>
            </div>
            <main class="col-12 col-lg-9 col-xl-10 p-0">
                <?php include 'include/adminheadbar.php'; ?>
                <div class="p-3 p-md-4 p-xl-5">
                    <section class="dashboard-hero rounded-4 shadow-sm text-white p-4 p-lg-5 mb-4">
                        <div class="dashboard-hero-content d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                            <div>
                                <span class="badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 mb-3">
                                    <i class="bi bi-person-gear me-1" aria-hidden="true"></i> Team management
                                </span>
                                <h1 class="h2 fw-bold mb-2">Edit employee</h1>
                                <p class="mb-0 text-white-50">Update profile and work information for your team members.</p>
                            <a class="btn btn-light text-primary fw-semibold px-3 py-2 text-nowrap" href="employlist.php">
                                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Back to employees
                            </a>
                        </div>
                    </section>

                    <div class="alert alert-info border-0 rounded-3 d-flex align-items-start gap-2 mb-4" role="note">
                        <i class="bi bi-info-circle-fill mt-1" aria-hidden="true"></i>
                        <div><strong>UI preview:</strong> The fields are sample values and changes are not saved.</div>
                    </div>

                    <div class="row g-4">
                        <div class="col-12 col-xl-8">
                            <section class="card dashboard-card rounded-4 shadow-sm" aria-labelledby="employee-details-title">
                                <div class="card-body p-0">
                                    <div class="d-flex align-items-center gap-3 p-3 p-md-4 border-bottom">
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary flex-shrink-0" style="width: 2.75rem; height: 2.75rem">
                                            <i class="bi bi-person-vcard fs-5" aria-hidden="true"></i>
                                        </span>
                                        <div>
                                            <h2 class="h5 dashboard-section-title fw-bold mb-1" id="employee-details-title">Employee details</h2>
                                            <p class="small dashboard-subtitle mb-0">Manage contact and employment information.</p>
                                        </div>
                                    </div>

                                    <form class="p-3 p-md-4" method="post">
                                        <div class="mb-4">
                                            <h3 class="h6 text-uppercase text-secondary fw-semibold small mb-3">
                                                <i class="bi bi-person-lines-fill me-1" aria-hidden="true"></i>Personal information
                                            </h3>
                                            <div class="row g-3">
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label fw-semibold" for="employee-name">Full name <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text" id="employee-name" name="employee_name" value="<?php echo $row->employee_name; ?>" autocomplete="name" required>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label fw-semibold" for="employee-email">Email address <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="email" id="employee-email" name="login_email" value="<?php echo $row->login_email ; ?>" autocomplete="email" required>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label fw-semibold" for="employee-phone">Phone number</label>
                                                    <input class="form-control" type="tel" id="employee-phone" name="phone" value="<?php echo $row->phone; ?>" autocomplete="tel">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label fw-semibold" for="employee-role">Role</label>
                                                    <select class="form-select" id="employee-role" name="login_role">
                                                        <option value="employees"  <?= $row->login_role === 'employees' ? 'selected' : '' ?>>Employee</option>
                                                        <option value="accountant" <?= $row->login_role === 'accountant' ? 'selected' : '' ?>>Accountant</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="text-secondary-emphasis opacity-25">

                                        <div class="mt-4 mb-4">
                                            <h3 class="h6 text-uppercase text-secondary fw-semibold small mb-3">
                                                <i class="bi bi-briefcase me-1" aria-hidden="true"></i>Employment information
                                            </h3>
                                            <div class="row g-3">
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label fw-semibold" for="employee-designation">Designation</label>
                                                    <input class="form-control" type="text" id="employee-designation" name="designation" value="<?php echo $row->designation ; ?>">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label fw-semibold" for="employee-department">Department</label>
                                                     <input class="form-control" type="text" id="employee-department" name="department" value="<?php echo $row->department ; ?>">
                                                
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label fw-semibold" for="joining-date">Joining date</label>
                                                    <input class="form-control" type="date" id="joining-date" name="joining_date" value="<?php echo $row->joining_date; ?>">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label fw-semibold" for="basic-salary">Basic salary</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input class="form-control" type="number" id="basic-salary" name="basic_salary" min="0" step="0.01" value="<?php echo $row->basic_salary; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label fw-semibold" for="employee-status">Employment status</label>
                                                    <select class="form-select" id="employee-status" name="status">
                                                        <option selected value="active">Active</option>
                                                        <option value="inactive">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-column-reverse flex-sm-row justify-content-end gap-2 pt-3 border-top">
                                            <a class="btn btn-light border px-4" href="employlist.php">Cancel</a>
                                            <button class="btn btn-primary px-4" type="submit" name="submit">
                                                <i class="bi bi-check2 me-1" aria-hidden="true"></i>Save changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </section>
                        </div>

                        <div class="col-12 col-xl-4">
                            <aside class="card dashboard-card rounded-4 shadow-sm mb-4" aria-labelledby="profile-preview-title">
                                <div class="card-body p-4">
                                    <h2 class="h6 fw-bold mb-3" id="profile-preview-title">Profile preview</h2>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-semibold flex-shrink-0" style="width: 3.25rem; height: 3.25rem">AM</span>
                                        <div class="min-w-0">
                                            <div class="fw-semibold"><?php echo $row->employee_name; ?></div>
                                            <div class="small text-secondary"><?php echo $row->designation; ?></div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between gap-3 small mb-2">
                                        <span class="text-secondary">Employee ID</span>
                                        <span class="fw-semibold"><?php echo $row->employee_id; ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between gap-3 small">
                                        <span class="text-secondary">Status</span>
                                        <span class="badge rounded-pill bg-success-subtle text-success-emphasis">Active</span>
                                    </div>
                                </div>
                            </aside>
                            <aside class="card dashboard-card rounded-4 shadow-sm">
                                <div class="card-body p-4">
                                    <h2 class="h6 fw-bold mb-2"><i class="bi bi-shield-check text-success me-2" aria-hidden="true"></i>Account security</h2>
                                    <p class="small text-secondary mb-3">Login credentials are managed separately from employee profile details.</p>
                                    <a class="btn btn-sm btn-outline-primary" href="#" aria-disabled="true">Manage password</a>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

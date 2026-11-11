<?php
session_start();
include_once 'assets/db/bdconfig.php';
$rowData = $conn->query("SELECT * FROM employees ORDER BY employee_id DESC");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List | BizLedger</title>
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
                                    <i class="bi bi-people me-1" aria-hidden="true"></i> Team management
                                </span>
                                <h1 class="h2 fw-bold mb-2">Employees</h1>
                                <p class="mb-0 text-white-50">Everyone who works with your team.</p>
                            </div>
                            <a class="btn btn-light text-primary fw-semibold px-3 py-2 text-nowrap" href="permissions.php">
                                <i class="bi bi-person-plus me-1" aria-hidden="true"></i>Add Employee
                            </a>
                        </div>
                    </section>

                    <section class="card dashboard-card rounded-4 shadow-sm overflow-hidden" aria-labelledby="employee-list-title">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 p-3 p-md-4 border-bottom">
                                <div>
                                    <h2 class="h5 dashboard-section-title fw-bold mb-1" id="employee-list-title">All employees</h2>
                                    <p class="small dashboard-subtitle mb-0">Employee contact details and account actions</p>
                                </div>
                                <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2">
                                    <i class="bi bi-people me-1" aria-hidden="true"></i>
                                    <?php echo $rowData ? (int) $rowData->num_rows : 0; ?> total
                                </span>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="ps-4 py-3 small text-uppercase text-secondary">Employee</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary">ID</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary">Phone</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary">Designation</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary">Department</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary">Salary</th>
                                            <th scope="col" class="pe-4 py-3 small text-uppercase text-secondary text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($rowData && $rowData->num_rows > 0) { ?>
                                            <?php while ($row = $rowData->fetch_assoc()) { ?>
                                                <?php
                                                $nameParts = preg_split('/\s+/', trim($row['employee_name']));
                                                $initials = strtoupper(substr($nameParts[0] ?? '', 0, 1) . (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : ''));
                                                $status = strtolower((string) ($row['employee_status'] ?? ''));
                                               
                                                ?>
                                                <tr>
                                                    <td class="ps-4 py-3">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary fw-semibold flex-shrink-0" style="width: 2.5rem; height: 2.5rem">
                                                                <?php echo $initials ; ?>
                                                            </span>
                                                            <div>
                                                                <div class="fw-semibold"><?php echo $row['employee_name']; ?></div>
                                                                <div class="small text-secondary"><?php echo $row['login_email'] ; ?></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="fw-semibold text-secondary">
                                                        EMP-<?php echo str_pad((int) $row['employee_id'], 3, '0', STR_PAD_LEFT); ?>
                                                    </td>
                                                    <td class="text-secondary"><?php echo $row['phone'] ; ?></td>
                                                    <td class="text-secondary"><?php echo $row['designation'] ; ?></td>
                                                    <td class="text-secondary"><?php echo $row['department'] ; ?></td>
                                                    <td class="text-secondary"><?php echo $row['basic_salary'] ; ?></td>
                                                  
                                                    <td class="pe-4 text-end text-nowrap">
                                                        <a class="btn btn-sm btn-light text-primary" href="employedit.php?id=<?php echo $row['employee_id']; ?>" aria-label="Edit employee" title="Edit employee">
                                                            <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                                        </a>
                                                        <a class="btn btn-sm btn-light text-danger" onclick="return confirm('Delete this employee?')" href="deleteemploy.php?id=<?php echo $row['employee_id']; ?>" aria-label="Delete employee" title="Delete employee">
                                                            <i class="bi bi-trash3" aria-hidden="true"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td class="text-center py-5" colspan="6">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary mb-3" style="width: 3.5rem; height: 3.5rem">
                                                            <i class="bi bi-people fs-4" aria-hidden="true"></i>
                                                        </span>
                                                        <span class="fw-semibold text-dark">No employees yet</span>
                                                        <span class="small text-secondary mt-1">Add your first employee to get started.</span>
                                                        <a class="btn btn-primary btn-sm mt-3" href="permissions.php">
                                                            <i class="bi bi-person-plus me-1" aria-hidden="true"></i>Add Employee
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
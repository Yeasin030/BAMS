<?php
include_once 'assets/db/bdconfig.php';
$rowData = $conn->query("SELECT * FROM employees");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List | BizLedger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
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
                <div class="d-flex align-items-center gap-3 mb-4">
                    <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-label="Open menu">
                        <i class="bi bi-list"></i>
                    </button>
                    <div>
                        <h1 class="h2 fw-bold mb-1">Employees</h1>
                        <p class="text-secondary mb-0">Manage your business employees.</p>
                    </div>
                </div>

                <section class="card border-0 rounded-4 shadow-sm" aria-labelledby="employee-list-title">
                    <div class="card-body p-3 p-md-4">
                        <h2 class="h5 fw-bold mb-3" id="employee-list-title">Employee list</h2>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($rowData && $rowData->num_rows > 0) { ?>
                                        <?php $counter = 1; ?>
                                        <?php while ($row = $rowData->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $counter; ?></td>
                                                <td class="fw-semibold"><?php echo htmlspecialchars($row['employee_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-end text-nowrap">
                                                    <a class="btn btn-sm btn-outline-primary" href="permissions.php?id=<?php echo urlencode($row['employee_id']); ?>" aria-label="Edit employee" title="Edit employee">
                                                        <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this employee?')" href="employeeDelete.php?id=<?php echo urlencode($row['employee_id']); ?>" aria-label="Delete employee" title="Delete employee">
                                                        <i class="bi bi-trash3" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php $counter++; ?>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td class="text-center text-secondary py-4" colspan="5">No employees yet. Add an employee to get started.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

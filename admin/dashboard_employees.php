<?php

session_start();
include_once 'assets/db/bdconfig.php';
$email = mysqli_real_escape_string($conn, $_SESSION['email']);

$result   = mysqli_query($conn, "SELECT * FROM employees WHERE login_email = '$email' LIMIT 1");
$employee = mysqli_fetch_assoc($result);

if (!$employee) {
  die("Employee record not found.");
}
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
  <link href="assets/css/employee-dashboard.css" rel="stylesheet">

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
        <div class="employee-dashboard-content">
          <!-- Welcome banner -->
          <section class="welcome-banner mb-4">
            <h1>Welcome back, <?php echo $_SESSION['name']; ?>! 👋</h1>
            <p>Stay productive and keep up with your work details.</p>
            <div class="banner-art d-flex align-items-end">
              <i class="bi bi-laptop laptop"></i>
              <i class="bi bi-flower2 plant"></i>
            </div>
          </section>

          <!-- Row 1: Profile + Quick actions -->
          <div class="row g-3 mb-3">

            <!-- Employee Profile -->
            <div class="col-12 col-lg-7">
              <div class="card dash-card employee-profile-card">
                <div class="card-body p-3">
                  <h2 class="card-heading mb-2"><i class="bi bi-people-fill"></i> Employee Profile</h2>

                  <div class="row g-2 align-items-start">
                    <div class="col-12 col-md-auto d-flex justify-content-center">
                      <div class="avatar-wrap">
                        <img src="https://via.placeholder.com/300x300?text=Photo" alt="Md. Tanvir Hasan">
                        <button type="button" class="avatar-edit" aria-label="Change photo">
                          <i class="bi bi-pencil"></i>
                        </button>
                      </div>
                    </div>

                    <div class="col-12 col-md">
                      <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <h3 class="emp-name mb-0"><?php echo $_SESSION['name']; ?></h3>
                        <span class="badge-role"><?php echo $_SESSION['role']; ?></span>
                      </div>

                      <div class="detail-row">
                        <i class="bi bi-person-badge"></i><span>Employee ID</span>
                        <span class="value"><?php echo "EMP-" . htmlspecialchars($employee['employee_id'] ?? '-') ?></span>
                      </div>
                      <div class="detail-row">
                        <i class="bi bi-building"></i><span>Department</span>
                        <span class="value"><?php echo  htmlspecialchars($employee['department'] ?? '-') ?></span>
                      </div>
                      <div class="detail-row">
                        <i class="bi bi-person-workspace"></i><span>Designation</span>
                        <span class="value"><?php echo htmlspecialchars($employee['designation'] ?? '-') ?></span>
                      </div>
                      <div class="detail-row email-detail-row">
                        <i class="bi bi-envelope"></i><span>Email</span>
                        <span class="value"><?php echo  htmlspecialchars($employee['login_email'] ?? '-') ?></span>
                      </div>
                    </div>

                    <div class="col-12 col-xl-4 quick-info ps-xl-4">
                      <h4 class="quick-title mb-3">Quick Info</h4>
                      <div class="d-grid gap-2">
                        <div class="info-tile">
                          <div class="info-icon tone-blue"><i class="bi bi-calendar-check-fill"></i></div>
                          <div>
                            <div class="info-label">Join Date</div>
                            <div class="info-value">Jan 15, 2024</div>
                          </div>
                        </div>
                        <div class="info-tile">
                          <div class="info-icon tone-green"><i class="bi bi-clock"></i></div>
                          <div>
                            <div class="info-label">Total Leave</div>
                            <div class="info-value">12 Days</div>
                          </div>
                        </div>
                        <div class="info-tile">
                          <div class="info-icon tone-orange"><i class="bi bi-briefcase-fill"></i></div>
                          <div>
                            <div class="info-label">Office Hours</div>
                            <div class="info-value">9:00 AM – 5:00 PM</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-12 col-lg-5">
              <div class="card dash-card quick-actions-card">
                <div class="card-body p-3">
                  <h2 class="card-heading mb-2"><i class="bi bi-lightning-charge"></i> Quick Actions</h2>

                  <div class="row g-1">
                    <div class="col-6">
                      <a href="profile.php" class="action-tile action-blue">
                        <span class="a-icon"><i class="bi bi-person-fill"></i></span>
                        <span>
                          <span class="a-title">View My Profile</span>
                          <span class="a-sub">Personal Details <i class="bi bi-arrow-right-short"></i></span>
                        </span>
                      </a>
                    </div>
                    <div class="col-6">
                      <a href="apply-leave.php" class="action-tile action-green">
                        <span class="a-icon"><i class="bi bi-calendar2-plus-fill"></i></span>
                        <span>
                          <span class="a-title">Apply for Leave</span>
                          <span class="a-sub">Request Leave <i class="bi bi-arrow-right-short"></i></span>
                        </span>
                      </a>
                    </div>
                    <div class="col-6">
                      <a href="payslip.php" class="action-tile action-purple">
                        <span class="a-icon"><i class="bi bi-file-earmark-text-fill"></i></span>
                        <span>
                          <span class="a-title">View Payslip</span>
                          <span class="a-sub">Check Salary <i class="bi bi-arrow-right-short"></i></span>
                        </span>
                      </a>
                    </div>
                    <div class="col-6">
                      <a href="transactions.php" class="action-tile action-orange">
                        <span class="a-icon"><i class="bi bi-list-check"></i></span>
                        <span>
                          <span class="a-title">View Transactions</span>
                          <span class="a-sub">My Records <i class="bi bi-arrow-right-short"></i></span>
                        </span>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Row 2: Recent activity + Company notices -->
          <div class="row g-3">

            <!-- Recent Activity -->
            <div class="col-12 col-lg-7">
              <div class="card dash-card">
                <div class="card-body p-3">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="card-heading"><i class="bi bi-clock-history"></i> Recent Activity</h2>
                    <a href="activity.php" class="view-all">View All <i class="bi bi-arrow-right-short"></i></a>
                  </div>

                  <div class="timeline">
                    <div class="tl-item">
                      <span class="tl-dot dot-green"></span>
                      <div class="tl-icon tone-green"><i class="bi bi-check-circle-fill"></i></div>
                      <div>
                        <div class="tl-title">Salary Credited</div>
                        <div class="tl-desc">Your salary for October 2026 has been credited.</div>
                      </div>
                      <div class="tl-time">Nov 1, 2026 • 10:30 AM</div>
                    </div>

                    <div class="tl-item">
                      <span class="tl-dot dot-blue"></span>
                      <div class="tl-icon tone-blue"><i class="bi bi-calendar-check"></i></div>
                      <div>
                        <div class="tl-title">Leave Request Approved</div>
                        <div class="tl-desc">Your leave request has been approved.</div>
                      </div>
                      <div class="tl-time">Oct 28, 2026 • 04:15 PM</div>
                    </div>

                    <div class="tl-item">
                      <span class="tl-dot dot-purple"></span>
                      <div class="tl-icon tone-purple"><i class="bi bi-file-earmark-text"></i></div>
                      <div>
                        <div class="tl-title">Payslip Available</div>
                        <div class="tl-desc">Your October 2026 payslip is ready to view.</div>
                      </div>
                      <div class="tl-time">Oct 25, 2026 • 09:20 AM</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Company Notices -->
            <div class="col-12 col-lg-5">
              <div class="card dash-card">
                <div class="card-body p-3">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="card-heading"><i class="bi bi-megaphone-fill"></i> Company Notices</h2>
                    <a href="notices.php" class="view-all">View All <i class="bi bi-arrow-right-short"></i></a>
                  </div>

                  <div class="notice-item">
                    <span class="notice-dot dot-blue"></span>
                    <div>
                      <div class="notice-title">Office Holiday on November 10</div>
                      <div class="notice-desc">The office will remain closed on November 10, 2026 (Tuesday).</div>
                    </div>
                    <span class="notice-date">Nov 5, 2026</span>
                  </div>

                  <div class="notice-item">
                    <span class="notice-dot dot-green"></span>
                    <div>
                      <div class="notice-title">New HR Policy Updated</div>
                      <div class="notice-desc">Please check the updated HR policy in the company portal.</div>
                    </div>
                    <span class="notice-date">Oct 30, 2026</span>
                  </div>

                  <div class="notice-item">
                    <span class="notice-dot dot-orange"></span>
                    <div>
                      <div class="notice-title">Year-End Bonus Announcement</div>
                      <div class="notice-desc">This year's bonus will be processed in December 2026.</div>
                    </div>
                    <span class="notice-date">Oct 25, 2026</span>
                  </div>
                </div>
              </div>
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
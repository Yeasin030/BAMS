<?php
session_start();
include_once("admin/assets/db/bdconfig.php");

$login_error = '';

if (isset($_POST['submit'])) {

  $email = trim($_POST['email'] ?? '');
  $password = sha1($_POST['password'] ?? '');

$stmt = $conn->prepare("SELECT u.user_id, u.company_id, u.name, r.role_name
                          FROM users u
                          LEFT JOIN user_roles ur ON ur.user_id = u.user_id
                          LEFT JOIN roles r ON r.role_id = ur.role_id
                          WHERE u.email = ? AND u.password = ?
                          LIMIT 1");
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  if ($row) {

    $role = strtolower($row['role_name'] ?? 'employees');

    if (!in_array($role, ['admin', 'accountant', 'employees'])) {
      $role = 'employees';
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $row['user_id'];
    $_SESSION['company_id'] = (int) $row['company_id'];
    $_SESSION['email'] = $email;
    $_SESSION['name'] = $row['name'];
    $_SESSION['role'] = $role;

    if ($role == 'admin') {
      $_SESSION['url'] = 'admin/dashboard_admin.php';
    } elseif ($role == 'accountant') {
      $_SESSION['url'] = 'admin/dashboard_account.php';
    } else {
      $_SESSION['url'] = 'admin/dashboard_employees.php';
    }

    header("Location: " . $_SESSION['url']);
    exit;

  } else {
    $login_error = 'We could not sign you in. Check your email and password and try again.';
  }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#0f4c75">
  <title>Sign in | BizLedger</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="admin/assets/css/glassmorphism.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary clay-page clay-login">
  <main class="container min-vh-100 d-flex align-items-center justify-content-center py-4 py-lg-5">
    <div class="row justify-content-center w-100">
      <div class="col-12 col-xxl-10">
      <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
      <div class="row g-0">
      <section class="col-md-6 bg-primary text-white d-flex flex-column p-4 p-lg-5" aria-label="About BizLedger">
        <a class="d-inline-flex align-items-center gap-2 text-decoration-none" href="#" aria-label="BizLedger home">
          <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-white bg-opacity-25 text-white p-2"><i class="bi bi-grid-1x2-fill"></i></span>
          <span class="fs-5 fw-bold text-white">BizLedger</span>
        </a>

        <div class="my-auto py-5">
          <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-white bg-opacity-25 px-3 py-2 mb-3"><i class="bi bi-shield-check"></i> Your business, in balance</div>
          <h1 class="display-6 fw-bold">Make every business decision count.</h1>
          <p class="lead fs-6">Manage your accounts, track transactions, and keep your business moving forward from one clear workspace.</p>
        </div>

        <div class="card bg-white bg-opacity-10 text-white border border-white border-opacity-25 p-3" aria-hidden="true">
          <div class="d-flex align-items-center justify-content-between">
            <span class="small fw-bold">DASHBOARD PREVIEW</span>
            <span class="small"><i class="bi bi-calendar3 me-1"></i>This month</span>
          </div>
          <div class="fs-3 fw-bold mt-3">$24,680.00</div>
          <div class="small text-white-50">Total business balance</div>
          <div class="d-flex align-items-end gap-2 border-bottom border-white border-opacity-25 mt-3" style="height: 68px">
            <span class="bg-success rounded-top flex-fill" style="height: 36%"></span><span class="bg-success rounded-top flex-fill" style="height: 51%"></span><span class="bg-success rounded-top flex-fill" style="height: 44%"></span><span class="bg-success rounded-top flex-fill" style="height: 67%"></span><span class="bg-success rounded-top flex-fill" style="height: 56%"></span><span class="bg-success rounded-top flex-fill" style="height: 82%"></span><span class="bg-success rounded-top flex-fill" style="height: 100%"></span>
          </div>
          <div class="d-flex justify-content-between pt-2 small text-white-50"><span>MON</span><span>TUE</span><span>WED</span><span>THU</span><span>FRI</span><span>SAT</span><span>SUN</span></div>
        </div>

        <div class="small text-white-50 pt-3">A clearer view of your business finances.</div>
      </section>

      <section class="col-md-6 d-flex align-items-center justify-content-center p-4 p-lg-5" aria-labelledby="login-title">
        <div class="w-100">
          <h2 class="h2 fw-bold mb-2" id="login-title">Welcome back</h2>
          <p class="text-secondary mb-4">Sign in to your account to continue.</p>

          <?php if ($login_error) { ?>
            <div class="alert alert-danger d-flex align-items-center gap-2" role="alert"><i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i><span><?= htmlspecialchars($login_error) ?></span></div>
          <?php } ?>

          <form method="post">
            <div class="mb-3">
              <label class="form-label" for="email" >Email address</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input class="form-control" type="email" id="email" name="email" value="admin@gmail.com" placeholder="you@company.com" value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" autocomplete="username" required>
              </div>
            </div>

            <div class="mb-3">
              <div class="d-flex align-items-center justify-content-between">
                <label class="form-label" for="password">Password</label>
                <span class="small text-secondary mb-2">Need help? Contact your administrator.</span>
              </div>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input class="form-control" type="password" id="password" name="password" placeholder="Enter your password" value="admin" autocomplete="current-password" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="Show password" aria-pressed="false">
                  <i class="bi bi-eye" aria-hidden="true"></i>
                </button>
              </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                <label class="form-check-label small text-secondary" for="remember">Remember me</label>
              </div>
            </div>

            <button class="btn btn-primary w-100" type="submit" name="submit">Sign in <i class="bi bi-arrow-right ms-1"></i></button>
            <div class="text-center mt-4">
            <p class="mb-2 text-secondary">New to BizLedger?</p>
            <a class="link-primary text-decoration-none" href="admin/createcompany.php"><i class="bi bi-building me-1"></i>Create a company</a>
          </div>

          </form>

          <p class="text-secondary small text-center mt-4 mb-0"><i class="bi bi-lock-fill me-1"></i> Your account information stays secure.</p>
        </div>
    </section>
    </div>
    </div>
    </div>
  </main>

</body>
</html>

<?php include_once ("admin/assets/db/bdconfig.php"); ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#f5f7fb">
  <title>Sign in | BizLedger</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
  <main class="container min-vh-100 d-flex align-items-center justify-content-center py-4 py-lg-5">
    <div class="login-card row g-0 w-100">
      <section class="col-md-6 welcome-panel" aria-label="About BizLedger">
        <a class="d-inline-flex align-items-center gap-2 text-decoration-none" href="#" aria-label="BizLedger home">
          <span class="brand-mark"><i class="bi bi-grid-1x2-fill"></i></span>
          <span class="brand-name text-white">Biz<span>Ledger</span></span>
        </a>

        <div class="panel-copy">
          <div class="panel-note"><i class="bi bi-shield-check"></i> Your business, in balance</div>
          <h1>Make every business decision count.</h1>
          <p>Manage your accounts, track transactions, and keep your business moving forward from one clear workspace.</p>
        </div>

        <div class="finance-preview" aria-hidden="true">
          <div class="d-flex align-items-center justify-content-between">
            <span class="preview-label">DASHBOARD PREVIEW</span>
            <span class="preview-period"><i class="bi bi-calendar3 me-1"></i>This month</span>
          </div>
          <div class="preview-balance">$24,680<span>.00</span></div>
          <div class="preview-caption">Total business balance</div>
          <div class="preview-chart">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </div>
          <div class="preview-months"><span>MON</span><span>TUE</span><span>WED</span><span>THU</span><span>FRI</span><span>SAT</span><span>SUN</span></div>
        </div>

        <div class="panel-footer small">A clearer view of your business finances.</div>
      </section>

      <section class="col-md-6 login-panel" aria-labelledby="login-title">
        <div class="login-content">
          <h2 class="login-title" id="login-title">Welcome back</h2>
          <p class="login-subtitle">Sign in to your account to continue.</p>
          <?php
              if (isset($_POST['submit'])) {
                extract($_POST);
                $password = sha1($password);
                
                //echo "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
                $result = $conn->query("SELECT * FROM users WHERE email = '$email' AND password = '$password'");

                $row = $result->fetch_assoc();
                

                if ($result->num_rows > 0) {
                  session_start();
                  $_SESSION['email'] = $email;
                  $_SESSION['name'] = $row['name'];
                  $_SESSION['role'] = $row['role'];


                  if ($row['role'] == 'admin') {
                    $_SESSION['url'] = 'admin/dashboard_admin.php';
                    header("Location: admin/dashboard_admin.php");
                  } 
                    else if ($row['role'] == 'accountant') {
                    $_SESSION['url'] = 'admin/dashboard_account.php';
                    header("Location: admin/dashboard_account.php");
                  } else if ($row['role'] == 'employees') {
                    $_SESSION['url'] = 'admin/dashboard_employees.php';
                    header("Location: admin/dashboard_employees.php");
                  }
                } else {
                  echo 'problem login';
                }
              }
              ?>

          <form method="post">
            <div class="mb-3">
              <label class="form-label" for="email" >Email address</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input class="form-control" type="text" id="email" name="email" placeholder="you@company.com" autocomplete="username" required>
              </div>
            </div>

            <div class="mb-3">
              <div class="d-flex align-items-center justify-content-between">
                <label class="form-label" for="password">Password</label>
                <a class="text-link mb-2" href="#">Forgot password?</a>
              </div>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input class="form-control" type="password" id="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
                <button class="btn password-toggle" type="button" id="togglePassword" aria-label="Show password" aria-pressed="false">
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
            
          

            <button class="btn btn-primary btn-signin w-100" type="submit" name="submit">Sign in <i class="bi bi-arrow-right ms-1"></i></button>
            <div class="text-center mt-4">
            <p class="mb-2 text-secondary">New to BizLedger?</p>
            <a class="btn btn-outline-primary w-100 mb-2" href="admin/signup.php">Sign up</a>
            <a class="text-link" href="admin/createcompany.php"><i class="bi bi-building me-1"></i>Create a company</a>
          </div>

          </form>

          <p class="login-footnote text-center mt-4 mb-0"><i class="bi bi-lock-fill me-1"></i> Your account information stays secure.</p>
        </div>
      </section>
    </div>
  </main>


</body>
</html>
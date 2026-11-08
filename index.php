<?php include_once ("admin/assets/db/bdconfig.php"); ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#f8f9fa">
  <title>Sign in | BizLedger</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary">
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
                <a class="link-primary small text-decoration-none mb-2" href="#">Forgot password?</a>
              </div>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input class="form-control" type="password" id="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
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
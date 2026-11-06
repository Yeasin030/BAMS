<?php 
    include_once ("assets/db/bdconfig.php"); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f6f8fb">
    <title>Create your account | BizLedger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/css/signup.css" rel="stylesheet">
</head>
<body>
    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-4 py-lg-5">
        <div class="signup-card row g-0 w-100">
            <section class="col-lg-5 signup-welcome" aria-label="About BizLedger">
                <a class="signup-brand d-inline-flex align-items-center gap-2 text-decoration-none" href="../index.php" aria-label="BizLedger home">
                    <span class="signup-brand-mark"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
                    <span>Biz<span class="signup-brand-accent">Ledger</span></span>
                </a>

                <div class="signup-message">
                    <span class="signup-eyebrow"><i class="bi bi-stars me-2" aria-hidden="true"></i>BUILT FOR YOUR BUSINESS</span>
                    <h1>A better way to manage your business finances.</h1>
                    <p>Bring your accounts and day-to-day finances together in one clear, easy-to-use workspace.</p>

                    <ul class="signup-benefits list-unstyled mb-0">
                        <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Keep your financial activity organized</span></li>
                        <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>See the details that matter at a glance</span></li>
                        <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Stay focused as your business grows</span></li>
                    </ul>
                </div>

                <p class="signup-panel-footer mb-0">A clearer view of your business finances.</p>
            </section>

            <section class="col-lg-7 signup-form-panel" aria-labelledby="signup-title">
                <div class="signup-content">
                    <div class="signup-heading">
                        <span class="signup-heading-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                        <h2 id="signup-title">Create your account</h2>
                        <p>Get started with BizLedger. It only takes a moment.</p>
                    </div>
                    <?php
              if (isset($_POST['signup'])) {
                extract($_POST);

                $password = sha1($password);

                //echo "INSERT INTO users ( name, email, password, role ) VALUES ('$name', '$email', '$password', '$role')";
             $conn->query("INSERT INTO users ( name, email, password, role ) VALUES ('$name', '$email', '$password', '$role')");
                if($conn->affected_rows){
                      $_SESSION['url'] = '../index.php';
                    header("Location: ../index.php");
               }
               else{
                    echo "<p class='error'>Error creating user: " . $conn->error . "</p>";
               }
              }
              ?>

                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label" for="name">Full name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person" aria-hidden="true"></i></span>
                                <input class="form-control" type="text" id="name" name="name" placeholder="Your full name" autocomplete="name" maxlength="120" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="email">Email address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope" aria-hidden="true"></i></span>
                                <input class="form-control" type="text" id="email" name="email" placeholder="you@company.com" autocomplete="email" maxlength="150" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="role">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="" selected disabled>Choose a role</option>
                                <option value="user">User</option>
                                <option value="accountant">Accountant</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock" aria-hidden="true"></i></span>
                                    <input class="form-control" type="password" id="password" name="password" placeholder="Create a password" autocomplete="new-password" required>
                                </div>
                                <div class="form-text">Use at least 8 characters.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="confirm_password">Confirm password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-shield-lock" aria-hidden="true"></i></span>
                                    <input class="form-control" type="password" id="confirm_password" name="confirm_password" placeholder="Enter it again" autocomplete="new-password"  required>
                                </div>
                            </div>
                        </div>

                    

                        <button class="btn btn-primary signup-submit w-100" type="submit" name="signup">
                            Create account <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                        </button>
                    </form>

                    <p class="signup-login text-center mb-0">Already have an account? <a class="signup-link" href="../index.php">Sign in</a></p>
                    <p class="signup-security text-center mb-0"><i class="bi bi-lock-fill me-1" aria-hidden="true"></i>Your account information stays secure.</p>
                </div>
            </section>
        </div>
    </main>
</body>
</html>

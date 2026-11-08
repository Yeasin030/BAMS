<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">
</head>

<body class="bg-body-tertiary">
    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
      <div class="card border-0 rounded-4 shadow-sm text-center w-100" style="max-width: 36rem">
        <div class="card-body p-4 p-md-5">
        <div class="d-inline-flex rounded-circle bg-primary-subtle text-primary p-4 mb-4 fs-1">
            <i class="bi bi-shield-lock"></i>
        </div>

        <span class="badge text-bg-danger">ERROR 403 · FORBIDDEN</span>

        <h1 class="h2 fw-bold mt-3">Access Denied</h1>

        <p class="text-secondary my-4">
            Sorry! Your current user role does not have permission
            to access this page. Please contact your administrator
            if you need access.
        </p>

        <div class="alert alert-light border mb-4">
            <i class="bi bi-lock me-2"></i>
            <strong>Restricted Area</strong>
            <div class="small mt-1">
                Your account is not authorized for this resource.
            </div>
        </div>
        <?php $url = $_SESSION['url'] ?? '../index.php'; ?>
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <a href="<?php echo $url; ?>" class="btn btn-primary">
                <i class="bi bi-house me-2"></i>
                Go to Dashboard
            </a>

            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>
                Go Back
            </a>
        </div>

        <div class="small text-secondary mt-4">
            Error Code: 403 &bull; Forbidden Access
        </div>
        </div>
      </div>
    </main>
</body>

</html>
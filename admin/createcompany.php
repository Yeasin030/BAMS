
<?php 
    include_once ("assets/db/bdconfig.php"); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create company</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary">


    <main class="container py-4 py-md-5">
      <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
        <div class="text-center mb-4">
          <span class="d-inline-flex rounded-3 bg-primary-subtle text-primary p-3 fs-4 mb-3"><i class="bi bi-buildings"></i></span>
          <h1 class="h3 fw-bold mb-2">Set up your company</h1>
          <p class="text-secondary mb-0">Add your business details to get started with BizLedger.</p>
        </div>
        <div class="card border-0 rounded-4 shadow-sm">
          <div class="card-body p-4 p-md-5">
        <h2 class="h5 fw-bold mb-1">Company information</h2>
        <p class="text-secondary small mb-4">Fields marked with <span class="text-danger">*</span> are required.</p>
        <?php
              if (isset($_POST['create'])) {
                extract($_POST);
                

                
                //echo "INSERT INTO companies (company_name, email, phone, address, currency, financial_year_start, status) VALUES ('$company_name', '$email', '$phone', '$address', '$currency', '$financial_year_start', '$status')";
                $conn->query("INSERT INTO companies (company_name, email, phone, address, currency, financial_year_start, status) VALUES ('$company_name', '$email', '$phone', '$address', '$currency', '$financial_year_start', '$status')");
               if($conn->affected_rows){
                      $_SESSION['url'] = '../index.php';
                    header("Location: ../index.php");
               }
               else{
                    echo "<p class='alert alert-danger'>Error creating company: " . $conn->error . "</p>";
               }
              }
              ?>

        <form method="post" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-12">
                    <label for="company_name" class="form-label fw-semibold">Company name <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="company_name" name="company_name" maxlength="150" required>
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input class="form-control" type="email" id="email" name="email" maxlength="150">
                </div>

                <div class="col-md-6">
                    <label for="phone" class="form-label fw-semibold">Phone</label>
                    <input class="form-control" type="tel" id="phone" name="phone" maxlength="30">
                </div>

                <div class="col-12">
                    <label for="address" class="form-label fw-semibold">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                </div>

                <div class="col-md-6">
                    <label for="logo" class="form-label fw-semibold">Logo</label>
                    <input class="form-control" type="file" id="logo" name="logo" accept="image/*">
                </div>

                <div class="col-md-6">
                    <label for="currency" class="form-label fw-semibold">Currency <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="currency" name="currency" maxlength="10" value="BDT" required>
                </div>

                <div class="col-md-6">
                    <label for="financial_year_start" class="form-label fw-semibold">Financial year start date</label>
                    <input class="form-control" type="date" id="financial_year_start" name="financial_year_start">
                </div>

                <div class="col-md-6">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <button class="btn btn-outline-secondary" type="reset">Clear</button>
                <button class="btn btn-primary" type="submit" name="create">Create company</button>
            </div>
        </form>
          </div>
        </div>
        </div>
      </div>
    </main>
</body>
</html>

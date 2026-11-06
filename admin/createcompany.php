
<?php 
    include_once ("assets/db/bdconfig.php"); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create company</title>
    <link href="assets/css/createcompany.css" rel="stylesheet">
</head>
<body>


    <main class="form-card">
        <h1>Create company</h1>
        <p class="intro">Enter the company details below. Fields marked with <span class="required">*</span> are required.</p>
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
                    echo "<p class='error'>Error creating company: " . $conn->error . "</p>";
               }
              }
              ?>

        <form method="post" accept="" enctype="multipart/form-data">
            <div class="fields">
                <div class="field full">
                    <label for="company_name">Company name <span class="required">*</span></label>
                    <input type="text" id="company_name" name="company_name" maxlength="150" required>
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" maxlength="150">
                </div>

                <div class="field">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" maxlength="30">
                </div>

                <div class="field full">
                    <label for="address">Address</label>
                    <textarea id="address" name="address"></textarea>
                </div>

                <div class="field">
                    <label for="logo">Logo</label>
                    <input type="file" id="logo" name="logo" accept="image/*">
                </div>

                <div class="field">
                    <label for="currency">Currency <span class="required">*</span></label>
                    <input type="text" id="currency" name="currency" maxlength="10" value="BDT" required>
                </div>

                <div class="field">
                    <label for="financial_year_start">Financial year start date</label>
                    <input type="date" id="financial_year_start" name="financial_year_start">
                </div>

                <div class="field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="actions">
                <button type="reset">Clear</button>
                <button type="submit" name="create">Create company</button>
            </div>
        </form>
    </main>
</body>
</html>

<?php
include 'assets/db/bdconfig.php';
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit;
}

if (($_SESSION['role'] ?? '') != 'admin') {
    header("Location: unauthorize.php");
    exit;
}

$company_id = (int) ($_SESSION['company_id'] ?? 1);

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $accountname = $conn -> real_escape_string(trim($_POST['account_name']));
    $accountcode = $conn -> real_escape_string(trim($_POST['account_code']));
    $accountcategory = $conn -> real_escape_string($_POST['account_category']);
    $parentaccount = ($_POST['parent_account'] ?? '') !== '' ? (int) $_POST['parent_account'] : "NULL";
    $opening_balance = (float) ($_POST['opening_balance'] ?? 0);
    $opening_balance_side = $conn -> real_escape_string($_POST['opening_balance_side']);
    $is_active = isset($_POST['account_active']) ? 1 : 0;

    $conn -> query("INSERT INTO accounts (company_id, parent_account_id, account_code, account_name, opening_balance, opening_balance_side, is_active, account_categorie)
    VALUES ('$company_id', $parentaccount, '$accountcode', '$accountname', '$opening_balance', '$opening_balance_side', '$is_active', '$accountcategory')");

    if($conn -> affected_rows){
        header('Location: chart_of_accounts.php');
        exit;
    }
}

$parents = $conn -> query("SELECT account_id, account_code, account_name FROM accounts WHERE company_id = $company_id ORDER BY account_code");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Account | BizLedger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/dashboard-shared.css" rel="stylesheet">
    <link href="assets/css/account-form.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary dashboard-page account-form-page">
    <div class="container-fluid px-0">
        <div class="row min-vh-100">
            <div class="col-lg-3 col-xl-2 p-0 bg-dark">
                <?php include 'include/adminsidebar.php'; ?>
            </div>
            <main class="col-12 col-lg-9 col-xl-10 p-0">
                <?php include 'include/adminheadbar.php'; ?>
                <div class="account-form-content p-3 p-md-4 p-xl-5">
                    <section class="account-form-hero rounded-4 shadow-sm text-white p-4 p-lg-5 mb-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                            <div>
                                <span class="badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 mb-3">
                                    <i class="bi bi-bank me-1" aria-hidden="true"></i> Chart of accounts
                                </span>
                                <h1 class="h2 fw-bold mb-2">Add a new account</h1>
                                <p class="mb-0 text-white-50">Create an account to keep your business finances organized.</p>
                            </div>
                            <a class="btn btn-light text-primary fw-semibold px-3 py-2 text-nowrap" href="chart_of_accounts.php">
                                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Back to accounts
                            </a>
                        </div>
                    </section>

                

                    <div class="row g-4 align-items-start">
                        <div class="col-12 col-xl-8">
                            <section class="card account-form-card rounded-4" aria-labelledby="account-details-title">
                                <div class="card-body p-4 p-lg-5">
                                    <div class="d-flex align-items-start gap-3 mb-4">
                                        <span class="account-form-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary">
                                            <i class="bi bi-journal-plus fs-4" aria-hidden="true"></i>
                                        </span>
                                        <div>
                                            <h2 class="h5 fw-bold mb-1" id="account-details-title">Account details</h2>
                                            <p class="small text-secondary mb-0">Enter the main information for this account.</p>
                                        </div>
                                    </div>

                                    <form class="account-entry-form" method="post">
                                        <div class="row g-3">
                                            <div class="col-12 col-md-7">
                                                <label class="form-label" for="account_name">Account name <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" id="account_name" name="account_name"  placeholder="e.g. Business checking" maxlength="120" required>
                                                <div class="form-text">Choose a clear name you'll recognize in reports.</div>
                                            </div>
                                            <div class="col-12 col-md-5">
                                                <label class="form-label" for="account_code">Account code <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" id="account_code" name="account_code" placeholder="e.g. 1010" maxlength="30" required>
                                                <div class="form-text">Use a unique code in your chart.</div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="account_category">Account category <span class="text-danger">*</span></label>
                                                <select class="form-select" id="account_category" name="account_category" required>
                                                    <option value="" selected disabled>Select a category</option>
                                                    <option value="ASSET">Asset</option>
                                                    <option value="LIABILITY">Liability</option>
                                                    <option value="EQUITY">Equity</option>
                                                    <option value="REVENUE">Revenue</option>
                                                    <option value="EXPENSE">Expense</option>
                                
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="parent_account">Parent account <span class="text-secondary fw-normal">(optional)</span></label>
                                                <select class="form-select" id="parent_account" name="parent_account">
    <option value="" selected>No parent account</option>
    <?php while($p = $parents -> fetch_assoc()): ?>
        <option value="<?php echo (int) $p['account_id']; ?>">
            <?php echo htmlspecialchars($p['account_code'] . ' · ' . $p['account_name']); ?>
        </option>
    <?php endwhile; ?>
</select>
                                                <div class="form-text">Use a parent to group related accounts.</div>
                                            </div>
                                        </div>

                                        <hr class="account-form-divider my-4">

                                        <div class="mb-3">
                                            <h3 class="h6 fw-bold mb-1">Opening balance</h3>
                                            <p class="small text-secondary mb-0">Set the starting amount for this account.</p>
                                        </div>
                                        <div class="row g-3 align-items-end">
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="opening_balance">Amount</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input class="form-control" type="number" id="opening_balance" name="opening_balance" min="0" step="0.01" placeholder="0.00">
                                                </div>
                                                <div class="form-text">Enter 0.00 if there is no opening balance.</div>
                                            </div>
                                            <fieldset class="col-12 col-md-6">
                                                <legend class="form-label">Balance side</legend>
                                                <div class="account-side-choice d-flex rounded-3 p-1">
                                                    <input class="btn-check" type="radio" name="opening_balance_side" id="balance-debit" value="DEBIT" checked>
                                                    <label class="btn flex-fill" for="balance-debit"><i class="bi bi-box-arrow-in-down me-1" aria-hidden="true"></i>Debit</label>
                                                    <input class="btn-check" type="radio" name="opening_balance_side" id="balance-credit" value="CREDIT">
                                                    <label class="btn flex-fill" for="balance-credit"><i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i>Credit</label>
                                                </div>
                                            </fieldset>
                                        </div>

                                        <hr class="account-form-divider my-4">

                                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                                            <div>
                                                <label class="form-label fw-semibold mb-1" for="account_active">Account status</label>
                                                <p class="small text-secondary mb-0">Inactive accounts won't appear in new transactions.</p>
                                            </div>
                                            <div class="form-check form-switch account-status-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="account_active" name="account_active" checked>
                                                <label class="form-check-label fw-semibold" for="account_active">Active</label>
                                            </div>
                                        </div>

                                        <div class="account-form-actions d-flex flex-column-reverse flex-sm-row justify-content-end gap-2 mt-4 pt-3 border-top">
                                            <a class="btn btn-light border px-4" href="chart_of_accounts.php">Cancel</a>
                                            <button class="btn btn-primary px-4" type="submit" name="submit">
                                                <i class="bi bi-check2 me-1" aria-hidden="true"></i>Save account
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </section>
                        </div>

                        <div class="col-12 col-xl-4">
                            <aside class="card account-form-card rounded-4 mb-4" aria-labelledby="category-guide-title">
                                <div class="card-body p-4">
                                    <span class="account-form-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-info-subtle text-info mb-3">
                                        <i class="bi bi-lightbulb fs-4" aria-hidden="true"></i>
                                    </span>
                                    <h2 class="h6 fw-bold" id="category-guide-title">Choose the right category</h2>
                                    <p class="small text-secondary mb-3">Categories help keep your books and reports easy to understand.</p>
                                    <div class="account-category-guide">
                                        <div><span class="category-dot bg-success"></span><strong>Asset</strong><span>What your business owns</span></div>
                                        <div><span class="category-dot bg-warning"></span><strong>Liability</strong><span>What your business owes</span></div>
                                        <div><span class="category-dot bg-primary"></span><strong>Equity</strong><span>Owner's share of the business</span></div>
                                        <div><span class="category-dot bg-info"></span><strong>Revenue</strong><span>Income earned by the business</span></div>
                                        <div><span class="category-dot bg-danger"></span><strong>Expense</strong><span>Costs of running the business</span></div>
                                    </div>
                                </div>
                            </aside>

                            <aside class="card account-form-card rounded-4" aria-labelledby="automatic-fields-title">
                                <div class="card-body p-4">
                                    <h2 class="h6 fw-bold mb-2" id="automatic-fields-title">
                                        <i class="bi bi-shield-check text-success me-2" aria-hidden="true"></i>Handled automatically
                                    </h2>
                                    <p class="small text-secondary mb-3">These system fields don't need to be entered here.</p>
                                    <div class="account-auto-fields">
                                        <div><span>Account ID</span><span>Auto-generated</span></div>
                                        <div><span>Company</span><span>Current company</span></div>
                                        <div><span>Created / updated</span><span>Automatic</span></div>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

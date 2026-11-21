<?php
include 'assets/db/bdconfig.php';
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit;
}

if (!in_array($_SESSION['role'] ?? '', ['admin', 'accountant'], true)) {
    header("Location: unauthorize.php");
    exit;
}

$company_id = (int) ($_SESSION['company_id'] ?? 1);
$error_message = '';
$accountname = '';
$accountcode = '';
$accountcategory = '';
$parentaccount = '';
$opening_balance = '0';
$opening_balance_side = 'DEBIT';
$is_active = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $accountname = trim($_POST['account_name']);
    $accountcode = trim($_POST['account_code']);
    $accountcategory = trim($_POST['account_category'] ?? '');
    $parentaccount = ($_POST['parent_account'] ?? '') !== '' ? (int) $_POST['parent_account'] : '';
    $opening_balance = (string) ($_POST['opening_balance'] ?? '0');
    $opening_balance_side = $_POST['opening_balance_side'] ?? 'DEBIT';
    $is_active = isset($_POST['account_active']) ? 1 : 0;

    $allowed_categories = ['ASSET', 'LIABILITY', 'EQUITY', 'REVENUE', 'EXPENSE'];
    $allowed_sides = ['DEBIT', 'CREDIT'];
    $opening_balance_value = is_numeric($opening_balance) ? (float) $opening_balance : -1;

    if ($accountname === '' || $accountcode === '' || $accountcategory === '') {
        $error_message = 'Account name, account code, and category are required.';
    } elseif (!in_array($accountcategory, $allowed_categories, true)) {
        $error_message = 'Please select a valid account category.';
    } elseif (!in_array($opening_balance_side, $allowed_sides, true)) {
        $error_message = 'Please select a valid balance side.';
    } elseif ($opening_balance_value < 0) {
        $error_message = 'Opening balance must be zero or a positive amount.';
    } elseif (strlen($accountname) > 120 || strlen($accountcode) > 30) {
        $error_message = 'Account name or account code exceeds the allowed length.';
    } elseif ($parentaccount !== '') {
        $parent_check = $conn->prepare(
            "SELECT account_id FROM accounts WHERE account_id = ? AND company_id = ? LIMIT 1"
        );
        $parent_check->bind_param('ii', $parentaccount, $company_id);
        $parent_check->execute();
        $parent_check->store_result();
        if ($parent_check->num_rows === 0) {
            $error_message = 'Please select a valid parent account from this company.';
        }
        $parent_check->close();
    }

    if ($error_message === '') {
        $duplicate = $conn->prepare(
            "SELECT account_id FROM accounts WHERE company_id = ? AND account_code = ? LIMIT 1"
        );
        $duplicate->bind_param('is', $company_id, $accountcode);
        $duplicate->execute();
        $duplicate->store_result();

        if ($duplicate->num_rows > 0) {
            $error_message = 'Account code "' . htmlspecialchars($accountcode) . '" already exists in this company.';
        } else {
            $parent_id = $parentaccount !== '' ? (int) $parentaccount : null;
            $stmt = $conn->prepare(
                "INSERT INTO accounts
                    (company_id, parent_account_id, account_code, account_name, opening_balance, opening_balance_side, is_active, account_categorie)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param(
                'iissdsis',
                $company_id,
                $parent_id,
                $accountcode,
                $accountname,
                $opening_balance_value,
                $opening_balance_side,
                $is_active,
                $accountcategory
            );

            try {
                if ($stmt->execute()) {
                    header('Location: chart_of_accounts.php');
                    exit;
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() === 1062) {
                    $error_message = 'Account code "' . htmlspecialchars($accountcode) . '" already exists in this company.';
                } else {
                    error_log('Failed to create account: ' . $e->getMessage());
                    $error_message = 'Unable to create the account. Please try again.';
                }
            }
            $stmt->close();
        }
        $duplicate->close();
    }
}

$parents = $conn->query("SELECT account_id, account_code, account_name FROM accounts WHERE company_id = $company_id ORDER BY account_code");
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
    <link href="assets/css/glassmorphism.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary dashboard-page account-form-page">
    <div class="container-fluid px-0">
        <div class="row min-vh-100">
            <div class="col-lg-3 col-xl-2 p-0 bg-dark">
                <?php if (($_SESSION['role'] ?? '') === 'accountant') {
                    include 'include/accountendsidebar.php';
                } else {
                    include 'include/adminsidebar.php';
                } ?>
            </div>
            <main class="col-12 col-lg-9 col-xl-10 p-0">
                <?php if (($_SESSION['role'] ?? '') === 'accountant') {
                    include 'include/accountendheadbar.php';
                } else {
                    include 'include/adminheadbar.php';
                } ?>
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
                                        <?php if ($error_message !== ''): ?>
                                            <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                                                <i class="bi bi-exclamation-triangle-fill me-2" aria-hidden="true"></i>
                                                <div><?php echo $error_message; ?></div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="row g-3">
                                            <div class="col-12 col-md-7">
                                                <label class="form-label" for="account_name">Account name <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" id="account_name" name="account_name" placeholder="e.g. Business checking" maxlength="120" value="<?php echo htmlspecialchars($accountname, ENT_QUOTES, 'UTF-8'); ?>" required>
                                                <div class="form-text">Choose a clear name you'll recognize in reports.</div>
                                            </div>
                                            <div class="col-12 col-md-5">
                                                <label class="form-label" for="account_code">Account code <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" id="account_code" name="account_code" placeholder="e.g. 1010" maxlength="30" value="<?php echo htmlspecialchars($accountcode, ENT_QUOTES, 'UTF-8'); ?>" required>
                                                <div class="form-text">Use a unique code in your chart.</div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="account_category">Account category <span class="text-danger">*</span></label>
                                                <select class="form-select" id="account_category" name="account_category" required>
                                                    <option value="" selected disabled>Select a category</option>
                                                    <option value="ASSET" <?php echo $accountcategory === 'ASSET' ? 'selected' : ''; ?>>Asset</option>
                                                    <option value="LIABILITY" <?php echo $accountcategory === 'LIABILITY' ? 'selected' : ''; ?>>Liability</option>
                                                    <option value="EQUITY" <?php echo $accountcategory === 'EQUITY' ? 'selected' : ''; ?>>Equity</option>
                                                    <option value="REVENUE" <?php echo $accountcategory === 'REVENUE' ? 'selected' : ''; ?>>Revenue</option>
                                                    <option value="EXPENSE" <?php echo $accountcategory === 'EXPENSE' ? 'selected' : ''; ?>>Expense</option>

                                                </select>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="parent_account">Parent account <span class="text-secondary fw-normal">(optional)</span></label>
                                                <select class="form-select" id="parent_account" name="parent_account">
                                                    <option value="" <?php echo $parentaccount === '' ? 'selected' : ''; ?>>No parent account</option>
                                                    <?php while ($p = $parents->fetch_assoc()): ?>
                                                        <option value="<?php echo (int) $p['account_id']; ?>" <?php echo (string) $parentaccount === (string) $p['account_id'] ? 'selected' : ''; ?>>
                                                            <?php echo $p['account_code'] . ' · ' . $p['account_name']; ?>
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
                                                    <input class="form-control" type="number" id="opening_balance" name="opening_balance" min="0" step="0.01" placeholder="0.00" value="<?php echo htmlspecialchars((string) $opening_balance, ENT_QUOTES, 'UTF-8'); ?>">
                                                </div>
                                                <div class="form-text">Enter 0.00 if there is no opening balance.</div>
                                            </div>
                                            <fieldset class="col-12 col-md-6">
                                                <legend class="form-label">Balance side</legend>
                                                <div class="account-side-choice d-flex rounded-3 p-1">
                                                    <input class="btn-check" type="radio" name="opening_balance_side" id="balance-debit" value="DEBIT" <?php echo $opening_balance_side === 'DEBIT' ? 'checked' : ''; ?>>
                                                    <label class="btn flex-fill" for="balance-debit"><i class="bi bi-box-arrow-in-down me-1" aria-hidden="true"></i>Debit</label>
                                                    <input class="btn-check" type="radio" name="opening_balance_side" id="balance-credit" value="CREDIT" <?php echo $opening_balance_side === 'CREDIT' ? 'checked' : ''; ?>>
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
                                                <input class="form-check-input" type="checkbox" role="switch" id="account_active" name="account_active" <?php echo $is_active ? 'checked' : ''; ?>>
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
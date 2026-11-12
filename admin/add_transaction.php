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

    $type = ($_POST['type'] ?? '') == 'expense' ? 'expense' : 'income';
    $category_id = (int) $_POST['category'];
    $account_id = (int) $_POST['account'];
    $amount = (float) $_POST['amount'];
    $date = $conn -> real_escape_string($_POST['date']);
    $description = $conn -> real_escape_string(trim($_POST['description'] ?? ''));
    $created_by = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : "NULL";

    if($type == 'income'){
        $conn -> query("INSERT INTO incomes (company_id, income_category_id, account_id, amount, income_date, description, created_by)
        VALUES ('$company_id', '$category_id', '$account_id', '$amount', '$date', '$description', $created_by)");
    } else {
        $conn -> query("INSERT INTO expenses (company_id, expense_category_id, account_id, amount, expense_date, description, created_by)
        VALUES ('$company_id', '$category_id', '$account_id', '$amount', '$date', '$description', $created_by)");
    }

    if($conn -> affected_rows){
        header('Location: transactions.php');
        exit;
    }
}

$income_cats = $conn -> query("SELECT income_category_id, category_name FROM income_categories WHERE company_id = $company_id ORDER BY category_name");
$expense_cats = $conn -> query("SELECT expense_category_id, category_name FROM expense_categories WHERE company_id = $company_id ORDER BY category_name");
$accounts = $conn -> query("SELECT account_id, account_code, account_name FROM accounts WHERE company_id = $company_id AND is_active = 1 AND account_categorie = 'ASSET' ORDER BY account_code");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Transaction | BizLedger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/dashboard-shared.css" rel="stylesheet">
    <link href="assets/css/transactions.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary dashboard-page transaction-page">
    <div class="container-fluid px-0">
        <div class="row min-vh-100">
            <div class="col-lg-3 col-xl-2 p-0 bg-dark">
                <?php include 'include/adminsidebar.php'; ?>
            </div>
            <main class="col-12 col-lg-9 col-xl-10 p-0">
                <?php include 'include/adminheadbar.php'; ?>
                <div class="p-3 p-md-4 p-xl-5">
                    <nav class="transaction-breadcrumb small mb-3" aria-label="Breadcrumb">
                        <a href="dashboard_admin.php" class="text-decoration-none">Dashboard</a>
                        <i class="bi bi-chevron-right mx-2" aria-hidden="true"></i>
                        <a href="transactions.php" class="text-decoration-none">Transactions</a>
                        <i class="bi bi-chevron-right mx-2" aria-hidden="true"></i>
                        <span aria-current="page">New transaction</span>
                    </nav>
                    <section class="transaction-hero transaction-entry-hero rounded-4 shadow-sm text-white p-4 p-lg-5 mb-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                            <div>
                                <span class="badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 mb-3">
                                    <i class="bi bi-plus-circle me-1" aria-hidden="true"></i> Income &amp; expenses
                                </span>
                                <h1 class="h2 fw-bold mb-2">Add transaction</h1>
                                <p class="mb-0 text-white-50">Keep money coming in and going out organized in one place.</p>
                            </div>
                            <a class="btn btn-light text-primary fw-semibold px-3 py-2 text-nowrap" href="transactions.php">
                                <i class="bi bi-list-ul me-1" aria-hidden="true"></i>View all transactions
                            </a>
                        </div>
                    </section>

                    

                    <div class="row g-4 align-items-start">
                        <div class="col-12 col-xl-8">
                            <section class="card transaction-panel transaction-entry-card rounded-4" aria-labelledby="transaction-details-title">
                                <div class="card-body p-4 p-lg-5">
                                    <div class="d-flex align-items-start gap-3 mb-4">
                                        <span class="transaction-entry-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary">
                                            <i class="bi bi-receipt fs-4" aria-hidden="true"></i>
                                        </span>
                                        <div>
                                            <h2 class="h5 fw-bold mb-1" id="transaction-details-title">Transaction details</h2>
                                            <p class="small text-secondary mb-0">Fields marked with <span class="text-danger">*</span> are required.</p>
                                        </div>
                                    </div>

                                    <form class="transaction-form" method="post">
                                        <fieldset class="mb-4">
                                            <legend class="form-label">Transaction type <span class="text-danger">*</span></legend>
                                            <div class="transaction-type-options row g-2">
                                                <div class="col-6">
                                                    <input class="btn-check" type="radio" name="type" id="type-income" value="income" autocomplete="off" checked>
                                                    <label class="btn transaction-type-choice transaction-income-choice w-100 text-start p-3" for="type-income">
                                                        <span class="d-flex align-items-center gap-3">
                                                            <span class="transaction-type-icon d-inline-flex align-items-center justify-content-center rounded-3">
                                                                <i class="bi bi-arrow-down-left-circle fs-4" aria-hidden="true"></i>
                                                            </span>
                                                            <span><strong class="d-block">Income</strong><small>Money received</small></span>
                                                            <i class="bi bi-check-circle-fill transaction-type-check ms-auto" aria-hidden="true"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="col-6">
                                                    <input class="btn-check" type="radio" name="type" id="type-expense" value="expense" autocomplete="off">
                                                    <label class="btn transaction-type-choice transaction-expense-choice w-100 text-start p-3" for="type-expense">
                                                        <span class="d-flex align-items-center gap-3">
                                                            <span class="transaction-type-icon d-inline-flex align-items-center justify-content-center rounded-3">
                                                                <i class="bi bi-arrow-up-right-circle fs-4" aria-hidden="true"></i>
                                                            </span>
                                                            <span><strong class="d-block">Expense</strong><small>Money spent</small></span>
                                                            <i class="bi bi-check-circle-fill transaction-type-check ms-auto" aria-hidden="true"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </fieldset>

                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="transaction-amount">Amount <span class="text-danger">*</span></label>
                                                <div class="input-group input-group-lg transaction-amount-input">
                                                    <span class="input-group-text">$</span>
                                                    <input class="form-control" type="number" id="transaction-amount" name="amount" min="0.01" step="0.01" placeholder="0.00" required>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="transaction-date">Date <span class="text-danger">*</span></label>
                                                <input class="form-control form-control-lg" type="date" id="transaction-date" name="date" required>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="transaction-category">Category <span class="text-danger">*</span></label>
                                                <select class="form-select form-select-lg" id="transaction-category" name="category" required>
                                                    <option value="" selected disabled>Select a category</option>
                                                    <optgroup label="Income categories" id="income-cats">
                                                        <?php while($c = $income_cats -> fetch_assoc()): ?>
                                                            <option value="<?php echo (int) $c['income_category_id']; ?>"><?php echo htmlspecialchars($c['category_name']); ?></option>
                                                        <?php endwhile; ?>
                                                    </optgroup>
                                                    <optgroup label="Expense categories" id="expense-cats" disabled>
                                                        <?php while($c = $expense_cats -> fetch_assoc()): ?>
                                                            <option value="<?php echo (int) $c['expense_category_id']; ?>"><?php echo htmlspecialchars($c['category_name']); ?></option>
                                                        <?php endwhile; ?>
                                                    </optgroup>
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="payment-account">Cash / bank account <span class="text-danger">*</span></label>
                                                <select class="form-select form-select-lg" id="payment-account" name="account" required>
                                                    <option value="" selected disabled>Select an account</option>
                                                    <?php while($a = $accounts -> fetch_assoc()): ?>
                                                        <option value="<?php echo (int) $a['account_id']; ?>"><?php echo htmlspecialchars($a['account_code'] . ' · ' . $a['account_name']); ?></option>
                                                    <?php endwhile; ?>
                                                </select>
                                                <div class="form-text">Choose the account where money was received or paid.</div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label" for="transaction-description">Description <span class="text-secondary fw-normal">(optional)</span></label>
                                                <textarea class="form-control" id="transaction-description" name="description" rows="3" placeholder="Add a short explanation for this transaction"></textarea>
                                            </div>
                                        </div>

                                        <div class="transaction-form-actions d-flex flex-column-reverse flex-sm-row justify-content-end gap-2 mt-4 pt-3 border-top">
                                            <a class="btn btn-light border px-4" href="transactions.php">Cancel</a>
                                            <button class="btn btn-primary px-4" type="submit">
                                                <i class="bi bi-check2 me-1" aria-hidden="true"></i>Save transaction
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </section>
                        </div>

                        <div class="col-12 col-xl-4 transaction-entry-aside">
                            <aside class="card transaction-panel transaction-entry-card rounded-4 mb-4" aria-labelledby="transaction-guide-title">
                                <div class="card-body p-4">
                                    <span class="transaction-entry-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-info-subtle text-info mb-3">
                                        <i class="bi bi-lightbulb fs-4" aria-hidden="true"></i>
                                    </span>
                                    <h2 class="h6 fw-bold" id="transaction-guide-title">A clear record goes a long way</h2>
                                    <p class="small text-secondary mb-3">A few details now make it easier to understand your books later.</p>
                                    <ul class="transaction-guide-list list-unstyled small text-secondary mb-0">
                                        <li><i class="bi bi-check-circle-fill text-success" aria-hidden="true"></i><span>Choose income for money received and expense for money paid.</span></li>
                                        <li><i class="bi bi-check-circle-fill text-success" aria-hidden="true"></i><span>Select the cash or bank account used.</span></li>
                                        <li><i class="bi bi-check-circle-fill text-success" aria-hidden="true"></i><span>Attach a receipt to support the transaction.</span></li>
                                    </ul>
                                </div>
                            </aside>

                            <aside class="card transaction-panel transaction-entry-card rounded-4" aria-labelledby="transaction-privacy-title">
                                <div class="card-body p-4">
                                    <h2 class="h6 fw-bold mb-2" id="transaction-privacy-title">
                                        <i class="bi bi-shield-check text-success me-2" aria-hidden="true"></i>Keep your records secure
                                    </h2>
                                    <p class="small text-secondary mb-0">Only attach documents related to this transaction, and make sure the amount and date are correct.</p>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    const incomeCats = document.getElementById('income-cats');
    const expenseCats = document.getElementById('expense-cats');
    const catSelect = document.getElementById('transaction-category');
    function switchType() {
        const isExpense = document.getElementById('type-expense').checked;
        incomeCats.disabled = isExpense;
        expenseCats.disabled = !isExpense;
        catSelect.value = '';
    }
    document.getElementById('type-income').addEventListener('change', switchType);
    document.getElementById('type-expense').addEventListener('change', switchType);
    </script>
</body>
</html>
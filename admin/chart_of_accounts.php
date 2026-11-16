<?php
session_start();
include_once 'assets/db/bdconfig.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit;
}

if (!in_array($_SESSION['role'] ?? '', ['admin', 'accountant'], true)) {
    header("Location: unauthorize.php");
    exit;
}

$company_id = (int) ($_SESSION['company_id'] ?? 1);
$rowData = $conn->query(
    "SELECT account_id, parent_account_id, account_code, account_name, opening_balance, is_active, account_categorie
     FROM accounts
     WHERE company_id = $company_id
     ORDER BY account_categorie, account_code, account_id"
);
$rows = $rowData->fetch_all(MYSQLI_ASSOC);

$meta = [
    'ASSET'     => ['Assets', 'Asset', 'bi-safe', 'success'],
    'LIABILITY' => ['Liabilities', 'Liability', 'bi-credit-card', 'warning'],
    'EQUITY'    => ['Equity', 'Equity', 'bi-pie-chart', 'primary'],
    'REVENUE'   => ['Revenue', 'Revenue', 'bi-cash-stack', 'info'],
    'EXPENSE'   => ['Expenses', 'Expense', 'bi-receipt', 'danger'],
];
$counts = [];
foreach ($rows as $row) {
    $category = strtoupper((string) ($row['account_categorie'] ?? ''));
    $counts[$category] = ($counts[$category] ?? 0) + 1;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chart of Accounts | BizLedger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/dashboard-shared.css" rel="stylesheet">
    <link href="assets/css/accounts.css" rel="stylesheet">
    <link href="assets/css/glassmorphism.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary dashboard-page account-page">
    <div class="container-fluid px-0">
        <div class="row min-vh-100">
            <div class="col-lg-3 col-xl-2 p-0 bg-dark">
                <?php if (($_SESSION['role'] ?? '') === 'accountant') { include 'include/accountendsidebar.php'; } else { include 'include/adminsidebar.php'; } ?>
            </div>
            <main class="col-12 col-lg-9 col-xl-10 p-0">
                <?php if (($_SESSION['role'] ?? '') === 'accountant') { include 'include/accountendheadbar.php'; } else { include 'include/adminheadbar.php'; } ?>
                <div class="p-3 p-md-4 p-xl-5">
                    <section class="account-hero rounded-4 shadow-sm text-white p-4 p-lg-5 mb-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                            <div>
                                <span class="badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 mb-3">
                                    <i class="bi bi-diagram-3 me-1" aria-hidden="true"></i> Account structure
                                </span>
                                <h1 class="h2 fw-bold mb-2">Chart of accounts</h1>
                                <p class="mb-0 text-white-50">Organize the accounts used to track your business finances.</p>
                            </div>
                            <a class="btn btn-light text-primary fw-semibold px-3 py-2 text-nowrap" href="add_account.php">
                                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Add account
                            </a>
                        </div>
                    </section>

                

                    <section class="row g-3 mb-4" aria-label="Account summary">
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card account-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4">
                                    <span class="account-stat-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary fs-4"><i class="bi bi-diagram-3" aria-hidden="true"></i></span>
                                    <div>
                                        <div class="small text-secondary">Total accounts</div>
                                        <div class="h4 fw-bold mb-0"><?php echo count($rows); ?></div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card account-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4">
                                    <span class="account-stat-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-success-subtle text-success fs-4"><i class="bi bi-safe" aria-hidden="true"></i></span>
                                    <div>
                                        <div class="small text-secondary">Assets</div>
                                        <div class="h4 fw-bold mb-0"><?php echo $counts['ASSET'] ?? 0; ?> accounts</div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card account-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4">
                                    <span class="account-stat-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-warning-subtle text-warning fs-4"><i class="bi bi-credit-card" aria-hidden="true"></i></span>
                                    <div>
                                        <div class="small text-secondary">Liabilities</div>
                                        <div class="h4 fw-bold mb-0"><?php echo $counts['LIABILITY'] ?? 0; ?> accounts</div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card account-stat rounded-4 h-100">
                                <div class="card-body d-flex align-items-center gap-3 p-4">
                                    <span class="account-stat-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-info-subtle text-info fs-4"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i></span>
                                    <div>
                                        <div class="small text-secondary">Income &amp; expenses</div>
                                        <div class="h4 fw-bold mb-0"><?php echo ($counts['REVENUE'] ?? 0) + ($counts['EXPENSE'] ?? 0); ?> accounts</div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </section>

                    <section class="card account-panel rounded-4 overflow-hidden" aria-labelledby="accounts-title">
                        <div class="card-body p-0">
                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 p-3 p-md-4 border-bottom">
                                <div>
                                    <h2 class="h5 fw-bold mb-1" id="accounts-title">Your accounts</h2>
                                    <p class="small text-secondary mb-0">Browse accounts by type and view their opening balances</p>
                                </div>
                                <div class="account-toolbar d-flex flex-column flex-sm-row gap-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-search" aria-hidden="true"></i></span>
                                        <input class="form-control" id="account-search" type="search" placeholder="Search accounts" aria-label="Search accounts">
                                    </div>
                                    <select class="form-select" id="account-category-filter" aria-label="Filter by account type">
                                        <option value="" selected>All account types</option>
                                        <option value="ASSET">Assets</option>
                                        <option value="LIABILITY">Liabilities</option>
                                        <option value="EQUITY">Equity</option>
                                        <option value="REVENUE">Revenue</option>
                                        <option value="EXPENSE">Expenses</option>
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle account-table mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="ps-4 py-3 small text-uppercase text-secondary">Account</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary">Type</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary text-end">Opening balance</th>
                                            <th scope="col" class="py-3 small text-uppercase text-secondary">Status</th>
                                            <th scope="col" class="pe-4 py-3 small text-uppercase text-secondary text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $current = '';
                                        foreach ($rows as $row):
                                            $cat = strtoupper((string) ($row['account_categorie'] ?? ''));
                                            $m = $meta[$cat] ?? ['Other', 'Other', 'bi-folder', 'secondary'];
                                            if ($cat != $current):
                                                $current = $cat;
                                        ?>
                                                <tr class="account-group-row" data-account-group="<?php echo $cat; ?>">
                                                    <td class="ps-4 py-3" colspan="5"><span class="account-type-icon d-inline-flex align-items-center justify-content-center rounded-3 bg-<?php echo $m[3]; ?>-subtle text-<?php echo $m[3]; ?> me-2"><i class="bi <?php echo $m[2]; ?>" aria-hidden="true"></i></span><span class="fw-bold"><?php echo $m[0]; ?></span><span class="small text-secondary ms-2"><?php echo $counts[$cat] ?? 0; ?> accounts</span></td>
                                                </tr>
                                            <?php endif; ?>
                                            <tr data-account-row data-account-category="<?php echo $cat ; ?>" data-account-search="<?php echo strtolower(implode(' ', [$row['account_code'], $row['account_name'], $m[0], $m[1], $row['is_active'] ? 'active' : 'inactive'])); ?>">
                                                <?php if ($row['parent_account_id'] !== null): ?>
                                                    <td class="account-child py-3"><span class="text-secondary me-2" aria-hidden="true">↳</span><span class="fw-semibold"><?php echo $row['account_code'] . ' · ' . $row['account_name']; ?></span></td>
                                                <?php else: ?>
                                                    <td class="ps-4 py-3"><span class="fw-semibold"><?php echo $row['account_code'] . ' · ' . $row['account_name']; ?></span></td>
                                                <?php endif; ?>
                                                <td><span class="badge rounded-pill bg-<?php echo $m[3]; ?>-subtle text-<?php echo $m[3]; ?>-emphasis"><?php echo $m[1]; ?></span></td>
                                                <td class="text-end fw-semibold">$<?php echo number_format($row['opening_balance'], 2); ?></td>
                                                <td>
                                                    <?php if ($row['is_active']): ?>
                                                        <span class="badge rounded-pill bg-success-subtle text-success-emphasis">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="pe-4 text-end"><a class="btn btn-sm btn-light text-primary" href="ledgers.php" title="View ledger" aria-label="View ledger for <?php echo htmlspecialchars($row['account_name'], ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-journal-text" aria-hidden="true"></i></a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                            <tr id="account-empty-state" hidden>
                                                <td class="py-5 text-center text-secondary" id="account-empty-message" colspan="5">No accounts have been added yet.</td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 p-3 p-md-4 border-top">
                                <span class="small text-secondary" id="account-result-summary">Showing <?php echo count($rows); ?> accounts</span>
                                <nav aria-label="Account pages" id="account-pagination-nav" hidden>
                                    <ul class="pagination pagination-sm mb-0" id="account-pagination"></ul>
                                </nav>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            const search = document.getElementById('account-search');
            const categoryFilter = document.getElementById('account-category-filter');
            const tableBody = document.querySelector('.account-table tbody');
            const rows = Array.from(tableBody.querySelectorAll('[data-account-row]'));
            const groups = Array.from(tableBody.querySelectorAll('[data-account-group]'));
            const emptyState = document.getElementById('account-empty-state');
            const emptyMessage = document.getElementById('account-empty-message');
            const summary = document.getElementById('account-result-summary');
            const paginationNav = document.getElementById('account-pagination-nav');
            const pagination = document.getElementById('account-pagination');
            const pageSize = 8;
            let currentPage = 1;

            function addPageButton(label, page, disabled, active, ariaLabel) {
                const item = document.createElement('li');
                item.className = `page-item${disabled ? ' disabled' : ''}${active ? ' active' : ''}`;
                if (active) item.setAttribute('aria-current', 'page');

                const button = document.createElement('button');
                button.className = 'page-link';
                button.type = 'button';
                button.textContent = label;
                button.setAttribute('aria-label', ariaLabel);
                button.disabled = disabled || active;
                button.addEventListener('click', () => {
                    currentPage = page;
                    render();
                });
                item.appendChild(button);
                pagination.appendChild(item);
            }

            function render() {
                const query = search.value.trim().toLocaleLowerCase();
                const category = categoryFilter.value;
                const filteredRows = rows.filter(row => {
                    return (!category || row.dataset.accountCategory === category)
                        && (!query || row.dataset.accountSearch.includes(query));
                });
                const pageCount = Math.ceil(filteredRows.length / pageSize);
                currentPage = Math.min(currentPage, Math.max(pageCount, 1));
                const start = (currentPage - 1) * pageSize;
                const visibleRows = filteredRows.slice(start, start + pageSize);
                const visibleCategories = new Set(visibleRows.map(row => row.dataset.accountCategory));

                rows.forEach(row => {
                    row.hidden = !visibleRows.includes(row);
                });
                groups.forEach(group => {
                    group.hidden = !visibleCategories.has(group.dataset.accountGroup);
                });
                emptyState.hidden = filteredRows.length !== 0;
                emptyMessage.textContent = rows.length === 0
                    ? 'No accounts have been added yet.'
                    : 'No accounts match your search.';
                paginationNav.hidden = pageCount <= 1;
                summary.textContent = filteredRows.length === 0
                    ? 'Showing 0 accounts'
                    : `Showing ${start + 1}–${start + visibleRows.length} of ${filteredRows.length} accounts`;

                pagination.replaceChildren();
                if (pageCount > 1) {
                    addPageButton('‹', currentPage - 1, currentPage === 1, false, 'Previous page');
                    for (let page = 1; page <= pageCount; page += 1) {
                        addPageButton(String(page), page, false, page === currentPage, `Page ${page}`);
                    }
                    addPageButton('›', currentPage + 1, currentPage === pageCount, false, 'Next page');
                }
            }

            search.addEventListener('input', () => {
                currentPage = 1;
                render();
            });
            categoryFilter.addEventListener('change', () => {
                currentPage = 1;
                render();
            });
            render();
        })();
    </script>
</body>

</html>
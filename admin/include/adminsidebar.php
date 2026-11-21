<?php $current = basename($_SERVER['PHP_SELF']); ?>
<aside class="offcanvas-lg offcanvas-start w-100 h-100 min-vh-100 bg-dark text-white border-end border-white border-opacity-10" tabindex="-1" id="sidebar">
  <div class="offcanvas-header d-lg-none border-bottom border-white border-opacity-10 px-3 py-3">
    <h2 class="h5 mb-0 fw-bold">BizLedger</h2>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close menu"></button>
  </div>

  <div class="offcanvas-body d-flex flex-column p-3 p-xl-4">
    <a class="d-flex align-items-center gap-2 text-white text-decoration-none fw-bold fs-5 mb-4" href="dashboard_admin.php">
      <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary p-2 shadow-sm">
        <i class="bi bi-hexagon-fill"></i>
      </span>
      BizLedger
    </a>

    <div class="d-flex align-items-center gap-3 p-3 mb-4 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10">
      <?php if (!empty($_SESSION['profile_image'])) { ?>
        <img class="rounded-circle object-fit-cover" src="<?= htmlspecialchars($_SESSION['profile_image']) ?>" alt="<?= htmlspecialchars($_SESSION['name'] ?? 'User') ?>" style="width: 2.5rem; height: 2.5rem;">
      <?php } else { ?>
        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-semibold" style="width: 2.5rem; height: 2.5rem;">RI</div>
      <?php } ?>
      <div class="flex-grow-1 min-w-0">
        <div class="fw-semibold text-truncate"><?php echo htmlspecialchars($_SESSION['name'] ?? 'Admin'); ?></div>
        <small class="text-white-50"><?php echo htmlspecialchars($_SESSION['role'] ?? 'Admin'); ?></small>
      </div>
      <i class="bi bi-chevron-down text-white-50 small"></i>
    </div>

    <nav class="nav flex-column gap-1 small" id="menu">
      <a class="nav-link rounded-3 px-3 py-2 <?= $current == 'dashboard_admin.php' ? 'active bg-primary text-white shadow-sm' : 'text-white text-opacity-75' ?>" href="dashboard_admin.php">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
      </a>

      <div class="mt-2 mb-1 small fw-semibold text-uppercase text-white-50 px-2">Management</div>

      <a class="nav-link rounded-3 px-3 py-2 d-flex align-items-center <?= in_array($current, ['users.php', 'permissions.php', 'employlist.php', 'employedit.php']) ? 'text-white' : 'text-white text-opacity-75' ?>" data-bs-toggle="collapse" href="#sub0" aria-expanded="<?= in_array($current, ['users.php', 'permissions.php', 'employlist.php', 'employedit.php']) ? 'true' : 'false' ?>">
        <span><i class="bi bi-people-fill me-2"></i>Users &amp; Roles</span>
        <i class="bi bi-chevron-down ms-auto small"></i>
      </a>
      <div class="collapse <?= in_array($current, ['users.php', 'permissions.php', 'employlist.php', 'employedit.php']) ? 'show' : '' ?>" id="sub0">
        <a class="nav-link ps-4 py-2 small rounded-3 <?= in_array($current, ['employlist.php', 'employedit.php']) ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="employlist.php">All Users</a>
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'permissions.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="permissions.php">Roles &amp; Permissions</a>
      </div>

      <a class="nav-link rounded-3 px-3 py-2 d-flex align-items-center <?= in_array($current, ['transactions.php', 'add_transaction.php']) ? 'text-white' : 'text-white text-opacity-75' ?>" data-bs-toggle="collapse" href="#sub1" aria-expanded="<?= in_array($current, ['transactions.php', 'add_transaction.php']) ? 'true' : 'false' ?>">
        <span><i class="bi bi-arrow-left-right me-2"></i>Transactions</span>
        <i class="bi bi-chevron-down ms-auto small"></i>
      </a>
      <div class="collapse <?= in_array($current, ['transactions.php', 'add_transaction.php']) ? 'show' : '' ?>" id="sub1">
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'transactions.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="transactions.php">All Transactions</a>
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'add_transaction.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="add_transaction.php">Add Transaction</a>
      </div>

      <a class="nav-link rounded-3 px-3 py-2 d-flex align-items-center <?= in_array($current, ['products.php', 'stock.php']) ? 'text-white' : 'text-white text-opacity-75' ?>" data-bs-toggle="collapse" href="#sub2" aria-expanded="<?= in_array($current, ['products.php', 'stock.php']) ? 'true' : 'false' ?>">
        <span><i class="bi bi-box-seam-fill me-2"></i>Products &amp; Stock</span>
        <i class="bi bi-chevron-down ms-auto small"></i>
      </a>
      <div class="collapse <?= in_array($current, ['products.php', 'stock.php']) ? 'show' : '' ?>" id="sub2">
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'products.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="products.php">Products</a>
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'stock.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="stock.php">Stock Levels</a>
      </div>

      <a class="nav-link rounded-3 px-3 py-2 d-flex align-items-center <?= in_array($current, ['sales.php', 'invoices.php', 'customers.php']) ? 'text-white' : 'text-white text-opacity-75' ?>" data-bs-toggle="collapse" href="#sub3" aria-expanded="<?= in_array($current, ['sales.php', 'invoices.php', 'customers.php']) ? 'true' : 'false' ?>">
        <span><i class="bi bi-cart-fill me-2"></i>Sales &amp; Invoices</span>
        <i class="bi bi-chevron-down ms-auto small"></i>
      </a>
      <div class="collapse <?= in_array($current, ['sales.php', 'invoices.php', 'customers.php']) ? 'show' : '' ?>" id="sub3">
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'sales.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="sales.php">Sales</a>
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'invoices.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="invoices.php">Invoices</a>
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'customers.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="customers.php">Customers</a>
      </div>

      <a class="nav-link rounded-3 px-3 py-2 d-flex align-items-center <?= in_array($current, ['profit_loss.php', 'balance_sheet.php']) ? 'text-white' : 'text-white text-opacity-75' ?>" data-bs-toggle="collapse" href="#sub4" aria-expanded="<?= in_array($current, ['profit_loss.php', 'balance_sheet.php']) ? 'true' : 'false' ?>">
        <span><i class="bi bi-bar-chart-fill me-2"></i>Reports</span>
        <i class="bi bi-chevron-down ms-auto small"></i>
      </a>
      <div class="collapse <?= in_array($current, ['profit_loss.php', 'balance_sheet.php']) ? 'show' : '' ?>" id="sub4">
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'profit_loss.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="profit_loss.php">Profit &amp; Loss</a>
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'balance_sheet.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="balance_sheet.php">Balance Sheet</a>
      </div>

      <a class="nav-link rounded-3 px-3 py-2 d-flex align-items-center <?= in_array($current, ['chart_of_accounts.php', 'add_account.php', 'ledgers.php']) ? 'text-white' : 'text-white text-opacity-75' ?>" data-bs-toggle="collapse" href="#sub5" aria-expanded="<?= in_array($current, ['chart_of_accounts.php', 'add_account.php', 'ledgers.php']) ? 'true' : 'false' ?>">
        <span><i class="bi bi-bank2 me-2"></i>Accounts</span>
        <i class="bi bi-chevron-down ms-auto small"></i>
      </a>
      <div class="collapse <?= in_array($current, ['chart_of_accounts.php', 'add_account.php', 'ledgers.php']) ? 'show' : '' ?>" id="sub5">
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'chart_of_accounts.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="chart_of_accounts.php">Chart of Accounts</a>
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'add_account.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="add_account.php">Add Account</a>
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'ledgers.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="ledgers.php">Ledgers</a>
      </div>

      <a class="nav-link rounded-3 px-3 py-2 d-flex align-items-center <?= in_array($current, ['settings_general.php', 'preferences.php']) ? 'text-white' : 'text-white text-opacity-75' ?>" data-bs-toggle="collapse" href="#sub6" aria-expanded="<?= in_array($current, ['settings_general.php', 'preferences.php']) ? 'true' : 'false' ?>">
        <span><i class="bi bi-gear-fill me-2"></i>Settings</span>
        <i class="bi bi-chevron-down ms-auto small"></i>
      </a>
      <div class="collapse <?= in_array($current, ['settings_general.php', 'preferences.php']) ? 'show' : '' ?>" id="sub6">
        <a class="nav-link ps-4 py-2 small rounded-3 text-white text-opacity-75" href="settings_general.php">General</a>
        <a class="nav-link ps-4 py-2 small rounded-3 text-white text-opacity-75" href="preferences.php">Preferences</a>
      </div>
    </nav>

    <div class="text-uppercase small fw-semibold text-white-50 px-2 pt-4 pb-2">Admin Tools</div>
    <nav class="nav flex-column gap-1" id="tools"></nav>

    <hr class="border-light border-opacity-25 my-3">
    <nav class="nav flex-column gap-1 mt-auto">
      <a class="nav-link rounded-3 px-3 py-2 text-white text-opacity-75" href="#"><i class="bi bi-headset me-2"></i>Help &amp; Support</a>
      <a class="nav-link rounded-3 px-3 py-2 text-white text-opacity-75" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
    </nav>
  </div>
</aside>

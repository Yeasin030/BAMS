<?php $current = basename($_SERVER['PHP_SELF']); ?>
<aside class="offcanvas-lg offcanvas-start w-100 h-100 min-vh-100 bg-dark text-white" tabindex="-1" id="sidebar">
  <div class="offcanvas-header d-lg-none">
    <h2 class="h5 mb-0">BizLedger</h2>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close menu"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column p-2">
    <a class="d-flex align-items-center gap-2 text-white text-decoration-none fw-bold fs-6 mb-3" href="dashboard_admin.php">
      <i class="bi bi-hexagon-fill text-primary"></i> BizLedger
    </a>

    <nav class="nav flex-column gap-1 small" id="menu">

      <a class="nav-link rounded px-2 py-1 <?= $current == 'dashboard_admin.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="dashboard_admin.php">
        <i class="bi bi-speedometer2"></i>Dashboard
      </a>

      <!-- Users & Roles -->
      <a class="nav-link rounded d-flex align-items-center px-2 py-1 small text-white text-opacity-75" data-bs-toggle="collapse" href="#sub0">
        <i class="bi bi-people-fill"></i>Users &amp; Roles<i class="bi bi-chevron-down ms-auto small"></i>
      </a>
      <div class="collapse <?= in_array($current, ['users.php', 'permissions.php', 'employlist.php', 'employedit.php']) ? 'show' : '' ?>" id="sub0">
        <a class="nav-link ps-4 py-1 small <?= in_array($current, ['employlist.php', 'employedit.php']) ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="employlist.php">All Users</a>
        <a class="nav-link ps-4 py-1 small rounded <?= $current == 'permissions.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="permissions.php">Roles &amp; Permissions</a>
      </div>

      <!-- Transactions -->
      <a class="nav-link rounded d-flex align-items-center px-2 py-1 small <?= in_array($current, ['transactions.php', 'add_transaction.php']) ? 'text-white' : 'text-white text-opacity-75' ?>" data-bs-toggle="collapse" href="#sub1" aria-expanded="<?= in_array($current, ['transactions.php', 'add_transaction.php']) ? 'true' : 'false' ?>" aria-controls="sub1">
        <i class="bi bi-arrow-left-right"></i>Transactions<i class="bi bi-chevron-down ms-auto small"></i>
      </a>
      <div class="collapse <?= in_array($current, ['transactions.php', 'add_transaction.php']) ? 'show' : '' ?>" id="sub1">
        <a class="nav-link ps-4 py-1 small rounded <?= $current == 'transactions.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="transactions.php" <?= $current == 'transactions.php' ? 'aria-current="page"' : '' ?>>All Transactions</a>
        <a class="nav-link ps-4 py-1 small rounded <?= $current == 'add_transaction.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="add_transaction.php" <?= $current == 'add_transaction.php' ? 'aria-current="page"' : '' ?>>Add Transaction</a>
      </div>

      <!-- Products & Stock -->
      <a class="nav-link rounded d-flex align-items-center px-2 py-1 small text-white text-opacity-75" data-bs-toggle="collapse" href="#sub2">
        <i class="bi bi-box-seam-fill"></i>Products &amp; Stock<i class="bi bi-chevron-down ms-auto small"></i>
      </a>
      <div class="collapse <?= in_array($current, ['products.php', 'stock.php']) ? 'show' : '' ?>" id="sub2">
        <a class="nav-link ps-4 py-1 small rounded text-white text-opacity-75" href="products.php">Products</a>
        <a class="nav-link ps-4 py-1 small rounded text-white text-opacity-75" href="stock.php">Stock Levels</a>
      </div>

      <!-- Sales & Invoices -->
      <a class="nav-link rounded d-flex align-items-center px-2 py-1 small text-white text-opacity-75" data-bs-toggle="collapse" href="#sub3">
        <i class="bi bi-cart-fill"></i>Sales &amp; Invoices<i class="bi bi-chevron-down ms-auto small"></i>
      </a>
      <div class="collapse <?= in_array($current, ['invoices.php', 'customers.php']) ? 'show' : '' ?>" id="sub3">
        <a class="nav-link ps-4 py-1 small rounded text-white text-opacity-75" href="invoices.php">Invoices</a>
        <a class="nav-link ps-4 py-1 small rounded text-white text-opacity-75" href="customers.php">Customers</a>
      </div>

      <!-- Reports -->
      <a class="nav-link rounded d-flex align-items-center px-2 py-1 small text-white text-opacity-75" data-bs-toggle="collapse" href="#sub4">
        <i class="bi bi-bar-chart-fill"></i>Reports<i class="bi bi-chevron-down ms-auto small"></i>
      </a>
      <div class="collapse <?= in_array($current, ['profit_loss.php', 'balance_sheet.php']) ? 'show' : '' ?>" id="sub4">
        <a class="nav-link ps-4 py-1 small rounded text-white text-opacity-75" href="profit_loss.php">Profit &amp; Loss</a>
        <a class="nav-link ps-4 py-1 small rounded text-white text-opacity-75" href="balance_sheet.php">Balance Sheet</a>
      </div>

      <!-- Accounts -->
      <a class="nav-link rounded d-flex align-items-center px-2 py-1 small <?= in_array($current, ['chart_of_accounts.php', 'ledgers.php']) ? 'text-white' : 'text-white text-opacity-75' ?>" data-bs-toggle="collapse" href="#sub5" aria-expanded="<?= in_array($current, ['chart_of_accounts.php', 'ledgers.php']) ? 'true' : 'false' ?>" aria-controls="sub5">
        <i class="bi bi-bank2"></i>Accounts<i class="bi bi-chevron-down ms-auto small"></i>
      </a>
      <div class="collapse <?= in_array($current, ['chart_of_accounts.php', 'ledgers.php']) ? 'show' : '' ?>" id="sub5">
        <a class="nav-link ps-4 py-1 small rounded <?= $current == 'chart_of_accounts.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="chart_of_accounts.php" <?= $current == 'chart_of_accounts.php' ? 'aria-current="page"' : '' ?>>Chart of Accounts</a>
        <a class="nav-link ps-4 py-1 small rounded <?= $current == 'add_account.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="add_account.php" <?= $current == 'add_account.php' ? 'aria-current="page"' : '' ?>>Add Account</a>
        <a class="nav-link ps-4 py-1 small rounded <?= $current == 'ledgers.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="ledgers.php" <?= $current == 'ledgers.php' ? 'aria-current="page"' : '' ?>>Ledgers</a>
      </div>

      <!-- Settings -->
      <a class="nav-link rounded d-flex align-items-center px-2 py-1 small text-white text-opacity-75" data-bs-toggle="collapse" href="#sub6">
        <i class="bi bi-gear-fill"></i>Settings<i class="bi bi-chevron-down ms-auto small"></i>
      </a>
      <div class="collapse <?= in_array($current, ['settings_general.php', 'preferences.php']) ? 'show' : '' ?>" id="sub6">
        <a class="nav-link ps-4 py-1 small rounded text-white text-opacity-75" href="settings_general.php">General</a>
        <a class="nav-link ps-4 py-1 small rounded text-white text-opacity-75" href="preferences.php">Preferences</a>
      </div>

    </nav>

    <div class="text-uppercase small fw-semibold text-white-50 px-2 pt-3 pb-1">Admin Tools</div>
    <nav class="nav flex-column" id="tools">
    </nav>

    <hr class="border-light opacity-25">
    <nav class="nav flex-column mt-auto">
      <a class="nav-link rounded px-2 py-1 small text-white text-opacity-75" href="#"><i class="bi bi-headset me-2"></i>Help &amp; Support</a>
      <a class="nav-link rounded px-2 py-1 small text-white text-opacity-75" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
    </nav>
  </div>
</aside>
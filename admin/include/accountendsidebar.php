<?php $currentPage = basename($_SERVER['PHP_SELF']); $current = $currentPage; ?>
 <aside class="offcanvas-lg offcanvas-start w-100 h-100 min-vh-100 bg-dark text-white" tabindex="-1" id="sidebar">
   <div class="offcanvas-header d-lg-none">
     <h2 class="h5 mb-0">BizLedger</h2>
     <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close menu"></button>
   </div>

   <div class="offcanvas-body d-flex flex-column p-3 p-xl-4">
     <a class="d-flex align-items-center gap-2 text-white text-decoration-none fw-bold fs-5 mb-4" href="dashboard_account.php">
       <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary p-2">
         <i class="bi bi-grid-1x2-fill"></i>
       </span>
       BizLedger
     </a>

    <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3 bg-white bg-opacity-10">
       <?php if (!empty($_SESSION['profile_image'])) { ?>
         <img class="rounded-circle object-fit-cover" src="<?= htmlspecialchars($_SESSION['profile_image']) ?>" alt="<?= htmlspecialchars($_SESSION['name']) ?>" style="width: 2.5rem; height: 2.5rem;">
       <?php } else { ?>
         <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center p-2 fw-semibold" aria-label="Account user initials">RI</div>
       <?php } ?>
       <div class="flex-grow-1">
         <div class="fw-semibold"><?php echo $_SESSION['name']; ?></div>
         <small class="text-white-50">Accountant</small>
       </div>
      <i class="bi bi-chevron-down text-white-50 small"></i>
    </div>

    <div class="text-uppercase small fw-semibold text-white-50 px-3 mb-2">Main menu</div>
    <nav class="nav flex-column gap-1">
       <a class="nav-link rounded <?= $currentPage === 'dashboard_account.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="dashboard_account.php">
         <i class="bi bi-house-door me-2"></i>Dashboard
       </a>
       <div class="d-flex align-items-center">
         <a class="nav-link flex-grow-1 rounded-3 px-3 py-2 <?= in_array($current, ['transactions.php', 'add_transaction.php']) ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="transactions.php">
           <i class="bi bi-arrow-left-right me-2"></i>Transactions
         </a>
         <button class="btn btn-link text-white text-opacity-75 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#sub1" aria-expanded="false" aria-controls="sub1" aria-label="Toggle transaction links">
           <i class="bi bi-chevron-down small"></i>
         </button>
       </div>

      <div class="collapse" id="sub1">
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'transactions.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="transactions.php">All Transactions</a>
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'add_transaction.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="add_transaction.php">Add Transaction</a>
      </div>
      <div class="d-flex align-items-center">
        <a class="nav-link flex-grow-1 rounded-3 px-3 py-2 <?= in_array($current, ['chart_of_accounts.php', 'add_account.php', 'ledgers.php']) ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="chart_of_accounts.php">
          <i class="bi bi-bank2 me-2"></i>Accounts
        </a>
        <button class="btn btn-link text-white text-opacity-75 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#sub5" aria-expanded="false" aria-controls="sub5" aria-label="Toggle account links">
          <i class="bi bi-chevron-down small"></i>
        </button>
      </div>
      <div class="collapse" id="sub5">
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'chart_of_accounts.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="chart_of_accounts.php">Chart of Accounts</a>
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'add_account.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="add_account.php">Add Account</a>
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'ledgers.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="ledgers.php">Ledgers</a>
      </div>

      <div class="d-flex align-items-center">
        <a class="nav-link flex-grow-1 rounded-3 px-3 py-2 <?= in_array($current, ['profit_loss.php', 'balance_sheet.php']) ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="profit_loss.php">
          <i class="bi bi-bar-chart-fill me-2"></i>Reports
        </a>
        <button class="btn btn-link text-white text-opacity-75 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#sub4" aria-expanded="false" aria-controls="sub4" aria-label="Toggle report links">
          <i class="bi bi-chevron-down small"></i>
        </button>
      </div>
      <div class="collapse" id="sub4">
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'profit_loss.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="profit_loss.php">Profit &amp; Loss</a>
        <a class="nav-link ps-4 py-2 small rounded-3 <?= $current == 'balance_sheet.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="balance_sheet.php">Balance Sheet</a>
      </div>

       <a class="nav-link rounded-3 px-3 py-2 d-flex align-items-center <?= $current === 'ledgers.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="ledgers.php">
         <i class="bi bi-journal-text me-2"></i>Ledgers
       </a>
       <a class="nav-link rounded-3 px-3 py-2 d-flex align-items-center <?= $current === 'balance_sheet.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="balance_sheet.php">
         <i class="bi bi-bank me-2"></i>Bank &amp; Cash
       </a>
       <a class="nav-link rounded-3 px-3 py-2 d-flex align-items-center <?= $current === 'invoices.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="invoices.php">
         <i class="bi bi-file-earmark-text me-2"></i>Manage Invoices
       </a>
       <a class="nav-link rounded-3 px-3 py-2 d-flex align-items-center <?= $current === 'sales.php' ? 'active bg-primary text-white' : 'text-white text-opacity-75' ?>" href="sales.php">
         <i class="bi bi-pie-chart me-2"></i>Sales
       </a>
       <span class="nav-link rounded-3 px-3 py-2 d-flex align-items-center text-white text-opacity-50" aria-disabled="true" title="Settings page is not available">
         <i class="bi bi-gear me-2"></i>Settings
       </span>
    </nav>

    <div class="text-uppercase small fw-semibold text-white-50 px-3 mt-4 mb-2">Other</div>
    <nav class="nav flex-column gap-1">
       <span class="nav-link rounded text-white text-opacity-50" aria-disabled="true" title="Notifications page is not available">
         <i class="bi bi-bell me-2"></i>Notifications
         <span class="badge rounded-pill bg-danger ms-auto">3</span>
       </span>
       <span class="nav-link rounded text-white text-opacity-50" aria-disabled="true" title="Help page is not available">
         <i class="bi bi-question-circle me-2"></i>Help Center
       </span>
       <a class="nav-link rounded text-white text-opacity-75" href="logout.php">
         <i class="bi bi-box-arrow-left me-2"></i>Logout
       </a>
    </nav>

     <div class="card bg-white bg-opacity-10 text-white border-0 text-center p-3 mt-auto rounded-3">
       <i class="bi bi-shield-check fs-4 mb-2"></i>
       <b>BizLedger</b>
       <span class="small text-white-50">Business Account Management System</span>
    </div>
     <div class="small text-white-50 pt-3">© 2026 BizLedger</div>
   </div>
 </aside>
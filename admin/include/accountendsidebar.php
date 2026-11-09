 <aside class="offcanvas-lg offcanvas-start w-100 h-100 min-vh-100 bg-dark text-white" tabindex="-1" id="sidebar">
    <div class="offcanvas-header d-lg-none"><h2 class="h5 mb-0">BizLedger</h2><button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close menu"></button></div>
    <div class="offcanvas-body d-flex flex-column p-3 p-xl-4">
    <a class="d-flex align-items-center gap-2 text-white text-decoration-none fw-bold fs-5 mb-4" href="dashboard_account.php"><span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary p-2"><i class="bi bi-grid-1x2-fill"></i></span>BizLedger</a>
    <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3 bg-white bg-opacity-10">
      <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center p-2 fw-semibold">RI</div>
      <div class="flex-grow-1"><div class="fw-semibold"><?= htmlspecialchars($_SESSION['name'] ?? 'Accountant') ?></div><small class="text-white-50">Accountant</small></div>
      <i class="bi bi-chevron-down text-white-50 small"></i>
    </div>
    <div class="text-uppercase small fw-semibold text-white-50 px-3 mb-2">Main menu</div>
    <nav class="nav flex-column gap-1">
      <a class="nav-link rounded active bg-primary text-white" href="dashboard_account.php"><i class="bi bi-house-door me-2"></i>Dashboard</a>
      <span class="nav-link rounded text-white text-opacity-75"><i class="bi bi-arrow-left-right me-2"></i>Transactions</span>
      <span class="nav-link rounded text-white text-opacity-75"><i class="bi bi-wallet2 me-2"></i>Accounts</span>
      <span class="nav-link rounded text-white text-opacity-75"><i class="bi bi-bar-chart-line me-2"></i>Reports</span>
      <span class="nav-link rounded text-white text-opacity-75"><i class="bi bi-journal-text me-2"></i>Ledgers</span>
      <span class="nav-link rounded text-white text-opacity-75"><i class="bi bi-bank me-2"></i>Bank &amp; Cash</span>
      <span class="nav-link rounded text-white text-opacity-75"><i class="bi bi-file-earmark-text me-2"></i>Manage Invoices</span>
      <span class="nav-link rounded text-white text-opacity-75"><i class="bi bi-pie-chart me-2"></i>Budgets</span>
      <span class="nav-link rounded text-white text-opacity-75"><i class="bi bi-gear me-2"></i>Settings</span>
    </nav>
    <div class="text-uppercase small fw-semibold text-white-50 px-3 mt-4 mb-2">Other</div>
    <nav class="nav flex-column gap-1">
      <span class="nav-link rounded text-white text-opacity-75"><i class="bi bi-bell me-2"></i>Notifications<span class="badge rounded-pill bg-danger ms-auto">3</span></span>
      <span class="nav-link rounded text-white text-opacity-75"><i class="bi bi-question-circle me-2"></i>Help Center</span>
      <a class="nav-link rounded text-white text-opacity-75" href="logout.php"><i class="bi bi-box-arrow-left me-2"></i>Logout</a>
    </nav>
    <div class="card bg-white bg-opacity-10 text-white border-0 text-center p-3 mt-auto rounded-3"><i class="bi bi-shield-check fs-4 mb-2"></i><b>BizLedger</b><span class="small text-white-50">Business Account Management System</span></div>
    <div class="small text-white-50 pt-3">© 2026 BizLedger</div>
    </div>
  </aside>
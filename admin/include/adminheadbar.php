<header class="navbar bg-white border-bottom px-3 px-md-4 py-2 sticky-top">
  <button class="btn btn-outline-secondary d-lg-none me-2" id="toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-label="Open menu"><i class="bi bi-list fs-4"></i></button>
  <div class="input-group d-none d-sm-flex" style="max-width: 28rem">
    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
    <input id="q" class="form-control bg-light border-start-0" type="search" placeholder="Search anything...">
  </div>

  <div class="ms-auto d-flex align-items-center gap-3">
    <button class="btn btn-light position-relative" type="button" aria-label="Notifications"><i class="bi bi-bell fs-5"></i><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span></button>

    <!-- User dropdown -->
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-reset"
         id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center px-2 py-2">RI</div>
        <div class="d-none d-md-block lh-sm">
          <div class="fw-semibold"><?php echo $_SESSION['name']; ?></div>
          <small class="text-muted"><?php echo $_SESSION['role']; ?></small>
        </div>
        <i class="bi bi-chevron-down small text-muted"></i>
      </a>

      <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="userMenu">
        <li class="px-3 py-2">
          <div class="fw-semibold"><?php echo $_SESSION['name']; ?></div>
          <small class="text-muted"><?php echo $_SESSION['role']; ?></small>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>My Profile</a></li>
        <li><a class="dropdown-item" href="settings.php"><i class="bi bi-gear me-2"></i>Settings</a></li>
        <li><a class="dropdown-item" href="help.php"><i class="bi bi-question-circle me-2"></i>Help</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
      </ul>
    </div>
  </div>
</header>
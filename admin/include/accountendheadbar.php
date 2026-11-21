<header class="navbar sticky-top bg-white border-bottom shadow-sm px-3 px-md-4 py-2">
  <div class="d-flex align-items-center gap-2">
    <button class="btn btn-light border d-lg-none me-2" id="toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-label="Open menu"><i class="bi bi-list fs-4"></i></button>
    <div class="d-none d-md-flex align-items-center gap-2 text-primary fw-semibold">
      <i class="bi bi-calculator fs-5"></i>
      <span>Accounting</span>
    </div>
  </div>

  <div class="input-group d-none d-sm-flex ms-auto me-3" style="max-width: 28rem">
    <span class="input-group-text bg-body-tertiary border-end-0 rounded-start-pill"><i class="bi bi-search text-secondary"></i></span>
    <input id="q" class="form-control bg-body-tertiary border-start-0 rounded-end-pill" type="search" placeholder="Search anything..." aria-label="Search">
  </div>

  <div class="ms-auto d-flex align-items-center gap-3">
    <button class="btn btn-light border position-relative rounded-circle" type="button" aria-label="Notifications"><i class="bi bi-bell"></i><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span></button>

    <div class="dropdown">
      <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-reset" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
        <?php if (!empty($_SESSION['profile_image'])) { ?>
          <img class="rounded-circle object-fit-cover" src="<?= htmlspecialchars($_SESSION['profile_image']) ?>" alt="<?= htmlspecialchars($_SESSION['name']) ?>" style="width: 2.5rem; height: 2.5rem;">
        <?php } else { ?>
          <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-semibold" style="width: 2.5rem; height: 2.5rem;">RI</div>
        <?php } ?>
        <div class="d-none d-md-block lh-sm">
          <div class="fw-semibold"><?php echo $_SESSION['name'] ; ?></div>
          <small class="text-muted"><?php echo $_SESSION['role'] ; ?></small>
        </div>
        <i class="bi bi-chevron-down small text-muted"></i>
      </a>

      <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2 rounded-4 p-2" aria-labelledby="userMenu">
        <li class="px-3 py-2">
          <div class="fw-semibold"><?php echo $_SESSION['name'] ; ?></div>
          <small class="text-muted"><?php echo $_SESSION['role'] ; ?></small>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item rounded-3" href="profile.php"><i class="bi bi-person me-2"></i>My Profile</a></li>
        <li><a class="dropdown-item rounded-3" href="settings.php"><i class="bi bi-gear me-2"></i>Settings</a></li>
        <li><a class="dropdown-item rounded-3" href="help.php"><i class="bi bi-question-circle me-2"></i>Help</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger rounded-3" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
      </ul>
    </div>
  </div>
</header>
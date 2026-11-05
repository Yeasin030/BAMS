<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BizLedger — Business Account Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="app-shell">
        <!-- Sidebar -->
        <?php include 'include/sidebar.php'; ?>

        <main class="main">
            
          <!-- Headbar -->

            <?php include 'include/headbar.php'; ?>

            <!-- Dashboard Content -->

            <div class="content">
                <section class="section active" id="page-dashboard">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                        <div>
                            <h1 class="page-title mb-1">Good morning, Yeasin 👋</h1>
                            <div class="sub-title">Here's what's happening with your business today.</div>
                        </div>
                        <div class="d-flex gap-2"><button class="btn btn-light" id="dateRange"><i
                                    class="bi bi-calendar3 me-2"></i>This month</button><button class="btn btn-primary"
                                data-bs-toggle="modal" data-bs-target="#transactionModal"><i
                                    class="bi bi-plus-lg me-1"></i>New transaction</button></div>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-xl-3">
                            <div class="card metric-card">
                                <div class="d-flex justify-content-between">
                                    <div class="metric-label">TOTAL REVENUE</div>
                                    <div class="metric-icon bg-primary-subtle text-primary"><i
                                            class="bi bi-currency-exchange"></i></div>
                                </div>
                                <div class="metric-value">৳ 8,45,250</div>
                                <div class="tiny-change"><i class="bi bi-arrow-up-right"></i> 12.8% <span
                                        class="text-secondary fw-normal">vs last month</span></div>
                            </div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <div class="card metric-card">
                                <div class="d-flex justify-content-between">
                                    <div class="metric-label">TOTAL EXPENSES</div>
                                    <div class="metric-icon bg-danger-subtle text-danger"><i class="bi bi-wallet"></i>
                                    </div>
                                </div>
                                <div class="metric-value">৳ 3,12,480</div>
                                <div class="tiny-change text-danger"><i class="bi bi-arrow-up-right"></i> 4.3% <span
                                        class="text-secondary fw-normal">vs last month</span></div>
                            </div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <div class="card metric-card">
                                <div class="d-flex justify-content-between">
                                    <div class="metric-label">NET PROFIT</div>
                                    <div class="metric-icon bg-success-subtle text-success"><i
                                            class="bi bi-piggy-bank"></i></div>
                                </div>
                                <div class="metric-value">৳ 5,32,770</div>
                                <div class="tiny-change"><i class="bi bi-arrow-up-right"></i> 18.6% <span
                                        class="text-secondary fw-normal">vs last month</span></div>
                            </div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <div class="card metric-card">
                                <div class="d-flex justify-content-between">
                                    <div class="metric-label">CASH & BANK</div>
                                    <div class="metric-icon bg-warning-subtle text-warning"><i class="bi bi-bank"></i>
                                    </div>
                                </div>
                                <div class="metric-value">৳ 12,40,600</div>
                                <div class="metric-label">Across 3 accounts</div>
                            </div>
                        </div>
                    </div>
                    <h1 class="section-title mb-3">Welcome to the user dashbord</h1>


                </section>

                <section class="section" id="page-generic">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                        <div>
                            <h1 class="page-title mb-1" id="sectionTitle">Customers</h1>
                            <div class="sub-title" id="sectionDesc">Manage your business records.</div>
                        </div><button class="btn btn-primary" id="addRecordBtn"><i class="bi bi-plus-lg me-1"></i><span
                                id="addRecordLabel">Add new</span></button>
                    </div>
                    <div class="card">
                        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div class="fw-bold" id="tableHeading">All records <span class="text-secondary fw-normal"
                                    id="recordCount"></span></div>
                            <div class="d-flex gap-2">
                                <div class="search-box"><i class="bi bi-search"></i><input
                                        class="form-control form-control-sm" id="tableSearch"
                                        placeholder="Search records..."></div><button class="btn btn-light btn-sm"><i
                                        class="bi bi-funnel me-1"></i>Filter</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead id="genericHead"></thead>
                                <tbody id="genericBody"></tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 border-top"><span
                                class="text-secondary small" id="tableFoot">Showing sample records</span>
                            <div class="btn-group btn-group-sm"><button class="btn btn-light"
                                    disabled>Previous</button><button class="btn btn-primary">1</button><button
                                    class="btn btn-light" disabled>Next</button></div>
                        </div>
                    </div>
                    <div class="alert alert-info mt-3 small mb-0"><i class="bi bi-info-circle me-2"></i>UI preview only
                        — records shown here are sample data and are not saved to a database.</div>
                </section>
            </div>
        </main>
    </div>

   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

</body>

</html>
<?php
session_start();
include_once 'assets/db/bdconfig.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit;
}

if (($_SESSION['role'] ?? '') != 'admin') {
    header("Location: unauthorize.php");
    exit;
}

$company_id = (int) ($_SESSION['company_id'] ?? 1);
$error = '';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $sale_id = (int) $_POST['sale_id'];
    $account_id = (int) $_POST['account_id'];
    $amount = (float) $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $payment_method = $_POST['payment_method'];
    $reference_number = $_POST['reference_number'];

    $invoice = $conn -> query("SELECT s.sale_id, s.customer_id, s.total_amount,
    COALESCE((SELECT SUM(pa.allocated_amount) FROM payment_allocations pa WHERE pa.invoice_type = 'SALE' AND pa.invoice_id = s.sale_id), 0) AS paid_amount
    FROM sales s WHERE s.sale_id = '$sale_id' AND s.company_id = '$company_id'") -> fetch_assoc();

    if(!$invoice){
        $error = "Invoice not found.";
    } elseif($amount <= 0){
        $error = "Payment amount must be more than zero.";
    } elseif($amount > ($invoice['total_amount'] - $invoice['paid_amount'])){
        $error = "Payment amount cannot be more than the invoice balance.";
    } else {

        $customer_id = (int) $invoice['customer_id'];

        $conn -> query("INSERT INTO payments (company_id, party_type, customer_id, account_id, payment_type, amount, payment_date, payment_method, reference_number)
        VALUES ('$company_id', 'CUSTOMER', '$customer_id', '$account_id', 'RECEIPT', '$amount', '$payment_date', '$payment_method', '$reference_number')");

        $payment_id = $conn -> insert_id;

        if($payment_id){
            $conn -> query("INSERT INTO payment_allocations (payment_id, invoice_type, invoice_id, allocated_amount)
            VALUES ('$payment_id', 'SALE', '$sale_id', '$amount')");

            $new_paid = $invoice['paid_amount'] + $amount;
            if($new_paid >= $invoice['total_amount']){
                $new_status = 'PAID';
            } else {
                $new_status = 'PARTIAL';
            }

            $conn -> query("UPDATE sales SET status = '$new_status' WHERE sale_id = '$sale_id' AND company_id = '$company_id'");

            header('Location: ../admin/invoices.php');
            exit;
        }

    }

}

$invoice_result = $conn -> query("SELECT s.*, c.customer_name,
COALESCE((SELECT SUM(pa.allocated_amount) FROM payment_allocations pa WHERE pa.invoice_type = 'SALE' AND pa.invoice_id = s.sale_id), 0) AS paid_amount
FROM sales s LEFT JOIN customers c ON c.customer_id = s.customer_id
WHERE s.company_id = '$company_id' ORDER BY s.sale_date DESC, s.sale_id DESC");

$invoices = [];
$total_count = 0;
$paid_count = 0;
$paid_amount_total = 0;
$awaiting_count = 0;
$awaiting_amount_total = 0;
$draft_count = 0;
$draft_amount_total = 0;

while($row = $invoice_result -> fetch_assoc()){
    $invoices[] = $row;
    $total_count++;

    if($row['status'] == 'PAID'){
        $paid_count++;
        $paid_amount_total += $row['total_amount'];
    } elseif($row['status'] == 'POSTED' || $row['status'] == 'PARTIAL'){
        $awaiting_count++;
        $awaiting_amount_total += $row['total_amount'] - $row['paid_amount'];
    } elseif($row['status'] == 'DRAFT'){
        $draft_count++;
        $draft_amount_total += $row['total_amount'];
    }
}

$account_list = $conn -> query("SELECT account_id, account_code, account_name FROM accounts WHERE company_id = '$company_id' AND is_active = 1 ORDER BY account_name ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoices | BizLedger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/dashboard-shared.css" rel="stylesheet">
    <link href="assets/css/inventory.css" rel="stylesheet">
    <link href="assets/css/sales-invoices.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary dashboard-page inventory-page commerce-page">
    <div class="container-fluid px-0">
        <div class="row min-vh-100">
            <div class="col-lg-3 col-xl-2 p-0 bg-dark">
                <?php include 'include/adminsidebar.php'; ?>
            </div>
            <main class="col-12 col-lg-9 col-xl-10 p-0">
                <?php include 'include/adminheadbar.php'; ?>
                <div class="p-3 p-md-4 p-xl-5">
                    <nav class="inventory-breadcrumb mb-3" aria-label="Breadcrumb">
                        <a href="dashboard_admin.php" class="text-decoration-none">Dashboard</a>
                        <i class="bi bi-chevron-right mx-2" aria-hidden="true"></i>
                        <a href="sales.php" class="text-decoration-none">Sales</a>
                        <i class="bi bi-chevron-right mx-2" aria-hidden="true"></i>
                        <span aria-current="page">Invoices</span>
                    </nav>

                    <?php if($error){ ?>
                        <div class="alert alert-danger rounded-3 mb-4"><?= htmlspecialchars($error) ?></div>
                    <?php } ?>

                    <section class="inventory-hero commerce-hero invoice-hero rounded-4 shadow-sm text-white p-4 p-lg-5 mb-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                            <div>
                                <span class="badge rounded-pill bg-white bg-opacity-25 text-white px-3 py-2 mb-3"><i class="bi bi-file-earmark-text me-1" aria-hidden="true"></i> Billing workspace</span>
                                <h1 class="h2 fw-bold mb-2">Invoices</h1>
                                <p class="mb-0 text-white-50">Keep an eye on payments and outstanding balances.</p>
                            </div>
                            <a class="btn btn-light text-primary fw-semibold px-3 py-2 text-nowrap" href="sales.php">
                                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Create invoice
                            </a>
                        </div>
                    </section>

                    <section class="row g-3 mb-4" aria-label="Invoice summary">
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-primary-subtle text-primary"><i class="bi bi-files" aria-hidden="true"></i></span><div><div class="small text-secondary">Total invoices</div><div class="h4 fw-bold mb-0"><?= $total_count ?></div><div class="small text-secondary mt-1">All time</div></div></div></article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-success-subtle text-success"><i class="bi bi-check-circle" aria-hidden="true"></i></span><div><div class="small text-secondary">Paid</div><div class="h4 fw-bold text-success mb-0">$<?= number_format($paid_amount_total, 2) ?></div><div class="small text-secondary mt-1"><?= $paid_count ?> invoices</div></div></div></article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-warning-subtle text-warning-emphasis"><i class="bi bi-clock-history" aria-hidden="true"></i></span><div><div class="small text-secondary">Awaiting payment</div><div class="h4 fw-bold text-warning-emphasis mb-0">$<?= number_format($awaiting_amount_total, 2) ?></div><div class="small text-secondary mt-1"><?= $awaiting_count ?> invoices</div></div></div></article>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <article class="card inventory-stat rounded-4 h-100"><div class="card-body d-flex align-items-center gap-3 p-4"><span class="inventory-stat-icon rounded-3 bg-secondary-subtle text-secondary-emphasis"><i class="bi bi-pencil-square" aria-hidden="true"></i></span><div><div class="small text-secondary">Draft</div><div class="h4 fw-bold mb-0">$<?= number_format($draft_amount_total, 2) ?></div><div class="small text-secondary mt-1"><?= $draft_count ?> invoices</div></div></div></article>
                        </div>
                    </section>

                    <section class="card inventory-panel rounded-4 overflow-hidden" aria-labelledby="invoice-list-title">
                        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3 p-3 p-md-4 border-bottom">
                            <div><h2 class="h5 fw-bold mb-1" id="invoice-list-title">Invoice register</h2><p class="small text-secondary mb-0">Search invoices and check each payment balance</p></div>
                            <div class="d-flex flex-column flex-sm-row gap-2 inventory-filters">
                                <label class="input-group"><span class="input-group-text bg-white"><i class="bi bi-search" aria-hidden="true"></i></span><input class="form-control" type="search" placeholder="Invoice or customer" aria-label="Search invoices"></label>
                                <select class="form-select" aria-label="Filter invoices by status"><option selected>All statuses</option><option>Paid</option><option>Partial</option><option>Posted</option><option>Draft</option><option>Cancelled</option></select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle inventory-table commerce-table invoice-table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-4 py-3">Invoice</th>
                                        <th scope="col" class="py-3">Customer</th>
                                        <th scope="col" class="py-3">Issue date</th>
                                        <th scope="col" class="py-3 text-end">Total</th>
                                        <th scope="col" class="py-3 text-end">Balance</th>
                                        <th scope="col" class="py-3">Status</th>
                                        <th scope="col" class="pe-4 py-3 text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($invoices as $row){
                                        $balance = $row['total_amount'] - $row['paid_amount'];

                                        if($row['status'] == 'PAID'){
                                            $badge = 'bg-success-subtle text-success-emphasis'; $text = 'Paid'; $balance_class = 'text-success';
                                        } elseif($row['status'] == 'PARTIAL'){
                                            $badge = 'bg-warning-subtle text-warning-emphasis'; $text = 'Partial'; $balance_class = 'text-warning-emphasis';
                                        } elseif($row['status'] == 'POSTED'){
                                            $badge = 'bg-primary-subtle text-primary-emphasis'; $text = 'Posted'; $balance_class = 'text-danger';
                                        } elseif($row['status'] == 'CANCEL'){
                                            $badge = 'bg-danger-subtle text-danger-emphasis'; $text = 'Cancelled'; $balance_class = 'text-secondary';
                                        } else {
                                            $badge = 'bg-secondary-subtle text-secondary-emphasis'; $text = 'Draft'; $balance_class = 'text-secondary';
                                        }
                                    ?>
                                    <tr>
                                        <td class="ps-4"><div class="fw-semibold"><?= htmlspecialchars($row['invoice_number']) ?></div><div class="small text-secondary">Sale #<?= $row['sale_id'] ?></div></td>
                                        <td><div class="customer-cell"><span class="customer-avatar bg-primary-subtle text-primary"><?= htmlspecialchars(strtoupper(substr($row['customer_name'] ?? '?', 0, 2))) ?></span><span class="fw-medium"><?= htmlspecialchars($row['customer_name'] ?? 'Unknown') ?></span></div></td>
                                        <td class="text-secondary text-nowrap"><?= date('M d, Y', strtotime($row['sale_date'])) ?></td>
                                        <td class="text-end fw-semibold">$<?= number_format($row['total_amount'], 2) ?></td>
                                        <td class="text-end fw-semibold <?= $balance_class ?>">$<?= number_format($balance, 2) ?></td>
                                        <td><span class="badge rounded-pill <?= $badge ?>"><?= $text ?></span></td>
                                        <td class="pe-4 text-end">
                                            <?php if(($row['status'] == 'POSTED' || $row['status'] == 'PARTIAL') && $balance > 0){ ?>
                                                <button class="btn btn-sm btn-outline-primary text-nowrap receive-payment-btn" type="button" data-bs-toggle="modal" data-bs-target="#receivePaymentModal" data-sale-id="<?= $row['sale_id'] ?>" data-invoice="<?= htmlspecialchars($row['invoice_number']) ?>" data-balance="<?= number_format($balance, 2, '.', '') ?>">Receive payment</button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 p-3 p-md-4 border-top">
                            <span class="small text-secondary">Showing <?= $total_count ?> invoices</span>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="receivePaymentModal" tabindex="-1" aria-labelledby="receivePaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-bottom-0 px-4 pt-4"><div><h2 class="modal-title h5 fw-bold" id="receivePaymentModalLabel">Receive payment</h2><p class="small text-secondary mb-0">Record a payment received for this invoice.</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body px-4 pb-4">
                    <form class="commerce-form" method="post">
                        <input type="hidden" name="sale_id" id="paymentSaleId">
                        <div class="row g-3">
                            <div class="col-sm-6"><label class="form-label" for="paymentInvoice">Invoice</label><input class="form-control" id="paymentInvoice" type="text" readonly></div>
                            <div class="col-sm-6"><label class="form-label" for="paymentAmount">Amount</label><div class="input-group"><span class="input-group-text">$</span><input class="form-control" id="paymentAmount" name="amount" type="number" min="0.01" step="0.01" required></div><div class="form-text" id="paymentBalanceHelp"></div></div>
                            <div class="col-sm-6"><label class="form-label" for="paymentDate">Payment date</label><input class="form-control" id="paymentDate" name="payment_date" type="date" value="<?= date('Y-m-d') ?>" required></div>
                            <div class="col-sm-6"><label class="form-label" for="paymentMethod">Method</label>
                                <select class="form-select" id="paymentMethod" name="payment_method" required>
                                    <option value="Cash" selected>Cash</option>
                                    <option value="Bank transfer">Bank transfer</option>
                                    <option value="Card">Card</option>
                                    <option value="Mobile banking">Mobile banking</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-12"><label class="form-label" for="paymentAccount">Deposit to</label>
                                <select class="form-select" id="paymentAccount" name="account_id" required>
                                    <option value="" selected disabled>Select cash or bank account</option>
                                    <?php while($a = $account_list -> fetch_assoc()){ ?>
                                        <option value="<?= $a['account_id'] ?>"><?= htmlspecialchars($a['account_name']) ?> · <?= htmlspecialchars($a['account_code']) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12"><label class="form-label" for="paymentReference">Reference number <span class="text-secondary fw-normal">(optional)</span></label><input class="form-control" id="paymentReference" name="reference_number" type="text" maxlength="100" placeholder="e.g. transaction ID"></div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary" type="submit" name="submit"><i class="bi bi-check2 me-1" aria-hidden="true"></i>Save payment</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.receive-payment-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('paymentSaleId').value = btn.dataset.saleId;
                document.getElementById('paymentInvoice').value = btn.dataset.invoice;
                document.getElementById('paymentAmount').value = btn.dataset.balance;
                document.getElementById('paymentAmount').max = btn.dataset.balance;
                document.getElementById('paymentBalanceHelp').textContent = 'Balance due: $' + btn.dataset.balance;
            });
        });
    </script>
</body>

</html>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BizLedger — Business Account Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="app-shell">
    // Sidebar
    <?php include 'assets/include/sidebar.php'; ?>

  <main class="main">
    <header class="topbar">
      <div class="d-flex align-items-center gap-3"><button class="btn btn-light mobile-menu" id="menuBtn"><i class="bi bi-list fs-5"></i></button><div><div class="fw-bold" id="topTitle">Dashboard</div><div class="text-secondary small hide-mobile">Welcome back! Here's your business overview.</div></div></div>
      <div class="d-flex align-items-center gap-3"><button class="btn btn-light position-relative"><i class="bi bi-bell"></i><span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span></button><div class="d-flex align-items-center gap-2"><div class="avatar mb-0">YA</div><div class="hide-mobile"><div class="fw-bold small">Yeasin Arafat</div><div class="text-secondary" style="font-size:10px">Administrator</div></div><i class="bi bi-chevron-down small text-secondary"></i></div></div>
    </header>

    <div class="content">
      <section class="section active" id="page-dashboard">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4"><div><h1 class="page-title mb-1">Good morning, Yeasin 👋</h1><div class="sub-title">Here's what's happening with your business today.</div></div><div class="d-flex gap-2"><button class="btn btn-light" id="dateRange"><i class="bi bi-calendar3 me-2"></i>This month</button><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transactionModal"><i class="bi bi-plus-lg me-1"></i>New transaction</button></div></div>
        <div class="row g-3 mb-4">
          <div class="col-6 col-xl-3"><div class="card metric-card"><div class="d-flex justify-content-between"><div class="metric-label">TOTAL REVENUE</div><div class="metric-icon bg-primary-subtle text-primary"><i class="bi bi-currency-exchange"></i></div></div><div class="metric-value">৳ 8,45,250</div><div class="tiny-change"><i class="bi bi-arrow-up-right"></i> 12.8% <span class="text-secondary fw-normal">vs last month</span></div></div></div>
          <div class="col-6 col-xl-3"><div class="card metric-card"><div class="d-flex justify-content-between"><div class="metric-label">TOTAL EXPENSES</div><div class="metric-icon bg-danger-subtle text-danger"><i class="bi bi-wallet"></i></div></div><div class="metric-value">৳ 3,12,480</div><div class="tiny-change text-danger"><i class="bi bi-arrow-up-right"></i> 4.3% <span class="text-secondary fw-normal">vs last month</span></div></div></div>
          <div class="col-6 col-xl-3"><div class="card metric-card"><div class="d-flex justify-content-between"><div class="metric-label">NET PROFIT</div><div class="metric-icon bg-success-subtle text-success"><i class="bi bi-piggy-bank"></i></div></div><div class="metric-value">৳ 5,32,770</div><div class="tiny-change"><i class="bi bi-arrow-up-right"></i> 18.6% <span class="text-secondary fw-normal">vs last month</span></div></div></div>
          <div class="col-6 col-xl-3"><div class="card metric-card"><div class="d-flex justify-content-between"><div class="metric-label">CASH & BANK</div><div class="metric-icon bg-warning-subtle text-warning"><i class="bi bi-bank"></i></div></div><div class="metric-value">৳ 12,40,600</div><div class="metric-label">Across 3 accounts</div></div></div>
        </div>
        <div class="row g-3 mb-4">
          <div class="col-xl-8"><div class="card h-100"><div class="card-header d-flex justify-content-between align-items-center"><div><div class="fw-bold">Revenue & Expenses</div><div class="sub-title">Monthly financial performance</div></div><select class="form-select form-select-sm" style="width:120px"><option>Last 6 months</option><option>This year</option></select></div><div class="card-body"><canvas id="overviewChart" height="105"></canvas></div></div></div>
          <div class="col-xl-4"><div class="card h-100"><div class="card-header"><div class="fw-bold">Income breakdown</div><div class="sub-title">Revenue by source</div></div><div class="card-body"><canvas id="incomeChart" height="175"></canvas><div class="mt-3"><div class="d-flex justify-content-between small mb-2"><span><i class="bi bi-circle-fill text-primary me-2" style="font-size:8px"></i>Product sales</span><b>৳ 5,90,000</b></div><div class="d-flex justify-content-between small mb-2"><span><i class="bi bi-circle-fill text-success me-2" style="font-size:8px"></i>Services</span><b>৳ 1,65,250</b></div><div class="d-flex justify-content-between small"><span><i class="bi bi-circle-fill text-warning me-2" style="font-size:8px"></i>Other income</span><b>৳ 90,000</b></div></div></div></div></div>
        </div>
        <div class="row g-3 mb-4">
          <div class="col-xl-8"><div class="card"><div class="card-header d-flex justify-content-between align-items-center"><div class="fw-bold">Recent transactions</div><a class="small text-primary text-decoration-none fw-bold" data-page="journal">View all</a></div><div class="table-responsive"><table class="table"><thead><tr><th>Transaction</th><th>Date</th><th>Category</th><th>Amount</th><th>Status</th></tr></thead><tbody id="recentRows"></tbody></table></div></div></div>
          <div class="col-xl-4"><div class="card h-100"><div class="card-header"><div class="fw-bold">Quick actions</div><div class="sub-title">Common tasks</div></div><div class="card-body"><div class="row g-2"><div class="col-6"><button class="quick-action" data-bs-toggle="modal" data-bs-target="#transactionModal"><i class="bi bi-plus-circle"></i><strong>Add income</strong></button></div><div class="col-6"><button class="quick-action" data-bs-toggle="modal" data-bs-target="#transactionModal"><i class="bi bi-dash-circle"></i><strong>Add expense</strong></button></div><div class="col-6"><button class="quick-action" data-page="sales"><i class="bi bi-receipt"></i><strong>Create invoice</strong></button></div><div class="col-6"><button class="quick-action" data-page="reports"><i class="bi bi-file-earmark-bar-graph"></i><strong>View reports</strong></button></div></div></div></div></div>
        </div>
      </section>

      <section class="section" id="page-generic">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4"><div><h1 class="page-title mb-1" id="sectionTitle">Customers</h1><div class="sub-title" id="sectionDesc">Manage your business records.</div></div><button class="btn btn-primary" id="addRecordBtn"><i class="bi bi-plus-lg me-1"></i><span id="addRecordLabel">Add new</span></button></div>
        <div class="card"><div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2"><div class="fw-bold" id="tableHeading">All records <span class="text-secondary fw-normal" id="recordCount"></span></div><div class="d-flex gap-2"><div class="search-box"><i class="bi bi-search"></i><input class="form-control form-control-sm" id="tableSearch" placeholder="Search records..."></div><button class="btn btn-light btn-sm"><i class="bi bi-funnel me-1"></i>Filter</button></div></div><div class="table-responsive"><table class="table"><thead id="genericHead"></thead><tbody id="genericBody"></tbody></table></div><div class="d-flex justify-content-between align-items-center p-3 border-top"><span class="text-secondary small" id="tableFoot">Showing sample records</span><div class="btn-group btn-group-sm"><button class="btn btn-light" disabled>Previous</button><button class="btn btn-primary">1</button><button class="btn btn-light" disabled>Next</button></div></div></div>
        <div class="alert alert-info mt-3 small mb-0"><i class="bi bi-info-circle me-2"></i>UI preview only — records shown here are sample data and are not saved to a database.</div>
      </section>
    </div>
  </main>
</div>

<div class="modal fade" id="transactionModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><div><h5 class="modal-title fw-bold">New transaction</h5><div class="sub-title">Enter transaction details (demo only)</div></div><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form id="transactionForm"><div class="mb-3"><label class="form-label fw-semibold">Transaction type</label><select class="form-select" id="txType"><option>Income</option><option>Expense</option><option>Sales</option><option>Purchase</option><option>Payment</option></select></div><div class="row g-3"><div class="col-6"><label class="form-label fw-semibold">Date</label><input type="date" class="form-control" id="txDate" required></div><div class="col-6"><label class="form-label fw-semibold">Amount (BDT)</label><input type="number" min="0.01" step="0.01" class="form-control" id="txAmount" placeholder="0.00" required></div></div><div class="mt-3"><label class="form-label fw-semibold">Description</label><input class="form-control" id="txDesc" placeholder="e.g. Office supplies"></div><div class="mt-3"><label class="form-label fw-semibold">Account</label><select class="form-select"><option>Cash in Hand</option><option>Bank Account</option><option>Accounts Receivable</option></select></div></form></div><div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary" form="transactionForm">Save transaction</button></div></div></div></div>
<div class="toast-container position-fixed bottom-0 end-0 p-3"><div id="demoToast" class="toast border-0 shadow" role="alert"><div class="d-flex"><div class="toast-body"><i class="bi bi-check-circle-fill text-success me-2"></i><span id="toastMessage">Demo action completed.</span></div><button class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button></div></div></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
const sampleTransactions = [
 {name:'INV-2026-0084',party:'Rahman Traders',date:'04 Nov 2026',category:'Sales',amount:'৳ 48,500',status:'Paid',type:'Sales'},
 {name:'EXP-2026-0031',party:'Office rent',date:'03 Nov 2026',category:'Expense',amount:'৳ 22,000',status:'Paid',type:'Expense'},
 {name:'INC-2026-0028',party:'Consulting service',date:'02 Nov 2026',category:'Income',amount:'৳ 35,000',status:'Posted',type:'Income'},
 {name:'PUR-2026-0042',party:'Global Suppliers',date:'01 Nov 2026',category:'Purchase',amount:'৳ 67,200',status:'Pending',type:'Purchase'}
];
const datasets = {
 accounts:{title:'Chart of Accounts',desc:'Organize assets, liabilities, equity, revenue and expenses.',add:'Add account',cols:['Account code','Account name','Type','Category','Balance','Status'],rows:[
 ['1001','Cash in Hand','Asset','Current Assets','৳ 85,000','Active'],['1002','Bank Account','Asset','Current Assets','৳ 11,55,600','Active'],['1101','Accounts Receivable','Asset','Current Assets','৳ 2,45,000','Active'],['2001','Accounts Payable','Liability','Current Liabilities','৳ 1,20,000','Active'],['4001','Sales Revenue','Revenue','Operating Revenue','৳ 5,90,000','Active'],['5001','Office Rent','Expense','Operating Expenses','৳ 66,000','Active']]},
 journal:{title:'Journal Entries',desc:'Review and manage double-entry accounting records.',add:'Create journal',cols:['Journal no.','Date','Description','Debit','Credit','Status'],rows:[['JV-00045','04 Nov 2026','Sales invoice posting','৳ 48,500','৳ 48,500','Posted'],['JV-00044','03 Nov 2026','Office rent payment','৳ 22,000','৳ 22,000','Posted'],['JV-00043','02 Nov 2026','Consulting income','৳ 35,000','৳ 35,000','Draft']]},
 sales:{title:'Sales invoices',desc:'Create invoices and track customer payments.',add:'Create invoice',cols:['Invoice no.','Customer','Date','Total amount','Paid','Status'],rows:[['INV-0084','Rahman Traders','04 Nov 2026','৳ 48,500','৳ 48,500','Paid'],['INV-0083','Nahar Enterprise','02 Nov 2026','৳ 82,000','৳ 40,000','Partial'],['INV-0082','M/S Karim Store','30 Oct 2026','৳ 25,500','৳ 0','Pending']]},
 purchases:{title:'Purchase invoices',desc:'Track supplier bills, purchases and outstanding amounts.',add:'Add purchase',cols:['Invoice no.','Supplier','Date','Total amount','Paid','Status'],rows:[['PUR-0042','Global Suppliers','01 Nov 2026','৳ 67,200','৳ 30,000','Partial'],['PUR-0041','City Wholesale','29 Oct 2026','৳ 35,000','৳ 35,000','Paid'],['PUR-0040','Tech Distribution','25 Oct 2026','৳ 18,500','৳ 0','Pending']]},
 income:{title:'Income',desc:'Record other income and review received amounts.',add:'Add income',cols:['Reference','Income source','Date','Account','Amount','Status'],rows:[['INC-0028','Consulting service','02 Nov 2026','Bank Account','৳ 35,000','Posted'],['INC-0027','Delivery charge','28 Oct 2026','Cash in Hand','৳ 8,500','Posted']]},
 expenses:{title:'Expenses',desc:'Record operating costs and attach supporting receipts.',add:'Add expense',cols:['Reference','Expense category','Date','Paid from','Amount','Status'],rows:[['EXP-0031','Office rent','03 Nov 2026','Bank Account','৳ 22,000','Paid'],['EXP-0030','Internet bill','01 Nov 2026','Cash in Hand','৳ 2,000','Paid'],['EXP-0029','Utilities','28 Oct 2026','Bank Account','৳ 6,800','Pending']]},
 payments:{title:'Payments',desc:'Manage customer receipts and supplier payments.',add:'Record payment',cols:['Payment ID','Party','Type','Date','Amount','Method'],rows:[['PAY-0081','Rahman Traders','Receipt','04 Nov 2026','৳ 48,500','Bank'],['PAY-0080','Global Suppliers','Payment','03 Nov 2026','৳ 30,000','Cash']]},
 customers:{title:'Customers',desc:'Manage customer profiles and outstanding balances.',add:'Add customer',cols:['Customer','Phone','Email','Total sales','Due amount','Status'],rows:[['Rahman Traders','01711-223344','rahman@example.com','৳ 3,45,000','৳ 0','Active'],['Nahar Enterprise','01812-334455','nahar@example.com','৳ 2,18,000','৳ 42,000','Active'],['M/S Karim Store','01913-445566','karim@example.com','৳ 95,000','৳ 25,500','Active']]},
 suppliers:{title:'Suppliers',desc:'Manage supplier contact information and payable balances.',add:'Add supplier',cols:['Supplier','Phone','Email','Total purchases','Due amount','Status'],rows:[['Global Suppliers','01712-456789','global@example.com','৳ 4,20,000','৳ 37,200','Active'],['City Wholesale','01813-567890','city@example.com','৳ 2,10,000','৳ 0','Active']]},
 products:{title:'Products & Stock',desc:'Manage products, selling prices and stock movement.',add:'Add product',cols:['Product','SKU','Purchase price','Selling price','Stock','Status'],rows:[['Wireless Mouse','PRD-001','৳ 450','৳ 650','120','In stock'],['Keyboard','PRD-002','৳ 700','৳ 950','75','In stock'],['USB Cable','PRD-003','৳ 90','৳ 150','8','Low stock']]},
 employees:{title:'Employees & Payroll',desc:'Manage employee records and monthly payroll.',add:'Add employee',cols:['Employee','Designation','Phone','Basic salary','Payroll status','Status'],rows:[['Sabbir Hossain','Accountant','01722-123456','৳ 28,000','Paid','Active'],['Nusrat Jahan','Sales Executive','01833-234567','৳ 24,000','Pending','Active']]},
 reports:{title:'Financial Reports',desc:'Review business performance from posted journal entries.',add:'Generate report',cols:['Report','Period','Last generated','Description','Action'],rows:[['Profit & Loss','Oct 2026','01 Nov 2026','Revenue less expenses','View'],['Balance Sheet','As of 31 Oct','01 Nov 2026','Assets, liabilities and equity','View'],['Cash Flow','Oct 2026','01 Nov 2026','Cash inflows and outflows','View'],['Trial Balance','Oct 2026','01 Nov 2026','Debit and credit balances','View'],['Account Ledger','Oct 2026','01 Nov 2026','Account transaction history','View']]},
 users:{title:'Users & Roles',desc:'Manage system users and role assignments.',add:'Add user',cols:['User','Email','Role','Last login','Status'],rows:[['Yeasin Arafat','yeasin@example.com','Administrator','Today, 9:15 AM','Active'],['Sabbir Hossain','sabbir@example.com','Accountant','Yesterday','Active'],['Nusrat Jahan','nusrat@example.com','Sales','02 Nov 2026','Active']]},
 settings:{title:'Company settings',desc:'Update company profile and application preferences.',add:'Edit settings',cols:['Setting','Current value','Description'],rows:[['Company name','Acme Business Ltd.','Registered business name'],['Currency','BDT (৳)','Default reporting currency'],['Financial year','January – December','Accounting year'],['Timezone','Asia/Dhaka','Local business timezone']]}
};
function showToast(message){document.getElementById('toastMessage').textContent=message;bootstrap.Toast.getOrCreateInstance(document.getElementById('demoToast')).show()}
function openPage(page){
 document.querySelectorAll('.side-link').forEach(a=>a.classList.toggle('active',a.dataset.page===page));
 document.querySelectorAll('.section').forEach(s=>s.classList.remove('active'));
 document.getElementById('topTitle').textContent=page==='dashboard'?'Dashboard':(datasets[page]?.title||'Dashboard');
 if(page==='dashboard'){document.getElementById('page-dashboard').classList.add('active')}
 else{document.getElementById('page-generic').classList.add('active');renderTable(page)}
 document.getElementById('sidebar').classList.remove('show');
}
function renderTable(page){
 const d=datasets[page];if(!d)return;
 document.getElementById('sectionTitle').textContent=d.title;document.getElementById('sectionDesc').textContent=d.desc;
 document.getElementById('addRecordLabel').textContent=d.add;document.getElementById('tableHeading').childNodes[0].textContent='All records ';
 document.getElementById('recordCount').textContent=`(${d.rows.length})`;
 document.getElementById('genericHead').innerHTML='<tr>'+d.cols.map(c=>`<th>${c}</th>`).join('')+'</tr>';
 document.getElementById('genericBody').innerHTML=d.rows.map(row=>'<tr>'+row.map((cell,i)=>{
   let value=cell;
   if(['Active','Paid','Posted','In stock'].includes(cell)) value=`<span class="badge-soft status-paid">${cell}</span>`;
   else if(['Pending','Partial'].includes(cell)) value=`<span class="badge-soft status-pending">${cell}</span>`;
   else if(['Low stock'].includes(cell)) value=`<span class="badge-soft status-overdue">${cell}</span>`;
   else if(['Draft'].includes(cell)) value=`<span class="badge-soft status-draft">${cell}</span>`;
   return `<td>${i===0?`<span class="fw-semibold">${value}</span>`:value}</td>`;
 }).join('')+'</tr>').join('');
 document.getElementById('tableFoot').textContent=`Showing ${d.rows.length} sample records`;
 document.getElementById('tableSearch').value='';
}
document.querySelectorAll('[data-page]').forEach(el=>el.addEventListener('click',()=>openPage(el.dataset.page)));
document.getElementById('menuBtn').addEventListener('click',()=>document.getElementById('sidebar').classList.toggle('show'));
document.getElementById('tableSearch').addEventListener('input',e=>{
 const q=e.target.value.toLowerCase();document.querySelectorAll('#genericBody tr').forEach(tr=>tr.style.display=tr.textContent.toLowerCase().includes(q)?'':'none');
});
document.getElementById('addRecordBtn').addEventListener('click',()=>showToast('This is a UI-only preview. Connect a backend to save records.'));
document.getElementById('dateRange').addEventListener('click',()=>showToast('Date range selector is a demo control.'));
document.getElementById('transactionForm').addEventListener('submit',e=>{
 e.preventDefault();const type=document.getElementById('txType').value,amount=document.getElementById('txAmount').value;
 bootstrap.Modal.getInstance(document.getElementById('transactionModal')).hide();e.target.reset();
 showToast(`${type} of ৳ ${Number(amount).toLocaleString('en-BD')} captured in this demo only.`);
});
document.getElementById('recentRows').innerHTML=sampleTransactions.map(t=>`<tr><td><div class="fw-semibold">${t.name}</div><div class="text-secondary small">${t.party}</div></td><td>${t.date}</td><td>${t.category}</td><td class="fw-semibold">${t.amount}</td><td><span class="badge-soft ${t.status==='Paid'||t.status==='Posted'?'status-paid':'status-pending'}">${t.status}</span></td></tr>`).join('');
document.getElementById('txDate').value=new Date().toISOString().slice(0,10);
new Chart(document.getElementById('overviewChart'),{type:'line',data:{labels:['Jun','Jul','Aug','Sep','Oct','Nov'],datasets:[{label:'Revenue',data:[620,710,680,790,760,845],borderColor:'#356df3',backgroundColor:'rgba(53,109,243,.08)',fill:true,tension:.4,borderWidth:2,pointRadius:3},{label:'Expenses',data:[310,350,330,390,370,312],borderColor:'#f2a541',backgroundColor:'rgba(242,165,65,.05)',fill:true,tension:.4,borderWidth:2,pointRadius:3}]},options:{responsive:true,plugins:{legend:{position:'bottom',labels:{usePointStyle:true,boxWidth:7,font:{family:'DM Sans',size:11}}}},scales:{y:{grid:{color:'#eef1f5'},ticks:{callback:v=>'৳'+v+'k',font:{size:10}}},x:{grid:{display:false},ticks:{font:{size:10}}}}}});
new Chart(document.getElementById('incomeChart'),{type:'doughnut',data:{labels:['Product sales','Services','Other income'],datasets:[{data:[590,165,90],backgroundColor:['#356df3','#159b72','#f2b544'],borderWidth:0,hoverOffset:5}]},options:{cutout:'72%',plugins:{legend:{display:false}}}});
</script>
</body>
</html>
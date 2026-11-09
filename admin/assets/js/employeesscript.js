const menu = [
  ["My Profile", "bi-person-fill"],
  ["My Salary", "bi-calendar2-check"],
  ["Attendance", "bi-calendar-check"],
  ["Leave Request", "bi-send-check"],
  ["Payslips", "bi-receipt"],
  ["My Transactions", "bi-arrow-left-right"],
  ["Notices", "bi-bell"],
  ["Settings", "bi-gear"],
];
const $ = (id) => document.getElementById(id);
const escapeHtml = (value) => String(value).replace(/[&<>"']/g, (char) => ({
  "&": "&amp;",
  "<": "&lt;",
  ">": "&gt;",
  '"': "&quot;",
  "'": "&#39;",
})[char]);
const info = [
  ["bi-envelope", "Email", window.employeeProfile?.email ?? ""],
  ["bi-person-badge", "Role", window.employeeProfile?.role ?? ""],
];
$("menu").innerHTML = menu
  .map(
    (m, i) =>
      `<span class="nav-link rounded text-white text-opacity-75${i ? "" : " active bg-primary text-white"}"><i class="bi ${m[1]} me-2"></i>${m[0]}</span>`,
  )
  .join("");

$("info").innerHTML = info
  .map(
    (r) =>
      `<div class="row py-2 border-bottom"><dt class="col-sm-5 text-secondary fw-normal"><i class="bi ${r[0]} me-2"></i>${r[1]}</dt><dd class="col-sm-7 mb-0 text-break">${escapeHtml(r[2])}</dd></div>`,
  )
  .join("");

const quick = [
  ["bi-calendar3", "Join Date", "Jan 15, 2024"],
  ["bi-clock", "Total Leave", "12 Days"],
  ["bi-briefcase-fill", "Office Hours", "9:00 AM – 5:00 PM"],
];
$("quick").innerHTML = quick
  .map(
    (q) =>
      `<div class="d-flex align-items-center gap-3 bg-light rounded p-2 mb-2"><span class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center p-2"><i class="bi ${q[0]}"></i></span><div><small class="d-block text-secondary">${q[1]}</small><b>${q[2]}</b></div></div>`,
  )
  .join("");

const acts = [
  [
    "View My Profile",
    "Personal Details",
    "bi-person-fill",
  ],
  [
    "Apply for Leave",
    "Request Leave",
    "bi-calendar2-check-fill",
  ],
  [
    "View Payslip",
    "Check Salary",
    "bi-file-earmark-text-fill",
  ],
  ["View Transactions", "My Records", "bi-list-check"],
];
$("actions").innerHTML = acts
  .map(
    (a) =>
      `<div class="col-sm-6"><div class="card dashboard-card card-body h-100 rounded-3 text-dark"><span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary p-2 mb-2 align-self-start"><i class="bi ${a[2]}"></i></span><div><b>${a[0]}</b><small class="d-block text-secondary">${a[1]}</small></div></div></div>`,
  )
  .join("");

const act = [
  [
    "Salary Credited",
    "Your salary for October 2026 has been credited.",
    "Nov 1, 2026 • 10:30 AM",
    "bi-check-circle-fill",
  ],
  [
    "Leave Request Approved",
    "Your leave request has been approved.",
    "Oct 28, 2026 • 04:15 PM",
    "bi-calendar-check",
  ],
  [
    "Payslip Available",
    "Your October 2026 payslip is ready to view.",
    "Oct 25, 2026 • 09:20 AM",
    "bi-file-earmark-text-fill",
  ],
];
$("activity").innerHTML = act
  .map(
    (a) =>
      `<li class="list-group-item d-flex align-items-start gap-3 px-0"><span class="rounded-circle bg-primary-subtle text-primary p-2"><i class="bi ${a[3]}"></i></span><div><b>${a[0]}</b><p class="mb-0 text-secondary">${a[1]}</p><small class="text-secondary">${a[2]}</small></div></li>`,
  )
  .join("");

const notes = [
  [
    "Office Holiday on November 10",
    "The office will remain closed on November 10, 2026 (Tuesday)...",
    "Nov 5, 2026",
  ],
  [
    "New HR Policy Updated",
    "Please check the updated HR policy in the company portal...",
    "Oct 30, 2026",
  ],
  [
    "Year-End Bonus Announcement",
    "This year's bonus will be processed in December 2026...",
    "Oct 25, 2026",
  ],
];
$("notices").innerHTML = notes
  .map(
    (n) =>
      `<li class="list-group-item d-flex align-items-start gap-2 px-0"><span class="badge rounded-pill text-bg-primary mt-1">&nbsp;</span><div><b>${n[0]}</b><p class="mb-1 text-secondary small">${n[1]}</p><small class="text-secondary">${n[2]}</small></div></li>`,
  )
  .join("");

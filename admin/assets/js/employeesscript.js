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
      `<a class="nav-link rounded text-white text-opacity-75${i ? "" : " active bg-primary text-white"}" href="#"><i class="bi ${m[1]} me-2"></i>${m[0]}</a>`,
  )
  .join("");
$("menu").addEventListener("click", (e) => {
  const a = e.target.closest(".nav-link");
  if (!a) return;
  e.preventDefault();
  document
    .querySelectorAll("#menu .nav-link")
    .forEach((x) => x.classList.remove("active"));
  a.classList.add("active");
  document.querySelectorAll("#menu .nav-link").forEach((x) => {
    x.classList.toggle("bg-primary", x === a);
    x.classList.toggle("text-white", x === a);
    x.classList.toggle("text-opacity-75", x !== a);
  });
});


$("info").innerHTML = info
  .map(
    (r) =>
      `<div class="row py-2 border-bottom"><dt class="col-sm-5 text-secondary fw-normal"><i class="bi ${r[0]} me-2"></i>${r[1]}</dt><dd class="col-sm-7 mb-0 text-break">${escapeHtml(r[2])}</dd></div>`,
  )
  .join("");

const quick = [
  ["bi-calendar3", "Join Date", "Jan 15, 2024", "#2563eb", "#dbe7fd"],
  ["bi-clock", "Total Leave", "12 Days", "#22a06b", "#d9f4e6"],
  [
    "bi-briefcase-fill",
    "Office Hours",
    "9:00 AM – 5:00 PM",
    "#f59e0b",
    "#fdeccf",
  ],
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
    "#2563eb",
    "#e6eefc",
  ],
  [
    "Apply for Leave",
    "Request Leave",
    "bi-calendar2-check-fill",
    "#22a06b",
    "#e4f6ec",
  ],
  [
    "View Payslip",
    "Check Salary",
    "bi-file-earmark-text-fill",
    "#8b5cf6",
    "#efe8fd",
  ],
  ["View Transactions", "My Records", "bi-list-check", "#f59e0b", "#fff0d9"],
];
$("actions").innerHTML = acts
  .map(
    (a) =>
      `<div class="col-sm-6"><a href="#" class="card card-body h-100 rounded-3 text-decoration-none text-dark"><span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary p-2 mb-2 align-self-start"><i class="bi ${a[2]}"></i></span><div><b>${a[0]}</b><small class="d-block text-secondary">${a[1]} <i class="bi bi-arrow-right"></i></small></div></a></div>`,
  )
  .join("");

const act = [
  [
    "Salary Credited",
    "Your salary for October 2026 has been credited.",
    "Nov 1, 2026 • 10:30 AM",
    "bi-check-circle-fill",
    "#22a06b",
    "#d9f4e6",
  ],
  [
    "Leave Request Approved",
    "Your leave request has been approved.",
    "Oct 28, 2026 • 04:15 PM",
    "bi-calendar-check",
    "#2563eb",
    "#dbe7fd",
  ],
  [
    "Payslip Available",
    "Your October 2026 payslip is ready to view.",
    "Oct 25, 2026 • 09:20 AM",
    "bi-file-earmark-text-fill",
    "#8b5cf6",
    "#efe8fd",
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
    "#2563eb",
  ],
  [
    "New HR Policy Updated",
    "Please check the updated HR policy in the company portal...",
    "Oct 30, 2026",
    "#22a06b",
  ],
  [
    "Year-End Bonus Announcement",
    "This year's bonus will be processed in December 2026...",
    "Oct 25, 2026",
    "#f59e0b",
  ],
];
$("notices").innerHTML = notes
  .map(
    (n) =>
      `<li class="list-group-item d-flex align-items-start gap-2 px-0"><span class="badge rounded-pill text-bg-primary mt-1">&nbsp;</span><div><b>${n[0]}</b><p class="mb-1 text-secondary small">${n[1]}</p><small class="text-secondary">${n[2]}</small></div></li>`,
  )
  .join("");

// Profile photo upload
const inp = Object.assign(document.createElement("input"), {
  type: "file",
  accept: "image/*",
});
$("editBtn").onclick = () => inp.click();
inp.onchange = () => {
  const f = inp.files[0];
  if (!f) return;
  const r = new FileReader();
  r.onload = () =>
    (document.getElementById("photoContent").innerHTML =
      `<img class="w-100 h-100 object-fit-cover" src="${r.result}" alt="Profile photo">`);
  r.readAsDataURL(f);
};

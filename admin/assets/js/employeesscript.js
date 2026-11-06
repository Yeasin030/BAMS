const menu=[['My Profile','bi-person-fill'],['My Salary','bi-calendar2-check'],['Attendance','bi-calendar-check'],['Leave Request','bi-send-check'],['Payslips','bi-receipt'],['My Transactions','bi-arrow-left-right'],['Notices','bi-bell'],['Settings','bi-gear']];
const $=id=>document.getElementById(id);
$('menu').innerHTML=menu.map((m,i)=>`<a class="nav-link${i?'':' active'}" href="#"><i class="bi ${m[1]}"></i>${m[0]}</a>`).join('');
$('menu').addEventListener('click',e=>{const a=e.target.closest('.nav-link');if(!a)return;e.preventDefault();
 document.querySelectorAll('#menu .nav-link').forEach(x=>x.classList.remove('active'));a.classList.add('active');$('sidebar').classList.remove('open')});
$('toggle').onclick=()=>$('sidebar').classList.toggle('open');

const info=[['bi-person-badge','Employee ID','EMP-1001'],['bi-building','Department','IT Department'],['bi-person-workspace','Designation','Software Developer'],['bi-envelope','Email','tanvir.hasan@bizledger.com']];
$('info').innerHTML=info.map(r=>`<div><dt><i class="bi ${r[0]}"></i>${r[1]}</dt><dd>${r[2]}</dd></div>`).join('');

const quick=[['bi-calendar3','Join Date','Jan 15, 2024','#2563eb','#dbe7fd'],['bi-clock','Total Leave','12 Days','#22a06b','#d9f4e6'],['bi-briefcase-fill','Office Hours','9:00 AM – 5:00 PM','#f59e0b','#fdeccf']];
$('quick').innerHTML=quick.map(q=>`<div class="qrow"><span class="ic" style="background:${q[4]};color:${q[3]}"><i class="bi ${q[0]}"></i></span><div><small>${q[1]}</small><b>${q[2]}</b></div></div>`).join('');

const acts=[['View My Profile','Personal Details','bi-person-fill','#2563eb','#e6eefc'],['Apply for Leave','Request Leave','bi-calendar2-check-fill','#22a06b','#e4f6ec'],['View Payslip','Check Salary','bi-file-earmark-text-fill','#8b5cf6','#efe8fd'],['View Transactions','My Records','bi-list-check','#f59e0b','#fff0d9']];
$('actions').innerHTML=acts.map(a=>`<div class="col-sm-6"><a href="#" class="act" style="background:${a[4]}"><span class="ai" style="background:#fff8;color:${a[3]}"><i class="bi ${a[2]}"></i></span><div><b>${a[0]}</b><small style="color:${a[3]}">${a[1]} <i class="bi bi-arrow-right"></i></small></div></a></div>`).join('');

const act=[['Salary Credited','Your salary for October 2026 has been credited.','Nov 1, 2026 • 10:30 AM','bi-check-circle-fill','#22a06b','#d9f4e6'],
 ['Leave Request Approved','Your leave request has been approved.','Oct 28, 2026 • 04:15 PM','bi-calendar-check','#2563eb','#dbe7fd'],
 ['Payslip Available','Your October 2026 payslip is ready to view.','Oct 25, 2026 • 09:20 AM','bi-file-earmark-text-fill','#8b5cf6','#efe8fd']];
$('activity').innerHTML=act.map(a=>`<li style="--c:${a[4]}"><span class="ic" style="background:${a[5]};color:${a[4]}"><i class="bi ${a[3]}"></i></span><div><b>${a[0]}</b><p>${a[1]}</p></div><span class="when">${a[2]}</span></li>`).join('');

const notes=[['Office Holiday on November 10','The office will remain closed on November 10, 2026 (Tuesday)...','Nov 5, 2026','#2563eb'],['New HR Policy Updated','Please check the updated HR policy in the company portal...','Oct 30, 2026','#22a06b'],['Year-End Bonus Announcement','This year\'s bonus will be processed in December 2026...','Oct 25, 2026','#f59e0b']];
$('notices').innerHTML=notes.map(n=>`<li><span class="dot" style="background:${n[3]}"></span><div><b>${n[0]}</b><p>${n[1]}</p></div><span class="date">${n[2]}</span></li>`).join('');

// Profile photo upload
const inp=Object.assign(document.createElement('input'),{type:'file',accept:'image/*'});
$('editBtn').onclick=()=>inp.click();
inp.onchange=()=>{const f=inp.files[0];if(!f)return;const r=new FileReader();r.onload=()=>document.querySelector('.ph').innerHTML=`<img src="${r.result}" alt="Profile photo">`;r.readAsDataURL(f)};

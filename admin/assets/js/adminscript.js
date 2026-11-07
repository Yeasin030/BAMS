const $=id=>document.getElementById(id);
const fmt=n=>'৳ '+n.toLocaleString('en-IN');

// Greeting + date
const now=new Date(),h=now.getHours();
$('greet').textContent=(h<12?'Good Morning':h<17?'Good Afternoon':'Good Evening')+', Admin!';

// Sidebar
const mods=[['Users & Roles','bi-people-fill',['All Users','Roles & Permissions']],['Transactions','bi-arrow-left-right',['All Transactions','Add Transaction']],['Products & Stock','bi-box-seam-fill',['Products','Stock Levels']],['Sales & Invoices','bi-cart-fill',['Invoices','Customers']],['Reports','bi-bar-chart-fill',['Profit & Loss','Balance Sheet']],['Accounts','bi-bank2',['Chart of Accounts','Ledgers']],['Settings','bi-gear-fill',['General','Preferences']]];
$('menu').innerHTML=`<a class="nav-link active" href="#"><i class="bi bi-house-fill"></i>Dashboard</a><div class="grp">MAIN MODULES</div>`+
 mods.map((m,i)=>`<a class="nav-link" data-bs-toggle="collapse" href="#m${i}" aria-expanded="false"><i class="bi ${m[1]}"></i>${m[0]}<i class="bi bi-chevron-down chev"></i></a>
 <div class="collapse sub" id="m${i}" data-bs-parent="#menu">${m[2].map(s=>`<a href="#">${s}</a>`).join('')}</div>`).join('');
$('tools').innerHTML=[['Company Settings','bi-building-fill'],['Backup & Restore','bi-database-fill'],['Activity Logs','bi-clock-history'],['Manage Employees','bi-briefcase-fill']].map(t=>`<a class="nav-link" href="#"><i class="bi ${t[1]}"></i>${t[0]}</a>`).join('');
$('toggle').onclick=()=>$('sidebar').classList.toggle('open');

// KPIs
const kp=[['Total Revenue',845250,'12.8%',1,'bi-bar-chart-fill','#2563eb','#dbe7fd','vs last month',[3,4,3.5,4.5,4,5,7,6.5,8]],
 ['Total Expenses',312480,'4.3%',0,'bi-wallet2','#22a06b','#d9f4e6','vs last month',[3,3.5,3,4,3.5,5,6,5,7]],
 ['Net Profit',532770,'18.6%',1,'bi-graph-up-arrow','#8b5cf6','#ece4fd','vs last month',[2,3,3.5,3,4,4.5,6,5.5,7]],
 ['Bank Balance',1240600,'',1,'bi-bank','#f59e0b','#fdeccf','Across 3 accounts',[2,2.5,3,3.5,3,4,5,4.5,6.5]]];
$('kpis').innerHTML=kp.map((k,i)=>`<div class="col-sm-6 col-xl-3"><div class="cardx kpi"><div class="top">
 <div class="ic" style="background:${k[6]};color:${k[5]}"><i class="bi ${k[4]}"></i></div>
 <div><div class="lbl">${k[0]}</div><div class="val">${fmt(k[1])}</div>
 <div class="chg">${k[2]?`<span class="${k[3]?'up':'down'}"><i class="bi bi-arrow-up-right"></i> ${k[2]}</span> <span>${k[7]}</span>`:`<span>${k[7]}</span>`}</div></div></div>
 <canvas id="sp${i}"></canvas></div></div>`).join('');
kp.forEach((k,i)=>{const c=$('sp'+i).getContext('2d'),g=c.createLinearGradient(0,0,0,70);g.addColorStop(0,k[5]+'40');g.addColorStop(1,k[5]+'08');
 new Chart(c,{type:'line',data:{labels:k[8],datasets:[{data:k[8],borderColor:k[5]+'55',backgroundColor:g,fill:true,tension:.45,pointRadius:0,borderWidth:1.5}]},
 options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{enabled:false}},scales:{x:{display:false},y:{display:false}},events:[]}})});

// Financial chart
const all={labels:['Dec 2025','Jan 2026','Feb 2026','Mar 2026','Apr 2026','May 2026','Jun 2026','Jul 2026','Aug 2026','Sep 2026','Oct 2026','Nov 2026'],
 inc:[5.1,5.4,5.8,6.1,6.0,6.2,6.0,7.6,7.4,8.0,8.7,9.3],exp:[2.0,2.3,2.8,3.0,2.7,2.9,2.6,3.9,4.0,5.0,5.3,4.9]};
const ctx=$('line').getContext('2d');
const gr=c=>{const g=ctx.createLinearGradient(0,0,0,270);g.addColorStop(0,c+'33');g.addColorStop(1,c+'05');return g};
const line=new Chart(ctx,{type:'line',data:{labels:[],datasets:[
 {label:'Income',data:[],borderColor:'#2563eb',backgroundColor:gr('#2563eb'),fill:true,tension:.35,pointBackgroundColor:'#2563eb',pointRadius:4},
 {label:'Expenses',data:[],borderColor:'#f59e0b',backgroundColor:gr('#f59e0b'),fill:true,tension:.35,pointBackgroundColor:'#f59e0b',pointRadius:4}]},
 options:{maintainAspectRatio:false,interaction:{mode:'index',intersect:false},plugins:{legend:{position:'top',labels:{usePointStyle:true,boxWidth:8}},tooltip:{callbacks:{label:c=>` ${c.dataset.label}: ৳ ${c.parsed.y}L`}}},
 scales:{y:{min:0,max:10,ticks:{stepSize:2,callback:v=>v?v+'L':0},grid:{color:'#eef2f9'}},x:{grid:{display:false}}}}});
function setRange(n){const s=all.labels.length-n;line.data.labels=all.labels.slice(s);line.data.datasets[0].data=all.inc.slice(s);line.data.datasets[1].data=all.exp.slice(s);line.update()}
setRange(6);
$('seg').onclick=e=>{const b=e.target.closest('button');if(!b)return;document.querySelectorAll('#seg button').forEach(x=>x.classList.remove('on'));b.classList.add('on');
 setRange(b.dataset.r=='c'?4:+b.dataset.r)};

// Donut
const cat=[['Salaries',35.5,'#3b82f6'],['Purchases',28.1,'#34c38f'],['Other Costs',21.4,'#fb9f3c'],['Rent & Bills',14.9,'#8b5cf6']];
new Chart($('donut'),{type:'doughnut',data:{labels:cat.map(c=>c[0]),datasets:[{data:cat.map(c=>c[1]),backgroundColor:cat.map(c=>c[2]),borderWidth:2,borderColor:'#fff'}]},
 options:{cutout:'64%',plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>` ${c.label}: ${c.parsed}%`}}}}});
$('legend').innerHTML=cat.map(c=>`<li><span class="dot" style="background:${c[2]}"></span>${c[0]}<span class="pc">${c[1]}%</span></li>`).join('');

// Transactions
const tx=[['Nov 6, 2026','Product Purchase','Expense',45000],['Nov 6, 2026','Sales Invoice #INV-1043','Income',85000],['Nov 5, 2026','Employee Salary','Expense',70000],['Nov 4, 2026','Client Payment','Income',120000]];
$('txBody').innerHTML=tx.map(t=>`<tr><td>${t[0]}</td><td>${t[1]}</td><td><span class="pill ${t[2]=='Expense'?'p-exp':'p-inc'}">${t[2]}</span></td><td>${fmt(t[3])}</td><td><span class="pill p-ok">Completed</span></td></tr>`).join('');

// Quick actions
const ac=[['Add Transaction','bi-arrow-left-right','#2563eb','#dbe7fd'],['Generate Report','bi-file-earmark-text','#16874f','#d9f4e6'],['Manage Users','bi-people','#6d3fd0','#e9e1fb'],['Manage Products','bi-box-seam','#d97706','#fdebd0']];
$('actions').innerHTML=ac.map(a=>`<div class="col-6"><a href="#" class="act" style="background:${a[3]};color:${a[2]}"><i class="bi ${a[1]}"></i>${a[0]}</a></div>`).join('');

// Ctrl+K focuses search
addEventListener('keydown',e=>{if((e.ctrlKey||e.metaKey)&&e.key.toLowerCase()=='k'){e.preventDefault();$('q').focus()}});
$('today').textContent=now.toLocaleDateString('en-US',{month:'long',day:'numeric',year:'numeric'});

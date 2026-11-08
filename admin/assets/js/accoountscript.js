const kpis = [
    {
        l: 'Total Revenue',
        v: '৳ 2,75,000',
        c: '12.5%',
        i: 'bi-currency-exchange',
        col: '#2563eb',
        bg: '#e0eaff',
        d: [3, 4, 5, 6, 8, 9, 11]
    },
    {
        l: 'Total Expenses',
        v: '৳ 1,80,500',
        c: '8.3%',
        i: 'bi-cart3',
        col: '#22a06b',
        bg: '#d9f4e6',
        d: [3, 5, 4, 7, 8, 9, 11]
    },
    {
        l: 'Net Profit',
        v: '৳ 94,500',
        c: '15.2%',
        i: 'bi-graph-up-arrow',
        col: '#8b5cf6',
        bg: '#ece4fd',
        d: [2, 3, 4, 5, 7, 8, 10]
    },
    {
        l: 'Bank Balance',
        v: '৳ 5,20,000',
        c: '6.1%',
        i: 'bi-bank',
        col: '#f59e0b',
        bg: '#fdeccf',
        d: [2, 3, 4, 6, 7, 8, 9]
    }
];

document.getElementById('kpis').innerHTML = kpis.map((k, n) => `<div class="col-md-6 col-xl-3"><div class="card h-100 border-0 rounded-4 shadow-sm"><div class="card-body p-4">
 <div class="d-flex align-items-center gap-3">
 <div class="rounded-3 p-3 bg-primary-subtle text-primary"><i class="bi ${k.i} fs-4"></i></div>
 <div><div class="small text-secondary">${k.l}</div><div class="fs-5 fw-bold">${k.v}</div><div class="small"><span class="text-success"><i class="bi bi-arrow-up-right"></i> ${k.c}</span> <span class="text-secondary">vs last month</span></div></div></div>
 <canvas id="spark${n}" height="60"></canvas></div></div></div>`).join('');

kpis.forEach((k, n) => new Chart(document.getElementById('spark' + n), {
    type: 'bar',
    data: {
        labels: k.d.map((_, i) => i),
        datasets: [{
            data: k.d,
            backgroundColor: k.d.map((_, i) => k.col + (30 + i * 10).toString(16).padStart(2, '0').slice(-2)),
            borderRadius: 2
        }]
    },
    options: {
        plugins: {
            legend: { display: false },
            tooltip: { enabled: false }
        },
        scales: {
            x: { display: false },
            y: { display: false }
        },
        events: []
    }
}));

const actions = [
    ['Add Transaction', 'Income or Expense', 'bi-file-earmark-plus-fill', '#2563eb', '#e8f0ff'],
    ['Generate Report', 'PDF / Excel', 'bi-file-earmark-bar-graph-fill', '#22a06b', '#e4f7ec'],
    ['View Ledgers', 'All Accounts', 'bi-journal-bookmark-fill', '#8b5cf6', '#f0e9fe'],
    ['Manage Invoices', 'Create & Track', 'bi-calculator-fill', '#f59e0b', '#fff1dc'],
    ['Set Budget', 'Plan Your Budget', 'bi-pie-chart-fill', '#0e8fb5', '#e1f3fa'],
    ['Settings', 'Account Settings', 'bi-gear-fill', '#22a06b', '#e8f6ec']
];

document.getElementById('actions').innerHTML = actions.map(a => `<div class="col-sm-6"><a href="#" class="card card-body h-100 rounded-3 text-decoration-none text-dark">
 <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary p-2 mb-2 align-self-start"><i class="bi ${a[2]}"></i></span>
 <div class="fw-semibold">${a[0]}</div><small class="d-block text-secondary">${a[1]}</small></a></div>`).join('');

const tx = [
    ['Nov 7, 2026', 'Office Rent', 'Rent', 50000, 'Expense', 'bi-house-fill', '#ef4444'],
    ['Nov 6, 2026', 'Client Payment', 'Sales', 75000, 'Income', 'bi-credit-card-2-front', '#2563eb'],
    ['Nov 5, 2026', 'Electricity Bill', 'Utility', 8500, 'Expense', 'bi-credit-card-2-front', '#2563eb'],
    ['Nov 4, 2026', 'Product Purchase', 'Purchase', 42000, 'Expense', 'bi-bag-fill', '#8b5cf6'],
    ['Nov 3, 2026', 'Consulting Service', 'Service', 60000, 'Income', 'bi-bag-fill', '#8b5cf6']
];

const fmt = n => '৳ ' + n.toLocaleString('en-IN');

document.getElementById('txBody').innerHTML = tx.map(t => `<tr><td>${t[0]}</td>
 <td><i class="bi ${t[5]} text-primary me-2"></i>${t[1]}</td><td>${t[2]}</td>
 <td class="text-end fw-medium">${fmt(t[3])}</td>
 <td class="text-center"><span class="badge ${t[4] == 'Expense' ? 'text-bg-danger' : 'text-bg-success'}">${t[4]}</span></td>
 <td class="text-center"><span class="badge text-bg-success">Posted</span></td></tr>`).join('');

const ctx = document.getElementById('lineChart').getContext('2d');
const grad = c => {
    const g = ctx.createLinearGradient(0, 0, 0, 260);
    g.addColorStop(0, c + '33');
    g.addColorStop(1, c + '05');
    return g;
};

const sets = {
    Monthly: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        inc: [1.9, 2.55, 2.05, 2.2, 2.7, 3.0],
        exp: [1.0, 1.55, 1.3, 1.35, 1.5, 2.05]
    },
    Quarterly: {
        labels: ['Q1', 'Q2', 'Q3', 'Q4'],
        inc: [6.5, 7.9, 8.4, 9.1],
        exp: [3.9, 4.9, 5.2, 5.8]
    }
};

const line = new Chart(ctx, {
    type: 'line',
    data: {
        labels: sets.Monthly.labels,
        datasets: [
            {
                label: 'Income',
                data: sets.Monthly.inc,
                borderColor: '#2563eb',
                backgroundColor: grad('#2563eb'),
                fill: true,
                tension: .4,
                pointBackgroundColor: '#2563eb',
                pointRadius: 5
            },
            {
                label: 'Expenses',
                data: sets.Monthly.exp,
                borderColor: '#8b5cf6',
                backgroundColor: grad('#8b5cf6'),
                fill: true,
                tension: .4,
                pointBackgroundColor: '#8b5cf6',
                pointRadius: 5
            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: { usePointStyle: true, boxWidth: 8 }
            }
        },
        scales: {
            y: {
                min: 0,
                ticks: { callback: v => v ? v + 'L' : 0, stepSize: 1 },
                grid: { color: '#eef2f9' }
            },
            x: { grid: { display: false } }
        }
    }
});

document.getElementById('rangeSel').addEventListener('change', e => {
    const s = sets[e.target.value];
    line.data.labels = s.labels;
    line.data.datasets[0].data = s.inc;
    line.data.datasets[1].data = s.exp;
    line.update();
});

const seg = [
    ['Sales / Income', 60.4, 290000, '#2563eb'],
    ['Expenses', 37.5, 180500, '#8b5cf6'],
    ['Other', 2.1, 10500, '#f59e0b']
];

new Chart(document.getElementById('donut'), {
    type: 'doughnut',
    data: {
        datasets: [{
            data: seg.map(s => s[1]),
            backgroundColor: seg.map(s => s[3]),
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        cutout: '66%',
        plugins: {
            legend: { display: false },
            tooltip: { enabled: false }
        }
    }
});

document.getElementById('legend').innerHTML = seg.map(s => `<li class="d-flex align-items-center gap-2 py-2"><span class="rounded-circle flex-shrink-0" style="width:.7rem;height:.7rem;background:${s[3]}"></span><span>${s[0]}</span><span class="ms-auto text-end"><b class="d-block">${s[1]}%</b><small class="text-secondary">${fmt(s[2])}</small></span></li>`).join('');

document.querySelectorAll('#menu .nav-link').forEach(a => a.addEventListener('click', e => {
    e.preventDefault();
    document.querySelectorAll('#menu .nav-link').forEach(x => {
        x.classList.remove('active', 'bg-primary', 'text-white');
        x.classList.add('text-white', 'text-opacity-75');
    });
    a.classList.add('active', 'bg-primary', 'text-white');
    a.classList.remove('text-opacity-75');
}));

document.querySelectorAll('.period .dropdown-item').forEach(a => a.addEventListener('click', e => {
    e.preventDefault();
    document.getElementById('periodLabel').textContent = a.textContent;
}));

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

document.getElementById('kpis').innerHTML = kpis.map((k, n) => `<div class="col-md-6 col-xl-3"><div class="cardx kpi">
 <div class="ic" style="background:${k.bg};color:${k.col}"><i class="bi ${k.i}"></i></div>
 <div><div class="lbl">${k.l}</div><div class="val">${k.v}</div><div class="chg"><i class="bi bi-arrow-up-right"></i> ${k.c} <span>vs last month</span></div></div>
 <canvas id="spark${n}"></canvas></div></div>`).join('');

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

document.getElementById('actions').innerHTML = actions.map(a => `<div class="col-sm-4"><a href="#" class="act" style="background:${a[4]}">
 <span class="ai" style="background:#fff;color:${a[3]}"><i class="bi ${a[2]}"></i></span>
 <div><b>${a[0]}</b><small>${a[1]}</small></div><i class="bi bi-chevron-right"></i></a></div>`).join('');

const tx = [
    ['Nov 7, 2026', 'Office Rent', 'Rent', 50000, 'Expense', 'bi-house-fill', '#ef4444'],
    ['Nov 6, 2026', 'Client Payment', 'Sales', 75000, 'Income', 'bi-credit-card-2-front', '#2563eb'],
    ['Nov 5, 2026', 'Electricity Bill', 'Utility', 8500, 'Expense', 'bi-credit-card-2-front', '#2563eb'],
    ['Nov 4, 2026', 'Product Purchase', 'Purchase', 42000, 'Expense', 'bi-bag-fill', '#8b5cf6'],
    ['Nov 3, 2026', 'Consulting Service', 'Service', 60000, 'Income', 'bi-bag-fill', '#8b5cf6']
];

const fmt = n => '৳ ' + n.toLocaleString('en-IN');

document.getElementById('txBody').innerHTML = tx.map(t => `<tr><td>${t[0]}</td>
 <td><span class="t-ic" style="color:${t[6]};background:${t[6]}1a"><i class="bi ${t[5]}"></i></span>${t[1]}</td><td>${t[2]}</td>
 <td class="text-end fw-medium">${fmt(t[3])}</td>
 <td class="text-center"><span class="pill ${t[4] == 'Expense' ? 'p-exp' : 'p-inc'}">${t[4]}</span></td>
 <td class="text-center"><span class="pill p-post">Posted</span></td></tr>`).join('');

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
            data: [50, 28, 18, 4],
            backgroundColor: ['#2563eb', '#8b5cf6', '#34c38f', '#f59e0b'],
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

document.getElementById('legend').innerHTML = seg.map(s => `<li><span class="dot" style="background:${s[3]}"></span>${s[0]}<span class="pc">${s[1]}%</span><span class="am">${fmt(s[2])}</span></li>`).join('');

document.querySelectorAll('#menu .nav-link').forEach(a => a.addEventListener('click', e => {
    e.preventDefault();
    document.querySelectorAll('#menu .nav-link').forEach(x => x.classList.remove('active'));
    a.classList.add('active');
}));

document.querySelectorAll('.period .dropdown-item').forEach(a => a.addEventListener('click', e => {
    e.preventDefault();
    document.getElementById('periodLabel').textContent = a.textContent;
}));

document.getElementById('toggle').addEventListener('click', () => document.getElementById('sidebar').classList.toggle('open'));

@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/whatwg-fetch@3.6.2/dist/fetch.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/resize-observer-polyfill@1.5.1/dist/ResizeObserver.global.js"></script>

@php
$user = Auth::user();
$isAdmin = $user->hasRole('Admin');
$isRequestor = $user->hasRole('Requestor');
$isTeknisi = $user->hasRole('Teknisi');
$isView = $user->hasRole('Views');
$isTeknisiAdmin = $user->hasRole('AdminTeknisi');
$isStandardTeknisi = $isTeknisi && !$isAdmin && !$isTeknisiAdmin;
@endphp

<style>
    body, html {
        overflow-x: hidden;
        overflow-y: auto;
    }
    .card-link-hover:hover .card {
        transform: scale(1.03);
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.2) !important;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    .card-link-hover .card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    .card {
        border-radius: 1rem;
        border: none;
    }
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #e9ecef;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }
    .text-primary-dark {
        color: #0056b3;
    }
    tr[data-status="TO DO"] { background-color: #f8d7da !important; }
    tr[data-status="IN PROGRESS"] { background-color: #fff3cd !important; }
    tr[data-status="PENDING"] { background-color: #e2e3e5 !important; }
    tr[data-status] td { background-color: inherit; }
    strong { font-weight: bold; }

    .chart-container {
        position: relative;
        width: 100%;
        max-width: 250px;
        height: 250px;
        margin: 0 auto;
    }
    
    #disco-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.0);
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.5s ease-in-out;
    }

    @keyframes disco-blink {
        0% { background-color: rgba(255, 0, 0, 0.3); }
        16% { background-color: rgba(255, 165, 0, 0.3); }
        33% { background-color: rgba(255, 255, 0, 0.3); }
        50% { background-color: rgba(0, 128, 0, 0.3); }
        66% { background-color: rgba(0, 0, 255, 0.3); }
        83% { background-color: rgba(75, 0, 130, 0.3); }
        100% { background-color: rgba(255, 0, 0, 0.3); }
    }
    
    #disco-overlay.active {
        animation: disco-blink 2s infinite step-end;
        opacity: 1;
        transition: opacity 0.5s ease-in-out;
        pointer-events: none;
    }
</style>

@if($isView)
<div id="disco-overlay"></div>
@endif

<div class="{{ $isView ? 'container-fluid px-4' : 'container' }} py-3">
    <h2 class="text-center mb-2 text-dark"><strong>Welcome, {{ $user->name }} 👋</strong></h2>

    @if($isView)
        <div class="row mt-3">
            <div class="col-lg-4 col-md-5 mb-3">
                <div class="card h-100 p-3 shadow-sm">
                    <div class="card-header text-center">
                        <h5><strong>Status Ticket</strong></h5>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center">
                        <div class="w-100 d-flex flex-column align-items-center justify-content-center">
                            <div class="chart-container mb-3">
                                <canvas id="ticketStatusChart"></canvas>
                            </div>
                            <ul class="list-unstyled w-100">
                                @foreach($ticketStatusChartData['labels'] as $key => $label)
                                <li>
                                    <span style="display:inline-block;width:10px;height:10px;background-color:{{ $ticketStatusChartData['colors'][$key] }};margin-right:5px;border-radius:50%;"></span>
                                    <strong>{{ $label }}: {{ $ticketStatusChartData['data'][$key] }}</strong>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-7 mb-3">
                {{-- Added mb-2 to header to show the line clearly separated from table --}}
                <div class="card h-100 p-3 shadow-sm">
                    <div class="card-header text-center mb-2">
                        <h5><strong>WIP Dandory Tickets</strong></h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="height: 75vh; overflow-y: auto;">
                            <table class="table table-bordered table-striped align-middle mb-0">
                                <thead class="table-light sticky-top" style="top: 0; z-index: 1;">
                                    <tr>
                                        <th class="text-dark"><strong>ID</strong></th>
                                        <th class="text-dark"><strong>Line</strong></th>
                                        <th class="text-dark"><strong>Requestor</strong></th>
                                        <th class="text-dark"><strong>Customer</strong></th>
                                        <th class="text-dark"><strong>Nama Part</strong></th>
                                        <th class="text-dark"><strong>No Part</strong></th>
                                        <th class="text-dark"><strong>Proses</strong></th>
                                        <th class="text-dark"><strong>Mesin</strong></th>
                                        <th class="text-dark"><strong>Qty</strong></th>
                                        <th class="text-dark"><strong>Shift</strong></th>
                                        <th class="text-dark"><strong>Status</strong></th>
                                        <th class="text-dark"><strong>Dandori Man</strong></th>
                                    </tr>
                                </thead>
                                <tbody id="dandori-table-body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- STANDARD LAYOUT FOR OTHER ROLES --}}
        <div class="row row-cols-1 row-cols-md-3 g-3 justify-content-center mt-3">
            @if($isAdmin || $isRequestor || $isTeknisi || $isTeknisiAdmin)
            <div class="col-md-4">
                <a href="{{ route('dandories.index') }}" class="text-decoration-none card-link-hover">
                    <div class="card h-100 text-center shadow-sm p-3">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <i class="fas fa-clipboard-list fa-3x mb-2 text-info"></i>
                            <h5 class="card-title text-dark"><strong>Dandory Tickets</strong></h5>
                            <p class="card-text text-muted"><strong>Melihat dan manage tiket Dandori.</strong></p>
                        </div>
                    </div>
                </a>
            </div>
            @endif
        </div>

        @if (session('status'))
            <div class="alert alert-success text-center mt-3" role="alert">
                <strong>{{ session('status') }}</strong>
            </div>
        @endif

        <div class="row mt-3">
            <div class="row g-3 justify-content-center">
                <div class="col-lg-6 col-md-6 mb-3">
                    <div class="card h-100 p-3 shadow-sm">
                        <div class="card-header text-center">
                            <h5><strong>Status Ticket</strong></h5>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center">
                            <div class="row w-100 g-3 d-flex align-items-center justify-content-center">
                                <div class="col-md-6 d-flex justify-content-center align-items-center">
                                    <div class="chart-container">
                                        <canvas id="ticketStatusChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex justify-content-center">
                                    <ul class="list-unstyled mt-2 w-100">
                                        @foreach($ticketStatusChartData['labels'] as $key => $label)
                                        <li>
                                            <span style="display:inline-block;width:10px;height:10px;background-color:{{ $ticketStatusChartData['colors'][$key] }};margin-right:5px;border-radius:50%;"></span>
                                            <strong>{{ $label }}: {{ $ticketStatusChartData['data'][$key] }}</strong>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 col-md-6 mb-3">
                    <div class="card h-100 p-3 shadow-sm">
                        <div class="card-header text-center">
                            <h5><strong>Dandoriman Ticket</strong></h5>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center">
                            <div class="row w-100 g-3 d-flex align-items-center justify-content-center">
                                <div class="col-md-6 d-flex justify-content-center align-items-center">
                                    <div class="chart-container">
                                        <canvas id="dandoriManChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex justify-content-center">
                                    <ul class="list-unstyled mt-2 w-100">
                                        @foreach($dandoriManChartData['labels'] as $key => $label)
                                        <li>
                                            <span style="display:inline-block;width:10px;height:10px;background-color:{{ $dandoriManChartData['colors'][$key] }};margin-right:5px;border-radius:50%;"></span>
                                            <strong>{{ $label }}: {{ $dandoriManChartData['data'][$key] }}</strong>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <div class="card h-100 p-3 shadow-sm">
                    <div class="card-header text-center">
                        <h5><strong>Daily, Weekly & Monthly Tickets</strong></h5>
                    </div>
                    <div class="card-body">
                        <div class="btn-group mb-2 d-flex justify-content-center" role="group">
                            <button type="button" class="btn btn-sm btn-primary" id="dailyBtn"><strong>Daily</strong></button>
                            <button type="button" class="btn btn-sm btn-secondary" id="weeklyBtn"><strong>Weekly</strong></button>
                            <button type="button" class="btn btn-sm btn-secondary" id="monthlyBtn"><strong>Monthly</strong></button>
                        </div>
                        <div id="daily-filter-container" class="mb-2">
                            <div class="row g-2">
                                <div class="col">
                                    <input type="date" id="start-date" class="form-control form-control-sm" title="Start Date">
                                </div>
                                <div class="col">
                                    <input type="date" id="end-date" class="form-control form-control-sm" title="End Date">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div style="position: relative; height: 300px; width: 100%;">
                                    <canvas id="resolutionChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-center">
                                <ul id="resolution-legend" class="list-unstyled w-100"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    var ticketStatusChart, dandoriManChart, resolutionChart;
    
    var ticketStatusChartData = {
        labels: @json($ticketStatusChartData['labels']),
        datasets: [{
            data: @json($ticketStatusChartData['data']),
            backgroundColor: @json($ticketStatusChartData['colors']),
            hoverOffset: 4
        }]
    };

    var dandoriManChartData = {
        labels: @json($dandoriManChartData['labels']),
        datasets: [{
            data: @json($dandoriManChartData['data']),
            backgroundColor: @json($dandoriManChartData['colors']),
            hoverOffset: 4
        }]
    };

    var dailyTicketCounts = @json($dailyTicketCounts);
    var monthlyTicketCounts = @json($monthlyTicketCounts);
    var colorPalette = ['#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6610f2', '#6c757d', '#fd7e14', '#e83e8c', '#6f42c1', '#20c997', '#d63384'];

    var centerTextPlugin = {
        id: 'centerTextPlugin',
        beforeDraw: function(chart) {
            var activeElements = chart.tooltip && chart.tooltip.getActiveElements() ? chart.tooltip.getActiveElements() : [];
            if (activeElements.length === 0) return;
            
            var activeElement = activeElements[0];
            var dataIndex = activeElement.index;
            var value = chart.data.datasets[0].data[dataIndex];
            var label = chart.data.labels[dataIndex];
            
            var total = chart.data.datasets[0].data.reduce(function(sum, val) { return sum + val; }, 0);
            var percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;

            var ctx = chart.ctx;
            ctx.save();
            ctx.font = 'bolder 1.5rem sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            var centerX = (chart.chartArea.left + chart.chartArea.right) / 2;
            var centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2;

            ctx.fillStyle = chart.data.datasets[0].backgroundColor[dataIndex];
            ctx.fillText(percentage + '%', centerX, centerY - 15);

            ctx.font = '1rem sans-serif';
            ctx.fillStyle = '#6c757d';
            ctx.fillText(label, centerX, centerY + 15);
            ctx.restore();
        }
    };

    Chart.register(centerTextPlugin);

    function createDoughnutChart(chartId, chartData) {
        var canvas = document.getElementById(chartId);
        if(!canvas) return;
        var ctx = canvas.getContext('2d');
        if (Chart.getChart(chartId)) {
            Chart.getChart(chartId).destroy();
        }
        return new Chart(ctx, {
            type: 'doughnut',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false },
                },
            }
        });
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        var legendContainer = document.getElementById('resolution-legend');
        var dailyBtn = document.getElementById('dailyBtn');
        var weeklyBtn = document.getElementById('weeklyBtn');
        var monthlyBtn = document.getElementById('monthlyBtn');
        var allButtons = [dailyBtn, weeklyBtn, monthlyBtn];
        var dailyFilterContainer = document.getElementById('daily-filter-container');
        var startDateInput = document.getElementById('start-date');
        var endDateInput = document.getElementById('end-date');
        
        var today = new Date();
        var todayStr = today.toISOString().slice(0, 10);
        var sevenDaysAgo = new Date(today);
        sevenDaysAgo.setDate(today.getDate() - 6);
        var sevenDaysAgoStr = sevenDaysAgo.toISOString().slice(0, 10);

        if (startDateInput) {
            startDateInput.setAttribute('max', todayStr);
            endDateInput.setAttribute('max', todayStr);
            startDateInput.value = sevenDaysAgoStr;
            endDateInput.value = todayStr;
        }

        function setActiveButton(activeButton) {
            if (!activeButton) return;
            allButtons.forEach(function(btn) {
                if(btn) {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-secondary');
                }
            });
            activeButton.classList.remove('btn-secondary');
            activeButton.classList.add('btn-primary');
        }

        function tooltipWithPercentage(context) {
            var dataset = context.dataset.data;
            var total = dataset.reduce(function(a, b) { return a + b; }, 0);
            var value = context.raw;
            var percentage = total > 0 ? ((value / total) * 100).toFixed(2) : 0;
            if (context.chart.config.type === 'doughnut') {
                if (value === 0) return '';
                return context.label + ': ' + value + ' (' + percentage + '%)';
            }
            return context.label + ': ' + value;
        }

        function createResolutionChart(type, data, labels, chartLabel) {
            var canvas = document.getElementById('resolutionChart');
            if (!canvas) return;

            if (resolutionChart) resolutionChart.destroy();
            var ctx = canvas.getContext('2d');
            var resolutionColors = labels.map(function(_, index) { 
                return colorPalette[index % colorPalette.length]; 
            });
            
            resolutionChart = new Chart(ctx, {
                type: type,
                data: {
                    labels: labels,
                    datasets: [{
                        label: chartLabel,
                        data: data,
                        backgroundColor: resolutionColors,
                        borderColor: resolutionColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: { label: tooltipWithPercentage }
                        }
                    }
                }
            });
            updateLegend(labels, data, resolutionColors);
        }

        function updateLegend(labels, data, colors) {
            if (!legendContainer) return;
            legendContainer.innerHTML = '';
            labels.forEach(function(label, index) {
                var color = colors[index % colors.length];
                legendContainer.innerHTML += '<li><span style="display:inline-block;width:10px;height:10px;background-color:' + color + ';margin-right:5px;border-radius:50%;"></span><strong>' + label + ': ' + data[index] + ' tickets</strong></li>';
            });
        }

        function filterDailyByRange(startDate, endDate) {
            var labels = [];
            var data = [];
            var start = new Date(startDate);
            var end = new Date(endDate);
            var currentDate = new Date(start);
            
            while (currentDate <= end) {
                var key = currentDate.toISOString().slice(0, 10);
                labels.push(currentDate.toLocaleDateString('id-ID', { weekday: 'short', day: '2-digit', month: '2-digit' }));
                data.push(dailyTicketCounts[key] || 0);
                currentDate.setDate(currentDate.getDate() + 1);
            }
            createResolutionChart('bar', data, labels, 'Daily Tickets');
        }

        function filterDaily() {
            if (dailyFilterContainer) {
                dailyFilterContainer.style.display = 'block';
                filterDailyByRange(startDateInput.value, endDateInput.value);
            }
        }

        function filterWeekly() {
            if (dailyFilterContainer) dailyFilterContainer.style.display = 'none';
            
            var weeklyData = [];
            var weeklyLabels = [];
            var today = new Date();
            today.setHours(0, 0, 0, 0);
            
            for (var i = 3; i >= 0; i--) {
                var endDate = new Date(today);
                endDate.setDate(today.getDate() - (7 * (3 - i)));
                var startDate = new Date(endDate);
                startDate.setDate(endDate.getDate() - 6);
                
                var label = 'Week ' + (i + 1) + ' (' + startDate.toLocaleDateString('id-ID',{day:'2-digit',month:'2-digit'}) + ' - ' + endDate.toLocaleDateString('id-ID',{day:'2-digit',month:'2-digit'}) + ')';
                weeklyLabels.unshift(label);
                
                var total = 0;
                for (var key in dailyTicketCounts) {
                    if (dailyTicketCounts.hasOwnProperty(key)) {
                        var parts = key.split('-');
                        var ticketDate = new Date(parts[0], parts[1] - 1, parts[2]);
                        if (ticketDate >= startDate && ticketDate <= endDate) total += dailyTicketCounts[key];
                    }
                }
                weeklyData.unshift(total);
            }
            createResolutionChart('bar', weeklyData, weeklyLabels, 'Weekly Tickets');
        }

        function filterMonthly() {
            if (dailyFilterContainer) dailyFilterContainer.style.display = 'none';
            var labels = []; 
            var data = [];
            var today = new Date();
            today.setHours(0, 0, 0, 0);
            
            for (var i = 11; i >= 0; i--) {
                var month = new Date(today);
                month.setMonth(today.getMonth() - i);
                var key = month.toISOString().slice(0, 7);
                labels.push(month.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' }));
                data.push(monthlyTicketCounts[key] || 0);
            }
            createResolutionChart('bar', data, labels, 'Monthly Tickets');
        }

        if (dailyBtn) dailyBtn.addEventListener('click', function() { setActiveButton(dailyBtn); filterDaily(); });
        if (weeklyBtn) weeklyBtn.addEventListener('click', function() { setActiveButton(weeklyBtn); filterWeekly(); });
        if (monthlyBtn) monthlyBtn.addEventListener('click', function() { setActiveButton(monthlyBtn); filterMonthly(); });
        
        if (startDateInput) startDateInput.addEventListener('change', function() { filterDailyByRange(startDateInput.value, endDateInput.value); });
        if (endDateInput) endDateInput.addEventListener('change', function() { filterDailyByRange(startDateInput.value, endDateInput.value); });

        var isView = @json($isView);
        var isAdminOrTeknisi = @json($isAdmin || $isTeknisi || $isTeknisiAdmin);
        var isRequestor = @json($isRequestor);

        var ticketStatusChartInstance = null;
        if (isView || isAdminOrTeknisi || isRequestor) {
            ticketStatusChartInstance = createDoughnutChart('ticketStatusChart', ticketStatusChartData);

            setInterval(function updateTicketStatusChart() {
                fetch('{{ route('chart.data') }}')
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        if (ticketStatusChartInstance) {
                            ticketStatusChartInstance.data.datasets[0].data = data.ticketStatusChartData.data;
                            ticketStatusChartInstance.update();

                            var legendItems = document.querySelectorAll('.card-body ul.list-unstyled li');
                            data.ticketStatusChartData.data.forEach(function(val, idx) {
                                if (legendItems[idx]) {
                                    legendItems[idx].querySelector('strong').innerHTML = data.ticketStatusChartData.labels[idx] + ': ' + val;
                                }
                            });
                        }
                    });
            }, 10000);
        }

        if (isAdminOrTeknisi || isRequestor) {
            createDoughnutChart('dandoriManChart', dandoriManChartData);
        }

        if (dailyBtn) {
            filterDaily();
        } else if (isAdminOrTeknisi || isRequestor) {
            createResolutionChart('bar', [], [], '');
        }

        if (isView) {
            var lastTableState = "";
            var tableBody = document.getElementById('dandori-table-body');
            var discoOverlay = document.getElementById('disco-overlay');

            function fetchAndUpdateTable() {
                fetch('{{ route('home.dandories.data') }}')
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    var filteredData = data.filter(function(ticket) { return ticket.status !== 'FINISH'; });
                    
                    var currentTableState = JSON.stringify(filteredData.map(function(ticket) {
                        return {
                            id: ticket.ddcnk_id,
                            status: ticket.status,
                            assigned_to: ticket.assigned_to_name
                        };
                    }).sort(function(a,b) { return a.id.localeCompare(b.id); }));
                    
                    if (lastTableState !== "" && currentTableState !== lastTableState) {
                        if (discoOverlay) {
                            discoOverlay.classList.add('active'); 
                            setTimeout(function() {
                                discoOverlay.classList.remove('active');
                            }, 30000); 
                        }
                    }

                    tableBody.innerHTML = '';
                    filteredData.forEach(function(ticket) {
                        var row = document.createElement('tr');
                        row.setAttribute('data-status', ticket.status);

                        row.innerHTML = 
                            '<td><strong>' + ticket.ddcnk_id + '</strong></td>' +
                            '<td><strong>' + ticket.line_production + '</strong></td>' +
                            '<td><strong>' + ticket.requestor + '</strong></td>' +
                            '<td><strong>' + ticket.customer + '</strong></td>' +
                            '<td><strong>' + ticket.nama_part + '</strong></td>' +
                            '<td><strong>' + ticket.nomor_part + '</strong></td>' +
                            '<td><strong>' + ticket.proses + '</strong></td>' +
                            '<td><strong>' + ticket.mesin + '</strong></td>' +
                            '<td><strong>' + ticket.qty_pcs + '</strong></td>' +
                            '<td><strong>' + ticket.planning_shift + '</strong></td>' +
                            '<td><strong>' + ticket.status + '</strong></td>' +
                            '<td><strong>' + ticket.assigned_to_name + '</strong></td>';
                        tableBody.appendChild(row);
                    });

                    lastTableState = currentTableState;
                })
                .catch(function(error) {
                    console.error('Error fetching dandori tickets:', error);
                });
            }

            fetchAndUpdateTable();
            setInterval(fetchAndUpdateTable, 10000);
        }
    });
</script>
@endsection
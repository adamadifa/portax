@extends('layouts.app')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                 <p class="text-sm font-medium text-slate-500 mb-1">Total Products</p>
                 <h3 class="text-2xl font-bold text-slate-800">1,525</h3>
            </div>
            <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600">
                <i class="fas fa-cube text-lg"></i>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                 <p class="text-sm font-medium text-slate-500 mb-1">Total Sales</p>
                 <h3 class="text-2xl font-bold text-slate-800">10,892</h3>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center text-purple-600">
                <i class="fas fa-dollar-sign text-lg"></i>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                 <p class="text-sm font-medium text-slate-500 mb-1">Total Income</p>
                 <h3 class="text-2xl font-bold text-slate-800">$157,342</h3>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-600">
                <i class="fas fa-arrow-trend-up text-lg"></i>
            </div>
        </div>

         <!-- Card 4 -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                 <p class="text-sm font-medium text-slate-500 mb-1">Total Expenses</p>
                 <h3 class="text-2xl font-bold text-slate-800">$12,453</h3>
            </div>
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-red-600">
                <i class="fas fa-arrow-trend-down text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                     <h3 class="font-bold text-slate-800 text-lg">Sales Revenue</h3>
                     <div class="flex items-center gap-4 mt-1 text-xs">
                         <div class="flex items-center gap-1">
                             <span class="w-2 h-2 rounded-full bg-indigo-200"></span>
                             <span class="text-slate-500">One-Time Revenue</span>
                         </div>
                         <div class="flex items-center gap-1">
                             <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                             <span class="text-slate-500">Recurring Revenue</span>
                         </div>
                     </div>
                </div>
                <!-- Time Filter -->
                <div class="flex bg-slate-100 p-1 rounded-lg">
                    <button class="px-3 py-1 text-xs font-semibold rounded-md bg-white text-slate-800 shadow-sm">Monthly</button>
                    <button class="px-3 py-1 text-xs font-medium text-slate-500 hover:text-slate-700">Quarterly</button>
                    <button class="px-3 py-1 text-xs font-medium text-slate-500 hover:text-slate-700">Yearly</button>
                </div>
            </div>
            
            <!-- Chart Container -->
            <div class="h-64 w-full relative">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Categories Chart -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100">
             <div class="flex items-center justify-between mb-6">
                 <h3 class="font-bold text-slate-800 text-lg">Top Categories</h3>
                 <button class="text-xs font-medium text-slate-500 hover:text-indigo-600 border border-slate-200 px-2 py-1 rounded">See All</button>
             </div>
             
             <!-- Donut Chart -->
             <div class="h-48 relative flex justify-center items-center mb-6">
                 <canvas id="categoryChart"></canvas>
                 <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                     <span class="text-xs text-slate-400">Total Sales</span>
                     <span class="text-xl font-bold text-slate-800">$125k</span>
                 </div>
             </div>

             <!-- Legend -->
             <div class="space-y-3">
                 <div class="flex items-center justify-between text-sm">
                     <div class="flex items-center gap-2">
                         <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                         <span class="text-slate-600">Electronics</span>
                     </div>
                     <div class="flex items-center gap-4">
                         <span class="font-medium text-slate-800">$85,000</span>
                         <span class="text-slate-400 text-xs w-8 text-right">68%</span>
                     </div>
                 </div>
                 <div class="flex items-center justify-between text-sm">
                     <div class="flex items-center gap-2">
                         <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                         <span class="text-slate-600">Fashion</span>
                     </div>
                     <div class="flex items-center gap-4">
                         <span class="font-medium text-slate-800">$25,000</span>
                         <span class="text-slate-400 text-xs w-8 text-right">20%</span>
                     </div>
                 </div>
                  <div class="flex items-center justify-between text-sm">
                     <div class="flex items-center gap-2">
                         <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                         <span class="text-slate-600">Health & Wellness</span>
                     </div>
                     <div class="flex items-center gap-4">
                         <span class="font-medium text-slate-800">$10,000</span>
                         <span class="text-slate-400 text-xs w-8 text-right">8%</span>
                     </div>
                 </div>
                  <div class="flex items-center justify-between text-sm">
                     <div class="flex items-center gap-2">
                         <span class="w-2 h-2 rounded-full bg-purple-400"></span>
                         <span class="text-slate-600">Home & Living</span>
                     </div>
                     <div class="flex items-center gap-4">
                         <span class="font-medium text-slate-800">$5,000</span>
                         <span class="text-slate-400 text-xs w-8 text-right">4%</span>
                     </div>
                 </div>
             </div>
        </div>
    </div>

    <!-- Bottom Section: Recent Activity & Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Activity -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100">
             <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <i class="far fa-clock text-slate-400"></i>
                    <h3 class="font-bold text-slate-800 text-lg">Recent Activity</h3>
                </div>
                 <button class="text-xs font-medium text-slate-500 hover:text-indigo-600">See All</button>
             </div>

             <div class="space-y-4">
                 <!-- Activity Item -->
                 <div class="flex items-start gap-4 p-3 hover:bg-slate-50 rounded-lg transition-colors cursor-pointer group">
                     <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-100 transition-colors">
                         <i class="far fa-user-circle"></i>
                     </div>
                     <div class="flex-1">
                         <div class="flex justify-between items-start">
                            <div>
                                 <h4 class="text-sm font-semibold text-slate-800">Order #2048</h4>
                                 <p class="text-xs text-slate-500 mt-0.5">John Doe • 12 Jan 25</p>
                            </div>
                            <span class="px-2 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase rounded">New Order</span>
                         </div>
                     </div>
                 </div>

                 <!-- Activity Item -->
                 <div class="flex items-start gap-4 p-3 hover:bg-slate-50 rounded-lg transition-colors cursor-pointer group">
                     <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0 group-hover:bg-red-100 transition-colors">
                         <i class="fas fa-cube"></i>
                     </div>
                     <div class="flex-1">
                         <div class="flex justify-between items-start">
                            <div>
                                 <h4 class="text-sm font-semibold text-slate-800">Low Stock Alert</h4>
                                 <p class="text-xs text-slate-500 mt-0.5">MacBook Air M2 • 10 Jan 25</p>
                            </div>
                            <span class="px-2 py-1 bg-red-50 text-red-600 text-[10px] font-bold uppercase rounded">Low Stock</span>
                         </div>
                     </div>
                 </div>

                  <!-- Activity Item -->
                 <div class="flex items-start gap-4 p-3 hover:bg-slate-50 rounded-lg transition-colors cursor-pointer group">
                     <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0 group-hover:bg-purple-100 transition-colors">
                         <i class="fas fa-ticket-alt"></i>
                     </div>
                     <div class="flex-1">
                         <div class="flex justify-between items-start">
                            <div>
                                 <h4 class="text-sm font-semibold text-slate-800">Promo code "SUMMER20"</h4>
                                 <p class="text-xs text-slate-500 mt-0.5">Applied 52 times • 8 Jan 25</p>
                            </div>
                            <span class="px-2 py-1 bg-purple-50 text-purple-600 text-[10px] font-bold uppercase rounded">Campaign</span>
                         </div>
                     </div>
                 </div>
                 
                   <!-- Activity Item -->
                 <div class="flex items-start gap-4 p-3 hover:bg-slate-50 rounded-lg transition-colors cursor-pointer group">
                     <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0 group-hover:bg-slate-200 transition-colors">
                         <i class="fas fa-server"></i>
                     </div>
                     <div class="flex-1">
                         <div class="flex justify-between items-start">
                            <div>
                                 <h4 class="text-sm font-semibold text-slate-800">System Update</h4>
                                 <p class="text-xs text-slate-500 mt-0.5">Version 1.2.1 • 2 Jan 25</p>
                            </div>
                            <span class="px-2 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase rounded">System</span>
                         </div>
                     </div>
                 </div>
             </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <i class="fas fa-cube text-slate-400"></i>
                    <h3 class="font-bold text-slate-800 text-lg">Top Products</h3>
                </div>
                 <div class="flex gap-2">
                    <button class="flex items-center gap-1 px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50">
                        <i class="fas fa-sort-amount-down"></i> Sort
                    </button>
                    <button class="flex items-center gap-1 px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                 </div>
             </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs text-slate-400 border-b border-slate-50">
                            <th class="font-medium py-3 pl-2">Product</th>
                             <th class="font-medium py-3 text-center">Stocks</th>
                              <th class="font-medium py-3 text-right">Price</th>
                               <th class="font-medium py-3 text-center">Sales</th>
                                <th class="font-medium py-3 text-right pr-2">Earnings</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <!-- Row 1 -->
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 pl-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center text-slate-500">
                                        <i class="fas fa-mobile-alt"></i>
                                    </div>
                                    <span class="font-medium text-slate-700">iPhone 15 Pro</span>
                                </div>
                            </td>
                            <td class="py-3 text-center text-slate-500">6,200</td>
                            <td class="py-3 text-right text-slate-700">$999.00</td>
                            <td class="py-3 text-center text-slate-500">4,800</td>
                            <td class="py-3 text-right pr-2 font-medium text-slate-800">$4,795,200</td>
                        </tr>
                        <!-- Row 2 -->
                         <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 pl-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center text-slate-500">
                                        <i class="fas fa-laptop"></i>
                                    </div>
                                    <span class="font-medium text-slate-700">MacBook Air M2</span>
                                </div>
                            </td>
                            <td class="py-3 text-center text-slate-500">1,020</td>
                            <td class="py-3 text-right text-slate-700">$1,299</td>
                            <td class="py-3 text-center text-slate-500">3,200</td>
                            <td class="py-3 text-right pr-2 font-medium text-slate-800">$4,156,800</td>
                        </tr>
                         <!-- Row 3 -->
                         <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 pl-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center text-slate-500">
                                        <i class="fab fa-google"></i>
                                    </div>
                                    <span class="font-medium text-slate-700">Google Pixel 8</span>
                                </div>
                            </td>
                            <td class="py-3 text-center text-slate-500">1,500</td>
                            <td class="py-3 text-right text-slate-700">$699.00</td>
                            <td class="py-3 text-center text-slate-500">800</td>
                            <td class="py-3 text-right pr-2 font-medium text-slate-800">$559,200</td>
                        </tr>
                         <!-- Row 4 -->
                         <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 pl-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center text-slate-500">
                                        <i class="fas fa-shoe-prints"></i>
                                    </div>
                                    <span class="font-medium text-slate-700">Nike Air Max 90</span>
                                </div>
                            </td>
                            <td class="py-3 text-center text-slate-500">2,400</td>
                            <td class="py-3 text-right text-slate-700">$130.00</td>
                            <td class="py-3 text-center text-slate-500">1,800</td>
                            <td class="py-3 text-right pr-2 font-medium text-slate-800">$234,000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue Chart (Bar Chart)
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
                datasets: [
                    {
                        label: 'One-Time Revenue',
                        data: [100000, 35000, 5000, 8000, 15000, 10000, 5000, 8000],
                        backgroundColor: '#E0E7FF', // Light Indigo
                        borderRadius: 4,
                        barThickness: 24,
                        barPercentage: 0.6,
                    },
                    {
                        label: 'Recurring Revenue',
                        data: [50000, 20000, 60000, 110000, 10000, 40000, 120000, 50000],
                        backgroundColor: '#4F46E5', // Indigo 600
                        borderRadius: 4,
                        barThickness: 24,
                        barPercentage: 0.6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#1e293b',
                        bodyColor: '#475569',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: true,
                        callbacks: {
                             label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#94a3b8' }
                    },
                    y: {
                        grid: { color: '#f1f5f9', borderDash: [2, 2], drawBorder: false },
                        ticks: { 
                            color: '#94a3b8',
                            callback: function(value) { return value >= 1000 ? value/1000 + 'k' : value; }
                        }
                    }
                }
            }
        });

        // Category Chart (Donut Chart)
        const ctxCategory = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctxCategory, {
            type: 'doughnut',
            data: {
                labels: ['Electronics', 'Fashion', 'Health & Wellness', 'Home & Living'],
                datasets: [{
                    data: [68, 20, 8, 4],
                    backgroundColor: [
                        '#2563EB', // Blue 600
                        '#6366F1', // Indigo 500
                        '#FACC15', // Yellow 400
                        '#C084FC'  // Purple 400
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                         callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection

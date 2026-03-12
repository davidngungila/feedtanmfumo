<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIA Verified Payments - Feedtan CMG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .tab-active {
            border-bottom: 3px solid #015425;
            color: #015425;
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .loading-overlay {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .stat-card-green {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        .stat-card-red {
            background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
        }
        .stat-card-blue {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .table-hover tbody tr:hover {
            background-color: #f3f4f6;
        }
        .badge-success {
            background-color: #10b981;
        }
        .badge-pending {
            background-color: #f59e0b;
        }
        .badge-rejected {
            background-color: #ef4444;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-green-600 text-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <span class="text-green-600 font-bold text-lg">FC</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">FEEDTAN CMG</h1>
                        <p class="text-green-100 text-sm">FIA Verified Payments Dashboard</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="exportData()" class="bg-white text-green-600 px-4 py-2 rounded-md hover:bg-green-50 transition-colors duration-200 font-medium">
                        <i class="fas fa-download mr-2"></i>
                        Export
                    </button>
                    <button onclick="refreshData()" class="bg-green-700 text-white px-4 py-2 rounded-md hover:bg-green-800 transition-colors duration-200">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm">Total Verified</p>
                        <p class="text-3xl font-bold" id="total-verified">0</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card-green text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm">Total Amount</p>
                        <p class="text-3xl font-bold" id="total-amount">TSh 0</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card-blue text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm">This Month</p>
                        <p class="text-3xl font-bold" id="monthly-verified">0</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card-red text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm">Pending Review</p>
                        <p class="text-3xl font-bold" id="pending-review">0</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Verification Trend</h3>
                <canvas id="monthly-trend-chart" width="400" height="200"></canvas>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Methods Distribution</h3>
                <canvas id="payment-methods-chart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-wrap gap-4 items-center">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <div class="relative">
                        <input type="text" id="search-input" placeholder="Search by name, reference, or email..."
                            class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                
                <div class="min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                    <input type="date" id="date-from" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                
                <div class="min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                    <input type="date" id="date-to" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                
                <div class="min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select id="payment-method-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">All Methods</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cash">Cash</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">All Status</option>
                        <option value="verified">Verified</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button onclick="applyFilters()" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200">
                        <i class="fas fa-filter mr-2"></i>
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Verified Payments</h3>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">
                            Showing <span id="showing-count">0</span> of <span id="total-count">0</span> payments
                        </span>
                        <div class="flex items-center space-x-2">
                            <label class="text-sm text-gray-600">Per page:</label>
                            <select id="per-page" onchange="changePerPage()" class="px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="10">10</option>
                                <option value="25" selected>25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 table-hover">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('id')">
                                S/N <i class="fas fa-sort text-gray-400 ml-1"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('name')">
                                Name <i class="fas fa-sort text-gray-400 ml-1"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Phone
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('payment_reference')">
                                Reference <i class="fas fa-sort text-gray-400 ml-1"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('amount')">
                                Amount <i class="fas fa-sort text-gray-400 ml-1"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('payment_date')">
                                Payment Date <i class="fas fa-sort text-gray-400 ml-1"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Method
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable('verified_at')">
                                Verified Date <i class="fas fa-sort text-gray-400 ml-1"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="payments-tbody" class="bg-white divide-y divide-gray-200">
                        <!-- Payment rows will be loaded here -->
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="p-6 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-700">
                        Page <span id="current-page">1</span> of <span id="total-pages">1</span>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="goToPage(1)" class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50" id="first-page">
                            <i class="fas fa-angle-double-left"></i>
                        </button>
                        <button onclick="goToPage(currentPage - 1)" class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50" id="prev-page">
                            <i class="fas fa-angle-left"></i>
                        </button>
                        <span class="px-4 py-1 border border-gray-300 rounded-md bg-green-600 text-white" id="page-info">1</span>
                        <button onclick="goToPage(currentPage + 1)" class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50" id="next-page">
                            <i class="fas fa-angle-right"></i>
                        </button>
                        <button onclick="goToPage(totalPages)" class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50" id="last-page">
                            <i class="fas fa-angle-double-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Payment Details Modal -->
    <div id="payment-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Payment Details</h3>
                <button onclick="closeModal('payment-modal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="payment-details-content">
                <!-- Payment details will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 loading-overlay hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 text-center">
            <i class="fas fa-spinner fa-spin text-4xl text-green-600 mb-4"></i>
            <p class="text-lg font-medium text-gray-800">Loading...</p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="container mx-auto px-4 py-6">
            <div class="text-center">
                <div class="mb-2">
                    <span class="font-bold">FEEDTAN CMG</span>
                    <span class="text-gray-400">|</span>
                    <span class="text-sm">Feedtan Community Microfinance Group</span>
                </div>
                <p class="text-gray-400 text-sm">Powered by Feedtan CMG @2026 SECURED PAYMENT GATEWAY</p>
            </div>
        </div>
    </footer>

    <script>
        // Global variables
        let currentPage = 1;
        let totalPages = 1;
        let allPayments = [];
        let filteredPayments = [];
        let sortField = 'verified_at';
        let sortDirection = 'desc';
        let monthlyTrendChart = null;
        let paymentMethodsChart = null;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadVerifiedPayments();
            
            // Set today's date as default for date filters
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date-to').value = today;
            
            // Add search event listener
            document.getElementById('search-input').addEventListener('input', debounce(applyFilters, 300));
        });

        // Load verified payments
        async function loadVerifiedPayments() {
            showLoading(true);
            
            try {
                const response = await fetch('/fia/verified-payments-data');
                const result = await response.json();
                
                if (result.status === 'success') {
                    allPayments = result.data;
                    filteredPayments = [...allPayments];
                    updateTable();
                    updatePagination();
                    loadStatistics();
                    loadCharts();
                } else {
                    showError('Failed to load verified payments');
                }
            } catch (error) {
                console.error('Error loading payments:', error);
                showError('Error loading verified payments');
            } finally {
                showLoading(false);
            }
        }

        // Load statistics
        async function loadStatistics() {
            try {
                // Calculate statistics from loaded payments
                const totalVerified = allPayments.length;
                const totalAmount = allPayments.reduce((sum, p) => sum + parseFloat(p.amount || 0), 0);
                const currentMonth = new Date().getMonth();
                const currentYear = new Date().getFullYear();
                const monthlyVerified = allPayments.filter(p => {
                    const date = new Date(p.verified_at);
                    return date.getMonth() === currentMonth && date.getFullYear() === currentYear;
                }).length;
                
                // Update statistics cards
                document.getElementById('total-verified').textContent = totalVerified.toLocaleString();
                document.getElementById('total-amount').textContent = `TSh ${totalAmount.toLocaleString()}`;
                document.getElementById('monthly-verified').textContent = monthlyVerified.toLocaleString();
                document.getElementById('pending-review').textContent = '0'; // No pending in verified payments
            } catch (error) {
                console.error('Error loading statistics:', error);
            }
        }

        // Load charts
        function loadCharts() {
            loadMonthlyTrendChart();
            loadPaymentMethodsChart();
        }

        // Load monthly trend chart
        function loadMonthlyTrendChart() {
            const ctx = document.getElementById('monthly-trend-chart').getContext('2d');
            
            // Process data for last 6 months
            const monthlyData = processMonthlyData();
            
            monthlyTrendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthlyData.labels,
                    datasets: [{
                        label: 'Verified Payments',
                        data: monthlyData.data,
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Load payment methods chart
        function loadPaymentMethodsChart() {
            const ctx = document.getElementById('payment-methods-chart').getContext('2d');
            
            // Process payment methods data
            const methodsData = processPaymentMethodsData();
            
            paymentMethodsChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: methodsData.labels,
                    datasets: [{
                        data: methodsData.data,
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Process monthly data for chart
        function processMonthlyData() {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
            const currentMonth = new Date().getMonth();
            const data = [];
            const labels = [];
            
            for (let i = 5; i >= 0; i--) {
                const monthIndex = (currentMonth - i + 12) % 12;
                const year = currentMonth >= i ? new Date().getFullYear() : new Date().getFullYear() - 1;
                
                const count = allPayments.filter(p => {
                    const date = new Date(p.verified_at);
                    return date.getMonth() === monthIndex && date.getFullYear() === year;
                }).length;
                
                labels.push(months[monthIndex]);
                data.push(count);
            }
            
            return { labels, data };
        }

        // Process payment methods data for chart
        function processPaymentMethodsData() {
            const methods = {};
            
            allPayments.forEach(payment => {
                const method = payment.payment_method || 'other';
                const label = getPaymentMethodLabel(method);
                methods[label] = (methods[label] || 0) + 1;
            });
            
            return {
                labels: Object.keys(methods),
                data: Object.values(methods)
            };
        }

        // Get payment method label
        function getPaymentMethodLabel(method) {
            const labels = {
                'mobile_money': 'Mobile Money',
                'bank_transfer': 'Bank Transfer',
                'cash': 'Cash',
                'other': 'Other'
            };
            return labels[method] || method;
        }

        // Update table
        function updateTable() {
            const tbody = document.getElementById('payments-tbody');
            const startIndex = (currentPage - 1) * parseInt(document.getElementById('per-page').value);
            const endIndex = startIndex + parseInt(document.getElementById('per-page').value);
            const pageData = filteredPayments.slice(startIndex, endIndex);
            
            tbody.innerHTML = pageData.map((payment, index) => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${startIndex + index + 1}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-green-600 text-xs font-semibold">${payment.name.charAt(0).toUpperCase()}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">${payment.name}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.phone}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.email}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            ${payment.payment_reference}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-semibold text-gray-900">TSh ${parseFloat(payment.amount).toLocaleString()}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.payment_date}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getPaymentMethodBadgeClass(payment.payment_method)}">
                            ${getPaymentMethodLabel(payment.payment_method)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${payment.verified_at ? new Date(payment.verified_at).toLocaleDateString() : '-'}</div>
                        <div class="text-xs text-gray-500">${payment.verified_at ? new Date(payment.verified_at).toLocaleTimeString() : '-'}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="viewPaymentDetails(${startIndex + index})" class="text-green-600 hover:text-green-900 mr-3">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="downloadReceipt(${startIndex + index})" class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-download"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
            
            // Update counts
            document.getElementById('showing-count').textContent = pageData.length;
            document.getElementById('total-count').textContent = filteredPayments.length;
        }

        // Get payment method badge class
        function getPaymentMethodBadgeClass(method) {
            const classes = {
                'mobile_money': 'bg-green-100 text-green-800',
                'bank_transfer': 'bg-blue-100 text-blue-800',
                'cash': 'bg-yellow-100 text-yellow-800',
                'other': 'bg-gray-100 text-gray-800'
            };
            return classes[method] || classes.other;
        }

        // Apply filters
        function applyFilters() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const dateFrom = document.getElementById('date-from').value;
            const dateTo = document.getElementById('date-to').value;
            const paymentMethod = document.getElementById('payment-method-filter').value;
            const status = document.getElementById('status-filter').value;
            
            filteredPayments = allPayments.filter(payment => {
                // Search filter
                if (searchTerm) {
                    const matchesSearch = payment.name.toLowerCase().includes(searchTerm) ||
                                        payment.email.toLowerCase().includes(searchTerm) ||
                                        payment.payment_reference.toLowerCase().includes(searchTerm);
                    if (!matchesSearch) return false;
                }
                
                // Date filters
                if (dateFrom && new Date(payment.verified_at) < new Date(dateFrom)) return false;
                if (dateTo && new Date(payment.verified_at) > new Date(dateTo + 'T23:59:59')) return false;
                
                // Payment method filter
                if (paymentMethod && payment.payment_method !== paymentMethod) return false;
                
                // Status filter (all verified payments have status 'verified')
                if (status && status !== 'verified') return false;
                
                return true;
            });
            
            currentPage = 1;
            updateTable();
            updatePagination();
        }

        // Sort table
        function sortTable(field) {
            if (sortField === field) {
                sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                sortField = field;
                sortDirection = 'asc';
            }
            
            filteredPayments.sort((a, b) => {
                let aVal = a[field];
                let bVal = b[field];
                
                // Handle date fields
                if (field.includes('date') || field.includes('at')) {
                    aVal = new Date(aVal);
                    bVal = new Date(bVal);
                }
                
                // Handle numeric fields
                if (field === 'amount') {
                    aVal = parseFloat(aVal);
                    bVal = parseFloat(bVal);
                }
                
                if (sortDirection === 'asc') {
                    return aVal > bVal ? 1 : aVal < bVal ? -1 : 0;
                } else {
                    return aVal < bVal ? 1 : aVal > bVal ? -1 : 0;
                }
            });
            
            updateTable();
        }

        // Update pagination
        function updatePagination() {
            const perPage = parseInt(document.getElementById('per-page').value);
            totalPages = Math.ceil(filteredPayments.length / perPage);
            
            document.getElementById('current-page').textContent = currentPage;
            document.getElementById('total-pages').textContent = totalPages;
            document.getElementById('page-info').textContent = currentPage;
            
            // Update button states
            document.getElementById('first-page').disabled = currentPage === 1;
            document.getElementById('prev-page').disabled = currentPage === 1;
            document.getElementById('next-page').disabled = currentPage === totalPages;
            document.getElementById('last-page').disabled = currentPage === totalPages;
        }

        // Go to page
        function goToPage(page) {
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                updateTable();
                updatePagination();
            }
        }

        // Change per page
        function changePerPage() {
            currentPage = 1;
            updateTable();
            updatePagination();
        }

        // View payment details
        function viewPaymentDetails(index) {
            const payment = filteredPayments[index];
            
            const content = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-4">Payment Information</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Reference:</span>
                                <span class="font-medium">${payment.payment_reference}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Amount:</span>
                                <span class="font-semibold text-green-600">TSh ${parseFloat(payment.amount).toLocaleString()}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Date:</span>
                                <span class="font-medium">${payment.payment_date}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method:</span>
                                <span class="font-medium">${getPaymentMethodLabel(payment.payment_method)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Verified Date:</span>
                                <span class="font-medium">${payment.verified_at ? new Date(payment.verified_at).toLocaleString() : '-'}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-4">Member Information</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-medium">${payment.name}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phone:</span>
                                <span class="font-medium">${payment.phone}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium">${payment.email}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                ${payment.description ? `
                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-800 mb-2">Description</h4>
                        <p class="text-gray-600">${payment.description}</p>
                    </div>
                ` : ''}
            `;
            
            document.getElementById('payment-details-content').innerHTML = content;
            document.getElementById('payment-modal').classList.remove('hidden');
            document.getElementById('payment-modal').classList.add('flex');
        }

        // Download receipt
        function downloadReceipt(index) {
            const payment = filteredPayments[index];
            
            // Create receipt content
            const receiptContent = `
                FIA Payment Receipt
                ==================
                
                Reference: ${payment.payment_reference}
                Name: ${payment.name}
                Amount: TSh ${parseFloat(payment.amount).toLocaleString()}
                Payment Date: ${payment.payment_date}
                Payment Method: ${getPaymentMethodLabel(payment.payment_method)}
                Verified Date: ${payment.verified_at ? new Date(payment.verified_at).toLocaleString() : '-'}
                
                This receipt confirms that the above payment has been verified and processed.
                
                Powered by Feedtan CMG @2026 SECURED PAYMENT GATEWAY
            `;
            
            // Create and download file
            const blob = new Blob([receiptContent], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `FIA_Receipt_${payment.payment_reference}.txt`;
            a.click();
            window.URL.revokeObjectURL(url);
        }

        // Export data
        function exportData() {
            const csvContent = [
                ['S/N', 'Name', 'Phone', 'Email', 'Reference', 'Amount', 'Payment Date', 'Method', 'Verified Date'],
                ...filteredPayments.map((payment, index) => [
                    index + 1,
                    payment.name,
                    payment.phone,
                    payment.email,
                    payment.payment_reference,
                    payment.amount,
                    payment.payment_date,
                    getPaymentMethodLabel(payment.payment_method),
                    payment.verified_at ? new Date(payment.verified_at).toLocaleString() : '-'
                ])
            ].map(row => row.join(',')).join('\n');
            
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `FIA_Verified_Payments_${new Date().toISOString().split('T')[0]}.csv`;
            a.click();
            window.URL.revokeObjectURL(url);
        }

        // Refresh data
        function refreshData() {
            loadVerifiedPayments();
            loadStatistics();
            loadCharts();
        }

        // Close modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.getElementById(modalId).classList.remove('flex');
        }

        // Show/hide loading
        function showLoading(show) {
            const overlay = document.getElementById('loading-overlay');
            if (show) {
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
            } else {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
            }
        }

        // Show error
        function showError(message) {
            alert(message);
        }

        // Debounce function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    </script>
</body>
</html>

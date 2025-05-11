<?php
session_start();
require_once "../utils/user.php";

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isAdmin($_SESSION['user_id'])) {
    // Redirect to login page if not logged in or not an admin
    header("Location: ../auth/login.php");
    exit();
}

// Get current user information
$userId = $_SESSION['user_id'];
$userInfo = getUserInfo($userId);

// Sample activity logs data - in a real application, this would come from a database
$activity_logs = [
    [
        'id' => 1,
        'user_id' => 2,
        'user_name' => 'John Smith',
        'action' => 'Submitted a new article',
        'timestamp' => '2023-05-22 09:15:30',
        'time_ago' => '10 minutes ago',
        'ip_address' => '192.168.1.1',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    [
        'id' => 2,
        'user_id' => 1,
        'user_name' => 'Admin User',
        'action' => 'Approved article #1045',
        'timestamp' => '2023-05-22 09:00:00',
        'time_ago' => '25 minutes ago',
        'ip_address' => '192.168.1.2',
        'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15'
    ],
    [
        'id' => 3,
        'user_id' => 3,
        'user_name' => 'Jane Doe',
        'action' => 'Updated profile information',
        'timestamp' => '2023-05-22 08:45:00',
        'time_ago' => '40 minutes ago',
        'ip_address' => '192.168.1.3',
        'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15'
    ],
    [
        'id' => 4,
        'user_id' => 1,
        'user_name' => 'Admin User',
        'action' => 'Rejected article #1042 as fake news',
        'timestamp' => '2023-05-22 08:30:00',
        'time_ago' => '55 minutes ago',
        'ip_address' => '192.168.1.2',
        'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15'
    ],
    [
        'id' => 5,
        'user_id' => 4,
        'user_name' => 'Mike Johnson',
        'action' => 'Created a new account',
        'timestamp' => '2023-05-22 08:00:00',
        'time_ago' => '1 hour ago',
        'ip_address' => '192.168.1.4',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    [
        'id' => 6,
        'user_id' => 5,
        'user_name' => 'Sarah Williams',
        'action' => 'Submitted a new article',
        'timestamp' => '2023-05-22 07:30:00',
        'time_ago' => '1 hour 30 minutes ago',
        'ip_address' => '192.168.1.5',
        'user_agent' => 'Mozilla/5.0 (iPad; CPU OS 15_0 like Mac OS X) AppleWebKit/605.1.15'
    ],
    [
        'id' => 7,
        'user_id' => 1,
        'user_name' => 'Admin User',
        'action' => 'Changed user role for user #3',
        'timestamp' => '2023-05-22 07:15:00',
        'time_ago' => '1 hour 45 minutes ago',
        'ip_address' => '192.168.1.2',
        'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15'
    ],
    [
        'id' => 8,
        'user_id' => 6,
        'user_name' => 'Michael Brown',
        'action' => 'Logged in',
        'timestamp' => '2023-05-22 07:00:00',
        'time_ago' => '2 hours ago',
        'ip_address' => '192.168.1.6',
        'user_agent' => 'Mozilla/5.0 (Linux; Android 11; SM-G991B) AppleWebKit/537.36'
    ],
    [
        'id' => 9,
        'user_id' => 7,
        'user_name' => 'Emily Davis',
        'action' => 'Updated article #1032',
        'timestamp' => '2023-05-22 06:45:00',
        'time_ago' => '2 hours 15 minutes ago',
        'ip_address' => '192.168.1.7',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    [
        'id' => 10,
        'user_id' => 1,
        'user_name' => 'Admin User',
        'action' => 'Approved article #1032',
        'timestamp' => '2023-05-22 06:30:00',
        'time_ago' => '2 hours 30 minutes ago',
        'ip_address' => '192.168.1.2',
        'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15'
    ],
    [
        'id' => 11,
        'user_id' => 8,
        'user_name' => 'David Miller',
        'action' => 'Changed password',
        'timestamp' => '2023-05-22 06:15:00',
        'time_ago' => '2 hours 45 minutes ago',
        'ip_address' => '192.168.1.8',
        'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15'
    ],
    [
        'id' => 12,
        'user_id' => 2,
        'user_name' => 'John Smith',
        'action' => 'Commented on article #1028',
        'timestamp' => '2023-05-22 06:00:00',
        'time_ago' => '3 hours ago',
        'ip_address' => '192.168.1.1',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ]
];

// Include the header
include '../includes/admin_header.php';
?>

<!-- Main Content -->
<div class="main-content">
    <div class="page-header">
        <h1 class="header">Activity Logs</h1>
    </div>
    
    <!-- Activity Overview -->
    <div class="charts-container">
        <div class="chart-card">
            <h2>Activity Trends</h2>
            <canvas id="activityTrendsChart"></canvas>
        </div>
        
        <div class="chart-card">
            <h2>User Activity Distribution</h2>
            <canvas id="userActivityChart"></canvas>
        </div>
    </div>
    
    <!-- Search and Filter -->
    <div class="filter-bar">
        <div class="search-container">
            <input type="text" id="activitySearch" class="search-input" placeholder="Search activity logs...">
            <button class="search-button"><i class="fas fa-search"></i></button>
        </div>
        
        <div class="filter-options">
            <select id="userFilter" class="filter-select">
                <option value="">All Users</option>
                <option value="Admin User">Admin User</option>
                <option value="John Smith">John Smith</option>
                <option value="Jane Doe">Jane Doe</option>
                <option value="Mike Johnson">Mike Johnson</option>
                <option value="Sarah Williams">Sarah Williams</option>
                <option value="Michael Brown">Michael Brown</option>
                <option value="Emily Davis">Emily Davis</option>
                <option value="David Miller">David Miller</option>
            </select>
            
            <select id="actionFilter" class="filter-select">
                <option value="">All Actions</option>
                <option value="article">Article Actions</option>
                <option value="user">User Account Actions</option>
                <option value="login">Login/Logout</option>
            </select>
            
            <div class="date-filter">
                <input type="date" id="dateFrom" class="date-input" title="From Date">
                <span>to</span>
                <input type="date" id="dateTo" class="date-input" title="To Date">
                <button id="applyDateFilter" class="filter-button">Apply</button>
            </div>
        </div>
    </div>
    
    <!-- Activity Logs Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="data-table" id="activityTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Timestamp</th>
                        <th>IP Address</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activity_logs as $log): ?>
                        <tr data-id="<?php echo $log['id']; ?>">
                            <td><?php echo $log['id']; ?></td>
                            <td>
                                <a href="users.php?id=<?php echo $log['user_id']; ?>" class="user-link">
                                    <?php echo htmlspecialchars($log['user_name']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($log['action']); ?></td>
                            <td title="<?php echo $log['timestamp']; ?>"><?php echo $log['time_ago']; ?></td>
                            <td><?php echo $log['ip_address']; ?></td>
                            <td>
                                <button class="action-btn view" title="View Details" data-id="<?php echo $log['id']; ?>">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-container">
            <div class="pagination">
                <button class="pagination-btn" disabled><i class="fas fa-chevron-left"></i></button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <button class="pagination-btn">3</button>
                <span class="pagination-ellipsis">...</span>
                <button class="pagination-btn">10</button>
                <button class="pagination-btn"><i class="fas fa-chevron-right"></i></button>
            </div>
            
            <div class="pagination-info">
                Showing 1-12 of 120 entries
            </div>
        </div>
    </div>
</div>

<!-- Activity Details Modal -->
<div class="modal-overlay" id="activityDetailsModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Activity Details</h2>
            <button class="modal-close" id="closeDetailsModal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">User</div>
                    <div class="detail-value" id="detailUser">-</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Action</div>
                    <div class="detail-value" id="detailAction">-</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Timestamp</div>
                    <div class="detail-value" id="detailTimestamp">-</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">IP Address</div>
                    <div class="detail-value" id="detailIP">-</div>
                </div>
                
                <div class="detail-item full-width">
                    <div class="detail-label">User Agent</div>
                    <div class="detail-value" id="detailUserAgent">-</div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-secondary" id="closeDetails">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ChartJS for visualizations -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sample data for charts
    const activityTrendsData = {
        labels: ['00:00', '02:00', '04:00', '06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00'],
        values: [5, 3, 2, 8, 15, 20, 18, 25, 22, 18, 10, 6]
    };
    
    const userActivityData = {
        labels: ['Admin User', 'John Smith', 'Jane Doe', 'Mike Johnson', 'Sarah Williams', 'Others'],
        values: [42, 18, 12, 8, 7, 33]
    };
    
    // Activity Trends Chart
    const trendsCtx = document.getElementById('activityTrendsChart').getContext('2d');
    const trendsChart = new Chart(trendsCtx, {
        type: 'line',
        data: {
            labels: activityTrendsData.labels,
            datasets: [{
                label: 'Activity Count',
                data: activityTrendsData.values,
                fill: true,
                backgroundColor: 'rgba(114, 46, 209, 0.2)',
                borderColor: 'rgba(114, 46, 209, 1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
    
    // User Activity Chart
    const userCtx = document.getElementById('userActivityChart').getContext('2d');
    const userChart = new Chart(userCtx, {
        type: 'pie',
        data: {
            labels: userActivityData.labels,
            datasets: [{
                data: userActivityData.values,
                backgroundColor: [
                    '#722ed1',
                    '#2196f3',
                    '#4caf50',
                    '#ff9800',
                    '#f44336',
                    '#9e9e9e'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
    
    // Modal elements
    const activityDetailsModal = document.getElementById('activityDetailsModal');
    const closeDetailsModal = document.getElementById('closeDetailsModal');
    const closeDetails = document.getElementById('closeDetails');
    const detailUser = document.getElementById('detailUser');
    const detailAction = document.getElementById('detailAction');
    const detailTimestamp = document.getElementById('detailTimestamp');
    const detailIP = document.getElementById('detailIP');
    const detailUserAgent = document.getElementById('detailUserAgent');
    
    // Search and filter elements
    const activitySearch = document.getElementById('activitySearch');
    const userFilter = document.getElementById('userFilter');
    const actionFilter = document.getElementById('actionFilter');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const applyDateFilter = document.getElementById('applyDateFilter');
    
    // View Activity Details
    document.querySelectorAll('.action-btn.view').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            // In a real app, you would fetch activity data from the server
            // For demo, we'll use the sample data
            const activityData = getActivityData(id);
            
            if (activityData) {
                detailUser.textContent = activityData.user_name;
                detailAction.textContent = activityData.action;
                detailTimestamp.textContent = activityData.timestamp;
                detailIP.textContent = activityData.ip_address;
                detailUserAgent.textContent = activityData.user_agent;
                
                openModal(activityDetailsModal);
            }
        });
    });
    
    // Close Modals
    closeDetailsModal.addEventListener('click', () => closeModal(activityDetailsModal));
    closeDetails.addEventListener('click', () => closeModal(activityDetailsModal));
    
    // Search and Filter Functionality
    activitySearch.addEventListener('input', filterTable);
    userFilter.addEventListener('change', filterTable);
    actionFilter.addEventListener('change', filterTable);
    applyDateFilter.addEventListener('click', filterTable);
    
    // Pagination buttons (for demo)
    document.querySelectorAll('.pagination-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (!this.classList.contains('active') && !this.disabled) {
                document.querySelector('.pagination-btn.active').classList.remove('active');
                this.classList.add('active');
                // In a real app, this would load different page data
            }
        });
    });
    
    // Helper Functions
    function openModal(modal) {
        modal.classList.add('open');
    }
    
    function closeModal(modal) {
        modal.classList.remove('open');
    }
    
    function getActivityData(id) {
        // In a real app, this would be an AJAX request
        // For demo, we search through our sample data
        return <?php echo json_encode($activity_logs); ?>.find(log => log.id == id);
    }
    
    function filterTable() {
        const searchTerm = activitySearch.value.toLowerCase();
        const userValue = userFilter.value;
        const actionValue = actionFilter.value;
        const fromDate = dateFrom.value ? new Date(dateFrom.value) : null;
        const toDate = dateTo.value ? new Date(dateTo.value) : null;
        
        const rows = document.querySelectorAll('#activityTable tbody tr');
        
        rows.forEach(row => {
            const user = row.cells[1].textContent.trim();
            const action = row.cells[2].textContent.toLowerCase();
            const timestamp = row.cells[3].getAttribute('title');
            const date = new Date(timestamp);
            
            // Search term filter
            const matchesSearch = user.toLowerCase().includes(searchTerm) || 
                                 action.includes(searchTerm);
            
            // User filter
            const matchesUser = userValue === '' || user === userValue;
            
            // Action type filter
            let matchesAction = true;
            if (actionValue === 'article') {
                matchesAction = action.includes('article');
            } else if (actionValue === 'user') {
                matchesAction = action.includes('user') || 
                                action.includes('profile') || 
                                action.includes('password');
            } else if (actionValue === 'login') {
                matchesAction = action.includes('log');
            }
            
            // Date range filter
            let matchesDate = true;
            if (fromDate && toDate) {
                matchesDate = date >= fromDate && date <= toDate;
            } else if (fromDate) {
                matchesDate = date >= fromDate;
            } else if (toDate) {
                matchesDate = date <= toDate;
            }
            
            if (matchesSearch && matchesUser && matchesAction && matchesDate) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
});
</script>

<!-- Add additional styles specific to the activity logs page -->
<style>
/* Date Filter Styles */
.date-filter {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.date-input {
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    width: 130px;
}

.filter-button {
    background-color: var(--admin-primary);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
}

/* User Link Style */
.user-link {
    color: var(--admin-primary);
    text-decoration: none;
    font-weight: 500;
}

.user-link:hover {
    text-decoration: underline;
}

/* Pagination Styles */
.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    margin-top: 1rem;
    border-top: 1px solid #eee;
}

.pagination {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pagination-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    border: 1px solid #ddd;
    background-color: white;
    border-radius: 4px;
    cursor: pointer;
    transition: var(--transition);
}

.pagination-btn:hover:not([disabled]) {
    background-color: #f5f5f5;
}

.pagination-btn.active {
    background-color: var(--admin-primary);
    color: white;
    border-color: var(--admin-primary);
}

.pagination-btn[disabled] {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-ellipsis {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
}

.pagination-info {
    color: var(--gray);
    font-size: 0.9rem;
}

/* Detail Grid for Modal */
.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
}

.detail-item.full-width {
    grid-column: span 2;
}

.detail-label {
    font-size: 0.85rem;
    color: var(--gray);
    margin-bottom: 0.5rem;
}

.detail-value {
    font-size: 1rem;
    color: var(--black);
    word-break: break-word;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .filter-bar {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .filter-options {
        flex-direction: column;
        width: 100%;
    }
    
    .date-filter {
        width: 100%;
        flex-wrap: wrap;
    }
    
    .date-input {
        flex: 1;
    }
    
    .pagination-container {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .detail-item.full-width {
        grid-column: 1;
    }
}
</style>

<?php include '../includes/footer.php'; ?>

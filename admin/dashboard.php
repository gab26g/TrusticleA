<?php
session_start();
require_once "../utils/user.php";

// Auto-login as admin for demo purposes
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Admin user ID
}

// Check if user is logged in and is an admin
if (!isAdmin($_SESSION['user_id'])) {
    // Redirect to login page if not an admin
    header("Location: ../auth/login.php");
    exit();
}

// Get current user information
$userId = $_SESSION['user_id'];
$userInfo = getUserInfo($userId);

// Sample data for charts - in a real application, this would come from database
$articleStatusData = [
    'pending' => 45,
    'approved' => 78,
    'fake' => 23
];

$activityTrends = [
    'Jan' => 65,
    'Feb' => 59,
    'Mar' => 80,
    'Apr' => 81,
    'May' => 56,
    'Jun' => 55,
    'Jul' => 40,
    'Aug' => 70,
    'Sep' => 90,
    'Oct' => 110,
    'Nov' => 105,
    'Dec' => 120
];

$userRoleData = [
    'admin' => 3,
    'user' => 120
];

// Sample article data - in a real application, this would come from a database
$recentArticles = [
    [
        'id' => 1,
        'title' => 'The Rise of AI in Healthcare',
        'author' => 'John Smith',
        'status' => 'pending',
        'submitted' => '1 hour ago'
    ],
    [
        'id' => 2,
        'title' => 'Breaking: New Environmental Policies Announced',
        'author' => 'Jane Doe',
        'status' => 'approved',
        'submitted' => '2 hours ago'
    ],
    [
        'id' => 3,
        'title' => 'Scientists Discover New Species',
        'author' => 'Mike Johnson',
        'status' => 'fake',
        'submitted' => '3 hours ago'
    ],
    [
        'id' => 4,
        'title' => 'Global Economy Recovery Trends',
        'author' => 'Sarah Williams',
        'status' => 'approved',
        'submitted' => '5 hours ago'
    ],
    [
        'id' => 5,
        'title' => 'Latest Tech Innovations at CES 2023',
        'author' => 'Robert Chen',
        'status' => 'pending',
        'submitted' => '1 day ago'
    ]
];

// Sample user activity data
$recentActivities = [
    [
        'user' => 'John Smith',
        'action' => 'Submitted a new article',
        'time' => '10 minutes ago'
    ],
    [
        'user' => 'Admin',
        'action' => 'Approved article #1045',
        'time' => '30 minutes ago'
    ],
    [
        'user' => 'Jane Doe',
        'action' => 'Updated profile information',
        'time' => '1 hour ago'
    ],
    [
        'user' => 'Admin',
        'action' => 'Rejected article #1042 as fake news',
        'time' => '2 hours ago'
    ],
    [
        'user' => 'Mike Johnson',
        'action' => 'Created a new account',
        'time' => '3 hours ago'
    ]
];

// Include the header
include '../includes/admin_header.php';
?>

<!-- Main Content -->
<div class="main-content">
    <h1 class="header">Admin Dashboard</h1>
    
    <!-- Dashboard Overview -->
    <div class="dashboard-stats">
        <div class="stat-card">
            <i class="fas fa-newspaper card-icon"></i>
            <div class="stat-info">
                <h3>Total Articles</h3>
                <p class="stat-number"><?php echo array_sum($articleStatusData); ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <i class="fas fa-user card-icon"></i>
            <div class="stat-info">
                <h3>Total Users</h3>
                <p class="stat-number"><?php echo array_sum($userRoleData); ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <i class="fas fa-exclamation-triangle card-icon"></i>
            <div class="stat-info">
                <h3>Fake News</h3>
                <p class="stat-number"><?php echo $articleStatusData['fake']; ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <i class="fas fa-tasks card-icon"></i>
            <div class="stat-info">
                <h3>Pending Review</h3>
                <p class="stat-number"><?php echo $articleStatusData['pending']; ?></p>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="charts-container">
        <div class="chart-card">
            <h2>Article Status</h2>
            <canvas id="articleStatusChart"></canvas>
        </div>
        
        <div class="chart-card">
            <h2>User Activity Trends</h2>
            <canvas id="activityTrendsChart"></canvas>
        </div>
    </div>
    
    <div class="charts-container">
        <div class="chart-card">
            <h2>User Distribution</h2>
            <canvas id="userDistributionChart"></canvas>
        </div>
        
        <div class="chart-card">
            <h2>Recent Activity</h2>
            <div class="activity-list">
                <?php foreach ($recentActivities as $activity): ?>
                    <div class="activity-item">
                        <div class="activity-user"><?php echo htmlspecialchars($activity['user']); ?></div>
                        <div class="activity-action"><?php echo htmlspecialchars($activity['action']); ?></div>
                        <div class="activity-time"><?php echo htmlspecialchars($activity['time']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="view-all-link">
                <a href="activity_logs.php">View All Activity</a>
            </div>
        </div>
    </div>
    
    <!-- Recent Articles Table -->
    <div class="table-card">
        <div class="table-header">
            <h2>Recent Articles</h2>
            <a href="articles.php" class="view-all-btn">View All</a>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentArticles as $article): ?>
                        <tr>
                            <td><?php echo $article['id']; ?></td>
                            <td><?php echo htmlspecialchars($article['title']); ?></td>
                            <td><?php echo htmlspecialchars($article['author']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $article['status']; ?>">
                                    <?php echo ucfirst($article['status']); ?>
                                </span>
                            </td>
                            <td><?php echo $article['submitted']; ?></td>
                            <td class="actions-cell">
                                <button class="action-btn approve" title="Approve" data-id="<?php echo $article['id']; ?>">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="action-btn reject" title="Mark as Fake" data-id="<?php echo $article['id']; ?>">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button class="action-btn view" title="View Details" data-id="<?php echo $article['id']; ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Article Status Chart
const articleStatusCtx = document.getElementById('articleStatusChart').getContext('2d');
const articleStatusChart = new Chart(articleStatusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Approved', 'Fake'],
        datasets: [{
            data: [
                <?php echo $articleStatusData['pending']; ?>,
                <?php echo $articleStatusData['approved']; ?>,
                <?php echo $articleStatusData['fake']; ?>
            ],
            backgroundColor: ['#FFC107', '#4CAF50', '#F44336'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        },
        cutout: '70%'
    }
});

// Activity Trends Chart
const activityTrendsCtx = document.getElementById('activityTrendsChart').getContext('2d');
const activityTrendsChart = new Chart(activityTrendsCtx, {
    type: 'line',
    data: {
        labels: [
            <?php echo "'" . implode("', '", array_keys($activityTrends)) . "'"; ?>
        ],
        datasets: [{
            label: 'User Activity',
            data: [
                <?php echo implode(", ", array_values($activityTrends)); ?>
            ],
            fill: false,
            borderColor: '#3f6ad8',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// User Distribution Chart
const userDistributionCtx = document.getElementById('userDistributionChart').getContext('2d');
const userDistributionChart = new Chart(userDistributionCtx, {
    type: 'pie',
    data: {
        labels: ['Admin', 'Regular Users'],
        datasets: [{
            data: [
                <?php echo $userRoleData['admin']; ?>,
                <?php echo $userRoleData['user']; ?>
            ],
            backgroundColor: ['#4CAF50', '#2196F3'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

// Dashboard action buttons
document.querySelectorAll('.action-btn').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        
        if (this.classList.contains('approve')) {
            if (confirm('Are you sure you want to approve this article?')) {
                // Send AJAX request to approve article
                alert('Article #' + id + ' approved!');
            }
        } else if (this.classList.contains('reject')) {
            if (confirm('Are you sure you want to mark this article as fake?')) {
                // Send AJAX request to reject article
                alert('Article #' + id + ' marked as fake!');
            }
        } else if (this.classList.contains('view')) {
            // Redirect to article details page
            window.location.href = 'article_details.php?id=' + id;
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>

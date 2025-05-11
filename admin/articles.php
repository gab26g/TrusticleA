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

// Sample articles data - in a real application, this would come from a database
$articles = [
    [
        'id' => 1,
        'title' => 'The Rise of AI in Healthcare',
        'author' => 'John Smith',
        'author_id' => 2,
        'content' => 'Artificial intelligence is transforming healthcare in numerous ways, from diagnostics to treatment planning...',
        'status' => 'approved',
        'views' => 120,
        'created' => '2023-05-10',
        'time_ago' => '2 weeks ago'
    ],
    [
        'id' => 2,
        'title' => 'Breaking: New Environmental Policies Announced',
        'author' => 'Jane Doe',
        'author_id' => 3,
        'content' => 'Government officials unveiled new environmental protection policies aimed at reducing carbon emissions...',
        'status' => 'pending',
        'views' => 45,
        'created' => '2023-05-15',
        'time_ago' => '1 week ago'
    ],
    [
        'id' => 3,
        'title' => 'Scientists Discover New Species in Amazon Rainforest',
        'author' => 'Mike Johnson',
        'author_id' => 4,
        'content' => 'A team of researchers has identified several previously unknown species during an expedition...',
        'status' => 'fake',
        'views' => 89,
        'created' => '2023-05-18',
        'time_ago' => '4 days ago'
    ],
    [
        'id' => 4,
        'title' => 'Global Economy Recovery Trends Post-Pandemic',
        'author' => 'Sarah Williams',
        'author_id' => 5,
        'content' => 'Economic experts are analyzing the patterns of recovery across different sectors following the pandemic...',
        'status' => 'approved',
        'views' => 210,
        'created' => '2023-05-20',
        'time_ago' => '2 days ago'
    ],
    [
        'id' => 5,
        'title' => 'Latest Tech Innovations at CES 2023',
        'author' => 'Robert Chen',
        'author_id' => 8,
        'content' => 'The Consumer Electronics Show showcased groundbreaking technologies including advanced AI systems...',
        'status' => 'pending',
        'views' => 67,
        'created' => '2023-05-21',
        'time_ago' => '1 day ago'
    ],
    [
        'id' => 6,
        'title' => 'Breakthrough in Renewable Energy Storage',
        'author' => 'Emily Davis',
        'author_id' => 7,
        'content' => 'Engineers have developed a new type of battery that can store renewable energy more efficiently...',
        'status' => 'approved',
        'views' => 134,
        'created' => '2023-05-22',
        'time_ago' => '12 hours ago'
    ],
    [
        'id' => 7,
        'title' => 'Misleading Claims About Vaccine Effectiveness',
        'author' => 'David Miller',
        'author_id' => 8,
        'content' => 'Recent social media posts claiming vaccines are ineffective contain numerous factual errors...',
        'status' => 'fake',
        'views' => 320,
        'created' => '2023-05-22',
        'time_ago' => '10 hours ago'
    ],
    [
        'id' => 8,
        'title' => 'New Study Links Diet to Longevity',
        'author' => 'Sarah Williams',
        'author_id' => 5,
        'content' => 'Research published in a leading medical journal indicates that certain dietary patterns may extend lifespan...',
        'status' => 'pending',
        'views' => 75,
        'created' => '2023-05-22',
        'time_ago' => '6 hours ago'
    ]
];

// Include the header
include '../includes/admin_header.php';
?>

<!-- Main Content -->
<div class="main-content">
    <div class="page-header">
        <h1 class="header">Article Management</h1>
    </div>
    
    <!-- Stats Overview -->
    <div class="article-stats-overview">
        <div class="stat-card">
            <i class="fas fa-newspaper card-icon"></i>
            <div class="stat-info">
                <h3>Total Articles</h3>
                <p class="stat-number"><?php echo count($articles); ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <i class="fas fa-check-circle card-icon"></i>
            <div class="stat-info">
                <h3>Approved</h3>
                <p class="stat-number"><?php echo count(array_filter($articles, function($article) { return $article['status'] === 'approved'; })); ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <i class="fas fa-clock card-icon"></i>
            <div class="stat-info">
                <h3>Pending</h3>
                <p class="stat-number"><?php echo count(array_filter($articles, function($article) { return $article['status'] === 'pending'; })); ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <i class="fas fa-exclamation-triangle card-icon"></i>
            <div class="stat-info">
                <h3>Fake</h3>
                <p class="stat-number"><?php echo count(array_filter($articles, function($article) { return $article['status'] === 'fake'; })); ?></p>
            </div>
        </div>
    </div>
    
    <!-- Article Status Chart -->
    <div class="chart-container">
        <div class="chart-card">
            <h2>Article Status Distribution</h2>
            <canvas id="articleStatusDistribution"></canvas>
        </div>
    </div>
    
    <!-- Search and Filter -->
    <div class="filter-bar">
        <div class="search-container">
            <input type="text" id="articleSearch" class="search-input" placeholder="Search articles...">
            <button class="search-button"><i class="fas fa-search"></i></button>
        </div>
        
        <div class="filter-options">
            <select id="statusFilter" class="filter-select">
                <option value="">All Status</option>
                <option value="approved">Approved</option>
                <option value="pending">Pending</option>
                <option value="fake">Fake</option>
            </select>
            
            <select id="sortBy" class="filter-select">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="views">Most Views</option>
            </select>
        </div>
    </div>
    
    <!-- Articles Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="data-table" id="articlesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                        <tr data-id="<?php echo $article['id']; ?>">
                            <td><?php echo $article['id']; ?></td>
                            <td class="title-cell">
                                <div class="article-title-wrap">
                                    <?php echo htmlspecialchars($article['title']); ?>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($article['author']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $article['status']; ?>">
                                    <?php echo ucfirst($article['status']); ?>
                                </span>
                            </td>
                            <td><?php echo $article['views']; ?></td>
                            <td><?php echo $article['created']; ?></td>
                            <td class="actions-cell">
                                <?php if ($article['status'] !== 'approved'): ?>
                                    <button class="action-btn approve" title="Approve" data-id="<?php echo $article['id']; ?>">
                                        <i class="fas fa-check"></i>
                                    </button>
                                <?php endif; ?>
                                
                                <?php if ($article['status'] !== 'fake'): ?>
                                    <button class="action-btn mark-fake" title="Mark as Fake" data-id="<?php echo $article['id']; ?>">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                <?php endif; ?>
                                
                                <button class="action-btn view" title="View Details" data-id="<?php echo $article['id']; ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <button class="action-btn delete" title="Delete Article" data-id="<?php echo $article['id']; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Article Modal -->
<div class="modal-overlay" id="viewArticleModal">
    <div class="modal-container modal-lg">
        <div class="modal-header">
            <h2 id="articleTitle">Article Details</h2>
            <button class="modal-close" id="closeArticleModal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="article-detail-header">
                <div class="article-meta">
                    <p>By <span id="articleAuthor">Author Name</span> • <span id="articleDate">Date</span></p>
                    <div class="article-status" id="articleStatusBadge">
                        <span class="status-badge status-pending">Pending</span>
                    </div>
                </div>
                <div class="article-views">
                    <i class="far fa-eye"></i> <span id="articleViews">0</span> views
                </div>
            </div>
            
            <div class="article-content" id="articleContent">
                Article content will appear here...
            </div>
            
            <div class="article-actions">
                <button class="btn-secondary" id="closeArticleView">Close</button>
                <button class="btn-primary" id="approveArticle">Approve</button>
                <button class="btn-danger" id="markFakeArticle">Mark as Fake</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Article Confirmation Modal -->
<div class="modal-overlay" id="deleteArticleModal">
    <div class="modal-container modal-sm">
        <div class="modal-header">
            <h2>Delete Article</h2>
            <button class="modal-close" id="closeDeleteModal">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this article? This action cannot be undone.</p>
            
            <div class="form-actions">
                <button type="button" class="btn-secondary" id="cancelDelete">Cancel</button>
                <button type="button" class="btn-danger" id="confirmDelete">Delete Article</button>
            </div>
        </div>
    </div>
</div>

<!-- ChartJS for visualizations -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Article Status Chart
    const articleStatusData = {
        approved: <?php echo count(array_filter($articles, function($article) { return $article['status'] === 'approved'; })); ?>,
        pending: <?php echo count(array_filter($articles, function($article) { return $article['status'] === 'pending'; })); ?>,
        fake: <?php echo count(array_filter($articles, function($article) { return $article['status'] === 'fake'; })); ?>
    };
    
    const statusCtx = document.getElementById('articleStatusDistribution').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'bar',
        data: {
            labels: ['Approved', 'Pending', 'Fake'],
            datasets: [{
                label: 'Number of Articles',
                data: [articleStatusData.approved, articleStatusData.pending, articleStatusData.fake],
                backgroundColor: [
                    '#4CAF50',
                    '#FFC107',
                    '#F44336'
                ],
                borderWidth: 1
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
    
    // Modal elements
    const viewArticleModal = document.getElementById('viewArticleModal');
    const closeArticleModal = document.getElementById('closeArticleModal');
    const closeArticleView = document.getElementById('closeArticleView');
    const articleTitle = document.getElementById('articleTitle');
    const articleAuthor = document.getElementById('articleAuthor');
    const articleDate = document.getElementById('articleDate');
    const articleStatusBadge = document.getElementById('articleStatusBadge');
    const articleViews = document.getElementById('articleViews');
    const articleContent = document.getElementById('articleContent');
    const approveArticle = document.getElementById('approveArticle');
    const markFakeArticle = document.getElementById('markFakeArticle');
    
    // Delete modal elements
    const deleteArticleModal = document.getElementById('deleteArticleModal');
    const closeDeleteModal = document.getElementById('closeDeleteModal');
    const cancelDelete = document.getElementById('cancelDelete');
    const confirmDelete = document.getElementById('confirmDelete');
    
    // Search and filter elements
    const articleSearch = document.getElementById('articleSearch');
    const statusFilter = document.getElementById('statusFilter');
    const sortBy = document.getElementById('sortBy');
    
    // Current article for operations
    let currentArticle = null;
    
    // View Article Details
    document.querySelectorAll('.action-btn.view').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            // In a real app, you would fetch article data from the server
            // For demo, we'll use the sample data
            currentArticle = getArticleData(id);
            
            if (currentArticle) {
                articleTitle.textContent = currentArticle.title;
                articleAuthor.textContent = currentArticle.author;
                articleDate.textContent = currentArticle.created;
                articleViews.textContent = currentArticle.views;
                articleContent.textContent = currentArticle.content;
                
                // Set the status badge
                articleStatusBadge.innerHTML = `
                    <span class="status-badge status-${currentArticle.status}">
                        ${currentArticle.status.charAt(0).toUpperCase() + currentArticle.status.slice(1)}
                    </span>
                `;
                
                // Show/hide action buttons based on current status
                if (currentArticle.status === 'approved') {
                    approveArticle.style.display = 'none';
                    markFakeArticle.style.display = 'inline-block';
                } else if (currentArticle.status === 'fake') {
                    approveArticle.style.display = 'inline-block';
                    markFakeArticle.style.display = 'none';
                } else {
                    approveArticle.style.display = 'inline-block';
                    markFakeArticle.style.display = 'inline-block';
                }
                
                openModal(viewArticleModal);
            }
        });
    });
    
    // Approve Article
    document.querySelectorAll('.action-btn.approve').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.getAttribute('data-id');
            updateArticleStatus(id, 'approved');
        });
    });
    
    // Mark as Fake
    document.querySelectorAll('.action-btn.mark-fake').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.getAttribute('data-id');
            updateArticleStatus(id, 'fake');
        });
    });
    
    // Delete Article
    document.querySelectorAll('.action-btn.delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.getAttribute('data-id');
            currentArticle = { id: id };
            openModal(deleteArticleModal);
        });
    });
    
    // Modal Approve Button
    approveArticle.addEventListener('click', function() {
        if (currentArticle) {
            updateArticleStatus(currentArticle.id, 'approved');
            closeModal(viewArticleModal);
        }
    });
    
    // Modal Mark as Fake Button
    markFakeArticle.addEventListener('click', function() {
        if (currentArticle) {
            updateArticleStatus(currentArticle.id, 'fake');
            closeModal(viewArticleModal);
        }
    });
    
    // Confirm Delete
    confirmDelete.addEventListener('click', function() {
        if (currentArticle) {
            // In a real app, you would send this to the server
            // For demo purposes, we'll just show an alert
            alert(`Article ID ${currentArticle.id} deleted successfully!`);
            
            // Remove the row from the table for demo purposes
            const row = document.querySelector(`tr[data-id="${currentArticle.id}"]`);
            if (row) {
                row.remove();
            }
            
            closeModal(deleteArticleModal);
            
            // Update the stats and chart
            updateStats();
        }
    });
    
    // Close Modals
    closeArticleModal.addEventListener('click', () => closeModal(viewArticleModal));
    closeArticleView.addEventListener('click', () => closeModal(viewArticleModal));
    closeDeleteModal.addEventListener('click', () => closeModal(deleteArticleModal));
    cancelDelete.addEventListener('click', () => closeModal(deleteArticleModal));
    
    // Search and Filter Functionality
    articleSearch.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
    sortBy.addEventListener('change', sortTable);
    
    // Helper Functions
    function openModal(modal) {
        modal.classList.add('open');
    }
    
    function closeModal(modal) {
        modal.classList.remove('open');
    }
    
    function getArticleData(id) {
        // In a real app, this would be an AJAX request
        // For demo, we search through our sample data
        return <?php echo json_encode($articles); ?>.find(article => article.id == id);
    }
    
    function updateArticleStatus(id, newStatus) {
        // In a real app, you would send this to the server
        // For demo purposes, we'll just update the UI
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (row) {
            const statusCell = row.cells[3];
            statusCell.innerHTML = `
                <span class="status-badge status-${newStatus}">
                    ${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)}
                </span>
            `;
            
            // Update action buttons
            const actionsCell = row.cells[6];
            let actionButtons = '';
            
            if (newStatus !== 'approved') {
                actionButtons += `
                    <button class="action-btn approve" title="Approve" data-id="${id}">
                        <i class="fas fa-check"></i>
                    </button>
                `;
            }
            
            if (newStatus !== 'fake') {
                actionButtons += `
                    <button class="action-btn mark-fake" title="Mark as Fake" data-id="${id}">
                        <i class="fas fa-ban"></i>
                    </button>
                `;
            }
            
            actionButtons += `
                <button class="action-btn view" title="View Details" data-id="${id}">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="action-btn delete" title="Delete Article" data-id="${id}">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            
            actionsCell.innerHTML = actionButtons;
            
            // Re-attach event listeners
            attachActionListeners(row);
            
            // Show success message
            alert(`Article ID ${id} status updated to ${newStatus}!`);
            
            // Update the stats and chart
            updateStats();
        }
    }
    
    function attachActionListeners(row) {
        const id = row.getAttribute('data-id');
        
        // View button
        const viewBtn = row.querySelector('.action-btn.view');
        if (viewBtn) {
            viewBtn.addEventListener('click', function() {
                const articleData = getArticleData(id);
                if (articleData) {
                    currentArticle = articleData;
                    
                    articleTitle.textContent = articleData.title;
                    articleAuthor.textContent = articleData.author;
                    articleDate.textContent = articleData.created;
                    articleViews.textContent = articleData.views;
                    articleContent.textContent = articleData.content;
                    
                    // Set the status badge
                    articleStatusBadge.innerHTML = `
                        <span class="status-badge status-${articleData.status}">
                            ${articleData.status.charAt(0).toUpperCase() + articleData.status.slice(1)}
                        </span>
                    `;
                    
                    // Show/hide action buttons based on current status
                    if (articleData.status === 'approved') {
                        approveArticle.style.display = 'none';
                        markFakeArticle.style.display = 'inline-block';
                    } else if (articleData.status === 'fake') {
                        approveArticle.style.display = 'inline-block';
                        markFakeArticle.style.display = 'none';
                    } else {
                        approveArticle.style.display = 'inline-block';
                        markFakeArticle.style.display = 'inline-block';
                    }
                    
                    openModal(viewArticleModal);
                }
            });
        }
        
        // Approve button
        const approveBtn = row.querySelector('.action-btn.approve');
        if (approveBtn) {
            approveBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                updateArticleStatus(id, 'approved');
            });
        }
        
        // Mark as fake button
        const markFakeBtn = row.querySelector('.action-btn.mark-fake');
        if (markFakeBtn) {
            markFakeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                updateArticleStatus(id, 'fake');
            });
        }
        
        // Delete button
        const deleteBtn = row.querySelector('.action-btn.delete');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                currentArticle = { id: id };
                openModal(deleteArticleModal);
            });
        }
    }
    
    function filterTable() {
        const searchTerm = articleSearch.value.toLowerCase();
        const statusValue = statusFilter.value;
        
        const rows = document.querySelectorAll('#articlesTable tbody tr');
        
        rows.forEach(row => {
            const title = row.cells[1].textContent.toLowerCase();
            const author = row.cells[2].textContent.toLowerCase();
            const status = row.cells[3].textContent.trim().toLowerCase();
            
            const matchesSearch = title.includes(searchTerm) || author.includes(searchTerm);
            const matchesStatus = statusValue === '' || status === statusValue;
            
            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    function sortTable() {
        const sortValue = sortBy.value;
        const tbody = document.querySelector('#articlesTable tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        rows.sort((a, b) => {
            if (sortValue === 'newest') {
                const dateA = new Date(a.cells[5].textContent);
                const dateB = new Date(b.cells[5].textContent);
                return dateB - dateA;
            } else if (sortValue === 'oldest') {
                const dateA = new Date(a.cells[5].textContent);
                const dateB = new Date(b.cells[5].textContent);
                return dateA - dateB;
            } else if (sortValue === 'views') {
                const viewsA = parseInt(a.cells[4].textContent);
                const viewsB = parseInt(b.cells[4].textContent);
                return viewsB - viewsA;
            }
            return 0;
        });
        
        // Clear and re-append rows in the new order
        while (tbody.firstChild) {
            tbody.removeChild(tbody.firstChild);
        }
        
        rows.forEach(row => {
            tbody.appendChild(row);
        });
    }
    
    function updateStats() {
        // Count articles by status
        const rows = document.querySelectorAll('#articlesTable tbody tr');
        let approved = 0;
        let pending = 0;
        let fake = 0;
        let total = 0;
        
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                total++;
                const status = row.cells[3].textContent.trim().toLowerCase();
                if (status === 'approved') approved++;
                else if (status === 'pending') pending++;
                else if (status === 'fake') fake++;
            }
        });
        
        // Update stat cards
        document.querySelectorAll('.stat-card .stat-number').forEach((el, index) => {
            if (index === 0) el.textContent = total;
            else if (index === 1) el.textContent = approved;
            else if (index === 2) el.textContent = pending;
            else if (index === 3) el.textContent = fake;
        });
        
        // Update chart
        statusChart.data.datasets[0].data = [approved, pending, fake];
        statusChart.update();
    }
    
    // Initial sort
    sortTable();
});
</script>

<!-- Add additional styles specific to the articles page -->
<style>
/* Article Stats Overview */
.article-stats-overview {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

/* Chart Container */
.chart-container {
    margin-bottom: 2rem;
}

/* Article Title in Table */
.title-cell {
    max-width: 300px;
}

.article-title-wrap {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 300px;
}

/* Article Detail Modal */
.modal-lg {
    max-width: 800px;
}

.article-detail-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #eee;
}

.article-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.article-meta p {
    margin: 0;
    color: var(--gray);
}

.article-content {
    margin-bottom: 2rem;
    line-height: 1.6;
}

.article-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}
</style>

<?php include '../includes/footer.php'; ?>

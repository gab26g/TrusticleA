<?php
session_start();
require_once "../utils/user.php";
require_once "../utils/article.php";

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

// Check if article ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: articles.php");
    exit();
}

$articleId = $_GET['id'];
$article = getArticleById($articleId);

// If article doesn't exist, redirect to articles page
if (!$article) {
    header("Location: articles.php");
    exit();
}

// Include the header
include '../includes/admin_header.php';

// Sample comments for the article
$comments = [
    [
        'id' => 1,
        'user_id' => 2,
        'user_name' => 'John Smith',
        'content' => 'Great article! Very informative.',
        'timestamp' => '2023-05-22 10:15:30',
        'time_ago' => '1 hour ago'
    ],
    [
        'id' => 2,
        'user_id' => 3,
        'user_name' => 'Jane Doe',
        'content' => 'I find this perspective interesting, though I would like to see more sources cited.',
        'timestamp' => '2023-05-22 11:00:00',
        'time_ago' => '30 minutes ago'
    ],
    [
        'id' => 3,
        'user_id' => 4,
        'user_name' => 'Mike Johnson',
        'content' => 'I disagree with some points made in this article. The data presented seems cherry-picked.',
        'timestamp' => '2023-05-22 11:20:00',
        'time_ago' => '10 minutes ago'
    ]
];

// Sample fake news indicators for the article
$fakeNewsIndicators = [
    [
        'indicator' => 'Misleading Headlines',
        'score' => 0.2,
        'description' => 'The headline accurately represents the content.'
    ],
    [
        'indicator' => 'Source Credibility',
        'score' => 0.7,
        'description' => 'The article cites sources, but they are not well-established or authoritative.'
    ],
    [
        'indicator' => 'Emotional Language',
        'score' => 0.4,
        'description' => 'Some emotional language detected, but it\'s balanced with factual reporting.'
    ],
    [
        'indicator' => 'Factual Accuracy',
        'score' => 0.3,
        'description' => 'Most facts appear accurate, with minor discrepancies.'
    ],
    [
        'indicator' => 'Expert Consensus',
        'score' => 0.6,
        'description' => 'Some claims made contradict expert consensus on the topic.'
    ]
];

// Calculate overall fake news probability
$overallScore = array_sum(array_column($fakeNewsIndicators, 'score')) / count($fakeNewsIndicators);
?>

<!-- Main Content -->
<div class="main-content">
    <div class="breadcrumb">
        <a href="dashboard.php">Dashboard</a> &gt;
        <a href="articles.php">Articles</a> &gt;
        <span>Article Details</span>
    </div>
    
    <div class="article-header">
        <div class="article-title-section">
            <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
            <div class="article-meta">
                <span class="article-author">By <?php echo htmlspecialchars($article['author']); ?></span>
                <span class="article-date"><?php echo $article['created_at']; ?></span>
                <span class="article-views"><i class="far fa-eye"></i> <?php echo $article['views']; ?> views</span>
            </div>
        </div>
        
        <div class="article-status-section">
            <span class="status-badge status-<?php echo $article['status']; ?>">
                <?php echo ucfirst($article['status']); ?>
            </span>
            
            <div class="article-actions">
                <?php if ($article['status'] !== 'approved'): ?>
                    <button class="btn-primary" id="approveArticle">
                        <i class="fas fa-check"></i> Approve
                    </button>
                <?php endif; ?>
                
                <?php if ($article['status'] !== 'fake'): ?>
                    <button class="btn-danger" id="markFakeArticle">
                        <i class="fas fa-ban"></i> Mark as Fake
                    </button>
                <?php endif; ?>
                
                <button class="btn-secondary" id="deleteArticle">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
    
    <div class="content-layout">
        <div class="article-content-section">
            <div class="article-card">
                <div class="article-content">
                    <?php echo $article['content']; ?>
                </div>
            </div>
            
            <div class="article-card">
                <h2 class="section-title">Comments (<?php echo count($comments); ?>)</h2>
                
                <div class="comments-section">
                    <?php if (empty($comments)): ?>
                        <p class="no-comments">No comments yet.</p>
                    <?php else: ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment-item">
                                <div class="comment-header">
                                    <div class="comment-author">
                                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($comment['user_name']); ?>&background=random" alt="<?php echo htmlspecialchars($comment['user_name']); ?>" class="comment-avatar">
                                        <div class="comment-author-info">
                                            <span class="comment-author-name"><?php echo htmlspecialchars($comment['user_name']); ?></span>
                                            <span class="comment-time"><?php echo $comment['time_ago']; ?></span>
                                        </div>
                                    </div>
                                    <div class="comment-actions">
                                        <button class="action-btn delete" title="Delete Comment" data-id="<?php echo $comment['id']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="comment-body">
                                    <?php echo htmlspecialchars($comment['content']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="article-sidebar">
            <div class="article-card">
                <h2 class="section-title">Fake News Analysis</h2>
                
                <div class="fake-news-score">
                    <div class="score-gauge" data-score="<?php echo $overallScore; ?>">
                        <canvas id="scoreGauge"></canvas>
                        <div class="score-value"><?php echo round($overallScore * 100); ?>%</div>
                    </div>
                    <div class="score-label">Probability of Fake News</div>
                </div>
                
                <div class="indicators-list">
                    <?php foreach ($fakeNewsIndicators as $indicator): ?>
                        <div class="indicator-item">
                            <div class="indicator-header">
                                <span class="indicator-name"><?php echo htmlspecialchars($indicator['indicator']); ?></span>
                                <span class="indicator-score <?php echo $indicator['score'] > 0.5 ? 'high' : 'low'; ?>">
                                    <?php echo round($indicator['score'] * 100); ?>%
                                </span>
                            </div>
                            <div class="indicator-bar">
                                <div class="indicator-fill" style="width: <?php echo $indicator['score'] * 100; ?>%;"></div>
                            </div>
                            <p class="indicator-description">
                                <?php echo htmlspecialchars($indicator['description']); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="article-card">
                <h2 class="section-title">Author Information</h2>
                
                <div class="author-profile">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($article['author']); ?>&background=0D8ABC&color=fff" alt="<?php echo htmlspecialchars($article['author']); ?>" class="author-avatar">
                    <h3 class="author-name"><?php echo htmlspecialchars($article['author']); ?></h3>
                    <a href="users.php?id=<?php echo $article['author_id']; ?>" class="author-link">View Profile</a>
                    
                    <div class="author-stats">
                        <div class="stat-item">
                            <span class="stat-value">12</span>
                            <span class="stat-label">Articles</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">8</span>
                            <span class="stat-label">Approved</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">3</span>
                            <span class="stat-label">Pending</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">1</span>
                            <span class="stat-label">Fake</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteConfirmModal">
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

<!-- Delete Comment Confirmation Modal -->
<div class="modal-overlay" id="deleteCommentModal">
    <div class="modal-container modal-sm">
        <div class="modal-header">
            <h2>Delete Comment</h2>
            <button class="modal-close" id="closeDeleteCommentModal">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this comment? This action cannot be undone.</p>
            
            <div class="form-actions">
                <button type="button" class="btn-secondary" id="cancelDeleteComment">Cancel</button>
                <button type="button" class="btn-danger" id="confirmDeleteComment">Delete Comment</button>
            </div>
        </div>
    </div>
</div>

<!-- ChartJS for gauge -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Article Actions
    const approveArticle = document.getElementById('approveArticle');
    const markFakeArticle = document.getElementById('markFakeArticle');
    const deleteArticle = document.getElementById('deleteArticle');
    
    // Delete Modals
    const deleteConfirmModal = document.getElementById('deleteConfirmModal');
    const closeDeleteModal = document.getElementById('closeDeleteModal');
    const cancelDelete = document.getElementById('cancelDelete');
    const confirmDelete = document.getElementById('confirmDelete');
    
    // Delete Comment Modal
    const deleteCommentModal = document.getElementById('deleteCommentModal');
    const closeDeleteCommentModal = document.getElementById('closeDeleteCommentModal');
    const cancelDeleteComment = document.getElementById('cancelDeleteComment');
    const confirmDeleteComment = document.getElementById('confirmDeleteComment');
    
    // Comment variables
    let targetCommentId = null;
    
    // Approve Article
    if (approveArticle) {
        approveArticle.addEventListener('click', function() {
            // In a real app, you would send an AJAX request
            // For demo, just show alert and redirect
            alert('Article approved successfully!');
            window.location.href = 'articles.php';
        });
    }
    
    // Mark as Fake
    if (markFakeArticle) {
        markFakeArticle.addEventListener('click', function() {
            // In a real app, you would send an AJAX request
            // For demo, just show alert and redirect
            alert('Article marked as fake!');
            window.location.href = 'articles.php';
        });
    }
    
    // Delete Article
    if (deleteArticle) {
        deleteArticle.addEventListener('click', function() {
            openModal(deleteConfirmModal);
        });
    }
    
    // Delete Comment
    document.querySelectorAll('.comment-actions .delete').forEach(button => {
        button.addEventListener('click', function() {
            targetCommentId = this.getAttribute('data-id');
            openModal(deleteCommentModal);
        });
    });
    
    // Confirm Delete Article
    if (confirmDelete) {
        confirmDelete.addEventListener('click', function() {
            // In a real app, you would send an AJAX request
            // For demo, just show alert and redirect
            alert('Article deleted successfully!');
            window.location.href = 'articles.php';
        });
    }
    
    // Confirm Delete Comment
    if (confirmDeleteComment) {
        confirmDeleteComment.addEventListener('click', function() {
            // In a real app, you would send an AJAX request
            // For demo, just remove the comment from DOM
            if (targetCommentId) {
                const commentElement = document.querySelector(`.comment-actions .delete[data-id="${targetCommentId}"]`).closest('.comment-item');
                commentElement.remove();
                
                // If no more comments, show "No comments" message
                const commentsCount = document.querySelectorAll('.comment-item').length;
                if (commentsCount === 0) {
                    document.querySelector('.comments-section').innerHTML = '<p class="no-comments">No comments yet.</p>';
                }
                
                alert('Comment deleted successfully!');
                closeModal(deleteCommentModal);
            }
        });
    }
    
    // Close Modals
    if (closeDeleteModal) closeDeleteModal.addEventListener('click', () => closeModal(deleteConfirmModal));
    if (cancelDelete) cancelDelete.addEventListener('click', () => closeModal(deleteConfirmModal));
    if (closeDeleteCommentModal) closeDeleteCommentModal.addEventListener('click', () => closeModal(deleteCommentModal));
    if (cancelDeleteComment) cancelDeleteComment.addEventListener('click', () => closeModal(deleteCommentModal));
    
    // Fake News Score Gauge
    const scoreElement = document.querySelector('.score-gauge');
    if (scoreElement) {
        const score = parseFloat(scoreElement.getAttribute('data-score'));
        const ctx = document.getElementById('scoreGauge').getContext('2d');
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [score, 1 - score],
                    backgroundColor: [
                        score > 0.7 ? '#F44336' : (score > 0.4 ? '#FFC107' : '#4CAF50'),
                        '#e0e0e0'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '75%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: false
                    }
                }
            }
        });
    }
    
    // Helper Functions
    function openModal(modal) {
        modal.classList.add('open');
    }
    
    function closeModal(modal) {
        modal.classList.remove('open');
    }
});
</script>

<style>
/* Breadcrumb */
.breadcrumb {
    margin-bottom: 1.5rem;
    color: var(--gray);
    font-size: 0.9rem;
}

.breadcrumb a {
    color: var(--admin-primary);
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

/* Article Header */
.article-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
}

.article-title {
    font-size: 2rem;
    margin: 0 0 0.5rem 0;
    color: var(--black);
}

.article-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    color: var(--gray);
    font-size: 0.9rem;
}

.article-author {
    font-weight: 500;
}

.article-status-section {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 1rem;
}

.article-actions {
    display: flex;
    gap: 0.75rem;
}

/* Content Layout */
.content-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
}

.article-card {
    background-color: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.article-content {
    line-height: 1.6;
}

.section-title {
    font-size: 1.2rem;
    margin-top: 0;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #eee;
}

/* Comments Section */
.no-comments {
    color: var(--gray);
    font-style: italic;
}

.comment-item {
    padding: 1rem 0;
    border-bottom: 1px solid #eee;
}

.comment-item:last-child {
    border-bottom: none;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
}

.comment-author {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.comment-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
}

.comment-author-info {
    display: flex;
    flex-direction: column;
}

.comment-author-name {
    font-weight: 500;
}

.comment-time {
    font-size: 0.8rem;
    color: var(--gray);
}

.comment-body {
    margin-left: 44px;
    line-height: 1.5;
}

/* Fake News Analysis */
.fake-news-score {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 2rem;
}

.score-gauge {
    position: relative;
    width: 150px;
    height: 150px;
}

.score-value {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 2rem;
    font-weight: bold;
}

.score-label {
    margin-top: 0.5rem;
    color: var(--gray);
    font-size: 0.9rem;
}

.indicators-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.indicator-item {
    margin-bottom: 0.5rem;
}

.indicator-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.indicator-name {
    font-weight: 500;
}

.indicator-score {
    font-weight: bold;
}

.indicator-score.high {
    color: var(--red);
}

.indicator-score.low {
    color: var(--green);
}

.indicator-bar {
    height: 8px;
    background-color: #e0e0e0;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.indicator-fill {
    height: 100%;
    background-color: var(--admin-primary);
    border-radius: 4px;
}

.indicator-description {
    font-size: 0.9rem;
    color: var(--gray);
    margin: 0;
}

/* Author Profile */
.author-profile {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.author-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-bottom: 1rem;
}

.author-name {
    margin: 0 0 0.5rem 0;
    font-size: 1.2rem;
}

.author-link {
    color: var(--admin-primary);
    text-decoration: none;
    margin-bottom: 1.5rem;
}

.author-link:hover {
    text-decoration: underline;
}

.author-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    width: 100%;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0.75rem;
    background-color: #f5f7fb;
    border-radius: var(--border-radius);
}

.stat-value {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--admin-primary);
}

.stat-label {
    font-size: 0.8rem;
    color: var(--gray);
}

/* Responsive Styles */
@media (max-width: 992px) {
    .content-layout {
        grid-template-columns: 1fr;
    }
    
    .article-header {
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .article-status-section {
        align-items: flex-start;
    }
}

@media (max-width: 576px) {
    .article-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .article-actions {
        flex-wrap: wrap;
    }
}
</style>

<?php include '../includes/footer.php'; ?>

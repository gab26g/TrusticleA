<?php
// Sample article data (in a real application, this would come from a database)
$articles = [
    [
        'id' => 1,
        'title' => '11 excellent examples of artificial intelligence in the workplace',
        'excerpt' => 'Phasellus pellentesque, quam sed tempus tempus, dui magna semper urna, placerat tristique diam augue ut nunc.',
        'status' => 'pending',
        'views' => 50,
        'time_ago' => '15 min ago'
    ],
    [
        'id' => 2,
        'title' => '11 excellent examples of artificial intelligence in the workplace',
        'excerpt' => 'Phasellus pellentesque, quam sed tempus tempus, dui magna semper urna, placerat tristique diam augue ut nunc.',
        'status' => 'real',
        'views' => 120,
        'time_ago' => '1 hour ago'
    ],
    [
        'id' => 3,
        'title' => '11 excellent examples of artificial intelligence in the workplace',
        'excerpt' => 'Phasellus pellentesque, quam sed tempus tempus, dui magna semper urna, placerat tristique diam augue ut nunc.',
        'status' => 'pending',
        'views' => 32,
        'time_ago' => '2 hours ago'
    ]
];

// Process form submission (in a real application, this would save to a database)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_article'])) {
    // In a real application, you would validate and save the data
    // For this demo, we'll just redirect back to the page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Include the header (which contains the sidebar)
include '../includes/header.php';
?>

<!-- Main Content -->
<link rel="stylesheet" href="../assets/css/articles.css">
<div class="main-content">
    <h1 class="header">Articles</h1>
    
    <div>
        <h2 class="subheader">My Articles</h2>
        <p class="section-description">Here's your articles.</p>
        
        <div class="search-container">
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Search by title, content, or date...">
                <button class="search-button"><i class="fas fa-search"></i></button>
            </div>
            <div class="filter-wrapper">
                <button class="filter-button"><i class="fas fa-filter"></i></button>
                <div class="filter-dropdown">
                    <a href="#" class="filter-item" data-status="all">All</a>
                    <a href="#" class="filter-item" data-status="pending">Pending</a>
                    <a href="#" class="filter-item" data-status="real">Real</a>
                    <a href="#" class="filter-item" data-status="fake">Fake</a>
                </div>
            </div>
        </div>
        
        <!-- Article listings -->
        <?php foreach ($articles as $index => $article): ?>
            <div class="article-card">
                <div class="article-menu">
                    <i class="fas fa-ellipsis-h"></i>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item">Edit</a>
                        <a href="#" class="dropdown-item">Delete</a>
                    </div>
                </div>
                
                <h3 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                <p class="article-excerpt"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                <a href="#" class="read-more">Read More...</a>
                
                <div class="article-footer">
                    <div class="article-stats">
                        <div class="stat-item">
                            <i class="far fa-eye"></i>
                            <span><?php echo $article['views']; ?></span>
                        </div>
                        <div class="stat-item">
                            <i class="far fa-clock"></i>
                            <span><?php echo $article['time_ago']; ?></span>
                        </div>
                    </div>
                    
                    <div class="status-badge status-<?php echo $article['status']; ?>">
                        <?php echo ucfirst($article['status']); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <button class="see-all-button">See all my articles</button>
        
        <div class="section-divider"></div>
        
        <h2 class="subheader">Community Articles</h2>
        
        <!-- Community article (just showing one for demo) -->
        <div class="article-card">
            <div class="article-menu">
                <i class="fas fa-ellipsis-h"></i>
            </div>
            
            <h3 class="article-title">11 excellent examples of artificial intelligence in the workplace</h3>
            <p class="article-excerpt">Phasellus pellentesque, quam sed tempus tempus, dui magna semper urna, placerat tristique diam augue ut nunc.</p>
            <a href="#" class="read-more">Read More...</a>
            
            <div class="article-footer">
                <div class="article-stats">
                    <div class="stat-item">
                        <i class="far fa-eye"></i>
                        <span>78</span>
                    </div>
                    <div class="stat-item">
                        <i class="far fa-clock"></i>
                        <span>3 hours ago</span>
                    </div>
                </div>
                
                <div class="status-badge status-real">
                    Real
                </div>
            </div>
        </div>
        
        <button class="see-all-button">See all community articles</button>
    </div>
</div>

<?php
// Include the modal
include '../includes/modal.php';

// Include the footer
include '../includes/footer.php';
?>
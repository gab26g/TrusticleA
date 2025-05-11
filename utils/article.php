<?php
/**
 * Article utility functions
 * DRY principle implementation - centralize article operations
 */

/**
 * Get all articles with optional filters
 * 
 * @param array $filters Optional filters (status, user_id, search, etc.)
 * @param array $pagination Optional pagination params (page, limit)
 * @return array List of articles
 */
function getAllArticles($filters = [], $pagination = ['page' => 1, 'limit' => 10]) {
    global $conn;
    
    // In a real application, this would fetch from the database with filters
    // For demo, return sample data
    return [
        [
            'id' => 1,
            'title' => 'The Rise of AI in Healthcare',
            'author' => 'John Smith',
            'author_id' => 2,
            'content' => 'Artificial intelligence is transforming healthcare in numerous ways, from diagnostics to treatment planning...',
            'status' => 'approved',
            'views' => 120,
            'created_at' => '2023-05-10',
            'updated_at' => '2023-05-12'
        ],
        [
            'id' => 2,
            'title' => 'Breaking: New Environmental Policies Announced',
            'author' => 'Jane Doe',
            'author_id' => 3,
            'content' => 'Government officials unveiled new environmental protection policies aimed at reducing carbon emissions...',
            'status' => 'pending',
            'views' => 45,
            'created_at' => '2023-05-15',
            'updated_at' => '2023-05-15'
        ],
        [
            'id' => 3,
            'title' => 'Scientists Discover New Species in Amazon Rainforest',
            'author' => 'Mike Johnson',
            'author_id' => 4,
            'content' => 'A team of researchers has identified several previously unknown species during an expedition...',
            'status' => 'fake',
            'views' => 89,
            'created_at' => '2023-05-18',
            'updated_at' => '2023-05-20'
        ]
    ];
}

/**
 * Get article by ID
 * 
 * @param int $articleId Article ID
 * @return array|null Article data or null if not found
 */
function getArticleById($articleId) {
    global $conn;
    
    // In a real application, this would fetch from the database
    // For demo, search in sample data
    $articles = getAllArticles();
    foreach ($articles as $article) {
        if ($article['id'] == $articleId) {
            return $article;
        }
    }
    
    return null;
}

/**
 * Create a new article
 * 
 * @param array $articleData Article data
 * @return array Result with status and message
 */
function createArticle($articleData) {
    global $conn;
    
    // In a real application, this would insert into the database
    // For demo, return success
    return [
        'status' => 'success',
        'message' => 'Article created successfully',
        'article_id' => rand(1000, 9999) // Simulate a new ID
    ];
}

/**
 * Update an article
 * 
 * @param int $articleId Article ID
 * @param array $articleData Article data
 * @return array Result with status and message
 */
function updateArticle($articleId, $articleData) {
    global $conn;
    
    // In a real application, this would update the database
    // For demo, return success
    return [
        'status' => 'success',
        'message' => 'Article updated successfully'
    ];
}

/**
 * Delete an article
 * 
 * @param int $articleId Article ID
 * @return array Result with status and message
 */
function deleteArticle($articleId) {
    global $conn;
    
    // In a real application, this would delete from the database
    // For demo, return success
    return [
        'status' => 'success',
        'message' => 'Article deleted successfully'
    ];
}

/**
 * Change article status (admin function)
 * 
 * @param int $articleId Article ID
 * @param string $status New status (pending, approved, fake)
 * @return array Result with status and message
 */
function changeArticleStatus($articleId, $status) {
    global $conn;
    
    // Validate status
    $validStatuses = ['pending', 'approved', 'fake'];
    if (!in_array($status, $validStatuses)) {
        return [
            'status' => 'error',
            'message' => 'Invalid status'
        ];
    }
    
    // In a real application, this would update the database
    // For demo, return success
    return [
        'status' => 'success',
        'message' => "Article status changed to {$status} successfully"
    ];
}

/**
 * Get article statistics for dashboard
 * 
 * @return array Statistics data
 */
function getArticleStats() {
    global $conn;
    
    // In a real application, this would calculate from the database
    // For demo, return sample data
    return [
        'total' => 146,
        'pending' => 45,
        'approved' => 78,
        'fake' => 23,
        'views_total' => 12450,
        'views_today' => 320,
        'most_viewed' => [
            'id' => 1042,
            'title' => 'The Impact of Climate Change on Global Agriculture',
            'views' => 523
        ]
    ];
}

/**
 * Get article trends for analytics
 * 
 * @param string $period Period to analyze (day, week, month, year)
 * @return array Trends data
 */
function getArticleTrends($period = 'month') {
    global $conn;
    
    // In a real application, this would calculate from the database
    // For demo, return sample data for different periods
    
    if ($period === 'day') {
        return [
            'labels' => ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00'],
            'submissions' => [3, 1, 0, 5, 8, 12, 7, 4],
            'approvals' => [2, 1, 0, 4, 6, 9, 5, 3],
            'fake_flags' => [1, 0, 0, 1, 2, 3, 2, 1]
        ];
    } else if ($period === 'week') {
        return [
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'submissions' => [12, 15, 18, 14, 20, 8, 5],
            'approvals' => [9, 12, 15, 10, 16, 6, 4],
            'fake_flags' => [3, 3, 3, 4, 4, 2, 1]
        ];
    } else if ($period === 'year') {
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'submissions' => [150, 165, 180, 195, 210, 225, 240, 255, 270, 285, 300, 315],
            'approvals' => [120, 132, 144, 156, 168, 180, 192, 204, 216, 228, 240, 252],
            'fake_flags' => [30, 33, 36, 39, 42, 45, 48, 51, 54, 57, 60, 63]
        ];
    } else { // month
        return [
            'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            'submissions' => [45, 52, 48, 55],
            'approvals' => [35, 40, 38, 42],
            'fake_flags' => [10, 12, 10, 13]
        ];
    }
}

/**
 * Get top fake news indicators for dashboard
 * 
 * @return array Top indicators data
 */
function getTopFakeNewsIndicators() {
    global $conn;
    
    // In a real application, this would analyze fake news patterns
    // For demo, return sample data
    return [
        [
            'indicator' => 'Sensational Headlines',
            'percentage' => 35,
            'examples' => [
                'SHOCKING: Scientists Discover Cure for All Diseases!',
                'THIS Will Change Everything You Know About Health!'
            ]
        ],
        [
            'indicator' => 'Lack of Sources',
            'percentage' => 28,
            'examples' => [
                'Anonymous Sources Claim Government Conspiracy',
                'Experts Say New Treatment 100% Effective'
            ]
        ],
        [
            'indicator' => 'Manipulated Images',
            'percentage' => 18,
            'examples' => [
                'Photo of Politician at Controversial Event',
                'Image Shows Dramatic Weather Phenomenon'
            ]
        ],
        [
            'indicator' => 'Outdated Information',
            'percentage' => 12,
            'examples' => [
                'Study from 2010 Used to Support Current Claims',
                'Old Statistics Presented as New Findings'
            ]
        ],
        [
            'indicator' => 'Logical Fallacies',
            'percentage' => 7,
            'examples' => [
                'Because A Happened, B Must Be True',
                'Celebrity Endorses Product, So It Must Work'
            ]
        ]
    ];
}
?>
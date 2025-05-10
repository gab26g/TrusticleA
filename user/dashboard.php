<?php
session_start();
require_once "../utils/user.php";

// Check if user is logged in
if (!isLoggedIn()) {
    // Redirect to login page if not logged in
    header("Location: ../auth/login.php");
    exit();
}

// Get user info
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Trusticle</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/header.php';?>
        <!-- Main Content Area -->
        <div class="main-content">

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="welcome-message">
                    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
                    <p>Here's what's happening with your articles today.</p>
                </div>

                <div class="stat-cards-container">
                    <!-- Blue Card -->
                    <div class="stat-card blue">
                        <div class="stat-icon">📚</div>
                        <div class="stat-info">
                            <div class="stat-title">Total Articles Submitted</div>
                            <div class="stat-value">0</div>
                        </div>
                    </div>

                    <!-- Yellow Card -->
                    <div class="stat-card yellow">
                        <div class="stat-icon">⏳</div>
                        <div class="stat-info">
                            <div class="stat-title">Pending Articles</div>
                            <div class="stat-value">0</div>
                        </div>
                    </div>

                    <!-- Green Card -->
                    <div class="stat-card green">
                        <div class="stat-icon">✅</div>
                        <div class="stat-info">
                            <div class="stat-title">Legit Articles</div>
                            <div class="stat-value">0</div>
                        </div>
                    </div>

                    <!-- Red Card -->
                    <div class="stat-card red">
                        <div class="stat-icon">❌</div>
                        <div class="stat-info">
                            <div class="stat-title">Fake Articles</div>
                            <div class="stat-value">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
      
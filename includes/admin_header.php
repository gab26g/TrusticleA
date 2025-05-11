<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trusticle Admin - Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body class="admin-body">
    <!-- Sidebar -->
    <div class="sidebar admin-sidebar" id="sidebar">
        <div class="logo-container" id="toggleSidebar">
            <div>
                <i class="fas fa-feather-alt fa-2x"></i>
                <div class="logo-text">Trusticle <span class="admin-badge">Admin</span></div>
            </div>
        </div>
        
        <div class="sidebar-menu">
            <a href="dashboard.php" class="menu-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="articles.php" class="menu-item">
                <i class="fas fa-newspaper"></i>
                <span>Articles</span>
            </a>
            <a href="users.php" class="menu-item">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            <a href="activity_logs.php" class="menu-item">
                <i class="fas fa-history"></i>
                <span>Activity Logs</span>
            </a>
            <div class="menu-item settings-menu" id="settingsMenu">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
                <i class="fas fa-chevron-down settings-arrow"></i>
            </div>
            <!-- Settings dropdown menu items -->
            <div class="submenu" id="settingsSubmenu">
                <a href="settings.php#site-settings" class="submenu-item">
                    <i class="fas fa-sliders-h"></i>
                    <span>Site Settings</span>
                </a>
                <a href="settings.php#account-security" class="submenu-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Security</span>
                </a>
            </div>
        </div>
        
        <div class="user-profile">
            <img src="https://ui-avatars.com/api/?name=Admin&background=722ed1&color=fff" alt="Admin" class="profile-image">
            <div class="user-info">
                <small>Admin User</small>
                <small class="user-subtitle">admin@trusticle.com</small>
            </div>
            <div class="user-menu">
                <i class="fas fa-ellipsis-v"></i>
            </div>
            
            <!-- Dropdown menu inside user-profile for better positioning -->
            <div class="user-dropdown">
                <a href="settings.php#edit-profile" class="dropdown-item">Edit Profile</a>
                <a href="../auth/logout.php" class="dropdown-item logout-option">Logout</a>
            </div>
        </div>
    </div>
    <!-- Custom JavaScript -->
    <script src="../assets/js/sidebar.js" defer></script>
</body>
</html>

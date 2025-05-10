<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tru/ticle - Articles</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo-container" id="toggleSidebar">
            <div>
                <i class="fas fa-feather-alt fa-2x"></i>
                <div class="logo-text">Trusticle</div>
            </div>
        </div>
        
        <div class="sidebar-menu">
            <a href="../user/dashboard.php" class="menu-item">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>
            <a href="../user/article_main.php" class="menu-item">
                <i class="fas fa-file-alt"></i>
                <span>Article</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-chart-bar"></i>
                <span>Analytics</span>
            </a>
            <div class="menu-item settings-menu" id="settingsMenu">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
                <i class="fas fa-chevron-down settings-arrow"></i>
            </div>
            <!-- Settings dropdown menu items -->
            <div class="submenu" id="settingsSubmenu">
                <a href="../user/settings.php#edit-profile" class="submenu-item">
                    <i class="fas fa-user-edit"></i>
                    <span>Edit Profile</span>
                </a>
                <a href="../user/settings.php#account-security" class="submenu-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Account Security</span>
                </a>
            </div>
            <a href="#" class="menu-item">
                <i class="fas fa-info-circle"></i>
                <span>About Us</span>
            </a>
        </div>
        
        <div class="user-profile">
            <img src="https://ui-avatars.com/api/?name=Rhea+Mangesh&background=0D8ABC&color=fff" alt="User" class="profile-image">
            <div class="user-info">
                <small>Rhea Manipon</small>
                <small class="user-subtitle">trusticle@mail.com</small>
            </div>
            <div class="user-menu">
                <i class="fas fa-ellipsis-v"></i>
            </div>
            
            <!-- Dropdown menu inside user-profile for better positioning -->
            <div class="user-dropdown">
                <a href="../user/settings.php#edit-profile" class="dropdown-item">Edit Profile</a>
                <a href="../auth/logout.php" class="dropdown-item logout-option">Logout</a>
            </div>
        </div>
    </div>
    <!-- Custom JavaScript -->
    <script src="../assets/js/sidebar.js" defer></script>
</body>
</html>
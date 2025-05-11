<file>
      <absolute_file_name>/app/assets/js/sidebar.js</absolute_file_name>
      <content">document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar on small screens
    if (document.getElementById('toggleSidebar')) {
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.body.classList.toggle('sidebar-collapsed');
        });
    }
    
    // Settings dropdown
    if (document.getElementById('settingsMenu')) {
        document.getElementById('settingsMenu').addEventListener('click', function() {
            document.getElementById('settingsSubmenu').classList.toggle('open');
            document.querySelector('.settings-arrow').classList.toggle('rotate');
        });
    }
    
    // User profile dropdown
    if (document.querySelector('.user-menu')) {
        document.querySelector('.user-menu').addEventListener('click', function(e) {
            e.stopPropagation();
            document.querySelector('.user-dropdown').classList.toggle('open');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            document.querySelector('.user-dropdown').classList.remove('open');
        });
    }
});
</content>
    </file>
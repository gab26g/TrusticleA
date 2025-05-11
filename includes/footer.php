<file>
      <absolute_file_name>/app/includes/footer.php</absolute_file_name>
      <content">
<!-- Footer Section -->
<footer class="footer">
    <div class="footer-content">
        <div class="footer-copyright">
            &copy; 2023 Trusticle. All Rights Reserved.
        </div>
    </div>
</footer>

<script>
// Common JavaScript for all pages
document.addEventListener('DOMContentLoaded', function() {
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
</script>

</body>
</html>
</content>
    </file>
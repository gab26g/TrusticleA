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

// Sample site settings - in a real application, this would come from a database
$siteSettings = [
    'site_name' => 'Trusticle',
    'site_description' => 'Fake News Detection Platform',
    'site_logo' => '/assets/images/logo.png',
    'primary_color' => '#722ed1',
    'secondary_color' => '#f5f5f5',
    'articles_per_page' => 10,
    'allow_comments' => true,
    'allow_user_registration' => true,
    'require_email_verification' => true,
    'admin_email' => 'admin@trusticle.com',
    'admin_email_notifications' => true,
    'maintenance_mode' => false
];

// Sample security settings
$securitySettings = [
    'login_attempts' => 5,
    'login_timeout' => 15, // minutes
    'session_lifetime' => 120, // minutes
    'password_expiry' => 90, // days
    'password_min_length' => 8,
    'password_require_uppercase' => true,
    'password_require_lowercase' => true,
    'password_require_number' => true,
    'password_require_special' => true,
    'two_factor_auth' => false,
    'api_rate_limit' => 100 // requests per hour
];

// Include the header
include '../includes/admin_header.php';
?>

<!-- Main Content -->
<div class="main-content">
    <div class="page-header">
        <h1 class="header">Settings</h1>
    </div>
    
    <!-- Settings Tabs -->
    <div class="settings-tabs">
        <div class="tabs-navigation">
            <button class="tab-button active" data-tab="site-settings">
                <i class="fas fa-sliders-h"></i> Site Settings
            </button>
            <button class="tab-button" data-tab="account-security">
                <i class="fas fa-shield-alt"></i> Security
            </button>
            <button class="tab-button" data-tab="api-management">
                <i class="fas fa-plug"></i> API Management
            </button>
            <button class="tab-button" data-tab="edit-profile">
                <i class="fas fa-user-edit"></i> Profile
            </button>
        </div>
        
        <div class="tabs-content">
            <!-- Site Settings Tab -->
            <div class="tab-pane active" id="site-settings">
                <form id="siteSettingsForm" class="settings-form">
                    <div class="form-section">
                        <h2 class="section-title">General Settings</h2>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="site_name" class="form-label">Site Name</label>
                                <input type="text" id="site_name" name="site_name" class="form-control" value="<?php echo htmlspecialchars($siteSettings['site_name']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="site_description" class="form-label">Site Description</label>
                                <input type="text" id="site_description" name="site_description" class="form-control" value="<?php echo htmlspecialchars($siteSettings['site_description']); ?>">
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="admin_email" class="form-label">Admin Email</label>
                                <input type="email" id="admin_email" name="admin_email" class="form-control" value="<?php echo htmlspecialchars($siteSettings['admin_email']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="articles_per_page" class="form-label">Articles Per Page</label>
                                <input type="number" id="articles_per_page" name="articles_per_page" class="form-control" value="<?php echo $siteSettings['articles_per_page']; ?>" min="5" max="50">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2 class="section-title">Appearance</h2>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="primary_color" class="form-label">Primary Color</label>
                                <div class="color-input-group">
                                    <input type="color" id="primary_color" name="primary_color" class="color-input" value="<?php echo $siteSettings['primary_color']; ?>">
                                    <input type="text" id="primary_color_text" class="form-control color-text" value="<?php echo $siteSettings['primary_color']; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="secondary_color" class="form-label">Secondary Color</label>
                                <div class="color-input-group">
                                    <input type="color" id="secondary_color" name="secondary_color" class="color-input" value="<?php echo $siteSettings['secondary_color']; ?>">
                                    <input type="text" id="secondary_color_text" class="form-control color-text" value="<?php echo $siteSettings['secondary_color']; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="site_logo" class="form-label">Site Logo</label>
                            <div class="file-upload-group">
                                <input type="file" id="site_logo" name="site_logo" class="file-input" accept="image/*">
                                <div class="file-preview">
                                    <img src="<?php echo $siteSettings['site_logo']; ?>" alt="Site Logo" id="logo-preview">
                                </div>
                                <button type="button" class="btn-secondary btn-sm">Choose File</button>
                                <span class="file-name">No file chosen</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2 class="section-title">Features</h2>
                        
                        <div class="form-switches">
                            <div class="form-switch">
                                <label class="switch">
                                    <input type="checkbox" name="allow_comments" <?php echo $siteSettings['allow_comments'] ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                                <div class="switch-label">
                                    <span>Allow Comments</span>
                                    <small>Enable user comments on articles</small>
                                </div>
                            </div>
                            
                            <div class="form-switch">
                                <label class="switch">
                                    <input type="checkbox" name="allow_user_registration" <?php echo $siteSettings['allow_user_registration'] ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                                <div class="switch-label">
                                    <span>Allow User Registration</span>
                                    <small>Enable new user registration</small>
                                </div>
                            </div>
                            
                            <div class="form-switch">
                                <label class="switch">
                                    <input type="checkbox" name="require_email_verification" <?php echo $siteSettings['require_email_verification'] ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                                <div class="switch-label">
                                    <span>Require Email Verification</span>
                                    <small>Require email verification for new accounts</small>
                                </div>
                            </div>
                            
                            <div class="form-switch">
                                <label class="switch">
                                    <input type="checkbox" name="admin_email_notifications" <?php echo $siteSettings['admin_email_notifications'] ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                                <div class="switch-label">
                                    <span>Admin Email Notifications</span>
                                    <small>Receive email notifications for new articles and reports</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2 class="section-title">Maintenance</h2>
                        
                        <div class="form-switch maintenance-switch">
                            <label class="switch">
                                <input type="checkbox" name="maintenance_mode" <?php echo $siteSettings['maintenance_mode'] ? 'checked' : ''; ?>>
                                <span class="slider round"></span>
                            </label>
                            <div class="switch-label">
                                <span>Maintenance Mode</span>
                                <small>When enabled, only administrators can access the site</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-secondary" id="resetSiteSettings">Reset to Default</button>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
            
            <!-- Security Tab -->
            <div class="tab-pane" id="account-security">
                <form id="securitySettingsForm" class="settings-form">
                    <div class="form-section">
                        <h2 class="section-title">Login Security</h2>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="login_attempts" class="form-label">Max Login Attempts</label>
                                <input type="number" id="login_attempts" name="login_attempts" class="form-control" value="<?php echo $securitySettings['login_attempts']; ?>" min="1" max="10">
                                <small class="form-text">Number of failed login attempts before account is locked</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="login_timeout" class="form-label">Login Lockout Duration (minutes)</label>
                                <input type="number" id="login_timeout" name="login_timeout" class="form-control" value="<?php echo $securitySettings['login_timeout']; ?>" min="5" max="60">
                                <small class="form-text">Duration of account lockout after max failed attempts</small>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="session_lifetime" class="form-label">Session Lifetime (minutes)</label>
                                <input type="number" id="session_lifetime" name="session_lifetime" class="form-control" value="<?php echo $securitySettings['session_lifetime']; ?>" min="15" max="1440">
                                <small class="form-text">How long a user stays logged in before session expires</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="password_expiry" class="form-label">Password Expiry (days)</label>
                                <input type="number" id="password_expiry" name="password_expiry" class="form-control" value="<?php echo $securitySettings['password_expiry']; ?>" min="30" max="365">
                                <small class="form-text">Number of days before passwords must be changed (0 = never)</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2 class="section-title">Password Requirements</h2>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="password_min_length" class="form-label">Minimum Password Length</label>
                                <input type="number" id="password_min_length" name="password_min_length" class="form-control" value="<?php echo $securitySettings['password_min_length']; ?>" min="6" max="32">
                            </div>
                        </div>
                        
                        <div class="form-switches">
                            <div class="form-switch">
                                <label class="switch">
                                    <input type="checkbox" name="password_require_uppercase" <?php echo $securitySettings['password_require_uppercase'] ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                                <div class="switch-label">
                                    <span>Require Uppercase Letters</span>
                                </div>
                            </div>
                            
                            <div class="form-switch">
                                <label class="switch">
                                    <input type="checkbox" name="password_require_lowercase" <?php echo $securitySettings['password_require_lowercase'] ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                                <div class="switch-label">
                                    <span>Require Lowercase Letters</span>
                                </div>
                            </div>
                            
                            <div class="form-switch">
                                <label class="switch">
                                    <input type="checkbox" name="password_require_number" <?php echo $securitySettings['password_require_number'] ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                                <div class="switch-label">
                                    <span>Require Numbers</span>
                                </div>
                            </div>
                            
                            <div class="form-switch">
                                <label class="switch">
                                    <input type="checkbox" name="password_require_special" <?php echo $securitySettings['password_require_special'] ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                                <div class="switch-label">
                                    <span>Require Special Characters</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2 class="section-title">Advanced Security</h2>
                        
                        <div class="form-switch">
                            <label class="switch">
                                <input type="checkbox" name="two_factor_auth" <?php echo $securitySettings['two_factor_auth'] ? 'checked' : ''; ?>>
                                <span class="slider round"></span>
                            </label>
                            <div class="switch-label">
                                <span>Two-Factor Authentication</span>
                                <small>Require 2FA for administrator accounts</small>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="api_rate_limit" class="form-label">API Rate Limit (requests per hour)</label>
                            <input type="number" id="api_rate_limit" name="api_rate_limit" class="form-control" value="<?php echo $securitySettings['api_rate_limit']; ?>" min="10" max="1000">
                            <small class="form-text">Maximum number of API requests allowed per hour per user</small>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-secondary" id="resetSecuritySettings">Reset to Default</button>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
            
            <!-- API Management Tab -->
            <div class="tab-pane" id="api-management">
                <div class="api-keys-section">
                    <div class="section-header">
                        <h2 class="section-title">API Keys</h2>
                        <button class="btn-primary" id="generateApiKey">
                            <i class="fas fa-plus"></i> Generate New Key
                        </button>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Key Name</th>
                                    <th>Key</th>
                                    <th>Created</th>
                                    <th>Last Used</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Admin API</td>
                                    <td class="api-key-cell">
                                        <span class="api-key-mask">••••••••••••••••••••••••</span>
                                        <button class="toggle-key-btn" title="Show/Hide Key">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <span class="api-key-value">gKz3TS7ZYVxEPWeafR2HkFdJLqNcjv5b</span>
                                    </td>
                                    <td>2023-04-01</td>
                                    <td>2023-05-22</td>
                                    <td><span class="status-badge status-active">Active</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn revoke" title="Revoke Key">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                        <button class="action-btn edit" title="Edit Key Name">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Analytics API</td>
                                    <td class="api-key-cell">
                                        <span class="api-key-mask">••••••••••••••••••••••••</span>
                                        <button class="toggle-key-btn" title="Show/Hide Key">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <span class="api-key-value">RQxWGm9YbVj5TPaZsMk7HK8E4L3nCzX2</span>
                                    </td>
                                    <td>2023-04-15</td>
                                    <td>2023-05-21</td>
                                    <td><span class="status-badge status-active">Active</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn revoke" title="Revoke Key">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                        <button class="action-btn edit" title="Edit Key Name">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Testing API</td>
                                    <td class="api-key-cell">
                                        <span class="api-key-mask">••••••••••••••••••••••••</span>
                                        <button class="toggle-key-btn" title="Show/Hide Key">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <span class="api-key-value">aMbRcD2EfGhJkMnPqRsTuVwXyZ123456</span>
                                    </td>
                                    <td>2023-05-01</td>
                                    <td>2023-05-20</td>
                                    <td><span class="status-badge status-inactive">Inactive</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn activate" title="Activate Key">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="action-btn edit" title="Edit Key Name">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2 class="section-title">API Documentation</h2>
                    <div class="documentation-links">
                        <a href="#" class="doc-link">
                            <i class="fas fa-book"></i>
                            <span>API Reference</span>
                        </a>
                        <a href="#" class="doc-link">
                            <i class="fas fa-code"></i>
                            <span>Code Examples</span>
                        </a>
                        <a href="#" class="doc-link">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Tutorial</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Edit Profile Tab -->
            <div class="tab-pane" id="edit-profile">
                <div class="form-section">
                    <h2 class="section-title">Admin Profile</h2>
                    
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <img src="https://ui-avatars.com/api/?name=Admin&background=722ed1&color=fff" alt="Admin User" class="avatar-large">
                            <button class="change-avatar-btn">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                        <div class="profile-info">
                            <h3 class="profile-name">Admin User</h3>
                            <p class="profile-email">admin@trusticle.com</p>
                            <p class="profile-role">Administrator</p>
                        </div>
                    </div>
                    
                    <form id="profileForm" class="settings-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" id="first_name" name="first_name" class="form-control" value="Admin" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" id="last_name" name="last_name" class="form-control" value="User" required>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" value="admin@trusticle.com" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" id="username" name="username" class="form-control" value="admin" required>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
                
                <div class="form-section">
                    <h2 class="section-title">Change Password</h2>
                    
                    <form id="passwordForm" class="settings-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" id="current_password" name="current_password" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" id="new_password" name="new_password" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="password-requirements">
                            <p class="requirements-title">Password Requirements:</p>
                            <ul class="requirements-list">
                                <li class="requirement" id="length-requirement">At least 8 characters</li>
                                <li class="requirement" id="uppercase-requirement">At least one uppercase letter</li>
                                <li class="requirement" id="lowercase-requirement">At least one lowercase letter</li>
                                <li class="requirement" id="number-requirement">At least one number</li>
                                <li class="requirement" id="special-requirement">At least one special character</li>
                            </ul>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New API Key Modal -->
<div class="modal-overlay" id="apiKeyModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Generate API Key</h2>
            <button class="modal-close" id="closeApiKeyModal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="apiKeyForm">
                <div class="form-group">
                    <label for="keyName" class="form-label">Key Name</label>
                    <input type="text" id="keyName" name="keyName" class="form-control" placeholder="e.g., Analytics API" required>
                </div>
                
                <div class="form-group">
                    <label for="keyPermissions" class="form-label">Permissions</label>
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="permissions[]" value="read"> Read
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="permissions[]" value="write"> Write
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="permissions[]" value="delete"> Delete
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="keyExpiry" class="form-label">Expiry</label>
                    <select id="keyExpiry" name="keyExpiry" class="form-control">
                        <option value="never">Never</option>
                        <option value="30">30 Days</option>
                        <option value="60">60 Days</option>
                        <option value="90">90 Days</option>
                        <option value="365">1 Year</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-secondary" id="cancelApiKey">Cancel</button>
                    <button type="submit" class="btn-primary">Generate Key</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- New API Key Success Modal -->
<div class="modal-overlay" id="apiKeySuccessModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>API Key Generated</h2>
            <button class="modal-close" id="closeSuccessModal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="success-message">
                <p>Your new API key has been generated. Please copy it now, as you won't be able to see it again.</p>
            </div>
            
            <div class="api-key-display">
                <code id="newApiKey">aBcDeFgHiJkLmNoPqRsTuVwXyZ123456</code>
                <button class="copy-btn" id="copyApiKey">
                    <i class="fas fa-copy"></i> Copy
                </button>
            </div>
            
            <div class="key-info">
                <p><strong>Name:</strong> <span id="displayKeyName">New API Key</span></p>
                <p><strong>Created:</strong> <span id="displayKeyDate"><?php echo date('Y-m-d'); ?></span></p>
                <p><strong>Expiry:</strong> <span id="displayKeyExpiry">Never</span></p>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-primary" id="doneApiKey">Done</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab Navigation
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Deactivate all tabs
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));
            
            // Activate target tab
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
            
            // Update URL hash
            window.location.hash = targetTab;
        });
    });
    
    // Check for hash in URL
    if (window.location.hash) {
        const hash = window.location.hash.substring(1);
        const tabButton = document.querySelector(`.tab-button[data-tab="${hash}"]`);
        if (tabButton) {
            tabButton.click();
        }
    }
    
    // Color Input Sync
    document.querySelectorAll('.color-input').forEach(input => {
        const textInput = document.getElementById(input.id + '_text');
        input.addEventListener('input', function() {
            textInput.value = this.value;
        });
        
        textInput.addEventListener('input', function() {
            // Check if valid hex color
            if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                input.value = this.value;
            }
        });
    });
    
    // Logo Preview
    const logoInput = document.getElementById('site_logo');
    const logoPreview = document.getElementById('logo-preview');
    
    if (logoInput) {
        logoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoPreview.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
                
                // Update file name display
                const fileName = this.files[0].name;
                this.nextElementSibling.nextElementSibling.nextElementSibling.textContent = fileName;
            }
        });
        
        // Trigger file input when button is clicked
        const fileButton = logoInput.nextElementSibling.nextElementSibling;
        fileButton.addEventListener('click', function() {
            logoInput.click();
        });
    }
    
    // Form Submission
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // In a real application, this would send the form data to the server
            // For demo purposes, we'll just show an alert
            alert('Settings saved successfully!');
        });
    });
    
    // Reset buttons
    const resetSiteSettings = document.getElementById('resetSiteSettings');
    if (resetSiteSettings) {
        resetSiteSettings.addEventListener('click', function() {
            if (confirm('Are you sure you want to reset all site settings to default values?')) {
                // In a real application, this would reset the form with default values
                alert('Site settings reset to default values.');
            }
        });
    }
    
    const resetSecuritySettings = document.getElementById('resetSecuritySettings');
    if (resetSecuritySettings) {
        resetSecuritySettings.addEventListener('click', function() {
            if (confirm('Are you sure you want to reset all security settings to default values?')) {
                // In a real application, this would reset the form with default values
                alert('Security settings reset to default values.');
            }
        });
    }
    
    // API Key Management
    const toggleKeyBtns = document.querySelectorAll('.toggle-key-btn');
    toggleKeyBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const keyCell = this.parentElement;
            const keyMask = keyCell.querySelector('.api-key-mask');
            const keyValue = keyCell.querySelector('.api-key-value');
            const icon = this.querySelector('i');
            
            if (keyMask.style.display === 'none') {
                keyMask.style.display = 'inline';
                keyValue.style.display = 'none';
                icon.className = 'fas fa-eye';
            } else {
                keyMask.style.display = 'none';
                keyValue.style.display = 'inline';
                icon.className = 'fas fa-eye-slash';
            }
        });
    });
    
    // API Key Status Toggle
    const statusToggleBtns = document.querySelectorAll('.action-btn.revoke, .action-btn.activate');
    statusToggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const statusCell = row.querySelector('.status-badge');
            const actionsCell = row.querySelector('.actions-cell');
            
            if (this.classList.contains('revoke')) {
                statusCell.className = 'status-badge status-inactive';
                statusCell.textContent = 'Inactive';
                
                // Replace revoke button with activate button
                this.innerHTML = '<i class="fas fa-check"></i>';
                this.classList.remove('revoke');
                this.classList.add('activate');
                this.title = 'Activate Key';
                
                alert('API key revoked successfully.');
            } else {
                statusCell.className = 'status-badge status-active';
                statusCell.textContent = 'Active';
                
                // Replace activate button with revoke button
                this.innerHTML = '<i class="fas fa-ban"></i>';
                this.classList.remove('activate');
                this.classList.add('revoke');
                this.title = 'Revoke Key';
                
                alert('API key activated successfully.');
            }
        });
    });
    
    // Generate API Key
    const generateApiKey = document.getElementById('generateApiKey');
    const apiKeyModal = document.getElementById('apiKeyModal');
    const apiKeySuccessModal = document.getElementById('apiKeySuccessModal');
    const closeApiKeyModal = document.getElementById('closeApiKeyModal');
    const cancelApiKey = document.getElementById('cancelApiKey');
    const apiKeyForm = document.getElementById('apiKeyForm');
    const closeSuccessModal = document.getElementById('closeSuccessModal');
    const doneApiKey = document.getElementById('doneApiKey');
    const copyApiKey = document.getElementById('copyApiKey');
    
    // Open API Key Modal
    if (generateApiKey) {
        generateApiKey.addEventListener('click', function() {
            openModal(apiKeyModal);
        });
    }
    
    // Close API Key Modal
    if (closeApiKeyModal) {
        closeApiKeyModal.addEventListener('click', function() {
            closeModal(apiKeyModal);
        });
    }
    
    if (cancelApiKey) {
        cancelApiKey.addEventListener('click', function() {
            closeModal(apiKeyModal);
        });
    }
    
    // Submit API Key Form
    if (apiKeyForm) {
        apiKeyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const keyName = document.getElementById('keyName').value;
            const keyExpiry = document.getElementById('keyExpiry').value;
            
            // Generate random API key (for demo)
            const apiKey = generateRandomString(32);
            
            // Set values in success modal
            document.getElementById('newApiKey').textContent = apiKey;
            document.getElementById('displayKeyName').textContent = keyName;
            document.getElementById('displayKeyDate').textContent = new Date().toISOString().split('T')[0];
            
            if (keyExpiry === 'never') {
                document.getElementById('displayKeyExpiry').textContent = 'Never';
            } else {
                const expiryDate = new Date();
                expiryDate.setDate(expiryDate.getDate() + parseInt(keyExpiry));
                document.getElementById('displayKeyExpiry').textContent = expiryDate.toISOString().split('T')[0];
            }
            
            // Close form modal and open success modal
            closeModal(apiKeyModal);
            openModal(apiKeySuccessModal);
        });
    }
    
    // Close Success Modal
    if (closeSuccessModal) {
        closeSuccessModal.addEventListener('click', function() {
            closeModal(apiKeySuccessModal);
        });
    }
    
    if (doneApiKey) {
        doneApiKey.addEventListener('click', function() {
            closeModal(apiKeySuccessModal);
        });
    }
    
    // Copy API Key
    if (copyApiKey) {
        copyApiKey.addEventListener('click', function() {
            const apiKeyText = document.getElementById('newApiKey').textContent;
            navigator.clipboard.writeText(apiKeyText).then(function() {
                this.innerHTML = '<i class="fas fa-check"></i> Copied!';
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-copy"></i> Copy';
                }, 2000);
            }.bind(this)).catch(function(err) {
                console.error('Failed to copy: ', err);
            });
        });
    }
    
    // Password validation
    const newPassword = document.getElementById('new_password');
    if (newPassword) {
        newPassword.addEventListener('input', validatePassword);
    }
    
    // Helper Functions
    function openModal(modal) {
        modal.classList.add('open');
    }
    
    function closeModal(modal) {
        modal.classList.remove('open');
    }
    
    function generateRandomString(length) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';
        for (let i = 0; i < length; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    }
    
    function validatePassword() {
        const password = this.value;
        
        // Update requirement indicators
        document.getElementById('length-requirement').classList.toggle('met', password.length >= 8);
        document.getElementById('uppercase-requirement').classList.toggle('met', /[A-Z]/.test(password));
        document.getElementById('lowercase-requirement').classList.toggle('met', /[a-z]/.test(password));
        document.getElementById('number-requirement').classList.toggle('met', /[0-9]/.test(password));
        document.getElementById('special-requirement').classList.toggle('met', /[^A-Za-z0-9]/.test(password));
    }
});
</script>

<!-- Add additional styles specific to the settings page -->
<style>
/* Settings Tabs */
.settings-tabs {
    background-color: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.tabs-navigation {
    display: flex;
    border-bottom: 1px solid #eee;
    background-color: #f9fafb;
}

.tab-button {
    padding: 1rem 1.5rem;
    border: none;
    background: none;
    font-weight: 500;
    color: var(--gray);
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border-bottom: 2px solid transparent;
}

.tab-button:hover {
    color: var(--admin-primary);
}

.tab-button.active {
    color: var(--admin-primary);
    border-bottom: 2px solid var(--admin-primary);
    background-color: white;
}

.tab-button i {
    font-size: 1rem;
}

.tabs-content {
    padding: 1.5rem;
}

.tab-pane {
    display: none;
}

.tab-pane.active {
    display: block;
}

/* Form Sections */
.form-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #eee;
}

.form-section:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.section-title {
    font-size: 1.2rem;
    margin-bottom: 1.5rem;
    color: var(--black);
}

/* Form Switches */
.form-switches {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-switch {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: var(--transition);
}

.slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: var(--transition);
}

input:checked + .slider {
    background-color: var(--admin-primary);
}

input:focus + .slider {
    box-shadow: 0 0 1px var(--admin-primary);
}

input:checked + .slider:before {
    transform: translateX(26px);
}

.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

.switch-label {
    display: flex;
    flex-direction: column;
}

.switch-label span {
    font-weight: 500;
}

.switch-label small {
    color: var(--gray);
    font-size: 0.8rem;
}

/* Maintenance Mode Switch */
.maintenance-switch {
    padding: 1rem;
    border: 1px solid #ff9800;
    background-color: #fff8e1;
    border-radius: var(--border-radius);
}

/* File Upload */
.file-upload-group {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.file-input {
    display: none;
}

.file-preview {
    width: 120px;
    height: 120px;
    border: 1px dashed #ddd;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.file-preview img {
    max-width: 100%;
    max-height: 100%;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
}

.file-name {
    font-size: 0.85rem;
    color: var(--gray);
}

/* Color Input Group */
.color-input-group {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.color-input {
    width: 50px;
    height: 40px;
    padding: 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.color-text {
    flex: 1;
}

/* Form Text */
.form-text {
    font-size: 0.8rem;
    color: var(--gray);
    margin-top: 0.25rem;
}

/* API Key Management */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.api-key-cell {
    position: relative;
    width: 250px;
}

.api-key-mask,
.api-key-value {
    font-family: monospace;
}

.api-key-value {
    display: none;
}

.toggle-key-btn {
    background: none;
    border: none;
    cursor: pointer;
    color: var(--gray);
    margin-left: 0.5rem;
}

/* API Key Success Modal */
.success-message {
    margin-bottom: 1.5rem;
}

.api-key-display {
    background-color: #f5f5f5;
    padding: 1rem;
    border-radius: var(--border-radius);
    font-family: monospace;
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
}

.api-key-display code {
    flex: 1;
    overflow-x: auto;
    white-space: nowrap;
    font-size: 1rem;
}

.copy-btn {
    background-color: var(--admin-primary);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    margin-left: 1rem;
    white-space: nowrap;
}

.key-info {
    margin-bottom: 1.5rem;
}

.key-info p {
    margin: 0.5rem 0;
}

/* Documentation Links */
.documentation-links {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.doc-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5rem;
    background-color: #f9fafb;
    border-radius: var(--border-radius);
    text-decoration: none;
    color: var(--black);
    transition: var(--transition);
    width: calc(33.33% - 1rem);
}

.doc-link:hover {
    background-color: #f0f0f0;
    transform: translateY(-3px);
}

.doc-link i {
    font-size: 2rem;
    color: var(--admin-primary);
    margin-bottom: 1rem;
}

/* Profile Edit */
.profile-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.profile-avatar {
    position: relative;
}

.avatar-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
}

.change-avatar-btn {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-color: var(--admin-primary);
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.profile-info h3 {
    margin: 0 0 0.25rem 0;
}

.profile-email,
.profile-role {
    margin: 0;
    color: var(--gray);
}

.profile-role {
    background-color: #e8f5e9;
    color: #2e7d32;
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 50px;
    font-size: 0.75rem;
    margin-top: 0.5rem;
}

/* Password Requirements */
.password-requirements {
    margin: 1.5rem 0;
    padding: 1rem;
    background-color: #f9fafb;
    border-radius: var(--border-radius);
}

.requirements-title {
    margin: 0 0 0.75rem 0;
    font-weight: 500;
}

.requirements-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.requirement {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    color: var(--gray);
}

.requirement:before {
    content: "✕";
    color: #f44336;
    margin-right: 0.5rem;
}

.requirement.met {
    color: var(--black);
}

.requirement.met:before {
    content: "✓";
    color: #4caf50;
}

/* Checkbox Group */
.checkbox-group {
    display: flex;
    gap: 1.5rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .tabs-navigation {
        flex-wrap: wrap;
    }
    
    .tab-button {
        flex: 1;
        justify-content: center;
    }
    
    .color-input-group {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .documentation-links {
        flex-direction: column;
    }
    
    .doc-link {
        width: 100%;
    }
    
    .profile-header {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<?php include '../includes/footer.php'; ?>

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

// Sample users data - in a real application, this would come from a database
$users = [
    [
        'id' => 1,
        'username' => 'admin',
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@trusticle.com',
        'role' => 'admin',
        'status' => 'active',
        'created' => '2023-01-01'
    ],
    [
        'id' => 2,
        'username' => 'john_doe',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'role' => 'user',
        'status' => 'active',
        'created' => '2023-01-15'
    ],
    [
        'id' => 3,
        'username' => 'jane_smith',
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane@example.com',
        'role' => 'user',
        'status' => 'active',
        'created' => '2023-02-10'
    ],
    [
        'id' => 4,
        'username' => 'robert_johnson',
        'first_name' => 'Robert',
        'last_name' => 'Johnson',
        'email' => 'robert@example.com',
        'role' => 'user',
        'status' => 'inactive',
        'created' => '2023-02-20'
    ],
    [
        'id' => 5,
        'username' => 'sarah_williams',
        'first_name' => 'Sarah',
        'last_name' => 'Williams',
        'email' => 'sarah@example.com',
        'role' => 'user',
        'status' => 'active',
        'created' => '2023-03-05'
    ],
    [
        'id' => 6,
        'username' => 'michael_brown',
        'first_name' => 'Michael',
        'last_name' => 'Brown',
        'email' => 'michael@example.com',
        'role' => 'admin',
        'status' => 'active',
        'created' => '2023-03-20'
    ],
    [
        'id' => 7,
        'username' => 'emily_davis',
        'first_name' => 'Emily',
        'last_name' => 'Davis',
        'email' => 'emily@example.com',
        'role' => 'user',
        'status' => 'active',
        'created' => '2023-04-10'
    ],
    [
        'id' => 8,
        'username' => 'david_miller',
        'first_name' => 'David',
        'last_name' => 'Miller',
        'email' => 'david@example.com',
        'role' => 'user',
        'status' => 'active',
        'created' => '2023-04-25'
    ]
];

// Include the header
include '../includes/admin_header.php';
?>

<!-- Main Content -->
<div class="main-content">
    <div class="page-header">
        <h1 class="header">User Management</h1>
        <button class="btn-primary" id="addUserBtn">
            <i class="fas fa-plus"></i> Add New User
        </button>
    </div>
    
    <!-- Search and Filter -->
    <div class="filter-bar">
        <div class="search-container">
            <input type="text" id="userSearch" class="search-input" placeholder="Search users...">
            <button class="search-button"><i class="fas fa-search"></i></button>
        </div>
        
        <div class="filter-options">
            <select id="roleFilter" class="filter-select">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            
            <select id="statusFilter" class="filter-select">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>
    
    <!-- Users Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="data-table" id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr data-id="<?php echo $user['id']; ?>">
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="role-badge role-<?php echo $user['role']; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $user['status']; ?>">
                                    <?php echo ucfirst($user['status']); ?>
                                </span>
                            </td>
                            <td><?php echo $user['created']; ?></td>
                            <td class="actions-cell">
                                <button class="action-btn edit" title="Edit User" data-id="<?php echo $user['id']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <?php if ($user['id'] !== 1): // Prevent deleting main admin ?>
                                    <button class="action-btn delete" title="Delete User" data-id="<?php echo $user['id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                <?php endif; ?>
                                <button class="action-btn change-role" title="Change Role" data-id="<?php echo $user['id']; ?>" data-role="<?php echo $user['role']; ?>">
                                    <i class="fas fa-user-shield"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div class="modal-overlay" id="userModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="modalTitle">Add New User</h2>
            <button class="modal-close" id="closeUserModal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="userForm">
                <input type="hidden" id="userId" name="userId" value="">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" id="firstName" name="firstName" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" id="lastName" name="lastName" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="password" class="form-label password-field">Password</label>
                        <input type="password" id="password" name="password" class="form-control">
                        <small class="edit-mode-text">Leave blank to keep current password</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-secondary" id="cancelUserForm">Cancel</button>
                    <button type="submit" class="btn-primary">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Role Confirmation Modal -->
<div class="modal-overlay" id="changeRoleModal">
    <div class="modal-container modal-sm">
        <div class="modal-header">
            <h2>Change User Role</h2>
            <button class="modal-close" id="closeRoleModal">&times;</button>
        </div>
        <div class="modal-body">
            <p id="roleChangeMessage">Are you sure you want to change this user's role?</p>
            
            <div class="form-actions">
                <button type="button" class="btn-secondary" id="cancelRoleChange">Cancel</button>
                <button type="button" class="btn-primary" id="confirmRoleChange">Change Role</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Confirmation Modal -->
<div class="modal-overlay" id="deleteUserModal">
    <div class="modal-container modal-sm">
        <div class="modal-header">
            <h2>Delete User</h2>
            <button class="modal-close" id="closeDeleteModal">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this user? This action cannot be undone.</p>
            
            <div class="form-actions">
                <button type="button" class="btn-secondary" id="cancelDelete">Cancel</button>
                <button type="button" class="btn-danger" id="confirmDelete">Delete User</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // User Modal Elements
    const userModal = document.getElementById('userModal');
    const userForm = document.getElementById('userForm');
    const addUserBtn = document.getElementById('addUserBtn');
    const closeUserModal = document.getElementById('closeUserModal');
    const cancelUserForm = document.getElementById('cancelUserForm');
    const modalTitle = document.getElementById('modalTitle');
    const userId = document.getElementById('userId');
    const firstName = document.getElementById('firstName');
    const lastName = document.getElementById('lastName');
    const username = document.getElementById('username');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const role = document.getElementById('role');
    const status = document.getElementById('status');
    const editModeText = document.querySelector('.edit-mode-text');
    
    // Change Role Modal Elements
    const changeRoleModal = document.getElementById('changeRoleModal');
    const closeRoleModal = document.getElementById('closeRoleModal');
    const cancelRoleChange = document.getElementById('cancelRoleChange');
    const confirmRoleChange = document.getElementById('confirmRoleChange');
    const roleChangeMessage = document.getElementById('roleChangeMessage');
    
    // Delete User Modal Elements
    const deleteUserModal = document.getElementById('deleteUserModal');
    const closeDeleteModal = document.getElementById('closeDeleteModal');
    const cancelDelete = document.getElementById('cancelDelete');
    const confirmDelete = document.getElementById('confirmDelete');
    
    // Search and Filter Elements
    const userSearch = document.getElementById('userSearch');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    
    // User Table
    const usersTable = document.getElementById('usersTable');
    
    // Current target user for actions
    let targetUserId = null;
    let targetUserRole = null;
    
    // Add New User
    addUserBtn.addEventListener('click', function() {
        modalTitle.textContent = 'Add New User';
        userId.value = '';
        userForm.reset();
        editModeText.style.display = 'none';
        password.required = true;
        
        openModal(userModal);
    });
    
    // Edit User
    document.querySelectorAll('.action-btn.edit').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            modalTitle.textContent = 'Edit User';
            editModeText.style.display = 'block';
            password.required = false;
            
            // In a real app, you would fetch user data from the server
            // For demo, we'll use the sample data
            const userData = getUserData(id);
            if (userData) {
                userId.value = userData.id;
                firstName.value = userData.first_name;
                lastName.value = userData.last_name;
                username.value = userData.username;
                email.value = userData.email;
                role.value = userData.role;
                status.value = userData.status;
                password.value = '';
                
                openModal(userModal);
            }
        });
    });
    
    // Change Role
    document.querySelectorAll('.action-btn.change-role').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const currentRole = this.getAttribute('data-role');
            targetUserId = id;
            targetUserRole = currentRole;
            
            const newRole = currentRole === 'admin' ? 'user' : 'admin';
            roleChangeMessage.textContent = `Are you sure you want to change this user's role from ${currentRole} to ${newRole}?`;
            
            openModal(changeRoleModal);
        });
    });
    
    // Delete User
    document.querySelectorAll('.action-btn.delete').forEach(button => {
        button.addEventListener('click', function() {
            targetUserId = this.getAttribute('data-id');
            openModal(deleteUserModal);
        });
    });
    
    // Form Submission
    userForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // In a real app, you would send the form data to the server
        // For demo purposes, we'll just show an alert
        if (userId.value) {
            alert(`User ID ${userId.value} updated successfully!`);
        } else {
            alert('New user created successfully!');
        }
        
        closeModal(userModal);
    });
    
    // Confirm Role Change
    confirmRoleChange.addEventListener('click', function() {
        const newRole = targetUserRole === 'admin' ? 'user' : 'admin';
        
        // In a real app, you would send this to the server
        // For demo purposes, we'll just show an alert
        alert(`User ID ${targetUserId} role changed from ${targetUserRole} to ${newRole}!`);
        
        // Update the UI for demo purposes
        const row = document.querySelector(`tr[data-id="${targetUserId}"]`);
        if (row) {
            const roleBadge = row.querySelector('.role-badge');
            roleBadge.className = `role-badge role-${newRole}`;
            roleBadge.textContent = newRole.charAt(0).toUpperCase() + newRole.slice(1);
            
            const changeRoleBtn = row.querySelector('.action-btn.change-role');
            changeRoleBtn.setAttribute('data-role', newRole);
        }
        
        closeModal(changeRoleModal);
    });
    
    // Confirm Delete
    confirmDelete.addEventListener('click', function() {
        // In a real app, you would send this to the server
        // For demo purposes, we'll just show an alert
        alert(`User ID ${targetUserId} deleted successfully!`);
        
        // Remove the row from the table for demo purposes
        const row = document.querySelector(`tr[data-id="${targetUserId}"]`);
        if (row) {
            row.remove();
        }
        
        closeModal(deleteUserModal);
    });
    
    // Close Modals
    closeUserModal.addEventListener('click', () => closeModal(userModal));
    cancelUserForm.addEventListener('click', () => closeModal(userModal));
    closeRoleModal.addEventListener('click', () => closeModal(changeRoleModal));
    cancelRoleChange.addEventListener('click', () => closeModal(changeRoleModal));
    closeDeleteModal.addEventListener('click', () => closeModal(deleteUserModal));
    cancelDelete.addEventListener('click', () => closeModal(deleteUserModal));
    
    // Search Functionality
    userSearch.addEventListener('input', filterTable);
    roleFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);
    
    // Helper Functions
    function openModal(modal) {
        modal.classList.add('open');
    }
    
    function closeModal(modal) {
        modal.classList.remove('open');
    }
    
    function getUserData(id) {
        // In a real app, this would be an AJAX request
        // For demo, we search through our sample data
        return <?php echo json_encode($users); ?>.find(user => user.id == id);
    }
    
    function filterTable() {
        const searchTerm = userSearch.value.toLowerCase();
        const roleValue = roleFilter.value;
        const statusValue = statusFilter.value;
        
        const rows = usersTable.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const name = row.cells[1].textContent.toLowerCase();
            const username = row.cells[2].textContent.toLowerCase();
            const email = row.cells[3].textContent.toLowerCase();
            const role = row.cells[4].textContent.trim().toLowerCase();
            const status = row.cells[5].textContent.trim().toLowerCase();
            
            const matchesSearch = name.includes(searchTerm) || 
                                  username.includes(searchTerm) || 
                                  email.includes(searchTerm);
                                  
            const matchesRole = roleValue === '' || role === roleValue;
            const matchesStatus = statusValue === '' || status === statusValue;
            
            if (matchesSearch && matchesRole && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
});
</script>

<!-- Add additional styles for the user management page -->
<style>
/* Role Badges */
.role-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 500;
}

.role-admin {
    background-color: #e8f5e9;
    color: #2e7d32;
}

.role-user {
    background-color: #e3f2fd;
    color: #1565c0;
}

/* Status Badges */
.status-active {
    background-color: #e8f5e9;
    color: #2e7d32;
}

.status-inactive {
    background-color: #f5f5f5;
    color: #757575;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

/* Filter Bar */
.filter-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    background-color: white;
    padding: 1rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.filter-options {
    display: flex;
    gap: 1rem;
}

.filter-select {
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: white;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: var(--transition);
}

.modal-overlay.open {
    opacity: 1;
    visibility: visible;
}

.modal-container {
    background-color: white;
    border-radius: var(--border-radius);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-sm {
    max-width: 400px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #eee;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.25rem;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--gray);
}

.modal-body {
    padding: 1.5rem;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 1.5rem;
}

.btn-secondary {
    background-color: #f5f5f5;
    color: var(--black);
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
}

.btn-secondary:hover {
    background-color: #e0e0e0;
}

.btn-danger {
    background-color: var(--red);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
}

.btn-danger:hover {
    background-color: #d32f2f;
}

.edit-mode-text {
    display: none;
    color: var(--gray);
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

/* Search Container */
.search-container {
    display: flex;
    align-items: center;
}

.search-input {
    padding: 0.5rem 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    width: 300px;
}

.search-button {
    background: none;
    border: none;
    color: var(--gray);
    cursor: pointer;
    margin-left: -30px;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .filter-bar {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .search-input {
        width: 100%;
    }
    
    .filter-options {
        width: 100%;
    }
    
    .filter-select {
        flex: 1;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
}
</style>

<?php include '../includes/footer.php'; ?>

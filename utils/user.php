<?php
/**
 * User utility functions
 * DRY principle implementation - centralize common user operations
 */

/**
 * Get user information by ID
 * 
 * @param int $userId The user ID
 * @return array|false User data array or false if not found
 */
function getUserInfo($userId) {
    global $conn;
    
    // In a real application, this would retrieve data from a database
    // For the demo, we'll return dummy data
    return [
        'id' => $userId,
        'first_name' => 'Rhea',
        'last_name' => 'Manipon',
        'email' => 'trusticle@mail.com',
        'username' => 'rheamanipan',
        'birthdate' => '1990-01-01',
        'role' => $userId == 1 ? 'admin' : 'user' // Assume ID 1 is admin
    ];
}

/**
 * Check if a user has admin role
 * 
 * @param int $userId User ID
 * @return bool True if user is admin, false otherwise
 */
function isAdmin($userId) {
    $userInfo = getUserInfo($userId);
    return isset($userInfo['role']) && $userInfo['role'] === 'admin';
}

/**
 * Get all users (for admin dashboard)
 * 
 * @param array $filters Optional filters
 * @return array List of users
 */
function getAllUsers($filters = []) {
    global $conn;
    
    // In a real application, this would fetch from the database with filters
    // For the demo, we'll return dummy data
    return [
        [
            'id' => 1,
            'first_name' => 'Admin',
            'last_name' => 'User',
            'username' => 'admin',
            'email' => 'admin@trusticle.com',
            'role' => 'admin',
            'status' => 'active',
            'created_at' => '2023-01-01'
        ],
        [
            'id' => 2,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'john_doe',
            'email' => 'john@example.com',
            'role' => 'user',
            'status' => 'active',
            'created_at' => '2023-01-15'
        ],
        [
            'id' => 3,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'username' => 'jane_smith',
            'email' => 'jane@example.com',
            'role' => 'user',
            'status' => 'active',
            'created_at' => '2023-02-10'
        ]
    ];
}

/**
 * Log user activity
 * 
 * @param int $userId User ID
 * @param string $action Description of the action
 * @return bool Success or failure
 */
function logUserActivity($userId, $action) {
    global $conn;
    
    // In a real application, this would insert into the activity_logs table
    // For demo purposes, return true
    return true;
}

/**
 * Get user activity logs
 * 
 * @param array $filters Optional filters
 * @return array List of activity logs
 */
function getUserActivityLogs($filters = []) {
    global $conn;
    
    // In a real application, this would fetch from the database with filters
    // For the demo, we'll return dummy data
    return [
        [
            'id' => 1,
            'user_id' => 2,
            'user_name' => 'John Doe',
            'action' => 'Submitted a new article',
            'timestamp' => '2023-05-22 09:15:30',
            'time_ago' => '10 minutes ago'
        ],
        [
            'id' => 2,
            'user_id' => 1,
            'user_name' => 'Admin User',
            'action' => 'Approved article #1045',
            'timestamp' => '2023-05-22 09:00:00',
            'time_ago' => '25 minutes ago'
        ],
        [
            'id' => 3,
            'user_id' => 3,
            'user_name' => 'Jane Smith',
            'action' => 'Updated profile information',
            'timestamp' => '2023-05-22 08:45:00',
            'time_ago' => '40 minutes ago'
        ]
    ];
}

/**
 * Create a new user (admin function)
 * 
 * @param array $userData User data
 * @return array Result with status and message
 */
function createUser($userData) {
    global $conn;
    
    // In a real application, this would validate and insert into the database
    // For demo purposes, return success
    return [
        'status' => 'success',
        'message' => 'User created successfully'
    ];
}

/**
 * Update a user (admin function)
 * 
 * @param int $userId User ID
 * @param array $userData User data to update
 * @return array Result with status and message
 */
function updateUser($userId, $userData) {
    global $conn;
    
    // In a real application, this would validate and update the database
    // For demo purposes, return success
    return [
        'status' => 'success',
        'message' => 'User updated successfully'
    ];
}

/**
 * Delete a user (admin function)
 * 
 * @param int $userId User ID
 * @return array Result with status and message
 */
function deleteUser($userId) {
    global $conn;
    
    // In a real application, this would delete or mark as deleted in the database
    // For demo purposes, return success
    return [
        'status' => 'success',
        'message' => 'User deleted successfully'
    ];
}

/**
 * Change user role (admin function)
 * 
 * @param int $userId User ID
 * @param string $newRole New role (admin or user)
 * @return array Result with status and message
 */
function changeUserRole($userId, $newRole) {
    global $conn;
    
    // In a real application, this would update the role in the database
    // For demo purposes, return success
    return [
        'status' => 'success',
        'message' => "User role changed to {$newRole} successfully"
    ];
}

/**
 * Update user profile
 * 
 * @param int $userId User ID
 * @param array $data Profile data to update
 * @return array Result with status and message
 */
function updateUserProfile($userId, $data) {
    global $conn;
    
    // In a real application, this would update the database
    // For the demo, we'll just return success
    $result = [
        'status' => 'success',
        'message' => 'Profile updated successfully!'
    ];
    
    return $result;
}

/**
 * Update user password
 * 
 * @param int $userId User ID
 * @param string $currentPassword Current password
 * @param string $newPassword New password
 * @return array Result with status and message
 */
function updateUserPassword($userId, $currentPassword, $newPassword) {
    global $conn;
    
    // In a real application, this would verify the current password
    // and update to the new password in the database
    // For the demo, we'll simulate successful validation
    
    $result = [
        'status' => 'success',
        'message' => 'Password updated successfully!'
    ];
    
    // Simulate failure scenario for demo purposes
    if ($currentPassword === 'wrongpassword') {
        $result = [
            'status' => 'error',
            'message' => 'Current password is incorrect'
        ];
    } else if (strlen($newPassword) < 8) {
        $result = [
            'status' => 'error',
            'message' => 'New password must be at least 8 characters'
        ];
    } else if ($newPassword === $currentPassword) {
        $result = [
            'status' => 'error',
            'message' => 'New password must be different from current password'
        ];
    }
    
    return $result;
}
?>

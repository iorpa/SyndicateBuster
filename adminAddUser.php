<?php
session_start();    
require_once "config.php";

// Check if user is logged in and is admin (role_id = 5)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 5) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Initialize variables
$error = '';
$success = '';
$form_data = [
    'username' => '',
    'email' => '',
    'role_id' => '',
    'location' => '',
    'status' => 'active'
];

// Fetch roles for dropdown (excluding admin role if needed)
$roles_result = $conn->query("SELECT * FROM role WHERE role_id != 5 ORDER BY role_name");
$roles = $roles_result->fetch_all(MYSQLI_ASSOC);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role_id = intval($_POST['role_id']);
    $location = trim($_POST['location']);
    $status = $_POST['status'];
    
    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } else {
        // Check if username or email already exists
        $check_stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = 'Username or email already exists';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $insert_stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role_id, location, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $insert_stmt->bind_param("sssiss", $username, $email, $hashed_password, $role_id, $location, $status);
            
            if ($insert_stmt->execute()) {
                $success = 'User added successfully!';
                // Clear form data
                $form_data = [
                    'username' => '',
                    'email' => '',
                    'role_id' => '',
                    'location' => '',
                    'status' => 'active'
                ];
            } else {
                $error = 'Error adding user: ' . $conn->error;
            }
            
            $insert_stmt->close();
        }
        $check_stmt->close();
    }
    
    // Preserve form data on error
    if ($error) {
        $form_data = [
            'username' => $username,
            'email' => $email,
            'role_id' => $role_id,
            'location' => $location,
            'status' => $status
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User - Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

</head>
<body>
    <div class="container">
        <div class="top">Govt Market Monitor - Syndicate-Buster Portal</div>
        <div class="dashboard">
            <div class="header">
                <h2>Add New User</h2>
                <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
            </div>
            
            <div class="nav-menu">
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="adminManageUsers.php">Manage Users</a>
                <a href="add_user.php" style="background: rgba(255,255,255,0.1);">Add User</a>
                <a href="adminPriceCap.php">Price Caps</a>
                <a href="adminViolation.php">Violations</a>
                <a href="adminBlacklist.php">Blacklist</a>
                <a href="adminReports.php">Reports</a>
            </div>
            
            <div class="form-container">
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" class="form-control" 
                                   value="<?php echo htmlspecialchars($form_data['username']); ?>" 
                                   required maxlength="50">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email </label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   value="<?php echo htmlspecialchars($form_data['email']); ?>" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control" 
                                   required minlength="6">
                            <small>At least 6 characters</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password </label>
                            <input type="password" id="confirm_password" name="confirm_password" 
                                   class="form-control" required minlength="6">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="role_id">Role</label>
                            <select id="role_id" name="role_id" class="form-control" required>
                                <option value="">Select Role</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?php echo $role['role_id']; ?>"
                                        <?php echo $form_data['role_id'] == $role['role_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($role['role_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" id="location" name="location" class="form-control" 
                                   value="<?php echo htmlspecialchars($form_data['location']); ?>"
                                   maxlength="100">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="active" <?php echo $form_data['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo $form_data['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            <option value="suspended" <?php echo $form_data['status'] == 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="margin-top: 30px;">
                        <a href="adminManageUsers.php" class="btn-cancel">Cancel</a>
                        <button type="submit" class="btn-submit">Add User</button>
                    </div>
                </form>
            </div>
            
            <div class="footer">
                <p>Syndicate Buster Admin Panel © 2024</p>
            </div>
        </div>
    </div>
    
   
</body>
</html>
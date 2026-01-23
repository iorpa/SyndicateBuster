<?php
session_start();    
require_once "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 5) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$error = '';
$success = '';
$form_data = [
    'username' => '',
    'email' => '',
    'role_id' => '',
    'location' => '',
    'status' => 'active'
];

$roles_result = $conn->query("SELECT * FROM roles WHERE role_id != 5 ORDER BY role_name");
$roles = $roles_result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role_id = intval($_POST['role_id']);
    $location = trim($_POST['location']);
    $status = $_POST['status'];
        if (empty($username) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } else {
        $check_stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = 'Username or email already exists';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $insert_stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role_id, location, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $insert_stmt->bind_param("sssiss", $username, $email, $hashed_password, $role_id, $location, $status);
            
            if ($insert_stmt->execute()) {
                $success = 'User added successfully!';
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
<link rel="stylesheet" href="../css/page.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/text.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/cards.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/error.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/button.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/table.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/model.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/form.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <div class="header">Govt Market Monitor - Syndicate-Buster Portal</div>
        <div class="dashboard">
            <div class="userDetailsCard">
                <h2>Add New User</h2>
                <button class="smallBtn Red" onclick="window.location.href='logout.php'">Logout</button>
            </div>
            
             <div class="navCard">
                <a href="../admin/adminDashboard.php" style="background: rgba(255,255,255,0.1);">Dashboard</a>
                <a href="../admin/adminManageUsers.php">Manage Users</a>
                <a href="../admin/adminPriceCap.php">Price Caps</a>
                <a href="../admin/adminViolation.php">Violations</a>
                <a href="../admin/adminBlacklist.php">Blacklist</a>
                <a href="../admin/adminReports.php">Reports</a>
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
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="active" <?php echo $form_data['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo $form_data['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            <option value="suspended" <?php echo $form_data['status'] == 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                        </select>
                    </div>
                    
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px ">
                    <button type="submit" class="greenBtn"style="height:40px;">Add User</button>
                    <a href="../admin/adminManageUsers.php" class="limebtn"  >Cancel</a>
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
<?php
session_start();    
require_once "config.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 5) {
    header("Location: login.php");
    exit();
}

$total_users_result = $conn->query("SELECT COUNT(*) as total FROM users");
$total_users = $total_users_result->fetch_assoc()['total'];

$total_active_users_result = $conn->query("SELECT COUNT(*) as total FROM users where status = 'active'");
$total_active_users = $total_active_users_result->fetch_assoc()['total'];

$total_suspended_users_result = $conn->query("SELECT COUNT(*) as total FROM users where status = 'suspended'");
$total_suspended_users = $total_suspended_users_result->fetch_assoc()['total'];

$total_banned_users_result = $conn->query("SELECT COUNT(*) as total FROM users where status = 'banned'");
$total_banned_users = $total_banned_users_result->fetch_assoc()['total'];




$users_result = $conn->query(
"SELECT *
FROM users
join role ON users.role_id = role.role_id
ORDER BY user_id ASC;
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <div class="top">Govt Market Monitor - Syndicate-Buster Portal</div>
        
        <div class="dashboard">
            <div class="header">
                <h2>Manage Users</h2>
                                    <button class="logout-btn">Logout</button>

            </div>
            
            <div class="nav-menu">
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="adminManageUsers.php">Manage Users</a>
                <a href="adminPriceCap.php">Price Caps</a>
                <a href="adminViolation.php">Violations</a>
                <a href="adminBlacklist.php">Blacklist</a>
                <a href="adminReports.php">Reports</a>
            </div>
            
            <div class="stats">
                <div class="card">
                    <h3>Total Users</h3>
                    <div class="number"><?php echo $total_users; ?></div>
                </div>
                <div class="card">
                    <h3>Active Users</h3>
                    <div class="number"><?php echo $total_active_users; ?></div>
                </div>
                <div class="card">
                    <h3>Suspended</h3>
                    <div class="number"><?php echo $total_suspended_users; ?></div>
                </div>
                <div class="card">
                    <h3>Banned</h3>
                    <div class="number"><?php echo $total_banned_users; ?></div>
                </div>
            </div>
            
            <div class="card">
                <h2>Search & Filter</h2>
                <div class="filter-grid">
                    <div class="search-box">
                        <input type="text" placeholder="Search by name, email or phone..." id="searchInput">
                        <button class="search-btn" >Search</button>
                    </div>
                    
                    <select class="filter-select" onchange="filterUsers()">
                        <option value="">All Roles</option>
                        <option value="1">Farmer</option>
                        <option value="2">Middleman</option>
                        <option value="3">Wholesaler</option>
                        <option value="4">Retailer</option>
                        <option value="5">Admin</option>
                    </select>
                    
                    <select class="filter-select" onchange="filterUsers()">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="suspended">Suspended</option>
                        <option value="banned">Banned</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="add-buttons">
                    <button class="search-btn" style="background-color: #28a745;">
                        + Add New User
                    </button>
                </div>
            </div>
            
<div class="card">
    <h2>All Users</h2>
    <table class="table-box">
        <thead>
            <tr>
                <th>User</th>
                <th>Role</th>
                <th>Status</th>
                <th>Trust Score</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($users_result->num_rows > 0): ?>
                <?php while ($user = $users_result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">
                                    <?php 
                                    $names = explode(" ", $user['username']);
                                    echo strtoupper($names[0][0] . ($names[1][0] ?? ""));
                                    ?>
                                </div>
                                <div class="tableFlexCol">
                                    <div class="tableBoldText"><?php echo htmlspecialchars($user['username']); ?></div>
                                    <div class="tablesmallText"><?php echo htmlspecialchars($user['email']); ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="tablesmallText">                     
                             <?php echo htmlspecialchars($user['role_name']); ?>
                                </div>
                        </td>
                        <td>
                            <span class="status status-<?php echo strtolower($user['status']); ?>">
                                <?php echo htmlspecialchars($user['status']); ?>
                            </span>
                        </td>
                        <td>
                            <span class="table-trust-score trust-<?php echo ($user['trust_score'] >= 80) ? 'high' : 'low'; ?>">
                                <?php echo $user['trust_score'] . '/100'; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-edit">Edit</button>
                            <button class="btn btn-delete">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination">
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn">3</button>
    </div>
</div>

            
            <div class="footer">
                <p>Syndicate Buster Admin Panel © 2024</p>
            
            </div>
        </div>
    </div>
    

</body>
</html>
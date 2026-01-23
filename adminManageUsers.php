<?php
session_start();    
require_once "../config.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 5) {
    header("Location: login.php");
    exit();
}

$total_users_result = $conn->query("SELECT COUNT(*) as total FROM users");
$total_users = $total_users_result->fetch_assoc()['total'];

$total_active_users_result = $conn->query("SELECT COUNT(*) as total FROM users where account_status = 'active'");
$total_active_users = $total_active_users_result->fetch_assoc()['total'];

$total_suspended_users_result = $conn->query("SELECT COUNT(*) as total FROM users where account_status = 'suspended'");
$total_suspended_users = $total_suspended_users_result->fetch_assoc()['total'];

$total_banned_users_result = $conn->query("SELECT COUNT(*) as total FROM users where account_status = 'banned'");
$total_banned_users = $total_banned_users_result->fetch_assoc()['total'];




$users_result = $conn->query(
"SELECT *
FROM users
join roles ON users.role_id = roles.role_id
ORDER BY user_id ASC;
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
       <link rel="stylesheet" href="../css/page.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/text.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/cards.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/error.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/button.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/table.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/model.css?v=<?php echo time(); ?>">
    </head>
<body>
    <div class="container">
        <div class="header">Govt Market Monitor - Syndicate-Buster Portal</div>
        
        <div class="dashboard">
            <div class="userDetailsCard">
                <h2>Manage Users</h2>
                 <button class="smallBtn Red">Logout</button>

            </div>
            
            <div class="navCard">
                <a href="../admin/adminDashboard.php" >Dashboard</a>
                <a href="../admin/adminManageUsers.php" style="background: rgba(255,255,255,0.1);">Manage Users</a>
                <a href="../admin/adminPriceCap.php">Price Caps</a>
                <a href="../admin/adminViolation.php">Violations</a>
                <a href="../admin/adminBlacklist.php">Blacklist</a>
                <a href="../admin/adminReports.php">Reports</a>
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
            <div style=" display:grid; padding:15px;">
                    <a href="../admin/adminAddUser.php" class="limebtn">+ Add New Use </a>
            </div>

            <div class="gridCard">
                <h2 style="color: #214332; margin-bottom: 15px;">Search & Filter</h2>
                <div class="filter-grid">
                    <div class="search-box">
                        <input type="text" placeholder="Search by name, email or phone..." id="searchInput">
                        <button class="smallBtn Green" >Search</button>
                    </div>
                    
                    <select  class="filterText" onchange="filterUsers()">
                        <option value="">All Roles</option>
                        <option value="1">Farmer</option>
                        <option value="2">Middleman</option>
                        <option value="3">Wholesaler</option>
                        <option value="4">Retailer</option>
                        <option value="5">Admin</option>
                    </select>
                    
                    <select onchange="filterUsers()"  class="filterText">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="suspended">Suspended</option>
                        <option value="banned">Banned</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
             </div>   
            
            
            
<div class="card">
    <div class="table-box">
    <h2 style="color: #214332; margin-bottom: 15px;">All Users</h2>
    <table>
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
                            <span class="tableBoldText <?php echo strtolower($user['account_status']); ?>">
                                <?php echo htmlspecialchars($user['account_status']); ?>
                            </span>
                        </td>
                        <td>
                            <span class="table-trust-score trust-<?php echo ($user['trust_score'] >= 80) ? 'high' : 'low'; ?>">
                                <?php echo $user['trust_score'] . '/100'; ?>
                            </span>
                        </td>
                        <td>
                            <button class="cardBtn Cyan">Edit</button>
                            <button class="cardBtn Red">Delete</button>
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
            </div>

            <!--page-->
</div>

            
            <div class="footer">
                <p>Syndicate Buster Admin Panel © 2026</p>
            
            </div>
        </div>
    </div>
    

</body>
</html>
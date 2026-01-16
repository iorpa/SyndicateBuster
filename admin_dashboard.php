<?php
session_start();    
require_once "config.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 5) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$total_users_result = $conn->query("SELECT COUNT(*) as total FROM users");
$total_users = $total_users_result->fetch_assoc()['total'];

$vendors_result = $conn->query("SELECT COUNT(*) as count FROM users WHERE role_id IN (1,2,3,4)");
$vendors = $vendors_result->fetch_assoc()['count'];

$today_trans_result  = $conn->query("SELECT COUNT(*) as count FROM transactions WHERE DATE(transaction_date) = CURDATE()");
$today_transactions = $today_trans_result->fetch_assoc()['count'];

$blacklisted_result = $conn->query("SELECT COUNT(*) as count FROM syndicate_blacklist");
$blacklisted_count = $blacklisted_result->fetch_assoc()['count'];

$revenue_result = $conn->query("SELECT SUM(unit_price*quantity) as total_revenue FROM transactions WHERE DATE(transaction_date) = CURDATE()");
$today_revenue = $revenue_result->fetch_assoc()['total_revenue'];


$recent_users = $conn->query(
"SELECT *
FROM users
JOIN role ON users.role_id = role.role_id
ORDER BY users.created_at DESC
LIMIT 5;
");
$recent_transactions = $conn->query("SELECT 
    t.*,
    s.username as seller_name,
    b.username as buyer_name,
    (
        SELECT c.name 
        FROM batches bt 
        JOIN commodities c ON bt.commodities_id = c.commodities_id 
        WHERE bt.owner_id = t.seller_id 
        ORDER BY bt.batch_id DESC 
        LIMIT 1
    ) as commodity_name
FROM transactions t
INNER JOIN users s ON t.seller_id = s.user_id
INNER JOIN users b ON t.buyer_id = b.user_id
ORDER BY t.transaction_date DESC 
LIMIT 5");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">

        <div class="top">Govt Market Monitor - Syndicate-Buster Portal</div>
        <div class="dashboard">
            <div class="header">
                 <h2>Welcome <?php echo htmlspecialchars($username); ?></h1>
                <button class="logout-btn">Logout</button>
            </div>
    
             <div class="nav-menu">
                <a href="admin_dashboard.php" style="background: rgba(255,255,255,0.1);">Dashboard</a>
                <a href="ManageUsers.php">Manage Users</a>
                <a href="PriceCap.php">Price Caps</a>
                <a href="violation.php">Violations</a>
                <a href="Blacklist.php">Blacklist</a>
                <a href="reports.php">Reports</a>
            </div>
    <div class="stats">
        <div class="card">
            <h3>Total Users </h3>
            <div class="number"><?php echo $total_users; ?></div>
        </div>
        <div class="card">
            <h3>Vendors</h3>
            <div class="number"><?php echo $vendors; ?></div>
        </div>
        <div class="card">
            <h3>Today's Transactions</h3>
            <div class="number"><?php echo $today_transactions; ?></div>
        </div>
        <div class="card">
            <h3>Blacklisted</h3>
            <div class="number"><?php echo $blacklisted_count; ?></div>
        </div>
       <!-- <div class="card">
            <h3>Pending Reports</h3>
            <div class="number">8</div>
        </div>-->
        <div class="card">
            <h3>Today's Revenue</h3>
            <div class="number">৳ <?php echo $today_revenue; ?></div>
        </div>
    </div>
    
    <div class="quick-actions">
        <h2>Quick Actions</h2>
        <div class="actions-grid">
            <a class="action-btn">Add User</a>
            <a class="action-btn">Set Price Cap</a>
            <a class="action-btn">View Reports</a>
            <a class="action-btn">View Blocklist</a>
        </div>
    </div>
    
    <div class="tables-section">
        <div class="table-box">
            <h2>Recent Users</h2>
            <table>
            <thead>
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Location</th>
                <th>Status</th>
            </tr>
            </thead>          
               <tbody>
                      <?php if($recent_users && $recent_users->num_rows > 0): ?>
                            <?php while($user = $recent_users->fetch_assoc()): 
                                $status_class = 'status-' . strtolower($user['status']);
                            ?>

                            <tr>
                                <td class="tableBoldText"><?php echo htmlspecialchars($user['username']); ?></td>
                                <td class="tablesmallText"><?php echo htmlspecialchars($user['role_name']); ?></td>
                                <td class="tablesmallText"><?php echo htmlspecialchars($user['location']); ?></td>
                                <td><span class="status <?php echo $status_class; ?>"><?php echo $user['status']; ?></span></td>

                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 20px;">No users found</td>
                            </tr>
                        <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="table-box">
                <h2>Recent Transactions</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Commodity</th>
                            <th>Seller</th>
                            <th>Buyer</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($recent_transactions && $recent_transactions->num_rows > 0): ?>
                            <?php while($trans = $recent_transactions->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($trans['commodity_name'] ?? 'Unknown'); ?></td>
                                <td><?php echo htmlspecialchars($trans['seller_name'] ?? 'Unknown'); ?></td>
                                <td><?php echo htmlspecialchars($trans['buyer_name'] ?? 'Unknown'); ?></td>
                                <td><strong>৳ <?php echo number_format(($trans['unit_price'] * $trans['quantity']), 2); ?></strong></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 20px;">No recent transactions</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>       
    
    
    <div class="price-caps">
        <h2>Current Price Caps</h2>
        <div style="margin-top: 15px;">
            <div class="price-item">
                <div class="price-header">
                    <div class="tableBoldText" style="font-size: 18px;">Rice</div>
                    <button class="btn btn-edit">Edit</button>
                </div>
                <div class="price-value">৳ 60 / kg</div>
                <div class="tablesmallText">Effective: 2024-01-01 | Expires: 2024-06-30</div>
            </div>
           
        </div>
    
    </div>
    
    <div class="footer">
        <p>Syndicate Buster Admin Panel © 2024</p>
    </div>
    
 
    </div>
</div>
</body>
</html>
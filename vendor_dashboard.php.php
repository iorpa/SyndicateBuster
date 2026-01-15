<?php
session_start();
require_once "config.php";

// Check if user is logged in and is a vendor (role 1-4)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] > 4) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user details
$user_sql = "SELECT u.*, r.role_name FROM users u 
             JOIN role r ON u.role_id = r.role_id 
             WHERE u.user_id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

// Get trust score
$trust_score = $user['trust_score'];

// Check if blacklisted
$blacklist_sql = "SELECT * FROM syndicate_blacklist 
                  WHERE user_id = ? AND status = 'Active'";
$blacklist_stmt = $conn->prepare($blacklist_sql);
$blacklist_stmt->bind_param("i", $user_id);
$blacklist_stmt->execute();
$is_blacklisted = $blacklist_stmt->get_result()->num_rows > 0;

// Get inventory summary
$inventory_sql = "SELECT c.name, SUM(b.quantity) as total_quantity 
                  FROM batches b 
                  JOIN commodities c ON b.commodities_id = c.commodities_id 
                  WHERE b.owner_id = ? 
                  GROUP BY c.commodities_id";
$inventory_stmt = $conn->prepare($inventory_sql);
$inventory_stmt->bind_param("i", $user_id);
$inventory_stmt->execute();
$inventory = $inventory_stmt->get_result();

// Get recent transactions
$trans_sql = "SELECT t.*, c.name as commodity_name, 
              seller.name as seller_name, buyer.name as buyer_name
              FROM transactions t
              JOIN batches b ON t.batch_id = b.batch_id
              JOIN commodities c ON b.commodities_id = c.commodities_id
              JOIN users seller ON t.seller_id = seller.user_id
              JOIN users buyer ON t.buyer_id = buyer.user_id
              WHERE t.seller_id = ? OR t.buyer_id = ?
              ORDER BY t.transaction_date DESC 
              LIMIT 5";
$trans_stmt = $conn->prepare($trans_sql);
$trans_stmt->bind_param("ii", $user_id, $user_id);
$trans_stmt->execute();
$recent_trans = $trans_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard - Syndicate Buster</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .welcome-text {
            font-size: 24px;
            color: #214332;
        }
        
        .user-info {
            text-align: right;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .card h2 {
            color: #214332;
            margin-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }
        
        .trust-score {
            font-size: 48px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            margin: 10px 0;
        }
        
        .trust-green { background: #d4edda; color: #155724; }
        .trust-yellow { background: #fff3cd; color: #856404; }
        .trust-red { background: #f8d7da; color: #721c24; }
        
        .alert-box {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #dc3545;
            margin-bottom: 20px;
        }
        
        .nav-menu {
            background: #214332;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .nav-menu ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }
        
        .nav-menu a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background 0.3s;
        }
        
        .nav-menu a:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .inventory-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th {
            background: #214332;
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        
        tr:hover {
            background: #f5f5f5;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #214332;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
        }
        
        .btn:hover {
            background: #163424;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top">Govt Market Monitor - Syndicate-Buster Portal</div>
        
        <div class="dashboard-container">
            <div class="dashboard-header">
                <div>
                    <h1 class="welcome-text">Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
                    <p>Role: <?php echo $user['role_name']; ?> | Location: <?php echo $user['district']; ?></p>
                </div>
                <div class="user-info">
                    <p>Trust Score: <strong><?php echo $trust_score; ?>/100</strong></p>
                    <a href="logout.php" style="color: #dc3545; text-decoration: none;">Logout</a>
                </div>
            </div>
            
            <?php if($is_blacklisted): ?>
            <div class="alert-box">
                ⚠️ <strong>SUSPENDED:</strong> You are currently blacklisted due to price violations. 
                Contact admin for appeal.
            </div>
            <?php endif; ?>
            
            <div class="nav-menu">
                <ul>
                    <li><a href="vendor_dashboard.php">Dashboard</a></li>
                    <li><a href="sell_product.php">Sell Product</a></li>
                    <li><a href="my_inventory.php">My Inventory</a></li>
                    <li><a href="view_transactions.php">Transactions</a></li>
                    <li><a href="index.php">Public Portal</a></li>
                </ul>
            </div>
            
            <div class="dashboard-grid">
                <!-- Trust Score Card -->
                <div class="card">
                    <h2>Trust Score</h2>
                    <?php 
                    $trust_class = 'trust-green';
                    if($trust_score < 60) $trust_class = 'trust-red';
                    elseif($trust_score < 80) $trust_class = 'trust-yellow';
                    ?>
                    <div class="trust-score <?php echo $trust_class; ?>">
                        <?php echo $trust_score; ?> / 100
                    </div>
                    <p style="text-align: center; margin-top: 10px;">
                        Based on transaction compliance and history
                    </p>
                </div>
                
                <!-- Inventory Summary -->
                <div class="card">
                    <h2>Current Inventory</h2>
                    <?php if($inventory->num_rows > 0): ?>
                        <?php while($item = $inventory->fetch_assoc()): ?>
                            <div class="inventory-item">
                                <span><?php echo $item['name']; ?></span>
                                <span><strong><?php echo $item['total_quantity']; ?> kg</strong></span>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No inventory found. Add your first batch!</p>
                    <?php endif; ?>
                    <a href="sell_product.php" class="btn">Add/Sell Batch</a>
                </div>
                
                <!-- Quick Stats -->
                <div class="card">
                    <h2>Quick Stats</h2>
                    <div class="inventory-item">
                        <span>Active Batches</span>
                        <span><strong>5</strong></span>
                    </div>
                    <div class="inventory-item">
                        <span>This Month Sales</span>
                        <span><strong>৳ 25,000</strong></span>
                    </div>
                    <div class="inventory-item">
                        <span>Violations</span>
                        <span><strong><?php echo $is_blacklisted ? '1' : '0'; ?></strong></span>
                    </div>
                    <div class="inventory-item">
                        <span>Warehouse Usage</span>
                        <span><strong>65%</strong></span>
                    </div>
                </div>
            </div>
            
            <!-- Recent Transactions -->
            <div class="card">
                <h2>Recent Transactions</h2>
                <?php if($recent_trans->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Commodity</th>
                                <th>Buyer/Seller</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($trans = $recent_trans->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $trans['transaction_date']; ?></td>
                                <td><?php echo $trans['commodity_name']; ?></td>
                                <td>
                                    <?php 
                                    if($trans['seller_id'] == $user_id) {
                                        echo "Sold to: " . $trans['buyer_name'];
                                    } else {
                                        echo "Bought from: " . $trans['seller_name'];
                                    }
                                    ?>
                                </td>
                                <td><?php echo $trans['quantity']; ?> kg</td>
                                <td>৳ <?php echo $trans['unit_price']; ?></td>
                                <td><strong>৳ <?php echo $trans['quantity'] * $trans['unit_price']; ?></strong></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <a href="view_transactions.php" class="btn">View All Transactions</a>
                <?php else: ?>
                    <p>No transactions yet. Start selling your products!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
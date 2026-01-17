<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] > 4) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$user_sql = "SELECT u.*, r.role_name FROM users u 
             JOIN role r ON u.role_id = r.role_id 
             WHERE u.user_id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

$trust_score = $user['trust_score'];

$blacklist_sql = "SELECT * FROM syndicate_blacklist WHERE seller_id = ?";
$blacklist_stmt = $conn->prepare($blacklist_sql);
$blacklist_stmt->bind_param("i", $user_id);
$blacklist_stmt->execute();
$is_blacklisted = $blacklist_stmt->get_result()->num_rows > 0;

$inventory_sql = "SELECT c.name, SUM(b.quantity) as total_quantity 
                  FROM batches b 
                  JOIN commodities c ON b.commodities_id = c.commodities_id 
                  WHERE b.owner_id = ? 
                  GROUP BY c.commodities_id";
$inventory_stmt = $conn->prepare($inventory_sql);
$inventory_stmt->bind_param("i", $user_id);
$inventory_stmt->execute();
$inventory = $inventory_stmt->get_result();

$price_caps = $conn->query("
                    SELECT c.name,c.unit_type, gpc.max_price, gpc.effective_date 
                    FROM govt_price_cap gpc 
                    JOIN commodities c ON gpc.commodities_id = c.commodities_id 
                    ORDER BY gpc.effective_date DESC
                ");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard - Syndicate Buster</title>
<link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <div class="top">Govt Market Monitor - Syndicate-Buster Portal</div>
        <div class="dashboard">
            <div class="header">
                <div>
                    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
                    <p style="color: #666;">Role: <?php echo $user['role_name']; ?> | Location: <?php echo $user['location']; ?></p>
                </div>
                <div>
                    <p>Trust Score: <strong><?php echo $trust_score; ?>/100</strong></p>
                    <button class="logout-btn">Logout</button>
                </div>
            </div>
            <?php if($is_blacklisted): ?>
            <div class="alert">
                <strong>SUSPENDED:</strong> You are currently blacklisted due to price violations. 
                Contact admin for appeal.
            </div>
            <?php endif; ?>
                <div class="nav-menu">
                <a href="vendorDashboard.php" style="background: rgba(255,255,255,0.1);">Dashboard</a>
                <a href="sell_product.php">Sell Product</a>
                <a href="userInventory.php">My Inventory</a>
                <a href="userTransaction.php">Transactions</a>
                <a href="userBlocklist.php">Violations</a>
                <a href="index.php">Public Portal</a>
            </div>
            <div class="grid">
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
                
                <div class="card">
                    <h2 style="margin-bottom: 10px;">Current Inventory</h2>
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
                    <a href="sell_product.php"  class="addSellBatchBtn">Add/Sell Batch</a>
                </div>
                
                <div class="card">
                    <h2>Quick Stats</h2>
                    <div class="inventory-item">
                        <span>Active Batches</span>
                        <span><strong><?php echo $inventory->num_rows; ?></strong></span>
                    </div>
                    <div class="inventory-item">
                        <span>Status</span>
                        <span style="color: <?php echo $user['status'] == 'Active' ? '#28a745' : '#dc3545'; ?>">
                            <strong><?php echo $user['status']; ?></strong>
                        </span>
                    </div>
                    <div class="inventory-item">
                        <span>Violations</span>
                        <span><strong><?php echo $is_blacklisted ? 'Yes' : 'None'; ?></strong></span>
                    </div>
                </div>
            </div>
            
            

             <div class="card">
                <h2 style="color: #214332; margin-bottom: 20px;">Current Price Caps</h2>
                <?php if($price_caps->num_rows > 0): ?>
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th>Commodity</th>
                                <th>Max Price</th>
                                <th>Effective Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $price_caps->data_seek(0);
                            while($cap = $price_caps->fetch_assoc()): 
                            ?>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar" style="background-color: #28a745;">
                                            <?php echo strtoupper(substr($cap['name'], 0, 1)); ?>
                                        </div>
                                        <div class="user-details">
                                            <div class="tableBoldText"><?php echo htmlspecialchars($cap['name']); ?></div>
                                            <div class="tablesmallText">Unit: <?php echo $cap['unit_type']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="tableBoldText" style="color: #28a745; font-size: 18px;">
                                        ৳ <?php echo number_format($cap['max_price'], 2); ?>
                                    </div>
                                    <div class="tablesmallText">per <?php echo $cap['unit_type']; ?></div>
                                </td>
                                <td>
                                    <div class="tableBoldText"><?php echo $cap['effective_date']; ?></div>
                                    <div class="tablesmallText">
                                        <?php 
                                        $today = new DateTime();
                                        $effective = new DateTime($cap['effective_date']);
                                        $interval = $today->diff($effective);
                                        
                                        if ($effective <= $today) {
                                            echo "Active for " . $interval->days . " days";
                                        } else {
                                            echo "Starts in " . $interval->days . " days";
                                        }
                                        ?>
                                    </div>
                                </td>
                               
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #666;">
                        <p>No price caps set yet.</p>
                        <p>Use the form above to set your first price cap.</p>
                    </div>
                <?php endif; ?>
            </div>


        </div>
        <div class="footer">
                <p>Syndicate Buster Admin Panel © 2024</p>
            
        </div>
    </div>
</body>
</html>


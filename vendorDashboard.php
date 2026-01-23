<?php
session_start();
require_once("../config.php");
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] > 4) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$user_sql = "SELECT u.*, r.role_name FROM users u 
             JOIN roles r ON u.role_id = r.role_id 
             WHERE u.user_id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();
$user_stmt->close();

$is_blacklisted =$user['account_status'];

$inventory_sql = "SELECT c.commodity_name, SUM(b.current_quantity) as total_quantity 
                  FROM batches b 
                  JOIN commodities c ON b.commodity_id = c.commodity_id 
                  WHERE b.owner_id = ? 
                  GROUP BY c.commodity_id";
$inventory_stmt = $conn->prepare($inventory_sql);
$inventory_stmt->bind_param("i", $user_id);
$inventory_stmt->execute();
$inventory = $inventory_stmt->get_result();

$price_caps = $conn->query("
                    SELECT c.commodity_name,c.unit_type, pc.max_price_per_unit, pc.effective_date 
                    FROM price_caps pc 
                    JOIN commodities c ON pc.commodity_id = c.commodity_id 
                    ORDER BY pc.effective_date DESC
                ");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard - Syndicate Buster</title>
    <link rel="stylesheet" href="../css/page.css">
        <link rel="stylesheet" href="../css/text.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/cards.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/error.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/button.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../css/table.css?v=<?php echo time(); ?>">

</head>
<body>
    <div class="container">
        <div class="header">Govt Market Monitor - Syndicate-Buster Portal</div>
        <div class="dashboard">
            <div class="userDetailsCard">
                <div>
                    <h1 class="GreenTextLarge">Welcome, <?php echo htmlspecialchars($user['username']);  ?>!</h1>
                    <p style="color: #666;">Role: <?php echo $user['role_name']; ?> | Location: <?php echo $user['location']; ?></p>
                </div>
                <div>
                    <p>Trust Score: <strong><?php echo $user['trust_score']; ?>/100</strong></p>
                        <form action="logout.php" method="post">
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                </div>
            </div>
            <?php if($is_blacklisted==='blacklisted'): ?>
            <div class="alert">
                <strong>SUSPENDED:</strong> You are currently blacklisted due to price violations. 
                Contact admin for appeal.
            </div>
            <?php endif; ?>
            <div class="navCard">
                <a href="../vendors/vendorDashboard.php" style="background: rgba(255,255,255,0.1);">Dashboard</a>
                <a href="sell_product.php">Sell Product</a>
                <a href="../vendors/userInventory.php">My Inventory</a>
                <a href="userTransaction.php">Transactions</a>
                <a href="userViolation.php">Violations</a>
            </div>
            <div class="grid">
                <div class="gridCard">
                    <h2 style="color: #214332; margin-bottom: 20px; ">Trust Score</h2>
                    <?php 
                    $trust_class = 'green-card';
                    if($user['trust_score'] < 60) $trust_class = 'red-card';
                    elseif($user['trust_score'] < 80) $trust_class = 'yellow-card';
                    ?>
                    <div class="trust-score <?php echo $trust_class; ?>">
                        <?php echo $user['trust_score'] ; ?> / 100
                    </div>
                    <p style="text-align: center; margin-top: 10px;">
                        Based on transaction compliance and history
                    </p>
                </div>
                
                <div class="gridCard">
                    <h2 style="color: #214332; margin-bottom: 20px;">Current Inventory</h2>
                    <?php if($inventory->num_rows > 0): ?>
                        <?php while($item = $inventory->fetch_assoc()): ?>
                            <div class="gridCard-item">
                                <span><?php echo $item['name']; ?></span>
                                <span><strong><?php echo $item['total_quantity']; ?> kg</strong></span>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No inventory found. Add your first batch!</p>
                    <?php endif; ?>
                    <a href="userInventory.php"  class="limebtn">Add/Sell Batch</a>
                </div>
                
                <div class="gridCard">
                    <h2 style="color: #214332; margin-bottom: 20px;">Quick Stats</h2>
                    <div class="gridCard-item">
                        <span>Active Batches</span>
                        <span><strong><?php echo $inventory->num_rows; ?></strong></span>
                    </div>
                    <div class="gridCard-item">
                        <span>Status</span>
                        <span style="color: <?php echo $user['account_status'] == 'Active' ? '#28a745' : '#dc3545'; ?>">
                            <strong><?php echo $user['account_status']; ?></strong>
                        </span>
                    </div>
                    <div class="gridCard-item">
                        <span>Violations</span>
                        <span><strong><?php echo $is_blacklisted==='blacklisted' ? 'Yes' : 'None'; ?></strong></span>
                    </div>
                </div>
            </div>
            
            

             <div class="gridCard">
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
                                            <?php echo strtoupper(substr($cap['commodity_name'], 0, 1)); ?>
                                        </div>
                                        <div class="user-details">
                                            <div class="tableBoldText"><?php echo htmlspecialchars($cap['commodity_name']); ?></div>
                                            <div class="tablesmallText">Unit: <?php echo $cap['unit_type']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="tableBoldText" style="color: #28a745; font-size: 18px;">
                                        ৳ <?php echo number_format($cap['max_price_per_unit'], 2); ?>
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
                <p>Syndicate Buster Admin Panel © 2026</p>
            
        </div>
    </div>
</body>
</html>


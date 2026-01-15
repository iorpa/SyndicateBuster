<?php
session_start();
require_once "config.php";

// Check if user is logged in and is a farmer
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}

// Fetch farmer's data
$user_id = $_SESSION['user_id'];
$sql = "SELECT u.*, r.role_name FROM users u 
        JOIN role r ON u.role_id = r.role_id 
        WHERE u.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch farmer's batches
$batch_sql = "SELECT b.*, c.name as commodity_name 
              FROM batches b 
              JOIN commodities c ON b.commodities_id = c.commodities_id 
              WHERE b.owner_id = ? 
              ORDER BY b.harvest_date DESC";
$batch_stmt = $conn->prepare($batch_sql);
$batch_stmt->bind_param("i", $user_id);
$batch_stmt->execute();
$batches = $batch_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard - Syndicate Buster</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard_style.css">
</head>
<body>
    <div class="container">
        <div class="top">Govt Market Monitor - Syndicate-Buster Portal</div>
        
        <div class="dashboard">
            <div class="dashboard-header">
                <h1 class="dashboard-title">Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
                <div class="user-info">
                    <span>Role: <?php echo $user['role_name']; ?></span>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </div>

            <div class="nav-menu">
                <ul>
                    <li><a href="farmer_dashboard.php">Dashboard</a></li>
                    <li><a href="add_batch.php">Add New Batch</a></li>
                    <li><a href="my_transactions.php">My Transactions</a></li>
                    <li><a href="price_list.php">Current Prices</a></li>
                    <li><a href="report_violation.php">Report Violation</a></li>
                </ul>
            </div>

            <div class="dashboard-grid">
                <!-- Trust Score Card -->
                <div class="card">
                    <h2>Trust Score</h2>
                    <div class="trust-score trust-green">
                        85 / 100
                    </div>
                    <p>Based on your transaction history</p>
                </div>

                <!-- Inventory Summary -->
                <div class="card">
                    <h2>Current Inventory</h2>
                    <?php 
                    $total_quantity = 0;
                    while($batch = $batches->fetch_assoc()) {
                        $total_quantity += $batch['quantity'];
                    }
                    ?>
                    <p class="inventory">
                        You have <strong><?php echo $total_quantity; ?> units</strong> of commodities in stock.
                    </p>
                    <a href="add_batch.php" class="greenBtn">Add New Batch</a>
                </div>

                <!-- Recent Transactions -->
                <div class="card">
                    <h2>Recent Transactions</h2>
                    <p>No recent transactions. Start selling your produce!</p>
                    <a href="sell_batch.php" class="limeBtn">Sell Now</a>
                </div>
            </div>

            <!-- My Batches Table -->
            <div class="card">
                <h2>My Batches</h2>
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background:#214332; color:white;">
                            <th style="padding:10px;">Batch ID</th>
                            <th style="padding:10px;">Commodity</th>
                            <th style="padding:10px;">Quantity</th>
                            <th style="padding:10px;">Harvest Date</th>
                            <th style="padding:10px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $batches->data_seek(0); // Reset pointer
                        while($batch = $batches->fetch_assoc()): 
                        ?>
                        <tr style="border-bottom:1px solid #ddd;">
                            <td style="padding:10px;">#<?php echo $batch['batch_id']; ?></td>
                            <td style="padding:10px;"><?php echo $batch['commodity_name']; ?></td>
                            <td style="padding:10px;"><?php echo $batch['quantity']; ?> units</td>
                            <td style="padding:10px;"><?php echo $batch['harvest_date']; ?></td>
                            <td style="padding:10px;">
                                <span style="background:#d4edda; padding:5px 10px; border-radius:4px;">
                                    Available
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
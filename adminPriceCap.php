<?php
session_start();    
require_once "config.php";
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 5) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commodity_id = $_POST['commodity_id'];
    $max_price = $_POST['max_price'];
    $effective_date = $_POST['effective_date'];
    
    $check_sql = "SELECT * FROM govt_price_cap 
                  WHERE commodities_id = ? AND effective_date = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("is", $commodity_id, $effective_date);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $error = "Price cap already exists for this commodity on selected date.";
    } else {
        $sql = "INSERT INTO govt_price_cap (commodities_id, max_price, effective_date) 
                VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ids", $commodity_id, $max_price, $effective_date);
        
        if ($stmt->execute()) {
            $message = "Price cap set successfully!";
        } else {
            $error = "Failed to set price cap: " . $conn->error;
        }
    }
}

if (isset($_GET['delete'])) {
    $cap_id = intval($_GET['delete']);
    $conn->query("DELETE FROM govt_price_cap WHERE cap_id = $cap_id");
    $message = "Price cap deleted successfully!";
}

$commodities = $conn->query("SELECT * FROM commodities ORDER BY name");

$price_caps = $conn->query("
    SELECT gpc.*, c.name as commodity_name, c.unit_type
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
    <title>Price Caps - Admin</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <div class="top">Govt Market Monitor - Syndicate-Buster Portal</div>
        
        <div class="dashboard">
         <div class="header">
                <h1>Price Caps Management</h1>
                <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
            </div>
            
            <div class="nav-menu">
                <a href="admin_dashboard.php" >Dashboard</a>
                <a href="adminManageUsers.php">Manage Users</a>
                <a href="adminPriceCap.php" style="background: rgba(255,255,255,0.1);">Price Caps</a>
                <a href="adminViolation.php">Violations</a>
                <a href="adminBlacklist.php">Blacklist</a>
                <a href="adminReports.php">Reports</a>
            </div>
            
            <?php if($message): ?>
                <div class="alert" style="background: #d4edda; color: #155724; border-left-color: #28a745;">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <?php if($error): ?>
                <div class="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <h2 style="color: #214332; margin-bottom: 20px;">Set New Price Cap</h2>
                <form method="POST" action="">
                    <div class="filter-grid">
                        <div>
                            <label style="display: block; margin-bottom: 5px; color: #666; font-size: 14px;">Commodity</label>
                            <select name="commodity_id" class="filter-select" required>
                                <option value="">Select Commodity</option>
                                <?php while($commodity = $commodities->fetch_assoc()): ?>
                                    <option value="<?php echo $commodity['commodities_id']; ?>">
                                        <?php echo htmlspecialchars($commodity['name']); ?> (per <?php echo $commodity['unit_type']; ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label style="display: block; margin-bottom: 5px; color: #666; font-size: 14px;">Max Price (৳)</label>
                            <input type="number" name="max_price" class="filter-select" 
                                   placeholder="Enter maximum price" step="0.01" min="0" required>
                        </div>
                        
                        <div>
                            <label style="display: block; margin-bottom: 5px; color: #666; font-size: 14px;">Effective Date</label>
                            <input type="date" name="effective_date" class="filter-select" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    
                    <div class="add-buttons" style="margin-top: 20px;">
                        <button type="submit" class="search-btn">
                            Set Price Cap
                        </button>
                        <button type="reset" class="page-btn">
                            Clear Form
                        </button>
                    </div>
                </form>
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
                                <th>Actions</th>
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
                                        
                                        /*if ($effective <= $today) {
                                            echo "Active for " . $interval->days . " days";
                                        } else {
                                            echo "Starts in " . $interval->days . " days";
                                        }*/
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 5px;">
                                        <a href="edit_price_cap.php?id=<?php echo $cap['cap_id']; ?>" 
                                           class="btn btn-edit" style="text-decoration: none; padding: 6px 12px;">
                                            Edit
                                        </a>
                                        <a href="price_caps.php?delete=<?php echo $cap['cap_id']; ?>" 
                                           class="btn btn-delete" style="text-decoration: none; padding: 6px 12px;"
                                           onclick="return confirm('Are you sure you want to delete this price cap?')">
                                            Delete
                                        </a>
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
    </div>

</body>
</html>
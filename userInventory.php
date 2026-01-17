
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
    <title>My Inventory - Syndicate Buster</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <div class="top">Govt Market Monitor - Syndicate-Buster Portal</div>
        
        <div class="dashboard">
            <div class="header">
                <h1>Transactions</h1>
                <button class="logout-btn">Logout</button>
            </div>
          
            <div class="nav-menu">
                <a href="vendorDashboard.php">Dashboard</a>
                <a href="sell_product.php">Sell Product</a>
                <a href="userInventory.php" style="background: rgba(255,255,255,0.1);">My Inventory</a>
                <a href="userTransaction.php">Transactions</a>
                <a href="userBlocklist.php" >Violations</a>
                <a href="index.php">Public Portal</a>
            </div>
           
            <div class="card">
                    <div style="margin: 15px 0;">
                        <button class="addSellBatchBtn" onclick="openAddBatchModal()" style="margin: 10px 0;">Add New Batch</button>
                        <button class="addSellBatchBtn" onclick="openAddCommodityModal()" style="background: #17a2b8; margin: 10px 0;">Add New Commodity</button>
                    </div>
            </div>
            <div class="search-filter">
                <h2 style="color: #214332; margin-bottom: 15px;">Search & Filter</h2>
                <div class="filter-grid">
                    <div class="search-box">
                        <input type="text" placeholder="Search by commodity or batch code...">
                        <button class="search-btn">Search</button>
                    </div>
                    <select class="filter-select">
                        <option value="">All Commodities</option>
                        <option value="rice">Rice</option>
                        <option value="wheat">Wheat</option>
                        <option value="potato">Potato</option>
                        <option value="onion">Onion</option>
                        <option value="lentil">Lentil</option>
                        <option value="sugar">Sugar</option>
                        <option value="oil">Oil</option>
                    </select>
                    <select class="filter-select">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="sold">Sold</option>
                        <option value="pending">Pending</option>
                    </select>
                    <select class="filter-select">
                        <option value="">Sort By</option>
                        <option value="date">Newest First</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="quantity">Quantity</option>
                    </select>
                </div>
            </div>
            
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="color: #214332;">My Batches</h2>
                </div>
                
                <div class="batch-card violation">
                    <div class="batch-header">
                        <div>
                            <div class="tableBoldText">Rice (Premium)</div>
                            <div class="tablesmallText">Batch ID: RICE-2024-001 | Location: Dhaka Warehouse</div>
                        </div>
                        <div class="batch-price">৳ 68.50/kg</div>
                    </div>
                    <div class="violation-alert">
                        ⚠ Price exceeds cap by 14.2%
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin: 10px 0;">
                        <div>
                            <div class="tablesmallText">Quantity</div>
                            <div class="tableBoldText">500 kg</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Added On</div>
                            <div class="tableBoldText">Mar 15, 2024</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Price Cap</div>
                            <div class="tableBoldText" style="color: #28a745;">৳ 60.00/kg</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Status</div>
                            <div><span class="status status-active">Available</span></div>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button class="btn btn-view" onclick="sellBatch('RICE-2024-001')">Sell</button>
                        <button class="btn btn-edit" onclick="editBatch('RICE-2024-001')">Edit</button>
                        <button class="btn btn-delete" onclick="deleteBatch('RICE-2024-001')">Remove</button>
                    </div>
                </div>
                
                <div class="batch-card warning">
                    <div class="batch-header">
                        <div>
                            <div class="tableBoldText">Wheat</div>
                            <div class="tablesmallText">Batch ID: WHT-2024-045 | Location: Chittagong</div>
                        </div>
                        <div class="batch-price">৳ 45.20/kg</div>
                    </div>
                    <div style="background: #fff3cd; color: #856404; padding: 5px 10px; border-radius: 4px; font-size: 12px; margin: 5px 0;">
                        ⚠ Price near cap limit (7.6% above)
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin: 10px 0;">
                        <div>
                            <div class="tablesmallText">Quantity</div>
                            <div class="tableBoldText">300 kg</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Added On</div>
                            <div class="tableBoldText">Mar 18, 2024</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Price Cap</div>
                            <div class="tableBoldText" style="color: #28a745;">৳ 42.00/kg</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Status</div>
                            <div><span class="status status-active">Available</span></div>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button class="btn btn-view" onclick="sellBatch('WHT-2024-045')">Sell</button>
                        <button class="btn btn-edit" onclick="editBatch('WHT-2024-045')">Edit</button>
                        <button class="btn btn-delete" onclick="deleteBatch('WHT-2024-045')">Remove</button>
                    </div>
                </div>
                
                <!-- Batch 3 - Normal -->
                <div class="batch-card">
                    <div class="batch-header">
                        <div>
                            <div class="tableBoldText">Potato</div>
                            <div class="tablesmallText">Batch ID: POT-2024-123 | Location: Rajshahi Farm</div>
                        </div>
                        <div class="batch-price">৳ 28.00/kg</div>
                    </div>
                    <div style="background: #d4edda; color: #155724; padding: 5px 10px; border-radius: 4px; font-size: 12px; margin: 5px 0;">
                        ✓ Price within cap limit (6.7% below)
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin: 10px 0;">
                        <div>
                            <div class="tablesmallText">Quantity</div>
                            <div class="tableBoldText">1,200 kg</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Added On</div>
                            <div class="tableBoldText">Mar 20, 2024</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Price Cap</div>
                            <div class="tableBoldText" style="color: #28a745;">৳ 30.00/kg</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Status</div>
                            <div><span class="status status-active">Available</span></div>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button class="btn btn-view" onclick="sellBatch('POT-2024-123')">Sell</button>
                        <button class="btn btn-edit" onclick="editBatch('POT-2024-123')">Edit</button>
                        <button class="btn btn-delete" onclick="deleteBatch('POT-2024-123')">Remove</button>
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="pagination">
                <a href="#" class="page-btn">«</a>
                <a href="#" class="page-btn active">1</a>
                <a href="#" class="page-btn">2</a>
                <a href="#" class="page-btn">3</a>
                <a href="#" class="page-btn">»</a>
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
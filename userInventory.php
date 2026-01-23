<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] > 4) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$commodity_filter = isset($_GET['commodity']) ? $_GET['commodity'] : '';
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'production_date';

$batches_sql = "
    SELECT b.batch_id,b.parent_batch_id,b.current_quantity,b.initial_quantity,b.production_date,
        c.commodity_name,c.unit_type
    FROM batches b
    INNER JOIN commodities c ON b.commodity_id = c.commodity_id
    WHERE b.owner_id = ?
";

if (!empty($search)) {
    $batches_sql .= " AND (c.commodity_name LIKE ? OR b.batch_id LIKE ?)";
}

if (!empty($commodity_filter)) {
    $batches_sql .= " AND c.commodity_id = ?";
}

switch ($sort_by) {
    case 'quantity':
        $batches_sql .= " ORDER BY b.current_quantity DESC";
        break;
    case 'production_date':
    default:
        $batches_sql .= " ORDER BY b.production_date DESC";
        break;
}

$batches_stmt = $conn->prepare($batches_sql);

if (!empty($search)) {
    $search_param = "%$search%";
    if (!empty($commodity_filter)) {
        $batches_stmt->bind_param("issi", $user_id, $search_param, $search_param, $commodity_filter);
    } else {
        $batches_stmt->bind_param("iss", $user_id, $search_param, $search_param);
    }
} else {
    if (!empty($commodity_filter)) {
        $batches_stmt->bind_param("ii", $user_id, $commodity_filter);
    } else {
        $batches_stmt->bind_param("i", $user_id);
    }
}

$batches_stmt->execute();
$batches_result = $batches_stmt->get_result();
$batches = $batches_result->fetch_all(MYSQLI_ASSOC);
$total_batches = count($batches);
$commodities_sql = "SELECT commodity_id, commodity_name FROM commodities ORDER BY commodity_name";
$commodities_result = $conn->query($commodities_sql);
$commodities = $commodities_result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Inventory - Syndicate Buster</title>
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
                <h1>My Inventory</h1>
                <form action="logout.php" method="post">
                    <button type="submit" class="smallBtn Red">Logout</button>
                </form>
            </div>
          
       <div class="navCard">
                <a href="../vendors/vendorDashboard.php">Dashboard</a>
                <a href="sell_product.php">Sell Product</a>
                <a href="../vendors/userInventory.php"  style="background: rgba(255,255,255,0.1);">My Inventory</a>
                <a href="userTransaction.php">Transactions</a>
                <a href="userBlocklist.php">Violations</a>
            </div>
           
            <div class="gridCard">
                <div style="display: flex; gap: 15px; margin: 20px 0;">
                    <button class="greenBtn" onclick="openAddBatchModal()">Add New Batch</button>
                    <button class="greenBtn secondary" onclick="openAddCommodityModal()">Add New Commodity</button>
                </div>
            </div>
            
            <div class="gridCard">
                <h2 style="color: #214332; margin-bottom: 15px;">Search & Filter</h2>
                <form method="GET" action="">
                    <div class="filter-grid">
                        <div class="search-box">
                            <input type="text" name="search" placeholder="Search by commodity or batch code..." 
                                   value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="smallBtn Green">Search</button>
                        </div>
                        
                        <div>
                            <select name="commodity" class="filterText">
                                <option value="">All Commodities</option>
                                <?php foreach($commodities as $commodity): ?>
                                    <option value="<?php echo $commodity['commodity_id']; ?>" 
                                        <?php echo $commodity_filter == $commodity['commodity_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($commodity['commodity_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <select name="sort" class="filterText">
                                <option value="production_date" <?php echo $sort_by == 'production_date' ? 'selected' : ''; ?>>Latest Harvest First</option>
                                <option value="quantity" <?php echo $sort_by == 'quantity' ? 'selected' : ''; ?>>Quantity: High to Low</option>
                            </select>
                        </div>
                        
                        <div>
                            <button type="submit" class="smallBtn Green" style="width: 100%;">Apply </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="gridCard">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 class="GreenTextLarge">My Batches</h2>
                    <div class="tablesmallText">
                        Showing <?php echo $total_batches; ?> batch(es)
                    </div>
                </div>
                
                <?php if($total_batches > 0): ?>
                    <?php foreach($batches as $batch): 
                        $status_class = $batch['current_quantity'] ? 'status-sold' : 'status-available';
                        $status_text = $batch['current_quantity']=== 0 ? 'Sold' : 'Available';
                    ?>
                    <div class="batchCard">
                        <div class="batchCardItem">
                            <div>
                                <div class="tableBoldText"><?php echo htmlspecialchars($batch['commodity_name']); ?></div>
                                <div class="tablesmallText">
                                    Batch ID: <?php echo htmlspecialchars($batch['batch_id']); ?> | 
                                    <?php if($batch['parent_batch_id']): ?>
                                        Parent Batch: <?php echo $batch['parent_batch_id']; ?>
                                    <?php else: ?>
                                        Original Batch
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin: 10px 0;">
                            <div>
                                <div class="tablesmallText">Total Quantity</div>
                                <div class="tableBoldText"><?php echo number_format($batch['initial_quantity'], 0); ?> <?php echo htmlspecialchars($batch['unit_type']); ?></div>
                            </div>
                            <div>
                                <div class="tablesmallText">Harvest Date</div>
                                <div class="tableBoldText"><?php echo date('M d, Y', strtotime($batch['production_date'])); ?></div>
                            </div>
                            <div>
                                <div class="tablesmallText">Remaining</div>
                                    <div class="tableBoldText" style="color: <?php echo $batch['current_quantity'] > 0 ? '#214332' : '#dc3545'; ?>;">
                                    <?php echo number_format($batch['current_quantity'], 0); ?> <?php echo htmlspecialchars($batch['unit_type']); ?>
                                </div>
                            </div>
                            <div>
                                <div class="tablesmallText">Status</div>
                                <div><span class="tableBoldText <?php echo $status_class; ?>"><?php echo $status_text; ?></span></div>
                            </div>
                        </div>
                        <div class="action-buttons">
                            <?php if($batch['current_quantity']>0): ?>
                            <button class="cardBtn Cyan" onclick="editBatch('<?php echo $batch['batch_id']; ?>')">Edit</button>
                            <?php endif; ?>
                            <button class="cardBtn DarkRed" onclick="deleteBatch('<?php echo $batch['batch_id']; ?>')">Remove</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #666;">
                        No batches found. <a href="#" onclick="openAddBatchModal()" style="color: #007bff;">Add your first batch</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php include '../functions/pagination.php'; ?>
            
            <div class="footer">
                <p>Syndicate Buster Admin Panel © 2024</p>
            </div>
        </div>
    </div>
    

    <script src="../functions/function.js"></script>
    <?php include '../functions/modals.php'; ?>
</body>
</html>
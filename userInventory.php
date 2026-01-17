<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] > 4) {
    header("Location: login.php");
    exit();
}
$inventory_sql = "SELECT c.name, SUM(b.quantity) as total_quantity 
                  FROM batches b 
                  JOIN commodities c ON b.commodities_id = c.commodities_id 
                  WHERE b.owner_id = ? 
                  GROUP BY c.commodities_id";
$inventory_stmt = $conn->prepare($inventory_sql);
$inventory_stmt->bind_param("i", $user_id);
$inventory_stmt->execute();
$inventory = $inventory_stmt->get_result();



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Inventory - Syndicate Buster</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="model.css?v=<?php echo time(); ?>">
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
            </div>
           
            <div class="card">
                    <div class="button-row-container">
                        <button class="button-row" onclick="openAddBatchModal()">Add New Batch</button>
                        <button class="button-row secondary" onclick="openAddCommodityModal()">Add New Commodity</button>
                    </div>
            </div>
            <div class="search-filter">
                <h2 style="color: #214332; margin-bottom: 15px;">Search & Filter</h2>
                <div class="filter-grid">
                    <div class="search-box">
                        <input type="text" placeholder="Search by commodity or batch code...">
                        <button class="search-btn">Search</button>
                    
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
                        <button class="btn btn-edit" onclick="editBatch('RICE-2024-001')">Edit</button>
                        <button class="btn btn-delete" onclick="deleteBatch('RICE-2024-001')">Remove</button>
                    </div>
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
           
          
    <!-- Add Batch Modal -->
    <div id="addBatchModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Batch</h2>
                <button class="close-btn" onclick="closeModal('addBatchModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addBatchForm" method="POST" action="process_add_batch.php">
                    <div class="form-group">
                        <label class="form-label">Commodity</label>
                        <select id="commoditySelect" name="commodity" class="form-control" required>
                            <option value="">Select Commodity</option>
                            <?php
                            // Get commodities from database
                            $commodities_sql = "SELECT commodities_id, name FROM commodities ORDER BY name";
                            $commodities_result = $conn->query($commodities_sql);
                            while($row = $commodities_result->fetch_assoc()) {
                                echo '<option value="' . $row['commodities_id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Quantity (kg)</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" placeholder="Enter quantity" required min="0.01" step="0.01">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Price per kg (৳)</label>
                        <input type="number" id="price" name="unit_price" class="form-control" placeholder="Enter price" required step="0.01">
                        <small style="color: #666;">Current price cap: <span id="priceCapDisplay">৳ 0.00</span></small>
                        
                        <div id="priceWarning" class="price-warning">
                            <!-- Price warning will appear here -->
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Location</label>
                        <input type="text" id="location" name="location" class="form-control" placeholder="Enter storage location" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Quality Grade (Optional)</label>
                        <select id="quality" name="quality" class="form-control">
                            <option value="">Select Grade</option>
                            <option value="premium">Premium</option>
                            <option value="standard">Standard</option>
                            <option value="economy">Economy</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-delete" onclick="closeModal('addBatchModal')">Cancel</button>
                <button type="button" class="btn btn-view" onclick="submitBatchForm()">Add Batch</button>
            </div>
        </div>
    </div>
    
    <!-- Add Commodity Modal -->
    <div id="addCommodityModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Commodity</h2>
                <button class="close-btn" onclick="closeModal('addCommodityModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addCommodityForm" method="POST" action="process_add_commodity.php">
                    <div class="form-group">
                        <label class="form-label">Commodity Name *</label>
                        <input type="text" id="commodityName" name="name" class="form-control" placeholder="e.g., Tomato, Chicken, Milk" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Unit Type *</label>
                        <select id="commodityUnit" name="unit_type" class="form-control" required>
                            <option value="">Select Unit</option>
                            <option value="kg">Kilogram (kg)</option>
                            <option value="liter">Liter</option>
                            <option value="piece">Piece</option>
                            <option value="bag">Bag (50kg)</option>
                            <option value="sack">Sack</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select id="commodityCategory" name="category" class="form-control" required>
                            <option value="">Select Category</option>
                            <option value="grain">Grain</option>
                            <option value="vegetable">Vegetable</option>
                            <option value="fruit">Fruit</option>
                            <option value="protein">Protein</option>
                            <option value="dairy">Dairy</option>
                            <option value="oil">Oil & Fat</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Description (Optional)</label>
                        <textarea id="commodityDesc" name="description" class="form-control" rows="3" placeholder="Brief description..."></textarea>
                    </div>
                    
                    <div class="price-warning" style="background: #e8f5e9; color: #155724; display: block;">
                        <strong>Note:</strong> New commodities require admin approval.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-delete" onclick="closeModal('addCommodityModal')">Cancel</button>
                <button type="button" class="btn btn-view" onclick="submitCommodityForm()">Submit for Approval</button>
            </div>
        </div>
    </div>

    <script>
        function openAddBatchModal() {
            console.log("Opening Add Batch Modal");
            document.getElementById('addBatchModal').style.display = 'flex';
        }
        
        function openAddCommodityModal() {
            console.log("Opening Add Commodity Modal");
            document.getElementById('addCommodityModal').style.display = 'flex';
        }
        
        function closeModal(modalId) {
            console.log("Closing modal: " + modalId);
            document.getElementById(modalId).style.display = 'none';
        }
        
    
       
        
          
    </script>


</body>
</html>
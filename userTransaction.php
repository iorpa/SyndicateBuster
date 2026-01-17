<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Transactions</title>
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
                <a href="userInventory.php">My Inventory</a>
                <a href="userTransaction.php" style="background: rgba(255,255,255,0.1);">Transactions</a>
                <a href="userBlocklist.php">Violations</a>
                <a href="index.php">Public Portal</a>
            </div>
            <!-- Tabs -->
            <div style="margin: 20px 0; display: flex; gap: 10px;">
                <button class="tab-btn active" onclick="showTab('all')">All Transactions</button>
                <button class="tab-btn" onclick="showTab('sell')">My Sales</button>
                <button class="tab-btn" onclick="showTab('buy')">My Purchases</button>
            </div>
            
            <!-- All Transactions Tab -->
            <div id="allTab" class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>All Transactions</h2>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <span class="tablesmallText">Show:</span>
                        <select style="padding: 5px; border-radius: 4px;">
                            <option>10</option>
                            <option selected>20</option>
                            <option>50</option>
                            <option>100</option>
                        </select>
                    </div>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Commodity</th>
                            <th>Partner</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Transaction 1 -->
                        <tr>
                            <td>Mar 25, 2024</td>
                            <td><span class="type-sold">SOLD</span></td>
                            <td>Rice</td>
                            <td>Wholesale Traders Ltd (Buyer)</td>
                            <td>200 kg</td>
                            <td>৳ 68.50/kg</td>
                            <td>৳ 13,700</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                        
                        <!-- Transaction 2 -->
                        <tr>
                            <td>Mar 24, 2024</td>
                            <td><span class="type-sold">SOLD</span></td>
                            <td>Wheat</td>
                            <td>City Retailers (Buyer)</td>
                            <td>300 kg</td>
                            <td>৳ 45.20/kg</td>
                            <td>৳ 13,560</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                        
                        <!-- Transaction 3 -->
                        <tr>
                            <td>Mar 23, 2024</td>
                            <td><span class="type-sold">SOLD</span></td>
                            <td>Potato</td>
                            <td>Local Market (Buyer)</td>
                            <td>500 kg</td>
                            <td>৳ 28.00/kg</td>
                            <td>৳ 14,000</td>
                            <td><span class="status-pending">Pending</span></td>
                        </tr>
                        
                        <!-- Transaction 4 -->
                        <tr>
                            <td>Mar 22, 2024</td>
                            <td><span class="type-sold">SOLD</span></td>
                            <td>Onion</td>
                            <td>Super Mart (Buyer)</td>
                            <td>150 kg</td>
                            <td>৳ 55.00/kg</td>
                            <td>৳ 8,250</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                        
                        <!-- Transaction 5 -->
                        <tr>
                            <td>Mar 21, 2024</td>
                            <td><span class="type-bought">BOUGHT</span></td>
                            <td>Rice</td>
                            <td>Farmer Rahman (Seller)</td>
                            <td>400 kg</td>
                            <td>৳ 58.00/kg</td>
                            <td>৳ 23,200</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                        
                        <!-- Transaction 6 -->
                        <tr>
                            <td>Mar 20, 2024</td>
                            <td><span class="type-bought">BOUGHT</span></td>
                            <td>Wheat</td>
                            <td>Agro Suppliers (Seller)</td>
                            <td>250 kg</td>
                            <td>৳ 42.50/kg</td>
                            <td>৳ 10,625</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                        
                        <!-- Transaction 7 -->
                        <tr>
                            <td>Mar 19, 2024</td>
                            <td><span class="type-bought">BOUGHT</span></td>
                            <td>Lentil</td>
                            <td>Pulse Traders (Seller)</td>
                            <td>100 kg</td>
                            <td>৳ 120.00/kg</td>
                            <td>৳ 12,000</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                        
                        <!-- Transaction 8 -->
                        <tr>
                            <td>Mar 18, 2024</td>
                            <td><span class="type-sold">SOLD</span></td>
                            <td>Sugar</td>
                            <td>Bakery Shop (Buyer)</td>
                            <td>80 kg</td>
                            <td>৳ 85.00/kg</td>
                            <td>৳ 6,800</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                        
                        <!-- Transaction 9 -->
                        <tr>
                            <td>Mar 17, 2024</td>
                            <td><span class="type-bought">BOUGHT</span></td>
                            <td>Oil</td>
                            <td>Oil Mill Ltd (Seller)</td>
                            <td>50 liters</td>
                            <td>৳ 180.00/liter</td>
                            <td>৳ 9,000</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                        
                        <!-- Transaction 10 -->
                        <tr>
                            <td>Mar 16, 2024</td>
                            <td><span class="type-sold">SOLD</span></td>
                            <td>Potato</td>
                            <td>Restaurant Chain (Buyer)</td>
                            <td>700 kg</td>
                            <td>৳ 29.50/kg</td>
                            <td>৳ 20,650</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="pagination">
                    <button class="page-btn">« Previous</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn">4</button>
                    <button class="page-btn">5</button>
                    <span style="padding: 8px 12px;">...</span>
                    <button class="page-btn">10</button>
                    <button class="page-btn">Next »</button>
                </div>
                
                <div style="text-align: center; margin-top: 15px; color: #666; font-size: 14px;">
                    Showing 1-10 of 47 transactions
                </div>
            </div>
            
            <!-- Sales Only Tab -->
            <div id="sellTab" class="card" style="display: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>My Sales</h2>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <span class="tablesmallText">Show:</span>
                        <select style="padding: 5px; border-radius: 4px;">
                            <option>10</option>
                            <option selected>20</option>
                            <option>50</option>
                        </select>
                    </div>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Commodity</th>
                            <th>Buyer</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Mar 25, 2024</td>
                            <td>Rice</td>
                            <td>Wholesale Traders Ltd</td>
                            <td>200 kg</td>
                            <td>৳ 68.50/kg</td>
                            <td>৳ 13,700</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                        <tr>
                            <td>Mar 24, 2024</td>
                            <td>Wheat</td>
                            <td>City Retailers</td>
                            <td>300 kg</td>
                            <td>৳ 45.20/kg</td>
                            <td>৳ 13,560</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                        <tr>
                            <td>Mar 23, 2024</td>
                            <td>Potato</td>
                            <td>Local Market</td>
                            <td>500 kg</td>
                            <td>৳ 28.00/kg</td>
                            <td>৳ 14,000</td>
                            <td><span class="status-pending">Pending</span></td>
                        </tr>
                        <tr>
                            <td>Mar 22, 2024</td>
                            <td>Onion</td>
                            <td>Super Mart</td>
                            <td>150 kg</td>
                            <td>৳ 55.00/kg</td>
                            <td>৳ 8,250</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                        <tr>
                            <td>Mar 18, 2024</td>
                            <td>Sugar</td>
                            <td>Bakery Shop</td>
                            <td>80 kg</td>
                            <td>৳ 85.00/kg</td>
                            <td>৳ 6,800</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                        <tr>
                            <td>Mar 16, 2024</td>
                            <td>Potato</td>
                            <td>Restaurant Chain</td>
                            <td>700 kg</td>
                            <td>৳ 29.50/kg</td>
                            <td>৳ 20,650</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Pagination for Sales -->
                <div class="pagination">
                    <button class="page-btn">« Previous</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn">Next »</button>
                </div>
                
                <div style="text-align: center; margin-top: 15px; color: #666; font-size: 14px;">
                    Showing 1-6 of 18 sales transactions
                </div>
            </div>
            
            <!-- Purchases Only Tab -->
            <div id="buyTab" class="card" style="display: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>My Purchases</h2>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <span class="tablesmallText">Show:</span>
                        <select style="padding: 5px; border-radius: 4px;">
                            <option>10</option>
                            <option selected>20</option>
                            <option>50</option>
                        </select>
                    </div>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Commodity</th>
                            <th>Seller</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Mar 21, 2024</td>
                            <td>Rice</td>
                            <td>Farmer Rahman</td>
                            <td>400 kg</td>
                            <td>৳ 58.00/kg</td>
                            <td>৳ 23,200</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                        <tr>
                            <td>Mar 20, 2024</td>
                            <td>Wheat</td>
                            <td>Agro Suppliers</td>
                            <td>250 kg</td>
                            <td>৳ 42.50/kg</td>
                            <td>৳ 10,625</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                        <tr>
                            <td>Mar 19, 2024</td>
                            <td>Lentil</td>
                            <td>Pulse Traders</td>
                            <td>100 kg</td>
                            <td>৳ 120.00/kg</td>
                            <td>৳ 12,000</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                        <tr>
                            <td>Mar 17, 2024</td>
                            <td>Oil</td>
                            <td>Oil Mill Ltd</td>
                            <td>50 liters</td>
                            <td>৳ 180.00/liter</td>
                            <td>৳ 9,000</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                        <tr>
                            <td>Mar 15, 2024</td>
                            <td>Potato</td>
                            <td>Farm Fresh Ltd</td>
                            <td>600 kg</td>
                            <td>৳ 26.50/kg</td>
                            <td>৳ 15,900</td>
                            <td><span class="status-pending">Pending</span></td>
                        </tr>
                        <tr>
                            <td>Mar 14, 2024</td>
                            <td>Onion</td>
                            <td>Vegetable Wholesalers</td>
                            <td>200 kg</td>
                            <td>৳ 52.00/kg</td>
                            <td>৳ 10,400</td>
                            <td><span class="status-completed">Completed</span></td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Pagination for Purchases -->
                <div class="pagination">
                    <button class="page-btn">« Previous</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn">Next »</button>
                </div>
                
                <div style="text-align: center; margin-top: 15px; color: #666; font-size: 14px;">
                    Showing 1-6 of 16 purchase transactions
                </div>
            </div>
            
            <!-- Summary Section -->
            <div class="grid">
                <div class="card">
                    <h2>Sales Summary</h2>
                    <div class="inventory-item">
                        <span>Total Sold</span>
                        <span><strong>৳ 76,960</strong></span>
                    </div>
                    <div class="inventory-item">
                        <span>Total Batches</span>
                        <span><strong>18</strong></span>
                    </div>
                    <div class="inventory-item">
                        <span>Avg. Price</span>
                        <span><strong>৳ 52.18/kg</strong></span>
                    </div>
                </div>
                
                <div class="card">
                    <h2>Purchase Summary</h2>
                    <div class="inventory-item">
                        <span>Total Bought</span>
                        <span><strong>৳ 81,125</strong></span>
                    </div>
                    <div class="inventory-item">
                        <span>Total Batches</span>
                        <span><strong>16</strong></span>
                    </div>
                    <div class="inventory-item">
                        <span>Avg. Price</span>
                        <span><strong>৳ 65.70/kg</strong></span>
                    </div>
                </div>
                
                <div class="card">
                    <h2>Quick Stats</h2>
                    <div class="inventory-item">
                        <span>Total Transactions</span>
                        <span><strong>34</strong></span>
                    </div>
                    <div class="inventory-item">
                        <span>Net Balance</span>
                        <span><strong>-৳ 4,165</strong></span>
                    </div>
                    <div class="inventory-item">
                        <span>This Month</span>
                        <span><strong>10 transactions</strong></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Syndicate Buster Admin Panel © 2024</p>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.getElementById('allTab').style.display = 'none';
            document.getElementById('sellTab').style.display = 'none';
            document.getElementById('buyTab').style.display = 'none';
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab and activate button
            document.getElementById(tabName + 'Tab').style.display = 'block';
            event.target.classList.add('active');
        }
        
        // Pagination function
        function goToPage(pageNumber) {
            alert('Loading page ' + pageNumber + '...');
            // In real app, this would load the data for that page
        }
    </script>
</body>
</html>
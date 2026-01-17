<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price Violations - Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .severity-high {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .severity-medium {
            background-color: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        
        .severity-low {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .violation-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .violation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .excess-amount {
            font-size: 20px;
            font-weight: bold;
            color: #dc3545;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #214332;
            margin: 10px 0;
        }
        
        .violator-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .export-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .modal-footer {
            padding: 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top">Govt Market Monitor - Syndicate-Buster Portal</div>
        
        <div class="dashboard">
            <div class="header">
                <h1>Price Violations</h1>
                <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
            </div>
            
            <div class="nav-menu">
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="adminManageUsers.php">Manage Users</a>
                <a href="adminPriceCap.php">Price Caps</a>
                <a href="adminViolation.php" style="background: rgba(255,255,255,0.1);">Violations</a>
                <a href="adminBlacklist.php">Blacklist</a>
                <a href="adminReports.php">Reports</a>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div>Total Violations</div>
                    <div class="stat-number">00</div>
                </div>
                
                <div class="stat-card">
                    <div>Unique Sellers</div>
                    <div class="stat-number">00</div>
                </div>
                
                <div class="stat-card">
                    <div>This Week</div>
                    <div class="stat-number">00</div>
                </div>
                
                <div class="stat-card">
                    <div>Avg Excess</div>
                    <div class="stat-number">00</div>
                </div>
            </div>
            
           
            <div class="card" style="margin-bottom: 20px;">
                <h2 style="color: #214332; margin-bottom: 15px;">Top Violators</h2>
                <div class="violator-item">
                    <div>
                        <div class="tableBoldText">Rahul Traders</div>
                        <div class="tablesmallText">Wholesaler</div>
                    </div>
                    <div style="text-align: right;">
                        <div class="tableBoldText" style="color: #dc3545;">12 violations</div>
                        <div class="tablesmallText">Avg excess: ৳18.50</div>
                    </div>
                </div>
                <div class="violator-item">
                    <div>
                        <div class="tableBoldText">Farmer Kamal</div>
                        <div class="tablesmallText">Farmer</div>
                    </div>
                    <div style="text-align: right;">
                        <div class="tableBoldText" style="color: #dc3545;">8 violations</div>
                        <div class="tablesmallText">Avg excess: ৳12.75</div>
                    </div>
                </div>
                </div>
              
                 
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="color: #214332;">Recent Violations</h2>
                    <div class="search-box" style="width: 300px;">
                        <input type="text" placeholder="Search violations..." id="searchViolations">
                        <button class="search-btn" onclick="searchViolations()">Search</button>
                    </div>
                </div>
                
                <div class="violation-card severity-high">
                    <div class="violation-header">
                        <div>
                            <div class="tableBoldText">Rice</div>
                            <div class="tablesmallText">
                                Violation ID: VIO-2024-045 | 
                                2 days ago
                            </div>
                        </div>
                        <div class="excess-amount">
                            +৳8.50 (14.2%)
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin: 15px 0;">
                        <div>
                            <div class="tablesmallText">Seller</div>
                            <div class="tableBoldText">Rahul Traders</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Buyer</div>
                            <div class="tableBoldText">City Retailers</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Transaction Date</div>
                            <div class="tableBoldText">Mar 25, 2024</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Severity</div>
                            <div class="tableBoldText">High</div>
                        </div>
                    </div>
                    
                    <div style="background: #f8f9fa; padding: 10px; border-radius: 4px; margin: 10px 0;">
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                            <div>
                                <div class="tablesmallText">Reported Price</div>
                                <div class="tableBoldText">৳ 68.50</div>
                            </div>
                            <div>
                                <div class="tablesmallText">Max Allowed</div>
                                <div class="tableBoldText" style="color: #28a745;">৳ 60.00</div>
                            </div>
                            <div>
                                <div class="tablesmallText">Quantity</div>
                                <div class="tableBoldText">500 units</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <button class="btn btn-view" onclick="viewViolationDetails('VIO-2024-045')">
                            View Details
                        </button>
                        <button class="btn btn-edit" onclick="issueFine('VIO-2024-045', 'seller123')">
                            Issue Fine
                        </button>
                        <button class="btn btn-delete" onclick="blacklistSeller('seller123')">
                            Blacklist Seller
                        </button>
                        <button class="btn" style="background: #6c757d; color: white;" onclick="markAsResolved('VIO-2024-045')">
                            Mark Resolved
                        </button>
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
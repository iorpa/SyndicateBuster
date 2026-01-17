<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Violations - Syndicate Buster</title>
<link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <div class="top">Govt Market Monitor - Syndicate-Buster Portal</div>
        
        <div class="dashboard">
            <div class="header">
                <h1>Violations & Blacklist Status</h1>
                <button class="logout-btn">Logout</button>
          </div>
                <div class="nav-menu">
                <a href="vendorDashboard.php">Dashboard</a>
                <a href="sell_product.php">Sell Product</a>
                <a href="userInventory.php">My Inventory</a>
                <a href="userTransaction.php">Transactions</a>
                <a href="userBlocklist.php" style="background: rgba(255,255,255,0.1);">Violations</a>
            </div>
            
            <div class="alert" style="background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 24px;">⚠</span>
                    <div>
                        <strong style="font-size: 18px;">ACCOUNT SUSPENDED</strong>
                        <p>You are currently BLACKLISTED. All selling privileges are suspended until: <strong>April 25, 2024</strong></p>
                    </div>
                </div>
            </div>
            
            <div class="card" style="border-left: 4px solid #dc3545;">
                <h2>Account Status</h2>
                <div class="grid" style="grid-template-columns: repeat(4, 1fr); gap: 20px; margin: 20px 0;">
                    <div style="text-align: center; padding: 15px; background: #f8d7da; border-radius: 8px;">
                        <div style="font-size: 32px; font-weight: bold; color: #721c24;">BLACKLISTED</div>
                        <div style="color: #666;">Current Status</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: #fff3cd; border-radius: 8px;">
                        <div style="font-size: 28px; font-weight: bold; color: #856404;">5</div>
                        <div style="color: #666;">Violations</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: #cce5ff; border-radius: 8px;">
                        <div style="font-size: 28px; font-weight: bold; color: #004085;">৳ 5,000</div>
                        <div style="color: #666;">Total Fines</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: #d4edda; border-radius: 8px;">
                        <div style="font-size: 28px; font-weight: bold; color: #155724;">12 days</div>
                        <div style="color: #666;">Remaining</div>
                    </div>
                </div>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; margin-top: 15px;">
                    <h3 style="color: #214332; margin-bottom: 10px;">Blacklist Details</h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                        <div>
                            <div class="tablesmallText">Blacklisted Since</div>
                            <div class="tableBoldText">March 15, 2024</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Duration</div>
                            <div class="tableBoldText">30 days (April 13, 2024 - May 13, 2024)</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Reason</div>
                            <div class="tableBoldText">Multiple price violations exceeding 15% cap</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Appeal Status</div>
                            <div class="tableBoldText" style="color: #856404;">Under Review</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pay Fines Section -->
            <div class="card">
                <h2>Outstanding Fines</h2>
                
                <div style="background: #fff3cd; padding: 15px; border-radius: 6px; margin: 15px 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div class="tableBoldText">Total Due: ৳ 2,500</div>
                            <div class="tablesmallText">Due Date: April 5, 2024</div>
                        </div>
                        <button class="btn btn-view" style="padding: 10px 20px;">Pay Now</button>
                    </div>
                </div>
                
                <table class="data-table" style="margin-top: 20px;">
                    <thead>
                        <tr>
                            <th>Fine ID</th>
                            <th>Violation Date</th>
                            <th>Reason</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>FINE-2024-001</td>
                            <td>Mar 25, 2024</td>
                            <td>Price violation - Rice sold at ৳ 68.50/kg (14.2% above cap)</td>
                            <td>৳ 1,000</td>
                            <td><span class="status status-pending">Pending</span></td>
                            <td><button class="btn btn-view">Pay</button></td>
                        </tr>
                       
                        <tr>
                            <td>FINE-2023-045</td>
                            <td>Dec 15, 2023</td>
                            <td>Price violation - Sugar sold above cap</td>
                            <td>৳ 500</td>
                            <td><span class="status status-completed">Paid</span></td>
                            <td><button class="btn btn-view" disabled>Paid</button></td>
                        </tr>
                    </tbody>
                </table>
                
                <div style="text-align: center; margin-top: 20px;">
                    <button class="addSellBatchBtn" style="background: #28a745; width: auto; padding: 12px 30px;">
                        Pay All Fines (৳ 1,500)
                    </button>
                </div>
            </div>
            
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>Violation History</h2>
                    <span class="tablesmallText">Last 12 months</span>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Commodity</th>
                            <th>Your Price</th>
                            <th>Price Cap</th>
                            <th>Excess %</th>
                            <th>Severity</th>
                            <th>Status</th>
                            <th>Penalty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background: #f8d7da;">
                            <td>Mar 25, 2024</td>
                            <td>Rice</td>
                            <td>৳ 68.50/kg</td>
                            <td>৳ 60.00/kg</td>
                            <td>14.2%</td>
                            <td><span style="color: #dc3545; font-weight: bold;">Critical</span></td>
                            <td><span class="status status-suspended">Blacklisted</span></td>
                            <td>৳ 1,000</td>
                        </tr>
                        <tr style="background: #fff3cd;">
                            <td>Mar 18, 2024</td>
                            <td>Wheat</td>
                            <td>৳ 45.20/kg</td>
                            <td>৳ 42.00/kg</td>
                            <td>7.6%</td>
                            <td><span style="color: #856404; font-weight: bold;">High</span></td>
                            <td><span class="status status-pending">Warning</span></td>
                            <td>৳ 500</td>
                        </tr>
                        <tr>
                            <td>Mar 10, 2024</td>
                            <td>Potato</td>
                            <td>৳ 32.50/kg</td>
                            <td>৳ 30.00/kg</td>
                            <td>8.3%</td>
                            <td><span style="color: #856404; font-weight: bold;">High</span></td>
                            <td><span class="status status-completed">Resolved</span></td>
                            <td>৳ 500</td>
                        </tr>
                        
                    
                    </tbody>
                </table>
                
                <div class="pagination">
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn">»</button>
                </div>
            </div>
            
            <div class="card">
                <h2>Submit Appeal</h2>
                <div style="background: #e8f5e9; padding: 20px; border-radius: 8px; margin: 15px 0;">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div>
                            <h3 style="color: #155724; margin-bottom: 10px;">Appeal Process</h3>
                            <p style="color: #666; margin-bottom: 15px;">
                                If you believe you were blacklisted unfairly or have evidence to support your case, 
                                you can submit an appeal. Our admin team will review your appeal within 3-5 business days.
                            </p>
                            <div class="tablesmallText">
                                <strong>Note:</strong> Paying outstanding fines improves your appeal chances.
                            </div>
                        </div>
                        <button class="btn btn-view" style="padding: 12px 24px;">Submit Appeal</button>
                    </div>
                </div>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; margin-top: 20px;">
                    <h3 style="color: #214332; margin-bottom: 10px;">Current Appeal Status</h3>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                        <div>
                            <div class="tablesmallText">Appeal ID</div>
                            <div class="tableBoldText">APL-2024-012</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Submitted On</div>
                            <div class="tableBoldText">March 20, 2024</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Status</div>
                            <div class="tableBoldText" style="color: #856404;">Under Review</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Review By</div>
                            <div class="tableBoldText">April 3, 2024</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Admin Remarks</div>
                            <div class="tableBoldText">"Awaiting additional documentation"</div>
                        </div>
                        <div>
                            <div class="tablesmallText">Action</div>
                            <button class="btn btn-edit">Upload Docs</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid">
                
                <div class="card">
                    <h2>Trust Score Impact</h2>
                    <div style="text-align: center; margin: 20px 0;">
                        <div style="font-size: 48px; font-weight: bold; color: #dc3545;">35</div>
                        <div style="color: #666;">Current Trust Score</div>
                    </div>
                    <div class="inventory-item">
                        <span>Before Violations</span>
                        <span><strong>85</strong></span>
                    </div>
                    <div class="inventory-item">
                        <span>Points Lost</span>
                        <span><strong>-50</strong></span>
                    </div>
                    <div class="inventory-item">
                        <span>Recovery Time</span>
                        <span><strong>6 months</strong></span>
                    </div>
                </div>
                 <div class="card">
                    <h2>Avoid Future Violations</h2>
                    <div style="margin-top: 15px;">
                        <div style="display: flex; align-items: start; gap: 10px; margin-bottom: 10px;">
                            <span style="color: #28a745;">✓</span>
                            <span>Check current price caps before selling</span>
                        </div>
                        <div style="display: flex; align-items: start; gap: 10px; margin-bottom: 10px;">
                            <span style="color: #28a745;">✓</span>
                            <span>Use the built-in price checker in your dashboard</span>
                        </div>
                        <div style="display: flex; align-items: start; gap: 10px; margin-bottom: 10px;">
                            <span style="color: #28a745;">✓</span>
                            <span>Keep your prices within 5% of government caps</span>
                        </div>
                        <div style="display: flex; align-items: start; gap: 10px;">
                            <span style="color: #28a745;">✓</span>
                            <span>Monitor price cap updates regularly</span>
                        </div>
                    </div>
                </div>
                
               
            </div>
        </div>
        
        <div class="footer">
            <p>Syndicate Buster Admin Panel © 2024</p>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syndicate Blacklist - Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    
</head>
<body>
    <div class="container">
        <div class="top">Govt Market Monitor - Syndicate-Buster Portal</div>
        <div class="dashboard">
            <div class="header">
                <h2>Syndicate Blacklist Management</h2>
                    <button class="logout-btn">Logout</button>
                
            </div>
            
           <div class="nav-menu">
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="adminManageUsers.php">Manage Users</a>
                <a href="adminPriceCap.php">Price Caps</a>
                <a href="adminViolation.php">Violations</a>
                <a href="adminBlacklist.php" style="background: rgba(255,255,255,0.1);">Blacklist </a>
                <a href="adminReports.php">Reports</a>
            </div>

            <div class="card">
        <div class="Blocklist-items">
            <div>
                <h2>Add Entity to Blacklist</h2>
            </div>
            <form">
                <div class="form-group">
                    <input type="text" id="entityName" class="form-control" placeholder="Enter entity name" required>
                </div>
                
                <div class="form-group">
                    <input type="text" id="entityId" class="form-control" placeholder="Enter user ID or business ID">
                </div>
                
                <div class="form-group">
                    <select id="entityRole" class="form-control" required>
                        <option value="">Select role</option>
                        <option value="farmer">Farmer</option>
                        <option value="middleman">Middleman</option>
                        <option value="wholesaler">Wholesaler</option>
                        <option value="retailer">Retailer</option>
                        <option value="organization">Organization</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <input type="text" id="location" class="form-control" placeholder="Enter location" required>
                </div>
                
                <div class="form-group">
                    <select id="blacklistReason" class="form-control" required>
                        <option value="">Select reason</option>
                        <option value="price_manipulation">Price Manipulation</option>
                        <option value="hoarding">Hoarding Commodities</option>
                        <option value="false_reporting">False Reporting</option>
                        <option value="multiple_violations">Multiple Violations</option>
                        <option value="syndicate_activity">Syndicate Activity</option>
                        <option value="other">Other</option>
                    </select>
                </div>
               
                <div class="form-group">
                    <select id="blacklistDuration" class="form-control" required>
                        <option value="30">30 days</option>
                        <option value="90">90 days</option>
                        <option value="180">6 months</option>
                        <option value="365">1 year</option>
                        <option value="permanent">Permanent</option>
                    </select>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="action-btn btn-warning" id="cancelBtn">Cancel</button>
                    <button type="submit" class="action-btn">Add to Blacklist</button>
                </div>
            </form>
        </div>
    </div>
            
                <div class="card">
                    <h3>Search & Filter</h3>
                    <div class="filter-grid">
                        <div class="search-box">
                            <input type="text" placeholder="Search by name, location, or reason...">
                            <button class="search-btn">Search</button>
                        </div>
                        <select class="filter-select">
                            <option value="">All Status</option>
                            <option value="blacklisted">Blacklisted</option>
                            <option value="watchlist">Watchlist</option>
                            <option value="removed">Removed</option>
                        </select>
                        <select class="filter-select">
                            <option value="">All Roles</option>
                            <option value="farmer">Farmer</option>
                            <option value="middleman">Middleman</option>
                            <option value="wholesaler">Wholesaler</option>
                            <option value="retailer">Retailer</option>
                        </select>
                        <select class="filter-select">
                            <option value="">All Regions</option>
                            <option value="dhaka">Dhaka</option>
                            <option value="chittagong">Chittagong</option>
                            <option value="rajshahi">Rajshahi</option>
                            <option value="khulna">Khulna</option>
                        </select>
                    </div>
                </div>
                
                <div class="card">
                    <div class="table-header">
                        <h3>Blacklist Records</h3>
                       
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50px;">
                                </th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Location</th>
                                <th>Blacklisted Since</th>
                                <th>Status</th>
                                <th>Violations</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                                            </table>
                    
                    <div class="pagination">
                        <button class="page-btn">«</button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>

                        <button class="page-btn">»</button>
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
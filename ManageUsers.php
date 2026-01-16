<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <div class="top">Govt Market Monitor - Syndicate-Buster Portal</div>
        
        <div class="dashboard">
            <div class="header">
                <h2>Manage Users</h2>
            </div>
            
            <div class="nav-menu">
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="ManageUsers.php">Manage Users</a>
                <a href="PriceCap.php">Price Caps</a>
                <a href="violation.php">Violations</a>
                <a href="Blacklist.php">Blacklist</a>
                <a href="reports.php">Reports</a>
            </div>
            
            <div class="stats">
                <div class="card">
                    <h3>Total Users</h3>
                    <div class="number">1,247</div>
                </div>
                <div class="card">
                    <h3>Active Users</h3>
                    <div class="number">1,154</div>
                </div>
                <div class="card">
                    <h3>Suspended</h3>
                    <div class="number">63</div>
                </div>
                <div class="card">
                    <h3>Banned</h3>
                    <div class="number">18</div>
                </div>
            </div>
            
            <div class="card">
                <h2>Search & Filter</h2>
                <div class="filter-grid">
                    <div class="search-box">
                        <input type="text" placeholder="Search by name, email or phone..." id="searchInput">
                        <button class="search-btn" >Search</button>
                    </div>
                    
                    <select class="filter-select" onchange="filterUsers()">
                        <option value="">All Roles</option>
                        <option value="1">Farmer</option>
                        <option value="2">Middleman</option>
                        <option value="3">Wholesaler</option>
                        <option value="4">Retailer</option>
                        <option value="5">Admin</option>
                    </select>
                    
                    <select class="filter-select" onchange="filterUsers()">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="suspended">Suspended</option>
                        <option value="banned">Banned</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="add-buttons">
                    <button class="search-btn" style="background-color: #28a745;">
                        + Add New User
                    </button>
                </div>
            </div>
            
            <div class="card">
                <h2>All Users</h2>
                <table class="table-box">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Trust Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">RH</div>
                                    <div class="tableFlexCol">
                                        <div class="tableBoldText">Rahim Hossain</div>
                                        <div class="tablesmallText">rahim@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="role role-farmer">Farmer</span>
                            </td>
                            <td>
                                <span class="status status-active">Active</span>
                            </td>
                            <td>
                                <span class="table-trust-score trust-high">92/100</span>
                            </td>
                            <td>
                                
                            </td>
                        </tr>
                     </tbody>
                </table>
                
                <div class="pagination">
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn">→</button>
                </div>
            </div>
            
            <div class="footer">
                <p>Syndicate Buster Admin Panel © 2024</p>
            
            </div>
        </div>
    </div>
    

</body>
</html>
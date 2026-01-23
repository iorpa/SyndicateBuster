<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] > 4) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];


$users_sql = "SELECT account_status as current_status,trust_score FROM users WHERE user_id = ?";
$users_stmt = $conn->prepare($users_sql);
$users_stmt->bind_param("i", $user_id);
$users_stmt->execute();
$users_result = $users_stmt->get_result();
$users_status = $users_result->fetch_assoc();
$users_stmt->close();

$statusColors = [
    'Active' => '#28a745',    
    'inactive' => '#6c757d',  
    'Suspended' => '#ffc107', 
    'Blacklisted' => '#dc3545'     
];
$backgroundColors = [
    'Active' => '#d4edda',    
    'inactive' => '#e2e3e5',  
    'Suspended' => '#fff3cd', 
    'Blacklisted' => '#f8d7da'     
];



$current_status = $users_status['current_status'] ?? 'Active';
$borderColor = $statusColors[$current_status] ?? '#dc3545';
$backgroundColor = $backgroundColors[$current_status] ?? '#f8d7da';

$statusDisplay = strtoupper($current_status);

$violations_sql = "SELECT 
        SUM(p.fine_amount) AS fine,
        COUNT(*) AS violation_count,
        (
            SELECT p2.suspension_days
            FROM violations v2
            JOIN penalties p2 ON v2.violation_id = p2.violation_id
            WHERE v2.reported_user_id = ?
            ORDER BY p2.issued_date DESC
            LIMIT 1
        ) AS suspension_days
    FROM violations v
    JOIN penalties p ON v.violation_id = p.violation_id
    WHERE v.reported_user_id = ?
";
$violations_stmt = $conn->prepare($violations_sql);
$violations_stmt->bind_param("ii",$user_id, $user_id);
$violations_stmt->execute();
$violations = $violations_stmt->get_result()->fetch_assoc();
$violations_stmt->close();


$penalties_sql = "SELECT 
                  p.penalty_id AS penalty_id,
                  v.violation_date AS violation_date,
                  v.description AS reason,
                  p.fine_amount AS fine_amount,
                  p.status AS status
              FROM violations v
              JOIN penalties p ON v.violation_id = p.violation_id
              WHERE v.reported_user_id = ?
              ORDER BY v.violation_date DESC
";
$penalties_stmt = $conn->prepare($penalties_sql);
$penalties_stmt->bind_param("i", $user_id);
$penalties_stmt->execute();
$penalties = $penalties_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$total_penalties= count($penalties);
$penalties_stmt->close();


$violations_history_sql = "SELECT 
    DATE(v.violation_date) as 'Date',
    c.commodity_name as 'Commodity',
        t.unit_price as 'Your Price',
        pc.max_price_per_unit as 'Price Cap',
        ROUND(((t.unit_price - pc.max_price_per_unit) / pc.max_price_per_unit * 100), 2) as 'Excess %',
        CASE 
        WHEN ((t.unit_price - pc.max_price_per_unit) / pc.max_price_per_unit * 100) <= 10 THEN 'Low'
        WHEN ((t.unit_price - pc.max_price_per_unit) / pc.max_price_per_unit * 100) <= 25 THEN 'Medium'
        WHEN ((t.unit_price - pc.max_price_per_unit) / pc.max_price_per_unit * 100) <= 50 THEN 'High'
        ELSE 'Critical'
    END as 'Severity',
        v.status as 'Status',
        COALESCE(
        CONCAT(
            CASE 
                WHEN p.fine_amount IS NOT NULL AND p.fine_amount > 0 THEN CONCAT('Fine: ৳', p.fine_amount)
                ELSE ''
            END,
            CASE 
                WHEN p.fine_amount IS NOT NULL AND p.fine_amount > 0 AND p.suspension_days IS NOT NULL THEN ', '
                ELSE ''
            END,
            CASE 
                WHEN p.suspension_days IS NOT NULL AND p.suspension_days > 0 THEN CONCAT('Suspension: ', p.suspension_days, ' days')
                ELSE ''
            END,
            CASE 
                WHEN (p.fine_amount IS NULL OR p.fine_amount = 0) 
                     AND (p.suspension_days IS NULL OR p.suspension_days = 0) 
                THEN 'Warning'
                ELSE ''
            END
        ),
        'No Penalty'
    ) as 'Penalty',
        v.violation_id,
    t.transaction_id,
    reporter.username as 'Reported By',
    reported.username as 'Your Name'
FROM violations v
INNER JOIN price_cap_violations pcv ON v.violation_id = pcv.violation_id
INNER JOIN transactions t ON pcv.transaction_id = t.transaction_id
INNER JOIN batches b ON t.batch_id = b.batch_id
INNER JOIN commodities c ON b.commodity_id = c.commodity_id
INNER JOIN price_caps pc ON c.commodity_id = pc.commodity_id 
AND v.violation_date BETWEEN pc.effective_date AND COALESCE(pc.expiry_date, '9999-12-31')
INNER JOIN users reporter ON v.reporter_id = reporter.user_id
INNER JOIN users reported ON v.reported_user_id = reported.user_id
LEFT JOIN penalties p ON v.violation_id = p.violation_id
WHERE v.violation_type = 'PRICE_CAP'
AND v.reported_user_id = ?
AND v.violation_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
ORDER BY v.violation_date DESC, v.violation_id DESC;
";
$violations_history_stmt = $conn->prepare($violations_history_sql);
$violations_history_stmt->bind_param("i", $user_id);
$violations_history_stmt->execute();
$violations_history = $violations_history_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$total_violations_history= count($violations_history);
$violations_history_stmt->close();



$appeals_sql = "SELECT appeal_id,
                appeal_date,status,
                review_date,review_notes
            FROM appeals WHERE user_id = ?
            ORDER BY review_date DESC
            limit 1;";
$appeals_stmt = $conn->prepare($appeals_sql);
$appeals_stmt->bind_param("i", $user_id);
$appeals_stmt->execute();
$appeals_result = $appeals_stmt->get_result();
$appeals_status = $appeals_result->fetch_assoc();
$appeals_stmt->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Violations - Syndicate Buster</title> 
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="model.css?v=<?php echo time(); ?>">
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
   <body>
    <body>
    <div class="container">
        <div class="header">Govt Market Monitor - Syndicate-Buster Portal</div>
        <div class="dashboard">
            <div class="userDetailsCard">
                <h1>My Transactions</h1>
                <form action="logout.php" method="post">
                    <button type="submit" class="smallBtn Red">Logout</button>
                </form>
            </div>
          
            <div class="navCard">
                <a href="../vendors/vendorDashboard.php">Dashboard</a>
                <a href="sell_product.php">Sell Product</a>
                <a href="../vendors/userInventory.php">My Inventory</a>
                <a href="../vendor/userTransaction.php" style="background: rgba(255,255,255,0.1);">Transactions</a>
                <a href="../vendor/userviolation.php">Violations</a>
            </div>

            
            <?php if ($current_status === 'Blacklist'): ?>
            <div class="alert" style="background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 24px;">⚠</span>
                    <div>
                        <strong style="font-size: 18px;">ACCOUNT SUSPENDED</strong>
                        <p>You are currently BLACKLISTED. All selling privileges are suspended until: <strong>April 25, 2024</strong></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

<div class="gridCard" style="border-left: 4px solid <?php echo $borderColor; ?>;">
    <h2 style="color: #214332; margin-bottom: 20px; ">Account Status</h2>
    <div class="grid">
        <div style="text-align: center; padding: 15px; background: <?php echo $backgroundColor; ?>; border-radius: 8px;">
            <div style="font-size: 32px; font-weight: bold; color:<?php echo $borderColor; ?>;"><?php echo htmlspecialchars($statusDisplay); ?></div>
            <div style="color: #666;">Current Status</div>
        </div>
        <div style="text-align: center; padding: 15px; background: #fff3cd; border-radius: 8px;">
            <div style="font-size: 28px; font-weight: bold; color: #856404;"><?php echo $violations['violation_count']; ?></div>
            <div style="color: #666;">Violations</div>
        </div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
        <div style="text-align: center; padding: 15px; background: #cce5ff; border-radius: 8px;">
            <div style="font-size: 28px; font-weight: bold; color: #004085;">৳ <?php echo $violations['fine']??0; ?></div>
            <div style="color: #666;">Total Fines</div>
        </div>
        <?php if (($violations['status'] ?? '') === 'Suspended'): ?>
            <div style="text-align: center; padding: 15px; background: #d4edda; border-radius: 8px;">
            <div style="font-size: 28px; font-weight: bold; color: #155724;">
                <?php echo ($violations['suspension_days'] ?? 0) . ' days'; ?>
            </div>
            <div style="color: #666;">Remaining</div>
            </div>
        <?php endif; ?>
    </div>
    <?php if ($current_status === 'Blacklist'): ?>
    <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; margin-top: 15px;">
        <h3 style="color: #214332; margin-bottom: 10px;">Blacklist Details</h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
            <div>
                <div class="tablesmallText">Blacklisted Since</div>
                <div class="tableBoldText">###</div>
            </div>
            <div>
                <div class="tablesmallText">Duration</div>
                <div class="tableBoldText">###</div>
            </div>
            <div>
                <div class="tablesmallText">Reason</div>
                <div class="tableBoldText">###</div>
            </div>
            <div>
                <div class="tablesmallText">Appeal Status</div>
                <div class="tableBoldText" style="color: #856404;">###</div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

            <!-- Pay Fines  -->
            <div class="gridCard">
                    <h2 class="GreenTextLarge">Fines</h2>
                
                <div style="background: #fff3cd; padding: 15px; border-radius: 6px; margin: 15px 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div class="tableBoldText">Total Due: ৳ 2,500</div>
                            <div class="tablesmallText">Due Date: April 5, 2024</div>
                        </div>
                        <button class="btn btn-view" style="padding: 10px 20px;">Pay Now</button>
                    </div>
                </div>
                <?php if($total_penalties > 0): ?>

                <table class="data-table" style="margin-top: 20px;">
                    <thead>
                        <tr class="tableBoldText">
                            <th>Fine ID</th>
                            <th>Violation Date</th>
                            <th>Reason</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="tablesmallText">
                    <?php foreach($penalties as $penaltie):?>
                            <td><?= htmlspecialchars($penaltie['penalty_id'])?></td>
                            <td><?= date('Y-m-d', strtotime($fine['violation_date'])) ?></td>
                            <td><?= htmlspecialchars($penaltie['reason'])?></td>
                            <td><?= htmlspecialchars($penaltie['fine_amount'])?></td>
                            <td><?= htmlspecialchars($penaltie['status'])?></td>
                            <td><button class="btn btn-view">Pay</button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #666;">
                        No transactions found.
                    </div>
                <?php endif; ?>
                <div style="text-align: center; margin-top: 20px;">
                    <button class="addSellBatchBtn" style="background: #28a745; width: auto; padding: 12px 30px;">
                        Pay All Fines (৳ 1,500)
                    </button>
                </div>
            </div>
            
            
            <div class="gridCard">
                <div class="table-top">
                    <h2 class="GreenTextLarge">Violation History</h2>
                    <span class="tablesmallText">Last 12 months</span>
                </div>
                <?php if($total_violations_history > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr class="tableBoldText">
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
                    <tbody class="tablesmallText">
                        <?php foreach($violations_history as $history): 
                $excess_percentage = 0;
                if(isset($history['Price Cap']) && $history['Price Cap'] > 0) {
                    $excess_percentage = (($history['Your Price'] - $history['Price Cap']) / $history['Price Cap']) * 100;
                }
                
                $severity = 'Low';
                $severity_color = '#28a745';
                if($excess_percentage > 50) {
                    $severity = 'Critical';
                    $severity_color = '#dc3545';
                } elseif($excess_percentage > 25) {
                    $severity = 'High';
                    $severity_color = '#fd7e14';
                } elseif($excess_percentage > 10) {
                    $severity = 'Medium';
                    $severity_color = '#ffc107';
                }
                $penalty_text = 'No Penalty';
                if(isset($history['fine_amount']) && $history['fine_amount'] > 0) {
                    $penalty_text = '৳ ' . number_format($history['fine_amount'], 2);
                } elseif(isset($history['suspension_days']) && $history['suspension_days'] > 0) {
                    $penalty_text = $history['suspension_days'] . ' days suspension';
                }
            ?>                     
            <tr>      
                <td><?= date('Y-m-d', strtotime($history['Date'] ?? $history['violation_date'])) ?></td>
                <td><?= htmlspecialchars($history['Commodity'] ?? $history['commodity_name'] ?? 'N/A') ?></td>
                <td>৳ <?= number_format($history['Your Price'] ?? $history['your_price'] ?? 0, 2) ?></td>
                <td>৳ <?= number_format($history['Price Cap'] ?? $history['price_cap'] ?? 0, 2) ?></td>
                <td>
                    <span style="color: <?= $severity_color ?>; font-weight: bold;">
                        <?= number_format($excess_percentage, 2) ?>%
                    </span>
                </td>
                <td>
                    <span style="color: <?= $severity_color ?>; font-weight: bold;">
                        <?= $severity ?>
                    </span>
                </td>
                <td>
                    <span class="status status-<?= strtolower($history['Status'] ?? $history['status'] ?? 'unknown') ?>">
                        <?= htmlspecialchars($history['Status'] ?? $history['status'] ?? 'Unknown') ?>
                    </span>
                </td>
                <td><?= $penalty_text ?></td>
            </tr>
            <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
        <div style="text-align: center; padding: 40px; color: #666;">
            No violation history found in the last 12 months.
        </div>
    <?php endif; ?>
               <!--page number-->
            </div>
            
            <div class="gridCard">
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
                        <button class="smallBtn Red" style="padding: 12px 24px;">Submit Appeal</button>
                    </div>
                </div>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; margin-top: 20px;">
                    <h3 style="color: #214332; margin-bottom: 10px;">Current Appeal Status</h3>
                    <?php if ($appeals_status): ?>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                        <div>
                            <div class="tablesmallText">Appeal ID</div>
                            <div class="tableBoldText"><?=htmlspecialchars($appeals_status['appeal_id']) ?></div>
                        </div>

                        <div>
                            <div class="tablesmallText">Submitted On</div>
                            <div class="tableBoldText"><?= date('d M Y', strtotime($appeals_status['appeal_date'])) ?></div>
                        </div>

                        <div>
                            <div class="tablesmallText">Status</div>
                            <span class="status <?= strtolower($appeals_status['status']) ?>">
                                <?= htmlspecialchars($appeals_status['status']) ?>
                            </span>
                        </div>

                        <div>
                            <div class="tablesmallText">Review By</div>
                            <div class="tableBoldText"><?= date('d M Y', strtotime($appeals_status['review_date'])) ?></div>
                        </div>

                        <div>
                            <div class="tablesmallText">Admin Remarks</div>
                            <div class="tableBoldText">
                                <?= $appeals_status['review_notes']
                                ? htmlspecialchars($appeals_status['review_notes'])
                                 : 'Awaiting review' ?>
                            </div>
                        </div>

                        <div>
                            <div class="tablesmallText">Action</div>
                            <button class="btn btn-edit">Upload Docs</button>
                        </div>

                    </div>
                    <?php else: ?>
                    <div>No appeal found</div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="gridCard">
                <div class="card">
                    <h2>Trust Score Impact</h2>
                    <div style="text-align: center; margin: 20px 0;">
                 <?php 
                        $trust_class = 'textMedium greenText';
                        if($users_status['trust_score'] < 60) {
                        $trust_class = 'textMedium redText';
                        } elseif($users_status['trust_score'] < 80) {
                        $trust_class = 'textMedium yellowText';
                        }                       
                        ?>
                        <div class="<?php echo $trust_class; ?>">
                            <?php echo $users_status['trust_score']; ?> / 100
                        </div>
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
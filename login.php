<?php
session_start();
require_once "config.php";

$error = ""; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT user_id, username, password, role_id
     FROM users 
     WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if(!$stmt){
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role_id'] = $user['role_id'];
             $update = $conn->prepare(
                "UPDATE users SET last_login = NOW() WHERE user_id = ?"
            );
            $update->bind_param("i", $user['user_id']);
            $update->execute();
            $update->close();

            if ($user['role_id'] == 5) {
                header("Location: admin/adminDashboard.php");
            } else {
                header("Location: vendors/vendorDashboard.php");
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Username not found.";
    }
        $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Syndicate Buster</title>
 <link rel="stylesheet" href="css/page.css">
        <link rel="stylesheet" href="css/text.css">
        <link rel="stylesheet" href="css/cards.css">
        <link rel="stylesheet" href="css/error.css">
        <link rel="stylesheet" href="css/button.css">
</head>
<body>
<div class="container">
        <div class="header">Govt Market Monitor - Syndicate-Buster Portal</div>
<div class="registerCard">
    <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form action="login.php" method="post">  
        <div class="registerBody">
            <div class="sub_title">Log In</div>
            <input type="text" name="username" class="inputAreaText" placeholder="Username" required>
            <input type="password" name="password" class="inputAreaText" placeholder="Password" required>
            <button type="submit" class="greenBtn">Log In</button>
            <a href="#" class="linktxt">Forgotten password?</a>
            <hr class="hrline">
            <a href="register.php" class="limebtn">Create new account</a>
        </div>
    </form>
    </div>
</div>  
</body>
</html>

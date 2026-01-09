<<<<<<< HEAD
<?php
session_start();
require_once "config.php";

$error = ""; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT user_id, username, password, role_id FROM users WHERE username = ?";
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
            switch ($user['role_id']) {
                case 1: header("Location: farmer_dashboard.php"); break;
                case 2: header("Location: middleman_dashboard.php"); break;
                case 3: header("Location: wholesaler_dashboard.php"); break;
                case 4: header("Location: retailer_dashboard.php"); break;
                case 5: header("Location: admin_dashboard.php"); break;
                case 6: header("Location: inspector_dashboard.php"); break;
                default: header("Location: login.php"); break;
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Username not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Syndicate Buster</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <p class="tital">Syndicate Buster</p>

    <!-- Display error message -->
    <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form action="login.php" method="post">  
        <div class="information_form">
            <input type="text" name="username" class="input" placeholder="Username" required>
            <input type="password" name="password" class="input" placeholder="Password" required>
            <button type="submit" class="greenBtn">Log In</button>
            <a href="#" class="linktxt">Forgotten password?</a>
            <hr class="hrline">
            <a href="register.php" class="limeBtn">Create new account</a>
        </div>
    </form>
</div>  
</body>
</html>
=======
<?php
session_start();
require_once "config.php";

$error = ""; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT user_id, username, password, role_id FROM users WHERE username = ?";
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
            switch ($user['role_id']) {
                case 1: header("Location: farmer_dashboard.php"); break;
                case 2: header("Location: middleman_dashboard.php"); break;
                case 3: header("Location: wholesaler_dashboard.php"); break;
                case 4: header("Location: retailer_dashboard.php"); break;
                case 5: header("Location: admin_dashboard.php"); break;
                case 6: header("Location: inspector_dashboard.php"); break;
                default: header("Location: login.php"); break;
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Username not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Syndicate Buster</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <p class="tital">Syndicate Buster</p>

    <!-- Display error message -->
    <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form action="login.php" method="post">  
        <div class="information_form">
            <input type="text" name="username" class="input" placeholder="Username" required>
            <input type="password" name="password" class="input" placeholder="Password" required>
            <button type="submit" class="greenBtn">Log In</button>
            <a href="#" class="linktxt">Forgotten password?</a>
            <hr class="hrline">
            <a href="register.php" class="limeBtn">Create new account</a>
        </div>
    </form>
</div>  
</body>
</html>
>>>>>>> 09f6418f665c4c9e3f3b2a742eea527adc036c3f

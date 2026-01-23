<?php
require_once "config.php";
$error = "";
$success = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $password = $_POST['password'];
    $role_id  = $_POST['role_id'];
    $location = $_POST['location'];
    $address = $_POST['address'];


    $sql = "INSERT INTO users (username, email, phone, password, role_id, address, location )
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssiss", $username, $email, $phone, $password, $role_id, $address, $location);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Signup failed: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Syndicate Buster</title>
    <link rel="stylesheet" href="css/page.css">
        <link rel="stylesheet" href="css/button.css">
        <link rel="stylesheet" href="css/text.css">
        <link rel="stylesheet" href="css/cards.css">
        <link rel="stylesheet" href="css/error.css">

</head>
<body>
    <div class="container">
        <div class="header">Govt Market Monitor - Syndicate-Buster Portal</div>
        <div class="registerCard">
            <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
             <form action="signup.php" method="post">  
            <div class="registerBody">
                <p class="sub_title">Register New Account</p>
                <input type="text" name="username" class="inputArea" placeholder="Name" required>
                <select name="role_id" class="inputArea" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="1">Farmer</option>
                    <option value="2">Middleman</option>
                    <option value="3">Wholesaler</option>
                    <option value="4">Retailer</option>
                </select>
                <input type="email" name="email" class="inputArea" placeholder="Email" required>
                <input type="text" name="phone" class="inputArea" placeholder="Phone number" required>
                  <select name="location" class="inputArea" required>
                    <option value="" disabled selected>Select your location</option>
                    <option value="Dhaka">Dhaka</option>
                    <option value="Chittagong">Chittagong</option>
                    <option value="Sylhet">Sylhet</option>
                    <option value="Rajshahi">Rajshahi</option>
                    <option value="Rangpur">Rangpur</option>
                    <option value="Khulna">Khulna</option>
                     <option value="Khulna">Khulna</option>
                    <option value="Barisal">Barisal</option>
                    <option value="Mymensingh">Mymensingh</option>
                </select>
                <input type="text" name="address" class="inputArea" placeholder="Address" required>
                <input type="password" name="password" class="inputArea" placeholder="Password" required>
                <button type="submit" class="greenBtn">Register</button>
                <a href="login.php" class="linktxt">Already have an account?</a>
            </div>
         </form>
        </div>
    </div>
</body>
</html>

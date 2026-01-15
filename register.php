<?php
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $password = $_POST['password'];
    $role_id  = $_POST['role_id'];

    $sql = "INSERT INTO users (username, email, phone, password, role_id)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $username, $email, $phone, $password, $role_id);

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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <p class="tital">Syndicate Buster</p>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="register.php" method="post">  
            <div class="information_form">
                <p class="sub_title">Create New Account</p>

                <input type="text" name="username" class="input2" placeholder="Name" required>

                <select name="role_id" class="input2" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="1">Farmer</option>
                    <option value="2">Middleman</option>
                    <option value="3">Wholesaler</option>
                    <option value="4">Retailer</option>
                    <option value="5">Admin</option>
                    <option value="6">Inspector</option>
                </select>

                <input type="email" name="email" class="input2" placeholder="Email" required>
                <input type="text" name="phone" class="input2" placeholder="Phone number" required>
                <input type="password" name="password" class="input2" placeholder="Password" required>
                <button type="submit" class="greenBtn">Create Account</button>
                <a href="login.html" class="linktxt">Already have an account?</a>
            </div>
        </form>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
         <p class="tital">Syndicate Buster</p>
        <form action="login.php" method="post">  
            <div class="login_form">
            <p class="sub_title">Create New Account</p>
            <input type="text" name="Username" class="input2" placeholder="Name" required>
            <input type="text" name="Role" class="input2" placeholder="Role" required>
            <input type="email" name="Email" class="input2" placeholder="Email" required>
            <input type="text" name="Phone" class="input2" placeholder="Phone number" required>
            <input type="password" name="Password" class="input2" placeholder="Password" required>
            <button class="greenBtn">Create Account</button>
            <a href="#" class="linktxt">Already have an account?</a>
            </div>
    </form>
</body>
</html>
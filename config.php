<<<<<<< HEAD
<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "syndicatebuster";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else {
    echo "Connection successful";
}
?>
=======
<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "syndicatebuster";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else {
    echo "Connection successful";
}
?>
>>>>>>> 09f6418f665c4c9e3f3b2a742eea527adc036c3f

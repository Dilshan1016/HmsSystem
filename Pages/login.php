<?php
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "loginsys";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, password FROM login WHERE username = ?");
$stmt->bind_param("s", $username);

$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $hashed_password);
$stmt->fetch();

if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
    // Login successful - redirect to dashboard
    header("Location: hospital-dashboard.html");
    exit();
} else {
    // Login failed - show popup using JavaScript
    echo "<script>alert('Login unsuccessful. Invalid username or password.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>

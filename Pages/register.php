<?php
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "loginsys";

// Create connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = trim($_POST['username']);
$password_raw = $_POST['password'];

if (empty($username) || empty($password_raw)) {
    echo "<script>alert('Username and password cannot be empty.'); window.history.back();</script>";
    exit;
}

// Check if username already exists
$checkStmt = $conn->prepare("SELECT id FROM login WHERE username = ?");
$checkStmt->bind_param("s", $username);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo "<script>alert('Username already taken. Please choose another.'); window.history.back();</script>";
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();

$password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO login (username, password) VALUES (?, ?)");
if ($stmt === false) {
    echo "<script>alert('Prepare failed: " . $conn->error . "'); window.history.back();</script>";
    $conn->close();
    exit;
}
$stmt->bind_param("ss", $username, $password_hashed);

if ($stmt->execute()) {
    echo "<script>alert('Successfully registered!'); window.location.href='login.php';</script>";
} else {
    echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>

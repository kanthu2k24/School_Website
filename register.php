<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$username = $_POST['username'];
$password =$_POST['password'];

$checkQuery = "SELECT * FROM users WHERE email = ? OR username = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("ss", $email, $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Error: The email or username already exists. Please choose a different one. <a href='index.php'>Go back</a>";
} else {

    $sql = "INSERT INTO users (name, email, phone, username, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $phone, $username, $password);

    if ($stmt->execute()) {
        header("Location: index.php?user=" . urlencode($username));
    } else {
        echo "Error: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();
?>

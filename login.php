<?php

$servername = "localhost";
$dbusername = "root"; 
$dbpassword = "";
$dbname = "mydatabase"; 


$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


session_start();


$loginMessage = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password); 

 
    $stmt->execute();

 
    $result = $stmt->get_result();

   
    if ($result->num_rows > 0) {
       
        $user = $result->fetch_assoc();

      
        $_SESSION['username'] = $user['username'];

        
        $loginMessage = "Login successful! Welcome, " . $_SESSION['username'] . ".";
    } else {
        $loginMessage = "Invalid username or password.";
    }

    
    $stmt->close();
}


$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
       
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

       
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }

       
        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .message-box {
            padding: 20px;
            margin-top: 10px;
            border-radius: 8px;
        }

        .success-message {
            color: #28a745;
            font-size: 16px;
            font-weight: bold;
        }

        .error-message {
            color: #dc3545;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <?php if ($loginMessage): ?>
        
        <div class="message-box">
            <p class="<?php echo ($loginMessage == "Invalid username or password.") ? 'error-message' : 'success-message'; ?>">
                <?php echo $loginMessage; ?>
            </p>
        </div>
    <?php else: ?>
        
        <h2>Login Form</h2>
        <form method="post" action="">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" value="Login">
        </form>
    <?php endif; ?>
</div>

</body>
</html>

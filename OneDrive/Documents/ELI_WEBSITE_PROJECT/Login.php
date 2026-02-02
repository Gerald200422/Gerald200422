<?php
// Database connection details
$servername = "localhost";
$username = "Admin";
$password = "Password123#";
$dbname = "eli_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind
$stmt = $conn->prepare("SELECT password_hash FROM users WHERE username = ?");
$stmt->bind_param("s", $input_username);

// Get the username and password from POST request
$input_username = $_POST['username'];
$input_password = $_POST['password'];

// Execute statement
$stmt->execute();
$stmt->store_result();

// Check if username exists
if ($stmt->num_rows > 0) {
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    
    // Verify password
    if (password_verify($input_password, $hashed_password)) {
        // Password is correct, login successful
        echo "Login successful!";
        // Optionally, start a session and redirect
        session_start();
        $_SESSION['username'] = $input_username;
        header("Location: ELI_MainWeb.html");
        exit();
    } else {
        // Password is incorrect
        echo "Invalid username or password.";
    }
} else {
    // Username does not exist
    echo "Invalid username or password.";
}

// Close connections
$stmt->close();
$conn->close();
?>

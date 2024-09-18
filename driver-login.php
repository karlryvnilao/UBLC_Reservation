<?php
include 'config/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $driverUsername = $_POST['name'];
    $driverPassword = $_POST['password'];

    // SQL query to check if the provided credentials match any record in the drivers table
    $sql = "SELECT * FROM driver WHERE name = ? AND password = ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$driverUsername, $driverPassword]);
    $driver = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($driver) {
        // Login successful
        $_SESSION['name'] = $driver['name'];
        $_SESSION['id'] = $driver['id'];
        header("Location: driver/dashboard.php"); // Redirect to the driver dashboard or any other page
        exit();
    } else {
        // Login failed
        $error = "Invalid username or password";
        header("Location: login.php?error=" . urlencode($error));
        exit();
    }
}
?>

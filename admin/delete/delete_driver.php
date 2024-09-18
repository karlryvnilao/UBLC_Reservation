<?php
include '../../config/connection.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve driver ID to be deleted
    $id = $_POST['id'];

    // SQL DELETE query
    $query = "DELETE FROM driver WHERE id = :id";
    $stmt = $con->prepare($query);

    // Bind parameters
    $stmt->bindParam(':id', $id);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect back to the page displaying the list of drivers
        header("Location: ../manage_driver.php");
        exit();
    } else {
        echo "Error deleting driver.";
    }
}
?>

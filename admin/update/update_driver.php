<?php
include '../../config/connection.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $id = $_POST['id'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];

    // SQL UPDATE query
    $query = "UPDATE driver SET name = :name, contact = :contact WHERE id = :id";
    $stmt = $con->prepare($query);

    // Bind parameters
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':contact', $contact);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect back to the page displaying the list of drivers
        header("Location: ../manage_driver.php");
        exit();
    } else {
        echo "Error executing update query: " . $stmt->errorCode();
        // Debugging: Display error information
        print_r($stmt->errorInfo());
    }
} else {
    echo "Invalid request method.";
}
?>

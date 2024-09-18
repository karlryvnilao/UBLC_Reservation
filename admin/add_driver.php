<?php
include '../config/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if form is submitted

    // Retrieve form data
    $name = $_POST['name'];
    $contact = $_POST['contact'];

    // Prepare SQL statement to insert data into the database
    $stmt = $con->prepare("INSERT INTO driver (name, contact) VALUES (:name, :contact)");

    // Bind parameters
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':contact', $contact);

    try {
        // Execute the prepared statement
        $stmt->execute();
        echo "Driver added successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    exit; // exit after processing the form
}
?>
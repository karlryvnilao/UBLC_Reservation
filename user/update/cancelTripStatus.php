<?php
include '../../config/connection.php';

// Check if the reservation ID is provided via GET parameter
if(isset($_GET['id'])) {
    // Sanitize input
    $userId = intval($_GET['id']);
    
    if ($userId <= 0) {
        echo "Invalid reservation ID";
        exit; // Stop further execution
    }
    
    try {
        // Prepare SQL statement
        $stmt = $con->prepare("UPDATE user_reservation_vehicle SET status = 'cancelled' WHERE id = :id");
        
        // Bind parameters
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        
        // Execute the query
        if ($stmt->execute()) {
            // Redirect to dashboard after updating status
            header('Location:../dashboard.php');
            exit; // Stop further execution
        } else {
            echo "Failed to cancel reservation. Please try again later.";
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        // Log the error for further analysis
        error_log("Error: " . $e->getMessage(), 0);
    }
} else {
    echo "Reservation ID not provided";
}
?>

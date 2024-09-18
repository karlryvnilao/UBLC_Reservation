<?php
// Include database connection code
include '../../config/connection.php';
include '../../session1.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve rating, comment, and reservation ID from form
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $user_id = $_SESSION['id'];
    $reservation_id = $_POST['reservation_id']; // Assuming reservation_id is submitted via form
    
    try {
        // Begin a transaction
        $con->beginTransaction();
        
        // Retrieve driver's ID associated with the reservation
        $queryDriverId = "SELECT driver_id FROM user_reservation_vehicle WHERE id = :reservation_id";
        $stmtDriverId = $con->prepare($queryDriverId);
        $stmtDriverId->bindParam(':reservation_id', $reservation_id);
        $stmtDriverId->execute();
        $driver_id_row = $stmtDriverId->fetch(PDO::FETCH_ASSOC);
        $driver_id = $driver_id_row['driver_id'];
      
        // SQL query to insert rating
        $sql = "INSERT INTO ratings (user_id, driver_id, reservation_id, rating, comment, created_at)
                VALUES (:user_id, :driver_id, :reservation_id, :rating, :comment, NOW())";
      
        // Prepare the SQL statement
        $stmt = $con->prepare($sql);
      
        // Bind parameters
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':driver_id', $driver_id); // Bind driver's ID
        $stmt->bindParam(':reservation_id', $reservation_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);
      
        // Execute the SQL statement to insert rating
        $stmt->execute();
        
        // Update the status of the reservation
        $updateStatusQuery = "UPDATE user_reservation_vehicle SET rate_status = 'done' WHERE id = :reservation_id";
        $updateStatusStmt = $con->prepare($updateStatusQuery);
        $updateStatusStmt->bindParam(':reservation_id', $reservation_id);
        $updateStatusStmt->execute();
        
        // Commit the transaction
        $con->commit();
        
        echo "Rating inserted successfully.";
    } catch (PDOException $e) {
        // Roll back the transaction if an error occurs
        $con->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>

<?php
// Include the database connection
include '../../config/connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

// Check if the trip ID is provided in the URL
if(isset($_GET['id'])) {
    $trip_id = $_GET['id'];
    
    // Check if the cancellation button is clicked
    if(isset($_POST['cancel'])) {
        // Update the reservation status to "cancelled"
        $updateQuery = "UPDATE user_reservation_vehicle SET status = 'cancelled' WHERE id = :trip_id";
        $updateStmt = $con->prepare($updateQuery);
        $updateStmt->bindParam(':trip_id', $trip_id);
        
        try {
            $updateStmt->execute();
            
            // Fetch trip details for email notification
            $tripDetailsQuery = "SELECT user_email FROM user_reservation_vehicle WHERE id = :trip_id";
            $tripDetailsStmt = $con->prepare($tripDetailsQuery);
            $tripDetailsStmt->bindParam(':trip_id', $trip_id);
            $tripDetailsStmt->execute();
            $tripDetailsRow = $tripDetailsStmt->fetch(PDO::FETCH_ASSOC);
            $user_email = $tripDetailsRow['user_email'];
            
            // Send cancellation email notification
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = '1920578@ub.edu.ph';
            $mail->Password   = 'oyuz opom igdd fdqi';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            $mail->setFrom('1920578@ub.edu.ph', 'Your Name');
            $mail->addAddress($user_email);
            $mail->isHTML(true);
            $mail->Subject = 'Trip Cancellation Notification';
            $mail->Body = "Your trip has been cancelled.";
            
            if($mail->send()) {
                header("Location: ../ticket.php"); // Redirect back to the main page
                exit(); // Exit after cancellation and notification
            } else {
                throw new Exception("Error sending email: " . $mail->ErrorInfo);
            }
        } catch(PDOException $e) {
            echo "Error updating trip status: " . $e->getMessage();
        } catch(Exception $e) {
            echo "Email sending error: " . $e->getMessage();
        }
    } else {
        echo "Cancellation button not clicked.";
    }
} else {
    echo "Trip ID not provided.";
}
?>

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
    
    // Assuming the driver ID and user email are sent via POST when the form is submitted
    if(isset($_POST['driver_id']) && isset($_POST['user_email'])) {
        $driver_id = $_POST['driver_id'];
        $user_email = $_POST['user_email'];
        
        // Fetch the driver's name and contact using the driver ID
        $driverQuery = "SELECT name, contact FROM driver WHERE id = :driver_id";
        $driverStmt = $con->prepare($driverQuery);
        $driverStmt->bindParam(':driver_id', $driver_id);
        $driverStmt->execute();
        
        // Retrieve the driver's details
        if($driverRow = $driverStmt->fetch(PDO::FETCH_ASSOC)) {
            $driver_name = $driverRow['name'];
            $driver_contact = $driverRow['contact'];
            
            // Update the user_reservation_vehicle table with the driver's name and contact
            $updateQuery = "UPDATE user_reservation_vehicle SET driver_name = :driver_name, driver_contact = :driver_contact, status = 'approved', driver_id = :driver_id WHERE id = :trip_id";
            $updateStmt = $con->prepare($updateQuery);
            $updateStmt->bindParam(':driver_name', $driver_name);
            $updateStmt->bindParam(':driver_contact', $driver_contact);
            $updateStmt->bindParam(':driver_id', $driver_id);
            $updateStmt->bindParam(':trip_id', $trip_id);

            // Execute the update query
            $mail = new PHPMailer(true);
            if($updateStmt->execute()) {
                // Trip status updated successfully
                
                
                // Send an email to the user using PHPMailer
                // $mail = new PHPMailer\PHPMailer\PHPMailer();
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = '1920578@ub.edu.ph';                     //SMTP username
                $mail->Password   = 'oyuz opom igdd fdqi';                               //SMTP password
                $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
                $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('1920578@ub.edu.ph', 'Your Name');
                $mail->addAddress($user_email);    //Add a recipient
                // $mail->addAddress('ellen@example.com');               //Name is optional
                // $mail->addReplyTo('info@example.com', 'Information');
                // $mail->addCC('cc@example.com');
                // $mail->addBCC('bcc@example.com');

                //Attachments
                // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Approved Trip Ticket';
                $mail->Body = "Your trip has been approved. The driver assigned to your trip is: $driver_name, Contact: $driver_contact";

                if($mail->send()) {
                    // Email sent successfully
                    header("Location: ../ticket.php"); // Redirect back to the main page
                    exit();
                } else {
                    // Error sending email
                    echo "Error sending email: " . $mail->ErrorInfo;
                }
            } else {
                // Error updating trip status
                echo "Error updating trip status.";
            }
        } else {
            // Driver not found with the provided ID
            echo "Driver not found.";
        }
    } else {
        // Driver ID or user email not provided
        echo "Driver ID or user email not provided.";
    }
} else {
    // Trip ID not provided
    echo "Trip ID not provided.";
}
?>

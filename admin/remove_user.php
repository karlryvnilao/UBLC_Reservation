<?php
// Include database connection
include '../config/connection.php';

// Check if form is submitted
if (isset($_POST['form_remove'])) {
    // Retrieve user ID from the form
    $userId = isset($_POST['form_id']) ? $_POST['form_id'] : '';

    // Perform deletion logic (replace this with your actual deletion code)
    try {
        $stmt = $con->prepare("DELETE FROM users WHERE id = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        // Redirect to the user list page after deletion
        header("Location: congratulation.php?goto_page=users.php&message=User deleted successfully");
        exit;
    } catch (PDOException $e) {
        // Handle the error appropriately (log it, display a message, etc.)
        echo "Error: " . $e->getMessage();
        exit;
    }
} else {
    // If the form is not submitted, redirect to an error page or handle it accordingly
    header("Location: error.php");
    exit;
}
?>

<?php
// Include your database connection code (e.g., config.php)
include './php/config.php';

// Check if the user is logged in (you might need to implement user authentication)
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Check if the required parameters are provided (user_id, book_id)
    if (isset($_GET['book_id'])) {
        $book_id = $_GET['book_id'];

        // Insert the interaction record into your book_interactions table
        $timestamp = date('Y-m-d H:i:s'); // Get the current timestamp
        $insert_query = "INSERT INTO book_interactions (user_id, book_id, timestamp) VALUES ('$user_id', '$book_id', '$timestamp')";

        if (mysqli_query($con, $insert_query)) {
            // Interaction recorded successfully
            echo 'Interaction recorded successfully.';
        } else {
            // Error recording interaction
            echo 'Error recording interaction: ' . mysqli_error($con);
        }
    } else {
        // Missing book_id parameter
        echo 'Missing book_id parameter.';
    }
} else {
    // User is not logged in
    echo 'User is not logged in.';
}
?>

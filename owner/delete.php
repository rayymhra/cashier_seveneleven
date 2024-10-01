<?php
include "../koneksi.php";

// Check if ID is provided via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the user from the database
    $deleteQuery = "DELETE FROM user WHERE id = $id";
    if (mysqli_query($conn, $deleteQuery)) {
        // Redirect back to the main page with a success message
        header("Location: kelola_users.php?delete=success");
        exit;
    } else {
        // Error occurred
        echo "Error deleting user: " . mysqli_error($conn);
    }
}
?>

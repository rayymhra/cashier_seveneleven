<?php
include "../koneksi.php";

// cek apakah id ada
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the user from the database
    $deleteQuery = "DELETE FROM supplier WHERE id = $id";
    if (mysqli_query($conn, $deleteQuery)) {
        // Redirect back to the main page with a success message
        header("Location: dashboard_gudang.php?page=kelola_supplier&delete=success");
        exit;
    } else {
        // Error occurred
        echo "Error deleting user: " . mysqli_error($conn);
    }
}
?>
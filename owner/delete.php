<?php
include "../koneksi.php";

// cek apakah id ada
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    
    $deleteQuery = "DELETE FROM user WHERE id = $id";
    if (mysqli_query($conn, $deleteQuery)) {
        // redirect ke halaman awal dgn sweetalert
        header("Location: dashboard.php?page=kelola_user&delete=success");
        exit;
    } else {
        // Error occurred
        echo "Error deleting user: " . mysqli_error($conn);
    }
}
?>

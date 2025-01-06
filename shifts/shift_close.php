<?php
// Include database connection
include "../koneksi.php";  // Assuming the connection file is in the parent directory

session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to close a shift.";
    exit;
}

$kasir_id = $_SESSION['user_id'];  // Get the current user's ID from the session

// Fetch the current open shift for the cashier
$query = "SELECT * FROM shifts WHERE kasir_id = '$kasir_id' AND buka = 1";
$shift = mysqli_fetch_assoc(mysqli_query($conn, $query));

if (!$shift) {
    echo "No active shift to close.";
    exit;
}

// Handle shift closing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['close_shift'])) {
    $balance_tutup = intval($_POST['balance_tutup']);
    $waktu_tutup = date('Y-m-d H:i:s');

    // Get balance_buka for the open shift
    $balance_buka = $shift['balance_buka'];
    $balance_selisih = $balance_tutup - $balance_buka;

    // Update shift to close it
    $update_query = "UPDATE shifts 
                     SET waktu_tutup = '$waktu_tutup', 
                         balance_tutup = '$balance_tutup', 
                         balance_selisih = '$balance_selisih', 
                         buka = 0 
                     WHERE id = '{$shift['id']}'";
    if (mysqli_query($conn, $update_query)) {
        echo "Shift closed successfully!";
        header("Location: ../login.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seven Eleven Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/style.css">
    <!-- sweetalert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    <form method="POST" action="shift_close.php">
        <label for="balance_tutup">Closing Balance:</label>
        <input type="number" id="balance_tutup" name="balance_tutup" required>
        <button type="submit" name="close_shift">Close Shift</button>
    </form>

</body>
</html>

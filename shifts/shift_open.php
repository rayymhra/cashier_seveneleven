<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['open_shift'])) {
    $kasir_id = $_SESSION['user_id'];
    $balance_buka = intval($_POST['balance_buka']);
    $waktu_buka = date('Y-m-d H:i:s');

    // Insert new shift
    $query = "INSERT INTO shifts (kasir_id, waktu_buka, balance_buka, buka) 
              VALUES ('$kasir_id', '$waktu_buka', '$balance_buka', 1)";
    if (mysqli_query($conn, $query)) {
        // Get the newly created shift ID
        $shift_id = mysqli_insert_id($conn);

        // Save the shift ID in the session
        $_SESSION['shift_id'] = $shift_id;

        // Redirect to dashboard with success message
        $_SESSION['success'] = "Shift opened successfully!";
        header("Location: ../kasir/dashboard_kasir.php");
        exit;
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
    <form method="POST" action="shift_open.php">
        <label for="balance_buka">Opening Balance:</label>
        <input type="number" id="balance_buka" name="balance_buka" required>
        <button type="submit" name="open_shift">Open Shift</button>
    </form>
</body>


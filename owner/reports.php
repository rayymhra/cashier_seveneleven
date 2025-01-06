<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get the user ID
$user_id = $_SESSION['user_id'];

// Check if the user is the owner (assume owner has a user_id of 1, change this as needed)
$is_owner = ($user_id == 1); // Adjust the condition based on how you identify the owner

$date_filter = $_GET['filter'] ?? 'daily'; // 'daily', 'weekly', 'monthly'
$filter_query = '';

if ($date_filter === 'daily') {
    $filter_query = "DATE(waktu_buka) = CURDATE()";
} elseif ($date_filter === 'weekly') {
    $filter_query = "YEARWEEK(waktu_buka) = YEARWEEK(CURDATE())";
} elseif ($date_filter === 'monthly') {
    $filter_query = "MONTH(waktu_buka) = MONTH(CURDATE()) AND YEAR(waktu_buka) = YEAR(CURDATE())";
}

// If the user is not the owner, filter by their kasir_id
if (!$is_owner) {
    $kasir_id = $_SESSION['user_id'];
    $filter_query .= " AND kasir_id = '$kasir_id'";
}

// Query to fetch shift reports
$query = "SELECT * FROM shifts WHERE $filter_query";
$result = mysqli_query($conn, $query);

// Check if there was an error with the query
if (!$result) {
    die("SQL Error: " . mysqli_error($conn)); // Output SQL error for debugging
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Reports</h1>
        <form method="GET" action="dashboard.php">
            <input type="hidden" name="page" value="reports"> <!-- Added hidden input for page -->
            <select name="filter" onchange="this.form.submit()">
                <option value="daily" <?= $date_filter == 'daily' ? 'selected' : '' ?>>Daily</option>
                <option value="weekly" <?= $date_filter == 'weekly' ? 'selected' : '' ?>>Weekly</option>
                <option value="monthly" <?= $date_filter == 'monthly' ? 'selected' : '' ?>>Monthly</option>
            </select>
        </form>

        <table class="table table-bordered">
            <tr>
                <th>Cashier ID</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Opening Balance</th>
                <th>Closing Balance</th>
                <th>Discrepancy</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <?php
                $discrepancy = $row['balance_tutup'] - $row['balance_buka'];  // Calculate discrepancy
                ?>
                <tr>
                    <td><?= $row['kasir_id'] ?></td>
                    <td><?= $row['waktu_buka'] ?></td>
                    <td><?= $row['waktu_tutup'] ?></td>
                    <td><?= $row['balance_buka'] ?></td>
                    <td><?= $row['balance_tutup'] ?></td>
                    <td><?= $discrepancy ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>

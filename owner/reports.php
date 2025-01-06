<?php
// reports.php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$date_filter = $_GET['filter'] ?? 'daily'; // 'daily', 'weekly', 'monthly'
$kasir_id = $_SESSION['user_id'];
$filter_query = '';

if ($date_filter === 'daily') {
    $filter_query = "DATE(waktu_buka) = CURDATE()";
} elseif ($date_filter === 'weekly') {
    $filter_query = "YEARWEEK(waktu_buka) = YEARWEEK(CURDATE())";
} elseif ($date_filter === 'monthly') {
    $filter_query = "MONTH(waktu_buka) = MONTH(CURDATE()) AND YEAR(waktu_buka) = YEAR(CURDATE())";
}

$query = "SELECT * FROM shifts WHERE kasir_id = '$kasir_id' AND $filter_query";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    echo "Shift ID: {$row['id']} - Balance Open: {$row['balance_buka']} - Balance Close: {$row['balance_tutup']}<br>";
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Reports</h1>
    <form method="GET">
        <select name="filter" onchange="this.form.submit()">
            <option value="daily" <?= $filter == 'daily' ? 'selected' : '' ?>>Daily</option>
            <option value="weekly" <?= $filter == 'weekly' ? 'selected' : '' ?>>Weekly</option>
            <option value="monthly" <?= $filter == 'monthly' ? 'selected' : '' ?>>Monthly</option>
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
            <tr>
                <td><?= $row['kasir_id'] ?></td>
                <td><?= $row['waktu_buka'] ?></td>
                <td><?= $row['waktu_tutup'] ?></td>
                <td><?= $row['balance_buka'] ?></td>
                <td><?= $row['balance_tutup'] ?></td>
                <td><?= $row['balance_selisih'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>

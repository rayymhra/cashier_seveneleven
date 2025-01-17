<?php
session_start();

if (!isset($_SESSION['receipt'])) {
    echo "No receipt data available.";
    exit;
}

$receipt_data = $_SESSION['receipt'];
$shift = $receipt_data['shift'];
$transactions = $receipt_data['transactions'];
$total_transactions = $receipt_data['total_transactions'];
$balance_tutup = $receipt_data['balance_tutup'];
$balance_selisih = $receipt_data['balance_selisih'];
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shift Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center">Shift Receipt</h3>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h6>Opening Balance</h6>
                                <p>Rp <?= number_format($shift['balance_buka'], 0, ',', '.') ?></p>

                                <h6>Closing Balance</h6>
                                <p>Rp <?= number_format($balance_tutup, 0, ',', '.') ?></p>
                            </div>
                            <div class="col-6">
                                <h6>Total Transaction</h6>
                                <p>Rp <?= number_format($total_transactions, 0, ',', '.') ?></p>

                                <h6>Balance Difference</h6>
                                <p>Rp <?= number_format($balance_selisih, 0, ',', '.') ?></p>
                            </div>
                        </div>
                    </div>
                </div>


                <h4 class="mt-4 text-center">Transactions History</h4>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Date</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td><?= $transaction['id'] ?></td>
                                        <td><?= $transaction['tanggal'] ?></td>
                                        <td>Rp <?= number_format($transaction['harga_total'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <button class="btn btn-success w-100 mt-5 print-button" onclick="window.print()">Print Receipt</button>
                <a href="../login.php" class="btn btn-success w-100 mt-2 print-button">Log Out</a>
            </div>
        </div>
    </div>
</body>

</html>
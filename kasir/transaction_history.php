<?php
include "../koneksi.php";

// session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$query = "SELECT t.id, t.tanggal, t.user_id, t.harga_total, t.bayar, t.kembalian, t.shift_id 
          FROM transaksi t 
          ORDER BY t.tanggal DESC";
$transactions = mysqli_query($conn, $query);

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transaction History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .details-row {
            display: none;
        }

        .toggle-details {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h3 class="text-center mb-3">Transaction History</h3>
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <!-- <th>Kasir</th> -->
                            <th>Total</th>
                            <th>Bayar</th>
                            <th>Kembalian</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($transaction = mysqli_fetch_assoc($transactions)): ?>
                            <tr>
                                <td><?= $transaction['id'] ?></td>
                                <td><?= $transaction['tanggal'] ?></td>
                                <!-- <td><?= $transaction['user_id'] ?></td> -->
                                <td>Rp. <?= number_format($transaction['harga_total'], 0, ',', '.') ?></td>
                                <td>Rp. <?= number_format($transaction['bayar'], 0, ',', '.') ?></td>
                                <td>Rp. <?= number_format($transaction['kembalian'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge bg-success toggle-details" data-id="<?= $transaction['id'] ?>">View</span>
                                </td>
                            </tr>
                            <tr class="details-row" id="details-<?= $transaction['id'] ?>">
                                <td colspan="7">
                                    <strong>Transaction Details:</strong>
                                    <ul>
                                        <?php
                                        $details_query = "
                                                        SELECT dt.barang_id, dt.jumlah, dt.harga_total, b.nama AS barang_nama 
                                                        FROM detail_transaksi dt 
                                                        JOIN barang b ON dt.barang_id = b.id 
                                                        WHERE dt.transaksi_id = " . $transaction['id'];
                                        $details_result = mysqli_query($conn, $details_query);
                                        while ($detail = mysqli_fetch_assoc($details_result)): ?>
                                            <li>
                                                Barang ID: <?= $detail['barang_id'] ?><br>
                                                Produk: <?= $detail['barang_nama'] ?><br>
                                                Jumlah: <?= $detail['jumlah'] ?><br>
                                                Total: Rp <?= number_format($detail['harga_total'], 0, ',', '.') ?>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-details').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const detailsRow = document.getElementById('details-' + id);
                if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                    detailsRow.style.display = 'table-row';
                } else {
                    detailsRow.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>
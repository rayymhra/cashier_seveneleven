<?php
include "../koneksi.php";

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
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .toggle-details {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <table id="transactionTable" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
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
                        <td>Rp. <?= number_format($transaction['harga_total'], 0, ',', '.') ?></td>
                        <td>Rp. <?= number_format($transaction['bayar'], 0, ',', '.') ?></td>
                        <td>Rp. <?= number_format($transaction['kembalian'], 0, ',', '.') ?></td>
                        <td>
                            <span class="badge bg-success toggle-details" data-id="<?= $transaction['id'] ?>">View</span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
    var table = $('#transactionTable').DataTable({
        responsive: true,
        "columnDefs": [{
            "targets": [5], // Make "Details" column non-sortable
            "orderable": false
        }]
    });

    $('.toggle-details').click(function() {
        var button = $(this);
        var transactionId = button.data('id');
        var row = table.row(button.closest('tr'));

        if (row.child.isShown()) {
            // If already visible, hide it
            row.child.hide();
            button.removeClass('expanded').text('View');
        } else {
            // Fetch details if not already loaded
            if (!button.hasClass('loaded')) {
                $.ajax({
                    url: "get_transaction_details.php",
                    type: "GET",
                    data: { id: transactionId },
                    success: function(response) {
                        row.child(response).show();
                        button.addClass('loaded expanded').text('Hide');
                    }
                });
            } else {
                row.child.show();
                button.addClass('expanded').text('Hide');
            }
        }
    });
});

    </script>
</body>

</html>

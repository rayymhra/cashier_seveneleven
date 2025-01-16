<?php
// Include database connection
include "../koneksi.php";

session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to close a shift.";
    exit;
}

$kasir_id = $_SESSION['user_id']; // Current user's ID

// Fetch the current open shift for the cashier
$query = "SELECT * FROM shifts WHERE kasir_id = '$kasir_id' AND buka = 1";
$shift = mysqli_fetch_assoc(mysqli_query($conn, $query));

if (!$shift) {
    echo "No active shift to close.";
    exit;
}

// Calculate total transactions for the current shift
$shift_id = $shift['id'];
$transaction_query = "SELECT SUM(harga_total) AS total_transactions 
                      FROM transaksi 
                      WHERE shift_id = '$shift_id'";
$result = mysqli_fetch_assoc(mysqli_query($conn, $transaction_query));
$total_transactions = $result['total_transactions'] ?? 0;

// Calculate expected balance
$expected_balance = $shift['balance_buka'] + $total_transactions;

// Handle shift closing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['close_shift'])) {
    $balance_tutup = intval($_POST['balance_tutup']);
    $waktu_tutup = date('Y-m-d H:i:s');
    $balance_selisih = $balance_tutup - $expected_balance;

    // Update shift to close it
    $update_query = "UPDATE shifts 
                     SET waktu_tutup = '$waktu_tutup', 
                         balance_tutup = '$balance_tutup', 
                         balance_selisih = '$balance_selisih', 
                         buka = 0 
                     WHERE id = '$shift_id'";
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['success'] = "Shift closed successfully!";
        header("Location: ../login.php");
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
    <title>Close Shift</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    <div class="container mt-5">
        <h3>Close Shift</h3>
        <p><strong>Opening Balance:</strong> Rp. <?= number_format($shift['balance_buka'], 0, ',', '.') ?></p>
        <p><strong>Total Transactions:</strong> Rp. <?= number_format($total_transactions, 0, ',', '.') ?></p>
        <p><strong>Expected Balance:</strong> Rp. <?= number_format($expected_balance, 0, ',', '.') ?></p>


        <form id="close-shift-form" method="POST" action="">
    <div class="mb-3">
        <label for="balance_tutup">Closing Balance:</label>
        <input type="number" id="balance_tutup" name="balance_tutup" class="form-control" required>
    </div>
    <button type="button" id="validate-balance" class="btn btn-primary">Validate & Close Shift</button>
    <!-- Hidden submit button for form submission -->
    <button type="submit" name="close_shift" id="hidden-close-shift" style="display: none;"></button>
</form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('validate-balance').addEventListener('click', function () {
            const expectedBalance = <?= $expected_balance ?>;
            const closingBalance = parseInt(document.getElementById('balance_tutup').value);

            if (isNaN(closingBalance)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please enter a valid closing balance.'
                });
                return;
            }

            if (closingBalance === expectedBalance) {
                Swal.fire({
                    icon: 'success',
                    title: 'Balance Sesuai',
                    text: 'Balance nya sesuai, Lanjut Tutup Shift nya?',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('close-shift-form').submit();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Balance Tidak Sesuai',
                    html: `Balance tutup nya (Rp ${closingBalance.toLocaleString('id-ID')}) Tidak sesuai dengan balance yang seharusnya yaitu: (Rp ${expectedBalance.toLocaleString('id-ID')}).Do you want to proceed?`,
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('close-shift-form').submit();
                    }
                });
            }
        });
    </script>
    

    <script>
        document.getElementById('validate-balance').addEventListener('click', function () {
    const expectedBalance = <?= $expected_balance ?>;
    const closingBalance = parseInt(document.getElementById('balance_tutup').value);

    if (isNaN(closingBalance)) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please enter a valid closing balance.'
        });
        return;
    }

    if (closingBalance === expectedBalance) {
        Swal.fire({
            icon: 'success',
            title: 'Balance Matches',
            text: 'The balance matches the expected value. Do you want to proceed?',
            showCancelButton: true,
            confirmButtonText: 'Yes, Close Shift',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hidden-close-shift').click(); // Trigger hidden submit button
            }
        });
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Balance Mismatch',
            html: `The closing balance (Rp ${closingBalance.toLocaleString('id-ID')}) does not match the expected balance (Rp ${expectedBalance.toLocaleString('id-ID')}).<br>Do you want to proceed?`,
            showCancelButton: true,
            confirmButtonText: 'Yes, Proceed',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hidden-close-shift').click(); // Trigger hidden submit button
            }
        });
    }
});

    </script>
</body>

</html>

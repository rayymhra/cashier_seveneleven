<?php
include "../koneksi.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Kasir') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_SESSION['shift_id'])) {
    die("Shift not started. Please start your shift before making transactions.");
}


//akan terexecute jika tombol complete transaksi ditekan
if (isset($_POST['submit'])) {
    $tanggal = date('Y-m-d'); //ngambil tgl saat ini
    $user_id = $_SESSION['user_id']; //ngambil id yg lagi login
    $shift_id = $_SESSION['shift_id'];
    $bayar = $_POST['bayar']; //ngambil jumlah uang yg dibayar

    // itung total harga
    $total_harga = 0; //set nilai awal total harga
    foreach ($_POST['barang_id'] as $key => $barang_id) { // foreach dari array barang_id jadi $key
        $jumlah = $_POST['jumlah'][$key]; // jumlah barang yang dibeli
        $harga_per_barang = $_POST['harga_total'][$key]; // harga each productnya
        $total_harga += $jumlah * $harga_per_barang; // menghitung total harga untuk barang yang sedang diproses dengan mengalikan jumlah barang ($jumlah) dengan harga per barang ($harga_per_barang). Kemudian, kita menambahkan hasilnya ke $total_harga. Simbol += artinya "tambahkan ke"
    }

    $kembalian = $bayar - $total_harga; //itung kembalian

    // insert data ke tabel transaksi
    $queryTransaksi = "INSERT INTO transaksi(tanggal, user_id, harga_total, bayar, kembalian, shift_id) 
                       VALUES('$tanggal', '$user_id', '$total_harga', '$bayar', '$kembalian', '$shift_id')";
    mysqli_query($conn, $queryTransaksi);

    $transaksi_id = mysqli_insert_id($conn); //ngambil id transaksi

    // simpen detail transaksi
    // setiap barang yang dibeli, kita memasukkan detailnya (seperti jumlah dan harga) ke dalam tabel detail_transaksi.
    foreach ($_POST['barang_id'] as $key => $barang_id) {
        $jumlah = $_POST['jumlah'][$key];
        $harga_per_barang = $_POST['harga_total'][$key];
        $total_per_item = $jumlah * $harga_per_barang; // Calculate the total for each item
        $queryDetail = "INSERT INTO detail_transaksi(transaksi_id, barang_id, jumlah, harga_total) 
                        VALUES('$transaksi_id', '$barang_id', '$jumlah', '$total_per_item')";
        mysqli_query($conn, $queryDetail);


        // Setelah transaksi, kita mengurangi stok barang yang terjual di tabel barang
        $queryUpdateStock = "UPDATE barang SET stok = stok - '$jumlah' WHERE id = '$barang_id'";
        mysqli_query($conn, $queryUpdateStock);
    }

    // echo "<script>alert('Transaction successfully recorded!');window.location='dashboard_kasir.php?page=kelola_transaksi';</script>";

    // Set success message in session
    $_SESSION['success'] = "Transaction successfully recorded!";

    // Redirect to avoid form resubmission
    header("Location: dashboard_kasir.php?page=kelola_transaksi&success=1");
    exit;
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/style.css">
    <!-- sweetalert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>

    <div class="container">
        <h3>Kelola Transaksi</h3>
        <form action="" method="post">
            <div id="items-container">
                <div class="row mb-3">
                    <div class="col-6 mb-3">
                        <label for="barang_id">Product</label>
                        <select name="barang_id[]" class="form-select" required>
                            <option value="" disabled selected>Select Product</option>
                            <?php
                            $sqlBarang = $conn->query("SELECT id, nama, harga FROM barang WHERE stok > 0");
                            while ($row = $sqlBarang->fetch_assoc()) { ?>
                                <option value="<?php echo $row['id']; ?>" data-harga="<?php echo $row['harga']; ?>">
                                    <?php echo $row['nama']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <div class="mb-3">
                            <label for="jumlah">Quantity</label>
                            <input type="number" name="jumlah[]" class="form-control" required min="1">
                        </div>

                        <div class="mb-3">
                            <label for="harga_total">Price (Rp)</label>
                            <span class="harga_total_text"></span>
                            <input type="hidden" name="harga_total[]" class="harga_total_input">
                        </div>
                    </div>

                    <div class="mb-3 col-6">
                        <button type="button" class="btn btn-success mb-4 remove-item">Remove</button>
                        <button type="button" id="add-item" class="btn btn-success mb-3">Add Item</button>
                    </div>


                </div>
            </div>

            <div class="mb-3">
                <label>Total Price (Rp): </label>
                <span id="total-price">Rp 0</span>
            </div>

            <div class="mb-3">
                <label for="bayar">Amount Paid (Rp)</label>
                <input type="number" name="bayar" class="form-control" required>
            </div>

            <button type="submit" name="submit" class="btn btn-success">Complete Transaction</button>
            <!-- munculin modal kaya struk gitu tiap transaksi -->
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === '1') {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Transaction successfully recorded!',
        }).then(() => {
            // Clear the URL parameters after the SweetAlert to prevent it from showing again
            const newURL = window.location.href.split('?')[0];
            window.history.pushState(null, null, newURL);
        });
    }
});

    </script>

    <script>
        // Add new item row dynamically
        // Delegate the click event for 'Add Item' button
        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'add-item') {
                let container = document.getElementById('items-container');
                let newItem = container.children[0].cloneNode(true);
                newItem.querySelector('select').value = ''; // Reset select
                newItem.querySelector('input[name="jumlah[]"]').value = ''; // Reset quantity input
                newItem.querySelector('.harga_total_text').textContent = ''; // Reset price text
                newItem.querySelector('.harga_total_input').value = ''; // Reset price hidden input

                container.appendChild(newItem);
                updateTotal(); // Update total when new item is added
                checkRemoveButton(); // Check the remove button status
            }
        });



        // Remove item row dynamically
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                if (document.querySelectorAll('#items-container .row').length > 1) {
                    e.target.closest('.row').remove();
                    updateTotal(); // update total when item is removed
                    checkRemoveButton(); // check the remove button status
                }
            }
        });

        // Auto-fill price based on selected product
        document.addEventListener('change', function(e) {
            if (e.target.tagName === 'SELECT' && e.target.name === 'barang_id[]') {
                let harga = e.target.options[e.target.selectedIndex].dataset.harga;
                let row = e.target.closest('.row');
                row.querySelector('.harga_total_text').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(harga);
                row.querySelector('.harga_total_input').value = harga;
                updateTotal(); // update total when product is selected
            }

            if (e.target.name === 'jumlah[]') {
                updateTotal(); // update total when quantity is changed
            }
        });

        // Update total harga
        function updateTotal() {
            let totalPrice = 0;
            document.querySelectorAll('#items-container .row').forEach(function(row) {
                let harga = parseInt(row.querySelector('.harga_total_input').value) || 0;
                let jumlah = parseInt(row.querySelector('input[name="jumlah[]"]').value) || 0;
                totalPrice += harga * jumlah;
            });
            document.getElementById('total-price').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalPrice);
        }

        // Disable remove button if there's only one row
        function checkRemoveButton() {
            let removeButtons = document.querySelectorAll('.remove-item');
            if (removeButtons.length === 1) {
                removeButtons[0].disabled = true;
            } else {
                removeButtons.forEach(button => {
                    button.disabled = false;
                });
            }
        }

        // Initial check on page load
        checkRemoveButton();
    </script>


    <script src="../assets/script.js"></script>
</body>

</html>


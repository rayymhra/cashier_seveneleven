<?php
include "../koneksi.php";
session_start(); //simpen informasi siapa yg lagi login

// cek apakah pengguna sudah login dan apakah perannya adalah kasir
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Kasir') {
    header("Location: ../login.php");
    exit;
}

//akan terexecute jika tombol complete transaksi ditekan
if (isset($_POST['submit'])) {
    $tanggal = date('Y-m-d'); //ngambil tgl saat ini
    $user_id = $_SESSION['user_id']; //ngambil id yg lagi login
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
    $queryTransaksi = "INSERT INTO transaksi(tanggal, user_id, harga_total, bayar, kembalian) 
                       VALUES('$tanggal', '$user_id', '$total_harga', '$bayar', '$kembalian')";
    mysqli_query($conn, $queryTransaksi);

    $transaksi_id = mysqli_insert_id($conn); //ngambil id transaksi

    // simpen detail transaksi
    // setiap barang yang dibeli, kita memasukkan detailnya (seperti jumlah dan harga) ke dalam tabel detail_transaksi.
    foreach ($_POST['barang_id'] as $key => $barang_id) {
        $jumlah = $_POST['jumlah'][$key];
        $harga_per_barang = $_POST['harga_total'][$key];
        $queryDetail = "INSERT INTO detail_transaksi(transaksi_id, barang_id, jumlah, harga_total) 
                        VALUES('$transaksi_id', '$barang_id', '$jumlah', '$harga_per_barang')";
        mysqli_query($conn, $queryDetail);


        // Setelah transaksi, kita mengurangi stok barang yang terjual di tabel barang
        $queryUpdateStock = "UPDATE barang SET stok = stok - '$jumlah' WHERE id = '$barang_id'";
        mysqli_query($conn, $queryUpdateStock);
    }

    echo "<script>alert('Transaction successfully recorded!');window.location='kelola_transaksi.php';</script>";
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
</head>

<body>

    <div class="container">
        <h3>Kelola Transaksi</h3>
        <form action="" method="post">
            <div id="items-container">
                <div class="row mb-3">
                    <div class="mb-3">
                        <label for="barang_id">Product</label>
                        <select name="barang_id[]" class="form-select" required>
                            <option value="" disabled selected>Select Product</option>
                            <?php
                            $sqlBarang = $conn->query("SELECT id, nama, harga FROM barang WHERE stok > 0");
                            while ($row = $sqlBarang->fetch_assoc()) { ?>
                                <option value="<?php echo $row['id']; ?>" data-harga="<?php echo $row['harga']; ?>">
                                    <?php echo $row['nama'] . " (Rp " . number_format($row['harga'], 0, ',', '.') . ")"; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah">Quantity</label>
                        <input type="number" name="jumlah[]" class="form-control" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="harga_total">Price (Rp)</label>
                        <input type="number" name="harga_total[]" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <button type="button" class="btn btn-success mt-4 remove-item">Remove</button>
                    </div>
                </div>
            </div>

            <button type="button" id="add-item" class="btn btn-success mb-3">Add Item</button>

            <div class="mb-3">
                <label for="bayar">Amount Paid (Rp)</label>
                <input type="number" name="bayar" class="form-control" required>
            </div>

            <button type="submit" name="submit" class="btn btn-success">Complete Transaction</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add new item row dynamically
        document.getElementById('add-item').addEventListener('click', function() {
            let container = document.getElementById('items-container');
            let newItem = container.children[0].cloneNode(true);
            newItem.querySelector('select').value = '';
            newItem.querySelector('input[name="jumlah[]"]').value = '';
            newItem.querySelector('input[name="harga_total[]"]').value = '';
            container.appendChild(newItem);
        });

        // Remove item row dynamically
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.row').remove();
            }
        });

        // Auto-fill price based on selected product
        document.addEventListener('change', function(e) {
            if (e.target.tagName === 'SELECT' && e.target.name === 'barang_id[]') {
                let harga = e.target.options[e.target.selectedIndex].dataset.harga;
                e.target.closest('.row').querySelector('input[name="harga_total[]"]').value = harga;
            }
        });
    </script>

    <script src="../assets/script.js"></script>
</body>

</html>
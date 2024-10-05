<?php
include "../koneksi.php";
$successMessage = '';
$errorMessage = '';



// cek apakah ada id
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // fetch data barang berdasarkan id
    $query = mysqli_query($conn, "SELECT * FROM barang WHERE id = $id");
    if (mysqli_num_rows($query) > 0) {
        $barang = mysqli_fetch_assoc($query); // ngambil data barang
    } else {
        $errorMessage = "Product not found!";
    }
}


$sql_barang = $conn->query("SELECT * FROM barang WHERE id = '$id'");
$row_barang = mysqli_fetch_assoc($sql_barang);
$sql_supplier = $conn->query("SELECT id, nama FROM supplier");


if (isset($_POST['submit'])) {
    $name = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $supplier_id = $_POST['supplier_id'];

    // Execute the update query
    if (mysqli_query($conn, "UPDATE barang SET nama = '$name', harga='$harga', stok='$stok', supplier_id ='$supplier_id' WHERE id = '$id'")) {
        $successMessage = "User updated successfully!";
        header("Location: dashboard_gudang.php?page=kelola_barang&success=1");
        exit;
    } else {
        $errorMessage = "Error: " . mysqli_error($conn);
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/style.css">
    <!-- sweetalert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- DataTables CSS for Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
</head>

<body>
    <div class="container">
        <h3>Kelola Barang</h3>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo $row_barang['id']; ?>">
            <div class="mb-3">
                <label for="" class="input-group">Product's Name</label>
                <input type="text" name="nama" class="form-control" value="<?php echo $row_barang['nama'] ?>">
            </div>
            <div class="mb-3">
                <label for="">Price</label>
                <input type="text" name="harga" class="form-control" value="<?php echo $row_barang['harga']?>">
            </div>
            <div class="mb-3">
                <label for="">Stock</label>
                <input type="text" name="stok" class="form-control" value="<?php echo $row_barang['stok']?>">
            </div>
            <div class="mb-3">
                <label for="">Supplier</label>
                <select class="form-control" name="supplier_id">
                    <?php while ($row_supplier = mysqli_fetch_assoc($sql_supplier)): ?>
                        <option value="<?php echo $row_supplier['id']; ?>"
                            <?php echo $row_supplier['id'] == $row_barang['supplier_id'] ? 'selected' : ''; ?>>
                            <?php echo $row_supplier['nama']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-success mt-3">Update User</button>

    </div>





    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../assets/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables JS for Bootstrap 5 -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        // Show SweetAlert after redirecting
        <?php if (isset($_GET['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'User created successfully!',
                confirmButtonText: 'Okeyyy'
            });
        <?php endif; ?>
    </script>

</body>

</html>
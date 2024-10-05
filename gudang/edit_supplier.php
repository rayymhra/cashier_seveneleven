<?php
include "../koneksi.php";
$successMessage = '';
$errorMessage = '';



// cek apakah ada id
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // fetch data supplier berdasarkan id
    $query = mysqli_query($conn, "SELECT * FROM supplier WHERE id = $id");
    if (mysqli_num_rows($query) > 0) {
        $supplier = mysqli_fetch_assoc($query); // ngambil data supplier
    } else {
        $errorMessage = "Supplier not found!";
    }
}


if (isset($_POST['submit'])) {
    $name = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $contact = $_POST['contact'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];


    // Execute the update query
    if (mysqli_query($conn, "UPDATE supplier SET nama = '$name', alamat='$alamat', contact='$contact', telepon ='$telepon', email='$email' WHERE id = '$id'")) {
        $successMessage = "User updated successfully!";
        header("Location: dashboard_gudang.php?page=kelola_supplier&success=1");
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
        <h3>Edit Supplier</h3>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo $supplier['id']; ?>">
            <div class="mb-3">
                <label for="">Supplier's Name</label>
                <input type="text" name="nama" class="form-control" value="<?php echo $supplier['nama']; ?>">
            </div>
            <div class="mb-3">
                <label for="">Address</label>
                <input type="text" name="alamat" class="form-control" value="<?php echo $supplier['alamat']; ?>">
            </div>
            <div class="mb-3">
                <label for="">Contact</label>
                <input type="text" name="contact" class="form-control" value="<?php echo $supplier['contact']; ?>">
            </div>
            <div class="mb-3">
                <label for="">Telephone</label>
                <input type="text" name="telepon" class="form-control" value="<?php echo $supplier['telepon']; ?>">
            </div>
            <div class="mb-3">
                <label for="">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $supplier['email']; ?>">
            </div>

            <button type="submit" name="submit" class="btn btn-success">Update Supplier</button>

        </form>

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
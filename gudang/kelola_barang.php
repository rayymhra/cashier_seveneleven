<?php
include "../koneksi.php";

if (isset($_POST['submit'])) {
  $nama = $_POST['nama'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];
  $supplier_id = $_POST['supplier_id'];

  mysqli_query($conn, "INSERT INTO barang(nama, harga, stok, supplier_id) VALUES('$nama', '$harga', '$stok', '$supplier_id')");
}

$sqlSupplier = $conn->query("SELECT id, nama FROM supplier");
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Seven Eleven Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- bootstrap icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
  <div class="container">
    <h3>Kelola Barang</h3>
    <form action="" method="post">
      <div class="mb-3">
        <label for="" class="input-group">Product's Name</label>
        <input type="text" name="nama" class="form-control">
      </div>
      <div class="mb-3">
        <label for="">Price</label>
        <input type="text" name="harga" class="form-control">
      </div>
      <div class="mb-3">
        <label for="">Stock</label>
        <input type="text" name="stok" class="form-control">
      </div>
      <div class="mb-3">
        <label for="">Supplier</label>
        <select name="supplier_id" id="" class="form-select">
          <option value="" disabled selected>Pilih Supplier</option>

          <?php
          while ($row = $sqlSupplier->fetch_assoc()) { ?>

            <option value="<?php echo $row['id']; ?>">
              <?php echo $row['nama'] ?>
            </option>

          <?php } ?>
        </select>
      </div>


      <button type="submit" name="submit" class="btn btn-success">Add Product</button>
    </form>
  </div>



  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <!-- Font Awesome JS -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>

  <script src="../assets/script.js"></script>
</body>

</html>
<?php
include "../koneksi.php";

if (isset($_POST['submit'])){
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $contact = $_POST['contact'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];

    mysqli_query($conn, "INSERT INTO supplier(nama, alamat, contact, telepon, email) VALUES('$nama', '$alamat', '$contact', '$telepon', '$email')" );
}
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
    <h3>Kelola Supplier</h3>
    <form action="" method="post">
      <label for="">Supplier's Name</label>
      <input type="text" name="nama">
      <label for="">Address</label>
      <input type="text" name="alamat">
      <label for="">Contact</label>
      <input type="text" name="contact">
      <label for="">Telephone</label>
      <input type="text" name="telepon">
      <label for="">Email</label>
      <input type="text" name="email">

      <button type="submit" name="submit">Add Supplier</button>
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
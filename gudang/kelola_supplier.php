<?php
include "../koneksi.php";

if (isset($_POST['submit'])) {
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $contact = $_POST['contact'];
  $telepon = $_POST['telepon'];
  $email = $_POST['email'];

  if (mysqli_query($conn, "INSERT INTO supplier(nama, alamat, contact, telepon, email) VALUES('$nama', '$alamat', '$contact', '$telepon', '$email')")) {
    $successMessage = "Supplier added successfully!";
        header("Location: dashboard_gudang.php?page=kelola_supplier&success=1"); //redirect to prevent form resubmission, then sweetalert
        exit;
  }
}

$suppliers = mysqli_query($conn, "SELECT * FROM supplier");
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
   <!-- sweetalert -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!-- DataTables CSS for Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="../assets/style.css">
  <!-- DataTables JS for Bootstrap 5 -->
  <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script></body>
</head>

<body>
  <div class="container">
  <div class="img-register mb-4">
            <img src="../img/logo text.png" alt="">
        </div>
    <h3>Kelola Supplier</h3>
    <form action="" method="post">
      <div class="mb-3">
        <label for="">Supplier's Name</label>
        <input type="text" name="nama" class="form-control">
      </div>
      <div class="mb-3">
        <label for="">Address</label>
        <input type="text" name="alamat" class="form-control">
      </div>
      <div class="mb-3">
        <label for="">Contact</label>
        <input type="text" name="contact" class="form-control">
      </div>
      <div class="mb-3">
        <label for="">Telephone</label>
        <input type="text" name="telepon" class="form-control">
      </div>
      <div class="mb-3">
        <label for="">Email</label>
        <input type="email" name="email" class="form-control">
      </div>

      <button type="submit" name="submit" class="btn btn-success">Add Supplier</button>

    </form>

    <!-- datatable -->
    <div class="mt-5">
      <table id="supplierTable" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Supplier's Name</th>
            <th>Address</th>
            <th>Contact</th>
            <th>Telephone</th>
            <th>Email</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (mysqli_num_rows($suppliers) > 0) {
            $i = 1;
            while ($row = mysqli_fetch_assoc($suppliers)) {
              echo "<tr>
                                <td>" . $i++ . "</td>
                                <td>" . $row['nama'] . "</td>
                                <td>" . $row['alamat'] . "</td>
                                <td>" . $row['contact'] . "</td>
                                <td>" . $row['telepon'] . "</td>
                                <td>" . $row['email'] . "</td>
                                <td>
                            <a href='dashboard_gudang.php?page=edit_supplier&id=" . $row['id'] . "' class='btn btn-success mb-1'>Edit</a>
                            <button class='btn btn-success' onclick='confirmDelete(" . $row['id'] . ")'>Delete</button>
                                </td>
                            </tr>";
            }
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>


  <!-- jquery is needed for datatable dan harus ada di atas -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <!-- Font Awesome JS -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
   <!-- DataTables JS for Bootstrap 5 -->
   <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../assets/script.js"></script>

  <script>
    // Initialize DataTables with Bootstrap 5 styling
    $(document).ready(function() {
      $('#supplierTable').DataTable({
        "pagingType": "simple_numbers", // Use simple pagination
        "lengthMenu": [5, 10, 25, 50], // Options for showing records
        "pageLength": 5, // Default number of records shown
      });
    });

    // Show SweetAlert after redirecting
    <?php if (isset($_GET['success'])): ?>
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Supplier created successfully!',
        confirmButtonText: 'Okeyyy'
      });
    <?php endif; ?>
  </script>

  <script>
    function confirmDelete(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "delete_supplier.php?id=" + id; // redirect ke delete.php
        }
      })
    }

    // sweetalert
    <?php if (isset($_GET['delete']) && $_GET['delete'] == 'success'): ?>
      Swal.fire({
        icon: 'success',
        title: 'Deleted!',
        text: 'Supplier has been deleted successfully.'
      });
    <?php endif; ?>

    // Show SweetAlert after redirecting
    <?php if (isset($_GET['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Supplier created successfully!',
                confirmButtonText: 'Okeyyy'
            });
        <?php endif; ?>
  </script>
</body>

</html>
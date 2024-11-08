<?php
include "../koneksi.php";

// select kasir yg shift nya lagi open
$sql_cashier_shift = "SELECT cashier_id, start_time, opening_balance FROM shifts WHERE is_open = 1";
$cashier_shift = mysqli_query($conn, $sql_cashier_shift);
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
</head>

<body>

  <div class="sidebar">
    <div class="sidebar-page">
      <a href="dashboard_kasir.php" class="sidebar-img-link"><img src="../img/logo.png" alt="" class="sidebar-img"></a>
    
    <a href="dashboard_kasir.php?page=kelola_transaksi"><i class="bi bi-person-fill me-2"></i>Kelola Transaksi</a>
    </div>
    

    <div class="logout">
      <a href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
    </div>
  </div>

  <div class="content">
    <?php
    if (isset($_GET['page'])) {
      $page = $_GET['page'];

      // Dynamically include PHP content based on the page parameter
      switch ($page) {
        case 'kelola_transaksi':
          include 'kelola_transaksi.php';
          break;
        // default:
        //   echo "<h2>Welcome to the Dashboard</h2>";
        //   echo "<p>Select an option from the sidebar to manage users, products, or suppliers.</p>";
      }
    } else {
      ?>
      <h1>Dashboard Kasir</h1>
      <div class="container">
        <div class="row">
          <?php foreach($cashier_shift as $cs)?>
          <div class="col-6">
            <div class="card">
              <div class="card-body">
                <div class="h5">Shift</div>
                <div class="h6">Open</div>
              </div>
            </div>
          </div>
          <div class="col-6"></div>
        </div>
      </div>
      
      
      <?php
    }
    ?>
  </div>


  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <!-- Font Awesome JS -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../assets/script.js"></script>
</body>

</html>
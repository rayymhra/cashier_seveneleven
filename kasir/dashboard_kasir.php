<?php
include "../koneksi.php";
session_start();  // Keep this as the first call to start the session

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit;
}

// Ensure $user_id is properly set
$user_id = $_SESSION['user_id'];

// Query for the active shift
$query = "SELECT * FROM shifts WHERE kasir_id = $user_id AND buka = 1";
$result = mysqli_query($conn, $query);

if (!$result) {
  die("SQL Error: " . mysqli_error($conn)); // Output SQL error for debugging
}

$active_shift = mysqli_fetch_assoc($result);

if ($active_shift) {
  // echo "<h3>Active Shift</h3>";
  // echo "<p>Start Time: " . $active_shift['waktu_buka'] . "</p>";
  // echo "<p>Opening Balance: " . $active_shift['balance_buka'] . "</p>";
} else {
  echo "<p>No active shift found.</p>";
}

$query_barang = "SELECT COUNT(*) AS jum_barang
                  FROM barang";
$result_barang = mysqli_query($conn, $query_barang);
$barang = mysqli_fetch_assoc($result_barang)['jum_barang'] ?? 0;

$query_transaksi = "SELECT COUNT(*) AS jum_transaksi
                  FROM transaksi";
$result_transaksi = mysqli_query($conn, $query_transaksi);
$transaksi = mysqli_fetch_assoc($result_transaksi)['jum_transaksi'] ?? 0;


$query_table = "SELECT t.id, t.tanggal, t.user_id, t.harga_total, t.bayar, t.kembalian, t.shift_id 
          FROM transaksi t 
          ORDER BY t.tanggal DESC
          LIMIT 5";
$transactions = mysqli_query($conn, $query_table);



// chart
// $sql_transaksi_chart = "SELECT DATE(tanggal) as date, COUNT(*) as penjualan FROM transaksi GROUP BY DATE(tanggal)";
// $result_transaksi_chart = mysqli_query($conn, $sql_transaksi_chart);
// $data = [];
// while ($row = $result_transaksi_chart->fetch_assoc()){
//   array_push($data, $row);
// }
// echo json_encode($data);

if (isset($_GET['chart_data'])) {
  $type = $_GET['type'] ?? 'days';

  if ($type === 'months') {
    // monthly
    $sql_transaksi_chart = "
    SELECT 
    date_series.date AS date, 
    COALESCE(transaksi.penjualan, 0) AS penjualan 
FROM (
    SELECT DATE_FORMAT(DATE_SUB(NOW(), INTERVAL n MONTH), '%Y-%m') AS date
    FROM (
        SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
        UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
        UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14
        UNION ALL SELECT 15 UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19
        UNION ALL SELECT 20 UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 UNION ALL SELECT 24
        UNION ALL SELECT 25 UNION ALL SELECT 26 UNION ALL SELECT 27 UNION ALL SELECT 28 UNION ALL SELECT 29
    ) AS months
) AS date_series
LEFT JOIN (
    SELECT 
        DATE_FORMAT(MIN(tanggal), '%Y-%m') AS month, 
        COUNT(*) AS penjualan
    FROM transaksi 
    WHERE tanggal >= DATE_SUB(NOW(), INTERVAL 30 MONTH) 
    GROUP BY YEAR(tanggal), MONTH(tanggal)
) AS transaksi 
ON transaksi.month = date_series.date
ORDER BY date_series.date;

";
  } elseif ($type === 'days') {
    // daily
    $sql_transaksi_chart = "
    SELECT date_series.date AS date, 
           COALESCE(COUNT(transaksi.tanggal), 0) AS penjualan 
    FROM (
        SELECT CURDATE() - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY AS date
        FROM (SELECT 0 a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
              UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
        CROSS JOIN (SELECT 0 a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
                    UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
        CROSS JOIN (SELECT 0 a UNION ALL SELECT 1 UNION ALL SELECT 2) c
        WHERE CURDATE() - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY >= DATE_SUB(CURDATE(), INTERVAL 29 DAY)
    ) date_series
    LEFT JOIN transaksi ON DATE(transaksi.tanggal) = date_series.date
    GROUP BY date_series.date
    ORDER BY date_series.date;
";
  }

  $result_transaksi_chart = mysqli_query($conn, $sql_transaksi_chart);
  $data = [];
  while ($row = $result_transaksi_chart->fetch_assoc()) {
    $data[] = $row;
  }

  // Set appropriate headers for JSON response
  header('Content-Type: application/json');
  echo json_encode($data);
  exit;
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
  <!-- sweetalert -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <style>
    .details-row {
      display: none;
    }

    .toggle-details {
      cursor: pointer;
    }
  </style>
</head>

<body>

  <div class="sidebar">
    <div class="sidebar-page">
      <a href="dashboard_kasir.php" class="sidebar-img-link"><img src="../img/logo.png" alt="" class="sidebar-img"></a>

      <a href="dashboard_kasir.php?page=kelola_transaksi"><i class="bi bi-person-fill me-2"></i>Kelola Transaksi</a>
      <a href="dashboard_kasir.php?page=transaction_history"><i class="bi bi-clock-history me-2"></i>Transaction History</a>
      <!-- <a href="dashboard_kasir.php?page=kelola_shift"><i class="bi bi-clock-history me-2"></i>Kelola Shift</a> -->
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
        case 'transaction_history':
          include 'transaction_history.php';
          break;
          // default:
          //   echo "<h2>Welcome to the Dashboard</h2>";
          //   echo "<p>Select an option from the sidebar to manage users, products, or suppliers.</p>";
      }
    } else {
    ?>
      <h1 class="ms-3">Dashboard Kasir</h1>
      <div class="container dashboard-kasir">
        <div class="row">
          <div class="col-4">
            <?php
            $kasir_id = $_SESSION['user_id'];

            // Check shift status
            $query = "SELECT * FROM shifts WHERE kasir_id = '$kasir_id' AND buka = 1";
            $result = mysqli_query($conn, $query);
            $active_shift = mysqli_fetch_assoc($result);

            if ($active_shift) {
              // Shift is open
              // echo "<p>Shift is open. Balance: {$active_shift['balance_buka']}</p>";
            ?>
              <div class="card buka_shift-dashboard">
                <div class="card-body">
                  <!-- <p>Shift is open</p> -->
                  <h5 class="text-center shift-is-open">Shift is open</h5>
                  <h6>Balance: <?= number_format($active_shift['balance_buka'], 0, ',', '.') ?></h6>
                  <a href="../shifts/shift_close.php" class="btn btn-success mt-3">Close Shift</a>
                </div>
              </div>

              <!-- echo "<p>Shift is open. Balance: Rp " . number_format($active_shift['balance_buka'], 0, ',', '.') . "</p>";
          echo '<a href="../shifts/shift_close.php">Close Shift</a>'; -->
            <?php } else {
              // Shift is closed
              echo '<a href="../shifts/shift_open.php">Open Shift</a>';
            }
            ?>
          </div>
          <div class="col-4">
            <div class="card mt-3">
              <div class="card-header">
                <div class="card-header">Jumlah Barang</div>
              </div>
              <div class="card-body">
                <h5 class="jumlah-card-dashboard"><?= $barang ?></h5>
              </div>
            </div>
          </div>

          <div class="col-4">
            <div class="card mt-3">
              <div class="card-header">
                <div class="card-header">Jumlah Transaksi</div>
              </div>
              <div class="card-body">
                <h5 class="jumlah-card-dashboard"><?= $transaksi ?></h5>
              </div>
            </div>
          </div>
        </div>



        <!-- chart keuntungan -->
        <div class="card mt-5">
          <h3 class="mt-3 text-center">Grafik Penjualan 30 Hari</h3>
          <div class="card-body">
            <canvas id="chartDays"></canvas>
          </div>
        </div>

        <div class="card mt-5">
          <h3 class="mt-3 text-center">Grafik Penjualan 30 Bulan</h3>
          <div class="card-body">
            <canvas id="chartMonths"></canvas>
          </div>
        </div>


        <!-- 5 transaksi terakhir -->
        <div class="card mt-4">
          <div class="card-body">
            <h3 class="text-center mb-4 mt-2">5 Transaksi Terakhir</h3>
            <table class="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Tanggal</th>
                  <!-- <th>Kasir</th> -->
                  <th>Total</th>
                  <th>Bayar</th>
                  <th>Kembalian</th>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                while ($transaction = mysqli_fetch_assoc($transactions)): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $transaction['tanggal'] ?></td>
                    <!-- <td><?= $transaction['user_id'] ?></td> -->
                    <td>Rp. <?= number_format($transaction['harga_total'], 0, ',', '.') ?></td>
                    <td>Rp. <?= number_format($transaction['bayar'], 0, ',', '.') ?></td>
                    <td>Rp. <?= number_format($transaction['kembalian'], 0, ',', '.') ?></td>
                    <td>
                      <span class="badge bg-success toggle-details" data-id="<?= $transaction['id'] ?>">View</span>
                    </td>
                  </tr>
                  <tr class="details-row" id="details-<?= $transaction['id'] ?>">
                    <td colspan="7">
                      <strong>Transaction Details:</strong>
                      <ul>
                        <?php
                        $details_query = "
                                                        SELECT dt.barang_id, dt.jumlah, dt.harga_total, b.nama AS barang_nama 
                                                        FROM detail_transaksi dt 
                                                        JOIN barang b ON dt.barang_id = b.id 
                                                        WHERE dt.transaksi_id = " . $transaction['id'];
                        $details_result = mysqli_query($conn, $details_query);
                        while ($detail = mysqli_fetch_assoc($details_result)): ?>
                          <li>
                            Barang ID: <?= $detail['barang_id'] ?><br>
                            Produk: <?= $detail['barang_nama'] ?><br>
                            Jumlah: <?= $detail['jumlah'] ?><br>
                            Total: Rp <?= number_format($detail['harga_total'], 0, ',', '.') ?>
                          </li>
                        <?php endwhile; ?>
                      </ul>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>


      </div> <!-- penutup container-->
  </div>

  <!-- Modal -->
  <div class="modal fade" id="validateBalanceModal" tabindex="-1" aria-labelledby="validateBalanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="validateBalanceModalLabel">Validate Shift Balance</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p><strong>Opening Balance:</strong> <span id="openingBalance"></span></p>
          <p><strong>Total Transactions:</strong> <span id="totalTransactions"></span></p>
          <p><strong>Expected Closing Balance:</strong> <span id="expectedBalance"></span></p>
          <form id="validateBalanceForm">
            <div class="mb-3">
              <label for="closingBalance" class="form-label">Actual Closing Balance</label>
              <input type="number" class="form-control" id="closingBalance" name="closingBalance" required>
            </div>
            <div class="alert alert-danger d-none" id="balanceError">The balance is not matching!</div>
            <button type="submit" class="btn btn-primary">Validate</button>
          </form>
        </div>
      </div>
    </div>
  </div>


<?php
    }
?>
</div>

<!-- chart js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- Font Awesome JS -->
<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../assets/script.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const modal = new bootstrap.Modal(document.getElementById("validateBalanceModal"));
    const openingBalanceEl = document.getElementById("openingBalance");
    const totalTransactionsEl = document.getElementById("totalTransactions");
    const expectedBalanceEl = document.getElementById("expectedBalance");
    const balanceError = document.getElementById("balanceError");
    const validateBalanceForm = document.getElementById("validateBalanceForm");

    // Open the modal when closing shift
    document.getElementById("closeShiftButton").addEventListener("click", function() {
      fetch("shift_close.php")
        .then(response => response.json())
        .then(data => {
          openingBalanceEl.textContent = data.opening_balance;
          totalTransactionsEl.textContent = data.total_transactions;
          expectedBalanceEl.textContent = data.expected_balance;
          modal.show();
        });
    });

    // Handle form submission
    validateBalanceForm.addEventListener("submit", function(e) {
      e.preventDefault();
      const actualClosingBalance = parseFloat(document.getElementById("closingBalance").value);
      const expectedBalance = parseFloat(expectedBalanceEl.textContent);

      if (actualClosingBalance !== expectedBalance) {
        balanceError.classList.remove("d-none");
      } else {
        balanceError.classList.add("d-none");
        // Proceed to close the shift
        fetch("shift_close.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({
            closingBalance: actualClosingBalance
          })
        }).then(response => {
          if (response.ok) {
            modal.hide();
            alert("Shift closed successfully!");
            location.reload();
          } else {
            alert("Failed to close shift. Please try again.");
          }
        });
      }
    });
  });
</script>

<!-- 5 transaksi terakhir -->
<script>
  document.querySelectorAll('.toggle-details').forEach(button => {
    button.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const detailsRow = document.getElementById('details-' + id);
      if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
        detailsRow.style.display = 'table-row';
      } else {
        detailsRow.style.display = 'none';
      }
    });
  });
</script>


<!-- chart -->
<script src="chart.js">
</script>
</body>



</html>
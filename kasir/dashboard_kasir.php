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
  echo "<h3>Active Shift</h3>";
  echo "<p>Start Time: " . $active_shift['waktu_buka'] . "</p>";
  echo "<p>Opening Balance: " . $active_shift['balance_buka'] . "</p>";
} else {
  echo "<p>No active shift found.</p>";
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
</head>

<body>

  <div class="sidebar">
    <div class="sidebar-page">
      <a href="dashboard_kasir.php" class="sidebar-img-link"><img src="../img/logo.png" alt="" class="sidebar-img"></a>

      <a href="dashboard_kasir.php?page=kelola_transaksi"><i class="bi bi-person-fill me-2"></i>Kelola Transaksi</a>
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
          // default:
          //   echo "<h2>Welcome to the Dashboard</h2>";
          //   echo "<p>Select an option from the sidebar to manage users, products, or suppliers.</p>";
      }
    } else {
    ?>
      <h1>Dashboard Kasir</h1>
      <div class="container">
        <?php
        // Removed the extra session_start() call here.
        $kasir_id = $_SESSION['user_id'];

        // Check shift status
        $query = "SELECT * FROM shifts WHERE kasir_id = '$kasir_id' AND buka = 1";
        $result = mysqli_query($conn, $query);
        $active_shift = mysqli_fetch_assoc($result);

        if ($active_shift) {
          // Shift is open
          echo "<p>Shift is open. Balance: {$active_shift['balance_buka']}</p>";
          echo '<a href="../shifts/shift_close.php">Close Shift</a>';
        } else {
          // Shift is closed
          echo '<a href="shift_open.php">Open Shift</a>';
        }
        ?>
      </div>
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- Font Awesome JS -->
<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../assets/script.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const modal = new bootstrap.Modal(document.getElementById("validateBalanceModal"));
    const openingBalanceEl = document.getElementById("openingBalance");
    const totalTransactionsEl = document.getElementById("totalTransactions");
    const expectedBalanceEl = document.getElementById("expectedBalance");
    const balanceError = document.getElementById("balanceError");
    const validateBalanceForm = document.getElementById("validateBalanceForm");

    // Open the modal when closing shift
    document.getElementById("closeShiftButton").addEventListener("click", function () {
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
    validateBalanceForm.addEventListener("submit", function (e) {
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
</body>



</html>

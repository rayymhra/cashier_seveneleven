<?php
include "../koneksi.php";
session_start(); //simpen informasi siapa yg lagi login

//ambil id kasir yg login
$cashier_id = $_SESSION['user_id'];

// validasi apakah pengguna sudah login dan apakah perannya adalah kasir
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Kasir') {
    header("Location: ../login.php");
    exit;
} else if (isset($_POST["submit"])) {
    $startTime = date("Y-m-d H:i:s");
    // $endTime;
    $openingBalance = $_POST["opening_balance"];
    $is_open = 1;

    if(mysqli_query($conn, "INSERT INTO shifts(id, cashier_id, start_time, opening_balance, is_open) VALUES(NULL, '$cashier_id', '$startTime',  '$openingBalance','$is_open' )")) {
        header("Location: ../kasir/dashboard_kasir.php");
    }


}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/style.css">
    <!-- sweetalert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>

    <section class="form-shift">
        <h1 class="text-center mt-5">Open The Shift</h1>
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="" class="input-group">Opening Balance</label>
                            <input type="text" name="opening_balance" class="form-control" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-success">Start</button>
                    </form>
                </div>
            </div>

        </div>
    </section>





    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/script.js"></script>
</body>

</html>
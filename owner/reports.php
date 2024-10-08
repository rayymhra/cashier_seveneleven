<?php
include "../koneksi.php"; 

// kalo ada start date / end date maka akan ada di url, else null
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null; 
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;



$query = "SELECT b.nama as product_name, b.stok, SUM(td.jumlah) as total_sold, 
            SUM(td.jumlah * td.harga_total) as total_sales
          FROM barang b  -- tabel barang diberi nama b
          LEFT JOIN detail_transaksi td ON b.id = td.barang_id
          LEFT JOIN transaksi t ON td.transaksi_id = t.id";


if ($startDate && $endDate) {
    $query .= " WHERE t.transaction_date BETWEEN '$startDate' AND '$endDate'";
}

$query .= " GROUP BY p.id"; //mengelompokkan data berdasarkan ID produk
// untuk menghitung total terjual dan total penjualan per produk

// execute query, simpen hasilnya di variable
$result = mysqli_query($conn, $query);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add your custom styles -->
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container mt-5">
    <h3>Sales Report</h3>
    
    <!-- Filter form for date range -->
    <form method="get" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="date" name="start_date" class="form-control" placeholder="Start Date" value="<?php echo $startDate; ?>">
            </div>
            <div class="col-md-4">
                <input type="date" name="end_date" class="form-control" placeholder="End Date" value="<?php echo $endDate; ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <!-- Table to display sales data -->
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Product Name</th>
                <th>Stock</th>
                <th>Total Sold</th>
                <th>Total Sales</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                $i = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$i}</td>
                            <td>{$row['product_name']}</td>
                            <td>{$row['stok']}</td>
                            <td>" . ($row['total_sold'] ?? 0) . "</td>
                            <td>" . ($row['total_sales'] ? 'Rp ' . number_format($row['total_sales']) : 'Rp 0') . "</td>
                          </tr>";
                    $i++;
                }
            } else {
                echo "<tr><td colspan='5'>No data available</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include "../koneksi.php";

$search = isset($_GET['q']) ? $_GET['q'] : '';

$sql = "SELECT id, nama FROM barang WHERE nama LIKE '%$search%' AND stok > 0";
$result = mysqli_query($conn, $sql);

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = [
        "id" => $row['id'],
        "text" => $row['nama']
    ];
}

echo json_encode($products);

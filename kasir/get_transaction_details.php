<?php
include "../koneksi.php";

if (isset($_GET['id'])) {
    $transactionId = intval($_GET['id']);

    $details_query = "SELECT dt.barang_id, dt.jumlah, dt.harga_total, b.nama AS barang_nama 
                      FROM detail_transaksi dt 
                      JOIN barang b ON dt.barang_id = b.id 
                      WHERE dt.transaksi_id = $transactionId";
    $details_result = mysqli_query($conn, $details_query);

    echo '<div class="p-2 bg-light">';
    echo '<strong>Transaction Details:</strong><ul>';
    while ($detail = mysqli_fetch_assoc($details_result)) {
        echo "<li>Barang ID: {$detail['barang_id']} - Produk: {$detail['barang_nama']} - Jumlah: {$detail['jumlah']} - Total: Rp " . number_format($detail['harga_total'], 0, ',', '.') . "</li>";
    }
    echo '</ul></div>';
}

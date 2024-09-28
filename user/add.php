<?php
include "../koneksi.php";

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $telepon = $_POST['telepon'];
    $jabatan = $_POST['jabatan'];

    $sql = $conn->query("INSERT INTO user VALUES(NULL, '$nama', '$username', '$password', '$telepon', '$jabatan')");
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seven Eleven Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1>User</h1>

        <form action="" method="post">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
            <label>Password</label>
            <input type="text" name="password" class="form-control" required>
            <label>Telepon</label>
            <input type="text" name="telepon" class="form-control" required>
            <label>Jabatan</label>
            <select class="form-select" aria-label="Default select example">
                <option selected>Open this select menu</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
            <button class="btn btn-success">Tambah</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
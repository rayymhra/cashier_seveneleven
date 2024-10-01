<?php
include "../koneksi.php";
$successMessage = '';
$errorMessage = '';

// Check if user ID is provided in the query string
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch user data based on ID
    $query = mysqli_query($conn, "SELECT * FROM user WHERE id = $id");
    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query); // Get user data
    } else {
        $errorMessage = "User not found!";
    }
}

// Handle form submission for updating user data
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $telp = $_POST['telp'];
    $role = $_POST['role'];

    // If password is empty, don't update the password
    if (empty($password)) {
        $updateQuery = "UPDATE user SET nama='$name', username='$username', telepon='$telp', jabatan='$role' WHERE id=$id";
    } else {
        $enkripsi_password = password_hash($password, PASSWORD_DEFAULT);
        $updateQuery = "UPDATE user SET nama='$name', username='$username', password='$enkripsi_password', telepon='$telp', jabatan='$role' WHERE id=$id";
    }

    // Execute the update query
    if (mysqli_query($conn, $updateQuery)) {
        $successMessage = "User updated successfully!";
        header("Location: dashboard.php?page=kelola_user?success=1");
        exit;
    } else {
        $errorMessage = "Error: " . mysqli_error($conn);
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/style.css">
    <!-- sweetalert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- DataTables CSS for Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
</head>

<body>

<div class="container">
        <div class="img-register">
            <img src="../img/logo text.png" alt="">
        </div>
        <h4>Edit User</h4>
        <p>Edit user details below.</p>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <div class="register">
            <form action="" method="post">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="name" value="<?php echo $user['nama']; ?>" required>
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="username" value="<?php echo $user['username']; ?>" required>
                        <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password (Leave blank to keep the current password)</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    </div>
                    <div class="password-footer">
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" id="show-password">
                            <label class="form-check-label" for="show-password">
                                Show Password
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Telephone</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="telp" value="<?php echo $user['telepon']; ?>" required>
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select class="form-select" name="role" required>
                        <option disabled>Select Your Role</option>
                        <option value="Owner" <?php if ($user['jabatan'] == 'Owner') echo 'selected'; ?>>Owner</option>
                        <option value="Kasir" <?php if ($user['jabatan'] == 'Kasir') echo 'selected'; ?>>Kasir</option>
                        <option value="Gudang" <?php if ($user['jabatan'] == 'Gudang') echo 'selected'; ?>>Gudang</option>
                    </select>
                </div>

                <button type="submit" name="submit" class="btn btn-success mt-3">Update User</button>

            </form>
        </div>
    </div>
    




    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../assets/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables JS for Bootstrap 5 -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        // Show SweetAlert after redirecting
        <?php if (isset($_GET['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'User created successfully!',
                confirmButtonText: 'Okeyyy'
            });
        <?php endif; ?>
    </script>
    
</body>

</html>
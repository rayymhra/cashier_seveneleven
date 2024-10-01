<?php
include "../koneksi.php";
$successMessage = '';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $telp = $_POST['telp'];
    $role = $_POST['role'];

    $enkripsi_password = password_hash($password, PASSWORD_DEFAULT);

    if (mysqli_query($conn, "INSERT INTO user(nama, username, password, telepon, jabatan) VALUES ('$name', '$username', '$enkripsi_password', '$telp', '$role')")) {
        $successMessage = "User created successfully!";
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1"); //Redirect to the same page to prevent form resubmission, then sweetalert
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// ngambil data users
$users = mysqli_query($conn, "SELECT * FROM user");
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-2">

            </div>
            <div class="col-10">

            </div>
        </div>
    </div>
    <div class="container">
        <div class="img-register">
            <img src="../img/logo text.png" alt="">
        </div>
        <h4>Kelola Akun</h4>
        <p>Lorem ipsum dolor sit amet.</p>

        <div class="register">
            <form action="" method="post">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="name">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="username">
                        <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
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
                    <div class="input-group" name="telp">
                        <input type="text" class="form-control" name="telp">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select class="form-select" aria-label="Default select example" name="role">
                        <option selected disabled>Select Your Role</option>
                        <option value="Owner">Owner</option>
                        <option value="Kasir">Kasir</option>
                        <option value="Gudang">Gudang</option>
                    </select>
                </div>


                <button type="submit" name="submit" class="btn btn-success mt-3">Add User</button>

            </form>


        </div>

        <!-- Table displaying user data -->
        <table id="userTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Telephone</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($users) > 0) {
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($users)) {
                        echo "<tr>
                                <td>" . $i++ . "</td>
                                <td>" . $row['nama'] . "</td>
                                <td>" . $row['username'] . "</td>
                                <td>" . $row['telepon'] . "</td>
                                <td>" . $row['jabatan'] . "</td>
                            </tr>";
                    }
                }
                ?>
            </tbody>
        </table>

    </div>
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
        // Initialize DataTables with Bootstrap 5 styling
        $(document).ready(function() {
            $('#userTable').DataTable({
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
                text: 'User created successfully!',
                confirmButtonText: 'Okeyyy'
            });
        <?php endif; ?>
    </script>

</body>

</html>
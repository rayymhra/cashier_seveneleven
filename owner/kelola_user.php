<?php
include "../koneksi.php";
$successMessage = '';

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $telp = $_POST['telp'];
    $role = $_POST['role'];

    $enkripsi_password = password_hash($password, PASSWORD_DEFAULT);

    if (mysqli_query($conn, "INSERT INTO user(nama, username, password, telepon, jabatan) VALUES ('$name', '$username', '$enkripsi_password', '$telp', '$role')")){
        $successMessage = "User created successfully!";
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1"); //Redirect to the same page to prevent form resubmission, then sweetalert
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
    <div class="container shadow-lg rounded">
        <div class="row">
                <div class="col-7 login">
                <img src="../img/logo text.png" alt="">
                <h4>Kelola Akun</h4>
                <p>Lorem ipsum dolor sit amet.</p>

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
            <div class="col-5 contact rounded-end d-flex flex-column align-items-center justify-content-center text-center"><!-- flex column is arranges the children (image and heading) in a vertical column -->
                <img src="../img/undraw_tasting_re_3k5a.svg" alt="" class="w-100">
                <h5 class="mt-5 text-center">Login to get to our services</h5>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing.</p>
            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../assets/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
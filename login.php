<?php
include "koneksi.php";
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
    <link rel="stylesheet" href="assets/style.css">

</head>

<body>
    <div class="container shadow-lg rounded">
        <div class="row">
            <div class="col-7 login">
                <img src="img/logo text.png" alt="">
                <h4>Log in to your Account</h4>
                <p>Lorem ipsum dolor sit amet.</p>

                <form action="">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control">
                            <i class="bi bi-person" class="input-icon"></i>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <div class="input-group">
                            <input type="text" class="form-control">
                            <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        </div>
                        <div class="password-footer d-flex justify-content-between">
                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" id="show-password">
                                <label class="form-check-label" for="show-password">
                                    Show Password
                                </label>
                            </div>
                            <div class="mt-2">
                                <a href="" class="forget">Forgot Password?</a>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telephone</label>
                        <div class="input-group">
                            <input type="text" class="form-control">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" aria-label="Default select example">
                            <option selected disabled>Select Your Role</option>
                            <option value="1">Owner</option>
                            <option value="2">Kasir</option>
                            <option value="3">Gudang</option>
                        </select>
                    </div>


                    <button type="submit" class="btn btn-success mt-3">Login</button>

                </form>
            </div>
            <div class="col-5 contact rounded-end">
                sdfs
            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="assets/script.js"></script>
</body>

</html>
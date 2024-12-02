<?php
include_once __DIR__ . "/../config/koneksi.php";
session_start();
date_default_timezone_set('Asia/Jakarta');

if (isset($_SESSION["is_login"])) {
    if ($_SESSION['email'] === 'admin@acs.id') {
        header("Location: /home/services/services.php");
    } else {
        header("Location: /home/services/u/services.php");
    }
    exit();
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hash_password = hash('sha256', $password);

    $sql = "SELECT * FROM user WHERE email='$email' AND 
        password='$hash_password'";

    $result = $konek->query($sql);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $_SESSION["email"] = $data["email"];
        $_SESSION["is_login"] = true;

        $login_time = date('Y-m-d H:i:s');
        $log_session = "INSERT INTO session_logs (email, login_time) VALUES ('$data[email]', '$login_time')";
        $konek->query($log_session);        

        if ($_SESSION['email'] === 'admin@acs.id') {
            header('Location: /home/services/services.php');
            exit();
        } else {
            header('Location: /home/services/u/services.php');
            exit();
        }
    } else {
        $validError = "toastr.error('Password atau Email Salah')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="../assets/ic-dial-logo.png" sizes="32x64">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include_once __DIR__ . "/../config/bootstrap.php" ?>
    <style>
        <?php include_once __DIR__ . "/../styles/style.css" ?>
    </style>
</head>

<body style="background: #000; height: 100vh; margin: 0; position: relative;">
    <!-- Background Image -->
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url(../assets/dial.jpeg); background-size: cover; background-position: center; opacity: 0.4; z-index: -1;"></div>
    <!-- Overlay Layer -->
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.2); z-index: -1;"></div>

    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-3">
                    <div class="card shadow">
                        <div class="card-body">
                            <form action="login.php" class="d-grid gap-3" id="registrationForm" method="POST">
                                <div class="form-group">
                                    <img src="../assets/ic-dial-logo.png" alt="dial.png" class="mx-auto d-block w-50 mb-3">
                                    <label for="email">
                                        Email
                                    </label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="Email" required />
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" required />
                                </div>
                                <button type="submit" name="login" class="btn btn-primary">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "7000"
        };
        <?= $validError ?>
    </script>
</body>
</body>
</html>
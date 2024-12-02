<?php
session_start();
include_once __DIR__ . "/../../../config/koneksi.php";

if (!isset($_SESSION['email'])) {
    header('Location: /home/login.php');
    exit();
}

$query = mysqli_query($konek, "SELECT * FROM session_logs");

$email = $_SESSION['email'];

$sql = "SELECT name FROM user WHERE email = '$email'";
$result = $konek->query($sql);

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $_SESSION['name'] = $data['name'];
} else {
    $_SESSION['name'] = "Unknown";
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('location: /home/login.php');
    exit();
}

if (!isset($_SESSION['email'])) {
    session_destroy();
    header('Location: /home/login.php');
    exit();
}

$id = isset($_GET['id']) ? $_GET['id'] : null;
if ($id) {
    $query_get_data = mysqli_query($konek, "SELECT * FROM urls WHERE id=$id");
    $url_data = mysqli_fetch_assoc($query_get_data);
}

$grafana = "SELECT id, dash_name, url1, height FROM urls";
$result = $konek->query($grafana);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="/../../assets/dial.png" sizes="16x32">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <title>Server dashboard</title>
    <?php include_once __DIR__ . "/../../../config/bootstrap.php" ?>
    <?php include_once __DIR__ . "/../../../config/google-font.php" ?>
    <?php include_once __DIR__ . "/../../../config/font-awesome.php" ?>
    <style>
        <?php include_once __DIR__ . "../../../../styles/style.css" ?>
    </style>
</head>

<body style="background: #fff">
    <?php include_once __DIR__ . "/../../../assets/components/header-user.php" ?>
    <div class="heading">
        <h1>Welcome to dashboard, <span style="color: #05a2e4;">"<?= $_SESSION["name"] ?>"</span></h1>
        <hr>
    </div>
    <div class="container mt-5">
        <div class="row tabel">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-14 mb-4">
                        <div class="card" style="background: #05a2e4; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
                            <div class="card-header">
                                <h5 class="mb-2 d-inline text-light"><?= $row['dash_name'] ?></h5>
                                <a href="#" class="nav-link dropdown-toggle d-inline" data-bs-theme="light" data-bs-toggle="collapse" data-bs-target="#collapse<?= $row['id'] ?>" aria-expanded="false" aria-controls="collapse<?= $row['id'] ?>"></a>
                                <a onclick="deleteValid()" class="btn-close float-end btn-close btn-close-white" disable aria-label="Close"></a>
                                <a onclick="editValid()" class="modal-title fa-solid fa-pencil float-end btn" disable data-bs-toggle="modal" data-bs-target="#updateTableModal<?= $row['id'] ?>" style="color:#E5E7EB"></a>
                            </div>
                            <div class="collapse" id="collapse<?= $row['id'] ?>">
                                <div class="card-body" style="background: #E5E7EB;">
                                    <div class="row">
                                        <div>
                                            <iframe style="pointer-events: none;" src="<?= $row['url1'] ?>&kiosk" width="100%" height="<?= $row['height'] ?>" frameborder="0"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php include_once __DIR__ . "/../../../assets/components/footer.php" ?>
</body>
<script>
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        toastr.error("Cant't right-click in here")
    })
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F12' || e.key === 'Escape' ||
            (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J')) ||
            (e.ctrlKey && e.key === 'U')) {
            e.preventDefault();
            toastr.error("Can't open Developer Tools here")
        }
    })

    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    }

    function editValid(){
        toastr.error("Can't edit the table")
    }
    function deleteValid(){
        toastr.error("Can't delete the table")
    }
    function addValid(){
        toastr.error("Can't add the table")
    }
    function sessionValid(){
        toastr.error("Can't open the Session History")
    }
</script>

</html>
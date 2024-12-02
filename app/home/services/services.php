<?php
session_start();
include_once __DIR__ . "/../../config/koneksi.php";

$validError = '';

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
    header('location: ../login.php');
    exit();
}

if (!isset($_SESSION['email'])) {
    session_destroy();
    header('Location: /home/login.php');
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $konek->query("DELETE FROM urls WHERE id = $id");
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

$id = isset($_GET['id']) ? $_GET['id'] : null;
if ($id) {
    $query_get_data = mysqli_query($konek, "SELECT * FROM urls WHERE id=$id");
    $url_data = mysqli_fetch_assoc($query_get_data);
}

if (isset($_POST["submit"])) {
    $dash_name = $_POST["dash_name"];
    $url1 = $_POST["url1"];
    $height = $_POST["height"];

    if (!is_numeric($height) || $height < 250 || $height > 500) {
        echo "<script>alert('Height harus angka antara 250 hingga 500.');</script>";
    } else {
        $query = mysqli_query($konek, "UPDATE urls SET dash_name='$dash_name', url1='$url1', height='$height' WHERE id=$id");

        if ($query) {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $validError = "toastr.error('URL tidak valid atau tidak dapat diakses')";
        }
    }
}


function isValidUrl($url)
{
    $headers = @get_headers($url);
    return $headers && strpos($headers[0], '200') !== false;
}
if (isset($_POST['create-dashboard'])) {
    $dash = $_POST['dash_name'];
    $url1 = $_POST['url1'];
    $height = $_POST['height'];

    if (!is_numeric($height) || $height < 250 || $height > 500) {
        echo "<script>alert('Height harus angka antara 250 hingga 500.');</script>";
    } else {
        if (!isValidUrl($url1)) {
            $validError = "toastr.error('URL tidak valid atau tidak dapat diakses')";
        } else {
            $sql = "INSERT INTO urls (id, dash_name, url1, height) VALUES 
                    (NULL, '$dash', '$url1', '$height')";
            $konek->query($sql);

            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}

$grafana = "SELECT id, dash_name, url1, height FROM urls";
$result = $konek->query($grafana);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="../../assets/pac.png" sizes="16x32">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server dashboard</title>
    <?php include_once __DIR__ . "/../../config/bootstrap.php" ?>
    <?php include_once __DIR__ . "/../../config/google-font.php" ?>
    <?php include_once __DIR__ . "/../../config/font-awesome.php" ?>
    <style>
        <?php include_once __DIR__ . "../../../styles/style.css" ?>
    </style>
</head>

<body style="background: #fff">
    <?php include_once __DIR__ . "/../../assets/components/header.php" ?>
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
                                <a href="?delete=<?= $row['id'] ?>" class="btn-close float-end btn-close btn-close-white" aria-label="Close"></a>
                                <a class="modal-title fa-solid fa-pencil float-end btn" data-bs-toggle="modal" data-bs-target="#updateTableModal<?= $row['id'] ?>" style="color:#E5E7EB"></a>
                            </div>
                            <div class="collapse" id="collapse<?= $row['id'] ?>">
                                <div class="card-body" style="background: #E5E7EB;">
                                    <div class="row">
                                        <div>
                                            <iframe src="<?= $row['url1'] ?>&kiosk" width="100%" height="<?= $row['height'] ?>" frameborder="0"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="updateTableModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="updateTableModalLabel<?= $row['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: #05a2e4; color: #fff;">
                                    <h5 class="modal-title" id="updateTableModalLabel<?= $row['id'] ?>">Update Data</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="?id=<?= $row['id'] ?>" method="post">
                                        <div class="mb-3">
                                            <label for="dash_name" class="form-label">Dashboard Name</label>
                                            <input type="text" class="form-control" name="dash_name" value="<?= $row['dash_name'] ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="url1" class="form-label">URL 1</label>
                                            <input type="text" class="form-control" name="url1" value="<?= $row['url1'] ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="height" class="form-label">Height</label>
                                            <input type="number" class="form-control" name="height" value="<?= $row['height'] ?>" min="250" max="500" required>
                                        </div>
                                        <button type="submit" name="submit" class="btn btn-success">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="modal fade" id="addTableModal" tabindex="-1" aria-labelledby="addTableModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #05a2e4; color: #fff;">
                    <h5 class="modal-title" id="addTableModalLabel">Add New Table</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" method="post">
                        <div class="col-12">
                            <input class="form-control" name="dash_name"
                                type="text"
                                placeholder="Dashboard Name"
                                aria-label="input" required />
                        </div>
                        <div class="col-12">
                            <input class="form-control" name="url1"
                                type="text"
                                placeholder="Main Vizualization - URL"
                                aria-label="input" required />
                        </div>
                        <div class="col-12">
                            <input class="form-control" name="height"
                                type="number"
                                placeholder="Select height (min: 250 - max: 500)"
                                min="250"
                                max="500"
                                required />
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success mb-3"
                                name="create-dashboard" />
                            Add Table
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="sessionModal" tabindex="-1" aria-labelledby="sessionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #05a2e4; color: #fff;">
                    <h5 class="modal-title" id="sessionModalLabel">Session History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <section class="wrapper mx-2" style="max-height: 400px; overflow-y: auto;">
                        <table class="table">
                            <thead class="thead-dark" style="border-radius: 10px;">
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Login-Time</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php foreach ($query as $session) : ?>
                                    <tr>
                                        <td><?php echo $session['id'] ?></td>
                                        <td><?php echo $session['email'] ?></td>
                                        <td><?php echo $session['login_time'] ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . "/../../assets/components/footer.php" ?>
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
</html>
<?php
/* 
    Rebuild by Maulana Rizki from Frendy
    // Don't Remove Copyright

*/
session_start();
require '../../include/function.php';

if (!isset($_SESSION['username'])) {
    header('location:../../login');
    exit();
}

$username = $_SESSION['username'];
$queryUser = mysqli_query($konek, "SELECT * FROM user WHERE username = '$username'");
$dataUser = mysqli_fetch_assoc($queryUser);

if ($dataUser['level'] !== "Admin") {
    require '../../404.shtml';
    die();
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if (isset($_POST['tombol'])) {
    $penerima = addslashes(htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['penerima']))));

    if (empty($penerima)) {
      alert('gagal', 'Masih ada data yang kosong', 'reset-password');
    } else {
        $qPenerima = mysqli_query($konek, "SELECT * FROM user WHERE username = '$penerima'");
        if (mysqli_num_rows($qPenerima) === 1) {

          $pw = generateRandomString();

          $pw_hash = password_hash($pw, PASSWORD_DEFAULT);

          mysqli_query($konek, "UPDATE user SET password = '$pw_hash' WHERE username = '$penerima'");
          alert('berhasil', 'Password berhasil di reset : ' . $pw, 'reset-password');
        } else {
          alert('gagal', 'Username tidak ditemukan', 'reset-password');
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Reset Password</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="../../assets/css/style.css">
  <link rel="stylesheet" href="../../assets/css/components.css">
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      
      <?php require '../../include/admin-menu.php'; ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Reset Password</h1>
          </div>
          <div class="row justify-content-center">
            <div class="col-lg-6">
              <div class="card">
                <div class="card-header bg-primary">
                  <h4 class="text-white">Reset Password</h4>
                </div>
                <div class="card-body" style="overflow: auto;">
                  <?php if (isset($_COOKIE['gagal'])): ?>
                  <div class="alert alert-danger">
                      <?= $_COOKIE['gagal']; ?>
                  </div>
                  <?php endif ?>
                  <?php if (isset($_COOKIE['berhasil'])): ?>
                  <div class="alert alert-success">
                      <?= $_COOKIE['berhasil']; ?>
                  </div>
                  <?php endif ?>
                  <form class="form-horizontal" role="form" action="" method="POST">
                    <div class="form-group">
                        <label class="control-label">Username</label>
                        <input type="text" class="form-control" name="penerima" autocomplete="off" >
                    </div>

                    <div class="text-right">
                      <button class="btn btn-default" type="reset">Reset</button>
                      <button class="btn btn-primary" type="submit" name="tombol">Reset Password</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php require '../../include/footer.php'; ?>
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
  <script src="../../assets/js/stisla.js"></script>

  <!-- Template JS File -->
  <script src="../../assets/js/scripts.js"></script>
  <script src="../../assets/js/custom.js"></script>

  <!-- Page Specific JS File -->
  <script src="../../assets/js/page/index-0.js"></script>
  
  <script>
    $(document).ready( function () {
      $('#datatable').DataTable();
    });
  </script>
</body>
</html>
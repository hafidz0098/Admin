<?php 
session_start();
require '../include/function.php';

if (!isset($_SESSION['username'])) {
    header('location:../');
    exit();
}

$username = $_SESSION['username'];
$queryUser = mysqli_query($konek, "SELECT * FROM user WHERE username = '$username'");
$dataUser = mysqli_fetch_assoc($queryUser);

if ($dataUser['level'] !== "Admin") {
    require '../404.shtml';
    die();
}

function jumlah($qu) {
  global $konek;
  $q = mysqli_query($konek, $qu);
  return number_format(mysqli_num_rows($q),0,',','.');
}

function total($qu, $mi) {
  global $konek;
  $q = mysqli_query($konek, $qu);
  $a = 0;
  while ($f = mysqli_fetch_assoc($q)) {
    $a += $f[$mi];
  }
  return number_format($a,0,',','.');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Dashboard</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/components.css">
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      
      <?php require '../include/admin-menu.php'; ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Dashboard</h1>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="fa fa-2x text-white fa-users"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total Pengguna</h4>
                  </div>
                  <div class="card-body">
                    <?= jumlah("SELECT * FROM user"); ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total Saldo</h4>
                  </div>
                  <div class="card-body">
                    <?= total("SELECT * FROM user", "saldo"); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="fa fa-2x text-white fa-shopping-cart"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total Pembelian</h4>
                  </div>
                  <div class="card-body">
                    <?= jumlah("SELECT * FROM riwayat"); ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="fa fa-2x text-white fa-shopping-basket"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Jumlah Saldo</h4>
                  </div>
                  <div class="card-body">
                    <?= total("SELECT * FROM riwayat", 'harga'); ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="fa fa-2x text-white fa-shopping-basket"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Pembelian Hari Ini</h4>
                  </div>
                  <div class="card-body">
                    <?= total("SELECT * FROM riwayat WHERE tanggal = '$tanggal'", 'harga'); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php require '../include/footer.php'; ?>
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="../assets/js/stisla.js"></script>

  <!-- Template JS File -->
  <script src="../assets/js/scripts.js"></script>
  <script src="../assets/js/custom.js"></script>

  <!-- Page Specific JS File -->
  <script src="../assets/js/page/index-0.js"></script>
</body>
</html>

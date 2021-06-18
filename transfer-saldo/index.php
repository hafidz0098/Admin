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

if (isset($_POST['tombol'])) {
    $penerima = addslashes(htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['penerima']))));
    $jumlah = addslashes(htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['jumlah']))));

    if (empty($penerima) OR empty($jumlah)) {
      alert('gagal', 'Masih ada data yang kosong', 'transfer-saldo');
    } else if (is_numeric($jumlah)) {
        $qPenerima = mysqli_query($konek, "SELECT * FROM user WHERE username = '$penerima'");
        if (mysqli_num_rows($qPenerima) === 1) {

          $fPenerima = mysqli_fetch_assoc($qPenerima);
          $awal = $fPenerima['saldo'];
          $akhir = $fPenerima['saldo'] + $jumlah;

          mysqli_query($konek, "UPDATE user SET saldo = saldo+$jumlah WHERE username = '$penerima'");
          mysqli_query($konek, "INSERT INTO saldo (username, aksi, saldo_aktifity, tanggal, efek, saldo_awal, saldo_jadi) VALUES ('$penerima', 'Deposit via Admin $username','$jumlah','$tanggal $waktu','+ Saldo','$awal','$akhir')");
          alert('berhasil', 'Saldo berhasil di kirimkan ke ' . $penerima, 'transfer-saldo');
        } else {
          alert('gagal', 'Penerima transfer tidak ditemukan', 'transfer-saldo');
        }
    } else {
      alert('gagal', 'Jumlah transfer tidak valid', 'transfer-saldo');
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Transfer Saldo</title>

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
            <h1>Transfer Saldo</h1>
          </div>
          <div class="row justify-content-center">
            <div class="col-lg-6">
              <div class="card">
                <div class="card-header bg-primary">
                  <h4 class="text-white">Transfer Saldo</h4>
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

                    <div class="form-group">
                        <label class="control-label">Jumlah Transfer</label>
                        <input type="number" class="form-control" name="jumlah" autocomplete="off" >
                    </div>

                    <div class="text-right">
                      <button class="btn btn-default" type="reset">Reset</button>
                      <button class="btn btn-primary" type="submit" name="tombol">Transfer Saldo</button>
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
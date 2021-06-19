<?php

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

if (isset($_GET['tolak'])) {
    $tolak = $_GET['tolak'];
    mysqli_query($konek, "UPDATE deposit SET status = 'Gagal' WHERE id = '$tolak'");
}

if (isset($_GET['terima'])) {
    $terima = $_GET['terima'];
    $qD = mysqli_query($konek, "SELECT * FROM deposit WHERE id = '$terima'");
    if (mysqli_num_rows($qD) === 1 ) {
        $fD = mysqli_fetch_assoc($qD);
        $idDepo = $fD['id_deposit'];

        if ($fD['status'] === "Menunggu") {
            $userDeposit = $fD['username'];
            $dapatDeposit = $fD['saldo_didapat'];

            $qDepo = mysqli_query($konek, "SELECT * FROM user WHERE username = '$userDeposit'");
            $fDepo = mysqli_fetch_assoc($qDepo);

            $saldoSekarang = $fDepo['saldo'];
            $saldoJadi = $fDepo['saldo'] + $dapatDeposit;

            mysqli_query($konek, "UPDATE user SET saldo = saldo+$dapatDeposit WHERE username = '$userDeposit'");
            mysqli_query($konek, "UPDATE deposit SET status = 'Sukses' WHERE id = '$terima'");

            mysqli_query($konek, "INSERT INTO saldo (username, aksi, saldo_aktifity, tanggal, efek, saldo_awal, saldo_jadi) VALUES ('$userDeposit','Melakukan Deposit Saldo dengan ID : $idDepo', '$dapatDeposit','$tanggal $waktu','+ Saldo','$saldoSekarang','$saldoJadi')");

            alert('berhasil', 'Deposit berhasil, saldo sudah di kirimkan', 'kelola-deposit');
        } else {
            alert('gagal', 'Deposit sudah ' . $fD['status'], 'kelola-deposit');
        }

    } else {
        alert('gagal', 'Deposit tidak di temukan', 'kelola-deposit');
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Kelola Deposit</title>

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
            <h1>Kelola Deposit</h1>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header bg-primary">
                  <h4 class="text-white">Kelola Deposit</h4>
                </div>
                <div class="card-body" style="overflow: auto;">
                  <?php if (isset($_COOKIE['berhasil'])): ?>
                  <div class="alert alert-success">
                      <?= $_COOKIE['berhasil']; ?>
                  </div>
                  <?php endif ?>
                  <?php if (isset($_COOKIE['gagal'])): ?>
                  <div class="alert alert-danger">
                      <?= $_COOKIE['gagal']; ?>
                  </div>
                  <?php endif ?>
                  <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>ID&nbsp;Deposit</th>
                        <th>Username</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                        <th>Saldo&nbsp;Didapat</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php 
                      $no = 1;
                      $q = mysqli_query($konek, "SELECT * FROM deposit ORDER BY id DESC");
                      while($r = mysqli_fetch_assoc($q)) :
                      ?>
                      <tr>
                        <td><?= $no; ?></td>
                        <td><?= $r['id_deposit']; ?></td>
                        <td><?= $r['username']; ?></td>
                        <td><?= $r['metode_deposit']; ?></td>
                        <td><?= number_format($r['jumlah_deposit'],0,',','.'); ?></td>
                        <td><?= number_format($r['saldo_didapat'],0,',','.'); ?></td>
                        <td>
                          <?php 
                          $statusPembelian = $r['status'];
                          if ($statusPembelian === "Menunggu") {
                              echo '<span class="badge badge-warning">Menunggu</span>';
                          } else if ($statusPembelian === "Success" || $statusPembelian === "Sukses") {
                              echo '<span class="badge badge-success">Sukses</span>';
                          } else {
                              echo '<span class="badge badge-danger">'.$statusPembelian.'</span>';
                          }
                          ?>
                        </td>
                        <td><?= $r['tanggal']; ?></td>
                        <td align="center">
                          <span onclick="detail('<?= $r['id']; ?>')" class="badge badge-primary" style="cursor: pointer;" title="Detail Deposit"><i class="fa fa-eye"></i></span>
                        </td>
                      </tr>
                      <?php $no++; endwhile; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php require '../../include/footer.php'; ?>
    </div>
  </div>

  <!-- sample modal content -->
  <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Detail Deposit</h5>
              </div>
              <form action="" method="POST">
                  <div class="modal-body">
                      <div id="data_detail"></div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tutup</button>
                  </div>
              </form>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

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

  <script>
    function detail(id) {
      $("#myModal").modal('show');
      $.ajax({
        url : 'get-data.php',
        data    : 'id='+id,
        type    : 'POST',
        dataType: 'html',
        success : function(msg){
                 $("#data_detail").html(msg);
            }
      });
    }
  </script>

</body>
</html>

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

if (isset($_POST['tombol'])) {
  $tipe = addslashes(htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['tipe']))));
  $metode = addslashes(htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['metode']))));
  $rate = addslashes(htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['rate']))));
  $tujuan = addslashes(htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['tujuan']))));

  if (empty($tipe) OR empty($metode) OR empty($rate) OR empty($tujuan)) {
    alert('gagal', 'Masih ada data yang kosong', 'metode-deposit');
  } else {
    mysqli_query($konek, "INSERT INTO metode (tipe, metode, rate, tujuan) VALUES ('$tipe','$metode','$rate','$tujuan')");
    alert('berhasil', 'Metode baru berhasil di tambahkan', 'metode-deposit');
  }
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  mysqli_query($konek, "DELETE FROM metode WHERE id = '$id'");
  alert('berhasil', 'Metode berhasil di hapus', 'metode-deposit');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Metode Deposit</title>

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
            <h1>Metode Deposit</h1>
          </div>
          <div class="row">
            <div class="col-lg-5">
              <div class="card">
                <div class="card-header bg-primary">
                  <h4 class="text-white">Tambah Metode</h4>
                </div>
                <div class="card-body" style="overflow: auto;">
                  <form class="form-horizontal" role="form" action="" method="POST">
                    <div class="form-group">
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
                      <label class="control-label">Tipe</label>
                      <input type="text" class="form-control" name="tipe" autocomplete="off">
                    </div>
                    <div class="form-group">
                      <label class="control-label">Nama Metode</label>
                      <input type="text" class="form-control" name="metode" autocomplete="off">
                    </div>

                    <div class="form-group">
                      <label class="control-label">Rate</label>
                      <input type="text" class="form-control" name="rate" autocomplete="off">
                    </div>

                    <div class="form-group">
                      <label class="control-label">Tujuan</label>
                      <input type="text" class="form-control" name="tujuan" autocomplete="off">
                    </div>

                    <div class="text-right">
                      <button class="btn btn-default" type="reset">Reset</button>
                      <button class="btn btn-primary" type="submit" name="tombol">Tambah Metode</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-lg-7">
              <div class="card">
                <div class="card-header bg-primary">
                  <h4 class="text-white">Data Metode</h4>
                </div>
                <div class="card-body" style="overflow: auto;">
                  <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Tipe</th>
                        <th>Metode</th>
                        <th>Rate</th>
                        <th>Tujuan</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $no = 1;
                      $qInfo = mysqli_query($konek, "SELECT * FROM metode ORDER BY id DESC LIMIT 5");
                      while($rInfo = mysqli_fetch_assoc($qInfo)) :
                      ?>
                      <tr>
                        <td><?= $no; ?></td>
                        <td><?= $rInfo['tipe']; ?></td>
                        <td><?= $rInfo['metode']; ?></td>
                        <td><?= $rInfo['rate']; ?></td>
                        <td><?= $rInfo['tujuan']; ?></td>
                        <td align="center"><a href="?id=<?= $rInfo['id']; ?>" class="label label-danger"><i class="fa fa-trash"></i></a></td>
                      </tr>
                      <?php $no++; endwhile; ?>
                      <?php if (mysqli_num_rows($qInfo) === 0 ): ?>
                      <tr>
                        <td colspan="5" align="center">Tidak ada metode</td>
                      </tr>
                      <?php endif ?>
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
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title" id="myModalLabel">Detail Deposit</h4>
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
v
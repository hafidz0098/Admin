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

if (isset($_GET['hapus'])) {
    $hapus = $_GET['hapus'];
    if($hapus === "kosong") {
        mysqli_query($konek, "TRUNCATE TABLE service");
        alert('berhasil', 'Layanan berhasil di hapus', 'kelola-layanan');
    } else {
        mysqli_query($konek, "DELETE FROM service WHERE id = '$hapus'");
        alert('berhasil', 'Layanan berhasil di hapus', 'kelola-layanan');
    }
}

if (isset($_POST['tombol'])) {
    $id = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['id'])));
    $layanan = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['layanan'])));
    $kategori = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['kategori'])));
    $harga = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['harga'])));
    $min = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['min'])));
    $max = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['max'])));
    $catatan = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['catatan'])));

    if (empty($id) OR empty($layanan) OR empty($kategori) OR empty($harga) OR empty($min) OR empty($max) OR empty($catatan)) {
        alert('gagal', 'Data layanan tidak boleh kosong', 'kelola-layanan');
    } else {
        mysqli_query($konek, "UPDATE service SET service = '$layanan', category = '$kategori', harga = '$harga', min = '$min', max = '$max', catatan = '$catatan' WHERE id = '$id'");
        alert('berhasil', 'Layanan berhasil di update', 'kelola-layanan');
    }

}

if (isset($_GET['get'])) {
    $url = $link . "/sistem/service.php";
     
    $curlHandle = curl_init();
    curl_setopt($curlHandle, CURLOPT_URL, $url);
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, "data1=x&data2=y&data3=z");
    curl_setopt($curlHandle, CURLOPT_HEADER, 0);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
    curl_setopt($curlHandle, CURLOPT_POST, 1);
    curl_exec($curlHandle);
}

if (isset($_POST['tombolTambah'])) {
    $service = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['service'])));
    $category = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['category'])));
    $harga = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['harga'])));
    $min = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['min'])));
    $max = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['max'])));
    $catatan = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['catatan'])));
    $provider_id = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['provider_id'])));

    if (empty($service) OR empty($category) OR empty($harga) OR empty($min) OR empty($max) OR empty($catatan) OR empty($provider_id)) {
        alert('gagal', 'Layanan baru gagal di tambahkan', 'kelola-layanan');
    } else {
        mysqli_query($konek, "INSERT INTO service (service, harga, min, max, category, catatan, provider_id) VALUES ('$service','$harga','$min','$max','$category','$catatan','$provider_id')");
        alert('berhasil', 'Layanan baru berhasil di tambahkan', 'kelola-layanan');
    }

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Kelola Layanan</title>

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
            <h1>Kelola Layanan</h1>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header bg-primary">
                  <h4 class="text-white">Kelola Layanan</h4>
                </div>
                <div class="card-body" style="overflow: auto;">
                  <div class="row mb-3">
                      <div class="col-md-4">
                          <button class="btn btn-primary btn-block" onclick="get();">Ambil Layanan</button>
                      </div>
                      <div class="col-md-4">
                          <a href="?hapus=kosong" class="btn btn-primary btn-block">Kosongkan Semua Layanan</a>
                      </div>
                      <div class="col-md-4">
                          <button class="btn btn-primary btn-block" onclick="tambahLayanan();">Tambah Layanan</button>
                      </div>
                  </div>

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
                        <th>Provider&nbsp;ID</th>
                        <th>Layanan</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Min</th>
                        <th>Max</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php 
                      $no = 1;
                      $q = mysqli_query($konek, "SELECT * FROM service ORDER BY id DESC");
                      while($r = mysqli_fetch_assoc($q)) :
                      ?>
                      <tr>
                          <td><?= $no; ?></td>
                          <td><?= $r['provider_id']; ?></td>
                          <td><?= $r['service']; ?></td>
                          <td><?= $r['category']; ?></td>
                          <td><?= number_format($r['harga'],0,',','.'); ?></td>
                          <td><?= number_format($r['min'],0,',','.'); ?></td>
                          <td><?= number_format($r['max'],0,',','.'); ?></td>
                          <td><?= $r['catatan']; ?></td>
                          <td align="center">
                            <span onclick="edit('<?= $r['id']; ?>')" class="badge badge-info" style="cursor: pointer;" title="Edit Layanan"><i class="fa fa-pen"></i></span>
                            <span onclick="hapusLayanan('<?= $r['id']; ?>');" class="badge badge-danger" style="cursor: pointer;" title="Hapus Layanan"><i class="fa fa-trash"></i></span>
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
                    <h5 class="modal-title" id="myModalLabel">Edit Layanan</h5>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div id="data_detail"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tutup</button>
                        <button type="submit" name="tombol" class="btn btn-primary">Simpan Layanan</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- sample modal content -->
    <div id="hapusLayanan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Layanan</h5>
                </div>
                <form action="" method="GET">
                    <div class="modal-body">
                        <input type="hidden" name="hapus" id="id_layanan">
                        Layanan akan di hapus?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tutup</button>
                        <button type="submit" name="tombolHapus" class="btn btn-primary">Tetap Hapus</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- sample modal content -->
    <div id="tambahLayanan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Layanan</h5>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Layanan</label>
                            <input type="text" class="form-control" required="required" name="service">
                        </div>
                        <div class="form-group">
                            <label>Kategori</label>
                            <input type="text" class="form-control" required="required" name="category">
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" class="form-control" required="required" name="harga">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Minimal Pembelian</label>
                                    <input type="number" class="form-control" required="required" name="min">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Maksimal Pembelian</label>
                                    <input type="number" class="form-control" required="required" name="max">
                                </div>
                            </div>
                        </div>
                         <div class="form-group">
                            <label>Catatan</label>
                            <textarea name="catatan" id="catatan" cols="30" rows="6" class="form-control"></textarea>
                        </div>
                         <div class="form-group">
                            <label>Provider ID</label>
                            <input type="number" class="form-control" required="required" name="provider_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tutup</button>
                        <button type="submit" name="tombolTambah" class="btn btn-primary">Tambah Layanan</button>
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
    function get() {
      var a = confirm('Get layanan?, Semua layanan akan di hapus dan di ganti');
      if (a == true) {
         window.location.href = '?get=bp';
      }
    }
    function edit(id) {
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
    function tambahLayanan() {
      $("#tambahLayanan").modal('show');
    }
    function hapusLayanan(id) {
      $("#id_layanan").val(id);
      $("#hapusLayanan").modal('show');
    }
  </script>
</body>
</html>

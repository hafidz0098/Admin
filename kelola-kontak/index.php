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
    $nama = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['nama'])));
    $wa = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['wa'])));
    $nama_facebook = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['nama_facebook'])));
    $link_facebook = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['link_facebook'])));
    $username_ig = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['username_ig'])));

    if (empty($nama) OR empty($wa) OR empty($nama_facebook) OR empty($link_facebook) OR empty($username_ig)) {
        alert('gagal', 'Masih ada data yang kosong', 'kelola-kontak');
    } else {
        mysqli_query($konek, "INSERT INTO kontak (nama, fb, link_fb, wa, ig, jabatan) VALUES ('$nama','$nama_facebook','$link_facebook','$wa','$username_ig', 'None')");
        alert('berhasil', 'Kontak baru berhasil di tambahkan', 'kelola-kontak');
    }
}

if (isset($_GET['id'])) {
    $idN = $_GET['id'];
    mysqli_query($konek, "DELETE FROM kontak WHERE id = '$idN'");
    alert('berhasil', 'Kontak berhasil di hapus', 'kelola-kontak');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Kelola kontak</title>

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
            <h1>Kelola kontak</h1>
          </div>
          <div class="row">
            <div class="col-lg-5">
              <div class="card">
                <div class="card-header bg-primary">
                  <h4 class="text-white">Tambah Kontak</h4>
                </div>
                <div class="card-body" style="overflow: auto;">
                  <form class="form-horizontal" role="form" action="" method="POST">
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
                    <div class="form-group">
                      <label class="control-label">Nama</label>
                      <input type="text" class="form-control" autocomplete="off" name="nama">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Kontak Whatsapp</label>
                        <input type="numer" class="form-control" autocomplete="off" name="wa">
                    </div>

                    <div class="form-group">
                      <label class="control-label">Nama Facebook</label>
                      <input type="text" class="form-control" autocomplete="off" name="nama_facebook">
                    </div>

                    <div class="form-group">
                      <label class="control-label">Link Facebook</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            https://facebook.com/
                          </div>
                        </div>
                        <input type="text" name="link_facebook" class="form-control" autocomplete="off">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label">Username Instagram</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            https://instagram.com/
                          </div>
                        </div>
                        <input type="text" name="username_ig" class="form-control" autocomplete="off">
                      </div>
                    </div>

                    <div class="text-right">
                      <button class="btn btn-default" type="reset">Reset</button>
                      <button class="btn btn-primary" type="submit" name="tombol">Tambah Kontak</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-lg-7">
              <div class="card">
                <div class="card-header bg-primary">
                  <h4 class="text-white">Data Kontak</h4>
                </div>
                <div class="card-body" style="overflow: auto;">
                  <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Whatsapp</th>
                        <th>Facebook</th>
                        <th>Instagram</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $no = 1;
                      $qInfo = mysqli_query($konek, "SELECT * FROM kontak ORDER BY id DESC");
                      while($rInfo = mysqli_fetch_assoc($qInfo)) :
                      ?>
                      <tr>
                        <td><?= $no; ?></td>
                        <td><?= $rInfo['nama']; ?></td>
                        <td><?= $rInfo['wa']; ?></td>
                        <td><a href="http://facebook.com/<?= $rInfo['link_fb']; ?>"><?= $rInfo['fb']; ?></a></td>
                        <td><a href="http://facebook.com/<?= $rInfo['ig']; ?>"><?= $rInfo['ig']; ?></a></td>
                        <td align="center"><a href="?id=<?= $rInfo['id']; ?>" class="badge badge-danger"><i class="fa fa-trash"></i></a></td>
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
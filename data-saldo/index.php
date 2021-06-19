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


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Data Saldo</title>

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
            <h1>Data Saldo</h1>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header bg-primary">
                  <h4 class="text-white">Data Saldo</h4>
                </div>
                <div class="card-body" style="overflow: auto;">
                  <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Keterangan</th>
                        <th>Saldo&nbsp;Awal</th>
                        <th>Saldo&nbsp;Aksi</th>
                        <th>Saldo&nbsp;Akhir</th>
                        <th>Tanggal</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php 
                      $no = 1;
                      $q = mysqli_query($konek, "SELECT * FROM saldo ORDER BY id DESC");
                      while($r = mysqli_fetch_assoc($q)) :
                      ?>
                      <tr>
                        <td><?= $no; ?></td>
                        <td><?= $r['username']; ?></td>
                        <td><?= $r['aksi']; ?></td>
                        <td><?= number_format($r['saldo_awal'],0,',','.'); ?></td>
                        <td>
                          <?php 
                          if ($r['efek'] === "- Saldo") {
                            echo '<span class="badge badge-danger">- '.number_format($r['saldo_aktifity'],0,',','.').'</span>';
                          } else {
                            echo '<span class="badge badge-success">+ '.number_format($r['saldo_aktifity'],0,',','.').'</span>';
                          }
                          ?>
                        </td>
                        <td><?= number_format($r['saldo_jadi'],0,',','.'); ?></td>
                        <td><?= $r['tanggal']; ?></td>
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

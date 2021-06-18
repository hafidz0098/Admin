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


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Kelola Ticket</title>

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
            <h1>Kelola Ticket</h1>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header bg-primary">
                  <h4 class="text-white">Kelola Ticket</h4>
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
                        <th style="width: 10px;">No</th>
                        <th>Username</th>
                        <th>Ticket ID</th>
                        <th>Judul</th>
                        <th style="width: 10px;">Status</th>
                        <th>Tanggal</th>
                        <th style="width: 10px;">Aksi</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php 
                      $no = 1;
                      $q = mysqli_query($konek, "SELECT * FROM id_ticket ORDER BY id DESC");
                      while($r = mysqli_fetch_assoc($q)) :
                      ?>
                      <tr>
                        <td><?= $no; ?></td>
                        <td><?= $r['username']; ?></td>
                        <td><?= $r['ticket_id']; ?></td>
                        <td><?= $r['judul']; ?></td>
                        <td align="center">
                          <?php 
                          if ($r['status'] === "Unread-Member") {
                            echo '<span class="badge badge-info">Unread Member</span>';
                          } else if ($r['status'] === "Unread-Admin") {
                            echo '<span class="badge badge-info">Unread Admin</span>';
                          } else if ($r['status'] === "Read-Admin") {
                            echo '<span class="badge badge-primary">Read Admin</span>';
                          } else if ($r['status'] === "Read-Member") {
                            echo '<span class="badge badge-primary">Read Member</span>';
                          } else {
                            echo $r['status'];
                          }
                          ?>
                        </td>
                        <td><?= $r['tanggal']; ?></td>
                        <td align="center">
                          <a href="<?= $link; ?>/ticket/<?= $r['ticket_id']; ?>" class="label label-info">
                            <i class="fa fa-eye"></i>
                          </a>
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

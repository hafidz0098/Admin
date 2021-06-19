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


if (isset($_POST['tombol_simpan'])) {
    $status = addslashes(htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['status']))));
    $trx = addslashes(htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['trx']))));

    if (empty($status) OR empty($trx)) {
        alert('gagal', 'Gaga mengupdate pembelian', 'kelola-pembelian');
    } else {
        mysqli_query($konek, "UPDATE riwayat SET status = '$status' WHERE order_id = '$trx'");
        alert('berhasil', 'Status pembelian berhasil di update', 'kelola-pembelian');
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Kelola Pembelian</title>

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
            <h1>Kelola Pembelian</h1>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header bg-primary">
                  <h4 class="text-white">Kelola Pembelian</h4>
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
                        <th>Trx</th>
                        <th>Username</th>
                        <th>Layanan</th>
                        <th>Target</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php 
                      $no = 1;
                      $q = mysqli_query($konek, "SELECT * FROM riwayat ORDER BY id DESC");
                      while($r = mysqli_fetch_assoc($q)) :
                      ?>
                      <tr>
                        <td><?= $no; ?></td>
                        <td><?= $r['order_id']; ?></td>
                        <td><?= $r['username']; ?></td>
                        <td><?= $r['service']; ?></td>
                        <td><?= $r['target']; ?></td>
                        <td><?= number_format($r['jumlah'],0,',','.'); ?></td>
                        <td><?= number_format($r['harga'],0,',','.'); ?></td>
                        <td>
                          <?php 
                          $statusPembelian = $r['status'];
                          if ($statusPembelian === "Pending") {
                              echo '<span class="badge badge-warning">Pending</span>';
                          } else if ($statusPembelian === "Success" || $statusPembelian === "Sukses") {
                              echo '<span class="badge badge-success">Success</span>';
                          } else if ($statusPembelian === "Gagal" || $statusPembelian === "Canceled" || $statusPembelian === "Error" || $statusPembelian === "Partial") {
                              echo '<span class="badge badge-danger">'.$statusPembelian.'</span>';
                          } else {
                              echo '<span class="badge badge-primary">'.$statusPembelian.'</span>';
                          }
                          ?>
                        </td>
                        <td align="center">
                          <span onclick="edit('<?= $r['order_id']; ?>')" class="badge badge-info" style="cursor: pointer;" title="Edit Pembelian"><i class="fa fa-pen"></i></span>
                          <span onclick="detail('<?= $r['id']; ?>')" class="badge badge-primary" style="cursor: pointer;" title="Detail Pembelian"><i class="fa fa-eye"></i></span>
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
                    <h5 class="modal-title" id="myModalLabel">Edit Pembelian</h5>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="0">Pilih salah satu</option>
                                <option value="Success">Success</option>
                                <option value="Proses">Proses</option>
                                <option value="Pending">Pending</option>
                                <option value="Error">Error</option>
                                <option value="Partial">Partial</option>
                            </select>
                            <input type="hidden" name="trx" id="trx_input">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tutup</button>
                        <button type="submit" name="tombol_simpan" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- sample modal content -->
    <div id="detail_pembelian" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Detail Pembelian</h5>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div id="detial_modal"></div>
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
    function edit(id) {
      $("#trx_input").val(id);
      $("#myModal").modal('show');
    }

    function detail(id) {
      $("#detail_pembelian").modal('show');
      $.ajax({
        url : 'get-detail.php',
        data    : 'id='+id,
        type    : 'POST',
        dataType: 'html',
        success : function(msg){
                 $("#detial_modal").html(msg);
            }
      });
    }
  </script>

</body>
</html>

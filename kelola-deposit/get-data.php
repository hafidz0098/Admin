<?php 
if (isset($_POST['id'])) {
	require '../../include/function.php';
	$id = htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['id'])));
	if (!empty($id) AND is_numeric($id)) {
		$q = mysqli_query($konek, "SELECT * FROM deposit WHERE id = '$id'");
		if (mysqli_num_rows($q) === 1) {
			$f = mysqli_fetch_assoc($q);
            if ($f['pengirim'] === "") {
                $pengirim = "-";
            } else {
                $pengirim = $f['pengirim'];
            }
            $statusPembelian = $f['status'];
            if ($statusPembelian === "Menunggu") {
                $statusNa = '<span class="label label-warning">Menunggu</span>';
            } else if ($statusPembelian === "Success" || $statusPembelian === "Sukses") {
                $statusNa = '<span class="label label-success">Sukses</span>';
            } else {
                $statusNa = '<span class="label label-danger">'.$statusPembelian.'</span>';
            }
			echo '
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <td>ID Deposit</td>
                    <td>'.$f['id_deposit'].'</td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td>'.$f['username'].'</td>
                </tr>
                <tr>
                    <td>Jumlah Deposit</td>
                    <td>'.number_format($f['jumlah_deposit'],0,',','.').'</td>
                </tr>
                <tr>
                    <td>Saldo Didapat</td>
                    <td>'.number_format($f['saldo_didapat'],0,',','.').'</td>
                </tr>
                <tr>
                    <td>Pengirim</td>
                    <td>'.$pengirim.'</td>
                </tr>
                <tr>
                    <td>Tipe Deposit</td>
                    <td>'.$f['tipe_deposit'].'</td>
                </tr>
                <tr>
                    <td>Metode Deposit</td>
                    <td>'.$f['metode_deposit'].'</td>
                </tr>
                <tr>
                    <td>Tujuan Deposit</td>
                    <td>'.$f['tujuan_deposit'].'</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>'.$statusNa.'</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>'.$f['tanggal'].'</td>
                </tr>
            </table>
			';
            if ($f['status'] === "Menunggu") {
                echo '<div class="row">
                    <div class="col-md-6" style="margin-bottom: 10px;">
                        <a href="?terima='.$id.'" class="btn btn-success btn-block">Terima</a>
                    </div>
                    <div class="col-md-6" style="margin-bottom: 10px;">
                        <a href="?tolak='.$id.'" class="btn btn-danger btn-block">Tolak</a>
                    </div>
                </div>';
            }

		}
	} 
} else {
	require '../../404.shtml';
}
?>
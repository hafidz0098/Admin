<?php 
require '../../include/function.php';
if (isset($_POST['id'])) {
	$id = $_POST['id'];
	$q = mysqli_query($konek, "SELECT * FROM riwayat WHERE id = '$id'");
	if (mysqli_num_rows($q) === 1 ) {
		$f = mysqli_fetch_assoc($q);

		echo '
			<table class="table table-bordered table-striped table-hover">
				<tr>
					<th>Trx Pembelian</th>
					<td>'.$f['order_id'].'</td>
				</tr>
				<tr>
					<th>Username</th>
					<td>'.$f['username'].'</td>
				</tr>
				<tr>
					<th>Jumlah</th>
					<td>'.number_format($f['jumlah'],0,',','.').'</td>
				</tr>
				<tr>
					<th>Harga</th>
					<td>'.number_format($f['harga'],0,',','.').'</td>
				</tr>
				<tr>
					<th>Jumlah Mulai</th>
					<td>'.number_format($f['start_count'],0,',','.').'</td>
				</tr>
				<tr>
					<th>Jumlah Kurang</th>
					<td>'.number_format($f['remains'],0,',','.').'</td>
				</tr>
				<tr>
					<th>Status</th>
					<td>'.$f['status'].'</td>
				</tr>
				<tr>
					<th>Tanggal</th>
					<td>'.$f['tanggal'].' '.$f['waktu'].'</td>
				</tr>
			</table>
		';

	} else {
		echo '<div class="alert alert-danger">Pembelian tidak di temukan</div>';
	}
} else {
	require '../../404.shtml';
}
?>
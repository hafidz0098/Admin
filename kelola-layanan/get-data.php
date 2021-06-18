<?php 
if (isset($_POST['id'])) {
	require '../../include/function.php';
	$id = addslashes(htmlspecialchars(trim(mysqli_real_escape_string($konek, $_POST['id']))));
	if (!empty($id) AND is_numeric($id)) {
		$q = mysqli_query($konek, "SELECT * FROM service WHERE id = '$id'");
		if (mysqli_num_rows($q) === 1) {
			$f = mysqli_fetch_assoc($q);
			echo '
			<div class="form-group">
                <label>Layanan</label>
                <input type="hidden" name="id" value="'.$f['id'].'">
                <input type="text" class="form-control" name="layanan" value="'.$f['service'].'">
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" class="form-control" name="kategori" value="'.$f['category'].'">
            </div>
            <div class="form-group">
                <label>Harga</label>
                <input type="text" class="form-control" name="harga" value="'.$f['harga'].'">
            </div>
            <div class="row">
            	<div class="col-md-6">
            		<div class="form-group">
		                <label>Min</label>
		                <input type="text" class="form-control" name="min" value="'.$f['min'].'">
		            </div>
            	</div>
            	<div class="col-md-6">
            		<div class="form-group">
		                <label>Max</label>
		                <input type="text" class="form-control" name="max" value="'.$f['max'].'">
		            </div>
            	</div>
            </div>
    		<div class="form-group">
                <label>Catatan</label>
                <textarea name="catatan" id="catatan" name="catatan" class="form-control" cols="30" rows="7">'.$f['catatan'].'</textarea>
            </div>
			';

		}
	} 
} else {
	require '../../404.shtml';
}
?>
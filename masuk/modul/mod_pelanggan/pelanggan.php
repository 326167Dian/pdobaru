<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// session_start(); // Sudah aktif di media_admin.php
if (empty($_SESSION['username']) and empty($_SESSION['passuser'])) {
	echo "<link href=../css/style.css rel=stylesheet type=text/css>";
	echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
} else {

	$aksi = "modul/mod_pelanggan/aksi_pelanggan.php";
	$aksi_pelanggan = "masuk/modul/mod_pelanggan/aksi_pelanggan.php";
	switch (isset($_GET['act']) ? $_GET['act'] : '') {
			// Tampil Siswa
		default:

			$stmt = $db->query("SELECT * FROM pelanggan ORDER BY id_pelanggan ASC");
			$tampil_pelanggan = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">DATA PELANGGAN</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div><!-- /.box-tools -->
				</div>
				<div class="box-body table-responsive">
					<a class='btn  btn-success btn-flat' href='?module=pelanggan&act=tambah'>TAMBAH</a>
					<br><br>


					<table id="tampil" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Pelanggan</th>
								<th>Telepon</th>
								<th>Alamat</th>
								<th>Follow Up</th>
								<th width="70">Aksi</th>
							</tr>
						</thead>
						<tbody>
				</tbody></table>
<!-- DataTables server-side: rows are loaded via ajax -->

				<script>
					$(document).ready(function() {
						$("#tampil").DataTable({
							processing: true,
							serverSide: true,
							autoWidth: false,
							ajax: {
								"url": "modul/mod_pelanggan/pelanggan_serverside.php?action=table_data",
								"dataType": "JSON",
								"type": "POST"
							},
							columns: [{
								"data": "no",
								"className": 'text-center',
							},
							{
								"data": "nm_pelanggan"
							},
							{
								"data": "tlp_pelanggan"
							},
							{
								"data": "alamat_pelanggan"
							},
							{
								"data": "followup"
							},
							{
								"data": "pilih",
								"className": 'text-center'
							}
						]
						});
					});
				</script>
	
				</div>
			</div>


<?php

			break;

		case "tambah":

			echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>TAMBAH</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body table-responsive'>
				
						<form method=POST action='$aksi?module=pelanggan&act=input_pelanggan' enctype='multipart/form-data' class='form-horizontal'>
						
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nama Pelanggan</label>        		
									 <div class='col-sm-4'>
										<input type=text name='nm_pelanggan' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Telepon</label>        		
									 <div class='col-sm-4'>
										<input type=text name='tlp_pelanggan' class='form-control' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Alamat</label>        		
									 <div class='col-sm-4'>
										<textarea name='alamat_pelanggan' class='form-control' rows='3'></textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Keterangan</label>        		
									 <div class='col-sm-4'>
										<textarea name='ket_pelanggan' class='form-control' rows='3'></textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-5'>
											<input class='btn btn-info' type=submit value=SIMPAN>
									<input class='btn btn-primary' type=button value=BATAL onclick='self.history.back()'>
							  
				</div> 
				
			</div>";


			break;

		case "riwayat":
			$stmt = $db->prepare("SELECT * FROM pelanggan WHERE id_pelanggan = ?");
			$stmt->execute([$_GET['id']]);
			$p = $stmt->fetch(PDO::FETCH_ASSOC);
			// Generate CSRF token for riwayat actions if not set
			if (!isset($_SESSION['csrf_pelanggan']) || empty($_SESSION['csrf_pelanggan'])) {
				if (function_exists('random_bytes')) {
					$_SESSION['csrf_pelanggan'] = bin2hex(random_bytes(16));
				} else {
					$_SESSION['csrf_pelanggan'] = bin2hex(openssl_random_pseudo_bytes(16));
				}
			}
			$token = $_SESSION['csrf_pelanggan'];
			echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>RIWAYAT PELANGGAN : $p[nm_pelanggan]</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
					</div><!-- /.box-tools -->
				</div>
				<div class='box-body table-responsive'>";
			// flash message display if exists
			if (isset($_SESSION['flash'])){
				echo $_SESSION['flash'];
				unset($_SESSION['flash']);
			}
			echo "
			<form method=POST action='$aksi?module=pelanggan&act=input_riwayat' enctype='multipart/form-data' class='form-horizontal'>
				<input type=hidden name='id_pelanggan' value='$_GET[id]'>
				<input type=hidden name='token' value='$token'>
				<div class='form-group'>
					<label class='col-sm-2 control-label'>Tanggal</label>
					<div class='col-sm-4'>
						<input type='date' name='tgl' class='form-control' required='required' value='".date('Y-m-d')."'>
					</div>
				</div>
				<div class='form-group'>
					<label class='col-sm-2 control-label'>Diagnosa</label>
					<div class='col-sm-4'>
						<textarea name='diagnosa' class='form-control' rows='3'></textarea>
					</div>
				</div>
				<div class='form-group'>
					<label class='col-sm-2 control-label'>Tindakan</label>
					<div class='col-sm-4'>
						<textarea name='tindakan' class='form-control' rows='3'></textarea>
					</div>
				</div>
				<div class='form-group'>
					<label class='col-sm-2 control-label'>Followup</label>
					<div class='col-sm-4'>
						<textarea name='followup' class='form-control' rows='3'></textarea>
					</div>
				</div>
				<div class='form-group'>
					<label class='col-sm-2 control-label'></label>
					<div class='col-sm-5'>
						<input class='btn btn-info' type=submit value=SIMPAN>
							<input class='btn btn-primary' type=button value=KEMBALI onclick='self.history.back()'>
					</div>
				</div>
			</form>
			<hr>
			<h4>Riwayat Sebelumnya</h4>
			<table class='table table-bordered table-striped'>
				<thead>
					<tr>
						<th>No</th>
						<th>Tanggal</th>
						<th>Diagnosa</th>
						<th>Tindakan</th>
						<th>Followup</th>
						<th>Created</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>";
			$stmt = $db->prepare("SELECT * FROM riwayat_pelanggan WHERE id_pelanggan = ? ORDER BY tgl DESC");
			$stmt->execute([$_GET['id']]);
			$riwayat = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$no = 1;
			foreach($riwayat as $rw){
				$edit_link = "?module=pelanggan&act=edit_riwayat&id=$_GET[id]&idr=".$rw['id'];
				$delete_link = $aksi."?module=pelanggan&act=hapus_riwayat&id=".$rw['id']."&token=".$token;
				echo "<tr>
					<td>$no</td>
					<td>$rw[tgl]</td>
					<td>$rw[diagnosa]</td>
					<td>$rw[tindakan]</td>
					<td>$rw[followup]</td>
					<td>$rw[created_at]</td>
					<td>
						<a href='".$edit_link."' title='EDIT' class='btn btn-warning btn-xs'>EDIT</a>
						<a href=javascript:confirmdelete('".$delete_link."') title='HAPUS' class='btn btn-danger btn-xs'>HAPUS</a>
					</td>
				</tr>";
				$no++;
			}
			echo "</tbody></table>
			</div>
		</div>";
			break;
		case "edit_riwayat":
			$idr = intval($_GET['idr']);
			$stmt = $db->prepare("SELECT * FROM riwayat_pelanggan WHERE id = ? AND id_pelanggan = ?");
			$stmt->execute([$idr, $_GET['id']]);
			if ($stmt->rowCount() < 1) {
				$_SESSION['flash'] = "<div class='alert alert-danger'>Riwayat tidak ditemukan.</div>";
				header('location:../../media_admin.php?module=pelanggan&act=riwayat&id='.$_GET['id']);
				exit;
			}
			$rw = $stmt->fetch(PDO::FETCH_ASSOC);
			$token = isset($_SESSION['csrf_pelanggan']) ? $_SESSION['csrf_pelanggan'] : '';
			echo "
		  <div class='box box-primary box-solid'>
			<div class='box-header with-border'>
				<h3 class='box-title'>UBAH RIWAYAT PELANGGAN</h3>
				<div class='box-tools pull-right'>
					<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
				</div>
			</div>
			<div class='box-body table-responsive'>
			<form method=POST action='$aksi?module=pelanggan&act=update_riwayat' enctype='multipart/form-data' class='form-horizontal'>
				<input type=hidden name='id_pelanggan' value='$_GET[id]'>
				<input type=hidden name='id_riwayat' value='".$rw['id']."'>
				<input type=hidden name='token' value='".$token."'>
				<div class='form-group'>
					<label class='col-sm-2 control-label'>Tanggal</label>
					<div class='col-sm-4'>
						<input type='date' name='tgl' class='form-control' required='required' value='".$rw['tgl']."'>
					</div>
				</div>
				<div class='form-group'>
					<label class='col-sm-2 control-label'>Diagnosa</label>
					<div class='col-sm-4'>
						<textarea name='diagnosa' class='form-control' rows='3'>".htmlspecialchars($rw['diagnosa'])."</textarea>
					</div>
				</div>
				<div class='form-group'>
					<label class='col-sm-2 control-label'>Tindakan</label>
					<div class='col-sm-4'>
						<textarea name='tindakan' class='form-control' rows='3'>".htmlspecialchars($rw['tindakan'])."</textarea>
					</div>
				</div>
				<div class='form-group'>
					<label class='col-sm-2 control-label'>Followup</label>
					<div class='col-sm-4'>
						<textarea name='followup' class='form-control' rows='3'>".htmlspecialchars($rw['followup'])."</textarea>
					</div>
				</div>
				<div class='form-group'>
					<label class='col-sm-2 control-label'></label>
					<div class='col-sm-5'>
						<input class='btn btn-primary' type=submit value=UPDATE>
						<input class='btn btn-default' type=button value=BATAL onclick='self.history.back()'>
					</div>
				</div>
			</form>
			</div>
		</div>";
			break;
		case "edit": 
			$stmt = $db->prepare("SELECT * FROM pelanggan WHERE id_pelanggan = ?");
			$stmt->execute([$_GET['id']]);
			$r = $stmt->fetch(PDO::FETCH_ASSOC);

			echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>UBAH</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body table-responsive'>
						<form method=POST method=POST action=$aksi?module=pelanggan&act=update_pelanggan  enctype='multipart/form-data' class='form-horizontal'>
							  <input type=hidden name=id value='$r[id_pelanggan]'>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nama Pelanggan</label>        		
									 <div class='col-sm-4'>
										<input type=text name='nm_pelanggan' class='form-control' value='$r[nm_pelanggan]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Telepon</label>        		
									 <div class='col-sm-4'>
										<input type=text name='tlp_pelanggan' class='form-control' value='$r[tlp_pelanggan]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Alamat</label>        		
									 <div class='col-sm-4'>
										<textarea name='alamat_pelanggan' class='form-control' rows='3'>$r[alamat_pelanggan]</textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Keterangan</label>        		
									 <div class='col-sm-4'>
										<textarea name='ket_pelanggan' class='form-control' rows='3'>$r[ket_pelanggan]</textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-5'>
											<input class='btn btn-primary' type=submit value=SIMPAN>
								<input class='btn btn-primary' type=button value=BATAL onclick='self.history.back()'>
							  
				</div> 
				
			</div>";




			break;

	}
}
?>
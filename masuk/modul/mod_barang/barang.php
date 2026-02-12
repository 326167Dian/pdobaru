<?php
session_start();
include "../../../configurasi/koneksi.php";
if (empty($_SESSION['username']) and empty($_SESSION['passuser'])) {
	echo "<link href=../css/style.css rel=stylesheet type=text/css>";
	echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
} else {

	$aksi = "modul/mod_barang/aksi_barang.php";
	$aksi_barang = "masuk/modul/mod_barang/aksi_barang.php";
	switch ($_GET['act']) {
			// Tampil barang
		default:

			// $tampil_barang = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang ORDER BY barang.id_barang ");
        
        
?>


			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">DATA BARANG</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div><!-- /.box-tools -->
				</div>
				<div class="box-body table-responsive">
					<a class='btn  btn-success btn-flat' href='?module=barang&act=tambah'>TAMBAH</a>
					<?php
					
					$lupa = $_SESSION['level'];
					if ($lupa == 'pemilik') {
                                echo " <a class='btn  btn-primary btn-flat' href='modul/mod_laporan/cetak_barang_excel.php' target='_blank'>EXPORT TO EXCEL</a>     
                                 <a class='btn  btn-warning btn-flat' href='modul/mod_laporan/cetak_batch.php' target='_blank'>EXPORT TO EXCEL BASED ON ACTIVE BATCH</a>
									        ";
                            }
                            
                    
                    ?>        
					
					<hr>
					<CENTER><strong>MySIFA PROFIT ANALYSIS</strong></CENTER><br>
					<center><button type="button" class="btn btn-info">PROFIT>30%</button>
						<button type="button" class="btn btn-success">PROFIT = 25 - 30 % </button>
						<button type="button" class="btn btn-warning">PROFIT = 20 - 25%"</button>
						<button type="button" class="btn btn-danger">PROFIT < 20% </button>
					</center>
					<br><br>


					<table id="tes" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Kode</th>
								<th>Nama Barang</th>
								<th>No. Batch</th>
								<th style="text-align: right; ">Qty/Stok</th>
								<th style="text-align: right; ">Satuan</th>
								<th style="text-align: center; ">Jenis Obat</th>
								<th style="text-align: right; ">Harga Beli</th>
								<th style="text-align: right; ">Harga Jual</th>
								<th style="text-align: center; ">Komposisi dan Indikasi</th>
								<!--<th style="text-align: center; ">Aksi</th>-->
								<?php
								$lupa = $_SESSION['level'];
								if ($lupa == 'pemilik') {
									echo "<th>Aksi</th> ";
								} else {
								}
								?>
							</tr>
						</thead>

					</table>
				</div>
			</div>


<?php

			break;

		case "tambah":

			echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>TAMBAH DATA BARANG &rarr; <a href='https://www.youtube.com/watch?v=9daG5ZnVVGw' target='_blanks'> (TONTON TUTORIAL)</a> </h3> 
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body table-responsive'>
				
						<form method=POST action='$aksi?module=barang&act=input_barang' enctype='multipart/form-data' class='form-horizontal'>
						
					<div class='col-md-6'>
							  							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Kode Barang</label>        		
									 <div class='col-sm-8'>
										<input type=text name='kd_barang' class='form-control' autocomplete='off'>
									 </div>
							  </div>
							  
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Nama Barang</label>        		
									 <div class='col-sm-8'>
										<input type=text name='nm_barang' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  <!-- tidak bisa tambah stok dari sini
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Qty/Stok</label>        		
									 <div class='col-sm-8'>
										<input type=number name='stok_barang' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div> -->
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Stok Buffer</label>        		
									 <div class='col-sm-8'>
										<input type=number name='stok_buffer' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Satuan Retail</label>        		
									 <div class='col-sm-8'>
										<select name='sat_barang' class='form-control' >";
                            			$tampil = $db->query("SELECT * FROM satuan ORDER BY nm_satuan ASC");
                            			while ($rk = $tampil->fetch(PDO::FETCH_ASSOC)) {
                            				echo "<option value=$rk[nm_satuan]>$rk[nm_satuan]</option>";
                            			}
                            			echo "
                            			</select>
									 </div>
							  </div> 
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Satuan Grosir</label>        		
									 <div class='col-sm-8'>
										<select name='sat_grosir' class='form-control' >";
                            			$tampil = $db->query("SELECT * FROM satuan ORDER BY nm_satuan ASC");
                            			while ($rk = $tampil->fetch(PDO::FETCH_ASSOC)) {
                            				echo "<option value=$rk[nm_satuan]>$rk[nm_satuan]</option>";
                            			}
                            			echo "
                            			</select>
									 </div>
							  </div> 
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Jenis Obat</label>        		
									 <div class='col-sm-8'>
										<select name='jenisobat' class='form-control' >";
                            			$tampil = $db->query("SELECT * FROM jenis_obat ORDER BY jenisobat ASC");
                            			while ($rk = $tampil->fetch(PDO::FETCH_ASSOC)) {
                            				echo "<option value=$rk[jenisobat]>$rk[jenisobat]</option>";
                            			}
                            			echo "
                            			</select>
									 </div>
							  </div>
							  							  
							   <div class='form-group'>
									<label class='col-sm-4 control-label'>Konversi</label>        		
									 <div class='col-sm-8'>
										<input type='number' min='0' name='konversi' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div> 
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Harga Beli Retail</label>        		
									 <div class='col-sm-8'>
										<input type='number' min='0' name='hrgsat_barang' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Harga Beli Grosir</label>        		
									 <div class='col-sm-8'>
										<input type='number' min='0' name='hrgsat_grosir' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							   <div class='form-group'>
									<label class='col-sm-4 control-label'>Harga Jual Reguler</label>        		
									 <div class='col-sm-8'>
										<input type='number' min='0' name='hrgjual_barang' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Harga Jual Resep</label>        		
									 <div class='col-sm-8'>
										<input type='number' min='0' name='hrgjual_barang1' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Harga Jual Marketplace</label>        		
									 <div class='col-sm-8'>
										<input type='number' min='0' name='hrgjual_barang2' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
					</div>		  
					<div class='col-md-6'>
							  <div class='form-group'>
									<label class='col-sm-5 control-label'>Komposisi dan Indikasi</label>
										<div class='col-sm-12'>
											<div >	
													<textarea name='indikasi' class='ckeditor' id='content' rows='3'>$r[indikasi]</textarea>
											</div>
										</div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Keterangan Lain</label>        		
									 <div class='col-sm-12'>
										<textarea name='ket_barang' class='ckeditor' rows='3' id='content'></textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'></label>       
										<div class='col-sm-8'>
											<input class='btn btn-primary' type=submit value=SIMPAN>
											<input class='btn btn-danger' type=button value=BATAL onclick=self.history.back()>
										</div>
								</div>
					</div>			
							  </form>
							  
				</div> 
				
			</div>";


			break;

		case "edit":
			$edit = $db->prepare("SELECT * FROM barang WHERE id_barang = ?");
			$edit->execute([$_GET['id']]);
			$r = $edit->fetch(PDO::FETCH_ASSOC);

			echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>UBAH DATA BARANG</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body table-responsive'>
						<form method=POST method=POST action=$aksi?module=barang&act=update_barang  enctype='multipart/form-data' class='form-horizontal'>
							  <input type=hidden name=id value='$r[id_barang]'>						  
						<div class='col-md-6'>	 
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Kode Barang</label>        		
									 <div class='col-sm-8'>
										<input type=text name='kd_barang' class='form-control' required='required' value='$r[kd_barang]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Nama Barang</label>        		
									 <div class='col-sm-8'>
										<input type=text name='nm_barang' class='form-control' required='required' value='$r[nm_barang]' autocomplete='off'>
									 </div>
							  </div>
							  <!-- tidak bisa edit stok dari sini
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Qty/Stok</label>        		
									 <div class='col-sm-3'>
										<input type=number name='stok_barang' class='form-control' required='required' value='$r[stok_barang]' autocomplete='off'>
									 </div>
							  </div> -->
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Stok Buffer</label>        		
									 <div class='col-sm-8'>
										<input type=number name='stok_buffer' class='form-control' required='required' value='$r[stok_buffer]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Satuan Retail</label>        		
									 <div class='col-sm-8'>
										<select name='sat_barang' class='form-control' >
											 <option  value=$r[sat_barang] selected>$r[sat_barang]</option>";
                                            $tampil = $db->query("SELECT * FROM satuan ORDER BY nm_satuan");
                                            while ($k = $tampil->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value=$k[nm_satuan]>$k[nm_satuan]</option>";
                                            }
                                            echo "</select>
									 </div>
							  </div> 
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Satuan Grosir</label>        		
									 <div class='col-sm-8'>
										<select name='sat_grosir' class='form-control' >
											 <option  value=$r[sat_grosir] selected>$r[sat_grosir]</option>";
                                            $tampil = $db->query("SELECT * FROM satuan ORDER BY nm_satuan");
                                            while ($k = $tampil->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value=$k[nm_satuan]>$k[nm_satuan]</option>";
                                            }
                                            echo "</select>
									 </div>
							  </div> 
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Jenis Obat</label>        		
									 <div class='col-sm-8'>
										<select name='jenisobat' class='form-control' >
											 <option  value=$r[jenisobat] selected>$r[jenisobat]</option>";
                                                $tampil = $db->query("SELECT * FROM jenis_obat ORDER BY idjenis");
                                                while ($k = $tampil->fetch(PDO::FETCH_ASSOC)) {
                                                    echo "<option value=$k[jenisobat]>$k[jenisobat]</option>";
                                                }
                                                echo "</select>
									 </div>
							  </div>
							  
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Konversi</label>        		
									 <div class='col-sm-8'>
										<input type='number' min='0' name='konversi' class='form-control' required='required' value='$r[konversi]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Harga Beli Retail</label>        		
									 <div class='col-sm-8'>
										<input type='number' min='0' name='hrgsat_barang' class='form-control' required='required' value='$r[hrgsat_barang]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Harga Beli Grosir</label>        		
									 <div class='col-sm-8'>
										<input type='number' min='0' name='hrgsat_grosir' class='form-control' required='required' value='$r[hrgsat_grosir]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Harga Jual Reguler</label>        		
									 <div class='col-sm-8'>
										<input type='number' min='0' name='hrgjual_barang' class='form-control' required='required' value='$r[hrgjual_barang]' autocomplete='off'>
									 </div>
							  </div>
							  
							   <div class='form-group'>
									<label class='col-sm-4 control-label'>Harga Jual Resep</label>        		
									 <div class='col-sm-8'>
										<input type='number' min='0' name='hrgjual_barang1' class='form-control' required='required' value='$r[hrgjual_barang1]' autocomplete='off'>
									 </div>
							  </div>
							  
							   <div class='form-group'>
									<label class='col-sm-4 control-label'>Harga Jual Marketplace</label>        		
									 <div class='col-sm-8'>
										<input type='number' min='0' name='hrgjual_barang2' class='form-control' required='required' value='$r[hrgjual_barang2]' autocomplete='off'>
									 </div>
							  </div>
							  
						</div>
						<div class='col-md-6'>	  
							  <div class='form-group'>
									<label class='col-sm-5 control-label'>Komposisi dan Indikasi</label>
										<div class='col-sm-12'>
											<div >	
													<textarea name='indikasi' class='ckeditor' id='content' rows='3'>$r[indikasi]</textarea>
											</div>
										</div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Komposisi</label>        		
									 <div class='col-sm-8'>
										<textarea name='ket_barang' class='ckeditor' id='content' rows='3'>$r[ket_barang]</textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Dosis / Kekuatan </label>        		
									 <div class='col-sm-8'>
										<textarea name='dosis' rows='3'>$r[dosis]</textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'></label>       
										<div class='col-sm-8'>
											<input class='btn btn-primary' type=submit value=SIMPAN>
											<input class='btn btn-danger' type=button value=BATAL onclick=self.history.back()>
										</div>
								</div>
						</div>		
							  </form>
							  
				</div> 
				
			</div>";




			break;
        case "detail" :
            $detail = $db->prepare("SELECT * FROM barang WHERE id_barang = ?");
            $detail->execute([$_GET['id']]);
            $s = $detail->fetch(PDO::FETCH_ASSOC);
            $sid = $s['kd_barang'];

        ?>
        <div class="box box-primary box-solid">
            <div class='box-header with-border'>
                <h3 class='box-title'>DETAIL BARANG</h3>
                <div class='box-tools pull-right'>
                    <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                </div><!-- /.box-tools -->
            </div>
        <div class='box-body table-responsive'>
        <div class="form-group row">
            <div class="container" style="font-weight: bold">
                <div class="row" style="background-color: #00FFFF" >
                    <div class="col-xs-4">
                        Nama Barang
                    </div>
                    <div class="col-xs-8">
                        : <?=$s['nm_barang']?>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-xs-4">
                        Satuan Retail
                    </div>
                    <div class="col-xs-8">
                        : <?=$s['sat_barang']?>
                    </div>
                </div>
                <div class="row" style="background-color: #00FFFF">
                    <div class="col-xs-4">
                        Satuan Grosir
                    </div>
                    <div class="col-xs-8">
                        : <?=$s['sat_grosir']?>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-xs-4">
                        Stok Retail
                    </div>
                    <div class="col-xs-8">
                        : <?=$s['stok_barang']?>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-xs-4">
                        Stok Grosir
                    </div>
                    <div class="col-xs-8">
                        : <?=round($s['stok_barang'] / $s['konversi'])?>
                    </div>
                </div>
                <div class="row" style="background-color: #00FFFF" >
                    <div class="col-xs-4">
                        Jenis Obat
                    </div>
                    <div class="col-xs-8">
                        : <?=$s['jenisobat']?>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-xs-4">
                        Konversi
                    </div>
                    <div class="col-xs-8">
                        : <?=$s['konversi']?>
                    </div>
                </div>
                <div class="row" style="background-color: #FF1493" >
                    <div class="col-xs-4">
                        Harga Nett Apotek
                    </div>
                    <div class="col-xs-8">
                        : <?= format_rupiah($s['hna'])?>
                    </div>
                </div>
                <div class="row" style="background-color: #00FFFF" >
                    <div class="col-xs-4">
                        Harga Beli Retail
                    </div>
                    <div class="col-xs-8">
                        : <?= format_rupiah($s['hrgsat_barang'])?>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-xs-4">
                        Harga Beli Grosir
                    </div>
                    <div class="col-xs-8">
                        : <?= format_rupiah($s['hrgsat_grosir'])?>
                    </div>
                </div>
                <div class="row" style="background-color: #00FFFF" >
                    <div class="col-xs-4" >
                        Harga Jual Reguler
                    </div>
                    <div class="col-xs-8">
                        : <?= format_rupiah($s['hrgjual_barang'])?>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-xs-4" >
                        Harga Jual Member
                    </div>
                    <div class="col-xs-8">
                        : <?=format_rupiah($s['hrgjual_barang1'])?>
                    </div>
                </div>
                <div class="row" style="background-color: #00FFFF">
                    <div class="col-xs-4">
                       Harga Jual Marketplace
                    </div>
                    <div class="col-xs-8">
                        : <?= format_rupiah($s['hrgjual_barang2'])?>
                    </div>
                </div>
                <div class="row"  >
                    <div class="col-xs-4">
                        Komposisi dan Indikasi
                    </div>
                    <div class="col-xs-8">
                        <?=$s['indikasi']?>
                    </div>
                </div>

            </div>
            <div style="text-align:center;">
                <?php
                   echo" <a href='?module=barang&act=edit&id=$s[id_barang]' title='EDIT' class='btn btn-warning btn-xl'>EDIT</a>
                ";
                ?>
                    <input class='btn btn-success' type='button' value=KEMBALI onclick=self.history.back()>
            </div>
            </div>
        </div>
        <?php
            break ;
	}
}
?>

<script type="text/javascript" src="vendors/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
	var editor = CKEDITOR.replace("content", {
		filebrowserBrowseUrl: '',
		filebrowserWindowWidth: 1000,
		filebrowserWindowHeight: 500
	});
</script>
<script>
	$(document).ready(function() {
		$('#tes').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				"url": "modul/mod_barang/barang-serverside.php?action=table_data",
				"dataType": "JSON",
				"type": "POST"
			},
			"rowCallback": function(row, data, index) {
                let q = (data['hrgjual_barang_reguler'] - data['hrgsat_barang']) / data['hrgsat_barang'];
                
                if(q <= 0.2){
                    $(row).find('td:eq(7)').css('background-color', '#ff003f');
                    $(row).find('td:eq(7)').css('color', '#ffffff');
                } else if(q > 0.2 && q <= 0.25){
                    $(row).find('td:eq(7)').css('background-color', '#f39c12');
                    $(row).find('td:eq(7)').css('color', '#ffffff');
                    
                } else if(q > 0.25 && q <= 0.3){
                    $(row).find('td:eq(7)').css('background-color', '#00ff3f');
                    $(row).find('td:eq(7)').css('color', '#ffffff');
                    
                } else if(q > 0.3){
                    $(row).find('td:eq(7)').css('background-color', '#00bfff');
                    $(row).find('td:eq(7)').css('color', '#ffffff');
                    
                }
                
            },
			columns: [{
					"data": "no",
					"className": 'text-center'
				},
				{
					"data": "kd_barang"
				},
				{
					"data": "nm_barang"
				},
				{
					"data": "batch"
				},
				{
					"data": "stok_barang",
					"className": 'text-center'
				},
				{
					"data": "sat_barang",
					"className": 'text-center'
				},
				{
					"data": "jenisobat",
					"className": 'text-center'
				},
				{
					"data": "hrgsat_barang",
					"className": 'text-right',
					"render": function(data, type, row) {
						return formatRupiah(data);
					}
				},
				{
					"data": "hrgjual_barang",
					"className": 'text-left',
				// 	"render": function(data, type, row) {
				// 		return formatRupiah(data);
				// 	}
				},
				{
					"data": "indikasi",
					"className": 'text-justify'
				},
				{
					"data": "aksi",
					"visible": <?= ($_SESSION['level'] == 'pemilik') ? 'true' : 'false'; ?>,
					"render": function(data, type, row) {
						var btn = "<div style='text-align:center'><a href='?module=barang&act=edit&id=" + data + "' title='EDIT' class='btn btn-warning btn-xs'>EDIT</a> " +
                            "<div style='text-align:center'><a href='?module=barang&act=detail&id=" + data + "') title='DETAIL' class='btn btn-primary btn-xs'>DETAIL</a> " +
                            "<a href=javascript:confirmdelete('modul/mod_barang/aksi_barang.php?module=barang&act=hapus&id=" + data + "') title='HAPUS' class='btn btn-danger btn-xs'>HAPUS</a></div>";

						return btn;
					}
				},
			]
		});
	});
</script>
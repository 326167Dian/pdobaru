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
								 <a class='btn  btn-danger btn-flat' href='?module=zataktif'>Zat Aktif/Merk Obat</a>
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
								<th>Nama Barang</th>
								<th style="text-align: center; ">Nama Editor</th>
								<th style="text-align: right; ">Harga Jual</th>
								<th style="text-align: right; ">Zat Aktif</th>
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

					<div id="indikasiModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-fullscreen" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title">Edit Komposisi dan Indikasi</h4>
								</div>
								<div class="modal-body">
									<textarea id="indikasi_modal_editor" rows="12"></textarea>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
									<button type="button" class="btn btn-primary" id="indikasi_modal_save">Simpan</button>
								</div>
							</div>
						</div>
					</div>

					<div id="zataktifModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-fullscreen" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title">Edit Zat Aktif</h4>
								</div>
								<div class="modal-body">
									<textarea id="zataktif_modal_editor" rows="12"></textarea>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
									<button type="button" class="btn btn-primary" id="zataktif_modal_save">Simpan</button>
								</div>
							</div>
						</div>
					</div>
					<?php
					$editor = $db->query("select updated_by, count(*) as jumlah from barang GROUP BY updated_by");
					
					while ($row = $editor->fetch(PDO::FETCH_ASSOC)) {
						echo "<p style='color: #666; font-size: 12px;'>Nama Editor <strong>$row[updated_by]</strong> : $row[jumlah] item</p>";
					}
					?>
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
									<label class='col-sm-4 control-label'>Harga Jual Member</label>        		
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

							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Zat Aktif</label>        		
									 <div class='col-sm-8'>
										<input type='text' name='zataktif' class='form-control' autocomplete='off'>
									 </div>
						  	  </div>
						  					</div>		  
					<div class='col-md-6'>
							  <div class='form-group'>
									<label class='col-sm-5 control-label'>Komposisi dan Indikasi</label>
										<div class='col-sm-12'>
											<div >	
													<textarea name='indikasi' id='content' rows='3'></textarea>
											</div>
										</div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Keterangan Lain</label>        		
									 <div class='col-sm-12'>
										<textarea name='ket_barang' id='content_ket' rows='3'></textarea>
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
			$returnStart = isset($_GET['start']) ? (int)$_GET['start'] : 0;

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
							  <input type=hidden name=return_start value='$returnStart'>
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
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Zat Aktif</label>        		
									 <div class='col-sm-8'>										
									 	<textarea name='zataktif' class='ckeditor' id='content2' rows='3'>$r[zataktif]</textarea>
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
										<textarea name='ket_barang' class='ckeditor'  rows='3'>$r[ket_barang]</textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-4 control-label'>Dosis / Kekuatan </label>        		
									 <div class='col-sm-8'>
										<textarea name='dosis' class='ckeditor' id='content3' rows='3'>$r[dosis]</textarea>
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
<style>
	.modal-fullscreen {
		width: 98%;
		margin: 10px auto;
	}
	.modal-fullscreen .modal-content {
		height: calc(100vh - 20px);
		overflow: hidden;
	}
	.modal-fullscreen .modal-body {
		height: calc(100% - 120px);
		overflow: auto;
	}
	#indikasi_modal_editor {
		width: 100%;
		height: 100%;
	}
	#zataktif_modal_editor {
		width: 100%;
		height: 100%;
	}
	#indikasiModal.is-open {
		display: block;
		position: fixed;
		inset: 0;
		background: rgba(0, 0, 0, 0.5);
		z-index: 1050;
		overflow: auto;
	}
	#indikasiModal.is-open.fade {
		opacity: 1;
	}
	#indikasiModal.is-open .modal-content {
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
	}
	#indikasiModal.is-open .modal-dialog {
		margin: 10px auto;
	}
	#zataktifModal.is-open {
		display: block;
		position: fixed;
		inset: 0;
		background: rgba(0, 0, 0, 0.5);
		z-index: 1050;
		overflow: auto;
	}
	#zataktifModal.is-open.fade {
		opacity: 1;
	}
	#zataktifModal.is-open .modal-content {
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
	}
	#zataktifModal.is-open .modal-dialog {
		margin: 10px auto;
	}
</style>
<script type="text/javascript">
	// Inisialisasi CKEditor untuk form tambah
	if (document.getElementById('content')) {
		CKEDITOR.replace('content', {
			filebrowserBrowseUrl: '',
			filebrowserWindowWidth: 1000,
			filebrowserWindowHeight: 500
		});
	}
	if (document.getElementById('content_ket')) {
		CKEDITOR.replace('content_ket', {
			filebrowserBrowseUrl: '',
			filebrowserWindowWidth: 1000,
			filebrowserWindowHeight: 500
		});
	}
	
	// Inisialisasi CKEditor untuk form edit
	if (document.getElementById('edit_zataktif')) {
		CKEDITOR.replace('edit_zataktif', {
			filebrowserBrowseUrl: '',
			filebrowserWindowWidth: 1000,
			filebrowserWindowHeight: 500
		});
	}
	if (document.getElementById('edit_indikasi')) {
		CKEDITOR.replace('edit_indikasi', {
			filebrowserBrowseUrl: '',
			filebrowserWindowWidth: 1000,
			filebrowserWindowHeight: 500
		});
	}
	if (document.getElementById('edit_ket_barang')) {
		CKEDITOR.replace('edit_ket_barang', {
			filebrowserBrowseUrl: '',
			filebrowserWindowWidth: 1000,
			filebrowserWindowHeight: 500
		});
	}
	if (document.getElementById('edit_dosis')) {
		CKEDITOR.replace('edit_dosis', {
			filebrowserBrowseUrl: '',
			filebrowserWindowWidth: 1000,
			filebrowserWindowHeight: 500
		});
	}
</script>
<script>
var userLevel = '<?= $_SESSION['level']; ?>';
</script>
<?php
$barang_table_config_path = __DIR__ . '/barang_table_config.js';
$barang_table_config_ver = file_exists($barang_table_config_path) ? filemtime($barang_table_config_path) : time();
?>
<script src="modul/mod_barang/barang_table_config.js?v=<?= $barang_table_config_ver ?>"></script>
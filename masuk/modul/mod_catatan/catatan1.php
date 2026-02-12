<?php
session_start();
include "../../../configurasi/koneksi.php";
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href=../css/style.css rel=stylesheet type=text/css>";
  echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

$aksi="modul/mod_catatan/aksi_catatan.php";

switch($_GET['act']){
  // tampil catatan
  default:

	  ?>
			
			
			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">CATATAN HARIAN</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class="box-body">
					<a  class ='btn  btn-success btn-flat' href='?module=catatan&act=tambah'>TAMBAH</a>
					<br><br>
					
					
					<table id="example1" class="table table-bordered table-striped" >
						<thead>
							<tr>
								<th>No</th>
								<th>tanggal</th>
								<th>Shift</th>
                                <th>Petugas</th>
                                <th>catatan singkat</th>
								<th width="70">Aksi</th>
							</tr>
						</thead>
						<tbody>
						<?php 
								$no=1;
								$tampil_catatan = $db->prepare("SELECT * FROM catatan ORDER BY tgl DESC");
                                $tampil_catatan->execute();
								while ($r = $tampil_catatan->fetch(PDO::FETCH_ASSOC)){
									echo "<tr class='warnabaris' >
											<td>$no</td>           
											 <td>$r[tgl]</td>
											 <td>$r[shift]</td>
											 <td>$r[petugas]</td>
											 <td>$r[deskripsi]</td>
											 <td>
											 <a href='?module=catatan&act=edit&id=$r[id_catatan]' title='EDIT' class='btn btn-warning btn-xs'>EDIT</a> 
											 <a href='?module=catatan&act=tampil&id=$r[id_catatan]' title='EDIT' class='btn btn-primary btn-xs'>TAMPIL</a> 
										     <a href=javascript:confirmdelete('$aksi?module=catatan&act=hapus&id=$r[id_catatan]') title='HAPUS' class='btn btn-danger btn-xs'>HAPUS</a>
											</td>
										</tr>";
								$no++;
								}
						echo "</tbody></table>";
					?>
				</div>
                
			</div>	
             

<?php
    
    break;
	
	case "tambah":
        $tglharini = date('Y-m-d');
        $petugas = $_SESSION['namalengkap'];
        $cekshift = $db->prepare("SELECT * FROM waktukerja WHERE tanggal = ? AND status = 'ON'");
        $cekshift->execute([$tglharini]);
        $hitung = $cekshift->rowCount();
        $sshift = $cekshift->fetch(PDO::FETCH_ASSOC);
        $shift = $sshift['shift'];

        echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>TAMBAH CATATAN</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
				
						<form method=POST action='$aksi?module=catatan&act=input_catatan' enctype='multipart/form-data' class='form-horizontal'>
						 <input type='hidden' name='petugas' id='petugas' value='$petugas'>
						 <input type='hidden' name='shift' id='shift' value='$shift'>
							 
							 <label class='col-sm-4 control-label'>Tanggal </label>
										<div class='col-sm-6'>
											<div class='input-group date'>
												<div class='input-group-addon'>
													<span class='glyphicon glyphicon-th'></span>
												</div>
													<input type='text' class='datepicker' name='tgl' value='$tglharini' autocomplete='off'>
											</div>
										</div>
							  
							<br>
							<label style='text-align:left;'>Catatan</label>
										<div class='col-xs-12'>
											<div>	
													<textarea name='deskripsi' class='ckeditor' id='content'></textarea>
											</div>
										</div>
							
							 <br> 
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-5'>
											<input class='btn btn-primary' type=submit value='SIMPAN'>
											<input class='btn btn-danger' type=button value='BATAL' onclick='self.history.back()'>
										</div>
								</div>
								
							  </form>
							  
				</div> 
				
			</div>";
					
	
    break;

  case "edit":
      $petugas = $_SESSION['namalengkap'];
      $edit = $db->prepare("SELECT * FROM catatan WHERE id_catatan = ?");
      $edit->execute([$_GET['id']]);
      $r = $edit->fetch(PDO::FETCH_ASSOC);
      $lupa = $_SESSION['level'];
        

      if (($petugas !== $r['petugas']) && ($lupa !== 'pemilik')) {
          echo "<script type='text/javascript'>alert('catatan hanya bisa di edit oleh orang yang sama!');history.go(-1);</script>";
      } else {

     	echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>EDIT CATATAN</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
						<form method=POST method=POST action=$aksi?module=catatan&act=update_catatan  enctype='multipart/form-data' class='form-horizontal'>
							<input type=hidden name=id value='$r[id_catatan]'>
							 <div class='form-group'>
									
											<div class='col-xs-12'>
											<div >	
													<textarea name='deskripsi' class='ckeditor' id='content'>$r[deskripsi]</textarea>
													
											</div>
										</div>
							  </div>
							 
							 <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-5'>
											<input class='btn btn-primary' type=submit value=SIMPAN>
											<input class='btn btn-danger' type=button value=BATAL onclick=self.history.back()>
										</div>
								</div>
							  </form>
							  
				</div> 
				
			</div>";}
	
    break;
    case "tampil" :
        $edit = $db->prepare("SELECT * FROM catatan WHERE id_catatan = ?");
        $edit->execute([$_GET['id']]);
        $r = $edit->fetch(PDO::FETCH_ASSOC);
        echo "$r[deskripsi]
        <input class='btn btn-primary' type=button value=KEMBALI onclick=self.history.back()>";
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
<script type="text/javascript">
 $(function(){
  $(".datepicker").datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true,
      todayHighlight: true,
  });
 });
</script>
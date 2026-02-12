<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
include "../../../configurasi/koneksi.php";
include "../../../configurasi/fungsi_thumb.php";
include "../../../configurasi/library.php";

$module=$_GET['module'];
$act=$_GET['act'];

// Input admin
if ($module=='setheader' AND $act=='update_setheader'){
 
    $fupload_name =  $_FILES["fupload1"]["name"];
    UploadLogo_Struk($fupload_name);
    
    $stmt = $db->prepare("SELECT logo FROM setheader WHERE id_setheader = ?");
    $stmt->execute([$_POST['id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($row['logo'] != 'mysifalogo.png' and $fupload_name !=''){
        unlink('../../images/'.$row['logo']);
    }
    if($fupload_name == ''){
        $fupload_name = $row['logo'];
    }
    
    $stmt = $db->prepare("UPDATE setheader SET satu = ?, dua = ?, tiga = ?, empat = ?, lima = ?, enam = ?, tujuh = ?, delapan = ?, sembilan = ?, sepuluh = ?, sebelas = ?, duabelas = ?, tigabelas = ?, logo = ? WHERE id_setheader = ?");
    $stmt->execute([$_POST['satu'], $_POST['dua'], $_POST['tiga'], $_POST['empat'], $_POST['lima'], $_POST['enam'], $_POST['tujuh'], $_POST['delapan'], $_POST['sembilan'], $_POST['sepuluh'], $_POST['sebelas'], $_POST['duabelas'], $_POST['tigabelas'], $fupload_name, $_POST['id']]);
									
	echo "<script type='text/javascript'>alert('Data berhasil diubah !');window.location='../../media_admin.php?module=".$module."'</script>";
	//header('location:../../media_admin.php?module='.$module);
	
}

}
?>

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
    if ($module=='catatan' AND $act=='input_catatan'){
        
        $cekganda = $db->prepare("SELECT deskripsi FROM catatan WHERE deskripsi = ?");
        $cekganda->execute([$_POST['deskripsi']]);
        $ada = $cekganda->rowCount();
        
        if ($ada > 0){
            echo "<script type='text/javascript'>alert('catatan sudah ada!');history.go(-1);</script>";
        }else{

            $db->prepare("INSERT INTO catatan (
                            tgl,shift,petugas,deskripsi)
							VALUES(?,?,?,?)")->execute([$_POST['tgl'], $_POST['shift'], $_POST['petugas'], $_POST['deskripsi']]);

            //echo "<script type='text/javascript'>alert('Data berhasil ditambahkan !');window.location='../../media_admin.php?module=".$module."'</script>";
            header('location:../../media_admin.php?module='.$module);

        }
    }
    //update catatan
    elseif ($module=='catatan' AND $act=='update_catatan'){

        $db->prepare("UPDATE catatan SET   
                                deskripsi = ?
									WHERE id_catatan = ?")->execute([$_POST['deskripsi'], $_POST['id']]);

        //echo "<script type='text/javascript'>alert('Data berhasil diubah !');window.location='../../media_admin.php?module=".$module."'</script>";
        header('location:../../media_admin.php?module='.$module);

    }
//Hapus Proyek
    elseif ($module=='catatan' AND $act=='hapus'){
        $petugas = $_SESSION['namalengkap'];
        $edit = $db->prepare("SELECT * FROM catatan WHERE id_catatan = ?");
        $edit->execute([$_GET['id']]);
        $r = $edit->fetch(PDO::FETCH_ASSOC);

        if ( $petugas !== $r['petugas'] && $_SESSION['level']!=='pemilik')
        { echo "<script type='text/javascript'>alert('catatan harus dihapus orang yang sama atau pemilik apotek!');history.go(-1);</script>";}
        else{
            $db->prepare("DELETE FROM catatan WHERE id_catatan = ?")->execute([$_GET['id']]);
            //echo "<script type='text/javascript'>alert('Data berhasil dihapus !');window.location='../../media_admin.php?module=".$module."'</script>";
            header('location:../../media_admin.php?module='.$module);

        }
    }

}
?>

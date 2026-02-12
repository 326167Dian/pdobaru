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
if ($module=='pelanggan' AND $act=='input_pelanggan'){

$stmt = $db->prepare("SELECT COUNT(*) FROM pelanggan WHERE nm_pelanggan = ? AND tlp_pelanggan = ?");
$stmt->execute([$_POST['nm_pelanggan'], $_POST['tlp_pelanggan']]);
$ada = $stmt->fetchColumn();
if ($ada > 0){
echo "<script type='text/javascript'>alert('Nama Pelanggan dengan nomor telepon ini sudah ada!');history.go(-1);</script>";
}else{

    $stmt = $db->prepare("INSERT INTO pelanggan(nm_pelanggan, tlp_pelanggan, alamat_pelanggan, ket_pelanggan)
								 VALUES(?, ?, ?, ?)");
    $stmt->execute([$_POST['nm_pelanggan'], $_POST['tlp_pelanggan'], $_POST['alamat_pelanggan'], $_POST['ket_pelanggan']]);
										
										
	//echo "<script type='text/javascript'>alert('Data berhasil ditambahkan !');window.location='../../media_admin.php?module=".$module."'</script>";
	header('location:../../media_admin.php?module='.$module);

}
}
 //updata pelanggan
 elseif ($module=='pelanggan' AND $act=='update_pelanggan'){
 
     $stmt = $db->prepare("UPDATE pelanggan SET nm_pelanggan = ?,
									tlp_pelanggan = ?,
									alamat_pelanggan = ?,
									ket_pelanggan = ?
									WHERE id_pelanggan = ?");
     $stmt->execute([$_POST['nm_pelanggan'], $_POST['tlp_pelanggan'], $_POST['alamat_pelanggan'], $_POST['ket_pelanggan'], $_POST['id']]);
									
	//echo "<script type='text/javascript'>alert('Data berhasil diubah !');window.location='../../media_admin.php?module=".$module."'</script>";
	header('location:../../media_admin.php?module='.$module);
	
}
//Hapus Proyek
elseif ($module=='pelanggan' AND $act=='hapus'){

    $stmt = $db->prepare("DELETE FROM pelanggan WHERE id_pelanggan = ?");
    $stmt->execute([$_GET['id']]);
  //echo "<script type='text/javascript'>alert('Data berhasil dihapus !');window.location='../../media_admin.php?module=".$module."'</script>";
  header('location:../../media_admin.php?module='.$module);
}

// Input Riwayat Pelanggan
elseif ($module=='pelanggan' AND $act=='input_riwayat'){
    // CSRF check
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_pelanggan']){
        $_SESSION['flash'] = "<div class='alert alert-danger'>Token tidak valid. Coba ulangi.</div>";
        header('location:../../media_admin.php?module='.$module.'&act=riwayat&id=' . intval($_POST['id_pelanggan']));
        exit;
    }
    // basic validation
    $id_p = intval($_POST['id_pelanggan']);
    $tgl = $_POST['tgl'];
    $diagnosa = trim($_POST['diagnosa']);
    $tindakan = trim($_POST['tindakan']);
    $followup = trim($_POST['followup']);

    if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl)){
        $_SESSION['flash'] = "<div class='alert alert-danger'>Format tanggal tidak valid.</div>";
        header('location:../../media_admin.php?module='.$module.'&act=riwayat&id='.$id_p);
        exit;
    }

    // ensure pelanggan exists
    $stmt = $db->prepare("SELECT id_pelanggan FROM pelanggan WHERE id_pelanggan = ?");
    $stmt->execute([$id_p]);
    if($stmt->rowCount() < 1){
        $_SESSION['flash'] = "<div class='alert alert-danger'>Pelanggan tidak ditemukan.</div>";
        header('location:../../media_admin.php?module='.$module);
        exit;
    }

    $stmt = $db->prepare("INSERT INTO riwayat_pelanggan(id_pelanggan, tgl, diagnosa, tindakan, followup, created_at)
                                VALUES(?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$id_p, $tgl, $diagnosa, $tindakan, $followup]);

    // invalidate token so it can't be reused
    unset($_SESSION['csrf_pelanggan']);

    $_SESSION['flash'] = "<div class='alert alert-success'>Riwayat berhasil disimpan.</div>";
    header('location:../../media_admin.php?module='.$module.'&act=riwayat&id='.$id_p);
}

// Update Riwayat Pelanggan
elseif ($module=='pelanggan' AND $act=='update_riwayat'){
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_pelanggan']){
        $_SESSION['flash'] = "<div class='alert alert-danger'>Token tidak valid. Coba ulangi.</div>";
        header('location:../../media_admin.php?module='.$module.'&act=riwayat&id=' . intval($_POST['id_pelanggan']));
        exit;
    }
    $id_p = intval($_POST['id_pelanggan']);
    $id_r = intval($_POST['id_riwayat']);
    $tgl = $_POST['tgl'];
    $diagnosa = trim($_POST['diagnosa']);
    $tindakan = trim($_POST['tindakan']);
    $followup = trim($_POST['followup']);

    if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl)){
        $_SESSION['flash'] = "<div class='alert alert-danger'>Format tanggal tidak valid.</div>";
        header('location:../../media_admin.php?module='.$module.'&act=edit_riwayat&id='.$id_p.'&idr='.$id_r);
        exit;
    }

    $cek = $db->prepare("SELECT id FROM riwayat_pelanggan WHERE id = ? AND id_pelanggan = ?");
    $cek->execute([$id_r, $id_p]);
    if($cek->rowCount() < 1){
        $_SESSION['flash'] = "<div class='alert alert-danger'>Riwayat tidak ditemukan.</div>";
        header('location:../../media_admin.php?module='.$module.'&act=riwayat&id='.$id_p);
        exit;
    }

    $stmt = $db->prepare("UPDATE riwayat_pelanggan SET tgl = ?, diagnosa = ?, tindakan = ?, followup = ? WHERE id = ?");
    $stmt->execute([$tgl, $diagnosa, $tindakan, $followup, $id_r]);

    unset($_SESSION['csrf_pelanggan']);
    $_SESSION['flash'] = "<div class='alert alert-success'>Riwayat berhasil diperbarui.</div>";
    header('location:../../media_admin.php?module='.$module.'&act=riwayat&id='.$id_p);
}

// Hapus Riwayat Pelanggan
elseif ($module=='pelanggan' AND $act=='hapus_riwayat'){
    if (!isset($_GET['token']) || $_GET['token'] !== $_SESSION['csrf_pelanggan']){
        $_SESSION['flash'] = "<div class='alert alert-danger'>Token tidak valid. Coba ulangi.</div>";
        header('location:../../media_admin.php?module='.$module);
        exit;
    }
    $id = intval($_GET['id']);
    $q = $db->prepare("SELECT id_pelanggan FROM riwayat_pelanggan WHERE id = ?");
    $q->execute([$id]);
    if ($q->rowCount() < 1){
        $_SESSION['flash'] = "<div class='alert alert-danger'>Riwayat tidak ditemukan.</div>";
        header('location:../../media_admin.php?module='.$module);
        exit;
    }
    $row = $q->fetch(PDO::FETCH_ASSOC);
    $id_p = $row['id_pelanggan'];
    $stmt = $db->prepare("DELETE FROM riwayat_pelanggan WHERE id = ?");
    $stmt->execute([$id]);
    unset($_SESSION['csrf_pelanggan']);
    $_SESSION['flash'] = "<div class='alert alert-success'>Riwayat berhasil dihapus.</div>";
    header('location:../../media_admin.php?module='.$module.'&act=riwayat&id='.$id_p);
}

}
?>

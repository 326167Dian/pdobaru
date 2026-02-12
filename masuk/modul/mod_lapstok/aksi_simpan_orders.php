<?php

session_start();
include "../../../configurasi/koneksi.php";

$module         = $_GET['module'];
$act            = $_GET['act'];
$count          = $_POST['check'];
$id_supplier    = $_POST['id_supplier'];

//cek apakah ada kode transaksi ON berdasarkan user
$stmt = $db->prepare("SELECT * FROM kdbm WHERE id_admin=? AND id_resto='pesan' AND stt_kdbm='ON'");
$stmt->execute([$_SESSION['idadmin']]);
$ketemucekkd = $stmt->rowCount();
$hcekkd = $stmt->fetch(PDO::FETCH_ASSOC);

if ($ketemucekkd > 0) {
    $kdtransaksi = $hcekkd['kd_trbmasuk'];
} else {
    $kdunik = date('dmyhis')+1;
	$kdtransaksi = "ORD-" . $kdunik;
	$db->prepare("INSERT INTO kdbm(kd_trbmasuk,id_resto,id_admin) VALUES(?,?,?)")->execute([$kdtransaksi,'pesan',$_SESSION['idadmin']]);
}

$tglharini = date('Y-m-d');
$ttl_trkasir = 0;

for ($i = 0; $i < count($count); $i++) {
    // echo $count[$i] . '<br>';
    
    $stmt_brg = $db->prepare("SELECT * FROM barang WHERE id_barang=?");
    $stmt_brg->execute([$count[$i]]);
    $brg = $stmt_brg->fetch(PDO::FETCH_ASSOC);
    
    $id_barang      = $brg['id_barang'];
    $kd_barang      = $brg['kd_barang'];
    $nm_barang      = $brg['nm_barang'];
    $qty_retail     = $brg['t30'] - $brg['stok_barang'];
    $sat_barang     = $brg['sat_barang'];
    $konversi       = $brg['konversi'];
    $qty_grosir     = $qty_retail / $konversi;
    $sat_grosir     = $brg['sat_grosir'];
    $hna            = $brg['hna'];
    $hrgsat_barang  = $brg['hrgsat_barang'];
    $hrgjual_barang = $brg['hrgjual_barang'];
    $ttl_harga      = $hrgsat_barang * $qty_retail;
    $ttl_trkasir    = $ttl_trkasir + $ttl_harga;
    
    
    $db->prepare("INSERT INTO ordersdetail(kd_trbmasuk,
										id_barang,
										kd_barang,
										nmbrg_dtrbmasuk,
										qty_dtrbmasuk,
										sat_dtrbmasuk,
										hrgsat_dtrbmasuk,
										hrgjual_dtrbmasuk,
										hnasat_dtrbmasuk,
										hrgttl_dtrbmasuk,
										konversi,
										satgrosir_dtrbmasuk,
										qtygrosir_dtrbmasuk)
								  VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)")->execute([
								        $kdtransaksi,
										$id_barang,
										$kd_barang,
										$nm_barang,
										$qty_retail,
										$sat_barang,
										$hrgsat_barang,
										$hrgjual_barang,
										$hna,
										$ttl_harga,
										$konversi,
										$sat_grosir,
										$qty_grosir
										]);
    
}

$stmt_sup = $db->prepare("SELECT * FROM supplier WHERE id_supplier=?");
$stmt_sup->execute([$id_supplier]);
$supplier = $stmt_sup->fetch(PDO::FETCH_ASSOC);
    
// mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO 
//         										orders(id_resto,
//         										petugas,
//         										kd_trbmasuk,
//         										tgl_trbmasuk,
//         										id_supplier,
//         										nm_supplier,
//         										tlp_supplier,
//         										alamat_trbmasuk,
//         										ttl_trbmasuk,
//         										dp_bayar,
//         										sisa_bayar,
//         										ket_trbmasuk)
//         								 VALUES('pesan',
//         								        '$_SESSION[namalengkap]',
//         										'$kdtransaksi',
//         										'$tglharini',
//         										'$id_supplier',
//         										'$supplier[nm_supplier]',
//         										'$supplier[tlp_supplier]',
//         										'$supplier[alamat_supplier]',
//         										'$ttl_trkasir',
//         										'0',
//         										'$ttl_trkasir',
//         										'')");

// mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE kdbm SET stt_kdbm = 'OFF' WHERE id_admin = '$_SESSION[idadmin]' AND id_resto = 'pesan' AND kd_trbmasuk = '$kdtransaksi'");
	
echo $kdtransaksi;
header('location:../../media_admin.php?module=stok_kritis&act=orders&id='.$kdtransaksi);

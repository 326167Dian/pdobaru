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

    $tampil_barang = $db->prepare("SELECT * FROM barang ORDER BY barang.id_barang ");
    $tampil_barang->execute();
    $no=1;

    while ($r = $tampil_barang->fetch(PDO::FETCH_ASSOC)) {

        $beli = "SELECT trbmasuk.tgl_trbmasuk,                                           
                                           SUM(trbmasuk_detail.qty_dtrbmasuk) AS totalbeli                                            
                                           FROM trbmasuk_detail join trbmasuk 
                                           on (trbmasuk_detail.kd_trbmasuk=trbmasuk.kd_trbmasuk)
                                           WHERE kd_barang = ?";
        $buy = $db->prepare($beli);
        $buy->execute([$r['kd_barang']]);
        $buy2 = $buy->fetch(PDO::FETCH_ASSOC);
        $jual = "SELECT trkasir.tgl_trkasir,                                
                                            sum(trkasir_detail.qty_dtrkasir) AS totaljual
                                            FROM trkasir_detail join trkasir 
                                            on (trkasir_detail.kd_trkasir=trkasir.kd_trkasir)
                                            WHERE kd_barang = ?";
        $jokul = $db->prepare($jual);
        $jokul->execute([$r['kd_barang']]);
        $sell = $jokul->fetch(PDO::FETCH_ASSOC);
        $selisih = $buy2['totalbeli'] - $sell['totaljual'];
        $stokbarang = $r['stok_barang'];

        if($stokbarang != $selisih)
        {
            $db->prepare("UPDATE barang SET stok_barang = ? 
                            WHERE kd_barang = ? 
                            AND ($selisih>=0)")->execute([$selisih, $r['kd_barang']]);
        }
    
    }
    header('location:../../media_admin.php?module=trkasir');
}
?>

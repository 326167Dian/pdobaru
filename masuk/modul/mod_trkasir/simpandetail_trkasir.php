<?php 
session_start();
include "../../../configurasi/koneksi.php";

$kd_trkasir         = $_POST['kd_trkasir'];
$id_dtrkasir        = $_POST['id_dtrkasir'];
$id_barang          = $_POST['id_barang'];
$kd_barang          = $_POST['kd_barang'];
$nmbrg_dtrkasir     = $_POST['nmbrg_dtrkasir'];
$qty_dtrkasir       = $_POST['qty_dtrkasir'];
$sat_dtrkasir       = $_POST['sat_dtrkasir'];
$hrgjual_dtrkasir   = $_POST['hrgjual_dtrkasir'];
$indikasi           = $_POST['indikasi'];

if ($_SESSION['komisi'] == 'Y') {
    $tarik = $db->prepare("select komisi from barang where id_barang='$id_barang' ");
    $tarik->execute();
    $kom = $tarik->fetch(PDO::FETCH_ASSOC);

    $komisi = $kom['komisi'] * $_POST['qty_dtrkasir'] ;
}
elseif ($_SESSION['komisi'] == 'N'){
    $komisi = 0;
}

$currentdate = date('Y-m-d',time());
$id_admin = $_POST['id_admin'];

$disc = (empty($_POST['disc']))?0:$_POST['disc'];
$no_batch = $_POST['no_batch'];
$exp_date = $_POST['exp_date'];
$hrgdisc = $hrgjual_dtrkasir * (1-($disc/100));
$tipe = $_POST['tipe'];
$datetime = date('Y-m-d H:i:s', time());

if($qty_dtrkasir == ""){
    $qty_dtrkasir = "1";
}


if($id_dtrkasir == "" || $id_dtrkasir == null){

    //cek apakah barang sudah ada
    $cekdetail = $db->prepare("SELECT * FROM trkasir_detail 
                                WHERE kd_barang=? AND kd_trkasir=?");
    $cekdetail->execute([$kd_barang, $kd_trkasir]);
    $ketemucekdetail = $cekdetail->rowCount();
    $rcek = $cekdetail->fetch(PDO::FETCH_ASSOC);

    if ($ketemucekdetail > 0){
    
        $id_dtrkasir = $rcek['id_dtrkasir'];
        $qtylama = $rcek['qty_dtrkasir'];
        $ttlqty = $qtylama + $qty_dtrkasir;
        $ttlharga = $ttlqty * $hrgdisc;
    
        $mdl = $db->prepare("select hrgsat_barang from barang where id_barang=?");
        $mdl->execute([$id_barang]);
        $mdl1 = $mdl->fetch(PDO::FETCH_ASSOC);
        $modal = $mdl1['hrgsat_barang'];
        $profit = $ttlharga - ($modal * $ttlqty) ;
    
        $stmt_update = $db->prepare("UPDATE trkasir_detail SET qty_dtrkasir = ?,
    										hrgjual_dtrkasir = ?,
                                            modal = ?,
    										profit = ?,
    										hrgttl_dtrkasir = ?,
                                            komisi = ?
    										WHERE id_dtrkasir = ? and kd_barang=?");
    	$stmt_update->execute([$ttlqty, $hrgjual_dtrkasir, $modal, $profit, $ttlharga, $komisi, $id_dtrkasir, $kd_barang]);									
        //update stok
        //cek tambah stok
        $tambahstok = $db->prepare("select id_dtrkasir, kd_trkasir, qty_dtrkasir 
                                    from trkasir_detail 
                                    where kd_trkasir =? 
                                    and kd_barang =?");
        $tambahstok->execute([$kd_trkasir, $kd_barang]);
        $ketemutambahstok = $tambahstok->fetch(PDO::FETCH_ASSOC);
        $angka = $ketemutambahstok[$qty_dtrkasir];
        // if($angka==$ttlqty) {
    
            $cekstok = $db->prepare("SELECT * FROM barang 
                                        WHERE id_barang=?");
            $cekstok->execute([$id_barang]);
            $rst = $cekstok->fetch(PDO::FETCH_ASSOC);
    
            $stok_barang = $rst['stok_barang'];
            $stokakhir = (($stok_barang + $qtylama) - $ttlqty);
    
            $update_barang = $db->prepare("UPDATE barang SET 
                                            stok_barang = ?
                                            WHERE id_barang = ?");
            $update_barang->execute([$stokakhir, $id_barang]);
        //                  }
        // else{}
    
        if($_SESSION['komisi']=='Y'){
            if($_SESSION['penjualansebelum']=='Y'){
                $ttlkomisi = $ttlqty * $komisi;
                $stmt_updatekomisi = $db->prepare("UPDATE komisi_pegawai SET ttl_komisi = ? 
                                                    WHERE id_dtrkasir = ?");
                $stmt_updatekomisi->execute([$ttlkomisi, $id_dtrkasir]);
            } else {
                $ttlkomisi = $ttlqty * $komisi;
                $stmt_updatekomisi = $db->prepare("UPDATE komisi_pegawai SET ttl_komisi = ? 
                                                    WHERE id_dtrkasir = ? 
                                                    AND id_admin = ?");		
                $stmt_updatekomisi->execute([$ttlkomisi, $id_dtrkasir, $_SESSION['idadmin']]);
            }
        }
        
        	//cek apakah barang dengan no batch yang dimaksud sudah ada
        $cekbatchdetail = $db->prepare("SELECT no_batch, kd_transaksi,qty
                                        FROM batch 
                                        WHERE no_batch = '$no_batch' 
                                        AND kd_transaksi = '$kd_trkasir' 
                                        AND status = 'keluar'");
                                        
        $ketemucekbatchdetail = $cekbatchdetail->rowCount();
        if($ketemucekbatchdetail>0)
        {
            //tarikstok dari batch
            $tampung = $cekbatchdetail->fetch(PDO::FETCH_ASSOC);
            $qtybatchlama = $tampung['qty'];
            $qtybatchbaru = $qtybatchlama + $qty_dtrkasir;
    
            $stmt_updatebatch = $db->prepare("UPDATE batch SET qty = ?
                                                WHERE kd_transaksi = ? 
                                                      AND no_batch = ?
                                                      AND status = ?");
            $stmt_updatebatch->execute([$qtybatchbaru, $kd_trkasir, $no_batch, 'keluar']);
        }
    }else{
            
        $ttlharga = $qty_dtrkasir * $hrgdisc;
        
        $mdl = $db->prepare("SELECT hrgsat_barang FROM barang WHERE id_barang =?");
        $mdl->execute([$id_barang]);
        $mdl1 = $mdl->fetch(PDO::FETCH_ASSOC);
        $modal = $mdl1['hrgsat_barang'];
        $profit = $ttlharga - ($modal * $qty_dtrkasir) ;
        
        $stmt_insert_trkasirdetail = $db->prepare("INSERT INTO trkasir_detail(kd_trkasir,
    										id_barang,
    										kd_barang,
    										nmbrg_dtrkasir,
    										qty_dtrkasir,
    										sat_dtrkasir,
    										hrgjual_dtrkasir,
    										disc,
                                            modal,
    										profit,
    										no_batch,
    										exp_date,										
    										hrgttl_dtrkasir,
    										tipe,
                                            komisi,
                                            idadmin)
    								  VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt_insert_trkasirdetail->execute([$kd_trkasir, $id_barang, $kd_barang, $nmbrg_dtrkasir, $qty_dtrkasir, $sat_dtrkasir, $hrgjual_dtrkasir, 
                                $disc, $modal, $profit, $no_batch, $exp_date, $ttlharga, $tipe, $komisi, $id_admin]);
    
        $insertid_dtrkasir = $db->lastInsertId();
        
        if($no_batch != ""){
            // Input batch
            $stmt_insert_batch = $db->prepare("INSERT INTO batch(
                                                tgl_transaksi,
                                                no_batch,
                                                exp_date,
                                                qty,
                                                satuan,
                                                kd_transaksi,										
        										kd_barang,
        										status	
        										)
        								  VALUES(?,?,?,?,?,?,?,?)");    
        	$stmt_insert_batch->execute([$datetime, $no_batch, $exp_date, $qty_dtrkasir, $sat_dtrkasir, $kd_trkasir, $kd_barang, 'keluar']);
        }
    
    										
        //cek transaksi sukses
        $cekmasuk = $db->prepare("SELECT * FROM trkasir_detail 
                                    WHERE kd_trkasir =?");
        $cekmasuk->execute([$kd_trkasir]);
        $ketemucekmasuk = $cekmasuk->rowCount();
        if($ketemucekmasuk > 0 ) {
        //update stok
            
            $cekstok = $db->prepare("SELECT * FROM barang 
                                    WHERE id_barang =?");
            $cekstok->execute([$id_barang]);
            $rst = $cekstok->fetch(PDO::FETCH_ASSOC);
        
            $stok_barang = $rst['stok_barang'];
            $stokakhir = $stok_barang - $qty_dtrkasir;
            
        //update harga jual hanya sementara hanya untuk pemakai awal, setelah itu harga jual tidak bisa diupdate lewat kasir
            $lst_trx = $db->prepare("SELECT * FROM trkasir 
                                        ORDER BY id_trkasir ASC
                                        LIMIT 1");
            $lst_trx->execute();
            $ketemulst_trx = $lst_trx->rowCount();
            if ($ketemulst_trx > 0) {
                $trx =  $lst_trx->fetch(PDO::FETCH_ASSOC);
                $tgl_first = date('Y-m-d', strtotime('+1 months', strtotime($trx['tgl_trkasir'])));
                $tgl_last  = date('Y-m-d', time());
            
            } else {
                $tgl_first = date('Y-m-d', strtotime('+1 months', time()));
                $tgl_last  = date('Y-m-d', time());
            }
            
            if($tgl_last > $tgl_first){
                $set_query = "stok_barang = '$stokakhir'";
            } else {
                if($_POST['tipe'] == '1'){
                    $set_query = "hrgjual_barang = '$hrgjual_dtrkasir',
                    stok_barang = '$stokakhir'";
                } 
                elseif($_POST['tipe'] == '2'){
                    $set_query = "hrgjual_barang1 = '$hrgjual_dtrkasir',
                    stok_barang = '$stokakhir'";
                }
                elseif($_POST['tipe'] == '3'){
                    $set_query = "hrgjual_barang2 = '$hrgjual_dtrkasir',
                    stok_barang = '$stokakhir'";
                }
            }
            
            $stmt_updatebarang = $db->prepare("UPDATE barang SET 
                                                $set_query
                                                WHERE id_barang = '$id_barang'");
            $stmt_updatebarang->execute();
                
            if($_SESSION['komisi']=='Y'){
                if($_SESSION['penjualansebelum']=='Y'){
                    
                    $ttlkomisi = $qty_dtrkasir * $komisi;
                    $stmt_insert_komisi = $db->prepare("INSERT INTO komisi_pegawai (
                    kd_trkasir, id_dtrkasir, id_admin, ttl_komisi, tgl_komisi, status_komisi)
                    VALUES(?, ?, ?, ?, ?, ?)");
                    $stmt_insert_komisi->execute([$kd_trkasir, $insertid_dtrkasir, $id_admin, $ttlkomisi, $currentdate, 'on']);
                } else {
                    $ttlkomisi = $qty_dtrkasir * $komisi;
                    $stmt_insert_komisi = $db->prepare("INSERT INTO komisi_pegawai (
                    kd_trkasir, id_dtrkasir, id_admin, ttl_komisi, tgl_komisi, status_komisi)
                    VALUES(?, ?, ?, ?, ?, ?)");
                    $stmt_insert_komisi->execute([$kd_trkasir, $insertid_dtrkasir, $_SESSION['idadmin'], $ttlkomisi, $currentdate, 'on']);
                }
            }
        }
    }

}else{
    
    $cekdetail = $db->prepare("SELECT * FROM trkasir_detail 
                                WHERE id_dtrkasir =?");
    $cekdetail->execute([$id_dtrkasir]);
    $rcek = $cekdetail->fetch(PDO::FETCH_ASSOC);
    $id_dtrkasir = $rcek['id_dtrkasir'];
    $qtylama = $rcek['qty_dtrkasir'];
    $qtybaru = $qtylama + $qty_dtrkasir;
    $ttlharga = $qtybaru * $hrgjual_dtrkasir;
    
    $update_trkasir = $db->prepare("UPDATE trkasir_detail SET qty_dtrkasir = '$qtybaru',
    										hrgjual_dtrkasir = '$hrgjual_dtrkasir',
    										hrgttl_dtrkasir = '$ttlharga'
    										WHERE id_dtrkasir = '$id_dtrkasir'");
    $update_trkasir->execute();
										
    //update stok
    //cek untuk update
    // $cekmasuk22 = $db->prepare("SELECT * FROM trkasir_detail 
    //                         WHERE id_dtrkasir='$id_dtrkasir'");
    // $cekmasuk22->execute();
    // $ceklagi = $cekmasuk2[$qty_dtrkasir];
    // if($ceklagi == $qtybaru) {
        $cekstok = $db->prepare("SELECT * FROM barang 
                                WHERE id_barang='$id_barang'");
        $cekstok->execute();
        $rst = $cekstok->fetch(PDO::FETCH_ASSOC);

        $stok_barang = $rst['stok_barang'];
        $stokakhir = (($stok_barang + $qtylama) - $qty_dtrkasir);

        $updatebarang = $db->prepare("UPDATE barang SET 
                                    stok_barang = '$stokakhir'
                                    WHERE id_barang = '$id_barang'");
        $updatebarang->execute();
    // }
    // else{}
    
    if($_SESSION['komisi']=='Y'){
        if($_SESSION['penjualansebelum']=='Y'){
            $ttlkomisi = $qtybaru * $komisi;
            $updatekomisi = $db->prepare("UPDATE komisi_pegawai SET ttl_komisi = '$ttlkomisi' 
                                        WHERE id_dtrkasir='$id_dtrkasir'");
            $updatekomisi->execute();
        } else {
            $ttlkomisi = $qtybaru * $komisi;
            $updatekomisi = $db->prepare("UPDATE komisi_pegawai SET ttl_komisi = '$ttlkomisi' 
                                        WHERE id_dtrkasir='$id_dtrkasir' AND id_admin='$_SESSION[idadmin]'");
            $updatekomisi->execute();
        }
    }
}


?>

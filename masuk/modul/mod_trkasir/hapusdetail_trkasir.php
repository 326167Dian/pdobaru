<?php
session_start();
include "../../../configurasi/koneksi.php";

$id_dtrkasir  = $_POST['id_dtrkasir'];

//ambil data
$ambildata=$db->prepare("SELECT * FROM trkasir_detail 
                        WHERE id_dtrkasir =?");
$ambildata->execute([$id_dtrkasir]);
$r = $ambildata->fetch(PDO::FETCH_ASSOC);

$id_barang      = $r['id_barang'];
$qty_dtrkasir   = $r['qty_dtrkasir'];
$kd_trbmasuk    = $r['kd_trkasir'];
$no_batch       = $r['no_batch'];

//update stok

$cekstok = $db->prepare("SELECT id_barang, stok_barang FROM barang 
                        WHERE id_barang =?");
$cekstok->execute([$id_barang]);
$rst = $cekstok->fetch(PDO::FETCH_ASSOC);

$stok_barang = $rst['stok_barang'];
$stokakhir = $stok_barang + $qty_dtrkasir;

// Update stok barang
$stmt_update_barang = $db->prepare("UPDATE barang SET stok_barang =? 
                                    WHERE id_barang =?");
$stmt_update_barang->execute([$stokakhir, $id_barang]);

// Insert into history
$stmt_insert_hist = $db->prepare("INSERT INTO trkasir_detail_hist (
                                            kd_trkasir,
                                            id_barang,
                                            kd_barang,
                                            nmbrg_dtrkasir,
                                            qty_dtrkasir,
                                            sat_dtrkasir,
                                            hrgjual_dtrkasir,
                                            disc,
                                            hrgttl_dtrkasir,
                                            no_batch,
                                            exp_date,
                                            waktu,
                                            tipe,
                                            komisi,
                                            idadmin
                                            ) 
                                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
$stmt_insert_hist->execute([$r['kd_trkasir'],$r['id_barang'],$r['kd_barang'],$r['nmbrg_dtrkasir'],$r['qty_dtrkasir'],
                            $r['sat_dtrkasir'],$r['hrgjual_dtrkasir'],$r['disc'],
                            $r['hrgttl_dtrkasir'],$r['no_batch'],$r['exp_date'],$r['waktu'],$r['tipe'],$r['komisi'],$r['idadmin']]);
// Hapus detail
$stmt_del_trkasirdetail = $db->prepare("DELETE FROM trkasir_detail WHERE id_dtrkasir = '$id_dtrkasir'");
$stmt_del_trkasirdetail->execute();

$stmt_del_komisi = $db->prepare("DELETE FROM komisi_pegawai WHERE id_dtrkasir = '$id_dtrkasir'");
$stmt_del_komisi->execute();

$stmt_del_batch = $db->prepare("DELETE FROM batch WHERE kd_transaksi = '$kd_trbmasuk' AND no_batch='$no_batch' AND status = 'keluar'");
$stmt_del_batch->execute();
echo $stokakhir;
?>

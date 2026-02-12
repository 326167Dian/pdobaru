<?php
session_start();
include "../../../configurasi/koneksi.php";
require('../../assets/pdf/fpdf.php');
include "../../../configurasi/fungsi_indotgl.php";
include "../../../configurasi/fungsi_rupiah.php";


$tgl_awal = $_POST['tgl_awal'];
$tgl_akhir = $_POST['tgl_akhir'];

if ($_POST['shift']>0){
	$shift = $_POST['shift'];}
else {
	$shift=("1,2,3");
}
$pdf = new FPDF("P","cm","A4");

$pdf->SetMargins(1,1,1);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25.5,0.7,"LAPORAN LABA PENJUALAN",0,10,'L');
$pdf->SetFont('Arial','',9);
$pdf->Cell(5.5,0.5,"Tanggal Cetak : ".date('d-m-Y H:i:s'),0,0,'L');
$pdf->Cell(5,0.5,"Dicetak Oleh : ".$_SESSION['namalengkap'],0,1,'L');
$pdf->Cell(5.5,0.5,"Periode : ".tgl_indo($tgl_awal)." - ".tgl_indo($tgl_akhir),0,0,'L');


$pdf->ln(0.5);
$pdf->SetFont('Arial','',9);

$no=1;
$penjualan = $db->prepare("select * from trkasir where tgl_trkasir between '$tgl_awal' and '$tgl_akhir' and shift in ($shift) order by id_carabayar ");
$penjualan->execute();
while ($jual = $penjualan->fetch(PDO::FETCH_ASSOC)) {
	$cabay = $db->prepare("select * from carabayar where id_carabayar='$jual[id_carabayar]' ");
	$cabay->execute();
	$cba = $cabay->fetch(PDO::FETCH_ASSOC);
	//hitung angsuran

	$masuk=$jual['ttl_trkasir'];

	$pdf->Cell(3, 0.4, 'No', 0, 0, 'L');
	$pdf->Cell(0.5, 0.4, ': ', 0, 0, 'L');
	$pdf->Cell(5, 0.4, $no, 0, 1, 'L');

	$pdf->Cell(3, 0.4, 'Nama Pelanggan', 0, 0, 'L');
	$pdf->Cell(0.5, 0.4, ': ', 0, 0, 'L');
	$pdf->Cell(5, 0.4, $jual['nm_pelanggan'], 0, 1, 'L');

	$pdf->Cell(3, 0.4, 'Kode Transaksi', 0,0 , 'L');
	$pdf->Cell(0.5, 0.4, ': ', 0, 0, 'L');
	$pdf->Cell(5, 0.4, $jual['kd_trkasir'], 0, 1, 'L');

	$pdf->Cell(3, 0.4, 'Metode Bayar', 0,0 , 'L');
	$pdf->Cell(0.5, 0.4, ': ', 0, 0, 'L');
	$pdf->Cell(5, 0.4, $cba['nm_carabayar'], 0, 1, 'L');

	$pdf->Cell(3, 0.4, 'Total Transaksi', 0,0 , 'L');
	$pdf->Cell(0.5, 0.4, ': ', 0, 0, 'L');
	$pdf->Cell(5, 0.4, format_rupiah($masuk), 0, 1, 'L');

	$detail = $db->prepare("select * from trkasir_detail where kd_trkasir='$jual[kd_trkasir]' order by nmbrg_dtrkasir ");
	$detail->execute();
	$no2=1;

	$pdf->Cell(1, 0.7, 'No', 1, 0, 'C');
	$pdf->Cell(9.5, 0.7, 'Nama Barang', 1, 0, 'C');
	$pdf->Cell(1, 0.7, 'Jml', 1, 0, 'C');
	$pdf->Cell(1.5, 0.7, 'Sat', 1, 0, 'C');
	$pdf->Cell(2, 0.7, 'Harga', 1, 0, 'C');
	$pdf->Cell(2, 0.7, 'Modal', 1, 0, 'C');
	$pdf->Cell(2, 0.7, 'Sub Total', 1, 1, 'C');
	$pdf->SetFont('Arial','',8);



	while($det=$detail->fetch(PDO::FETCH_ASSOC)){
		$hrgawl = $det['hrgjual_dtrkasir'] + $det['disc'];
		$mdl = $db->prepare("select hrgsat_barang from barang where id_barang='$det[id_barang]' ");
		$mdl->execute();
		$mdl1 = $mdl->fetch(PDO::FETCH_ASSOC);
		$modal = $mdl1['hrgsat_barang'];


		$pdf->Cell(1, 0.6,$no2, 1, 0, 'C');
		$pdf->Cell(9.5, 0.6,$det['nmbrg_dtrkasir'], 1, 0, 'L');
		$pdf->Cell(1, 0.6, $det['qty_dtrkasir'], 1, 0, 'C');
		$pdf->Cell(1.5, 0.6, $det['sat_dtrkasir'], 1, 0, 'C');
		$pdf->Cell(2, 0.6, format_rupiah($hrgawl), 1, 0, 'R');
		$pdf->Cell(2, 0.6, format_rupiah($modal), 1, 0, 'R');
		$pdf->Cell(2, 0.6, format_rupiah($det['profit']), 1, 1, 'R');
		$no2++;

	}
	$sub=$db->prepare("select sum(profit) as total,
								  sum(hrgttl_dtrkasir) as tx
							from trkasir_detail where kd_trkasir='$jual[kd_trkasir]' ");
	$sub->execute();						
	$subt = $sub->fetch(PDO::FETCH_ASSOC);
	$sttl = format_rupiah($subt['total']);
	$grand = $db->prepare("select ttl_trkasir from trkasir where kd_trkasir='$jual[kd_trkasir]' ");
	$grand->execute();
	$akhir = $grand->fetch(PDO::FETCH_ASSOC);
	$discfaktur =  ($subt['tx']-$akhir['ttl_trkasir']);
	$ttllaba = $subt['total'] - ($subt['tx']-$akhir['ttl_trkasir']);;

	$pdf->Cell(17, 0.6,'Sub Total Profit', 1, 0, 'R');
	$pdf->Cell(2, 0.6,$sttl, 1, 1, 'R');
	$pdf->Cell(17, 0.6,'Diskon Transaksi', 1, 0, 'R');
	$pdf->Cell(2, 0.6,format_rupiah($discfaktur), 1, 1, 'R');
	$pdf->Cell(17, 0.6,'Subtotal Laba', 1, 0, 'R');
	$pdf->Cell(2, 0.6,format_rupiah($ttllaba), 1, 1, 'R');
	$pdf->Cell(2, 0.6,'', 0, 1, 'R');

	$no++;
}



$finish = $db->prepare("select 
							sum(ttl_trkasir) as nilai
					from trkasir where shift in ($shift) and tgl_trkasir between '$tgl_awal' and '$tgl_akhir'  ");
$finish->execute();
$akh = $finish->fetch(PDO::FETCH_ASSOC);
$subfinish1 = $db->prepare("select 
							sum(ttl_trkasir) as sisa														
					from trkasir where shift in ($shift) and tgl_trkasir between '$tgl_awal' and '$tgl_akhir' and id_carabayar=2  ");
$subfinish1->execute();
$subakh1 = $subfinish1->fetch(PDO::FETCH_ASSOC);

$subfinish2 = $db->prepare("select 
							sum(ttl_trkasir) as sisa1														
					from trkasir where shift in ($shift) and tgl_trkasir between '$tgl_awal' and '$tgl_akhir' and id_carabayar=3  ");
$subfinish2->execute();
$subakh2 = $subfinish2->fetch(PDO::FETCH_ASSOC);

$subfinish3 = $db->prepare("select 
							sum(ttl_trkasir) as sisa3														
					from trkasir where shift in ($shift) and tgl_trkasir between '$tgl_awal' and '$tgl_akhir' and id_carabayar=1  ");
$subfinish3->execute();
$subakh3 = $subfinish3->fetch(PDO::FETCH_ASSOC);

$subfinish4 = $db->prepare("select 
							sum(profit) as sisa4														
					from trkasir_detail join trkasir on(trkasir_detail.kd_trkasir=trkasir.kd_trkasir) 
					where shift in ($shift) and trkasir.tgl_trkasir between '$tgl_awal' and '$tgl_akhir'  ");
$subfinish4->execute();
$subakh4 = $subfinish4->fetch(PDO::FETCH_ASSOC);

$sub=$db->prepare("select sum(hrgttl_dtrkasir) as tx
							from trkasir_detail join trkasir on(trkasir_detail.kd_trkasir=trkasir.kd_trkasir)
							where shift in ($shift) and trkasir.tgl_trkasir between '$tgl_awal' and '$tgl_akhir' ");
$sub->execute();
$subt = $sub->fetch(PDO::FETCH_ASSOC);

$totlaba = $subakh4['sisa4'] - ($subt['tx'] - $akh['nilai'] );

$pdf->SetFont('Arial','B',14);

$pdf->Cell(6, 0.7, '', 0, 0, 'L');
$pdf->Cell(0.5, 0.7, '', 0, 0, 'L');
$pdf->Cell(5, 0.7, '', 0, 1, 'R');

$pdf->Cell(6, 0.7, 'Total Nilai transaksi', 0, 0, 'L');
$pdf->Cell(0.5, 0.7, ': Rp. ', 0, 0, 'L');
$pdf->Cell(5, 0.7, format_rupiah($akh['nilai']), 0, 1, 'R');

$pdf->SetFont('Arial','B',14);
$pdf->Cell(6, 0.7, 'Total Laba', 0, 0, 'L');
$pdf->Cell(0.5, 0.7, ': Rp. ', 0, 0, 'L');
$pdf->Cell(5, 0.7, format_rupiah($totlaba), 0, 1, 'R');

// $pdf->Cell(6, 0.7, 'Pembayaran Tunai', 0,0 , 'L');
// $pdf->Cell(0.5, 0.7, ': Rp. ', 0, 0, 'L');
// $pdf->Cell(5, 0.7, format_rupiah($subakh3['sisa3']), 0, 1, 'R');

// $pdf->Cell(6, 0.7, 'Pembayaran Transfer', 0,0 , 'L');
// $pdf->Cell(0.5, 0.7, ': Rp. ', 0, 0, 'L');
// $pdf->Cell(5, 0.7, format_rupiah($subakh1['sisa']), 0, 1, 'R');

// $pdf->Cell(6, 0.7, 'Pembayaran Tempo', 0,0 , 'L');
// $pdf->Cell(0.5, 0.7, ': Rp.', 0, 0, 'L');
// $pdf->Cell(5, 0.7, format_rupiah($subakh2['sisa1']), 0, 1, 'R');
$pdf->Output("Laporan_data_barang.pdf","I");


?>


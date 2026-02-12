<?php
include "../../../configurasi/koneksi.php";

$key = $_POST['nm_barang'];
$nmbrg = explode("(", $key);
$key1 = trim($nmbrg[0]);
$key1 = preg_replace('/\s+/', ' ', $key1);
$ubah = $db->prepare("SELECT * FROM barang WHERE LOWER(REPLACE(REPLACE(REPLACE(nm_barang, '  ', ' '), '  ', ' '), '  ', ' ')) = LOWER(?)");
$ubah->execute([$key1]);


$json = [];
while($re = $ubah->fetch(PDO::FETCH_ASSOC)){
$json[] = array(
            'id_barang'=> $re['id_barang'],
			'nm_barang'=> $re['nm_barang'],
            'jenisobat'=> $re['jenisobat'],
			'stok_barang'=> $re['stok_barang'],
			'sat_barang'=> $re['sat_barang'],
// 			'indikasi'=> $re['indikasi'],
			'hrgjual_barang'=> $re['hrgjual_barang'],
            'hrgjual_barang1'=> $re['hrgjual_barang1'],
            'hrgjual_barang2'=> $re['hrgjual_barang2'],
            'hrgjual_barang3'=> $re['hrgjual_barang3'],
            'hrgjual_barang4'=> $re['hrgjual_barang4'],
            'hrgjual_barang5'=> $re['hrgjual_barang5'],
			'kd_barang'=> $re['kd_barang'],
			'komisi'=> $re['komisi'],
			);
}
echo json_encode($json);
?>
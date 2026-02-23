<?php
include "../../../configurasi/koneksi.php";

header('Content-Type: application/json');

if (!isset($_POST['nm_barang']) || trim($_POST['nm_barang']) === '') {
	echo json_encode([]);
	exit;
}

$key = $_POST['nm_barang'];
$nmbrg = explode("(", $key);
$key1 = trim($nmbrg[0]);
$key1 = preg_replace('/\s+/', ' ', $key1);
$ubah = $db->prepare("SELECT * FROM barang WHERE LOWER(REPLACE(REPLACE(REPLACE(nm_barang, '  ', ' '), '  ', ' '), '  ', ' ')) = LOWER(?)");
$ubah->execute([$key1]);


$json = [];
while($re = $ubah->fetch(PDO::FETCH_ASSOC)){
$harga_dasar = isset($re['hrgjual_barang']) ? $re['hrgjual_barang'] : 0;
$harga_level1 = isset($re['hrgjual_barang1']) ? $re['hrgjual_barang1'] : $harga_dasar;
$harga_level2 = isset($re['hrgjual_barang2']) ? $re['hrgjual_barang2'] : $harga_level1;
$harga_level3 = isset($re['hrgjual_barang3']) ? $re['hrgjual_barang3'] : $harga_level2;
$harga_level4 = isset($re['hrgjual_barang4']) ? $re['hrgjual_barang4'] : $harga_level3;
$harga_level5 = isset($re['hrgjual_barang5']) ? $re['hrgjual_barang5'] : $harga_level4;
$json[] = array(
            'id_barang'=> $re['id_barang'],
			'nm_barang'=> $re['nm_barang'],
            'jenisobat'=> $re['jenisobat'],
			'stok_barang'=> $re['stok_barang'],
			'sat_barang'=> $re['sat_barang'],
// 			'indikasi'=> $re['indikasi'],
			'hrgjual_barang'=> $harga_dasar,
			'hrgjual_barang1'=> $harga_level1,
			'hrgjual_barang2'=> $harga_level2,
			'hrgjual_barang3'=> $harga_level3,
			'hrgjual_barang4'=> $harga_level4,
			'hrgjual_barang5'=> $harga_level5,
			'kd_barang'=> $re['kd_barang'],
			'komisi'=> $re['komisi'],
			);
}
echo json_encode($json);
?>
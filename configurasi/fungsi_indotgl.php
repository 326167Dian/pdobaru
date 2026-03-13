<?php
include 'koneksi.php';

	function tgl_indo($tgl){
			$tanggal = substr($tgl,8,2);
			$bulan = getBulan(substr($tgl,5,2));
			$tahun = substr($tgl,0,4);
			return $tanggal.' '.$bulan.' '.$tahun;		 
	}	

	function getBulan($bln){
				switch ($bln){
					case 1: 
						return "Januari";
						break;
					case 2:
						return "Februari";
						break;
					case 3:
						return "Maret";
						break;
					case 4:
						return "April";
						break;
					case 5:
						return "Mei";
						break;
					case 6:
						return "Juni";
						break;
					case 7:
						return "Juli";
						break;
					case 8:
						return "Agustus";
						break;
					case 9:
						return "September";
						break;
					case 10:
						return "Oktober";
						break;
					case 11:
						return "November";
						break;
					case 12:
						return "Desember";
						break;
				}
			} 
	
	function generate_kode(){
        global $db;
        $kode = rand(100000, 999999);
        return $kode;
    }
    
    function get_kode(){
        global $db;
        
        // $kode = "";
        // while($kode == ""){
        //     $kd_barang = generate_kode();
        //     $stmt = $db->prepare("SELECT kd_barang FROM barang WHERE kd_barang = ?");
        //     $stmt->execute([$kd_barang]);
        //     $cek = $stmt->rowCount();
            
        //     if ($cek > 0) {
        //         $kode = "";
        //     } else {
        //         $kode = $kd_barang;
        //     }    
        // }
        
        $year  = date("y", time());
        $month = date("m", time());
        
        $kd_barang = $year.$month;
        $stmt = $db->prepare("SELECT kd_barang, RIGHT(kd_barang, 3) AS kode_int FROM barang WHERE LEFT(kd_barang, 4) LIKE ? ORDER BY kd_barang DESC LIMIT 1");
        $stmt->execute(['%'.$kd_barang.'%']);
        $cek = $stmt->rowCount();
            
        if ($cek > 0) {
            $r = $stmt->fetch(PDO::FETCH_ASSOC);
            $kode = $kd_barang.str_pad($r['kode_int'] + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $kode = $kd_barang.'001';
        }
        return $kode;
    }
?>

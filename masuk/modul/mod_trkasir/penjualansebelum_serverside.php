<?php
include_once '../../../configurasi/koneksi.php';

      
if ($_GET['action'] == "table_data") {

    
    $columns = array(
        0 => 'id_trkasir',
        1 => 'kd_trkasir',
        2 => 'tgl_trkasir',
        3 => 'nm_pelanggan',
        4 => 'kodetx',
        5 => 'nm_carabayar',
        6 => 'ttl_trkasir',
        7 => 'id_trkasir',
        
    );
    $aksi="modul/mod_trkasir/aksi_trkasir.php";
    $tgl_awal = date('Y-m-d', time());
    $tgl_kemarin = date('Y-m-d', strtotime('-1 days', strtotime( $tgl_awal)));
    $tgl_akhir = date('Y-m-d', strtotime('-360 days', strtotime( $tgl_awal)));

    $querycount = $db->prepare("SELECT count(id_trkasir) as jumlah FROM trkasir WHERE tgl_trkasir BETWEEN '$tgl_akhir' AND '$tgl_kemarin'");
    $querycount->execute();
    $datacount = $querycount->fetch(PDO::FETCH_ASSOC);

    $totalData = $datacount['jumlah'];

    $totalFiltered = $totalData;

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];

    if (empty($_POST['search']['value'])) {
        $query = $db->prepare("SELECT * FROM trkasir a 
            JOIN carabayar b ON (a.id_carabayar=b.id_carabayar) 
            WHERE a.tgl_trkasir BETWEEN '$tgl_akhir' AND '$tgl_kemarin'
            ORDER BY a.id_trkasir DESC LIMIT $limit OFFSET $start");
    } else {
        $search = $_POST['search']['value'];
        $query = $db->prepare("SELECT * FROM trkasir a 
            JOIN carabayar b ON a.id_carabayar = b.id_carabayar
            WHERE a.tgl_trkasir BETWEEN '$tgl_akhir' AND '$tgl_kemarin' 
                        AND (a.kd_trkasir LIKE '%$search%'
                        OR a.tgl_trkasir LIKE '%$search%'
                        OR a.nm_pelanggan LIKE '%$search%'
                        OR a.ttl_trkasir LIKE '%$search%'
                        OR a.kodetx LIKE '%$search%'
                        OR b.nm_carabayar LIKE '%$search%') 
            ORDER BY a.id_trkasir DESC LIMIT $limit OFFSET $start");

        $querycount = $db->prepare("SELECT count(id_trkasir) as jumlah 
            FROM trkasir a 
            JOIN carabayar b ON a.id_carabayar = b.id_carabayar
            WHERE a.tgl_trkasir BETWEEN '$tgl_akhir' AND '$tgl_kemarin'
                        AND (a.kd_trkasir LIKE '%$search%'
                        OR a.tgl_trkasir LIKE '%$search%'
                        OR a.nm_pelanggan LIKE '%$search%'
                        OR a.ttl_trkasir LIKE '%$search%'
                        OR a.kodetx LIKE '%$search%'
                        OR b.nm_carabayar LIKE '%$search%') ");

        $querycount->execute();
        $datacount = $querycount->fetch(PDO::FETCH_ASSOC);
        $totalFiltered = $datacount['jumlah'];
    }

    $data = array();
    if (!empty($query)) {
        $no = $start + 1;
        $query->execute();
        while ($value = $query->fetch(PDO::FETCH_ASSOC)) {
            $nestedData['no'] = $no;
            $nestedData['kd_trkasir'] = $value['kd_trkasir'];
            $nestedData['tgl_trkasir'] = $value['tgl_trkasir'];
            $nestedData['nm_pelanggan'] = $value['nm_pelanggan'];
            $nestedData['kodetx'] = $value['kodetx'];
            $nestedData['nm_carabayar'] = $value['nm_carabayar'];
            $nestedData['ttl_trkasir'] = $value['ttl_trkasir'];
            $nestedData['pilih'] = "<a href='?module=trkasir&act=ubah&id=$value[id_trkasir]' title='EDIT' class='glyphicon glyphicon-pencil'>&nbsp</a>
            <a class='glyphicon glyphicon-print' onclick='window.open(\"modul/mod_laporan/struk.php?kd_trkasir=$value[kd_trkasir]\",\"nama window\",\"width=500,height=600,toolbar=no,location=no,directories=no,status=no,menubar=no, scrollbars=no,resizable=yes,copyhistory=no\")'>&nbsp</a>
            <a href='javascript:confirmdelete(\"$aksi?module=trkasir&act=hapus&id=$value[id_trkasir]\")' title='HAPUS' class='glyphicon glyphicon-remove'>&nbsp</a>";
            
            $data[] = $nestedData;
            $no++;
        }
    }

    $json_data = [
        "draw"            => intval($_POST['draw']),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $data
    ];

    echo json_encode($json_data);
}

<?php
session_start();
include_once '../../../configurasi/koneksi.php';

      
if ($_GET['action'] == "table_data") {

    
    $columns = array(
        0 => 'id_trkasir',
        1 => 'kd_trkasir',
        2 => 'petugas',
        3 => 'shift',
        4 => 'jenistx',
        5 => 'tgl_trkasir',
        6 => 'nm_pelanggan',
        7 => 'kodetx',
        8 => 'nm_carabayar',
        9 => 'ttl_trkasir',
        10 => 'id_trkasir',
        
    );
    $aksi="modul/mod_trkasir/aksi_trkasir.php";
    $tgl_awal = date('Y-m-d');
    

    $querycount = $db->query("SELECT count(id_trkasir) as jumlah FROM trkasir WHERE tgl_trkasir='$tgl_awal' ");
    $datacount = $querycount->fetch_array();

    $totalData = $datacount['jumlah'];

    $totalFiltered = $totalData;

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];

    if (empty($_POST['search']['value'])) {
        $query = $db->query("SELECT * FROM trkasir a 
            JOIN carabayar b ON (a.id_carabayar=b.id_carabayar) 
            WHERE a.tgl_trkasir='$tgl_awal'
            ORDER BY a.id_trkasir DESC LIMIT $limit OFFSET $start");
    } else {
        $search = $_POST['search']['value'];
        $query = $db->query("SELECT * FROM trkasir a 
            JOIN carabayar b ON a.id_carabayar = b.id_carabayar
            WHERE a.tgl_trkasir='$tgl_awal'
                        AND (a.kd_trkasir LIKE '%$search%'
                        OR a.shift LIKE '%$search%'
                        OR a.jenistx LIKE '%$search%'
                        OR a.petugas LIKE '%$search%'
                        OR a.tgl_trkasir LIKE '%$search%'
                        OR a.ttl_trkasir LIKE '%$search%'
                        OR a.nm_pelanggan LIKE '%$search%'
                        OR a.kodetx LIKE '%$search%'
                        OR b.nm_carabayar LIKE '%$search%') 
            ORDER BY a.id_trkasir DESC LIMIT $limit OFFSET $start");

        $querycount = $db->query("SELECT count(id_trkasir) as jumlah 
            FROM trkasir a 
            JOIN carabayar b ON a.id_carabayar = b.id_carabayar
            WHERE a.tgl_trkasir='$tgl_awal'
                        AND (a.kd_trkasir LIKE '%$search%'
                        OR a.shift LIKE '%$search%'
                        OR a.jenistx LIKE '%$search%'
                        OR a.petugas LIKE '%$search%'
                        OR a.tgl_trkasir LIKE '%$search%'
                        OR a.tgl_trkasir LIKE '%$search%'
                        OR a.nm_pelanggan LIKE '%$search%'
                        OR a.kodetx LIKE '%$search%'
                        OR b.nm_carabayar LIKE '%$search%')");

        $datacount = $querycount->fetch_array();
        $totalFiltered = $datacount['jumlah'];
    }

    $data = array();
    if (!empty($query)) {
        $no = $start + 1;
        while ($value = $query->fetch_array()) {
            $nestedData['no'] = $no;
            $nestedData['kd_trkasir'] = $value['kd_trkasir'];
            $nestedData['shift'] = $value['shift'];
            $nestedData['jenistx'] = $value['jenistx'];
            $nestedData['petugas'] = $value['petugas'];
            $nestedData['tgl_trkasir'] = $value['tgl_trkasir'];
            $nestedData['nm_pelanggan'] = $value['nm_pelanggan'];
            $nestedData['kodetx'] = $value['kodetx'];
            $nestedData['nm_carabayar'] = $value['nm_carabayar'];
            $nestedData['ttl_trkasir'] = $value['ttl_trkasir'];
            // $nestedData['pilih'] = "
            // <a class='btn btn-primary btn-xs' onclick='window.open(\"modul/mod_laporan/struk.php?kd_trkasir=$value[kd_trkasir]\",\"nama window\",\"width=500,height=600,toolbar=no,location=no,directories=no,status=no,menubar=no, scrollbars=no,resizable=yes,copyhistory=no\")'>PRINT</a><br>            
            // <a href='modul/mod_laporan/kwitansi.php?kd_trkasir=$value[kd_trkasir]' target='_blank' title='KWITANSI' class='btn btn-warning btn-xs'>KWITANSI</a><br>
            // <a href='modul/mod_laporan/strukresep.php?kd_trkasir=$value[kd_trkasir]' target='_blank' title='RESEP' class='btn btn-success btn-xs'>RESEP</a><br>
           
            // ";
            
            if($_SESSION['level'] == 'pemilik'){
                
                $nestedData['pilih'] = '<div class="dropdown">
                                          <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" >
                                            Action
                                            <i class="fa fa-caret-down"></i>
                                          </button>
                                          <div class="dropdown-menu">
                                            <a href="?module=trkasir&act=ubah&id='.$value['id_trkasir'].'" title="EDIT" class="btn btn-info btn-xs" style="width:50%; margin:0 5 5 5">EDIT</a>
                                            <a href="javascript:confirmdelete(\''.$aksi.'?module=trkasir&act=hapus&id='.$value['id_trkasir'].'\')" title="HAPUS" class="btn btn-danger btn-xs" style="width:50%; margin:0 3 3 3">HAPUS</a>
                                            <a class="btn btn-primary btn-xs" onclick="window.open(\'modul/mod_laporan/struk.php?kd_trkasir='.$value['kd_trkasir'].'\',\'nama window\',\'width=500,height=600,toolbar=no,location=no,directories=no,status=no,menubar=no, scrollbars=no,resizable=yes,copyhistory=no\')" style="width:50%; margin:0 5 5 5">PRINT</a>
                                            <a href="modul/mod_laporan/kwitansi.php?kd_trkasir='.$value['kd_trkasir'].'" target="_blank" title="KWITANSI" class="btn btn-warning btn-xs" style="width:50%; margin:0 3 3 3">KWITANSI</a>
                                            <a href="modul/mod_laporan/invoice.php?kd_trkasir='.$value['kd_trkasir'].'" target="_blank" title="INVOICE" class="btn btn-primary btn-xs" style="width:50%; margin:0 3 3 3">INVOICE</a>
                                            <a href="modul/mod_laporan/strukresep.php?kd_trkasir='.$value['kd_trkasir'].'" target="_blank" title="RESEP" class="btn btn-success btn-xs" style="width:50%; margin:0 3  5">RESEP</a>
                                            
                                            
                                          </div>
                                        </div>';
            }else{
                $nestedData['pilih'] = '<div class="dropdown">
                                          <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" >
                                            Action
                                            <i class="fa fa-caret-down"></i>
                                          </button>
                                          <div class="dropdown-menu">
                                            <a class="btn btn-primary btn-xs" onclick="window.open(\'modul/mod_laporan/struk.php?kd_trkasir='.$value['kd_trkasir'].'\',\'nama window\',\'width=500,height=600,toolbar=no,location=no,directories=no,status=no,menubar=no, scrollbars=no,resizable=yes,copyhistory=no\')" style="width:50%; margin:0 5 5 5">PRINT</a>
                                            <a href="modul/mod_laporan/kwitansi.php?kd_trkasir='.$value['kd_trkasir'].'" target="_blank" title="KWITANSI" class="btn btn-warning btn-xs" style="width:50%; margin:0 5 5 5">KWITANSI</a>
                                            <a href="modul/mod_laporan/invoice.php?kd_trkasir='.$value['kd_trkasir'].'" target="_blank" title="INVOICE" class="btn btn-primary btn-xs" style="width:50%; margin:0 3 3 3">INVOICE</a>
                                            <a href="modul/mod_laporan/strukresep.php?kd_trkasir='.$value['kd_trkasir'].'" target="_blank" title="RESEP" class="btn btn-success btn-xs" style="width:50%; margin:0 5 5 5">RESEP</a>
                                            
                                            
                                          </div>
                                        </div>';
            }
            
            
            
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
   // <a href='modul/mod_laporan/invoice.php?kd_trkasir=$value[kd_trkasir]' target='_blank' title='INVOICE' class='btn btn-success btn-xs'>INVOICE</a>
}

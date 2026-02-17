<?php
include_once '../../../configurasi/koneksi.php';
include_once '../../../configurasi/fungsi_rupiah.php';

$aksi = "modul/mod_barang/aksi_barang.php";

if ($_GET['action'] == "table_data") {

    $columns = array(
        0 => 'id_barang',
        1 => 'nm_barang',
        2 => 'updated_by',
        3 => 'hrgjual_barang',
        4 => 'zataktif',
        5 => 'indikasi',
        6 => 'id_barang'
    );

    $querycount = $db->query("SELECT count(id_barang) as jumlah FROM barang");
    $datacount = $querycount->fetch(PDO::FETCH_ASSOC);

    $totalData = $datacount['jumlah'];

    $totalFiltered = $totalData;

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];

    if (empty($_POST['search']['value'])) {
        $query = $db->query("SELECT id_barang,
                                    kd_barang,
                                    nm_barang,
                                    stok_barang,
                                    sat_barang,
                                    jenisobat,
                                    hrgsat_barang,
                                    hrgjual_barang,
                                    hrgjual_barang1,
                                    hrgjual_barang2,
                                    zataktif,
                                    indikasi,
                                    updated_by
            FROM barang ORDER BY $order $dir LIMIT $limit OFFSET $start");
    } else {
        $search = $_POST['search']['value'];
        $query = $db->query("SELECT id_barang,
                                    kd_barang,
                                    nm_barang,
                                    stok_barang,
                                    sat_barang,
                                    jenisobat,
                                    hrgsat_barang,
                                    hrgjual_barang,
                                    hrgjual_barang1,
                                    hrgjual_barang2,
                                    zataktif,
                                    indikasi,
                                    updated_by 
            FROM barang WHERE kd_barang LIKE '%$search%' 
                        OR nm_barang LIKE '%$search%'
                        OR stok_barang LIKE '%$search%'
                        OR sat_barang LIKE '%$search%'
                        OR updated_by LIKE '%$search%'
                        OR hrgsat_barang LIKE '%$search%'
                        OR hrgjual_barang LIKE '%$search%'
                        OR zataktif LIKE '%$search%'
                        OR indikasi LIKE '%$search%' 
            ORDER BY $order $dir LIMIT $limit OFFSET $start");

        $querycount = $db->query("SELECT count(id_barang) as jumlah 
            FROM barang WHERE kd_barang LIKE '%$search%' 
                        OR nm_barang LIKE '%$search%'
                        OR stok_barang LIKE '%$search%'
                        OR sat_barang LIKE '%$search%'
                        OR updated_by LIKE '%$search%'
                        OR hrgsat_barang LIKE '%$search%'
                        OR hrgjual_barang LIKE '%$search%'
                        OR zataktif LIKE '%$search%'
                        OR indikasi LIKE '%$search%'");

        $datacount = $querycount->fetch(PDO::FETCH_ASSOC);
        $totalFiltered = $datacount['jumlah'];
    }

    $data = array();
    if (!empty($query)) {
        $no = $start + 1;
        while ($value = $query->fetch(PDO::FETCH_ASSOC)) {
            $nestedData['no']               = $no;
            $nestedData['nm_barang']        = $value['nm_barang'] . ' <span style="color: #666;">(' . $value['kd_barang'] . ')</span>' .
                                              ' - ' . $value['stok_barang'] . '<br><span style="color: #666;">(' . $value['sat_barang'] . ')</span>';
            $nestedData['updated_by']       = $value['updated_by'];
            $nestedData['hrgsat_barang']    = $value['hrgsat_barang'];
            $nestedData['hrgjual_barang_reguler']   = $value['hrgjual_barang'];
            $nestedData['hrgjual_barang']   = '<table><tr>
                                                <td><b>(R)</b> </td><td>'.format_rupiah($value['hrgjual_barang']).'</td>
                                            </tr>
                                            <tr>
                                                <td><b>(Re)</b> </td><td>'.format_rupiah($value['hrgjual_barang1']).'</td>
                                            </tr>
                                            <tr>
                                                <td><b>(Mp)</b> </td><td>'.format_rupiah($value['hrgjual_barang2']).'</td>
                                            </tr></table>';
            
            // Menampilkan zataktif dengan nama admin di dalam kurung
            $zataktif_display = $value['zataktif'];
            if (!empty($value['updated_by'])) {
                $zataktif_display .= ' <span style="color: #999; font-size: 0.9em;">(' . $value['updated_by'] . ')</span>';
            }
            $nestedData['zataktif']         = $zataktif_display;
            $nestedData['indikasi']         = $value['indikasi'];
                        $nestedData['aksi']             = "<div class='dropdown'>
    <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenuAksi" . $value['id_barang'] . "' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
        action
        <span class='caret'></span>
    </button>
    <ul class='dropdown-menu' aria-labelledby='dropdownMenuAksi" . $value['id_barang'] . "'>
        <li style='background-color:yellow;width:50%'><a href='?module=barang&act=edit&id=" . $value['id_barang'] . "'>EDIT</a></li>
        <li style='background-color:aqua;width:50%'><a href='?module=barang&act=detail&id=" . $value['id_barang'] . "'>DETAIL</a></li>
        <li style='background-color:pink;width:50%;'><a href='?module=kartustok&act=view&id=" . $value['kd_barang'] . "'>KARTU STOK</a></li>
        <li style='background-color:red;width:50%;'><a href=javascript:confirmdelete('" . $aksi . "?module=barang&act=hapus&id=" . $value['id_barang'] . "')>HAPUS</a></li>
    </ul>
</div>";
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

<?php
include_once '../../../configurasi/koneksi.php';
include_once '../../../configurasi/fungsi_rupiah.php';

if ($_GET['action'] == "table_data") {

    $columns = array(
        0 => 'id_barang',
        1 => 'nm_barang',
        2 => 'stok_barang',
        3 => 'sat_barang',
        4 => 'jenisobat',
        5 => 'hrgjual_barang',
        6 => 'zataktif',
        7 => 'indikasi',
        8 => 'id_barang'
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
                                    indikasi
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
                                    indikasi 
            FROM barang WHERE kd_barang LIKE '%$search%' 
                        OR nm_barang LIKE '%$search%'
                        OR stok_barang LIKE '%$search%'
                        OR sat_barang LIKE '%$search%'
                        OR jenisobat LIKE '%$search%'
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
                        OR jenisobat LIKE '%$search%'
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
            $nestedData['nm_barang']        = $value['nm_barang'] . ' <span style="color: #666;">(' . $value['kd_barang'] . ')</span>';
            $nestedData['stok_barang']      = $value['stok_barang'];
            $nestedData['sat_barang']       = $value['sat_barang'];
            $nestedData['jenisobat']        = $value['jenisobat'];
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
                                            
            $nestedData['zataktif']         = $value['zataktif'];
            $nestedData['indikasi']         = $value['indikasi'];
            $nestedData['aksi']             = $value['id_barang'];
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

<?php
include_once '../../../configurasi/koneksi.php';
$aksi = "modul/mod_orders/aksi_orders.php";
if ($_GET['action'] == "table_data") {
    $columns = array(
        0 => 'id_trbmasuk',
        1 => 'kd_trbmasuk',
        2 => 'tgl_trbmasuk',
        3 => 'nm_supplier',
        4 => 'tlp_supplier',
        5 => 'ttl_trbmasuk',
        6 => 'dp_bayar',
        7 => 'sisa_bayar',
        8 => 'id_trbmasuk'
    );

    $querycount = $db->query("SELECT count(id_trbmasuk) as jumlah FROM orders WHERE id_resto = 'pesan'");
    $datacount = $querycount->fetch_array();

    $totalData = $datacount['jumlah'];

    $totalFiltered = $totalData;

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];

    if (empty($_POST['search']['value'])) {
        $query = $db->query("SELECT *
            FROM orders
            JOIN ordersdetail ON orders.kd_trbmasuk = ordersdetail.kd_trbmasuk 
            WHERE orders.id_resto = 'pesan'
            GROUP BY ordersdetail.kd_trbmasuk
            ORDER BY $order DESC LIMIT $limit OFFSET $start");
    } else {
        $search = $_POST['search']['value'];
        $query = $db->query("SELECT * 
            FROM orders
            JOIN ordersdetail ON orders.kd_trbmasuk = ordersdetail.kd_trbmasuk
            WHERE orders.id_resto = 'pesan'
                        AND kd_trbmasuk LIKE '%$search%'
                        OR tgl_trbmasuk LIKE '%$search%'
                        OR nm_supplier LIKE '%$search%'
                        OR tlp_supplier LIKE '%$search%'
                        OR ttl_trbmasuk LIKE '%$search%'
                        OR dp_bayar LIKE '%$search%'
                        OR sisa_bayar LIKE '%$search%'
            GROUP BY trbmasuk_detail.kd_trbmasuk 
            ORDER BY $order DESC LIMIT $limit OFFSET $start");

        $querycount = $db->query("SELECT count(id_barang) as jumlah 
            FROM trbmasuk
            JOIN trbmasuk_detail ON trbmasuk.kd_trbmasuk = trbmasuk_detail.kd_trbmasuk
            WHERE orders.id_resto = 'pesan'
                        AND kd_trbmasuk LIKE '%$search%'
                        OR tgl_trbmasuk LIKE '%$search%'
                        OR nm_supplier LIKE '%$search%'
                        OR tlp_supplier LIKE '%$search%'
                        OR ttl_trbmasuk LIKE '%$search%'
                        OR dp_bayar LIKE '%$search%'
                        OR sisa_bayar LIKE '%$search%'
            GROUP BY trbmasuk_detail.kd_trbmasuk");

        $datacount = $querycount->fetch_array();
        $totalFiltered = $datacount['jumlah'];
    }

    $data = array();
    // $totalNilaiBarang = 0;
    if (!empty($query)) {
        $no = $start + 1;
        while ($value = $query->fetch_array()) {
            // for column

            $nestedData['no'] = $no;
            $nestedData['kd_trbmasuk'] = $value['kd_trbmasuk'];
            $nestedData['tgl_trbmasuk'] = $value['tgl_trbmasuk'];
            $nestedData['nm_supplier'] = $value['nm_supplier'];
            $nestedData['tlp_supplier'] = $value['tlp_supplier'];
            $nestedData['ttl_trbmasuk'] = $value['ttl_trbmasuk'];
            $nestedData['dp_bayar'] = $value['dp_bayar'];
            $nestedData['sisa_bayar'] = $value['sisa_bayar'];
            $nestedData['aksi'] ="<div class='dropdown'>
  <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
    action
    <span class='caret'></span>
  </button>
  <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
    <li style='background-color:yellow;'><a href='?module=orders&act=ubah&id=$value[id_trbmasuk]'>EDIT</a></li>
    <li style='background-color:red;'><a href=javascript:confirmdelete('$aksi?module=orders&act=hapus&id=$value[id_trbmasuk]')>HAPUS</a></li>
    <li style='background-color:aqua;'><a href='modul/mod_orders/tampil_orders.php?id=$value[kd_trbmasuk]'>SP REGULER</a></li>
    <li style='background-color:#00FF00;'><a href='modul/mod_orders/tampil_prekursor.php?id=$value[kd_trbmasuk]'>SP PREKURSOR</a></li>
    <li style='background-color:#FF7F50;'><a href='modul/mod_orders/tampil_oot.php?id=$value[kd_trbmasuk]'>SP OOT</a></li>
  </ul>
</div>
            ";
            $data[] = $nestedData;
            $no++;
        }
    }


    $json_data = [
        "draw"              => intval($_POST['draw']),
        "recordsTotal"      => intval($totalData),
        "recordsFiltered"   => intval($totalFiltered),
        "data"              => $data
    ];

    echo json_encode($json_data);
}

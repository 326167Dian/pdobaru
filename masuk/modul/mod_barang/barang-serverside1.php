<?php
include "../../../configurasi/fungsi_rupiah.php";
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'barang';

$primaryKey = 'id_barang';

$no = 1;
$columns = array(
    array('db' => 'id_barang', 'dt' => 0),
    array('db' => 'kd_barang', 'dt' => 1),
    array('db' => 'nm_barang', 'dt' => 2),
    array('db' => 'stok_barang', 'dt' => 3),
    array('db' => 'sat_barang', 'dt' => 4),
    array('db' => 'jenisobat', 'dt' => 5),
    array(
        'db' => 'hrgsat_barang', 'dt' => 6,
        'formatter' => function ($d, $row) {
            return format_rupiah($d);
        }
    ),
    array(
        'db' => 'hrgjual_barang', 'dt' => 7,
        'formatter' => function ($d, $row) {
            return format_rupiah($d);
        }
    ),
    array('db' => 'indikasi', 'dt' => 8),
    array('db' => 'id_barang', 'dt' => 9),
    // array(
    //     'db'        => 'salary',
    //     'dt'        => 5,
    //     'formatter' => function ($d, $row) {
    //         return '$' . number_format($d);
    //     }
    // )
);

// SQL server connection information
// $sql_details = array(
//     'user' => '',
//     'pass' => '',
//     'db'   => '',
//     'host' => ''
//     // ,'charset' => 'utf8' // Depending on your PHP and MySQL config, you may need this
// );
include_once '../../../configurasi/conn.php';

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require('../../../configurasi/ssp.class.php');

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);

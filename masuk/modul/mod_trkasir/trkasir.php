<?php
// session_start(); // Sudah aktif di media_admin.php
if (empty($_SESSION['username']) and empty($_SESSION['passuser'])) {
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
} else {

    $aksi = "modul/mod_trkasir/aksi_trkasir.php";
    $aksi_trkasir = "masuk/modul/mod_trkasir/aksi_trkasir.php";
    switch ($_GET['act']) {
        // Tampil Penjualan
        default:
            $tgl_awal = date('Y-m-d');
            $tanpatgl = substr($tgl_awal, 0, 8);
            $awalbulan = $tanpatgl . '01';
            $tampil_trkasir = $db->prepare("SELECT * FROM trkasir where tgl_trkasir = ? order by id_trkasir desc");
            $tampil_trkasir->execute([$tgl_awal]);


            /*$tgl_awal = date('Y-m-d');
      $tgl_akhir = date('Y-m-d', strtotime('-7 days', strtotime( $tgl_awal)));
      $tampil_trkasir = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir  
        where tgl_trkasir between '$tgl_akhir' and '$tgl_awal'ORDER BY tgl_trkasir desc ") ;*/

?>


            <div class="box box-primary box-solid table-responsive">
                <div class="box-header with-border">
                    <h3 class="box-title">TRANSAKSI PENJUALAN HARI INI <a href="https://youtu.be/WZlfjM0tTn0" target="_blanks">(Tonton Tutorial)</a></h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
                </div>
                <div class="box-body table-responsive">
                    <a class='btn  btn-success btn-flat' href='?module=trkasir&act=tambah'>(F4)TAMBAH</a>
                    <td><a class='btn btn-danger btn-flat' href='modul/mod_trkasir/barangmacet.php' target='_blank'>DOWNLOAD STOK MACET</a></td>
                    <?php
                    $lupa = $_SESSION['level'];
                    if ($lupa == 'pemilik') {
                        echo " <a class='btn  btn-info btn-flat' href='?module=trkasir&act=cari2'>CARI BERDASARKAN NO TRANSAKSI</a>";
                    }
                    ?>
                    <?php
                    $global = $db->prepare("SELECT * FROM komisiglobal WHERE status='ON'");
                    $global->execute();
                    $global1 = $global->fetch(PDO::FETCH_ASSOC);
                    $kogo = $global1['nilai'] / 100;
                    $status = $global1['status'];


                    if ($status == 'ON') {
                        $petugas = $_SESSION['namalengkap'];
                        

                        $querykomisi = $db->prepare("SELECT SUM(ttl_trkasir) as total_komisi FROM trkasir where tgl_trkasir between ? and ? and petugas=? ");
                        $querykomisi->execute([$awalbulan, $tgl_awal, $petugas]);
                        $komisi = $querykomisi->fetch(PDO::FETCH_ASSOC);
                        $komisipetugas = $komisi['total_komisi'] * $kogo;
                        echo "<marquee><h4><b>Total Komisi Transaksi  $petugas Saat Ini = Rp " . format_rupiah($komisipetugas) . "</b></h4></marquee>";
                    }
                    ?>
                    <div></div>
                    <hr>
                    <!--<a  class ='btn  btn-warning  btn-flat' href='?module=trkasir&act=jualsebelumnya'>PENJUALAN SEBELUMNYA</a>
                    <small>* Pembayaran belum lunas</small> -->


                    <table id="rekap" class="table table-bordered table-striped">
                        <thead style="text-align:center; text-transform:uppercase;">
                            <tr>
                                <th >No</th>
                                <th style="text-align:center">No Transaksi</th>
                                <th>shift</th>
                                <th>Jenis Transaksi</th>
                                <th>petugas</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th style="text-align:center">No Pesanan</th>
                                <th>Cara Bayar</th>
                                <th>Total</th>
                                <th width="70">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <table id="example1" class="table table-bordered table-striped">
                        <?php
                        $tgl_awal = date('Y-m-d');      
                        
                            $total = "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir)as ttlskrg1                                                              
                                        FROM trkasir WHERE tgl_trkasir='$tgl_awal'";
                            $tunai = "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir)as ttlskrg2                                                              
                                        FROM trkasir WHERE tgl_trkasir='$tgl_awal' AND id_carabayar='1'";
                            $transfer = "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir)as ttlskrg3                                                              
                                        FROM trkasir WHERE tgl_trkasir='$tgl_awal' AND id_carabayar='2'";
                            $tempo = "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir)as ttlskrg4                                                              
                                        FROM trkasir WHERE tgl_trkasir='$tgl_awal' AND id_carabayar='3'";
                            $shift1 = "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir)as ttlshift1                                                              
                                        FROM trkasir WHERE tgl_trkasir='$tgl_awal' AND shift=1 ";
                            $shift1tunai = "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir)as ttlshift1tunai                                                              
                                        FROM trkasir WHERE tgl_trkasir='$tgl_awal' AND shift=1 and id_carabayar='1' ";
                            $shift1transfer = "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir)as ttlshift1transfer                                                              
                                        FROM trkasir WHERE tgl_trkasir='$tgl_awal' AND shift=1 and id_carabayar='2' ";
                            $shift1tempo = "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir)as ttlshift1tempo                                                              
                                        FROM trkasir WHERE tgl_trkasir='$tgl_awal' AND shift=1 and id_carabayar=3 ";
                            $shift2 = "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir)as ttlshift2                                                              
                                        FROM trkasir WHERE tgl_trkasir='$tgl_awal' AND shift=2 ";
                            $shift2tunai = "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir)as ttlshift2tunai                                                              
                                        FROM trkasir WHERE tgl_trkasir='$tgl_awal' AND shift=2 and id_carabayar=1";
                            $shift2transfer = "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir)as ttlshift2transfer                                                              
                                        FROM trkasir WHERE tgl_trkasir='$tgl_awal' AND shift=2 and id_carabayar=2";
                            $shift2tempo = "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir)as ttlshift2tempo                                                              
                                        FROM trkasir WHERE tgl_trkasir='$tgl_awal' AND shift=2 and id_carabayar=3";

                            $shift3 = "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir)as ttlshift3                                                              
                                        FROM trkasir WHERE tgl_trkasir = '$tgl_awal' AND shift=3 ";
                            $shift3tunai = "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir)as ttlshift3tunai                                                              
                                        FROM trkasir WHERE tgl_trkasir = '$tgl_awal' AND shift=3 and id_carabayar=1";
                            $shift3transfer = "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir)as ttlshift3transfer                                                              
                                        FROM trkasir WHERE tgl_trkasir = '$tgl_awal' AND shift=3 and id_carabayar=2";
                            $shift3tempo = "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir)as ttlshift3tempo                                                              
                                        FROM trkasir WHERE tgl_trkasir = '$tgl_awal' AND shift=3 and id_carabayar=3";

                            $query2 = $db->prepare($total);
                            $query2->execute();
                            $query3 = $db->prepare($tunai);
                            $query3->execute();
                            $query4 = $db->prepare($transfer);
                            $query4->execute();
                            $query5 = $db->prepare($tempo);
                            $query5->execute();
                            $query6 = $db->prepare($shift1);
                            $query6->execute();
                            $query6tunai = $db->prepare($shift1tunai);
                            $query6tunai->execute();
                            $query6transfer = $db->prepare($shift1transfer);
                            $query6transfer->execute();
                            $query6tempo = $db->prepare($shift1tempo);
                            $query6tempo->execute();
                            $query7 = $db->prepare($shift2);
                            $query7->execute();
                            $query7tunai = $db->prepare($shift2tunai);
                            $query7tunai->execute();
                            $query7transfer = $db->prepare($shift2transfer);
                            $query7transfer->execute();
                            $query7tempo = $db->prepare($shift2tempo);
                            $query7tempo->execute();

                            $query8 = $db->prepare($shift3);
                            $query8->execute();
                            $query8tunai = $db->prepare($shift3tunai);
                            $query8tunai->execute();
                            $query8transfer = $db->prepare($shift3transfer);
                            $query8transfer->execute();
                            $query8tempo = $db->prepare($shift3tempo);
                            $query8tempo->execute();

                            $r2 = $query2->fetch(PDO::FETCH_ASSOC);
                            $ttlskrg = $r2['ttlskrg1'];
                            $ttlskrg2 = format_rupiah($ttlskrg);

                            $r3 = $query3->fetch(PDO::FETCH_ASSOC);
                            $ttltunai = $r3['ttlskrg2'];
                            $ttltunai2 = format_rupiah($ttltunai);

                            $r4 = $query4->fetch(PDO::FETCH_ASSOC);
                            $ttltransfer = $r4['ttlskrg3'];
                            $ttltransfer2 = format_rupiah($ttltransfer);

                            $r5 = $query5->fetch(PDO::FETCH_ASSOC);
                            $ttltempo = $r5['ttlskrg4'];
                            $ttltempo2 = format_rupiah($ttltempo);

                            $r6 = $query6->fetch(PDO::FETCH_ASSOC);
                            $ttlshift1 = $r6['ttlshift1'];
                            $ttlshiftx = format_rupiah($ttlshift1);

                            $r6tunai = $query6tunai->fetch(PDO::FETCH_ASSOC);
                            $ttlshift1tunai = $r6tunai['ttlshift1tunai'];
                            $ttlshiftxtunai = format_rupiah($ttlshift1tunai);

                            $r6transfer = $query6transfer->fetch(PDO::FETCH_ASSOC);
                            $ttlshift1transfer = $r6transfer['ttlshift1transfer'];
                            $ttlshiftxtransfer = format_rupiah($ttlshift1transfer);

                            $r6tempo = $query6tempo->fetch(PDO::FETCH_ASSOC);
                            $ttlshift1tempo = $r6tempo['ttlshift1tempo'];
                            $ttlshiftxtempo = format_rupiah($ttlshift1tempo);

                            $r7 = $query7->fetch(PDO::FETCH_ASSOC);
                            $ttlshift2 = $r7['ttlshift2'];
                            $ttlshifty = format_rupiah($ttlshift2);

                            $r7tunai = $query7tunai->fetch(PDO::FETCH_ASSOC);
                            $ttlshift2tunai = $r7tunai['ttlshift2tunai'];
                            $ttlshiftytunai = format_rupiah($ttlshift2tunai);

                            $r7transfer = $query7transfer->fetch(PDO::FETCH_ASSOC);
                            $ttlshift2transfer = $r7transfer['ttlshift2transfer'];
                            $ttlshiftytransfer = format_rupiah($ttlshift2transfer);

                            $r7tempo = $query7tempo->fetch(PDO::FETCH_ASSOC);
                            $ttlshift2tempo = $r7tempo['ttlshift2tempo'];
                            $ttlshiftytempo = format_rupiah($ttlshift2tempo);

                            $r8 = $query8->fetch(PDO::FETCH_ASSOC);
                            $ttlshift3 = $r8['ttlshift3'];
                            $ttlshifty3 = format_rupiah($ttlshift3);

                            $r8tunai = $query8tunai->fetch(PDO::FETCH_ASSOC);
                            $ttlshift3tunai = $r8tunai['ttlshift3tunai'];
                            $ttlshiftytunai3 = format_rupiah($ttlshift3tunai);

                            $r8transfer = $query8transfer->fetch(PDO::FETCH_ASSOC);
                            $ttlshift3transfer = $r8transfer['ttlshift3transfer'];
                            $ttlshiftytransfer3 = format_rupiah($ttlshift3transfer);

                            $r8tempo = $query8tempo->fetch(PDO::FETCH_ASSOC);
                            $ttlshift3tempo = $r8tempo['ttlshift3tempo'];
                            $ttlshiftytempo3 = format_rupiah($ttlshift3tempo);
                        // echo"
                        // <tr>
                        //         <td colspan='4' style='text-align: center;'><strong>Tunai Rp. $ttltunai2 </strong>  </td>
                        //         <td colspan='2' style='text-align: center;'><strong> Transfer Rp. $ttltransfer2   ,- </strong></td> 
                        //         <td colspan='4' style='text-align: center;'><strong> Tempo Rp. $ttltempo2  ,- </strong></td> 
                        //     </tr>
                        //     <tr>
                        //         <td colspan='5'><strong><center>Total shift Pagi = Rp. $ttlshiftx                                 
                        //                                    <br> Tunai Rp. $ttlshiftxtunai                                                            
                        //                                    <br> Transfer Rp. $ttlshiftxtransfer
                        //                                    <br> Tempo Rp. $ttlshiftxtempo</center> </strong> </td>
                        //         <td colspan='5'><strong><center>Total shift Sore = Rp. $ttlshifty
                        //                                     <br> Tunai Rp. $ttlshiftytunai
                        //                                     <br> Transfer Rp. $ttlshiftytransfer
                        //                                     <br> Tempo Rp. $ttlshiftytempo <center></strong></td> 
                        //     </tr><tr>
                        //         <td colspan='10' style='font-weight:bold;'><h2><center>Total Hari ini Rp. $ttlskrg2  ,-</center></h2>  </td>
                                 
                        // </tr>";
                        
                            ?>                            
                    </table>
                    <table class="table table-striped table-bordered table-responsive">
                    <h4>Ringkasan Transaksi</h4>
                                <thead>
                                <th width="150px">Tipe Transaksi</th>
                                <th>Nilai Transaksi</th>
                                <th>Shift Pagi</th>
                                <th>Shift Sore</th>
                                </thead>
                                <tbody>
                                <?php
                                $tgl_awal = date('Y-m-d');
                                $tamtot = $db->query("select * from carabayar");
                                while ($tt = $tamtot->fetch(PDO::FETCH_ASSOC)){

                                    $tcb= $db->prepare( "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir) as ttlskrg1
                                        FROM trkasir WHERE tgl_trkasir='$tgl_awal' AND id_carabayar='$tt[id_carabayar]'");
                                    $tcb->execute();
                                    $tamtcb = $tcb->fetch(PDO::FETCH_ASSOC);
                                    $dtamtcb = format_rupiah($tamtcb['ttlskrg1']);

                                    $shift1= $db->prepare( "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir) as ttlskrg1
                                        FROM trkasir WHERE tgl_trkasir='$tgl_awal' AND id_carabayar='$tt[id_carabayar]' and shift=1 ");
                                    $shift1->execute();
                                    $pagi1 = $shift1->fetch(PDO::FETCH_ASSOC);
                                    $pagi = format_rupiah($pagi1['ttlskrg1']);

                                    $shift2= $db->prepare( "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir) as ttlskrg1
                                        FROM trkasir WHERE tgl_trkasir='$tgl_awal' AND id_carabayar='$tt[id_carabayar]' and shift=2 ");
                                    $shift2->execute();
                                    $sore1 = $shift2->fetch(PDO::FETCH_ASSOC);
                                    $sore = format_rupiah($sore1['ttlskrg1']);

                                    echo"
                          <tr><td width='150 px'>$tt[nm_carabayar]</td>
                                <td>$dtamtcb</td>
                                <td>$pagi</td>
                                <td>$sore</td>
                          </tr>
                          ";
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                <?php
                                $tgl_awal = date('Y-m-d');
                                $tothari = $db->prepare("SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir) as ttlskrg4                                                               FROM trkasir WHERE tgl_trkasir='$tgl_awal'");
                                $tothari->execute();
                                $arth = $tothari->fetch(PDO::FETCH_ASSOC);
                                $tdm = format_rupiah($arth['ttlskrg4']);

                                $totpagi = $db->prepare("SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir) as ttlskrg4                                                             FROM trkasir WHERE tgl_trkasir='$tgl_awal' and shift=1 ");
                                $totpagi->execute();
                                $tpagi = $totpagi->fetch(PDO::FETCH_ASSOC);
                                $shiftpagi = format_rupiah($tpagi['ttlskrg4']);

                                $totsore = $db->prepare("SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir) as ttlskrg4                                                               FROM trkasir WHERE tgl_trkasir='$tgl_awal' and shift=2 ");
                                $totsore->execute();
                                $tsore = $totsore->fetch(PDO::FETCH_ASSOC);
                                $shiftsore = format_rupiah($tsore['ttlskrg4']);

                                echo"
                            <tr style='background-color: #00fafa;font-size:20px;font-weight:bold;'>
                                <td>TOTAL</td>                               
                                <td>$tdm</td>
                                <td>$shiftpagi</td>
                                <td>$shiftsore</td>
                            </tr>";
                                ?>
                               </tfoot>
                            </table>
                    
                    <!-- <table class="table table-striped table-bordered table-responsive">
                        <h4>Ringkasan Transaksi</h4>
                        <thead>
                            <th width="150px">Tipe Transaksi</th>
                            <th>Nilai Transaksi</th>
                            <th>Shift Pagi</th>
                            <th>Shift Sore</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td width='150 px'>Tunai</td>
                                <td><div id="totalTunai"></div></td>
                                <td><div id="totalTunaiPagi"></div></td>
                                <td><div id="totalTunaiSore"></div></td>
                            </tr>
                            <tr>
                                <td width='150 px'>Transfer</td>
                                <td><div id="totalTransfer"></div></td>
                                <td><div id="totalTransferPagi"></div></td>
                                <td><div id="totalTransferSore"></div></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr style='background-color: #00fafa;font-size:20px;font-weight:bold;'>
                                <td>TOTAL</td>
                                <td><div id="totalKasir"></div></td>
                                <td><div id="totalKasirPagi"></div></td>
                                <td><div id="totalKasirSore"></div></td>
                            </tr>
                        </tfoot>
                    </table>         -->
                </div>
                <?php
                $kom = $db->prepare("SELECT sum(komisi) AS tambahan FROM trkasir_detail JOIN trkasir
                                    ON(trkasir_detail.kd_trkasir=trkasir.kd_trkasir)
                                    WHERE trkasir_detail.idadmin='$_SESSION[idadmin]' 
                                    AND trkasir.tgl_trkasir BETWEEN '$awalbulan' AND '$tgl_awal' ");
                $kom->execute();
                $misi = $kom->fetch(PDO::FETCH_ASSOC);
                $pk = format_rupiah($misi['tambahan']);
                
                ?>
                <a class='btn  btn-success btn-flat' href='modul/mod_lapstok/sinkronisasi_stok.php'>SINKRONISASI</a>
                Klik SINKRONISASI sebelum tutup shift
                <?php
            
                if ($_SESSION['komisi']=='Y' && $_SESSION['level']=='petugas'){
                echo"
                <marquee><h3 style='font-weight: bold;'>Total Komisi per Produk <?php echo $petugas; ?> Rp. <?php echo $pk; ?></h3></marquee>
                "; }
                ?>
            </div>

            <script>
                $(document).ready(function() {
                    $("#rekap").DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            "url": "modul/mod_trkasir/penjualanhariini_serverside.php?action=table_data",
                            "dataType": "JSON",
                            "type": "POST"
                        },
                        "rowCallback": function(row, data, index) {
                             let q = (data['nm_carabayar']);
                             let r = (data['shift']);
                             let s = (data['jenistx']);
                            
                             if (q == 'Tempo' ) {
                                $(row).find('td:eq(8)').css('background-color', '#f39c12');
                                $(row).find('td:eq(8)').css('color', '#ffffff');
                             }
                             else {
                            // $(row).find('td:eq(7)').css('background-color', '#FFFFFF');
                            // $(row).find('td:eq(7)').css('color', '#000000');        
                            }
                            if (r == 1 ) {
                                $(row).find('td:eq(2)').text('Pagi');                               
                             }
                             else if (r == 2 ) {
                                $(row).find('td:eq(2)').text('Siang');                               
                             }
                             else if (r == 3 ) {
                                $(row).find('td:eq(2)').text('Malam');                               
                             }

                            if (s == 1 ) {
                               $(row).find('td:eq(3)').text('Reguler');
                               $(row).find('td:eq(3)').css('background-color', '#1e90ff');
                               $(row).find('td:eq(3)').css('color', '#ffffff'); 
                             }
                             else if (s == 2 ) {
                                $(row).find('td:eq(3)').text('Member');
                                $(row).find('td:eq(3)').css('background-color', '#32cd32');
                                $(row).find('td:eq(3)').css('color', '#ffffff');                                
                             }
                             else if (s == 3 ) {
                                $(row).find('td:eq(3)').text('Market Place'); 
                                $(row).find('td:eq(3)').css('background-color', '#ff0000');
                                $(row).find('td:eq(3)').css('color', '#ffffff');                               
                             }
                             else if (s == 4 ) {
                                $(row).find('td:eq(3)').text('Market Place'); 
                                $(row).find('td:eq(3)').css('background-color', '#ffa500');
                                $(row).find('td:eq(3)').css('color', '#ffffff');                             
                             }

                        },
                        columns: [{
                                "data": "no",
                                "className": 'text-center',
                            },
                            {
                                "data": "kd_trkasir"
                            },
                            {
                                "data": "shift",
                                "className": 'text-center',
                            },
                            {
                                "data": "jenistx",
                                "className": 'text-center',
                            },
                            {
                                "data": "petugas",
                                "className": 'text-center',
                            },
                            {
                                "data": "tgl_trkasir",
                                "className": 'text-center',
                            },
                            {
                                "data": "nm_pelanggan",
                            },
                            {
                                "data": "kodetx",
                            },
                            {
                                "data": "nm_carabayar",
                            },
                            {
                                "data": "ttl_trkasir",
                                "className": 'text-right',
                                "render": function(data, type, row) {
                                    return formatRupiah(data);
                                }
                            },
                            {
                                "data": "pilih",
                                "className": 'text-center'
                            },
                        ],
                        "footerCallback": function(row, data, start, end, display) {
                            let api = this.api();
                            let json = api.ajax.json();
                            // // $('#totalRupiah').html(formatRupiah(json.totalStok));
                            //console.log("Total Kasir = "+ json.totalKasir+"\nTotal Tunai = "+ json.totalTunai+"\nTunai Pagi = "+json.totalTunaiPagi+"\nTunai Sore = "+json.totalTunaiSore);
                            $('#totalTunai').html(formatRupiah(json.totalTunai));
                            $('#totalTunaiPagi').html(formatRupiah(json.totalTunaiPagi));
                            $('#totalTunaiSore').html(formatRupiah(json.totalTunaiSore));
                            
                            $('#totalTransfer').html(formatRupiah(json.totalTransfer));
                            $('#totalTransferPagi').html(formatRupiah(json.totalTransferPagi));
                            $('#totalTransferSore').html(formatRupiah(json.totalTransferSore));
                            
                            $('#totalKasir').html(formatRupiah(json.totalTunai + json.totalTransfer));
                            $('#totalKasirPagi').html(formatRupiah(json.totalTunaiPagi + json.totalTransferPagi));
                            $('#totalKasirSore').html(formatRupiah(json.totalTunaiSore + json.totalTransferSore));
                        }
                    })

                });
            </script>

        <?php

            break;

        case "tambah":
            //cek apakah ada SHIFT yang ON
            $tglharini = date('Y-m-d');
            $cekshift = $db->prepare("select * from waktukerja where tanggal = ? and status='ON' ");
            $cekshift->execute([$tglharini]);
            $hitung = $cekshift->rowCount();
            $sshift  = $cekshift->fetch(PDO::FETCH_ASSOC);
            $shift = $sshift['shift'];


            if ($hitung < 1) {
                echo "<script type='text/javascript'>alert('Shift Kasir Belum Dibuka!');history.go(-1);</script>";
            } else {
                //cek apakah ada kode transaksi ON berdasarkan user
                $cekkd = $db->prepare("SELECT * FROM kdtk WHERE id_admin=? AND stt_kdtk='ON'");
                $cekkd->execute([$_SESSION['idadmin']]);
                $ketemucekkd = $cekkd->rowCount();
                $hcekkd = $cekkd->fetch(PDO::FETCH_ASSOC);
                $petugas = $_SESSION['namalengkap'];
                $petugas2 = $_SESSION['idadmin'];


                if ($ketemucekkd > 0) {
                    $kdtransaksi = $hcekkd['kd_trkasir'];
                } else {
                    $kdunik = date('dmyHis');
                    $kdtransaksi = "TKP-" . $kdunik;
                    $cekkd2 = $db->prepare("SELECT * FROM kdtk WHERE kd_trkasir=?");
                    $cekkd2->execute([$kdtransaksi]);
                    $ketemucekkd2 = $cekkd2->rowCount();
                    if ($ketemucekkd2 > 0) {
                        $kdunik2 = date('dmyHis')+1;
                        $kdtransaksi = "TKP-" . $kdunik2;
                    }
                    
                    $db->prepare("INSERT INTO kdtk(kd_trkasir,id_admin) VALUES(?,?)")->execute([$kdtransaksi, $_SESSION['idadmin']]);
                }

                $tglharini = date('Y-m-d');

                // Validasi Transaksi
                $lst_trx = $db->prepare("SELECT * FROM trkasir 
                                            ORDER BY id_trkasir ASC
                                            LIMIT 1");
                $lst_trx->execute();
                $trx =  $lst_trx->fetch(PDO::FETCH_ASSOC);
                $tgl_first = date('Y-m-d', strtotime('+1 months', strtotime($trx['tgl_trkasir'])));
                $tgl_last  = date('Y-m-d', time());
                
                if($tgl_last > $tgl_first){
                    $disabled = "disabled";
                } else {
                    $disabled = "";
                }
                
                echo "<small>F1 => Simpan Detail || F2 => Input Jumlah Bayar || F3 => Simpan Transaksi</small>";
                echo "
		  <div class='box box-primary box-solid table-responsive'>
				<div class='box-header with-border'>
					<h3 class='box-title'>TAMBAH PENJUALAN</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body table-responsive'>
				
						<form onsubmit='return false;' method=POST action='$aksi?module=trkasir&act=input_trkasir' enctype='multipart/form-data' class='form-horizontal'>
						
						       <input type=hidden name='id_trkasir' id='id_trkasir' value='0'>
							   <input type=hidden name='kd_trkasir' id='kd_trkasir' value='$kdtransaksi'>
							   <input type=hidden name='stt_aksi' id='stt_aksi' value='input_trkasir'>
							   <input type=hidden name='petugas' id='petugas' value='$petugas'>
							   <input type=hidden name='shift' id='shift' value='$shift'>
							   <input type=hidden name='level' id='level' value='$_SESSION[level]'>
							 
							 
						<div class='col-lg-6'>
							  
								<div class='form-group'>
							  
									<label class='col-sm-4 control-label'>Tanggal </label>
										<div class='col-sm-6'>
											<div class='input-group date'>
												<div class='input-group-addon'>
													<span class='glyphicon glyphicon-th'></span>
												</div>
													<input type='text' class='datepicker' name='tgl_trkasir' id='tgl_trkasir' required='required' value='$tglharini' autocomplete='off'>
											</div>
										</div>
										
									<label class='col-sm-4 control-label'>Kode Transaksi</label>        		
										<div class='col-sm-6'>
											<input type=text name='kd_hid' id='kd_hid' class='form-control' required='required' value='$kdtransaksi' autocomplete='off' Disabled>
										</div>

									<label class='col-sm-4 control-label'>Kode Order</label>        		
										<div class='col-sm-6'>
											<textarea name='kodetx' id='kodetx' class='form-control' rows='1'></textarea>
										</div>	

                                    <label class='col-sm-4 control-label'>Petugas Pelayanan</label>        		
										<div class='col-sm-6'>
													<select class='form-control' name='id_user' id='id_user'>

													    <option value='$_SESSION[idadmin]'>$petugas</option>
													    ";
														$pelayan = $db->prepare("SELECT * FROM admin where id_admin !=1  ORDER BY nama_lengkap ASC");
														$pelayan->execute();
									               		while($rj = $pelayan->fetch(PDO::FETCH_ASSOC)){
    									                    echo "<option value='$rj[id_admin]'>$rj[nama_lengkap]</option>";
    									                }
								
								    		echo "
													</select>
											</div>
									<label class='col-sm-4 control-label'>Pelanggan</label>        		
										<div class='col-sm-6'>
											<input type=text name='nm_pelanggan' id='nm_pelanggan' class='typeahead form-control' autocomplete='off'>
										</div>
										
									<label class='col-sm-4 control-label'>Telepon</label>        		
										<div class='col-sm-6'>
											<input type=text name='tlp_pelanggan' id='tlp_pelanggan' class='form-control' autocomplete='off'>
										</div>
										
									<label class='col-sm-4 control-label'>Alamat</label>        		
										<div class='col-sm-6'>
											<textarea name='alamat_pelanggan' id='alamat_pelanggan' class='form-control' rows='2'></textarea>
										</div>
										
									<label class='col-sm-4 control-label'>Nama Dokter</label>        		
										<div class='col-sm-6'>
											<textarea name='ket_trkasir' id='ket_trkasir' class='form-control' rows='2'></textarea>
										</div>
									
									
								</div>
							  
						</div>
						
						<div class='col-lg-6'>
						
						
								<input type=hidden name='id_barang' id='id_barang'>
								<input type=hidden name='stok_barang' id='stok_barang' >
								<input type=hidden name='id_admin' id='id_admin' value='$_SESSION[idadmin]'>
								<input type=hidden name='komisi_dtrkasir' id='komisi_dtrkasir'>
								<input type=hidden name='level' id='level' value='$_SESSION[level]'>
								
								<div class='form-group'>
								
									<label class='col-sm-4 control-label'>Jenis Transaksi</label>        		
									 <div class='col-sm-7'>									    
										    <select name='jns_transaksi' id='jns_transaksi' class='form-control'>
										        <option value='1'>Reguler</option>
										        <option value='2'>Resep</option>
										        <option value='3'>Marketplace</option>
										       
										        
										    </select>										
									 </div>	
									 	
									<label class='col-sm-4 control-label'>Kode Barang</label>        		
									 <div class='col-sm-7'>
									 <div class='input-group'>
										<input type=text name='kd_barang' id='kd_barang' class='form-control' autocomplete='off'>
										<div class='input-group-addon'>
											<button type=button data-toggle='modal' data-target='#ModalItem' href='#' id='kode'><span class='glyphicon glyphicon-search'></span></button>
										</div>
                                        <div class='input-group-addon'>
                                            <button type=button data-toggle='modal' data-target='#ModalScanBarcode' href='#' id='btnScanBarcode'><span class='glyphicon glyphicon-camera'></span></button>
                                        </div>
										</div>
									 </div>
									 
									<label class='col-sm-4 control-label'>Nama Barang</label>        		
											<div class='col-sm-7'>
    											<div class='btn-group btn-group-justified' role='group' aria-label='...'>
                                                    <div class='btn-group' role='group'>
    											        <input type=text name='nmbrg_dtrkasir' id='nmbrg_dtrkasir' class='typeahead form-control' autocomplete='off'>
                                                        
                                                    </div>
                                                    <div class='btn-group' role='group'>
                                                        <button type='button' class='btn btn-primary' id='nmbrg_dtrkasir_enter'>Enter</button>
                                                    </div>
                                                </div>
    										</div>
    										
                                    <label class='col-sm-4 control-label'>Resep</label>        		
                                            <div class='col-sm-7'>
                                                    <select class='form-control' name='resep' id='resep'>
                                                        <option value='TIDAK'>TIDAK</option>
                                                        <option value='YA'>YA</option>
                                                    </select>
                                            </div>
											
														
									<label class='col-sm-4 control-label'>Qty</label>        		
										<div class='col-sm-7'>
											<input type='number' name='qty_dtrkasir' id='qty_dtrkasir' class='form-control' autocomplete='off'>
										</div>
									
									";
                $lupa = $_SESSION['level'];
                if ($lupa == 'pemilik') {
                    echo "
									<label class='col-sm-4 control-label'>Satuan</label>        		
										<div class='col-sm-7'>
											<input type=text name='sat_dtrkasir' id='sat_dtrkasir' class='form-control' autocomplete='off'>
										</div>
										
									<label class='col-sm-4 control-label'>Harga</label>        		
										<div class='col-sm-7'>
											<input type=text name='hrgjual_dtrkasir' id='hrgjual_dtrkasir' class='form-control' autocomplete='off' ".$disabled.">
										</div>";
                } else {
                    echo "
									<label class='col-sm-4 control-label'>Satuan</label>        		
										<div class='col-sm-7'>
											<input type=text name='sat_dtrkasir' id='sat_dtrkasir' class='form-control' autocomplete='off' disabled>
										</div>
										
									<label class='col-sm-4 control-label'>Harga</label>        		
										<div class='col-sm-7'>
											<input type=text name='hrgjual_dtrkasir' id='hrgjual_dtrkasir' class='form-control' autocomplete='off' ".$disabled.">
										</div>";
                }
                echo "
									<label class='col-sm-4 control-label'>Disc</label>        		
										<div class='col-sm-7'>
											<input type=text name='disc' id='disc' class='form-control' autocomplete='off'>											
										</div>
										
									<label class='col-sm-4 control-label'>batch</label>        		
										<div class='col-sm-7'>
										    <div class='input-group'>
    											<input type=text name='batch' id='batch' class='form-control' autocomplete='off'>
    											<div class='input-group-addon'>
        											<button type=button data-toggle='modal' data-target='#ModalBatch' href='#' id='caribatch'><span class='glyphicon glyphicon-search'></span></button>
        										</div>
        									</div>
										</div>
									
									<label class='col-sm-4 control-label'>Exp. Date</label>        		
										<div class='col-sm-7'>
											<input type='date' class='datepicker' name='exp_date' id='exp_date' required='required' autocomplete='off'>
											</p>
												<div class='buttons'>
													<button type='button' class='btn btn-success right-block' onclick='simpan_detail();'>[F1] SIMPAN DETAIL</button>
												</div>
										</div>	
										
								</div>
								
						</div>
						</form>
							  
				</div> 
				
				<div id='tabeldata'>
				
			</div>";
            }

            break;



        case "ubah":
            $ubah = $db->prepare("SELECT * FROM trkasir 
	                                WHERE id_trkasir=?");
            $ubah->execute([$_GET['id']]);
            $re = $ubah->fetch(PDO::FETCH_ASSOC);
            $shift = $re['shift'];
            $petugas = $_SESSION['namalengkap'];

            $admin = $db->prepare("SELECT * FROM komisi_pegawai 
	                                WHERE kd_trkasir=?");
            $admin->execute([$re['kd_trkasir']]);
            $radmin = $admin->fetch(PDO::FETCH_ASSOC);

            echo "
		  <div class='box box-primary box-solid table-responsive'>
				<div class='box-header with-border'>
					<h3 class='box-title'>UBAH PENJUALAN</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body table-responsive'>
				
						<form onsubmit='return false;' method=POST action='$aksi?module=trkasir&act=ubah_trkasir' enctype='multipart/form-data' class='form-horizontal'>
						
						       <input type=hidden name='id_trkasir' id='id_trkasir' value='$re[id_trkasir]'>
							   <input type=hidden name='kd_trkasir' id='kd_trkasir' value='$re[kd_trkasir]'>
							   <input type=hidden name='stt_aksi' id='stt_aksi' value='ubah_trkasir'>
							   <input type=hidden name='petugas' id='petugas' value='$petugas'>
							   <input type=hidden name='shift' id='shift' value='$shift'>
							   <input type=hidden name='level' id='level' value='$_SESSION[level]'>
							 
						<div class='col-lg-6'>
							  
								<div class='form-group'>
							  							        
									<label class='col-sm-4 control-label'>Tanggal</label>
										<div class='col-sm-6'>
											<div class='input-group date'>
												<div class='input-group-addon'>
													<span class='glyphicon glyphicon-th'></span>
												</div>
													<input type='text' class='datepicker' name='tgl_trkasir' id='tgl_trkasir' value='$re[tgl_trkasir]' required='required' value='$tglharini' autocomplete='off'>
											</div>
										</div>
										
									<label class='col-sm-4 control-label'>Kode Transaksi</label>        		
										<div class='col-sm-6'>
											<input type=text name='kd_hid' id='kd_hid' class='form-control' required='required' value='$re[kd_trkasir]' autocomplete='off' Disabled>
										</div>

									<label class='col-sm-4 control-label'>Petugas Pelayanan</label>        		
										<div class='col-sm-6'>
													<select class='form-control' name='id_user' id='id_user'>";
														$pelayan1 = $db->prepare("SELECT id_user,nama_lengkap FROM trkasir join admin on trkasir.id_user = admin.id_admin
														WHERE trkasir.kd_trkasir = '$re[kd_trkasir]'");
														$pelayan1->execute();
														$rj1 = $pelayan1->fetch(PDO::FETCH_ASSOC);									               		
													    echo "<option value='$rj1[id_admin]'>$rj1[nama_lengkap]</option>
													    ";
														$pelayan = $db->prepare("SELECT * FROM admin WHERE akses_level='petugas' ORDER BY nama_lengkap ASC");
														$pelayan->execute();
									               		while($rj = $pelayan->fetch(PDO::FETCH_ASSOC)){	
                                                          echo "<option value='$rj[id_admin]'>$rj[nama_lengkap]</option>";
									                    }
								
								    		echo "
													</select>
											</div>

									<label class='col-sm-4 control-label'>Pelanggan</label>        		
										<div class='col-sm-6'>
											<input type=text name='nm_pelanggan' id='nm_pelanggan' class='typeahead form-control' value='$re[nm_pelanggan]' autocomplete='off'>
										</div>
										
									<label class='col-sm-4 control-label'>Telepon</label>        		
										<div class='col-sm-6'>
											<input type=text name='tlp_pelanggan' id='tlp_pelanggan' class='form-control' value='$re[tlp_pelanggan]' autocomplete='off'>
										</div>
										
									<label class='col-sm-4 control-label'>Alamat</label>        		
										<div class='col-sm-6'>
											<textarea name='alamat_pelanggan' id='alamat_pelanggan' class='form-control' rows='2'>$re[alamat_pelanggan]</textarea>
										</div>
										
									<label class='col-sm-4 control-label'>Nama Dokter</label>        		
										<div class='col-sm-6'>
											<textarea name='ket_trkasir' id='ket_trkasir' class='form-control' rows='2'>$re[ket_trkasir]</textarea>
										</div>
									<label class='col-sm-4 control-label'>Kode Order</label>        		
										<div class='col-sm-6'>
											<textarea name='kodetx' id='kodetx' class='form-control' rows='2'>$re[kodetx]</textarea>
										</div>
										
								</div>
							  
						</div>
						
						<div class='col-lg-6'>
						
						
								<input type=hidden name='id_barang' id='id_barang'>
								<input type=hidden name='stok_barang' id='stok_barang' >
								<input type=hidden name='id_admin' id='id_admin' value='$radmin[id_admin]'>
								<input type=hidden name='komisi_dtrkasir' id='komisi_dtrkasir'>
								<input type=hidden name='level' id='level' value='$_SESSION[level]'>
								
								<div class='form-group'>
									
									<label class='col-sm-4 control-label'>Jenis Transaksi</label>        		
									 <div class='col-sm-7'>									    
										    <select name='jns_transaksi' id='jns_transaksi' class='form-control'>
										        <option value='1'>Reguler</option>
										        <option value='2'>Resep</option>
										        <option value='3'>Marketplace</option>
										       
										        
										    </select>										
									 </div>
									 
									<label class='col-sm-4 control-label'>Kode Barang</label>        		
									 <div class='col-sm-7'>
									 <div class='input-group'>
										<input type=text name='kd_barang' id='kd_barang' class='form-control' autocomplete='off'>
										<div class='input-group-addon'>
											<button type=button data-toggle='modal' data-target='#ModalItem' href='#' id='kode'><span class='glyphicon glyphicon-search'></span></button>
										</div>
										</div>
									 </div>
									 
									<label class='col-sm-4 control-label'>Nama Barang</label>        		
											<div class='col-sm-7'>
													<input type=text name='nmbrg_dtrkasir' id='nmbrg_dtrkasir' class='form-control' autocomplete='off'>
											</div>
									
									<label class='col-sm-4 control-label'>Resep</label>        		
											<div class='col-sm-7'>
													<select class='form-control' name='resep' id='resep'>
													    <option value='TIDAK'>TIDAK</option>
													    <option value='YA'>YA</option>
													</select>
											</div>
											
									
									<label class='col-sm-4 control-label'>Qty</label>        		
										<div class='col-sm-7'>
											<input type='number' name='qty_dtrkasir' id='qty_dtrkasir' class='form-control' autocomplete='off'>
										</div>
											
									<label class='col-sm-4 control-label'>Satuan</label>        		
										<div class='col-sm-7'>
											<input type=text name='sat_dtrkasir' id='sat_dtrkasir' class='form-control' autocomplete='off' Disabled>
										</div>
										
									<label class='col-sm-4 control-label'>Harga</label>        		
										<div class='col-sm-7'>
											<input type=number name='hrgjual_dtrkasir' id='hrgjual_dtrkasir' class='form-control' autocomplete='off' Disabled>
										</div>
										
									<label class='col-sm-4 control-label'>Disc</label>        		
										<div class='col-sm-7'>
											<input type=text name='disc' id='disc' class='form-control' autocomplete='off'>											
										</div>
										
									<label class='col-sm-4 control-label'>batch</label>        		
										<div class='col-sm-7'>
										    <div class='input-group'>
    											<input type=text name='batch' id='batch' class='form-control' autocomplete='off'>
    											<div class='input-group-addon'>
        											<button type=button data-toggle='modal' data-target='#ModalBatch' href='#' id='caribatch'><span class='glyphicon glyphicon-search'></span></button>
        										</div>
        									</div>
										</div>
									
									<label class='col-sm-4 control-label'>Exp. Date</label>        		
										<div class='col-sm-7'>
											<input type='date' class='datepicker' name='exp_date' id='exp_date' required='required' autocomplete='off'>
											</p>
												<div class='buttons'>
													<button type='button' class='btn btn-success right-block' onclick='simpan_detail();'>[F1] SIMPAN DETAIL</button>
												</div>
										</div>	
								</div>
								
								
						</div>
						</form>
							  
				</div> 
				
				<div id='tabeldata'>
				
			</div>";


            break;
        case "cari":
            $tgl_awal = date('Y-m-d');
            $tgl_kemarin = date('Y-m-d', strtotime('-1 days', strtotime($tgl_awal)));
            $tgl_akhir = date('Y-m-d', strtotime('-60 days', strtotime($tgl_awal)));
            $tampil_trkasir = $db->query("SELECT * FROM trkasir  
                where tgl_trkasir between '$tgl_akhir' and '$tgl_kemarin'ORDER BY id_trkasir desc ");
        ?>


            <div class="box box-primary box-solid table-responsive">
                <div class="box-header with-border">
                    <h3 class="box-title">TRANSAKSI PENJUALAN KEMARIN</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
                </div>
                <div class="box-body">

                    <a class='btn  btn-warning  btn-flat' href='#'></a>
                    <small>* Pembayaran belum lunas</small>
                    <div></div>
                    <!--	<a  class ='btn  btn-warning  btn-flat' href='?module=trkasir&act=penjualansebelum'>PENJUALAN SEBELUMNYA</a>
                        <small>* Pembayaran belum lunas</small> -->
                    <br><br>


                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Cara Bayar</th>
                                <th>Total</th>

                                <th width="70">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $tgl_awal = date('Y-m-d');
                            while ($r = $tampil_trkasir->fetch(PDO::FETCH_ASSOC)) {
                                $ttl_trkasir = $r['ttl_trkasir'];
                                $ttl_trkasir2 = format_rupiah($ttl_trkasir);
                                $ttljual += $ttl_trkasir;
                                $ttljual1 = format_rupiah($ttljual);

                                $query2 = $db->query("SELECT                                         
                                            id_trkasir,
                                            kd_trkasir,
                                            SUM(ttl_trkasir)as ttlskrg1                                                              
                                            FROM trkasir                                         
                                            WHERE tgl_trkasir='$tgl_awal'");
                                $r2 = $query2->fetch(PDO::FETCH_ASSOC);
                                $ttlskrg = $r2['ttlskrg1'];




                                if ($r['id_carabayar'] == 3) {
                                    echo "<td style='background-color:#ffbf00;'>$no</td>
                                                 <td style='background-color:#ffbf00;'>$r[kd_trkasir]</td>";
                                } else {
                                    echo "<td>$no</td>
                                                    <td>$r[kd_trkasir]";
                                }


                                echo "	<td>$r[tgl_trkasir]</td>
                                                <td>$r[nm_pelanggan]</td>";
                                $cabay = $db->prepare(
                                    "SELECT * FROM trkasir JOIN carabayar on trkasir.id_carabayar = carabayar.id_carabayar WHERE trkasir.kd_trkasir =?"
                                );
                                $cabay->execute([$r['kd_trkasir']]);
                                $cabay1 = $cabay->fetch(PDO::FETCH_ASSOC);

                                echo "
                                                <td align='center'>$cabay1[nm_carabayar]</td>";
                                echo "													
                                                <td align=right>$ttl_trkasir2</td>
                                                
                                                 <td><a href='?module=trkasir&act=ubah&id=$r[id_trkasir]' title='EDIT' class='glyphicon glyphicon-pencil'>&nbsp</a> 
                                                 ";
                            ?>
                                <a class='glyphicon glyphicon-print' onclick="window.open('modul/mod_laporan/struk.php?kd_trkasir=<?php echo $r['kd_trkasir'] ?>','nama window','width=500,height=600,toolbar=no,location=no,directories=no,status=no,menubar=no, scrollbars=no,resizable=yes,copyhistory=no')">&nbsp</a>
                            <?php
                                echo "
                                                 <a href=javascript:confirmdelete('$aksi?module=trkasir&act=hapus&id=$r[id_trkasir]') title='HAPUS' class='glyphicon glyphicon-remove'>&nbsp</a>
                                                 
                                                </td>
                                            </tr>";
                                $no++;
                            }
                            echo "</tbody>
                                
                            </table>";
                            ?>
                </div>
            </div>
        <?php
            break;

        case "cari2":

        ?>
            <div class="box box-primary box-solid">
                <div class='box-header with-border'>
                    <h3 class='box-title'>SEACRH BY Kode Transaksi</h3>
                    <div class='box-tools pull-right'>
                        <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
                </div>
                <div class='box-body'>
                    <form method="post" action="?module=trkasir&act=ubah2">

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Kode Transaksi</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="kd_trkasir" name="kd_trkasir">
                            </div>
                        </div>
                        <div class="form-group row justify-contend-end">
                            <label for="inputPassword" class="col-sm-2 col-form-label">&nbsp;</label>
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">
                                    <span class="glyphicon glyphicon-search"></span>
                                    Search
                                </button>

                                <button class='btn btn-primary' type='button' onclick=self.history.back()>
                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                    Kembali
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
<?php
            break;
        case "ubah2":

            echo $_POST['kd_trkasir'];

            $ubah = $db->prepare("SELECT * FROM trkasir 
	        WHERE kd_trkasir=?");
            $ubah->execute([$_POST['kd_trkasir']]);
            $re = $ubah->fetch(PDO::FETCH_ASSOC);
            //            $shift = $re['shift'];
            //            $petugas = $_SESSION['namalengkap'];
            //
            //            $admin = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM komisi_pegawai
            //	            WHERE kd_trkasir='$re[kd_trkasir]'");
            //            $radmin = mysqli_fetch_array($admin);

            echo "
		  <div class='box box-primary box-solid table-responsive'>
				<div class='box-header with-border'>
					<h3 class='box-title'>UBAH PENJUALAN</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body table-responsive'>
				
						<form onsubmit='return false;' method=POST action='$aksi?module=trkasir&act=ubah_trkasir' enctype='multipart/form-data' class='form-horizontal'>
						
						       <input type=hidden name='id_trkasir' id='id_trkasir' value='$re[id_trkasir]'>
							   <input type=hidden name='kd_trkasir' id='kd_trkasir' value='$re[kd_trkasir]'>
							   <input type=hidden name='stt_aksi' id='stt_aksi' value='ubah_trkasir'>
							   <input type=hidden name='petugas' id='petugas' value='$petugas'>
							   <input type=hidden name='shift' id='shift' value='$shift'>
							 
						<div class='col-lg-6'>
							  
								<div class='form-group'>
							  							        
									<label class='col-sm-4 control-label'>Tanggal</label>
										<div class='col-sm-6'>
											<div class='input-group date'>
												<div class='input-group-addon'>
													<span class='glyphicon glyphicon-th'></span>
												</div>
													<input type='text' class='datepicker' name='tgl_trkasir' id='tgl_trkasir' value='$re[tgl_trkasir]' required='required' value='$tglharini' autocomplete='off'>
											</div>
										</div>
										
									<label class='col-sm-4 control-label'>Kode Transaksi</label>        		
										<div class='col-sm-6'>
											<input type=text name='kd_hid' id='kd_hid' class='form-control' required='required' value='$re[kd_trkasir]' autocomplete='off' Disabled>
										</div>
									
									<label class='col-sm-4 control-label'>Pelanggan</label>        		
										<div class='col-sm-6'>
											<input type=text name='nm_pelanggan' id='nm_pelanggan' class='typeahead form-control' value='$re[nm_pelanggan]' autocomplete='off'>
										</div>
										
									<label class='col-sm-4 control-label'>Telepon</label>        		
										<div class='col-sm-6'>
											<input type=text name='tlp_pelanggan' id='tlp_pelanggan' class='form-control' value='$re[tlp_pelanggan]' autocomplete='off'>
										</div>
										
									<label class='col-sm-4 control-label'>Alamat</label>        		
										<div class='col-sm-6'>
											<textarea name='alamat_pelanggan' id='alamat_pelanggan' class='form-control' rows='2'>$re[alamat_pelanggan]</textarea>
										</div>
										
									<label class='col-sm-4 control-label'>Nama Dokter</label>        		
										<div class='col-sm-6'>
											<textarea name='ket_trkasir' id='ket_trkasir' class='form-control' rows='2'>$re[ket_trkasir]</textarea>
										</div>
									<label class='col-sm-4 control-label'>Kode Order</label>        		
										<div class='col-sm-6'>
											<textarea name='kodetx' id='kodetx' class='form-control' rows='2'>$re[kodetx]</textarea>
										</div>
										
								</div>
							  
						</div>
						
						<div class='col-lg-6'>
						
						
								<input type=hidden name='id_barang' id='id_barang'>
								<input type=hidden name='stok_barang' id='stok_barang' >
								<input type=hidden name='id_admin' id='id_admin' value='$radmin[id_admin]'>
								<input type=hidden name='komisi_dtrkasir' id='komisi_dtrkasir'>
								<input type=hidden name='level' id='level' value='$_SESSION[level]'>
								
								<div class='form-group'>
									
									<label class='col-sm-4 control-label'>Jenis Transaksi</label>        		
									 <div class='col-sm-7'>									    
										    <select name='jns_transaksi' id='jns_transaksi' class='form-control'>
										        <option value='1'>Reguler</option>
										        <option value='2'>Member</option>
										        <option value='3'>Marketplace</option>
										    </select>										
									 </div>
									 
									<label class='col-sm-4 control-label'>Kode Barang</label>        		
									 <div class='col-sm-7'>
									 <div class='input-group'>
										<input type=text name='kd_barang' id='kd_barang' class='form-control' autocomplete='off'>
										<div class='input-group-addon'>
											<button type=button data-toggle='modal' data-target='#ModalItem' href='#' id='kode'><span class='glyphicon glyphicon-search'></span></button>
										</div>
										</div>
									 </div>
									 
									<label class='col-sm-4 control-label'>Nama Barang</label>        		
											<div class='col-sm-7'>
													<input type=text name='nmbrg_dtrkasir' id='nmbrg_dtrkasir' class='form-control' autocomplete='off'>
											</div>
									
                                    <label class='col-sm-4 control-label'>Resep</label>        		
                                            <div class='col-sm-7'>
                                                    <select class='form-control' name='resep' id='resep'>
                                                        <option value='TIDAK'>TIDAK</option>
                                                        <option value='YA'>YA</option>
                                                    </select>
                                            </div>
											
									
									<label class='col-sm-4 control-label'>Qty</label>        		
										<div class='col-sm-7'>
											<input type='number' name='qty_dtrkasir' id='qty_dtrkasir' class='form-control' autocomplete='off'>
										</div>
											
									<label class='col-sm-4 control-label'>Satuan</label>        		
										<div class='col-sm-7'>
											<input type=text name='sat_dtrkasir' id='sat_dtrkasir' class='form-control' autocomplete='off'>
										</div>
										
									<label class='col-sm-4 control-label'>Harga</label>        		
										<div class='col-sm-7'>
											<input type=number name='hrgjual_dtrkasir' id='hrgjual_dtrkasir' class='form-control' autocomplete='off'>
										</div>
										
									<label class='col-sm-4 control-label'>Disc</label>        		
										<div class='col-sm-7'>
											<input type=text name='disc' id='disc' class='form-control' autocomplete='off'>											
										</div>
										
									<label class='col-sm-4 control-label'>No. Batch</label>        		
										<div class='col-sm-7'>
											<input type='text' name='no_batch' id='no_batch' class='form-control' autocomplete='off'>
											
										</div>
									
									<label class='col-sm-4 control-label'>Exp. Date</label>        		
										<div class='col-sm-7'>
											<input type='date' class='datepicker' name='exp_date' id='exp_date' required='required' autocomplete='off'>
											</p>
												<div class='buttons'>
													<button type='button' class='btn btn-success right-block' onclick='simpan_detail();'>[F1] SIMPAN DETAIL</button>
												</div>
										</div>	
								</div>
								
								
						</div>
						</form>
							  
				</div> 
				
				<div id='tabeldata'>
				
			</div>";

            break;
    }
}
?>

<!-- Modal itemmat -->
<div id="ModalItem" class="modal fade" role="dialog">
    <div class="modal-lg modal-dialog" style="width:90%">
        <div class="modal-content table-responsive">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PILIH ITEM BARANG</h4>

                <div id="box">
                    <CENTER><strong>MySIFA PROFIT ANALYSIS</strong></CENTER><br>
                    <center><button type="button" class="btn btn-info">PROFIT>30%</button>
                        <button type="button" class="btn btn-success">PROFIT = 25 - 30 % </button>
                        <button type="button" class="btn btn-warning">PROFIT = 20 - 25%"</button>
                        <button type="button" class="btn btn-danger">PROFIT < 20% </button>
                    </center>
                </div>
            </div>



            <div class="modal-body table-responsive">
                <table id="example" class="table table-condensed table-bordered table-striped table-hover">

                    <thead>
                        <tr class="judul-table">
                            <th style="vertical-align: middle; background-color: #008000; text-align: center; ">No</th>
                            <th style="vertical-align: middle; background-color: #008000; text-align: left; ">Kode</th>
                            <th style="vertical-align: middle; background-color: #008000; text-align: left; ">Nama Barang</th>
                            <th style="vertical-align: middle; background-color: #008000; text-align: right; ">Qty</th>
                            <th style="vertical-align: middle; background-color: #008000; text-align: center; ">Satuan</th>
                            <th style="vertical-align: middle; background-color: #008000; text-align: center; ">Harga Beli</th>
                            <th style="vertical-align: middle; background-color: #008000; text-align: center; width: 50px">Harga Jual</th>
                            <th style="vertical-align: middle; background-color: #008000; text-align: center; ">Komisi</th>
                            <th style="vertical-align: middle; background-color: #008000; text-align: center; ">Komposisi</th>
                            <th style="vertical-align: middle; background-color: #008000; text-align: center; ">Pilih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // 		 $no = 1;
                            // 		 $tampil_dproyek = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang ORDER BY id_barang ASC");
                            // 		 while ($rd = mysqli_fetch_array($tampil_dproyek)) {
                            // 		 	$Q = intval(($rd['hrgjual_barang'] - $rd['hrgsat_barang']) / $rd['hrgsat_barang']);
                            // 		 	$harga1 = format_rupiah($rd['hrgjual_barang']);
                            // 		 	$komisi = format_rupiah($rd['komisi']);
                            // 		 	$hargabeli = format_rupiah(intval($rd['hrgsat_barang']));

                            // 		 	echo "<tr style='font-size: 13px;'>
                            // 		 				     <td align=center>$no</td>
                            // 		 					 <td width='20px'>$rd[kd_barang]</td>
                            // 		 					 <td>$rd[nm_barang]</td>
                            // 		 					 <td align=right id='stok_barang'><div id='stok_$rd[id_barang]'>$rd[stok_barang]</div></td>
                            // 		 					 <td align=center>$rd[sat_barang]</td>";
                            // 		 	$lupa = $_SESSION['level'];
                            // 		 	if ($lupa == 'pemilik'){
                            // 		 		echo "	 <td align=center>$hargabeli</td></td>";
                            // 		 	}
                            // 		 	if ($Q <= 0.3) {
                            // 		 		echo " <td style='background-color:#ff003f;'align='center'><strong>$harga1</strong></td> ";
                            // 		 	} elseif ($Q > 0.3 && $Q <= 1) {
                            // 		 		echo "  <td style='background-color:#f39c12;' align='center'><strong>$harga1</strong></td>";
                            // 		 	} elseif ($Q > 1 && $Q <= 2) {
                            // 		 		echo "  <td style='background-color:#00ff3f;' align='center'><strong>$harga1</strong></td>";
                            // 		 	} elseif ($Q > 2) {
                            // 		 		echo "  <td style='background-color:#00bfff;' align='center'><strong>$harga1</strong></td>";
                            // 		 	}
                            // 		 	echo "

                            // 		 					 <td align=right>$komisi</td>
                            // 		 					 <td align=center>$rd[indikasi]</td>
                            // 		 					 <td align=center>

                            // 		  <button class='btn btn-xs btn-info' id='pilihbarang'
                            // 		 	 data-id_barang='$rd[id_barang]'
                            // 		 	 data-kd_barang='$rd[kd_barang]'
                            // 		 	 data-nm_barang='$rd[nm_barang]'
                            // 		 	 data-stok_barang='$rd[stok_barang]'
                            // 		 	 data-sat_barang='$rd[sat_barang]'
                            // 		 	 data-resep ='TIDAK'
                            // 		 	 data-indikasi='$rd[indikasi]'
                            // 		 	 data-hrgjual_barang='$rd[hrgjual_barang]'
                            // 		 	 data-hrgjual_barang1='$rd[hrgjual_barang1]'
                            // 		 	 data-hrgjual_barang2='$rd[hrgjual_barang2]'
                            // 		 	 data-hrgjual_barang3='$rd[hrgjual_barang3]'
                            // 		 	 data-komisi='$rd[komisi]'>
                            // 		 	 <i class='fa fa-check'></i>
                            // 		 	 </button>

                            // 		 					</td>
                            // 		 				</tr>";
                            // 		 	$no++;
                            // 		 }
                           // echo "</tbody></table>";
                            ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- end modal item -->

<!-- modal scan barcode -->
<div id="ModalScanBarcode" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SCAN BARCODE (KAMERA HP)</h4>
            </div>
            <div class="modal-body">
                <div id="barcodeScannerReader" style="width:100%; min-height:260px; max-height:320px; background:#000;"></div>
                <video id="barcodeScannerPreview" autoplay playsinline muted style="display:none; width:100%; max-height:320px; background:#000;"></video>
                <p id="barcodeScannerStatus" style="margin-top:10px; margin-bottom:0; font-size:12px; color:#666;">Arahkan kamera ke barcode item.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal scan barcode -->

<!-- modal batch -->
<div id="ModalBatch" class="modal fade" role="dialog">
    <div class="modal-lg modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PILIH NOMOR BATCH</h4>
            </div>
	  
            <div class="modal-body table-responsive">
		        <table id="table_batch" class="table table-condensed table-bordered table-striped table-hover" >
		            <thead>
						<tr class="judul-table">
							<th style="vertical-align: middle; background-color: #008000; text-align: center; ">No</th>
							<th style="vertical-align: middle; background-color: #008000; text-align: left; ">Kode Barang</th>
							<th style="vertical-align: middle; background-color: #008000; text-align: left; ">Nama Barang</th>
							<th style="vertical-align: middle; background-color: #008000; text-align: center; ">Nomor Batch</th>
							<th style="vertical-align: middle; background-color: #008000; text-align: center; ">Exp Date</th>
							<th style="vertical-align: middle; background-color: #008000; text-align: center; ">Qty</th>
							<th style="vertical-align: middle; background-color: #008000; text-align: center; ">Pilih</th>
                        </tr>
					</thead>
					<tbody>
					
					</tbody>
				</table>
            </div>
        </div>
    </div>
</div>
<!-- end modal batch -->

<script type="text/javascript">
    $(function() {
        $(".datepicker").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
    });
</script>

<script src="assets/js/html5-qrcode.min.js" type="text/javascript"></script>

<script>
    var AUTOCOMPLETE_LIMIT = 8;

    function parseJsonSafe(data) {
        if (typeof data === 'string') {
            try {
                return $.parseJSON(data);
            } catch (e) {
                return [];
            }
        }
        return data || [];
    }

    function normalizeAutocompleteItems(data) {
        var items = parseJsonSafe(data);
        if (!$.isArray(items)) {
            return [];
        }
        return items.slice(0, AUTOCOMPLETE_LIMIT);
    }

    function initAutocompleteSafe(selector, endpoint, method) {
        var $input = $(selector);
        if (!$input.length || $input.data('ac-initialized')) {
            return;
        }

        if ($.fn.typeahead) {
            $input.typeahead({
                source: function(query, process) {
                    query = $.trim(query || '');
                    if (query.length === 0) {
                        return process([]);
                    }

                    var payload = {
                        query: query
                    };
                    var handler = function(data) {
                        return process(normalizeAutocompleteItems(data));
                    };

                    if (method === 'get') {
                        return $.get(endpoint, payload, handler);
                    }
                    return $.post(endpoint, payload, handler);
                }
            });
            $input.data('ac-initialized', true);
            return;
        }

        if ($.ui && $.ui.autocomplete) {
            $input.autocomplete({
                minLength: 1,
                source: function(request, response) {
                    var term = $.trim(request.term || '');
                    if (term.length === 0) {
                        response([]);
                        return;
                    }

                    var payload = {
                        query: term
                    };

                    $.ajax({
                        url: endpoint,
                        type: method || 'post',
                        data: payload,
                        success: function(data) {
                            response(normalizeAutocompleteItems(data));
                        },
                        error: function() {
                            response([]);
                        }
                    });
                }
            });
            $input.data('ac-initialized', true);
            return;
        }

        console.warn('Plugin autocomplete/typeahead tidak ditemukan untuk', selector);
    }

    function closeAutocompleteSuggestions(selector) {
        var $input = $(selector);

        if ($.ui && $.ui.autocomplete && $input.data('ui-autocomplete')) {
            $input.autocomplete('close');
        }

        $('.tt-menu, .ui-autocomplete, .dropdown-menu.typeahead, ul.typeahead.dropdown-menu').hide();

        $input.focus();
    }

    var scannerStream = null;
    var scannerInterval = null;
    var barcodeDetectorInstance = null;
    var html5QrScanner = null;
    var html5QrScannerActive = false;
    var barcodeScanLocked = false;
    var scannerMode = null;
    var html5QrScriptPromise = null;

    function setScannerStatus(message, isError) {
        var statusEl = document.getElementById('barcodeScannerStatus');
        if (!statusEl) {
            return;
        }
        statusEl.innerText = message;
        statusEl.style.color = isError ? '#b90000' : '#666';
    }

    function stopBarcodeScanner() {
        barcodeScanLocked = false;

        if (scannerInterval) {
            clearInterval(scannerInterval);
            scannerInterval = null;
        }

        if (html5QrScanner && html5QrScannerActive) {
            try {
                html5QrScanner.stop().then(function() {
                    html5QrScanner.clear();
                }).catch(function() {
                    try {
                        html5QrScanner.clear();
                    } catch (e) {}
                });
            } catch (e) {}
        }
        html5QrScannerActive = false;
        scannerMode = null;

        if (scannerStream) {
            scannerStream.getTracks().forEach(function(track) {
                track.stop();
            });
            scannerStream = null;
        }

        var video = document.getElementById('barcodeScannerPreview');
        if (video) {
            video.srcObject = null;
            video.style.display = 'none';
        }

        var reader = document.getElementById('barcodeScannerReader');
        if (reader) {
            reader.style.display = 'block';
        }
    }

    function triggerBarangByKode(kd_brg) {
        if (!kd_brg) {
            return;
        }

        $('#kd_barang').val(kd_brg);

        $.ajax({
            url: 'modul/mod_trkasir/autobarang.php',
            type: 'post',
            data: {
                'kd_brg': kd_brg
            },
        }).success(function(data) {

            var json = data;
            var res1 = json.replace("[", "");
            var res2 = res1.replace("]", "");
            datab = JSON.parse(res2);
            document.getElementById('id_barang').value = datab.id_barang;
            document.getElementById('nmbrg_dtrkasir').value = datab.nm_barang;
            document.getElementById('stok_barang').value = datab.stok_barang;
            document.getElementById('qty_dtrkasir').value = "1";
            document.getElementById('sat_dtrkasir').value = datab.sat_barang;
            document.getElementById('hrgjual_dtrkasir').value = datab.hrgjual_barang;

        });
    }

    function loadHtml5QrcodeScript() {
        if (window.Html5Qrcode) {
            return Promise.resolve();
        }

        if (html5QrScriptPromise) {
            return html5QrScriptPromise;
        }

        html5QrScriptPromise = new Promise(function(resolve, reject) {
            var script = document.createElement('script');
            script.src = 'assets/js/html5-qrcode.min.js';
            script.async = true;
            script.onload = function() {
                resolve();
            };
            script.onerror = function() {
                reject(new Error('Gagal memuat html5-qrcode'));
            };
            document.head.appendChild(script);
        });

        return html5QrScriptPromise;
    }

    async function startHtml5QrcodeScanner() {
        if (!window.Html5Qrcode) {
            throw new Error('html5-qrcode belum tersedia');
        }

        var video = document.getElementById('barcodeScannerPreview');
        var reader = document.getElementById('barcodeScannerReader');
        if (video) {
            video.style.display = 'none';
        }
        if (reader) {
            reader.style.display = 'block';
        }

        html5QrScanner = new Html5Qrcode('barcodeScannerReader');
        var config = {
            fps: 10,
            qrbox: {
                width: 260,
                height: 120
            },
            aspectRatio: 1.7778,
            formatsToSupport: [
                Html5QrcodeSupportedFormats.CODE_128,
                Html5QrcodeSupportedFormats.EAN_13,
                Html5QrcodeSupportedFormats.EAN_8,
                Html5QrcodeSupportedFormats.UPC_A,
                Html5QrcodeSupportedFormats.UPC_E,
                Html5QrcodeSupportedFormats.CODABAR,
                Html5QrcodeSupportedFormats.CODE_39,
                Html5QrcodeSupportedFormats.CODE_93,
                Html5QrcodeSupportedFormats.ITF,
                Html5QrcodeSupportedFormats.QR_CODE
            ],
            experimentalFeatures: {
                useBarCodeDetectorIfSupported: true
            }
        };

        await html5QrScanner.start({
            facingMode: {
                ideal: 'environment'
            }
        }, config, function(decodedText) {
            if (barcodeScanLocked) {
                return;
            }

            barcodeScanLocked = true;
            var hasilScan = $.trim(decodedText || '');
            if (!hasilScan) {
                barcodeScanLocked = false;
                return;
            }

            setScannerStatus('Barcode terdeteksi: ' + hasilScan, false);
            triggerBarangByKode(hasilScan);
            $('#ModalScanBarcode').modal('hide');
        }, function() {
            // ignore per-frame decode error
        });

        html5QrScannerActive = true;
        scannerMode = 'html5-qrcode';
        setScannerStatus('Scanner aktif. Arahkan barcode ke area kamera.', false);
    }

    async function startBarcodeDetectorScanner() {
        if (!window.BarcodeDetector) {
            setScannerStatus('Scanner tidak didukung browser ini. Coba Chrome Android terbaru.', true);
            return;
        }

        var video = document.getElementById('barcodeScannerPreview');
        var reader = document.getElementById('barcodeScannerReader');
        if (reader) {
            reader.style.display = 'none';
        }
        if (video) {
            video.style.display = 'block';
        }

        try {
            barcodeDetectorInstance = new BarcodeDetector({
                formats: ['code_128', 'ean_13', 'ean_8', 'upc_a', 'upc_e', 'codabar', 'qr_code']
            });
        } catch (e) {
            setScannerStatus('Format barcode tidak didukung browser.', true);
            return;
        }

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            setScannerStatus('Kamera tidak tersedia di browser ini.', true);
            return;
        }

        try {
            scannerStream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: {
                        ideal: 'environment'
                    }
                },
                audio: false
            });

            video.srcObject = scannerStream;
            await video.play();
            setScannerStatus('Arahkan kamera ke barcode item.', false);

            scannerMode = 'barcode-detector';
            scannerInterval = setInterval(async function() {
                try {
                    if (!video || video.readyState < 2 || !barcodeDetectorInstance) {
                        return;
                    }

                    var detected = await barcodeDetectorInstance.detect(video);
                    if (detected && detected.length > 0 && detected[0].rawValue) {
                        if (barcodeScanLocked) {
                            return;
                        }

                        barcodeScanLocked = true;
                        var hasilScan = $.trim(detected[0].rawValue);
                        setScannerStatus('Barcode terdeteksi: ' + hasilScan, false);
                        $('#ModalScanBarcode').modal('hide');
                        triggerBarangByKode(hasilScan);
                    }
                } catch (scanErr) {
                    setScannerStatus('Gagal membaca barcode. Coba arahkan ulang kamera.', true);
                }
            }, 350);

        } catch (err) {
            setScannerStatus('Tidak bisa mengakses kamera. Pastikan izin kamera diaktifkan.', true);
        }
    }

    async function startBarcodeScanner() {
        stopBarcodeScanner();
        setScannerStatus('Menyiapkan scanner kamera...', false);

        try {
            await loadHtml5QrcodeScript();
            await startHtml5QrcodeScanner();
        } catch (err) {
            setScannerStatus('Fallback ke mode scanner bawaan browser...', false);
            await startBarcodeDetectorScanner();
        }
    }

    $(document).ready(function() {
        tabel_detail();
        $("#example").DataTable();
        initAutocompleteSafe('#nmbrg_dtrkasir', 'modul/mod_trkasir/autonamabarang.php', 'post');
    });

    $('#ModalScanBarcode').on('shown.bs.modal', function() {
        startBarcodeScanner();
    });

    $('#ModalScanBarcode').on('hidden.bs.modal', function() {
        stopBarcodeScanner();
    });

    $('select[name="jns_transaksi"]').on('change', function() {
        var jns_transaksi = $('select[name="jns_transaksi"]').val();
        var kd_barang = $('#kd_barang').val();
        let nm_barang = $('#nmbrg_dtrkasir').val();

        if (kd_barang != '') {

            $.ajax({
                url: 'modul/mod_trkasir/autonamabarang_enter.php',
                type: 'post',
                dataType: 'json',
                data: {
                    'nm_barang': nm_barang
                },
            }).done(function(data) {
                let qty_default = "1";

                for (let i = 0; i < data.length; i++) {
                    data = data[i];
                    // 1 = Grosir
                    // 2 = Retail
                    if (jns_transaksi == '6') {
                        document.getElementById('id_barang').value = data.id_barang;
                        document.getElementById('kd_barang').value = data.kd_barang;
                        document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                        document.getElementById('stok_barang').value = data.stok_barang;
                        document.getElementById('qty_dtrkasir').value = qty_default;
                        document.getElementById('sat_dtrkasir').value = data.sat_barang;
                        document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang5;
                    } else if (jns_transaksi == '5') {
                        document.getElementById('id_barang').value = data.id_barang;
                        document.getElementById('kd_barang').value = data.kd_barang;
                        document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                        document.getElementById('stok_barang').value = data.stok_barang;
                        document.getElementById('qty_dtrkasir').value = qty_default;
                        document.getElementById('sat_dtrkasir').value = data.sat_barang;
                        document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang4;
                    } else if (jns_transaksi == '4') {
                        document.getElementById('id_barang').value = data.id_barang;
                        document.getElementById('kd_barang').value = data.kd_barang;
                        document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                        document.getElementById('stok_barang').value = data.stok_barang;
                        document.getElementById('qty_dtrkasir').value = qty_default;
                        document.getElementById('sat_dtrkasir').value = data.sat_barang;
                        document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang3;
                    } else if (jns_transaksi == '3') {
                        document.getElementById('id_barang').value = data.id_barang;
                        document.getElementById('kd_barang').value = data.kd_barang;
                        document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                        document.getElementById('stok_barang').value = data.stok_barang;
                        document.getElementById('qty_dtrkasir').value = qty_default;
                        document.getElementById('sat_dtrkasir').value = data.sat_barang;
                        document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang2;
                    } else if (jns_transaksi == '2') {
                        document.getElementById('id_barang').value = data.id_barang;
                        document.getElementById('kd_barang').value = data.kd_barang;
                        document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                        document.getElementById('stok_barang').value = data.stok_barang;
                        document.getElementById('qty_dtrkasir').value = qty_default;
                        document.getElementById('sat_dtrkasir').value = data.sat_barang;
                        document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang1;
                    } else {
                        document.getElementById('id_barang').value = data.id_barang;
                        document.getElementById('kd_barang').value = data.kd_barang;
                        document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                        document.getElementById('stok_barang').value = data.stok_barang;
                        document.getElementById('qty_dtrkasir').value = qty_default;
                        document.getElementById('sat_dtrkasir').value = data.sat_barang;
                        document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang;
                    }
                }

            }).fail(function(xhr) {
                console.log('Gagal load data barang:', xhr.responseText);
            });
        }
    });

    initAutocompleteSafe('#nmbrg_dtrkasir', 'modul/mod_trkasir/autonamabarang.php', 'post');

    // event enter nama obat
    $(document).ready(function() {
        $('#nmbrg_dtrkasir').on('keydown', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                let nm_barang = $('#nmbrg_dtrkasir').val();
                let jns_transaksi = $('select[name="jns_transaksi"]').val();

                $.ajax({
                    url: 'modul/mod_trkasir/autonamabarang_enter.php',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'nm_barang': nm_barang
                    },
                }).done(function(data) {
                    let qty_default = "1";
                    for (let i = 0; i < data.length; i++) {
                        data = data[i];
                        // 1 = Grosir
                        // 2 = Retail

                        if (jns_transaksi == '6') {
                            document.getElementById('id_barang').value = data.id_barang;
                            document.getElementById('kd_barang').value = data.kd_barang;
                            document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                            document.getElementById('stok_barang').value = data.stok_barang;
                            document.getElementById('qty_dtrkasir').value = qty_default;
                            document.getElementById('sat_dtrkasir').value = data.sat_barang;
                            document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang5;
                        } else if (jns_transaksi == '5') {
                            document.getElementById('id_barang').value = data.id_barang;
                            document.getElementById('kd_barang').value = data.kd_barang;
                            document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                            document.getElementById('stok_barang').value = data.stok_barang;
                            document.getElementById('qty_dtrkasir').value = qty_default;
                            document.getElementById('sat_dtrkasir').value = data.sat_barang;
                            document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang4;
                        } else if (jns_transaksi == '4') {
                            document.getElementById('id_barang').value = data.id_barang;
                            document.getElementById('kd_barang').value = data.kd_barang;
                            document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                            document.getElementById('stok_barang').value = data.stok_barang;
                            document.getElementById('qty_dtrkasir').value = qty_default;
                            document.getElementById('sat_dtrkasir').value = data.sat_barang;
                            document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang3;
                        } else if (jns_transaksi == '3') {
                            document.getElementById('id_barang').value = data.id_barang;
                            document.getElementById('kd_barang').value = data.kd_barang;
                            document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                            document.getElementById('stok_barang').value = data.stok_barang;
                            document.getElementById('qty_dtrkasir').value = qty_default;
                            document.getElementById('sat_dtrkasir').value = data.sat_barang;
                            document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang2;
                        } else if (jns_transaksi == '2') {
                            document.getElementById('id_barang').value = data.id_barang;
                            document.getElementById('kd_barang').value = data.kd_barang;
                            document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                            document.getElementById('stok_barang').value = data.stok_barang;
                            document.getElementById('qty_dtrkasir').value = qty_default;
                            document.getElementById('sat_dtrkasir').value = data.sat_barang;
                            document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang1;
                        } else {
                            document.getElementById('id_barang').value = data.id_barang;
                            document.getElementById('kd_barang').value = data.kd_barang;
                            document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                            document.getElementById('stok_barang').value = data.stok_barang;
                            document.getElementById('qty_dtrkasir').value = qty_default;
                            document.getElementById('sat_dtrkasir').value = data.sat_barang;
                            document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang;
                        }
                    }

                }).fail(function(xhr) {
                    console.log('Gagal load data barang:', xhr.responseText);
                }).always(function() {
                    closeAutocompleteSuggestions('#nmbrg_dtrkasir');
                });
            }
        })
    });

    $('#nmbrg_dtrkasir_enter').on('click', function() {
        let nm_barang = $('#nmbrg_dtrkasir').val();
        let jns_transaksi = $('select[name="jns_transaksi"]').val();

        $.ajax({
            url: 'modul/mod_trkasir/autonamabarang_enter.php',
            type: 'post',
            dataType: 'json',
            data: {
                'nm_barang': nm_barang
            },
        }).done(function(data) {
            let qty_default = "1";

            for (let i = 0; i < data.length; i++) {
                data = data[i];

                if (jns_transaksi == '6') {
                    document.getElementById('id_barang').value = data.id_barang;
                    document.getElementById('kd_barang').value = data.kd_barang;
                    document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                    document.getElementById('stok_barang').value = data.stok_barang;
                    document.getElementById('qty_dtrkasir').value = qty_default;
                    document.getElementById('sat_dtrkasir').value = data.sat_barang;
                    document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang5;
                } else if (jns_transaksi == '5') {
                    document.getElementById('id_barang').value = data.id_barang;
                    document.getElementById('kd_barang').value = data.kd_barang;
                    document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                    document.getElementById('stok_barang').value = data.stok_barang;
                    document.getElementById('qty_dtrkasir').value = qty_default;
                    document.getElementById('sat_dtrkasir').value = data.sat_barang;
                    document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang4;
                } else if (jns_transaksi == '4') {
                    document.getElementById('id_barang').value = data.id_barang;
                    document.getElementById('kd_barang').value = data.kd_barang;
                    document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                    document.getElementById('stok_barang').value = data.stok_barang;
                    document.getElementById('qty_dtrkasir').value = qty_default;
                    document.getElementById('sat_dtrkasir').value = data.sat_barang;
                    document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang3;
                } else if (jns_transaksi == '3') {
                    document.getElementById('id_barang').value = data.id_barang;
                    document.getElementById('kd_barang').value = data.kd_barang;
                    document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                    document.getElementById('stok_barang').value = data.stok_barang;
                    document.getElementById('qty_dtrkasir').value = qty_default;
                    document.getElementById('sat_dtrkasir').value = data.sat_barang;
                    document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang2;
                } else if (jns_transaksi == '2') {
                    document.getElementById('id_barang').value = data.id_barang;
                    document.getElementById('kd_barang').value = data.kd_barang;
                    document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                    document.getElementById('stok_barang').value = data.stok_barang;
                    document.getElementById('qty_dtrkasir').value = qty_default;
                    document.getElementById('sat_dtrkasir').value = data.sat_barang;
                    document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang1;
                } else {
                    document.getElementById('id_barang').value = data.id_barang;
                    document.getElementById('kd_barang').value = data.kd_barang;
                    document.getElementById('nmbrg_dtrkasir').value = data.nm_barang;
                    document.getElementById('stok_barang').value = data.stok_barang;
                    document.getElementById('qty_dtrkasir').value = qty_default;
                    document.getElementById('sat_dtrkasir').value = data.sat_barang;
                    document.getElementById('hrgjual_dtrkasir').value = data.hrgjual_barang;
                }

            }

        }).fail(function(xhr) {
            console.log('Gagal load data barang:', xhr.responseText);
        }).always(function() {
            closeAutocompleteSuggestions('#nmbrg_dtrkasir');
        });
    })

    $(document).on('click', '#kode', function() {
        $("#example").DataTable().destroy();

        $("#example").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "modul/mod_trkasir/barang-serverside.php?action=table_data",
                "dataType": "JSON",
                "type": "POST"
            },
            "rowCallback": function(row, data, index) {
                let q = (data['hrgjual_barang'] - data['hrgsat_barang']) / data['hrgsat_barang'];

                if (q <= 0.2) {
                    $(row).find('td:eq(6)').css('background-color', '#ff003f');
                    $(row).find('td:eq(6)').css('color', '#ffffff');
                } else if (q > 0.2 && q <= 0.25) {
                    $(row).find('td:eq(6)').css('background-color', '#f39c12');
                    $(row).find('td:eq(6)').css('color', '#ffffff');

                } else if (q > 0.25 && q <= 0.3) {
                    $(row).find('td:eq(6)').css('background-color', '#00ff3f');
                    $(row).find('td:eq(6)').css('color', '#ffffff');

                } else if (q > 0.3) {
                    $(row).find('td:eq(6)').css('background-color', '#00bfff');
                    $(row).find('td:eq(6)').css('color', '#ffffff');

                }
            },
            columns: [{
                    "data": "no",
                    "className": 'text-center',
                },
                {
                    "data": "kd_barang"
                },
                {
                    "data": "nm_barang"
                },
                {
                    "data": "stok_barang",
                    "className": 'text-center',
                },
                {
                    "data": "sat_barang",
                    "className": 'text-center',
                },
                {
                    "data": "hrgsat_barang",
                    "className": 'text-right',
                    "visible": <?= ($_SESSION['level'] == 'pemilik') ? 'true' : 'false'; ?>,
                    "render": function(data, type, row) {
                        return formatRupiah(data);
                    }
                },
                {
                    "data": "hrgjual_barang",
                    "className": 'text-left',
                    // "render": function(data, type, row) {
                    //     return formatRupiah(data);
                    // }
                },
                {
                    "data": "komisi",
                    "className": 'text-right',
                    "render": function(data, type, row) {
                        return formatRupiah(data);
                    }
                },
                {
                    "data": "indikasi",
                    "className": 'text-center'
                },
                {
                    "data": "pilih",
                    "className": 'text-center'
                },
            ],
            "footerCallback": function(row, data, start, end, display) {
                console.log(row);
            }
        })

    });

    $(document).on('click', '#pilihbarang', function() {

        var id_barang       = $(this).data('id_barang');
        var kd_barang       = $(this).data('kd_barang');
        var nm_barang       = $(this).data('nm_barang');
        var stok_barang     = $(this).data('stok_barang');
        var sat_barang      = $(this).data('sat_barang');
        var hrgjual_barang  = $(this).data('hrgjual_barang');
        // var hrgjual_barang1 = $(this).data('hrgjual_barang1');
        // var hrgjual_barang2 = $(this).data('hrgjual_barang2');
        // var hrgjual_barang3 = $(this).data('hrgjual_barang3');
        var komisi_dtrkasir = $(this).data('komisi');
        var qty_default = "1";
        let jns_transaksi = $('select[name="jns_transaksi"]').val();


        document.getElementById('id_barang').value = id_barang;
        document.getElementById('kd_barang').value = kd_barang;
        document.getElementById('nmbrg_dtrkasir').value = nm_barang;
        document.getElementById('stok_barang').value = stok_barang;
        document.getElementById('qty_dtrkasir').value = qty_default;
        document.getElementById('sat_dtrkasir').value = sat_barang;
        if (jns_transaksi == '6') {
            document.getElementById('hrgjual_dtrkasir').value = hrgjual_barang5;
        } else if (jns_transaksi == '5') {
            document.getElementById('hrgjual_dtrkasir').value = hrgjual_barang4;
        } else if (jns_transaksi == '4') {
            document.getElementById('hrgjual_dtrkasir').value = hrgjual_barang3;
        } else if (jns_transaksi == '3') {
            document.getElementById('hrgjual_dtrkasir').value = hrgjual_barang2;
        } else if (jns_transaksi == '2') {
            document.getElementById('hrgjual_dtrkasir').value = hrgjual_barang1;
        } else {
            document.getElementById('hrgjual_dtrkasir').value = hrgjual_barang;
        }
        document.getElementById('komisi_dtrkasir').value = komisi_dtrkasir;

        //hilangkan modal
        $(".close").click();

    });


    function simpan_detail() {

        let kd_trkasir = document.getElementById('kd_trkasir').value;
        let id_barang = document.getElementById('id_barang').value;
        let kd_barang = document.getElementById('kd_barang').value;
        let nmbrg_dtrkasir = document.getElementById('nmbrg_dtrkasir').value;
        let stok_barang = document.getElementById('stok_barang').value;
        let qty_dtrkasir = document.getElementById('qty_dtrkasir').value;
        let sat_dtrkasir = document.getElementById('sat_dtrkasir').value;
        let hrgjual_dtrkasir = document.getElementById('hrgjual_dtrkasir').value;
        let disc = document.getElementById('disc').value;
        let no_batch = document.getElementById('batch').value;
        let exp_date = document.getElementById('exp_date').value;
        let resep = document.getElementById('resep').value;
        let komisi_dtrkasir = document.getElementById('komisi_dtrkasir').value;
        let id_admin = document.getElementById('id_admin').value;
        var jns_transaksi = $('select[name="jns_transaksi"]').val();

        if (nmbrg_dtrkasir == "") {
            alert('Belum ada Item terpilih');
        } else if (qty_dtrkasir == "") {
            alert('Qty tidak boleh kosong');
        } 
        // else if (parseInt(stok_barang) < parseInt(qty_dtrkasir)) {
        //     alert('Stok barang tidak mencukupi');
        // } 
        else if (parseInt(disc) > 100) {
            alert('Input Diskon lebih kecil dari 100');
        }    
        else if (level == "petugas" && resep == "YA") {
    			alert('Transaksi resep hanya bisa di proses Apoteker');
    //     } 
    //     else if (no_batch == "" && exp_date == "") {
  		// 	alert('No. batch tidak boleh kosong');
        } else {
            $.ajax({
                type: 'post',
                url: "modul/mod_trkasir/simpandetail_trkasir.php",
                data: {
                    'kd_trkasir': kd_trkasir,
                    'id_barang': id_barang,
                    'kd_barang': kd_barang,
                    'nmbrg_dtrkasir': nmbrg_dtrkasir,
                    'qty_dtrkasir': qty_dtrkasir,
                    'sat_dtrkasir': sat_dtrkasir,
                    'hrgjual_dtrkasir': hrgjual_dtrkasir,
                    'disc': disc,
                    'no_batch': no_batch,
                    'exp_date': exp_date,
                    'resep': resep,
                    'komisi_dtrkasir': komisi_dtrkasir,
                    'id_admin': id_admin,
                    'tipe': jns_transaksi,
                },
                success: function(data) {
                    //alert('Tambah data detail berhasil');
                    document.getElementById("id_barang").value = "";
                    document.getElementById("kd_barang").value = "";
                    document.getElementById("nmbrg_dtrkasir").value = "";
                    document.getElementById("qty_dtrkasir").value = "";
                    document.getElementById("sat_dtrkasir").value = "";
                    document.getElementById("hrgjual_dtrkasir").value = "";
                    document.getElementById("disc").value = "";
                    document.getElementById("batch").value = "";
                    document.getElementById("exp_date").value = "";
                    document.getElementById("komisi_dtrkasir").value = "";

                    document.getElementById("resep").value = "TIDAK";
                    

                    // let displayStok = stok_barang - qty_dtrkasir;
                    // $('#stok_' + id_barang).html(displayStok);
                    $('#nmbrg_dtrkasir').focus();
                    
                    tabel_detail();
                    console.log(data);
                }
            });
        }
    }



    $(document).on('click', '#hapusdetail', function() {

        var id_dtrkasir = $(this).data('id_dtrkasir');
        var id_barang = $(this).data('id_barang');

        $.ajax({
            type: 'post',
            url: "modul/mod_trkasir/hapusdetail_trkasir.php",
            data: {
                id_dtrkasir: id_dtrkasir
            },

            success: function(data) {
                //setelah simpan data, tabel_detail data terbaru
                //alert('Hapus data detail berhasil');
                tabel_detail();
                $('#stok_' + id_barang).html(data);
                //hilangkan modal
                $(".close").click();
            }
        });

    });



    //fungsi tabel detail
    function tabel_detail() {

        var kd_trkasir = document.getElementById('kd_trkasir').value;
        var stt_aksi = document.getElementById('stt_aksi').value;

        $.ajax({
            url: 'modul/mod_trkasir/tbl_detail.php',
            type: 'post',
            data: {
                'kd_trkasir': kd_trkasir,
                'stt_aksi': stt_aksi
            },
            success: function(data) {
                $('#tabeldata').html(data);
            }

        });
    }

    //auto pelanggan
    initAutocompleteSafe('#nm_pelanggan', 'modul/mod_trkasir/autopelanggan.php', 'get');


    //enter pelanggan
    $('#nm_pelanggan').keydown(function(e) {
        if (e.which == 13) { // e.which == 13 merupakan kode yang mendeteksi ketika anda   // menekan tombol enter di keyboard
            //letakan fungsi anda disini

            var nm_pelanggan = $("#nm_pelanggan").val();
            $.ajax({
                url: 'modul/mod_trkasir/autopelanggan_enter.php',
                type: 'post',
                data: {
                    'nm_pelanggan': nm_pelanggan
                },
            }).success(function(data) {

                var json = data;
                //replace array [] menjadi ''
                var res1 = json.replace("[", "");
                var res2 = res1.replace("]", "");
                //INI CONTOH ARRAY JASON const json = '{"result":true, "count":42}';
                datab = JSON.parse(res2);
                document.getElementById('nm_pelanggan').value = datab.nm_pelanggan;
                document.getElementById('tlp_pelanggan').value = datab.tlp_pelanggan;
                document.getElementById('alamat_pelanggan').value = datab.alamat_pelanggan;
            });

        }
    });

    $('#kd_barang').keydown(function(e) {
        if (e.which == 13) { // e.which == 13 merupakan kode yang mendeteksi ketika anda   // menekan tombol enter di keyboard
            //letakan fungsi anda disini
            e.preventDefault();
            var kd_brg = $("#kd_barang").val();
            triggerBarangByKode(kd_brg);

        }
    });


    function simpan_transaksi() {

        var id_trkasir = document.getElementById('id_trkasir').value;
        var kd_trkasir = document.getElementById('kd_trkasir').value;
        var id_user = document.getElementById('id_user').value;
        var petugas = document.getElementById('petugas').value;
        var shift = document.getElementById('shift').value;
        var tgl_trkasir = document.getElementById('tgl_trkasir').value;
        var nm_pelanggan = document.getElementById('nm_pelanggan').value;
        var tlp_pelanggan = document.getElementById('tlp_pelanggan').value;
        var alamat_pelanggan = document.getElementById('alamat_pelanggan').value;
        var kodetx = document.getElementById('kodetx').value;
        var ttl_trkasir = document.getElementById('ttl_trkasir').value;
        var diskon2 = document.getElementById('diskon2').value;
        var dp_bayar = document.getElementById('dp_bayar').value;
        var sisa_bayar = document.getElementById('sisa_bayar').value;
        
        var ket_trkasir = document.getElementById('ket_trkasir').value;
        var stt_aksi = document.getElementById('stt_aksi').value;
        var id_carabayar = document.getElementById('id_carabayar').value;


        var ttl_trkasir1 = ttl_trkasir.replace(".", "");
        var dp_bayar1 = dp_bayar.replace(".", "");
        var sisa_bayar1 = sisa_bayar.replace(".", "");

        var ttl_trkasir1x = ttl_trkasir1.replace(".", "");
        var dp_bayar1x = dp_bayar1.replace(".", "");
        var sisa_bayar1x = sisa_bayar1.replace(".", "");


        if (parseInt(dp_bayar1x) < parseInt(ttl_trkasir1x)) {
            alert('Input Nominal Bayar Lebih besar dari harga');
        } else {

            $.ajax({

                type: 'post',
                url: "modul/mod_trkasir/aksi_trkasir.php",
                dataType: 'json',
                data: {
                    'id_trkasir': id_trkasir,
                    'kd_trkasir': kd_trkasir,
                    'id_user': id_user,
                    'tgl_trkasir': tgl_trkasir,
                    'petugas': petugas,
                    'shift': shift,
                    'nm_pelanggan': nm_pelanggan,
                    'tlp_pelanggan': tlp_pelanggan,
                    'alamat_pelanggan': alamat_pelanggan,
                    'kodetx': kodetx,
                    'ttl_trkasir': ttl_trkasir1x,
                    'diskon2': diskon2,
                    'dp_bayar': dp_bayar1x,
                    'sisa_bayar': sisa_bayar1x,
                    'ket_trkasir': ket_trkasir,
                    'stt_aksi': stt_aksi,
                    'id_carabayar': id_carabayar
                },
                success: function(data) {
                    console.log(data);
                    if (data.message == 'success') {
                        window.open('modul/mod_laporan/struk.php?kd_trkasir=' + kd_trkasir, 'nama window', 'width=400,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no, scrollbars=no,resizable=yes,copyhistory=no');
                        alert('Proses berhasil !');
                        window.location = 'media_admin.php?module=trkasir';
                        
                    } else {
                        window.location.reload();
                    }

                }

                //	success: function(data) {

                //	window.open('modul/mod_laporan/struk.php?kd_trkasir='+kd_trkasir, 'nama window','
                //  	width=400,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no,
                //  	scrollbars=no,resizable=yes,copyhistory=no');
                //	alert('Proses berhasil !');window.location='media_admin.php?module=trkasir';

                //	}
            });
        }

    }

    $(document).on('click', '#caribatch', function() {
	    let kd_barang = $('#kd_barang').val();
		$("#table_batch").DataTable().destroy();

		$("#table_batch").DataTable({
			processing: false,
			serverSide: true,
			ajax: {
				"url": "modul/mod_trkasir/batch_serverside.php?action=table_data&id="+kd_barang,
				"dataType": "JSON",
				"type": "POST"
			},
			
			columns: [{
					"data": "no",
					"className": 'text-center',
				},
				{
					"data": "kd_barang"
				},
				{
					"data": "nm_barang"
				},
				{
					"data": "no_batch",
					"className": 'text-center',
				},
				{
					"data": "exp_date",
					"className": 'text-center',
				},
				{
					"data": "qty",
					"className": 'text-center',
				},
				{
					"data": "pilih",
					"className": 'text-center'
				},
			],
			
		})

	});
	
	$(document).on('click', '#pilihbatch', function(){
        var id_batch    = $(this).data('id_batch');
        var kd_barang   = $(this).data('kd_barang');
	    var nm_barang   = $(this).data('nm_barang');
	    var no_batch    = $(this).data('no_batch');
	    var exp_date    = $(this).data('exp_date');
	    
	    document.getElementById("batch").value = no_batch;
	    document.getElementById("exp_date").value = exp_date;
	    
	    $(".close").click();
    });

    function cetakstruk() {

        var kd_trkasir = document.getElementById('kd_trkasir').value;

        //window.open("modul/mod_laporan/struk.php?kd_trkasir="+kd_trkasir,"_blank");
        window.open('modul/mod_laporan/struk.php?kd_trkasir=' + kd_trkasir, 'nama window', 'width=400,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no, scrollbars=no,resizable=yes,copyhistory=no');

    }

    function cetakstrukresep() {

        var kd_trkasir = document.getElementById('kd_trkasir').value;

        //window.open("modul/mod_laporan/struk.php?kd_trkasir="+kd_trkasir,"_blank");
        window.open('modul/mod_laporan/strukresep.php?kd_trkasir=' + kd_trkasir, 'nama window', 'width=400,height=700,toolbar=no,location=no,directories=no,status=no,menubar=no, scrollbars=no,resizable=yes,copyhistory=no');

    }
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'F1' || event.keyCode === 112) {
            event.preventDefault(); // Mencegah help browser muncul
            simpan_detail();
        }
    });
	
	document.addEventListener('keydown', function(event) {
        if (event.key === 'F2' || event.keyCode === 113) {
            event.preventDefault(); // Mencegah help browser muncul
            $('#dp_bayar').focus();
        }
    });
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'F3' || event.keyCode === 114) {
            event.preventDefault(); // Mencegah help browser muncul
            // simpan_detail();
            simpan_transaksi();
        }
    });
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'F4' || event.keyCode === 115) {
            event.preventDefault(); // Mencegah action default
            // Shortcut untuk case "tambah"
            window.location = '?module=trkasir&act=tambah';
        }
    });

</script>
<script>
    function confirmdelete(delUrl) {
        if (confirm("Anda yakin ingin menghapus?")) {
            const params = new URLSearchParams(window.location.search);
            const module = params.get("module");
            document.location = delUrl+"&module2="+module;
        }
    }
</script>
<?php if ($_GET['module'] == 'home') { ?>
<script type="text/javascript">
    window.onload = function() {
        jam();
    }

    function jam() {
        var e = document.getElementById('jam'),
            d = new Date(),
            h, m, s;
        h = d.getHours();
        m = set(d.getMinutes());
        s = set(d.getSeconds());

        e.innerHTML = h + ':' + m + ':' + s;

        setTimeout('jam()', 1000);
    }

    function set(e) {
        e = e < 10 ? '0' + e : e;
        return e;
    }
</script>
<?php } ?>
<?php
include "../configurasi/koneksi.php";
include "../configurasi/library.php";
include "../configurasi/fungsi_indotgl.php";
include "../configurasi/fungsi_rupiah.php";
include "../configurasi/fungsi_combobox.php";
include "../configurasi/fungsi_logs.php";
include "../configurasi/class_paging.php";
$tgl_awal = date('d-m-Y');


// Bagian Home
if ($_GET['module'] == 'home') {

?>
    <!-- Small boxes (Stat box) -->

    <div class="box-body">
        <h1>SISTEM INVENTORY FOR APOTEK</h1>
        <div class="row">
            <div class="callout callout-info" style="margin:20px 20px 20px 20px">
                <h4><?php echo "Hai $_SESSION[namalengkap]"; ?></h4>
                <p><?php echo "Selamat datang di halaman SMART INVENTORY FOR APOTEK "; ?>
                    <BR>
                    Silahkan klik menu pilihan yang berada di sebelah kiri untuk mengelola aplikasi
                </p>
            </div>



        </div>

    <?php
    echo "<p align=right>Login : $hari_ini, $tgl_awal <br>
   <b><span id=\"jam\" style=\"font-size:24\"></span></b></p>
  <span id='date'></span>, <span id='clock'></span></p>
  </div>
 
 ";
}
// Bagian user admin
elseif ($_GET['module'] == 'admin') {
    include "modul/mod_admin/admin.php";
}

// Bagian setheader
elseif ($_GET['module'] == 'setheader') {
    include "modul/mod_setheader/setheader.php";
}

// Bagian satuan
elseif ($_GET['module'] == 'satuan') {
    include "modul/mod_satuan/satuan.php";
}

// Bagian jenisobat
elseif ($_GET['module'] == 'jenisobat') {
    include "modul/mod_jenisobat/jenisobat.php";
}
// Bagian profil
elseif ($_GET['module'] == 'profil') {
    include "modul/mod_profil/profil.php";
}

// Bagian pelanggan
elseif ($_GET['module'] == 'pelanggan') {
    include "modul/mod_pelanggan/pelanggan.php";
}

// Bagian konseling
elseif ($_GET['module'] == 'konseling') {
    include "modul/mod_konseling/konseling.php";
}

// Bagian meso
elseif ($_GET['module'] == 'meso') {
    include "modul/mod_meso/meso.php";
}

// Bagian pio
elseif ($_GET['module'] == 'pio') {
    include "modul/mod_pio/pio.php";
}

// Bagian cpp
elseif ($_GET['module'] == 'cpp') {
    include "modul/mod_cpp/cpp.php";
}

// Bagian supplier
elseif ($_GET['module'] == 'supplier') {
    include "modul/mod_supplier/supplier.php";
}

// Bagian carabayar
elseif ($_GET['module'] == 'carabayar') {
    include "modul/mod_carabayar/carabayar.php";
}

// Bagian barang
elseif ($_GET['module'] == 'barang') {
    include "modul/mod_barang/barang.php";
}

// Bagian zataktif
elseif ($_GET['module'] == 'zataktif') {
    include "modul/mod_zataktif/zataktif.php";
}

// Bagian trbmasuk
elseif ($_GET['module'] == 'trbmasuk') {
    include "modul/mod_trbmasuk/trbmasuk.php";
}
// Bagian trbmasuk
elseif ($_GET['module'] == 'trbmasukpbf') {
    include "modul/mod_trbmasukpbf/trbmasukpbf.php";
}

// Bagian trkasir
elseif ($_GET['module'] == 'trkasir') {
    include "modul/mod_trkasir/trkasir.php";
}

// Bagian lapbarang
elseif ($_GET['module'] == 'lapbarang') {
    include "modul/mod_lapbarang/lapbarang.php";
}

// Bagian lappenjualan
elseif ($_GET['module'] == 'lappenjualan') {
    include "modul/mod_lappenjualan/lappenjualan.php";
}

// Bagian lap brg masuk
elseif ($_GET['module'] == 'lapbrgmasuk') {
    include "modul/mod_lapbrgmasuk/lapbrgmasuk.php";
}

// Bagian lap Stok Opname
elseif ($_GET['module'] == 'lapstokopname') {
    include "modul/mod_laporan/laporan_stokopname.php";
}
// Bagian Stok Opname Harian

// Bagian nilai stok barang
elseif ($_GET['module'] == 'lapstok') {
    include "modul/mod_lapstok/lapstok.php";
} // Bagian nilai stok kritis
elseif ($_GET['module'] == 'stok_kritis') {
    include "modul/mod_lapstok/stok_kritis.php";
}// Bagian Kartu Stok
elseif ($_GET['module'] == 'kartustok') {
    include "modul/mod_kartustok/kartu_stok.php";
}

// Bagian orders
elseif ($_GET['module'] == 'orders') {
    include "modul/mod_orders/orders.php";
} // Penjualan sebelumnya
elseif ($_GET['module'] == 'penjualansebelumnya') {
    include "modul/mod_trkasir/trkasir_tes.php";
}
//Laba Penjualan
elseif ($_GET['module'] == 'labapenjualan') {
    include "modul/mod_lappenjualan/labapenjualan.php";
}
//Terima Barang Masuk Oleh Manager
elseif ($_GET['module'] == 'byrkredit') {
    include "modul/mod_trbmasuk/byrkredit.php";
} elseif ($_GET['module'] == 'byrkreditpbf') {
    include "modul/mod_trbmasukpbf/byrkredit.php";
}

//Stok Opname
// elseif ($_GET['module'] == 'stokopname') {
//     include "modul/mod_stokopname/stokopname_harian.php";
// }
elseif ($_GET['module'] == 'stokopname') {
    include "modul/mod_lapstok/stokopname.php";
} elseif ($_GET['module'] == 'soharian') {
    // include "modul/mod_lapstok/soharian.php";
    include "modul/mod_stokopname/stokopname_harian.php";
} elseif ($_GET['module'] == 'labajenisobat') {
    include "modul/mod_lappenjualan/labapenjualanjenisobat.php";
}
//koreksi stok karena sistem
elseif ($_GET['module'] == 'koreksistok') {
    include "modul/mod_lapstok/koreksistok.php";
} //input shiftkerja
elseif ($_GET['module'] == 'shiftkerja') {
    include "modul/mod_shiftkerja/shiftkerja.php";
}

// neraca
elseif ($_GET['module'] == 'neraca') {
    include "modul/mod_laporan/neraca.php";
}

// komisi
elseif ($_GET['module'] == 'komisi') {
    include "modul/mod_komisi/komisi.php";
} 
elseif ($_GET['module'] == 'lapkomisi') {
    include "modul/mod_komisi/lapkomisi.php";
}

// catatan
elseif ($_GET['module'] == 'catatan') {
    include "modul/mod_catatan/catatan1.php";
} 
// cek darah
elseif ($_GET['module'] == 'cekdarah') {
    include "modul/mod_cekdarah/cekdarah.php";

// Jurnal Kas
} elseif ($_GET['module'] == 'jurnalkas') {
    include "modul/mod_jurnalkas/jurnalkas.php";
}
// evaluasi pegawai
elseif ($_GET['module'] == 'evaluasi') {
    include "modul/mod_evaluasi/lapevaluasi.php";
}

// $getkdon = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT kd_trkasir FROM kdtk WHERE id_admin = '$_SESSION[idadmin]' AND stt_kdtk ='ON' ORDER BY id_kdtk DESC");
// $kdon = mysqli_fetch_array($getkdon);

// if ($_GET['module'] != 'trkasir') {
//     $getkasirdetail = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT kd_trkasir FROM trkasir_detail WHERE kd_trkasir = '$kdon[kd_trkasir]'");
//     $trkasir_detail = mysqli_num_rows($getkasirdetail);

//     if ($trkasir_detail > 0) {
//         $gettrkasir = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT kd_trkasir FROM trkasir WHERE kd_trkasir = '$kdon[kd_trkasir]'");
//         $trkasir = mysqli_num_rows($gettrkasir);

//         if ($trkasir <= 0) {
//             echo "<script type='text/javascript'>alert('Harap untuk mengklik simpan transaksi !');window.location='?module=trkasir&act=tambah'</script>";
//         }
//     }
// }

    ?>
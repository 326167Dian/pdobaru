-- Tabel untuk menyimpan data MESO (Monitoring Efek Samping Obat)
CREATE TABLE IF NOT EXISTS `meso` (
  `id_meso` int(11) NOT NULL AUTO_INCREMENT,
  `id_pelanggan` int(11) NOT NULL,
  `kode_sumber_data` varchar(50) DEFAULT NULL,
  
  -- Data Penderita
  `nama_singkat` varchar(100) DEFAULT NULL,
  `umur` varchar(20) DEFAULT NULL,
  `suku` varchar(50) DEFAULT NULL,
  `berat_badan` varchar(20) DEFAULT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `status_hamil` enum('hamil','tidak_hamil','tidak_tahu') DEFAULT NULL,
  
  -- Penyakit
  `penyakit_utama` text,
  `gangguan_ginjal` tinyint(1) DEFAULT 0,
  `gangguan_hati` tinyint(1) DEFAULT 0,
  `alergi` tinyint(1) DEFAULT 0,
  `kondisi_medis_lain` tinyint(1) DEFAULT 0,
  `kondisi_medis_lain_ket` varchar(255) DEFAULT NULL,
  `kesudahan_penyakit` enum('sembuh','sembuh_gejala_sisa','belum_sembuh','meninggal','tidak_tahu') DEFAULT NULL,
  
  -- Efek Samping Obat
  `manifestasi_eso` text,
  `masalah_mutu_produk` text,
  `tanggal_mula_eso` date DEFAULT NULL,
  `kesudahan_eso` enum('sembuh','sembuh_gejala_sisa','belum_sembuh','meninggal','tidak_tahu') DEFAULT NULL,
  `riwayat_eso` text,
  
  -- Obat yang dikonsumsi (disimpan sebagai JSON untuk multiple obat)
  `data_obat` text,
  
  -- Keterangan Tambahan
  `keterangan_tambahan` text,
  `data_laboratorium` text,
  `tanggal_pemeriksaan_lab` date DEFAULT NULL,
  
  -- Info Pelapor
  `tanggal_laporan` date DEFAULT NULL,
  `nama_pelapor` varchar(100) DEFAULT NULL,
  `tanda_tangan_pelapor` varchar(255) DEFAULT NULL,
  
  -- Metadata
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(100) DEFAULT NULL,
  
  PRIMARY KEY (`id_meso`),
  KEY `id_pelanggan` (`id_pelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `meso`
  ADD CONSTRAINT `fk_meso_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Create detail table for obat records inside riwayat_pelanggan
CREATE TABLE IF NOT EXISTS `riwayat_pelanggan_obat` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_riwayat` INT(11) NOT NULL,
  `kd_barang` BIGINT(20) NOT NULL,
  `nm_barang` VARCHAR(100) NOT NULL,
  `aturan_pakai` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_rpo_id_riwayat` (`id_riwayat`),
  KEY `idx_rpo_kd_barang` (`kd_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Optional foreign keys (enable only if all related tables use InnoDB and key types match)
-- ALTER TABLE `riwayat_pelanggan_obat`
--   ADD CONSTRAINT `fk_rpo_riwayat`
--   FOREIGN KEY (`id_riwayat`) REFERENCES `riwayat_pelanggan`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

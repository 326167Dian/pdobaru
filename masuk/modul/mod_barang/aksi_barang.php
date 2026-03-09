<?php
session_start();
if (empty($_SESSION['username']) and empty($_SESSION['passuser'])) {
	echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
	echo "<a href=../../index.php><b>LOGIN</b></a></center>";
} else {

	include "../../../configurasi/koneksi.php";
	include "../../../configurasi/fungsi_thumb.php";
	include "../../../configurasi/library.php";

	function normalize_barang_html($html)
	{
		$html = trim((string)$html);
		if ($html === '') {
			return '';
		}

		if (function_exists('tidy_repair_string')) {
			$config = [
				'show-body-only' => true,
				'clean' => true,
				'output-html' => true,
				'wrap' => 0,
				'char-encoding' => 'utf8',
			];
			$html = tidy_repair_string($html, $config, 'utf8');
		}

		if (class_exists('DOMDocument')) {
			$internalErrors = libxml_use_internal_errors(true);
			$dom = new DOMDocument('1.0', 'UTF-8');

			$flags = 0;
			if (defined('LIBXML_HTML_NOIMPLIED')) {
				$flags |= LIBXML_HTML_NOIMPLIED;
			}
			if (defined('LIBXML_HTML_NODEFDTD')) {
				$flags |= LIBXML_HTML_NODEFDTD;
			}

			$loaded = $dom->loadHTML('<?xml encoding="utf-8" ?><div id="barang-root">' . $html . '</div>', $flags);
			if ($loaded) {
				foreach (['script', 'iframe', 'object'] as $tag) {
					$nodes = $dom->getElementsByTagName($tag);
					while ($nodes->length > 0) {
						$node = $nodes->item(0);
						$node->parentNode->removeChild($node);
					}
				}

				$root = $dom->getElementById('barang-root');
				if ($root) {
					$normalized = '';
					foreach ($root->childNodes as $childNode) {
						$normalized .= $dom->saveHTML($childNode);
					}
					$normalized = trim($normalized);
					libxml_clear_errors();
					libxml_use_internal_errors($internalErrors);

					if ($normalized !== '') {
						return $normalized;
					}
				}
			}

			libxml_clear_errors();
			libxml_use_internal_errors($internalErrors);
		}

		return nl2br(htmlspecialchars($html, ENT_QUOTES, 'UTF-8'));
	}

	$module = $_GET['module'];
	$act = $_GET['act'];

	// Input admin
	if ($module == 'barang' and $act == 'input_barang') {

		$cekganda1 = $db->prepare("SELECT kd_barang FROM barang WHERE kd_barang = ?");
		$cekganda1->execute([$_POST['kd_barang']]);
		$ada1 = $cekganda1->rowCount();
		if ($ada1 > 0) {
			echo "<script type='text/javascript'>alert('Kode Barang sudah ada!');history.go(-1);</script>";
		} else {

			$cekganda = $db->prepare("SELECT nm_barang FROM barang WHERE nm_barang = ?");
			$cekganda->execute([$_POST['nm_barang']]);
			$ada = $cekganda->rowCount();
			if ($ada > 0) {
				echo "<script type='text/javascript'>alert('Nama Barang sudah ada!');history.go(-1);</script>";
			} else {

				$cekganda3 = $db->prepare("SELECT kd_barang, nm_barang, sat_barang FROM barang WHERE kd_barang = ? AND nm_barang = ? AND sat_barang = ?");
				$cekganda3->execute([$_POST['kd_barang'], $_POST['nm_barang'], $_POST['sat_barang']]);
				$ada3 = $cekganda3->rowCount();
				if ($ada3 > 0) {
					echo "<script type='text/javascript'>alert('Kode dengan Nama Barang dan Satuan ini sudah ada!');history.go(-1);</script>";
				} else {

					$tanggal = date('Y-m-d H:i:s');
					$updated_by = isset($_SESSION['namalengkap']) && !empty($_SESSION['namalengkap']) ? $_SESSION['namalengkap'] : $_SESSION['username'];
					$indikasi = normalize_barang_html($_POST['indikasi'] ?? '');
					$zataktif = normalize_barang_html($_POST['zataktif'] ?? '');

					$db->prepare("INSERT INTO barang(kd_barang, nm_barang, stok_buffer, sat_barang, sat_grosir, jenisobat, konversi, hrgsat_barang, hrgsat_grosir, hrgjual_barang, hrgjual_barang1, hrgjual_barang2, indikasi, ket_barang, zataktif, tgl, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")->execute([$_POST['kd_barang'], $_POST['nm_barang'], $_POST['stok_buffer'], $_POST['sat_barang'], $_POST['sat_grosir'], $_POST['jenisobat'], $_POST['konversi'], $_POST['hrgsat_barang'], $_POST['hrgsat_grosir'], $_POST['hrgjual_barang'], $_POST['hrgjual_barang1'], $_POST['hrgjual_barang2'], $indikasi, $_POST['ket_barang'], $zataktif, $tanggal, $updated_by]);

					//echo "<script type='text/javascript'>alert('Data berhasil ditambahkan !');window.location='../../media_admin.php?module=".$module."'</script>";
					header('location:../../media_admin.php?module=' . $module);
				}
			}
		}
	}
	//update barang
	elseif ($module == 'barang' and $act == 'update_barang') {

		$tanggal = date('Y-m-d H:i:s');
		$updated_by = isset($_SESSION['namalengkap']) && !empty($_SESSION['namalengkap']) ? $_SESSION['namalengkap'] : $_SESSION['username'];

		try {
			$indikasi = normalize_barang_html($_POST['indikasi'] ?? '');
			$zataktif = normalize_barang_html($_POST['zataktif'] ?? '');
			$stmt = $db->prepare("UPDATE barang SET
                                    kd_barang = ?,
									nm_barang = ?,									
									stok_buffer = ?,
									sat_barang = ?,
									sat_grosir = ?,
									jenisobat = ?,
									konversi = ?,
									hrgsat_barang = ?,
									hrgsat_grosir = ?,
									hrgjual_barang = ?,
									hrgjual_barang1 = ?,
									hrgjual_barang2 = ?,
									indikasi = ?,
									ket_barang = ?,
									dosis = ?,
									zataktif = ?,
									tgl = ?,
									updated_by = ?
									WHERE id_barang = ?");
			$stmt->execute([$_POST['kd_barang'], $_POST['nm_barang'], $_POST['stok_buffer'], $_POST['sat_barang'], $_POST['sat_grosir'], $_POST['jenisobat'], $_POST['konversi'], $_POST['hrgsat_barang'], $_POST['hrgsat_grosir'], $_POST['hrgjual_barang'], $_POST['hrgjual_barang1'], $_POST['hrgjual_barang2'], $indikasi, $_POST['ket_barang'], $_POST['dosis'], $zataktif, $tanggal, $updated_by, $_POST['id']]);
									
			//echo "<script type='text/javascript'>alert('Data berhasil diubah !');window.location='../../media_admin.php?module=".$module."'</script>";
			$returnStart = isset($_POST['return_start']) ? (int)$_POST['return_start'] : 0;
			$redirectUrl = '../../media_admin.php?module=' . $module;
			if ($returnStart > 0) {
				$redirectUrl .= '&start=' . $returnStart;
			}
			header('location:' . $redirectUrl);
		} catch (PDOException $e) {
			echo "<script type='text/javascript'>alert('Error: " . $e->getMessage() . "');history.go(-1);</script>";
		}
	}
	//Hapus Proyek
	elseif ($module == 'barang' and $act == 'hapus') {

		$db->prepare("DELETE FROM barang WHERE id_barang = ?")->execute([$_GET['id']]);
		//echo "<script type='text/javascript'>alert('Data berhasil dihapus !');window.location='../../media_admin.php?module=".$module."'</script>";
		header('location:../../media_admin.php?module=' . $module);
	}
	// Update indikasi inline
	elseif ($module == 'barang' and $act == 'update_indikasi') {
		if (!isset($_POST['id_barang'])) {
			http_response_code(400);
			exit('ID barang tidak ditemukan');
		}
		$indikasi = normalize_barang_html($_POST['indikasi'] ?? '');
		$stmt = $db->prepare("UPDATE barang SET indikasi = ? WHERE id_barang = ?");
		$stmt->execute([$indikasi, $_POST['id_barang']]);
		echo 'OK';
	}
	// Update zataktif inline
	elseif ($module == 'barang' and $act == 'update_zataktif') {
		if (!isset($_POST['id_barang'])) {
			http_response_code(400);
			exit('ID barang tidak ditemukan');
		}
		$zataktif = normalize_barang_html($_POST['zataktif'] ?? '');
		$stmt = $db->prepare("UPDATE barang SET zataktif = ? WHERE id_barang = ?");
		$stmt->execute([$zataktif, $_POST['id_barang']]);
		echo 'OK';
	}
}

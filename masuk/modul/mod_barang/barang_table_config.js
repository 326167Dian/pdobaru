$(document).ready(function() {
	function getParam(name) {
		var url = new URL(window.location.href);
		return url.searchParams.get(name);
	}
	var startParam = parseInt(getParam('start') || '0', 10);
	if (isNaN(startParam) || startParam < 0) {
		startParam = 0;
	}

	var table = $('#tes').DataTable({
		processing: true,
		serverSide: true,
		autoWidth: false,
		displayStart: startParam,
		ajax: {
			"url": "modul/mod_barang/barang-serverside.php?action=table_data",
			"dataType": "JSON",
			"type": "POST"
		},
		"rowCallback": function(row, data, index) {
			let q = (data['hrgjual_barang_reguler'] - data['hrgsat_barang']) / data['hrgsat_barang'];
			
			if(q <= 0.2){
				$(row).find('td:eq(3)').css('background-color', '#ff003f');
				$(row).find('td:eq(3)').css('color', '#ffffff');
			} else if(q > 0.2 && q <= 0.25){
				$(row).find('td:eq(3)').css('background-color', '#f39c12');
				$(row).find('td:eq(3)').css('color', '#ffffff');
				
			} else if(q > 0.25 && q <= 0.3){
				$(row).find('td:eq(3)').css('background-color', '#00ff3f');
				$(row).find('td:eq(3)').css('color', '#ffffff');
				
			} else if(q > 0.3){
				$(row).find('td:eq(3)').css('background-color', '#00bfff');
				$(row).find('td:eq(3)').css('color', '#ffffff');
			}
			
		},
		columns: [{
			"data": "no",
			"className": 'text-center'
		},
		{
			"data": "nm_barang"
		},
		{
			"data": "updated_by",
			"className": 'text-center'
		},
		{
			"data": "hrgjual_barang",
			"className": 'text-left',
		},
		{
			"data": "zataktif",
			"className": 'text-justify',
			"render": function(data, type, row) {
				if (type === 'display') {
					return (data || '') + "<div style='margin-top:6px;'><button type='button' class='btn btn-xs btn-info btn-edit-zataktif' data-id='" + (row.id_barang || '') + "'>Edit</button></div>";
				}
				return data;
			}
		},
		{
			"data": "indikasi",
			"className": 'text-justify',
			"render": function(data, type, row) {
				if (type === 'display') {
					return (data || '') + "<div style='margin-top:6px;'><button type='button' class='btn btn-xs btn-info btn-edit-indikasi' data-id='" + (row.id_barang || '') + "'>Edit</button></div>";
				}
				return data;
			}
		},
		{
			"data": "aksi",
			"className": "text-left",
			"width": "95px",
			"orderable": false,
			"searchable": false,
			"defaultContent": "",
			"visible": (typeof userLevel !== 'undefined' && userLevel == 'pemilik')
		}]
	});

	table.on('draw', function() {
		$('#tes th:last-child, #tes td:last-child').css({
			'white-space': 'nowrap',
			'min-width': '95px',
			'text-align': 'left'
		});
	});

	$(window).on('resize', function() {
		table.columns.adjust();
	});
	$(document).on('expanded.pushMenu collapsed.pushMenu', function() {
		table.columns.adjust();
	});

	$('#tes tbody').on('click', 'a', function(e) {
		var href = $(this).attr('href') || '';
		if (href.indexOf('act=edit') === -1) {
			return;
		}
		if (href.indexOf('start=') !== -1) {
			return;
		}
		var info = table.page.info();
		var start = info ? info.start : 0;
		var separator = href.indexOf('?') !== -1 ? '&' : '?';
		$(this).attr('href', href + separator + 'start=' + start);
	});

	$('#tes tbody').on('click', '.btn-print-barcode', function(e) {
		e.preventDefault();
		var idBarang = $(this).data('id');
		if (!idBarang) {
			return;
		}

		var qtyInput = window.prompt('Jumlah barcode yang akan di-print?', '1');
		if (qtyInput === null) {
			return;
		}

		var qty = parseInt(qtyInput, 10);
		if (isNaN(qty) || qty < 1) {
			alert('Jumlah barcode harus angka minimal 1.');
			return;
		}

		if (qty > 500) {
			qty = 500;
		}

		window.open('modul/mod_barang/print_barcode.php?id=' + idBarang + '&qty=' + qty, '_blank');
	});

	var indikasiModalRow = null;
	var indikasiModalData = null;
	var zataktifModalRow = null;
	var zataktifModalData = null;

	function showIndikasiModal() {
		if (typeof $.fn.modal === 'function') {
			$('#indikasiModal').modal('show');
		} else {
			$('body').addClass('modal-open');
			$('#indikasiModal')
				.addClass('is-open in')
				.attr('aria-hidden', 'false');
		}
	}
	function hideIndikasiModal() {
		if (typeof $.fn.modal === 'function') {
			$('#indikasiModal').modal('hide');
		} else {
			$('body').removeClass('modal-open');
			$('#indikasiModal')
				.removeClass('is-open in')
				.attr('aria-hidden', 'true');
		}
	}
	function showZataktifModal() {
		if (typeof $.fn.modal === 'function') {
			$('#zataktifModal').modal('show');
		} else {
			$('body').addClass('modal-open');
			$('#zataktifModal')
				.addClass('is-open in')
				.attr('aria-hidden', 'false');
		}
	}
	function hideZataktifModal() {
		if (typeof $.fn.modal === 'function') {
			$('#zataktifModal').modal('hide');
		} else {
			$('body').removeClass('modal-open');
			$('#zataktifModal')
				.removeClass('is-open in')
				.attr('aria-hidden', 'true');
		}
	}

	$(document).on('click', '.btn-edit-indikasi', function(e) {
		e.preventDefault();
		var rowEl = $(this).closest('tr');
		indikasiModalRow = table.row(rowEl).index();
		indikasiModalData = table.row(rowEl).data() || {};
		var idBarang = indikasiModalData.id_barang || $(this).data('id');
		if (!idBarang) {
			return;
		}
		indikasiModalData.id_barang = idBarang;
		var indikasiHtml = indikasiModalData.indikasi || '';
		if (!indikasiHtml) {
			var cellHtml = table.cell(rowEl, 5).data() || '';
			var temp = $('<div>').html(cellHtml);
			temp.find('.btn-edit-indikasi').remove();
			indikasiHtml = temp.html();
		}
		showIndikasiModal();
		if (typeof CKEDITOR !== 'undefined') {
			if (CKEDITOR.instances.indikasi_modal_editor) {
				CKEDITOR.instances.indikasi_modal_editor.destroy(true);
			}
			CKEDITOR.replace('indikasi_modal_editor', {
				filebrowserBrowseUrl: '',
				filebrowserWindowWidth: 1000,
				filebrowserWindowHeight: 500
			});
			CKEDITOR.instances.indikasi_modal_editor.setData(indikasiHtml || '');
		} else {
			$('#indikasi_modal_editor').val(indikasiHtml || '');
		}
	});

	$('#indikasiModal').on('hidden.bs.modal', function() {
		if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.indikasi_modal_editor) {
			CKEDITOR.instances.indikasi_modal_editor.destroy(true);
		}
		$('#indikasi_modal_editor').val('');
		indikasiModalRow = null;
		indikasiModalData = null;
	});
	$('#zataktifModal').on('hidden.bs.modal', function() {
		if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.zataktif_modal_editor) {
			CKEDITOR.instances.zataktif_modal_editor.destroy(true);
		}
		$('#zataktif_modal_editor').val('');
		zataktifModalRow = null;
		zataktifModalData = null;
	});
	$(document).on('click', '#indikasiModal .close, #indikasiModal [data-dismiss="modal"]', function(e) {
		e.preventDefault();
		hideIndikasiModal();
	});
	$(document).on('click', '#zataktifModal .close, #zataktifModal [data-dismiss="modal"]', function(e) {
		e.preventDefault();
		hideZataktifModal();
	});

	$('#indikasi_modal_save').on('click', function(e) {
		e.preventDefault();
		if (!indikasiModalData || !indikasiModalData.id_barang) {
			return;
		}
		var newText = $('#indikasi_modal_editor').val();
		if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.indikasi_modal_editor) {
			newText = CKEDITOR.instances.indikasi_modal_editor.getData();
		}
		$.ajax({
			type: 'POST',
			url: 'modul/mod_barang/aksi_barang.php?module=barang&act=update_indikasi',
			data: {
				id_barang: indikasiModalData.id_barang,
				indikasi: newText
			},
				success: function() {
				indikasiModalData.indikasi = newText;
				table.row(indikasiModalRow).data(indikasiModalData).invalidate().draw(false);
					hideIndikasiModal();
			},
			error: function() {
				alert('Gagal menyimpan perubahan.');
			}
		});
	});

	$(document).on('click', '.btn-edit-zataktif', function(e) {
		e.preventDefault();
		var rowEl = $(this).closest('tr');
		zataktifModalRow = table.row(rowEl).index();
		zataktifModalData = table.row(rowEl).data() || {};
		var idBarang = zataktifModalData.id_barang || $(this).data('id');
		if (!idBarang) {
			return;
		}
		zataktifModalData.id_barang = idBarang;
		var zataktifHtml = zataktifModalData.zataktif || '';
		if (!zataktifHtml) {
			var cellHtml = table.cell(rowEl, 4).data() || '';
			var temp = $('<div>').html(cellHtml);
			temp.find('.btn-edit-zataktif').remove();
			zataktifHtml = temp.html();
		}
		showZataktifModal();
		if (typeof CKEDITOR !== 'undefined') {
			if (CKEDITOR.instances.zataktif_modal_editor) {
				CKEDITOR.instances.zataktif_modal_editor.destroy(true);
			}
			CKEDITOR.replace('zataktif_modal_editor', {
				filebrowserBrowseUrl: '',
				filebrowserWindowWidth: 1000,
				filebrowserWindowHeight: 500
			});
			CKEDITOR.instances.zataktif_modal_editor.setData(zataktifHtml || '');
		} else {
			$('#zataktif_modal_editor').val(zataktifHtml || '');
		}
	});

	$('#zataktif_modal_save').on('click', function(e) {
		e.preventDefault();
		if (!zataktifModalData || !zataktifModalData.id_barang) {
			return;
		}
		var newText = $('#zataktif_modal_editor').val();
		if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.zataktif_modal_editor) {
			newText = CKEDITOR.instances.zataktif_modal_editor.getData();
		}
		$.ajax({
			type: 'POST',
			url: 'modul/mod_barang/aksi_barang.php?module=barang&act=update_zataktif',
			data: {
				id_barang: zataktifModalData.id_barang,
				zataktif: newText
			},
			success: function() {
				zataktifModalData.zataktif = newText;
				table.row(zataktifModalRow).data(zataktifModalData).invalidate().draw(false);
				hideZataktifModal();
			},
			error: function() {
				alert('Gagal menyimpan perubahan.');
			}
		});
	});
});

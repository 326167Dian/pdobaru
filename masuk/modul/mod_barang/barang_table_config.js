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
			"className": 'text-justify'
		},
		{
			"data": "indikasi",
			"className": 'text-justify',
			"render": function(data, type, row) {
				if (type === 'display') {
					return (data || '') + "<div style='margin-top:6px;'><button type='button' class='btn btn-xs btn-info btn-edit-indikasi'>Edit</button></div>";
				}
				return data;
			}
		},
		{
			"data": "aksi",
			"visible": (typeof userLevel !== 'undefined' && userLevel == 'pemilik')
		}]
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

	function openIndikasiEditor(cell) {
		var colIndex = cell.index().column;
		if (colIndex !== 5) {
			return;
		}

		var rowData = table.row(cell.index().row).data();
		if (!rowData || !rowData.id_barang) {
			return;
		}

		var originalHtml = cell.data();
		var editorId = 'indikasi_edit_' + rowData.id_barang;
		if (CKEDITOR && CKEDITOR.instances && CKEDITOR.instances[editorId]) {
			CKEDITOR.instances[editorId].destroy(true);
		}
		var textarea = $('<textarea class="form-control" rows="4"></textarea>')
			.attr('id', editorId)
			.val('');
		var saveBtn = $('<button class="btn btn-xs btn-primary" style="margin-top:6px;">Simpan</button>');
		var cancelBtn = $('<button class="btn btn-xs btn-default" style="margin-top:6px;margin-left:6px;">Batal</button>');

		$(cell.node()).empty().append(textarea, $('<div></div>').append(saveBtn, cancelBtn));
		if (typeof CKEDITOR !== 'undefined') {
			CKEDITOR.replace(editorId, {
				filebrowserBrowseUrl: '',
				filebrowserWindowWidth: 1000,
				filebrowserWindowHeight: 500
			});
			CKEDITOR.instances[editorId].setData(originalHtml || '');
		} else {
			textarea.val($(cell.node()).text().trim());
			textarea.focus();
		}

		cancelBtn.on('click', function(e) {
			e.preventDefault();
			if (CKEDITOR && CKEDITOR.instances && CKEDITOR.instances[editorId]) {
				CKEDITOR.instances[editorId].destroy(true);
			}
			cell.data(originalHtml).draw(false);
		});

		saveBtn.on('click', function(e) {
			e.preventDefault();
			var newText = textarea.val();
			if (CKEDITOR && CKEDITOR.instances && CKEDITOR.instances[editorId]) {
				newText = CKEDITOR.instances[editorId].getData();
			}
			$.ajax({
				type: 'POST',
				url: 'modul/mod_barang/aksi_barang.php?module=barang&act=update_indikasi',
				data: {
					id_barang: rowData.id_barang,
					indikasi: newText
				},
				success: function() {
					if (CKEDITOR && CKEDITOR.instances && CKEDITOR.instances[editorId]) {
						CKEDITOR.instances[editorId].destroy(true);
					}
					cell.data(newText).draw(false);
				},
				error: function() {
					if (CKEDITOR && CKEDITOR.instances && CKEDITOR.instances[editorId]) {
						CKEDITOR.instances[editorId].destroy(true);
					}
					cell.data(originalHtml).draw(false);
					alert('Gagal menyimpan perubahan.');
				}
			});
		});
	}

	$('#tes tbody').on('dblclick', 'td', function() {
		var cell = table.cell(this);
		openIndikasiEditor(cell);
	});

	$('#tes tbody').on('click', '.btn-edit-indikasi', function(e) {
		e.preventDefault();
		var cell = table.cell($(this).closest('td'));
		openIndikasiEditor(cell);
	});
});

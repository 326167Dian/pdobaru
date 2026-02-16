$(document).ready(function() {
	$('#tes').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			"url": "modul/mod_barang/barang-serverside.php?action=table_data",
			"dataType": "JSON",
			"type": "POST"
		},
		"rowCallback": function(row, data, index) {
			let q = (data['hrgjual_barang_reguler'] - data['hrgsat_barang']) / data['hrgsat_barang'];
			
			if(q <= 0.2){
				$(row).find('td:eq(4)').css('background-color', '#ff003f');
				$(row).find('td:eq(4)').css('color', '#ffffff');
			} else if(q > 0.2 && q <= 0.25){
				$(row).find('td:eq(4)').css('background-color', '#f39c12');
				$(row).find('td:eq(4)').css('color', '#ffffff');
				
			} else if(q > 0.25 && q <= 0.3){
				$(row).find('td:eq(4)').css('background-color', '#00ff3f');
				$(row).find('td:eq(4)').css('color', '#ffffff');
				
			} else if(q > 0.3){
				$(row).find('td:eq(4)').css('background-color', '#00bfff');
				$(row).find('td:eq(4)').css('color', '#ffffff');
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
			"data": "stok_barang",
			"className": 'text-center'
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
			"className": 'text-justify'
		},
		{
			"data": "aksi",
			"visible": (typeof userLevel !== 'undefined' && userLevel == 'pemilik')
		}]
	});
});

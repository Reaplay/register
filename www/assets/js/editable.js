function initTable7() {

	var table = $('#sample_editable_1');

	var oTable = table.dataTable({
		"lengthMenu": [
			[5, 15, 20, -1],
			[5, 15, 20, "All"] // change per page values here
		],
		// set the initial value
		"pageLength": 10,
		"language": {
			"lengthMenu": " _MENU_ records"
		},
		"columnDefs": [{ // set default column settings
			'orderable': true,
			'targets': [0]
		}, {
			"searchable": true,
			"targets": [0]
		}],
		"order": [
			[0, "asc"]
		] // set first column as a default sort by asc
	});

	var tableWrapper = $("#sample_editable_1_wrapper");

	tableWrapper.find(".dataTables_length select").select2({
		showSearchInput: false //hide search box with special css class
	}); // initialize select2 dropdown


	table.on('click', '.delete', function (e) {
		e.preventDefault();

		if (confirm("Вы действительно хотите удалить данную запись?") == false) {
			return;
		}
			

		var nRow = $(this).parents('tr')[0];
		var id = $(this).parents('tr').attr('data-id');
		var w = $(this).parents('tbody').attr('data-w');
	
		var url = "elements/ajax.php?w="+w+"&id="+id+"&a=d";
		$.get(url, function(data) {
			if (data == "success"){
				oTable.fnDeleteRow(nRow);
				_toastr("Удаление успешно выполнено","top-right","info",false);
			}
			else{
				_toastr("Ошибка выполнения","top-right","error",false);
			}
		});
			
			
	});

	}


	// Table Init
	initTable7();
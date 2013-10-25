$(document).ready(function() {

		H 	= $(window).outerHeight();
		rH	= 100;	// Скролл таблицы записей
		d_min = rH + 110;  // Расстояние от главной таблицы до дна
		eH	= 600;	// Скролл главной таблицы
		// Настройка скроллинга большой таблицы
		if( eH + d_min > H )
			eH = H - d_min;



		var oTable = $('#users').dataTable({
								"bJQueryUI": true,
								"sScrollY":  eH + "px",
								"bPaginate": false,
								"sDom": '<"H"Tf>t<"F"ip>',
								"aoColumnDefs": [
												{"bSortable":false, "aTargets": [3] },
												{"bSortable":false, "aTargets": [4] },
												{"bSortable":false, "aTargets": [5] },
												{"bSortable":false, "aTargets": [6] },
												{"bSortable":false, "sClass": "center", "aTargets": [7] },
												{"bSortable":false, "sClass": "center", "aTargets": [8] },
												],
								"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
													drawCheckBox(nRow);
												},
								"oTableTools": TTOpts

								});
		//$('#entry')

		var aTable = $('#aliases').dataTable({
								"bJQueryUI": true,
								"sDom": 't',
								"sScrollY": rH+"px",
								"aoColumnDefs": [
												{"sClass": "center", "aTargets": [0]},
												{"sClass": "center", "aTargets": [1]},
												{"sClass": "center","bSortable":false, "aTargets": [2] }
											],
								"oTableTools": {
												"aButtons": [
													"copy", "csv", "xls", "pdf",
													{
														"sExtends":    "collection",
														"sButtonText": "Save",
														"aButtons":    [ "csv", "xls", "pdf" ]
													}
												]
											}
								});
		//printTitle();

});

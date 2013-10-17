$(document).ready(function() {

		H 	= $(window).outerHeight();
		rH	= 100;	// Скролл таблицы записей
		d_min = rH + 110;  // Расстояние от главной таблицы до дна
		eH	= 600;	// Скролл главной таблицы
		// Настройка скроллинга большой таблицы
		if( eH + d_min > H )
			eH = H - d_min;

		$('#entry').dataTable({
							"bJQueryUI": true,
							"sScrollY":  eH + "px",
							"bPaginate": false,
							"aoColumnDefs": [
											{"bSortable":false, "aTargets": [3] },
											{"bSortable":false, "aTargets": [4] },
											{"bSortable":false, "aTargets": [5] },
											{"bSortable":false, "aTargets": [6] },
											{"bSortable":false, "sClass": "center", "aTargets": [7] },
											{"bSortable":false, "sClass": "center", "aTargets": [8] },
											]
							});
		$('#entry')

			$('#records').dataTable({
								"bJQueryUI": true,
								"sDom": 't',
								"sScrollY": rH+"px",
								"aoColumns": [
												{"sTitle":"Alias","sClass": "center"},
												{"sTitle":"Forward","sClass": "center"},
												{"sTitle":"on/off","sClass": "center","bSortable":false }
											],
								"aaData": [[null,null,null,{"mData": null}]]
								});


		// Хидер с названием
		$("div.fg-toolbar:first").append('<div class="page-name">Custom tool bar! Text/images etc.</div>');

});

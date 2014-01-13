$(function(){

		H 	= $(window).outerHeight();
		eH	= H - 150;	// Скролл главной таблицы

		TTOpts.aButtons[5].sButtonText = 'НАПОМИНАЛКА';
		TTOpts.aButtons.splice(3,2);

		var oTable = $('#tab-alarms').dataTable({
								"bJQueryUI": true,
								"sScrollY":  eH + "px",
								"bPaginate": false,
								"sDom": '<"H"Tf>t<"F"ip>',
								"aaSorting": [[3,"asc"]],
								"sAjaxSource": "/" + ctrl + "/showTable/",
								"bScrollCollapse": true,
								"sServerMethod": "POST",
								"fnInitComplete": function () {
														this.fnAdjustColumnSizing();
														this.fnDraw();
												},
								"aoColumns": [
												{"mData":"act","bSortable":false,},
												{"mData":"email","bSortable":false,"sWidth": "12%", },
												{"mData":"message","bSortable":false,},
												{"mData":"nextlaunch","sClass": "center","sWidth": "10%", },
												{"mData":"period","sClass": "mcenter","bSortable":false,"sWidth": "10%", },
												{"mData":"alarmbefore","sClass": "mcenter","bSortable":false,"sWidth": "10%", },
												{"mData":"active", "sClass": "center","bSortable":false,"sWidth": "5%",},
											],
								"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
													drawCheckBox(nRow);
													deadLinePeriod(nRow, aData);
												},
								"oTableTools": TTOpts

								});



})

/*
 *  Если НЕ выделена строка в таблице users - создавать для нее алиасы запрещаем
 *  Запещаем редактировать и удалять, если не выделено
 */
function blockNewButton(nodes) {

		if( nodes.length && (tab = nodes[0].offsetParent.id) ) {
			$('#ToolTables_'+tab+'_0').addClass('DTTT_disabled');
			$('#ToolTables_'+tab+'_2').addClass('DTTT_disabled');
		}
}

/*
 *  Если выделена строка в таблице users - разрешаем создавать для нее алиасы
 *  Разрешаем редактировать и удалять, если выделено
 */
function unblockNewButton(node) {

		tab = node[0].offsetParent.id;
		$('#ToolTables_'+tab+'_0').removeClass('DTTT_disabled');
		$('#ToolTables_'+tab+'_2').removeClass('DTTT_disabled');
}

function deadLinePeriod(nRow, aData) {

	var days = daysBetween(aData.nextlaunch);

	$(nRow).removeClass('gradeX gradeA');

	if( days < aData.alarmbefore) {
		$(nRow).addClass('gradeX');
	}
	else {
		$(nRow).addClass('gradeA');
	}

}

function dateParse(stringDate) {
	dt = stringDate.split('-');
	dt[1] = (dt[1] * 1 - 1 < 0) ? 11 : (dt[1] * 1 - 1);

	return (x = new Date(dt[0], dt[1], dt[2]));
}


function daysBetween(stringDate) {

    // Copy date parts of the timestamps, discarding the time parts.
    deadLineDay = dateParse(stringDate);
    cDate    = new Date();
    currDate = new Date(cDate.getFullYear(), cDate.getMonth(), cDate.getDate());

    // Do the math.
    var millisecondsPerDay = 1000 * 60 * 60 * 24;
    var millisBetween = deadLineDay.getTime() - currDate.getTime();
    var days = millisBetween / millisecondsPerDay;

    // Round down.
    return Math.floor(days);
}

modWin.validate_alarms = function () {

			var msg = '';
			email		= $('form :text[name="email"]').val();
			period		= $('form :text[name="period"]').val();
			nextlaunch	= $('form :text[name="nextlaunch"]').val();
			alarmbefore	= $('form :text[name="alarmbefore"]').val();
			id			= $(':hidden[name="id"]').val();

			$('textarea,:text', '#usersform').each(function(){

												if( ! $(this).val() ) {
													msg = 'Все поля должны быть заполнены. \n';
													return true;
												}
											});
			if( id == 0 && $(':checkbox[name="done"]').is(':checked') ) {
				$(':checkbox[name="done"]').removeAttr('checked');
			}

			if ( ! fnTestByType( email, 'mail') ) {
				return 'поле "Email" - должно содержать почтовый адрес.';
			}

			if ( ! fnTestByType( period, 'int') ) {
				return 'поле "Period launch" - должно содержать целое число.';
			}

			if ( ! fnTestByType( alarmbefore, 'int') ) {
				return 'поле "Alarm Before" - должно содержать целое число.';
			}

			if ( ! fnTestByType( nextlaunch, 'date') ) {
				return 'поле "Next launch" - должно содержать дату.';
			}

			return msg;
}

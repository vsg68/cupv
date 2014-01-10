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
								"aaSorting": [[1,"asc"]],
								"sAjaxSource": "/" + ctrl + "/showTable/",
								"bScrollCollapse": true,
								"sServerMethod": "POST",
								"fnInitComplete": function () {
														this.fnAdjustColumnSizing();
														this.fnDraw();
												},
								"aoColumns": [
												{"mData":"act","bSortable":false,},
												{"mData":"deadline", },
												{"mData":"startalarm", },
												{"mData":"email","bSortable":false, },
												{"mData":"message","bSortable":false,},
												{"mData":"active", "sClass": "center","bSortable":false,},
											],
								"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
													drawCheckBox(nRow);
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


modWin.validate_alarms = function () {

			var msg = '';
			email	= $('form :text[name="email"]').val();

			$('textarea,:text', '#usersform').each(function(){

												if( ! $(this).val() ) {
													msg = 'Все поля должны быть заполнены. \n';
													return true;
												}
											});

			if ( ! fnTestByType( email, 'mail') ) {
				msg += 'поле "Email" - должно содержать почтовый адрес.';
			}

			return msg;
}

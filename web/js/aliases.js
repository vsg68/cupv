$(function(){

		H 	= $(window).outerHeight();
		eH	= H - 150;	// Скролл главной таблицы

		TTOpts.aButtons[5].sButtonText = 'АЛИАСЫ';
		TTOpts.aButtons.splice(3,1);

		var oTable = $('#tab-aliases').dataTable({
								"bJQueryUI": true,
								"sScrollY":  eH + "px",
								"bPaginate": false,
								"sDom": '<"H"Tf>t<"F"ip>',
								"aaSorting": [[3,"asc"]],
								"aoColumnDefs": [
													{ "sClass": "center", "aTargets": [0] },
													{ "sClass": "center", "aTargets": [2] },
													{ "sWidth": "20px","bSortable":false, "sClass": "center", "aTargets": [1] },
													{"bSortable":false, "aTargets": [5] },
													{"bSortable":false, "sClass": "center", "aTargets": [6] },
												],
								"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
													drawCheckBox(nRow);
													drawNA(nRow);
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


/*
 *  Замена значений чекбоксов на картинку
 */
function drawNA(nRow) {

		// смотрим на активность записи
		$('td', nRow).each(function(){

			if( $(this).text() == '->' )
				$(this).html('<span class="ui-icon ui-icon-arrowthick-1-e"></span>');

			if( $(this).text() == 'N/A' )
				$(this).html('<span class="ui-icon ui-icon-person"></span>');
				//~$(this).addClass('noname');
		});

}


modWin.validate_aliases = function () {

			modWin.message = '';
			alias_name	= $('form :text[name="alias_name"]').val();
			delivery_to	= $('form :text[name="delivery_to"]').val();
			id			= '#tab-aliases-' + $(':hidden[name="id"]').val();

			if ( ! (alias_name && delivery_to) ) {
				modWin.message += 'Поля адресов должны быть заполнены. ';
			}

			if ( ! fnTestByType( alias_name, 'mail') ) {
				modWin.message += 'поле должно содержать почтовый адрес';
			}

			if ( ! fnTestByType( delivery_to, 'mail') ) {
				modWin.message += 'поле должно содержать почтовый адрес';
			}

			existNameID = 	$('tr')
									.filter('[aname="'+ alias_name + '"]')
									.filter('[fname="'+ delivery_to + '"]')
									.filter( id )
									.length;
			existName = 	$('tr')
									.filter('[aname="'+ alias_name + '"]')
									.filter('[fname="'+ delivery_to + '"]')
									.length;

			if( ! existNameID && existName ) {
					modWin.message += "Такие сочетания алиасов уже присутствуют";
					$('form :text[name="delivery_to"]').val('');

			}

			if (modWin.message.length > 0) {
				return false;
			}
			else {
				return true;
			}
}

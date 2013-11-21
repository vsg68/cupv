$(document).ready(function() {

/*
 * Общая для всех часть
 */
	TTOpts.aButtons.pslice(3,2);
	var TOptions = {
			"bJQueryUI": true,
			//"sScrollY":  eH + "px",
			"bPaginate": false,
			"sDom": "<'H'Tf>t<'F'ip>",
			"sServerMethod": "POST",
			"fnInitComplete": function () {
									this.fnAdjustColumnSizing();
									this.fnDraw();
							},
			"aoColumnDefs": [
								{"sClass": "center", "aTargets": [2] },
							],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
								$('td:nth-last').addClass('center')
								drawCheckBox(nRow);
								addRowAttr(nRow,'mbox',1);
							},
			"oTableTools": TTOpts
	}

/********************************/
		TOptions.oTableTools.sAjaxSource= "/admin/showTable/?tab=sections";
		TOptions.oTableTools.aButtons[3].sButtonText = 'РАЗДЕЛЫ';
		$('#tab-sections').dataTable(TOptions);


		TOptions.oTableTools.aButtons[1].sButtonClass = 'DTTT_button_new DTTT_disabled';
		TOptions.oTableTools.aButtons[3].sButtonText = 'СТРАНИЦЫ';
		$('#tab-controllers').dataTable(TOptions)

		TOptions.oTableTools.sAjaxSource= "/admin/showTable/?tab=controller";
		TOptions.oTableTools.aButtons[3].sButtonText = 'ОБЩЕЕ';
		TOptions.oTableTools.aButtons.spice(0,2);
		$('#tab-full').dataTable(TOptions);





});


/*
 *  Если НЕ выделена строка в таблице users - создавать для нее алиасы запрещаем
 *  Запещаем редактировать и удалять, если не выделено
 */
function blockNewButton(nodes) {

		if( nodes.length && (tab = nodes[0].offsetParent.id) ) {
			$('#ToolTables_'+tab+'_0').addClass('DTTT_disabled');
			$('#ToolTables_'+tab+'_2').addClass('DTTT_disabled');
		}

		if( nodes.length && nodes[0].offsetParent.id == 'tab-users') {
			$('#ToolTables_tab-aliases_1').addClass('DTTT_disabled');
			$('#ToolTables_tab-lists_0').addClass('DTTT_disabled');
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
 * Если хотим добавить в запрос на удаление какие-нить параметры -
 * то это делается тут
 */
function deleteWithParams(uid, tab, init) {
	if(tab == 'domains') {
		val = $('#'+uid).attr('domain');
		init['aname'] = val;
	}

	return init;
}

/*
 * Стираем значения в "подчиненных" таблицах
*/
function clearChildTable(uids) {

	if(tab == 'domains') {
		for(i=0; i < uids.length; i++) {
			id = '#tab-aliases-'+uids[i].id;
			$('#tab-aliases').dataTable().fnDeleteRow( $(id).get(0) );
		}
	}
}

/*
 * Функции проверок при редактировании записей в таблицах.
 * Проверка на совпадения доменов, алиасов - не производится !!
 */
modWin.validate_domains = function () {

			modWin.message 	= '';
			name 			= $('form :text[name="all_email"]').val();
			domain_name 	= $('form :text[name="domain_name"]').val();

			if ( email_enable) {
				if (! name ) {
					modWin.message += 'Если разрешена рассылка,то необходимо указать адрес. ';
				}
				// Нет проверки на существующие почтовые ящики
			}
			if ( ! domain_name ) {
				modWin.message += 'Поля алиаса и домена должны быть заполнены. ';
				if ( ! fnTestByType(domain_name,'domain')) {
					modWin.message += 'Поле "Название домена" должно иметь правильный формат.';
				}
			}
			if (modWin.message.length > 0) {
				return false;
			}
			else {
				return true;
			}
}

modWin.validate_transport = function () {
			modWin.message 	= '';
			address			= $('form :text[name="dalivery_to"]').val();
			domain_name 	= $('form :text[name="domain_name"]').val();

			if ( ! $('form :text[name="domain_name"]').val()) {
				modWin.message += 'Name is required. ';
			}
			if ( ! domain_name ) {
				modWin.message += 'Поля алиаса и домена должны быть заполнены. ';
				if ( ! fnTestByType(domain_name,'domain')) {
					modWin.message += 'Поле "Название домена" должно иметь правильный формат.';
				}
			}
			if ( ! fnTestByType(address,'transport')) {
				modWin.message += 'Поле "протокол:[адрес]" должно иметь правильный формат.';
			}
			if (modWin.message.length > 0) {
				return false;
			}
			else {
				return true;
			}
}

modWin.validate_aliases = function () {

			modWin.message = '';
			domain_name	= $('form :text[name="domain_name"]').val();


			if ( ! domain_name ) {
				modWin.message += 'Поля алиаса и домена должны быть заполнены. ';
				if ( ! fnTestByType(domain_name,'domain')) {
					modWin.message += 'Поле "Название домена" должно иметь правильный формат.';
				}
			}

			if (modWin.message.length > 0) {
				return false;
			}
			else {
				return true;
			}
}

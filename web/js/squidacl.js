$(document).ready(function() {

/*
 * Общая для всех настройка
 */
		var TOptions = {
				"bJQueryUI": true,
				//"sScrollY":  H/2 -110 + "px",
				"bPaginate": false,
				"sDom": "<'H'T>t<'F'>",
				"sAjaxSource": "/squidacl/showTable/",
				"sServerMethod": "POST",
				"fnInitComplete": function () {
										this.fnAdjustColumnSizing();
										this.fnDraw();
								},
				"aoColumns": [
							   {"mData":"name"},
							   {"mData":"type"},
							   {"mData":"comment","bSortable":false,},
							   {"mData":"active","sClass": "center","bSortable":false,},
							],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
									drawCheckBox(nRow);
								},
				"oTableTools": TTOpts
		};

		//~ TTOpts.aButtons[3] = {
							//~ "sExtends":"text",
							//~ "sButtonText": ".",
							//~ "sButtonClass": "DTTT_button_save DTTT_disabled",
							//~ "fnClick": function( nButton, oConfig ) {
											//~ //$(nButton).hasClass('DTTT_disabled') ? this.fnPrint( false, oConfig ) : this.fnPrint( true, oConfig );
										//~ }
							//~ };

		TTOpts.aButtons[5].sButtonText = 'ACL';
		TTOpts.aButtons.splice(4,1);
		$('#tab-squidacl').dataTable(TOptions);
		
		TTOpts.aButtons[1].sButtonClass = 'DTTT_button_new DTTT_disabled';
		TTOpts.aButtons[4].sButtonText = 'DATA';
		//~ TTOpts.aButtons.splice(3,1);
		TOptions.aoColumns.splice(1,1);
		delete TOptions.sAjaxSource;

		$('#tab-squidacl_data').dataTable(TOptions);
		

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

		if( nodes.length && nodes[0].offsetParent.id == 'tab-squidacl') {
			$('#ToolTables_tab-squidacl_data_1').addClass('DTTT_disabled');
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
		
		if( node[0].offsetParent.id == 'tab-squidacl') {
			$('#ToolTables_tab-squidacl_data_1').removeClass('DTTT_disabled');
		}
}

/*
 *  Получаю выделенную строку в таблице squidacl
 */
function usersRowID(objTT) {

		if( objTT.s.dt.sTableId != 'tab-squidacl' )
			return fnGetRowID("tab-squidacl");
		return ;
}

/*
 *  Если выделена строка в таблице acl - показываем связанные с ней data
 */
function showMapsTable(node) {

		if(node[0].offsetParent.id != 'tab-squidacl')
			return false;
		
		// при выборе ACL редактирование данных блокируются (ибо фокус со строки уходит)
		$('#ToolTables_tab-squidacl_data_0').addClass('DTTT_disabled');
		$('#ToolTables_tab-squidacl_data_2').addClass('DTTT_disabled');
		
		id = node[0].id.split('-')[2];
		$.getJSON( '/'+ ctrl +'/records/' + id, function(response) {
							$('#tab-squidacl_data').dataTable().fnClearTable();
							$('#tab-squidacl_data').dataTable().fnAddData(response);
					});
}

function deleteWithParams(uid, tab, init) {
	if(tab == 'squidacl_data') {
		val = fnGetParentSelectedRowID("#tab-squidacl").split('-')[2];
		init["pid"] = val;
	}

	return init;
}

/*
 * Стираем значения в "подчиненных" таблицах
*/
function clearChildTable(uids) {

		if(tab == 'squidacl') {
			$('#tab-squidacl_data').dataTable().fnClearTable();
		}
}

/*
 * Функции проверок при редактировании записей в таблицах.
 * Проверка на совпадения доменов, алиасов - не производится !!
 */
modWin.validate_squidacl = function () {
			
			return false;
			all_enable		= $('form :checkbox[name="all_enable"]').filter(':checked').length;
			all_email		= $('form :text[name="all_email"]').val();
			domain_name 	= $('form :text[name="domain_name"]').val();

			if ( all_enable) {
				if (! all_email ) {
					modWin.message += 'Если разрешена рассылка,то необходимо указать адрес. ';
				}
				// Нет проверки на существующие почтовые ящики
			}
			if ( ! domain_name ) {
				modWin.message += 'Поля алиаса и домена должны быть заполнены. ';
			}
			else {
				if ( ! fnTestByType(domain_name,'domain')) {
					modWin.message += 'Поле "Название домена" должно иметь правильный формат.';
				}
			}

			return modWin.message;
}

modWin.validate_squidacl_data = function () {
return false;
			address			= $('form :text[name="delivery_to"]').val();
			domain_name 	= $('form :text[name="domain_name"]').val();

			if ( ! domain_name ) {
				modWin.message += 'Поля алиаса и домена должны быть заполнены. ';
			}
			else {
				if ( ! fnTestByType(domain_name,'domain')) {
					modWin.message += 'Поле "Название домена" должно иметь правильный формат.';
				}
			}

			if ( ! fnTestByType(address,'transport')) {
				modWin.message += 'Поле "протокол:[адрес]" должно иметь правильный формат.';
			}

			return modWin.message;
}

modWin.validate_aliases = function () {

			domain_name	= $('form :text[name="domain_name"]').val();

			if ( ! domain_name ) {
				modWin.message += 'Поля алиаса и домена должны быть заполнены. ';
			}
			else {
				if ( ! fnTestByType(domain_name,'domain')) {
					modWin.message += 'Поле "Название домена" должно иметь правильный формат.';
				}
			}

			return modWin.message;
}

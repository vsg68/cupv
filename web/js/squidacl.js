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
									addRowAttr( nRow, 'sid', 0 );
								},
				"oTableTools": TTOpts
		};

		TTOpts.aButtons[5].sButtonText = 'ACL';
		TTOpts.aButtons.splice(3,2);
		$('#tab-squidacl').dataTable(TOptions);
		
		TTOpts.aButtons[1].sButtonClass = 'DTTT_button_new DTTT_disabled';
		TTOpts.aButtons[3].sButtonText = 'DATA';
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
 */

function validate() {
			tab		= $('form :hidden[name="tab"]').val();
			id		= '#tab-' + tab + '-' + $(':hidden[name="id"]').val();
			name	= $('form :text[name="name"]').val();
			
			existSidID = $('tr', '#tab-' + tab).filter('[sid="' + name + '"]').filter(id).length;
			existSid   = $('tr', '#tab-' + tab).filter('[sid="' + name + '"]').length;

			if( ! existSidID && existSid ) {
				modWin.message += 'В данном наборе значение '+ name +' уже существует!'
			}

			return modWin.message;
}

modWin.validate_squidacl = validate;
modWin.validate_squidacl_data = validate;

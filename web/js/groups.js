$(document).ready(function() {

/*
 * Общая для всех настройка
 */
	TTOpts.aButtons.splice(3,2);
	var TOptions = {
				"bJQueryUI": true,
				//"sScrollY":  550 + "px",
				"bPaginate": false,
				"sDom": "<'H'T>t<'F'ip>",
				"fnInitComplete": function () {
										this.fnAdjustColumnSizing();
										this.fnDraw();
								},
				"aoColumns": [
								{"mData": "name", "bSortable": true,},
								{"mData": "note","bSortable": false,},
								{"mData": "active", "bSortable": false, "sClass": "center",},
							],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
									drawCheckBox(nRow);
									addRowAttr(nRow,'group',0);
								},
				"oTableTools": TTOpts
		}

		TOptions.oTableTools.aButtons[3].sButtonText = 'СПИСКИ РАССЫЛКИ';
		$('#tab-groups').dataTable(TOptions);


		TOptions.oTableTools.aButtons[2] = {
											"sExtends":    "text",
											"sButtonText": ".",
											"sButtonClass": 'DTTT_button_group  DTTT_disabled',
											"fnClick": function( nButton, oConfig, oFlash ){
												//предотвращаем новое, если в основной таблице ничего не выбрано
												if( ! $(nButton).hasClass('DTTT_disabled') ) {
													fnGroupEdit( fnGetParentSelectedRowID('#tab-groups'), 'lists' );
												}
											}};
		TOptions.oTableTools.aButtons[3].sButtonText = 'ЧЛЕНЫ СПИСКА';
		TOptions.aoColumns = [
								{"mData": "mailbox", "bSortable": true,},
								{"mData": "username","bSortable": false,},
								{"mData": "active", "bSortable": false, "sClass": "center",},
							],
		TTOpts.aButtons.splice(0,2);
		$('#tab-lists').dataTable(TOptions)


		TOptions.aoColumns = [
								{"bSortable": true,},
								{"bSortable": true,},
								{"bSortable": false,},
								{"bSortable": false, "sClass": "center",},
							],
		TOptions.sAjaxSource = "/"+ ctrl +"/showTable/";
		TOptions.sDom = "<'H'Tf>t<'F'ip>",
		TOptions.oTableTools.aButtons[1].sButtonText = 'ОБЩИЙ СПИСОК';
		TTOpts.aButtons.splice(0,1);
		$('#tab-full').dataTable(TOptions);

});

var url = 'url(/css/smoothness/images/usr.png)';

/*
 *  Если НЕ выделена строка в таблице users - создавать для нее алиасы запрещаем
 *  Запещаем редактировать и удалять, если не выделено
 */
function blockNewButton(nodes) {

		if( nodes.length && (tab = nodes[0].offsetParent.id) ) {
			$('#ToolTables_'+tab+'_0').addClass('DTTT_disabled');
			$('#ToolTables_'+tab+'_2').addClass('DTTT_disabled');
		}

		if( nodes.length && nodes[0].offsetParent.id == 'tab-groups') {
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

		if( node[0].offsetParent.id == 'tab-groups') {
			$('#ToolTables_tab-lists_0').removeClass('DTTT_disabled');
		}
}


/*
 * Стираем значения в "подчиненных" таблицах
*/
function clearChildTable(uids) {

	if(tab == 'groups') {
		$('#tab-lists').dataTable().fnClearTable();
	}
}


/*
 *  Если выделена строка в таблице users - показываем связанные с ней алиасы
 */
function showMapsTable(node) {

		if(node[0].offsetParent.id != 'tab-groups')
			return false;

		id = node[0].id.split('-')[2];
		$.ajax({
				type: "GET",
				url: '/'+ ctrl +'/records/' + id,
				dataType: "json",
				success: function(response) {
										$('#tab-lists').dataTable().fnClearTable();
										$('#tab-lists').dataTable().fnAddData(response);

										},
				error: function() {
									$('#tab-lists').dataTable().fnClearTable();
									}
		});
}


/*
 * Функция срабатывает после обновления данных
 * Изменяем таблицу tab-full
 */
function afterUpdateData(str,node) {
	 $('#tab-full').dataTable().fnReloadAjax();
}

/*
 * Функция срабатывает после добавления данных
 * Изменяем таблицу tab-full
 */
function afterAddData(str) {
	 $('#tab-full').dataTable().fnReloadAjax();
}

/*
 * Функции проверок при редактировании записей в таблицах.
 * Проверка на совпадения доменов, алиасов - не производится !!
 */
modWin.validate_groups = function () {

			name = $('form :text[name="name"]').val();
			id	 = '#tab-groups-' + $(':hidden[name="id"]').val();

			if ( ! name ) {
				return  modWin.message = 'Название обязательно. ';
			}


			existGrpID = $('tr').filter('[group="' + name + '"]').filter(id).length;
			existGrp   = $('tr').filter('[group="' + name + '"]').length;

			if( ! existGrpID && existGrp ) {
				return modWin.message = 'Группа '+ name +' уже существует!'
			}

}

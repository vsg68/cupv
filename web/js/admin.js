$(document).ready(function() {

/*
 * Общая для всех часть
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
			"aoColumnDefs": [
								{ bSortable: true, aTargets: [ 0 ] },
								{ bSortable: false, aTargets: [ '_all' ] },
								{ sClass: "center", aTargets: [ -1 ] },
							],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
								drawCheckBox(nRow);
								//addRowAttr(nRow,'mbox',1);
							},
			"oTableTools": TTOpts
	}

/********************************/
		TOptions.oTableTools.aButtons[3].sButtonText = 'РАЗДЕЛЫ';
		$('#tab-sections').dataTable(TOptions);


		TOptions.oTableTools.aButtons[3].sButtonText = 'СТРАНИЦЫ';
		TOptions.oTableTools.aButtons[1].sButtonClass = 'DTTT_button_new DTTT_disabled';
		$('#tab-controllers').dataTable(TOptions)

		// Общая таблица отличается от двух других
		TOptions.aoColumnDefs.unshift({ bSortable: true, aTargets: [ 2 ] } );
		TOptions.aaSorting = [[ 2, "asc" ]];
		TOptions.sAjaxSource = "/admin/showTable/";
		TOptions.oTableTools.aButtons[3].sButtonText = 'ОБЩИЙ СПИСОК РАЗДЕЛОВ';
		TOptions.oTableTools.aButtons.splice(0,3);
		$('#tab-full').dataTable(TOptions);

/********************************/



});


/*
 *  Если НЕ выделена строка в таблице sections - создавать для нее controllers запрещаем
 *  Запещаем редактировать и удалять, если не выделено
 */
function blockNewButton(nodes) {

		if( nodes.length && (tab = nodes[0].offsetParent.id) ) {
			$('#ToolTables_'+tab+'_0').addClass('DTTT_disabled');
			$('#ToolTables_'+tab+'_2').addClass('DTTT_disabled');
		}

		if( nodes.length && nodes[0].offsetParent.id == 'tab-sections') {
			$('#ToolTables_tab-controllers_1').addClass('DTTT_disabled');
		}
}

/*
 *  Если выделена строка в таблице sections - разрешаем создавать для нее controllers
 *  Разрешаем редактировать и удалять, если выделено
 */
function unblockNewButton(node) {

		tab = node[0].offsetParent.id;

		$('#ToolTables_'+tab+'_0').removeClass('DTTT_disabled');
		$('#ToolTables_'+tab+'_2').removeClass('DTTT_disabled');

		if( node[0].offsetParent.id == 'tab-sections') {
			$('#ToolTables_tab-controllers_1').removeClass('DTTT_disabled');
		}
}

/*
 *  Получаю выделенную строку в таблице sections
 */
function usersRowID(objTT) {

		if( objTT.s.dt.sTableId != 'tab-sections' )
			return fnGetRowID("tab-sections");
		return 0;
}

/*
 *  Если выделена строка в таблице sections - показываем связанные с ней controllers
 */
function showMapsTable(node) {

		if(node[0].offsetParent.id != 'tab-sections')
			return false;

		id = node[0].id.split('-')[2];
		$.getJSON( '/'+ ctrl +'/records/' + id, function(response) {
														$('#tab-controllers').dataTable().fnClearTable();
														$('#tab-controllers').dataTable().fnAddData(response);
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
function afterAddData(str,node) {
	$('#tab-full').dataTable().fnReloadAjax();
}

/*
 * Стираем значения в "подчиненных" таблицах
*/
function clearChildTable(uids) {

	if(tab == 'sections') {
		$('#tab-controllers').dataTable().fnClearTable();
	}

	$('#tab-full').dataTable().fnReloadAjax();
}

/*
 * Функции проверок при редактировании записей в таблицах.
 * Проверка на совпадения доменов, алиасов - не производится !!
 */
modWin.validate_sections = function () {

			return true;

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

modWin.validate_controllers = function () {
			return true;
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

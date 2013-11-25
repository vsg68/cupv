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
		TOptions.oTableTools.aButtons[3].sButtonText = 'РОЛИ';
		$('#tab-roles').dataTable(TOptions);


		TOptions.oTableTools.aButtons[3].sButtonText = 'ПРАВА НА СТРАНИЦЫ';
		TOptions.oTableTools.aButtons[1].sButtonClass = 'DTTT_button_new DTTT_disabled';
		$('#tab-rights').dataTable(TOptions)

		// Общая таблица отличается от двух других
		//TOptions.aoColumnDefs.unshift({ bSortable: true, aTargets: [ 2 ] } );
		//TOptions.sAjaxSource = "/"+ ctrl +"/showTable/";
		TOptions.oTableTools.aButtons[3].sButtonText = 'МАТРИЦА РОЛЕЙ';
		TOptions.oTableTools.aButtons.splice(0,3);
		$('#tab-matrix').dataTable(TOptions);

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

		if( nodes.length && nodes[0].offsetParent.id == 'tab-roles') {
			$('#ToolTables_tab-rights_1').addClass('DTTT_disabled');
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

		if( node[0].offsetParent.id == 'tab-roles') {
			$('#ToolTables_tab-rights_1').removeClass('DTTT_disabled');
		}
}

/*
 *  Получаю выделенную строку в таблице sections
 */
function usersRowID(objTT) {

		if( objTT.s.dt.sTableId != 'tab-roles' )
			return fnGetRowID("tab-roles");
		return 0;
}

/*
 *  Если выделена строка в таблице sections - показываем связанные с ней controllers
 */
function showMapsTable(node) {

		if(node[0].offsetParent.id != 'tab-roles')
			return false;

		id = node[0].id.split('-')[2];
		$.getJSON( '/'+ ctrl +'/records/' + id, function(response) {
														$('#tab-rights').dataTable().fnClearTable();
														$('#tab-rights').dataTable().fnAddData(response);
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
modWin.validate_sections = function() {
									return emptyValidate();
								}


modWin.validate_controllers = function() {
									return emptyValidate();
								}

function emptyValidate() {

			msg 	= '';

			if (! $('form :text[name="name"]').val() ) {
				msg = 'Необходимо указывать названия раздела. '
			}

			return msg;
}

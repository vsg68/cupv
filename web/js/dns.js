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
			"aoColumnDefs": [
								{ bSortable: true, aTargets: [ 0 ] },
								{ bSortable: false, aTargets: [ '_all' ] },
							],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
								//drawCheckBox(nRow);
								//updateClass(nRow);
							},
			"oTableTools": TTOpts
	}

/********************************/
		TOptions.oTableTools.aButtons[3].sButtonText = 'DOMAIN NAME SERVER';
		$('#tab-dns').dataTable(TOptions);

		TOptions.oTableTools.aButtons[3].sButtonText = 'DOMAIN RECORDS';
		TOptions.oTableTools.aButtons[1].sButtonClass = 'DTTT_button_new DTTT_disabled';
		$('#tab-records').dataTable(TOptions)


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

		if( nodes.length && nodes[0].offsetParent.id == 'tab-dns') {
			$('#ToolTables_tab-records_1').addClass('DTTT_disabled');
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

		if( node[0].offsetParent.id == 'tab-dns') {
			$('#ToolTables_tab-records_1').removeClass('DTTT_disabled');
		}
}

/*
 *  Получаю выделенную строку в таблице rights
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

		if(node[0].offsetParent.id != 'tab-dns')
			return false;

		id = node[0].id.split('-')[2];
		$.ajax({
				url: '/'+ ctrl +'/records/' + id,
				type: "GET",
				dataType: "json",
				success: function(response) {
												$('#tab-records').dataTable().fnClearTable();
												$('#tab-records').dataTable().fnAddData(response);
						},
				error: function(response) {

							var msg = response.responseText;
							if( $(msg).find('.form-alert').length ) {
								$(msg).modal( modInfo );
							}
						},
				});
}

/*
 * Функция срабатывает после обновления данных
 * Изменяем таблицу tab-full
 */
function updateClass(nRow) {

	  if(nRow.id.split('-')[1] == 'roles' )
		return false;

	  mode = $('td:eq(2)', nRow).text();
	  $(nRow).removeClass('gradeX').removeClass('gradeB');

	  if( mode == 'WRITE') {
		$(nRow).addClass('gradeB');
	  }

	  if( mode == 'NONE') {
		 $(nRow).addClass('gradeX');
	  }
}

/*
 * Функция срабатывает после изменения данных
 * Почему-то не изменяется ID строки программно
 * поэтому делаю это руками
 */
function afterUpdateData(str,node) {

	if(node.id.split('-')[1] == 'rights' ) {
		node.id = str.DT_RowId ;
	}
}

/*
 * Функции проверок при редактировании записей в таблицах.
 * Проверка на совпадения доменов, алиасов - не производится !!
 */
modWin.validate_roles = function() {
									return emptyValidate();
								}


modWin.validate_records = function() {
									return false;
								}



function emptyValidate() {

			msg 	= '';

			if (! $('form :text[name="name"]').val() ) {
				msg = 'Необходимо указывать названия раздела. '
			}

			return msg;
}

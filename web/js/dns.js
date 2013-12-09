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
								if( nRow.id.split('-')[1] == 'records') {
									addRowAttr(nRow,'type',1);
								}
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

		if( objTT.s.dt.sTableId != 'tab-dns' )
			return fnGetRowID("tab-dns");
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
 * Стираем значения "подчиненных" таблиц
*/
function clearChildTable(info_data) {

	$('#tab-records').dataTable().fnClearTable();

	if(info_data) {
		$(info_data).modal(modInfo);
	}
}


/*
 * Функции проверок при редактировании записей в таблицах.
 * Проверка на совпадения доменов, алиасов - не производится !!
 */
modWin.validate_dns = function() {
			if ( ! $('form :text[name="name"]').val() )	 {
				return modWin.message = 'Заполнение полей  - обязательно. ';
			}
}


modWin.validate_records = function() {


			if ( ! $('form :text[name="name"]').val() ||
				 ! $('form :text[name="content"]').val() ||
				 ! $('form :text[name="ttl"]').val()
				)
			 {
				return modWin.message = 'Заполнение полей  - обязательно. ';
			}

}

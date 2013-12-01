$(document).ready(function() {

/*
 * Общая для всех настройка
 */
//~ var TOptions = {
		//~ "bJQueryUI": true,
		//~ //"sScrollY":  H/2 -110 + "px",
		//~ "bPaginate": false,
		//~ "sDom": "<'H'T>t<'F'>",
		//~ "aoColumnDefs": [
							//~ {"bSortable":true, "aTargets": [0,1] },
							//~ {"sClass": "center", "aTargets": [3,4] },
						//~ ],
		//~ "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
							//~ drawCheckBox(nRow);
							//~ addRowAttr(nRow,'domain',0); // Запоминаю имя домена для удаления алиасов
						//~ },
		//~ "oTableTools" : {
				//~ "sRowSelect": "single",
				//~ "fnRowSelected": function(node){
									//~ // Только для таблицы пользователей
									//~ //tab = node[0].id.split('-')[0];
									//~ if( function_exists('showMapsTable') )
										//~ showMapsTable( node );
//~
									//~ if( function_exists('unblockNewButton') )
										//~ unblockNewButton( node );  // Разблокировка кнопки
								//~ },
				//~ "fnRowDeselected": function(nodes){
									//~ // ставим блокировку на "New" для алиасов
										//~ if( function_exists('blockNewButton'))
											//~ blockNewButton( nodes );
									//~ },
				//~ "aButtons":[
								//~ {
									//~ "sExtends":"text",
									//~ "sButtonText": ".",
									//~ "sButtonClass": "DTTT_button_edit DTTT_disabled",
									//~ "fnClick": function( nButton, oConfig, oFlash ){
													//~ RowID = fnGetSelectedRowID(this);
													//~ fnEdit( RowID , 0);
												//~ },
								//~ },
								//~ {
									//~ "sExtends":"text",
									//~ "sButtonText": ".",
									//~ "sButtonClass": "DTTT_button_new",
									//~ "fnClick": function( nButton, oConfig, oFlash ){
													//~ if( ! $(nButton).hasClass('DTTT_disabled') ) {
														//~ pid = function_exists('usersRowID') ? usersRowID(this) : 0;
														//~ fnEdit( this.s.dt.sTableId +'-0', pid);
													//~ }
												//~ },
								//~ },
								//~ {
									//~ "sExtends":"text",
									//~ "sButtonText": ".",
									//~ "sButtonClass": "DTTT_button_del DTTT_disabled",
									//~ "fnClick": function( nButton, oConfig, oFlash ){
													//~ RowID = fnGetSelectedRowID(this);
													//~ fnDelete( RowID, 0 );
												//~ }
								//~ },
								//~ {
									//~ "sExtends":    "text",
									//~ "sButtonText": "ПОЧТОВЫЕ ДОМЕНЫ",
									//~ "sButtonClass": 'DTTT_label  DTTT_disabled',
								//~ }
							//~ ],
				//~ }
//~ };

		TTOpts.aButtons.splice(3,2);
		var TOptions = {
				"bJQueryUI": true,
				//"sScrollY":  550 + "px",
				"bPaginate": false,
				"sDom": "<'H'T>t<'F'ip>",
				//"sAjaxSource": "/"+ ctrl +"/showTable/groups",
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
									//updateClass(nRow);
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
													fnGroupEdit( fnGetParentSelectedRowID('#tab-groups') );
												}
											}};
		TOptions.oTableTools.aButtons[3].sButtonText = 'ЧЛЕНЫ СПИСКА';
		TTOpts.aButtons.splice(0,2);
		$('#tab-lists').dataTable(TOptions)

		TOptions.oTableTools.aButtons[1].sButtonText = 'ОБЩИЙ СПИСОК';
		TTOpts.aButtons.splice(0,1);
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
 * Выполняется после апдейта групп
 */
function afterEditGroup(response) {};

/*
 * Функции проверок при редактировании записей в таблицах.
 * Проверка на совпадения доменов, алиасов - не производится !!
 */
modWin.validate_domains = function () {

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

modWin.validate_transport = function () {

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

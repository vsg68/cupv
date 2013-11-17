$(document).ready(function() {

/*
 * Общая для всех настройка
 */
var TOptions = {
		"bJQueryUI": true,
		//"sScrollY":  d_min + "px",
		"bPaginate": false,
		"sDom": "<'H'T>t<'F'>",
		"aoColumnDefs": [
							{"bSortable":true, "aTargets": [0,1] },
							{"sClass": "center", "aTargets": [3,4] },
						],
		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
							drawCheckBox(nRow);
							addRowAttr(nRow,'domain',0); // Запоминаю имя домена для удаления алиасов
						},
		"oTableTools" : {
				"sRowSelect": "single",
				"fnRowSelected": function(node){
									// Только для таблицы пользователей
									//tab = node[0].id.split('-')[0];
									if( function_exists('showMapsTable') )
										showMapsTable( node );

									if( function_exists('unblockNewButton') )
										unblockNewButton( node );  // Разблокировка кнопки
								},
				"fnRowDeselected": function(nodes){
									// ставим блокировку на "New" для алиасов
										if( function_exists('blockNewButton'))
											blockNewButton( nodes );
									},
				"aButtons":[
								{
									"sExtends":"text",
									"sButtonText": ".",
									"sButtonClass": "DTTT_button_edit DTTT_disabled",
									"fnClick": function( nButton, oConfig, oFlash ){
													RowID = fnGetSelectedRowID(this);
													fnEdit( RowID , 0);
												},
								},
								{
									"sExtends":"text",
									"sButtonText": ".",
									"sButtonClass": "DTTT_button_new",
									"fnClick": function( nButton, oConfig, oFlash ){
													if( ! $(nButton).hasClass('DTTT_disabled') ) {
														pid = function_exists('usersRowID') ? usersRowID(this) : 0;
														fnEdit( this.s.dt.sTableId +'-0', pid);
													}
												},
								},
								{
									"sExtends":"text",
									"sButtonText": ".",
									"sButtonClass": "DTTT_button_del DTTT_disabled",
									"fnClick": function( nButton, oConfig, oFlash ){
													RowID = fnGetSelectedRowID(this);
													fnDelete( RowID, 0 );
												}
								},
								{
									"sExtends":    "text",
									"sButtonText": "ПОЧТОВЫЕ ДОМЕНЫ",
									"sButtonClass": 'DTTT_label  DTTT_disabled',
								}
							],
				}
};

		$('#tab-domains').dataTable(TOptions);

		TOptions.aoColumnDefs[1] = {"sClass": "center", "aTargets": [3] }; //
		TOptions.oTableTools.aButtons[3].sButtonText = 'АЛИАСЫ ДОМЕНОВ';
		$('#tab-aliases').dataTable(TOptions)

		TOptions.oTableTools.aButtons[3].sButtonText = 'ТРАНСПОРТ';
		$('#tab-transport').dataTable(TOptions);


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


modWin.validate_domains = function () {

			modWin.message 	= '';
			email_enable	= $('form :checkbox[name="all_enable"]').val();
			name 			= $('form :text[name="all_email"]').val();

			if ( email_enable) {
				if (! name ) {
					modWin.message += 'Address is required. ';
				}
				// Нет проверки на существующие почтовые ящики
			}

			if ( ! $('form :text[name="domain_name"]').val()) {
				modWin.message += 'Name is required. ';
			}

			if (modWin.message.length > 0) {
				return false;
			}
			else {
				return true;
			}
2}

modWin.validate_transport = function () {
			modWin.message 	= '';
			addrress		= $('form :text[name="dalivery_to"]').val();
			name 			= $('form :text[name="all_email"]').val();

			if ( ! $('form :text[name="domain_name"]').val()) {
				modWin.message += 'Name is required. ';
			}

			if ( ! fnTestByType(address,'proto_address')) {
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
			delivery_to	= $('form option:selected').val();;
			id			= '#tab-aliases-' + $(':hidden[name="id"]').val();


			if ( ! (domain_name && delivery_to) ) {
				modWin.message += 'Поля алиаса и домена должны быть заполнены. ';
			}

			if (modWin.message.length > 0) {
				return false;
			}
			else {
				return true;
			}
}

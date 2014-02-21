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
				"aoColumns": [ {"mData":"acl","bVisible":false,"bSortable":false,},
							   {"mData":"name"},
							   {"mData":"type"},
							   {"mData":"comment","bSortable":false,},
							   {"mData":"data","bVisible":false,"bSortable":false,},
							   //{'mData':'active',"sClass": "center","bSortable":false,},
							],
				//~ "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
									//~ drawCheckBox(nRow);
								//~ },
				"oTableTools": TTOpts
		};

		TTOpts.aButtons[4] = {
							"sExtends":"save",
							"sButtonText": ".",
							"fnClick": function( nButton, oConfig ) {
											$(nButton).hasClass('DTTT_disabled') ? this.fnPrint( false, oConfig ) : this.fnPrint( true, oConfig );
										}
							};
		TTOpts.aButtons[5].sButtonText = 'ACL';
		TTOpts.aButtons.splice(4,1);
		$('#tab-acl').dataTable(TOptions);
		
		TTOpts.aButtons[1].sButtonClass = 'DTTT_button_new DTTT_disabled';
		TTOpts.aButtons[4].sButtonText = 'DATA';
		TTOpts.aButtons.splice(3,1);
		TOptions.aoColumns = [null]; 
		delete TOptions.sAjaxSource;
		$('#tab-data').dataTable(TOptions);
		

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
 *  Если выделена строка в таблице sections - показываем связанные с ней controllers
 */
function showMapsTable(node) {

		if(node[0].offsetParent.id != 'tab-acl')
			return false;
		//Берем скрытое поле и парсим его в таблицу
		aTR = $('#tab-acl').dataTable().fnGetData( node[0].sectionRowIndex );

		$('#tab-data').dataTable().fnClearTable();
		cells = aTR.data.split(/\s/);
		
		for (i = 0; i < cells.length; i++) {
			$('#tab-data').dataTable().fnAddData([cells[i]]);
		}	
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

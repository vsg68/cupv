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

		if( node[0].offsetParent.id == 'tab-users') {
			$('#ToolTables_tab-aliases_1').removeClass('DTTT_disabled');
			$('#ToolTables_tab-lists_0').removeClass('DTTT_disabled');
		}
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

	if(tab != 'domains')
		return false;

	for(i=0; i < uids.length; i++) {
		x = '#tab-aliases-'+uids[i]['id'];
		$('#tab-'+tab).dataTable().fnDeleteRow( $(x).get(0) );
	}
}

/*
 * Редактирование групп
 */
function fnGroupEdit(uid) {

		if( ! uid.length )
			return false;

		tab = uid.split('-')[1];
		pid = uid.split('-')[2];

		$.post('/'+ ctrl +'/edGroup/'+pid, {}, function(response){
												$(response).modal(modGroup);
										});
}

/*
 * Скрываем колонку паролей
 */
function fnShowHide(nButton) {

	iCol = 3;
	tab = 'tab-users';

	var oTable = $('#'+tab).dataTable();
	var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;

	oTable.fnSetColumnVis( iCol, bVis ? false : true );

	// В зависимости от показа - включаем или выключаем возможность печати
	$(nButton).toggleClass('DTTT_unvis');
	$('.DTTT_button_print').toggleClass('DTTT_disabled');
}


/*
 * Опции модального окна для групп
 */
var modGroup = {

		onShow: function(dialog){
			closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
			$('#sb').button({label: 'Send'});

			// Показе документа инициализирую функции
			$('#sb').click(function (e) {
					e.preventDefault();
					// выделяю uid
					uid = $('.table-grp')[0].id.split('-')[1];

					if( $('#grp-right li').length ) {
					// Если у пользователя есть группы
						$('#grp-right li').each(function(){
							grp_id = this.id.split('-')[1];
							$('#usersform').append('<input type="hidden" name="grp_id[]" value="'+grp_id+'">');
						});
					}
					else
						$('#usersform').append('<input type="hidden" name="grp_id[]" value="">');

					$.post('/'+ ctrl +'/edGroup/'+uid, $('#usersform').serialize(), function(response){
																			// Записывам в таблицу групп
																			$('#tab-lists').dataTable().fnClearTable();
																			$('#tab-lists').dataTable().fnAddData(response);
																			$.modal.close();
																			}, 'json');
			});

			$(".nest-grp").selectable({
						start: function( event, ui ) {
										tid = event.target.id;
										if( $('#'+tid).children('.ui-selected').length == 0 ) {
											$('.ui-selected').removeClass('ui-selected');
										}
									},
						selected: function( event, ui ) {
										direction = event.target.id.split('-')[1];
										$('.image-arrow').addClass('disable-arrow');
										$('#arrow-' + direction).removeClass('disable-arrow');

									},
			});

			$('.image-arrow').click(function(){

										if($(this).hasClass('disable-arrow'))
											return false;

										var this_area_id = '#grp-'+this.id.split('-')[1];
										var target_area_id = $('.nest-grp').not(this_area_id);

										$('li.ui-selected').each(function(){
																		obj = $(this).clone().removeClass('ui-selected');
																		$(target_area_id).append(obj);
																		this.remove();
																});

										$(this).addClass('disable-arrow');
							});

		},



};

modWin.validate_domains = function () {

			return true;
			modWin.message 	= '';
			login 			= $('form :text[name="login"]').val();
			mailbox 		=  login + '@' +  $('form option:selected').val();
			allow_nets 		= $('form :text[name="allow_nets"]').val();
			id				= '#tab-users-' + $(':hidden[name="id"]').val();

			if ( ! login ) {
				modWin.message += 'Login is required. ';
			}
			else{
				existMboxID = $('tr').filter('[mbox="' + mailbox + '"]').filter(id).length;
				existMbox   = $('tr').filter('[mbox="' + mailbox + '"]').length;

				if( ! existMboxID && existMbox ) {
					modWin.message += 'П/я '+ mailbox +' уже существует!'
				}
			}

			if ( ! $('form :text[name="username"]').val()) {
				modWin.message += 'Name is required. ';
			}

			if ( ! $('form :text[name="password"]').val()) {
				modWin.message += 'Message is required.';
			}

			if ( ! $('form :text[name="password"]').val()) {
				modWin.message += 'Message is required.';
			}

			if ( ! fnTestByType(allow_nets,'nets')) {
				modWin.message += 'Поле "разрешенные сети" должно содержать правильную маску сети.\n';
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
			alias_name	= $('form :text[name="alias_name"]').val();
			delivery_to	= $('form :text[name="delivery_to"]').val();
			id			= '#tab-aliases-' + $(':hidden[name="id"]').val();


			if ( ! (alias_name || delivery_to) ) {
				modWin.message += 'Хотя бы одно поле должно быть заполнено. ';
			}

			if ( !(fnTestByType( alias_name, 'mail') && alias_name) ) {
				modWin.message += 'поле должно содержать почтовый адрес';
			}

			if ( !(fnTestByType( delivery_to, 'mail') && delivery_to) ) {
				modWin.message += 'поле должно содержать почтовый адрес';
			}

			existNameID = 	$('tr')
									.filter('[aname="'+ alias_name + '"]')
									.filter('[fname="'+ delivery_to + '"]')
									.filter( id )
									.length;
			existName = 	$('tr')
									.filter('[aname="'+ alias_name + '"]')
									.filter('[fname="'+ delivery_to + '"]')
									.length;

			if( ! existNameID && existName ) {
					modWin.message += "Такие сочетания алиасов уже присутствуют";
					$('form :text[name="delivery_to"]').val('');

			}

			if (modWin.message.length > 0) {
				return false;
			}
			else {
				return true;
			}
}

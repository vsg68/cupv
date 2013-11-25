$(document).ready(function() {

		H 	= $(window).outerHeight();
		rH	= 100;	// Скролл таблицы записей
		d_min = rH + 110;  // Расстояние от главной таблицы до дна
		eH	= 550;	// Скролл главной таблицы
		// Настройка скроллинга большой таблицы
		if( eH + d_min > H )
			eH = H - d_min;

		var lTable = $('#tab-lists').dataTable({
								"bJQueryUI": true,
								"sDom": '<T>t',
								"sScrollY": rH+"px",
								"aoColumnDefs": [
													{"bSortable":false, "aTargets": [0] },
													{"bSortable":false, "aTargets": [1] },
												],
								"oTableTools": {
									"aButtons":	[
													{
														"sExtends":    "text",
														"sButtonText": ".",
														"sButtonClass": 'DTTT_button_group  DTTT_disabled',
														"fnClick": function( nButton, oConfig, oFlash ){
															//предотвращаем новое, если в основной таблице ничего не выбрано
															if( ! $(nButton).hasClass('DTTT_disabled') ) {
																fnGroupEdit( usersRowID(this) );
															}
														},
													},
													{
														"sExtends":    "text",
														"sButtonText": "РАССЫЛКА",
														"sButtonClass": 'DTTT_label  DTTT_disabled',
													}
												]
									},
								});


		var oTable = $('#tab-users').dataTable({
								"bJQueryUI": true,
								"sScrollY":  eH + "px",
								"bPaginate": false,
								"sDom": "<'H'Tf>t<'F'ip>",
								"sAjaxSource": "/users/showTable/",
								"sServerMethod": "POST",
								"fnInitComplete": function () {
														this.fnAdjustColumnSizing();
														this.fnDraw();
												},
								"aoColumnDefs": [
													{"bVisible":false,"aTargets": [3] },
													{"bSortable":false, "aTargets": [3,4,5,6,7,8] },
													{"sClass": "center", "aTargets": [7,8] },
													{"sClass": "nowrap", "aTargets": [0] },
												],
								"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
													drawCheckBox(nRow);
													addRowAttr(nRow,'mbox',1);
												},
								"oTableTools": TTOpts
							});


		TTOpts.aButtons[1].sButtonClass = 'DTTT_button_new DTTT_disabled';
		TTOpts.aButtons[5].sButtonText = 'АЛИАСЫ';
		TTOpts.aButtons.splice(3,2);

		var aTable = $('#tab-aliases').dataTable({
								"bJQueryUI": true,
								"sDom": '<T>t',
								"sScrollY": rH+"px",
								"aoColumnDefs": [{"sClass": "center","bSortable":false, "aTargets": [2] }],
								"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
													drawCheckBox(nRow);
													// добавляем аттриут data для валидации
													addRowAttr(nRow,'aname',0); // alias_name
													addRowAttr(nRow,'fname',1); // delivery_to
												},
								"oTableTools": TTOpts,
								});


		$('body').on('click','.mkpwd', function(){

			$(this).closest('tr').find(':text[name="password"]').val(mkpasswd());
		});


});

/*
 *  Если выделена строка в таблице users - показываем связанные с ней алиасы
 */
function showMapsTable(node) {

		if(node[0].offsetParent.id != 'tab-users')
			return false;

		id = node[0].id.split('-')[2];
		$.ajax({
				type: "GET",
				url: '/'+ ctrl +'/records/' + id,
				dataType: "json",
				success: function(response) {
										$('#tab-aliases').dataTable().fnClearTable();
										$('#tab-aliases').dataTable().fnAddData(response['aliases']);
										$('#tab-lists').dataTable().fnClearTable();
										$('#tab-lists').dataTable().fnAddData(response['lists']);

										},
				error: function() {
									$('#tab-aliases').dataTable().fnClearTable();
									$('#tab-lists').dataTable().fnClearTable();
									}
		});
}

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
 *  Получаю выделенную строку в таблице users
 */
function usersRowID(objTT) {

		if( objTT.s.dt.sTableId != 'tab-users' )
			return fnGetRowID("tab-users");
		return 0;
}

/*
 * Стираем значения "подчиненных" таблиц
*/
function clearChildTable(info_data) {

	if(tab != 'users')
		return false;

	$('#tab-aliases').dataTable().fnClearTable();
	$('#tab-lists').dataTable().fnClearTable();

	if(info_data) {
		$(info_data).modal(modInfo);
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
 * Если хотим добавить в запрос на удаление какие-нить параметры -
 * то это делается тут
 */
function deleteWithParams(uid, tab, init) {
	if(tab == 'users') {
		val = $('#'+uid).attr('mbox');
		init["aname"] = val;
	}

	return init;
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

modWin.validate_users = function () {

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

			return modWin.message;
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

			return modWin.message;
}

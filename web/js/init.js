
var ctrl = window.location.pathname.split('/')[1];
ctrl = ( ctrl ) ? ctrl : 'users';



$(document).ready(function() {

		$('#'+ctrl+'.pagetab').addClass('pagetab-selected');

} );

/*
 * Аналог php-функции function_exists
 */
function function_exists( function_name ) {	// Return TRUE if the given function has been defined
	//
	// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +   improved by: Steve Clay
	// +   improved by: Legaev Andrey


	if (typeof function_name == 'string'){
		return (typeof window[function_name] == 'function');
	} else{
		return (function_name instanceof Function);
	}
}

/*
 * Редактирование строки по ее  ID и создание новой.
 * Начальные значения для новой строки таблицы aliases беруться из ID
 * выделенной строки таблицы users
 */
function fnEdit(uid, pid) {

		if( ! uid.length )
			return false;

		tab = uid.split('-')[1];
		id  = uid.split('-')[2];

		if( pid )
		    pid = pid.split('-')[2];

		$.post('/'+ ctrl +'/showEditForm/'+id, {t:tab,init:pid}, function(response){
					// Какую форму вернул запрос
					if( $(response).find('.form-alert').length )
						$(response).modal( modInfo );
					else
						$(response).modal( modWin );
		});


}
/*
 * Удаление строки по ее ID
 */
function fnDelete(uid) {

		if( ! uid.length )
			return false;

		if( ! confirm('Уверены, что надо стирать?') )
			return false;

		tab = uid.split('-')[1];
		id  = uid.split('-')[2];

		params = {id:id,tab:tab};

		// хочу передать параметры для удаления
		if( function_exists('deleteWithParams') ){
			params = deleteWithParams(uid, tab, params);
		}

		$.post('/'+ ctrl +'/delEntry/' + id, params, function(response){
							// Какую форму вернул запрос ?
							jsonArr = /(\{"\w+":\d+\},?)+/;

							if( jsonArr.test(response) || !response ) {
								// Получаем объект из строки, если ответ содержит правильные данные
								objJSON = response ? $.parseJSON(response) : '';

								$('#tab-'+tab).dataTable().fnDeleteRow( $('#'+uid).get(0) );

								if( function_exists('clearChildTable') ) {
									clearChildTable(objJSON);
								}
							}
							else {
								if( $(response).find('.form-alert').length ) {
									$(response).modal( modInfo );
								}
							}
				});
}

/*
 *  Замена значений чекбоксов на картинку
 */
function drawCheckBox(nRow) {

		// смотрим на активность записи
		$('td', nRow).filter('.center').each(function(){

			if( $(this).text() == '0' )
				$(this).text('');

			if( $(this).text() == '1' )
				$(this).html('<span class="ui-icon ui-icon-check"></span>');
		});

		drawUnActiveRow(nRow);

}
/*
 *  Присваивание класса в зависимости от значения поля active
 */
function drawUnActiveRow( nRow ) {

		if( $('td:last', nRow).html() )
			$(nRow).removeClass('gradeUU');
		else
			$(nRow).addClass('gradeUU');
}

/*
 *  Находим значение ID выделенной строки в "родной" таблице
 */
function fnGetSelectedRowID( objTT ) {
		// Получаем сущность dataTable(), где была нажата кнопка
		tab = TableTools.fnGetInstance(objTT.s.dt.sTableId);
		//TabID = objTT.s.dt.sTableId;
		// Смотрим, первую выделеную строку, берем ее ID
		RowID = tab.fnGetSelected()[0];
		//RowID = $('#'+ TabID).find('tr.DTTT_selected').get(0);

		return (RowID) ? RowID.id : false;
}

/*
 *  Получаем ID  выделеной строки в связанной таблице
 */
function fnGetParentSelectedRowID(tabID) {

		RowID = $('.DTTT_selected', tabID)[0].id;
		return (RowID) ? RowID : 0;
}

/*
 *  При создании строки в аттрибут data заносим значение поля mailbox
 *  для последующей проверки на совпадение значений mailbox
 */
function addRowAttr( nRow, attrName, ind ) {
		data = $('td:eq('+ind+')', nRow).text();
		$(nRow).attr( attrName, data);
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
 *  Тест значений по шаблонам
 */
function fnTestByType(str, type){

	one_net	  =	"(\\d{1,3}\\.){3}\\d{1,3}(/\\d{1,2})?";
	net_tmpl  = "^\\s*" + one_net + "(\\s*,\\s*" + one_net + ")*\\s*$";
	mail_tmpl = "^[-_\\w\\.]+@(\\w+\\.){1,}\\w+$";
	word_tmpl = "^[\\w\\.]+$";
	transp_tmpl	= "^\\w+:\\[(\\d{1,3}\\.){3}\\d{1,3}\\]$";
	domain_tmpl	= "^(\\w+\\.)+\\w+$";

	switch (type ) {
		case 'mail':
			reg = new RegExp(mail_tmpl,'i')
			break
		case 'nets':
			reg = new RegExp(net_tmpl,'i')
			break
		case 'domain':
			reg = new RegExp(domain_tmpl,'i')
			break
		case 'transport':
			reg = new RegExp(transp_tmpl,'i')
			break
		default:
			if( str )
				return true
			else
				return false
	}

	if( reg.test(str) )
		return true;

	return false;
}

/*
 *  Выбираем данные из поля FieldNum выделенной в таблице TabID строки
 */
function fnGetFieldData(TabID, FieldNum){

	oTT = TableTools.fnGetInstance( TabID );
    aData = oTT.fnGetSelectedData();
    return aData[0][FieldNum];
}

/*
 *  Получаем ID выделенной строки в таблице с инд. TabID
 */
function fnGetRowID(TabID) {

	oTT = TableTools.fnGetInstance( TabID );
    Row = oTT.fnGetSelected()[0];
    return Row.id;
}

/*
 * Пересылка данных из формы групп для обработки
 */
function postGroupData() {
	// выделяю uid
		pid = $('.table-grp')[0].id.split('-')[1];

		if( $('#grp-right li').length ) {
		// Если у пользователя есть группы
			$('#grp-right li').each(function(){
				obj_id = this.id.split('-')[1];
				$('#usersform').append('<input type="hidden" name="obj_id[]" value="'+obj_id+'">');
			});
		}
		else
			$('#usersform').append('<input type="hidden" name="obj_id[]" value="">');

		$.post('/'+ ctrl +'/edGroup/'+pid, $('#usersform').serialize(), function(response){
									// Записывам в таблицу групп
									$('#tab-lists').dataTable().fnClearTable();
									$('#tab-lists').dataTable().fnAddData(response);

									if( function_exists('afterEditGroup') ) {
										afterEditGroup(response);
									}

									$.modal.close();

									}, 'json');
}

/*
 *  Сотворение пароля из num_var символов
 */
function mkpasswd(num_var) {

			if(!num_var)
				num_var = 7;

			passwd = '';
			str = "OPQRSrstuvwxTUVWXYZ0123456789abcdefjhigklmABCDEFJHIGKLMNnopqyz_=-";

			for(i=0;i<num_var;i++) {
				n = Math.floor(Math.random() * str.length);
				passwd += str[n];
			}
			return passwd;
}


/*
 *  Опции модального окна
 */
var modWin = {

		onShow: function(dialog){
			message: null;
			TabID: null;
			RowNode: null;
			closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",

			$(':text, select').addClass('ui-widget-content ui-corner-all');

			$('.autocomp').autocomplete({source: '/' + ctrl +'/searchMailbox/'});

			$('#sb').button({label: 'Send'});

			// Показе документа инициализирую функции
			$('#sb').click(function (e) {
				e.preventDefault();

				// С какими строками какой таблицы работаем
				modWin.TabID = $('form :hidden[name="tab"]').val();
				RowID 		 = $('form :hidden[name="id"]').val();

				// ВНИМАНИЕ! - как создается ID
				modWin.RowNode = $('#tab-'+modWin.TabID+'-'+RowID).get(0);

				// каждый модуль содержит свою функцию валидации
				validateFunctionName = 'modWin.validate_' + modWin.TabID + '()';
				modWin.message = '';
				modWin.message = eval(validateFunctionName);
				if (! modWin.message ) {
					// Работа с запросом
					$.ajax ({
							url: '/'+ ctrl +'/edit/',
							data: $('form').serialize(),
							type: 'post',
							dataType: 'json',
							success: function(str) {
										// при удачном стечении обстоятельств
										if( modWin.RowNode ) {
											 $('#tab-'+modWin.TabID).dataTable().fnUpdate( str, modWin.RowNode );
											 // Проверка на активность
											 drawUnActiveRow( modWin.RowNode );

											 // Какие-то действия еще
											 if( function_exists('afterUpdateData') ){
													afterUpdateData(str, modWin.RowNode);
											 }
										}
										else {
											 $('#tab-'+modWin.TabID).dataTable().fnAddData(str);
											 // Какие-то действия еще
											 if( function_exists('afterAddData') ){
													afterAddData(str);
											 }

										}

										$.modal.close();
									},
							error: function(response) {
										$('.ui-state-error').empty().append(response.responseText).fadeIn('fast');
									},
					});
				}
				else
					modWin.showError();

			})
		},

		showError: function () {
			$('#mesg').empty().append(modWin.message).closest('.ui-state-error').fadeIn('fast');
		},

};

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
					postGroupData();
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

/*
 * Опции для алертов
 */
var modInfo = {
		escClose: false,
		closeHTML: '',
		opacity: 0,
		onShow: function(dialog){
				$('#ok').button({label: 'OK'});
				$('#ok').click(function(){
									$.modal.close();
									});
		},
};

/*
 *  Опции TableTools
*/
var TTOpts = {
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
											fnDelete( RowID );
										}
						},
						{
							"sExtends":"text",
							"sButtonText": ".",
							"sButtonClass": "DTTT_button_vis DTTT_unvis",
							"fnClick": function( nButton, oConfig, oFlash ){
										// Прячем пароли
											if( function_exists('fnShowHide')) {
												fnShowHide(nButton);
											}
										}
						},
						{
							"sExtends":"print",
							"sButtonText": ".",
							"fnClick": function( nButton, oConfig ) {
											$(nButton).hasClass('DTTT_disabled') ? this.fnPrint( false, oConfig ) : this.fnPrint( true, oConfig );
										}
						},
						{
							"sExtends":    "text",
							"sButtonText": "ПОЛЬЗОВАТЕЛИ",
							"sButtonClass": 'DTTT_label  DTTT_disabled',
						}
					   ]
};



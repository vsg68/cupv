
var ctrl = window.location.pathname.split('/')[1];
ctrl = ( ctrl ) ? ctrl : 'users';



$(document).ready(function() {

		/* Add a click handler for the delete row */
		$('#delete').click( function() {

			var anSelected = fnGetSelected( oTable );

			if ( anSelected.length !== 0 )
				oTable.fnDeleteRow( anSelected[0] );
		});

		$('#'+ctrl+'.pagetab').addClass('pagetab-selected');
} );

/* Хидер с названием */
//~ function printTitle() {
//~
	//~ $("div.fg-toolbar:first").append('<div class="page-name">' + $('#'+ctrl).attr('title') + '</div>');
//~ }

/* Get the rows which are currently selected */
//~ function fnGetSelected( oTableLocal ) {
//~
			//~ return oTableLocal.$('tr.row_selected');
//~ }

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
function fnEdit(uid, initValues) {

		if( ! uid.length )
			return false;

		tab = uid.split('-')[0];
		id  = uid.split('-')[1];

		if( initValues )
		    initValues = initValues.split('-')[1];

		$.post('/'+ ctrl +'/showEditForm/' + id, {t:tab,init:initValues}, function(response){
					$(response).modal({
										onShow: modWin.show
										});
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

		tab = uid.split('-')[0];
		mbox = 	$('#'+uid).attr('data');

		$.post('/'+ ctrl +'/delEntry/', {mbox: mbox}, function(){
											$('#'+tab).dataTable().fnDeleteRow( $('#'+uid).get(0) );
											clearAliasTable (tab);
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
		// добавляем аттриут data для валидации
		printRowDataAttr(nRow,1);
}
/*
 *  Присваивание класса в зависимости от значения поля active
 */
function drawUnActiveRow(nRow) {

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
 *  При создании строки в аттрибут data заносим значение поля mailbox
 *  для последующей проверки на совпадение значений mailbox
 */
function printRowDataAttr(nRow,ind) {
		mbox = $('td:eq('+ind+')', nRow).text();
		$(nRow).attr('data', mbox);
}

/*
 *  Тест значений по шаблонам
 */
function fnTestByType(str, type) {

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
function fnGetFieldData(TabID, FieldNum) {

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

		show: function(dialog){
			message: null;
			TabID: null;
			RowNode: null;
			closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
			// Показе документа инициализирую функции
			$('#submit').click(function (e) {
				e.preventDefault();

				// С какими строками какой таблицы работаем
				modWin.TabID = $('form :hidden[name="tab"]').val();
				RowID 		 = $('form :hidden[name="id"]').val();
				modWin.RowNode = $('#'+modWin.TabID+'-'+RowID).get(0);

				// каждый модуль содержит свою функцию валидации
				validateFunctionName = 'modWin.validate_' + modWin.TabID + '()';

				if (eval(validateFunctionName)) {
					// Работа с запросом
					$.ajax ({
							url: '/'+ ctrl +'/edit/',
							data: $('form').serialize(),
							type: 'post',
							dataType: 'json',
							success: function(str) {
										// при удачном стечении обстоятельств
										//if( RowNode != undefined) {
										if( modWin.RowNode ) {
											 $('#'+modWin.TabID).dataTable().fnUpdate( str, modWin.RowNode );
											 // Проверка на активность
											 drawUnActiveRow( modWin.RowNode );
										}
										else {
												$('#'+modWin.TabID).dataTable().fnAddData(str);
										}
										$.modal.close();
									},
							error: function(response) {
										$('.ui-state-error').empty().append(response);
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
 *  Опции TableTools
 */
var TTOpts = {
			"sRowSelect": "single",
			"fnRowSelected": function(node){
								// Только для таблицы пользователей
								//tab = node[0].id.split('-')[0];
								if( function_exists('showAliasesTable') )
									showAliasesTable( node );

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
									//предотвращаем новое, если в основной таблице ничего не выбрано
									if( ! $(nButton).hasClass('DTTT_disabled') ) {
										fnEdit( this.s.dt.sTableId +'-0', usersRowID(this) );
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
							"sExtends":"print",
							"sButtonText": ".",
						}
					   ]
};


var ctrl = window.location.pathname.split('/')[1];
ctrl = ( ctrl ) ? ctrl : 'users';



$(document).ready(function() {

		/* Add a click handler for the delete row */
		$('#delete').click( function() {

			var anSelected = fnGetSelected( oTable );

			if ( anSelected.length !== 0 )
				oTable.fnDeleteRow( anSelected[0] );
		});


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

function fnEdit(uid, initValues) {

		if( ! uid.length )
			return false;

		tab = uid.split('-')[0];
		id  = uid.split('-')[1];

		$.post('/'+ ctrl +'/showEditForm/' + id, {t:tab; init:initValues}, function(response){
					$(response).modal({
										onShow: modWin.show
										});
		});

}

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

function drawUnActiveRow(nRow) {

		if( $('td:last', nRow).html() )
			$(nRow).removeClass('gradeU');
		else
			$(nRow).addClass('gradeU');
}

function fnGetSelectedRowID( objTT ) {
		// Получаем сущность dataTable(), где была нажата кнопка
		tab = TableTools.fnGetInstance(objTT.s.dt.sTableId);
		//TabID = objTT.s.dt.sTableId;
		// Смотрим, первую выделеную строку, берем ее ID
		RowID = tab.fnGetSelected()[0];
		//RowID = $('#'+ TabID).find('tr.DTTT_selected').get(0);

		return (RowID) ? RowID.id : false;
}

function printToRowDataAttr(nRow,ind) {
		mbox = $('td:eq('+ind+')', nRow).text();
		$(nRow).attr('data', mbox);
}

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
			if( ! str )
				return true
			else
				return false
	}

	if( reg.test(str) )
		return false;

	return true;

}

function fnGetFieldData(TabID, FieldNum) {

	oTT = TableTools.fnGetInstance( TabID );
    aData = oTT.fnGetSelectedData();
    x = aData[0][FieldNum];
    return x;
}

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
							"sButtonText": "Edit",
							"fnClick": function( nButton, oConfig, oFlash ){
									RowID = fnGetSelectedRowID(this);
									fnEdit( RowID , false);
								},
						},
						{
							"sExtends":"text",
							"sButtonText": "New",
							"fnClick": function( nButton, oConfig, oFlash ){
									//предотвращаем новое, если в основной таблице ничего не выбрано
									if( ! $(nButton).hasClass('DTTT_disabled') ) {
										fnEdit( this.s.dt.sTableId +'-0', initValues );
									}

								},
						},
						{
							"sExtends":"text",
							"sButtonText": "Del",
							"fnClick": function( nButton, oConfig, oFlash ){

									RowID = fnGetSelectedRowID(this);
									fnDelete( RowID, false );

								}
						},
					   ]
};

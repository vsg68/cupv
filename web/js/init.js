
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

function showAliasesTable(uid) {

		id = uid.split('-')[1];
		$.ajax({
				type: "GET",
				url: '/'+ ctrl +'/records/' + id,
				dataType: "json",
				success: function(response) {
										$('#aliases').dataTable().fnClearTable();
										$('#aliases').dataTable().fnAddData(response);
										},
				error: function() {
									$('#aliases').dataTable().fnClearTable();
									}
		});
}

function fnEdit(uid) {

		if( ! uid.length )
			return false;

		tab = uid.split('-')[0];
		id  = uid.split('-')[1];

		$.post('/'+ ctrl +'/showEditForm/' + id, {t:tab}, function(response){
					$(response).modal({
										onShow: modWin.show
										});
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
		printToRowDataAttr(nRow);
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
		return RowID.id;
}

function printToRowDataAttr(nRow) {
		mbox = $('td:eq(1)', nRow).text();
		$(nRow).attr('data', mbox);
}

var modWin = {

		show: function(dialog){
			message: null;
			TabID: null;
			RowNode: null;
			// Показе документа инициализирую функции
			$('#submit').click(function (e) {
				e.preventDefault();
				// С какими строками какой таблицы работаем
				modWin.TabID = $('form :hidden[name="tab"]').val();
				RowID 		 = $('form :hidden[name="id"]').val();
				modWin.RowNode = $('#'+modWin.TabID+'-'+RowID).get(0);

				if (modWin.validate()) {
					// Работа с запросом
					$.ajax ({
							url: '/'+ ctrl +'/edit/',
							data: $('form').serialize(),
							type: 'post',
							dataType: 'json',
							success: function(str) {
										// при удачном стечении обстоятельств
										//if( RowNode != undefined) {
										if( RowNode ) {
											 $('#'+modWin.TabID).dataTable().fnUpdate( str, modWin.RowNode );
											 // Проверка на активность
											 drawUnActiveRow( RowNode );
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
			$('#mesg p').append(modWin.message).parent().fadeIn('fast');
		},

};


var TTOpts = {
			"sRowSelect": "single",
			"fnRowSelected": function(node){
								// Только для таблицы пользователей
								//tab = node[0].id.split('-')[0];
								if( node[0].offsetParent.id == 'users')
									showAliasesTable(node[0].id);
							},
			"aButtons":[
						{
							"sExtends":"text",
							"sButtonText": "Edit",
							"fnClick": function( nButton, oConfig, oFlash ){
									RowID = fnGetSelectedRowID(this);
									fnEdit( RowID );
								},
						},
						{
							"sExtends":"text",
							"sButtonText": "New",
							"fnClick": function( nButton, oConfig, oFlash ){
									// Извращение с поиском принадлежащей таблицы
									fnEdit( this.s.dt.sTableId +'-0' );
								}
						},
						{
							"sExtends":"text",
							"sButtonText": "Del",
							"fnClick": function( nButton, oConfig, oFlash ){
									RowID = fnGetSelectedRowID(this);
									fnDelete( RowID );
								}
						},
					   ]
};

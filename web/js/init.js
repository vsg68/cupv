
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
function fnGetSelected( oTableLocal ) {

			return oTableLocal.$('tr.row_selected');
}

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
			return;

		tab = uid.split('-')[0];
		id  = uid.split('-')[1];

		$.post('/'+ ctrl +'/editform/' + id, {t:tab}, function(response){
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
}

function drawUnActiveRow(nRow) {

		if( $('td:last', nRow).html() )
			$(nRow).removeClass('gradeU');
		else
			$(nRow).addClass('gradeU');

}

var modWin = {
			show: function(dialog){
					// Показе документа инициализирую функции
					$('#submit').click(function (e) {
						e.preventDefault();
						// С какими строками какой таблицы работаем
						var TabID = $('form :hidden[name="tab"]').val();
						var RowID = $('form :hidden[name="id"]').val();
						var RowNode = $('#'+TabID+'-'+RowID).get(0);
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
												 $('#'+TabID).dataTable().fnUpdate( str, RowNode );
												 // Проверка на активность
												 drawUnActiveRow( RowNode );
											}
											else {
													$('#'+TabID).dataTable().fnAddData(str);
											}
											$.modal.close();
										},
								error: function(response) {
											$('.ui-state-error').empty().append(response);
										},
						});
					})
				}
		};


var selRowID;

var TTOpts = {
			"sRowSelect": "single",
			"fnRowSelected": function(node){
								// Только для таблицы пользователей
								if( $(node[0]).closest('table').attr('id') == 'users') {
									selRowID = node[0].id;
									showAliasesTable(node[0].id);
								}
							},
			"aButtons":[{
						"sExtends":"text",
						"sButtonText": "Edit",
						"fnClick": function( nButton, oConfig, oFlash ){
								fnEdit( selRowID );
							},
						},
						{
						"sExtends":"text",
						"sButtonText": "Del",
						},
						{
						"sExtends":"text",
						"sButtonText": "Add",
						"fnClick": function( nButton, oConfig, oFlash ){
								// Извращение с поиском принадлежащей таблицы
								fnEdit( this.s.dt.sTableId +'-0' );
							},
						}]
		};

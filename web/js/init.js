
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

		id = getDigitFromID(uid);
		$.ajax({
				type: "GET",
				url: '/'+ ctrl +'/records/' + id,
				success: function(response) {
										$('#aliases').dataTable().fnClearTable();
										$('#aliases').dataTable().fnAddData(response);
										},
				error: function() {
									$('#aliases').dataTable().fnClearTable();
									},
				dataType: "json"
				});

}

function fnEdit(uid) {

		if( ! uid.length )
			return;

		tab = $('#' + uid).closest('table').attr('id');
		id = getDigitFromID(uid);

		$.post('/'+ ctrl +'/editform/' + id, {t:tab}, function(response){
				$(response).modal({
									onShow: modw.show
									});
		});

}

function getDigitFromID(uid) {

	if( ! uid.length)
		return;

	return uid.replace(/[^-]+-/,'');
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

var modw = {
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
											if( RowID.length ) {
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


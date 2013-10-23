
var ctrl = window.location.pathname.split('/')[1];
ctrl = ( ctrl ) ? ctrl : 'users';



$(document).ready(function() {

		/* Add a click handler for the delete row */
		$('#delete').click( function() {

			var anSelected = fnGetSelected( oTable );

			if ( anSelected.length !== 0 )
				oTable.fnDeleteRow( anSelected[0] );
		});


		//~ $("#entry tbody tr").dblclick(function(e) {
//~
				//~ showNumber(this);
		//~ });

		$( "#submit" ).on( "submit", function( event ) {
		  event.preventDefault();
		  alert( $( this ).serialize() );
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

		id = uid.replace('id-','');
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
		id = uid.replace(/[^-]+-/,'');

		$.post('/'+ ctrl +'/editform/' + id, {t:tab}, function(response){
				$(response).modal({
									onShow: modw.show
									});
		});

}


var modw = {
		 show: function(dialog){
					$('#submit').click(function (e) {
						e.preventDefault();
						$.ajax ({
								url: '/'+ ctrl +'/edit/',
								data: $('form').serialize(),
								type: 'post',
								dataType: 'json',
								success: function(response) {
											// при удачном стечении обстоятельств
											// если такой строки нет - добавляем, если есть - меняем
											// fnUpdate | fnAddData
											// (node or index) TR element you want to update or the aoData index
											}
										},
								error: function(response) {
											$('#error_title').empty().append(response);
										},
						});
					})
				}
		};


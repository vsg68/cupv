
var ctrl = window.location.pathname.split('/')[1];
ctrl = ( ctrl ) ? ctrl : 'users';



$(document).ready(function() {


		// Выделение строки
		$("#entry tbody tr").click( function( e ) {
			if ( $(this).hasClass('row_selected') ) {

				$(this).removeClass('row_selected');
			}
			else {
				$('tr.row_selected').removeClass('row_selected');
				$(this).addClass('row_selected');
			}

			showRecordTable( $(this).closest('tr').attr('id') );
			//alert( $(this).closest('tr').attr('id') );
		});


		/* Add a click handler for the delete row */
		$('#delete').click( function() {

			var anSelected = fnGetSelected( oTable );

			if ( anSelected.length !== 0 )
				oTable.fnDeleteRow( anSelected[0] );
		});






} );

/* Хидер с названием */
function printTitle() {

	$("div.fg-toolbar:first").append('<div class="page-name">' + $('#'+ctrl).attr('title') + '</div>');
}

/* Get the rows which are currently selected */
function fnGetSelected( oTableLocal ) {

			return oTableLocal.$('tr.row_selected');
}

function showRecordTable(uid) {

	id = uid.replace('id-','');
	$.ajax({
			type: "GET",
			url: '/'+ ctrl +'/records/' + id,
			success: function(response) {
									$('#records').dataTable().fnClearTable();
									$('#records').dataTable().fnAddData(response);
									},
			error: function() {
								$('#records').dataTable().fnClearTable();
								},
			dataType: "json"
			});

}

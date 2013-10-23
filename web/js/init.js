
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

function showNumber(obj) {

			if( ! $(obj).closest('tr').attr('id').length )
				return;

			id = $(obj).closest('tr').attr('id').replace(/[^-]+-/,'');
			tab = $(obj).closest('table').attr('id');
			$.post('/'+ ctrl +'/editform/' + id, {t:tab}, function(response){
																				$(response).modal({

																							});
																				});
}

function fnEdit(id) {

			if( ! id.length )
				return;

			tab = $('#' + id).closest('table').attr('id');
			id = id.replace(/[^-]+-/,'');

			$.post('/'+ ctrl +'/editform/' + id, {t:tab}, function(response){
				$(response).modal(modwindow);
				});

}

function trySubmit() {

			var params =  $('#usersform').serialize();

			$.ajax(	'/'+ ctrl +'/edit/', params , function(response) {

								tmpl = /^\d+$/;

								//if( tmpl.test(response) )
									$('#error_title').append(response);
								//~ else
									//~ $('#ed').empty().html(response);
							});
			return false;
}

var modwindow ={
				show: function(dialog){
							$('#submit').click(function (e) {
								e.preventDefault();
								$.ajax ({
										url: '/'+ ctrl +'/edit/',
										data: $('form').serialize(),
										success: function(response) {
													tmpl = /^\d+$/;
													$('#error_title').append(response);
												},
										error: ''
								});
							})
						}
				};


$(function(){


	$("#submit_filter").hover(function(){$(this).css("background-position",'0 0')},function(){$(this).css("background-position",'0 -24px')});

	$( ".date_field").datepicker({ dateFormat: "yy-mm-dd" });

	$('#submit_filter').click(function(){

		$(this).children('img').removeClass('hidden');

		filter = $('#fltr').val();
		mail_tmpl = /^[\w\.]+@(\w+\.){1,}\w+$/;

		if( ! mail_tmpl.test(filter) && filter ) {
			 alert('Проверьте правильность адреса!');
			 $('#fltr').val('')
			 return false;
		}

		var params =  $('#filterform').serialize();

		$.post(	'/logs/show/', params , function(response) {
				$('#logplace').empty().html(response);
				$('#logplace pre:even').css('background-color','#FCFBF4');
				$('#submit_filter').children('img').addClass('hidden');
		});
	});

})

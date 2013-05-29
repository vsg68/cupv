
$(function(){
	// добавление строк	
	$('.else').live('click',function(){
		
		name 		= $(this).attr('id');
		open_tag 	= '<tr class="alias">';
		mail_cell 	= '<td>' + $('input[name="mailbox"]').val() + '</td>';
		input_cell 	= '<td><input type="text" name="' + name + '[]"></td>';
		chkbox_cell = '<td><input type="checkbox" name="' + name + '_chk[]" checked></td>';
		button_cell = '<td><img src="/cross.gif" class="delRow" border="0"></td>';
		close_tag 	= '</tr>';

		var tbl = $(this).parents('.atable').get(0);
		tr 	= name == 'anext' ? open_tag + mail_cell + input_cell + chkbox_cell + button_cell + close_tag :
								open_tag + input_cell + mail_cell + chkbox_cell + button_cell + close_tag;
		$(tbl).append( tr );
		return false;
	});
	
	$('a, .usr').click(function(){
		
			var href = $(this).attr('href');
			if( href === undefined )  return false; 	
			$('.active').removeClass('active');
			$(this).parent('.usr').addClass('active');

			$.ajax({
				url: href,
				type: 'post',
				success: function(response) {
					$('.view')
							.empty()
							.html(response);
					//$('.alias:even').css('background-color','#b8c8c8');										
				}
			});								
			return false;
		});

	$('#submit_view').live('submit', function(event){
			event.preventDefault();
			//var params;
			var params =  $('#usersform').serialize();
			$.post(	'/users/add/', params , function(response) {
												$('.view').empty().html(response);
												$('.alias:even').css('background-color','#eee');
												});
			return false;
	});

 })

function submit_form() {
	
	$('#usersform').submit( function(event){
			event.preventDefault();
			//var params;
			var params =  $('#usersform').serialize();
			$.post(	'/users/add/', params , function(response) {
												$('#ufields').empty().html(response);
												});
			return false;
	});
	}


$(function(){
	// добавление строк	
	$('.else').live('click',function(){
		
		name 		= $(this).attr('id');
		open_tag 	= '<tr class="alias">';
		mail_cell 	= '<td><input type="hidden" tag="1" name="' + name + '_st[]" value="1">' + $('input[name="mailbox"]').val() + '</td>';
		input_cell 	= '<td><input type="text" name="' + name + '[]" value=""></td>';
		chkbox_cell = '<td><input type="checkbox" name="chk" checked></td>';
		button_cell = '<td><img src="/cross.gif" class="delRow" border="0"></td>';
		close_tag 	= '</tr>';

		var tbl = $(this).parents('.atable').get(0);
		tr 	= name == 'anext' ? open_tag + mail_cell + input_cell + chkbox_cell + button_cell + close_tag :
								open_tag + input_cell + mail_cell + chkbox_cell + button_cell + close_tag;
		$(tbl).append( tr );
		return false;
	});

	// Удаление строк
	$('.delRow').live('click', function(){

		var tr 		   = $(this).closest('tr.alias');
		var input_hide = $(tr).find(':hidden:eq(0)');

		// Если есть таг - то поле создано вручную
		if( $(input_hide).attr('tag') ) 
			$(tr).remove();
		else {	
			$(input_hide).val('2');
			$(tr).addClass('hidden');
		}	
		
			
		
		
	});

	// подсветка выбранного пользователя
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

	// Submit
	$('#submit_view').live('submit', function(event){
			event.preventDefault();

			var params =  $('#usersform').serialize();
			$.post(	'/users/add/', params , function(response) {
												$('.view').empty().html(response);
												//$('.alias:even').css('background-color','#eee');
												});
			return false;
	});


	// Отключение алиаса (disable)
	$(':checkbox').live('click', function(){
		
		var input_text = $(this).closest('tr.alias').find(':text:eq(0)');
		var input_hide = $(this).closest('tr.alias').find(':hidden:eq(0)');

		if ( $(this).attr('checked') ) {
			$(input_text).removeAttr('disabled');
			$(input_hide).val('1');
		}	
		else {
			$(input_text).attr('disabled', 'true');
			$(input_hide).val('0');
		}	
	});

	// Проверка введенных значений
	
 })



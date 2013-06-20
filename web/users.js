
$(function(){

	// заполняем массив для фильтрации
	var mboxes = $('option','#usrs');

	// добавление строк
	$('.else').live('click',function(){

		name 		= $(this).attr('id');
		email		= ( $('input[name="mailbox"]').size() == 1) ? $('input[name="mailbox"]').val() : 'mailbox@domain.ru';
		open_tag 	= '<tr class="alias">';
		mail_cell 	= '<td>'+
					 '</td>';
		alias_cell 	= '<td><input class="autocomp" type="text" name="' + name + '[]" value="" placeholder="введите почтовый адрес"></td>';
		fwd_cell 	= '<td><input class="autocomp" type="text" name="' + name + '[]" value="" placeholder="введите почтовый адрес"></td>';
		chkbox_cell = '<td><input type="checkbox" name="chk" checked></td>';
		button_cell = '<td>'+
							'<input type="hidden" name="' + name + '_st[]" value="1">' +
							'<input type="hidden" name="' + name + '_id[]" value="0">' +
							'<button class="delRow  web">r</button>' +
					  '</td>';
		close_tag 	= '</tr>';

		var tbl = $(this).parents('.atable').get(0);
		tr 	= name == 'alias' ? open_tag + alias_cell + chkbox_cell + button_cell + close_tag :
								open_tag + fwd_cell + chkbox_cell + button_cell + close_tag;
		$(tbl).append( tr );

		$('.autocomp').autocomplete({ serviceUrl:'/users/searchdomain/',type:'post'});

		return false;
	});

	// добавление пути
	$('#path').live('click',function(){
		path = '<input class="formtext path" type="text" name="path" value="" />';
		// если уже есть одно поле, то остальные пропускаем
		if( $('.path').length == 0 ) {

			$('#path').parent().append(path);
			$('.path').focus();
			$('#path').text('3');
		}
		else {
			$('.path').remove();
			$('#path').text('4');
		}
		return false;
	});

	// Submit
	$('#submit_view').live('click', function(event){

			event.preventDefault();

			// Если новый пользователь
			var mailbox = $(':text[name="login"]').val() + '@' + $('.domain option:selected').val();

			if( mailbox != '@' ) {

				$('.key:contains("' + mailbox + '")').each( function(){

						if( $(this).text() == mailbox ) {
							alert('Почтовый ящик '+ mailbox +' уже существует!');
							$(':text[name="login"]').val('');
						}
				});
			}

			try_submit();
			return false;
	});


	// Блокирование поля алиаса (disable)
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



 })




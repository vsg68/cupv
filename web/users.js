
$(function(){

	// заполняем массив для фильтрации
	var mboxes = $('option','#usrs');

	// добавление строк
	$('.else').live('click',function(){

		name 		= $('div',this).attr('id');
		type_tag	= (name == 'fwd') ? '1' : '0';
//		email		= ( $('input[name="mailbox"]').size() == 1) ? $('input[name="mailbox"]').val() : 'mailbox@domain.ru';
		new_tr  	= '<tr class="alias">' +
					  '<td><input class="autocomp" type="text" name="fname[]" value="" placeholder="введите почтовый адрес"></td>'+
					  '<td><input type="checkbox" name="chk" checked></td>'	+
					  '<td>'												+
							'<input type="hidden" name="stat[]" value="1">' +
							'<input type="hidden" name="fid[]" value="0">'  +
							'<input type="hidden" name="ftype[]" value="'	+ type_tag +'">'+
							'<div class="delRow"></div>'					+
					  '</td></tr>';

		$(this).closest('.atable').append(new_tr);

		$('.autocomp').autocomplete({ serviceUrl:'/users/searchdomain/',type:'post'});

		return false;
	});

	// добавление пути
	$('#path').live('click',function(){
		path = '<input class="formtext path" type="text" name="path" value="" />';
		// если уже есть одно поле, то остальные пропускаем
		if( $('.path .formtext').length == 0 ) {

			$('.path').append(path);
			$('.path .formtext').focus();
			$(this).toggleClass('ptr-hover');
		}
		else {
			$('.path .formtext').remove();
			$(this).toggleClass('ptr-hover');
		}
		return false;
	});

	// Submit
	$('#submit_view').live('click', function(event){

			event.preventDefault();

			// Если новый пользователь
			var mailbox = $(':text[name="login"]').val() + '@' + $('.domain option:selected').val();

			if( mailbox != '@' ) { // при редактировании сюда не попадаем

				if( $('tr').filter('[sname="' + mailbox + '"]').length ) {
					alert('Почтовый ящик '+ mailbox +' уже существует!');
					$(':text[name="login"]').val('');
					return false;
				}
			}

			var arrVal 	= $(':text[name="mailbox"],:text[name="fname[]"]');

			$($(arrVal).not(':hidden').get().reverse()).each(function(){

					var name = $(this).val();

					insertName = $(arrVal)
									.filter( function(){ return $(this).val() == name} )
									.length;

					if( insertName !=1 ) {
						alert('Запись "'+ $(this).val() +'" уже существует');
						$(this).val('');
						return false;
					}
			});

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




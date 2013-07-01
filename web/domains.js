$(function(){

	// Выбор записи
	key = window.location.search.split('=')[1];
	$('#i-' + key).addClass('selected_key');

	// Запрос на редактирование
	$('tr','.domain_box').not('.noedit').click( function(){

									reg = /i-/;
									// Выбор записи
									$('.selected_key').removeClass('selected_key');
									$(this).addClass('selected_key');
									// Запрос
									name = $(this).attr('id').replace('i-','');
									$.get('/domains/single/',{'name':name, 'act':'1'},function(response){
											$('#ed').empty().append(response);

											// Если имеем дело с транспортом - блокируем алиасы
											if( $(':text[name="delivery_to"]').val() != undefined )
												$('#alias').attr('disabled','true');
											})
								});
	// Транспорт
	$('#path').live('click',function(){
		path = '<input class="formtext" type="text" name="delivery_to" value="" placeholder="proto:[ip_addr]" />';
		// если уже есть одно поле, то остальные пропускаем
		if( $('.path .formtext').size() == 0 ) {

			$('.path').append(path);
			$('.path .formtext').focus();
			$(this).text('6');
			// удаляем алиасы и блокируем добавление
			$('#alias').attr('disabled','true');
			$('.atable tr').not(':first').remove();
			// Прячем и очищаем адреса
			$('.listbox .web').text('4');
			$('.listbox .formtext').remove();

		}
		else {
			$('.path .formtext').remove();
			$(this).text('4');
			$('#alias').removeAttr('disabled');
		}
		return false;
	});

	// Адрес рассылки
	$('#all_email').live('click',function(){

		email = "<div class='formtext'>"+
				"<input type='text' name='all_email' value='' placeholder='mailbox_name'/>@domain.name"+
				"<input type='hidden' name='all_enable' value='1'></div>";

		if( $('.listbox .formtext').size() == 0 ) {

			$(this).text('6');
			$('.listbox').append(email);
			$('.listbox :text').focus();
			// Удаляем транспорт
			$('.path .formtext').remove();
			$('.path .web').text('4');
			// Разрешаем алиас
			$('#alias').removeAttr('disabled');
		}
		else {
			$(this).text('4');
			$('.listbox .formtext').remove();
		}
		return false;
	});

	// Блокирование поля email (disable)
	$(':checkbox[name="all_enable"]').live('click', function(){

		if ( $(this).attr('checked') )
			$(':text[name="all_email"]').removeAttr('disabled');
		else
			$(':text[name="all_email"]').attr('disabled','true');
	});

	// Добавление alias
	$('.else').live('click',function(){

		open_tag 	= '<tr class="alias">';
		alias_cell 	= '<td><input type="text" name="dom[]" value="" placeholder="название домена"></td>';
		chkbox_cell = '<td>'+
						'<input type="hidden" name="dom_st[]" value="1">' +
						'<input type="hidden" name="dom_id[]" value="0">' +
						'<input type="checkbox" name="chk" checked>' +
					  '</td>';
		button_cell = '<td><button class="delRow  web">r</button></td>';
		close_tag 	= '</tr>';

		var tbl = $(this).parents('.atable').get(0);

		$(tbl).append( open_tag + alias_cell + chkbox_cell + button_cell + close_tag );



		return false;
	});

	$('#submit_domain').live('click', function(event){

			event.preventDefault();

			var is_ok  = true;
			var domain_name = $('input[name="domain_name"]').val();

			// Заполняем массив значениями полей
			var domainArr = $('.key').map(function(){
											if( ! $(this).parent().is('.noedit') )
												return $(this).text()
											});
			var aliasArr  = $('.noedit .key').map(function(){ return $(this).text() });
			var valuesArr = $(':text[name="domain_name"],:text[name="dom[]"]').map(function(){ return $(this).val() });

			// проверка на вхождение в массив интересующих значений
			$(':text[name="domain_name"],:text[name="dom[]"]').each(function(){
					var domain  = $(this).val();
					is_bad_field = 0;
					lenDomain = $.grep( domainArr, function(val){ return val == domain; }).length;
					lenVal 	  = $.grep( valuesArr, function(val){ return val == domain; }).length;
					lenAlias  = $.grep( aliasArr, function(val){ return val == domain; }).length;

					// проверка на существование домена
					// для новых записей
					if( $(':text').is('[name="domain_name"]') ) {

						if( lenDomain != 0 || lenVal != 1 || lenAlias != 0  )
							is_bad_field = 1;
					}
					else {
						if(  lenDomain != 0 || lenVal != 1 || lenAlias > 1  )
							is_bad_field = 1;
					}

					if( is_bad_field ) {
							alert('Домен "'+ $(this).val() +'" уже существует');
							$(this).val('');
							return false;
					}
			});

			// проверка на правильное заполнение полей
			$(':text', '#usersform').each(function(){

				if( checkfield( $(this) ) ) {

					$(this).addClass('badentry');
					is_ok = false;
				}
				else
					$(this).removeClass('badentry');
			});

			// проверка окончена
			if( ! is_ok )	{

				$('.badentry:first').focus();
				return false;
			}

			// удаляем атрибут, чтобы поле ушло на сервер
			// иначе получим рассогласование длины массивов
			$(':disabled','.alias, .listbox').removeAttr('disabled');

			var params =  $('#usersform').serialize();

			$.post(	'/domains/add/', params , function(response) {

								dom_id = /^\d+$/;

								if( dom_id.test(response) )
									window.location = '/domains/view/?name=' + response;
								else
									$('#ed').empty().html(response);
							});
			return false;

		});

});

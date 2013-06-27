$(function(){

	// Запрос на редактирование
	$('tr','.domain_box').not('.noedit').click( function(){
									// Выбор записи
									$('.selected_key').removeClass('selected_key');
									$(this).addClass('selected_key');
									// Запрос
									name = $(this).attr('id');
									$.get('/domains/single/',{'name':name, 'act':'1'},function(response){
											$('#ed').empty().append(response);

											// Если имеем дело с транспортом - блокируем алиасы
											if( $(':text[name="delivery_to"]').val() != undefined )
												$('#alias').attr('disabled','true');
											})
								});
	// Транспорт ?
	$('#path').live('click',function(){
		path = '<input class="formtext path" type="text" name="delivery_to" value="" placeholder="proto:[ip_addr]" />';
		// если уже есть одно поле, то остальные пропускаем
		if( $('.path').length == 0 ) {

			$('#path').parent().append(path);
			$('.path').focus();
			$('#path').text('3');
			// удаляем алиасы и блокируем добавление
			$('#alias').attr('disabled','true');
			$('.atable tr').not(':first').remove();
		}
		else {
			$('.path').remove();
			$('#path').text('4');
			$('#alias').removeAttr('disabled');
		}
		return false;
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

			// проверка на существование домена
			$(':text[name="domain_name"], :text[name="dom[]"]').each(function(){;

				var domain = this;
				// фильтрую набор по известному содержимому
				if ( $('.key').contents().filter( function(){ return $(this).text()==$(domain).val()}).length ) {
						alert('Домен "'+ $(domain).val() +'" уже существует');
						$(domain).val('');
						return false;
				}

			});

			// проверка на пустые поля
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
			$('.alias :text[disabled]').removeAttr('disabled');

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

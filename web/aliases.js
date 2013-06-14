$(function(){

	//Фильтрайия пользователей по домену (alias)
	$('select','#domains_flt').change(function(){

		filter = $('option:selected', '#domains_flt').text();

		$('.hidden').removeClass('hidden');

		if( filter )
			$('td.key:not(:contains("@' + filter+ '"))', '#aliases_box')
					.parent()
					.addClass('hidden');

	});

	//Фильтрайия пользователей по ящикам
	$('#fltr').keyup(function(event){

		$('.hidden_filter').removeClass('hidden_filter');

		search_str = $(this).val();

		if( search_str )
			$('td.val:not(:contains("' + search_str + '"))', '#aliases_box')
			.parent()
			.addClass('hidden_filter');

	});

	// Запрос на редактирование
	$('tr','#aliases_box').click( function(){
		alias_name = $('.key', this).text();
		$.post('/aliases/view/',{'id':alias_name},function(response){ $('#ed').empty().append(response);})
	});

	// Добавление полей
	$('.else').live('click',function(){

		open_tag 	= '<tr class="alias">';
		alias_cell 	= '<td><input class="autocomp" type="text" name="fwd[]" value=""></td>';
		chkbox_cell = '<td>'+
						'<input type="hidden" name="fwd_st[]" value="1">' +
						'<input type="hidden" name="fwd_id[]" value="0">' +
						'<input type="checkbox" name="chk" checked>' +
					  '</td>';
		button_cell = '<td><button class="delRow  web">r</button></td>';
		close_tag 	= '</tr>';

		var tbl = $(this).parents('.atable').get(0);

		$(tbl).append( open_tag + alias_cell + chkbox_cell + button_cell + close_tag );

		$('.autocomp').autocomplete({ serviceUrl:'/users/searchdomain/',type:'post'});

		return false;
	});


	// Submit
	$('#submit_view').live('click', function(event){

			event.preventDefault();
			var is_ok = true;

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
			$.post(	'/aliases/add/', params , function(response) {

								$('#ed').empty().html(response);
								// Если добавили нового пользователя
								// - вставляем его адрес в список адресов
/*								var user_id = $(':hidden[name="user_id"]','#usersform').val();
								var mailbox = $(':hidden[name="mailbox"]','#usersform').val();

								// если имела место ошибка - <div id='log'>xxx</div>
								if( mailbox === undefined )	return false;

								is_exist = $('option:contains("'+mailbox+'")', '#usrs').length;

								if( ! is_exist ) {

									var str = '<option value=' + user_id + '>' + mailbox + '</option>';
									$('#usrs').append(str);
									$('option:last', '#usrs').attr('selected', true).focus();
								}

								// обновляем св-во активности
								is_active = $(':checkbox[name="active"]:checked', '#usersform').length;

								$('option:contains("'+mailbox+'")', '#usrs').toggleClass( 'disabled', is_active==0)
*/
							});
			return false;
	});




	// Hover по массиву алиасов
	$('tr','#aliases_box').hover( function(){
									$(this).addClass('hover_tr');
									},
								  function(){
									$(this).removeClass('hover_tr');
	});

	// Выбор записи
	$('tr','#aliases_box').click(function(){

		$('.selected_key').removeClass('selected_key');
		$(this).addClass('selected_key');
	});


})

$(function(){

	// Выбор записи
	//key = window.location.search.split('=')[1];
	//$('#i-' + key).addClass('selected_key');


	// Транспорт
	$('#path').live('click',function(){
		path = '<input class="formtext" type="text" name="delivery_to" value="" placeholder="proto:[ip_addr]" />';
		// если уже есть одно поле, то остальные пропускаем
		if( $('.path .formtext').size() == 0 ) {

			$('.path').append(path);
			$('.path .formtext').focus();
			$(this).html("&dArr;");
			// удаляем алиасы и блокируем добавление
			$('#alias').attr('disabled','true');
			$('.atable tr').not(':first').remove();
			// Прячем и очищаем адреса
			$('.listbox .web').html('&rArr;');
			$('.listbox .formtext').remove();

		}
		else {
			$('.path .formtext').remove();
			$(this).html('&rArr;');
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

			$(this).html('&dArr;');
			$('.listbox').append(email);
			$('.listbox :text').focus();
			// Удаляем транспорт
			$('.path .formtext').remove();
			$('.path .web').html('&rArr;');
			// Разрешаем алиас
			$('#alias').removeAttr('disabled');
		}
		else {
			$(this).html('&rArr;');
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
		button_cell = '<td><span class="delRow  web">&otimes;</span></td>';
		close_tag 	= '</tr>';

		var tbl = $(this).parents('.atable').get(0);

		$(tbl).append( open_tag + alias_cell + chkbox_cell + button_cell + close_tag );

		return false;
	});

	$('#submit_domain').live('click', function(event){

			event.preventDefault();

			var is_ok  = true;
			var domain_name = $('input[name="domain_name"]').val();
			var aliasObj = {};

			// Заполняем массив значениями полей
			var domainArr = $('tr:not(.noedit) .key').map(function(){ return $(this).text()	});
			var valuesArr = $(':text[name="domain_name"],:text[name="dom[]"]').map(function(){ return $(this).val() });
			// Готовим ассоциативный массив [alias]=>domain
			$('tr.noedit').each(function(){
							key = $(this).children('.key').text();
							val = $(this).children('.val').text();
							aliasObj[key] = val;
			});


			// проверка на вхождение в РЕВЕРСИВНЫЙ массив интересующих значений
			$($(':text[name="domain_name"],:text[name="dom[]"]').not(':hidden').get().reverse()).each(function(){

							var domain  = $(this).val();
							lenDomain = $.grep( domainArr, function(val){ return val == domain; }).length;
							lenVal 	  = $.grep( valuesArr, function(val){ return val == domain; }).length;

							if( lenDomain != 0 || lenVal != 1 || ( aliasObj[domain] != undefined && aliasObj[domain] != domain_name)  ) {
									alert('Домен "'+ $(this).val() +'" уже существует');
									$(this).val('');
									return false;
							}
			});

			//~ // проверка на правильное заполнение полей
			//~ $(':text', '#usersform').each(function(){
//~
				//~ if( checkfield( $(this) ) ) {
//~
					//~ $(this).addClass('badentry');
					//~ is_ok = false;
				//~ }
				//~ else
					//~ $(this).removeClass('badentry');
			//~ });
//~
			//~ // проверка окончена
			//~ if( ! is_ok )	{
//~
				//~ $('.badentry:first').focus();
				//~ return false;
			//~ }
//~
			//~ // удаляем атрибут, чтобы поле ушло на сервер
			//~ // иначе получим рассогласование длины массивов
			//~ $(':disabled','.alias, .listbox').removeAttr('disabled');
//~
			//~ var params =  $('#usersform').serialize();
//~
			//~ $.post(	'/domains/add/', params , function(response) {
//~
								//~ dom_id = /^\d+$/;
//~
								//~ if( dom_id.test(response) )
									//~ window.location = '/domains/view/?name=' + response;
								//~ else
									//~ $('#ed').empty().html(response);
							//~ });
			try_submit();
			return false;

		});

});

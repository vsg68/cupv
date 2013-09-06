$(function(){

	// Транспорт
	$('#path').live('click',function(){
		path = '<input class="formtext" type="text" name="delivery_to" value="" placeholder="proto:[ip_addr]" />';
		// если уже есть одно поле, то остальные пропускаем
		if( $('.path .formtext').size() == 0 ) {

			$('.path').append(path);
			$('.path .formtext').focus();
			$(this).addClass("ptr-hover");
			// удаляем алиасы и блокируем добавление
			$('#alias').attr('disabled','true');
			$('.atable tr').not(':first').remove();
			// Прячем и очищаем адреса
			$('.listbox .ptr').removeClass('ptr-hover');
			$('.listbox .formtext').remove();

		}
		else {
			$('.path .formtext').remove();
			$(this).removeClass('ptr-hover');
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

			$(this).addClass('ptr-hover');
			$('.listbox').append(email);
			$('.listbox :text').focus();
			// Удаляем транспорт
			$('.path .formtext').remove();
			$('.path .ptr').removeClass('ptr-hover');
			// Разрешаем алиас
			$('#alias').removeAttr('disabled');
		}
		else {
			$(this).removeClass('ptr-hover');
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

		new_tr	= '<tr class="alias">'+
					'<td><input type="text" name="fname[]" value="" placeholder="название домена"></td>'+
					'<td>'												+
						'<input type="hidden" name="stat[]" value="1">' +
						'<input type="hidden" name="fid[]" value="0">'	+
						'<input type="checkbox" name="chk" checked>'	+
					  '</td>'											+
					'<td><div class="delRow"></div></td>'				+
					'</tr>';

		$(this).closest('.atable').append(new_tr);


		return false;
	});

	$('#submit_domain').live('click', function(event){

			event.preventDefault();

			var name 	= $(':text[name="domain_name"]').val();
			var id	 	= $(':hidden[name="domain_id"]').val();


			existNameId = $('tr')
							.filter('[sid="' + id + '"]')
							.filter('[sname="' + name + '"]')
							.length;

			existName = $('tr')
							.filter('[sname="' + name + '"]')
							.length;

			if( ! existNameId && existName ) {
					alert('Запись "'+ name +'" уже существует');
					$(':text[name="domain_name"]').val('');
					return false;
			}

			var arrVal 	= $(':text[name="domain_name"],:text[name="fname[]"]');

			$($(arrVal).not(':hidden').get().reverse()).each(function(){

					var name = this.val();

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

});

$(function(){


	//Фильтрайия пользователей по ящикам
	$('#fltr').keyup(function(event){

		$('.hidden_filter').removeClass('hidden_filter');

		var search_str = $(this).val();

		if( search_str )

			$('td.val').filter(function(){
								return $(this).children('div:contains("' + search_str + '")').length == 0; })
				.closest('tr')
				.addClass('hidden_filter');

	});



	// Добавление полей
	$('.else').live('click',function(){

		new_tr 	= '<tr class="alias">' +
				  '<td><input class="autocomp" type="text" name="fname[]" value="" placeholder="введите почтовый адрес"></td>'+
				  '<td>'												+
						'<input type="hidden" name="stat[]" value="1">' +
						'<input type="hidden" name="fid[]" value="0">' 	+
						'<input type="checkbox" name="chk" checked>'	+
				  '</td>'												+
				  '<td><div class="delRow"></div></td>'					+
				  '</tr>';

		$(this).closest('.atable').append(new_tr);

		$('.autocomp').autocomplete({ serviceUrl:'/users/searchdomain/',type:'post'});

		return false;
	});


	// Submit
	$('#submit_view').live('click', function(event){

			event.preventDefault();

			// Проверка на существование такого алиаса

			var name 	= $(':text[name="alias_name"]').val();
			var id	 	= $(':hidden[name="alias_uid"]').val();

			existNameId = $('tr')
								.filter('[sid="' + id + '"]')
								.filter('[sname="' + name + '"]')
								.length;

			existName = $('tr')
								.filter('[sname="' + name + '"]')
								.length;

			if( ! existNameId && existName ) {
					alert('Запись '+ name +' уже существует!');
					$(':text[name="alias_name"]').val('');
					return false;
			}

			var arrVal 	= $(':text[name="alias_name"],:text[name="fname[]"]');

			$($(arrVal).not(':hidden').get().reverse()).each(function(){

				var name = $(this).val();

				insertName = $(':text[name="fname[]"]')
								.filter( function(){ return $(this).val() == name} )
								.length;

				if( insertName != 1) {
					alert('Запись '+ name +' уже существует!');
					$(this).val('');
					return false;
				}
			});

			try_submit();
			return false;
	});









})

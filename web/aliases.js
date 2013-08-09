$(function(){


	//Фильтрайия пользователей по ящикам
	$('#fltr').keyup(function(event){

		$('.hidden_filter').removeClass('hidden_filter');

		search_str = $(this).val();

		if( search_str )
			$('td.val:not(:contains("' + search_str + '"))', '#aliases_box')
			.parent()
			.addClass('hidden_filter');

	});



	// Добавление полей
	$('.else').live('click',function(){

		open_tag 	= '<tr class="alias">';
		alias_cell 	= '<td><input class="autocomp" type="text" name="fname[]" value="" placeholder="введите почтовый адрес"></td>';
		chkbox_cell = '<td>'+
						'<input type="hidden" name="stat[]" value="1">' +
						'<input type="hidden" name="fid[]" value="0">' +
						'<input type="checkbox" name="chk" checked>' +
					  '</td>';
		button_cell = '<td><div class="delRow"></div></td>';
		close_tag 	= '</tr>';

		var tbl = $(this).parents('.atable').get(0);

		$(tbl).append( open_tag + alias_cell + chkbox_cell + button_cell + close_tag );

		$('.autocomp').autocomplete({ serviceUrl:'/users/searchdomain/',type:'post'});

		return false;
	});


	// Submit
	$('#submit_view').live('click', function(event){

			event.preventDefault();

			// Проверка на существование такого алиаса
			var alias = $(':text[name="newalias"]').val();

			$('.key:contains("' + alias +'")').each( function(){
					if( $(this).text() == alias ) {
						alert('Алиас '+ alias +' уже существует!');
						$(':text[name="newalias"]').val('');
					}
			});

			try_submit();
			return false;
	});

	//~ //Новый alias
	//~ $('#new').click(function(){
		//~ $.get('/aliases/new/',function(response) { $('#ed').empty().html(response); });
	//~ });







})

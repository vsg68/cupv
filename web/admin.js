$(function(){

	// Выбор записи
	//key = window.location.search.split('=')[1];

	//$('tr','.domain_box').filter('[sid="' +  key + '"]').addClass('selected_key');

	// Запрос на редактирование
	//~ $('tr','.domain_box').not('.noedit').click(function(){
//~
							//~ // Выбор записи
							//~ $('.selected_key').removeClass('selected_key');
							//~ $(this).addClass('selected_key');
							//~ // Запрос
							//~ name = $(this).attr('sid');
//~
							//~ $.get('/admin/single/',{'name':name, 'act':'1'},function(response){
									//~ $('#ed').empty().append(response);
//~
							//~ });
	//~ });
	// Добавление alias
	$('.else').live('click',function(event){
		event.preventDefault();

		open_tag 	= '<tr class="alias">';
		name_cell	= '<td>'+ '<input type="text" name="ctrl_name[]" value="">' +'</td>';
		alias_cell 	= '<td>'+ ctrl_cell +'</td>';
		chkbox_cell = '<td>'+
						'<input type="hidden" name="stat[]" value="1">' +
						'<input type="hidden" name="ctrl_id[]" value="0">' +
						'<input type="checkbox" name="chk" checked>' +
					  '</td>';
		button_cell = '<td><span class="delRow  web">&otimes;</span></td>';
		close_tag 	= '</tr>';

		$(this).parents('.atable')
				.append( open_tag + name_cell + alias_cell + chkbox_cell + button_cell + close_tag );

		return false;
	});

	$('#submit_ctrl').live('click', function(event){

			var is_ok  = true;
			var section_name = $('input[name="section_name"]').val();
			var section_id = $(':hidden[name="section_id"]').val();

			existNameId = $('tr').not('.noedit')
							.filter('[sid="' + section_id + '"]')
							.filter('[sname="' + section_name + '"]')
							.length;
			existName = $('tr').not('.noedit')
							.filter('[sname="' + section_name + '"]')
							.length;

			if( ! existNameId && existName )
				$('input[name="section_name"]').val('');


			// Массив названий страниц(контролов) в текущим разделе(section).
			var optArr = $('.alias option:selected').map(function(){ return $(this).val() });

			// работаем с реверсивным массивом
			$($('.alias option:selected').get().reverse()).each(function(){

				control_name  = $(this).text();

				// if exist контрол с данным id
				existNameId = $('tr').filter('.noedit')
									.filter('[sid="' + section_id + '"]')
									.filter('[cname="' + control_name + '"]')
									.length;
				// if exist контрол
				existName   = $('tr').filter('.noedit')
									.filter('[cname="' + control_name + '"]')
									.length;

				// проверка на дубли во введенном масиве контролов
				optVal 	  = $.grep( optArr, function(val){ return val == control_name; }).length;

				if( (optVal != 1) || ( ! existNameId && existName ) ) {

					alert('Страница с таким контроллером уже существует.');
					$(this).siblings('.zero').attr('selected','true');
					$(this).closest('.alias').find(':text').val('');

					return false;
				}
			});

			try_submit();
			return false;

		});

});

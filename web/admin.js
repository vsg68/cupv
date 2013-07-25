$(function(){

	// Выбор записи
	key = window.location.search.split('=')[1];
	$('#i-' + key).addClass('selected_key');

	// Запрос на редактирование
	$('tr','.domain_box').not('.noedit').click(function(){

							// Выбор записи
							$('.selected_key').removeClass('selected_key');
							$(this).addClass('selected_key');
							// Запрос
							name = $(this).attr('id').replace('i-','');

							$.get('/admin/single/',{'name':name, 'act':'1'},function(response){
									$('#ed').empty().append(response);

							});
	});
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

			event.preventDefault();

			var is_ok  = true;
			var section_name = $('input[name="section_name"]').val();
			var section_id = $(':hidden[name="section_id"]').val();

			// Заполняем массив значениями полей - названия разделов
			var sectionArr = $('tr:not(.noedit) .key').map(function(){ return $(this).text() });

			// Заполняем массив значениями полей - контроллеры
			var ctrlArr = $('tr.noedit .key').map(function(){ return $(this).text() });

			// Массив названий страниц(контролов) в текущим разделе(section).
			var optArr = $('.alias option:selected').map(function(){ return $(this).val() });

			// количество вхождений названия раздела в имеющиеся
			lenSect = $.grep( sectionArr, function(val){ return val == section_name; }).length;

			// Если НЕ существ. значения для такого id - пишем пустое
			sectVal = $('#i-' + section_id + ' .key').text();
			if( sectVal == undefined ) 	sectVal = '';

			if( (lenSect == 1 && sectVal != section_name ) )
				$('input[name="section_name"]').val('');


			// работаем с реверсивным массивом
			$($('.alias option:selected').get().reverse()).each(function(){

				control_name  = $(this).text();
				control_id	  = $(this).closest('.alias').find(':hidden[name="ctrl_id[]"]').val();

				// ищем название имеющегося контрола по имеющемуся id (если такой есть)
				ctrlVal = $('#n-' + control_id + ' .key').text();
				if( ctrlVal == undefined ) 	ctrlVal = '';

				// отбираем из всех имеющихся страниц(control) те, которые совпадают с вновь введенными
				lenVal 	  = $.grep( ctrlArr, function(val){ return val == control_name; }).length;

				// проверка на дубли во введенном масиве контролов
				optVal 	  = $.grep( optArr, function(val){ return val == control_name; }).length;

				if( (optVal != 1) || (lenVal == 1) && ( ctrlVal != control_name ) ) {

					$(this).siblings('.zero').attr('selected','true');
					$(this).removeAttr('selected');
					return false;
				}
			});

			try_submit();
			return false;
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
			//~ $.post(	'/admin/add/', params , function(response) {
//~
							//~ dom_id = /^\d+$/;							if( dom_id.test(response) )
								//~ window.location = '/admin/view/?name=' + response;
							//~ else
								//~ $('#ed').empty().html(response);
							//~ });
			//~ return false;

		});

});

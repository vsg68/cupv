$(function(){

	// Выбор записи
	key = window.location.search.split('=')[1];
	$('#i-' + key).addClass('selected_key');

	// Запрос на редактирование
	$('tr','.domain_box').not('.noedit').click(function(){

							reg = /i-/;
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
						'<input type="hidden" name="st[]" value="1">' +
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
			var domain_name = $('input[name="domain_name"]').val();
			var aliasObj = {};
/*
			// Заполняем массив значениями полей
			var domainArr = $('tr:not(.noedit) .key').map(function(){ return $(this).text()	});
			var valuesArr = $(':text[name="domain_name"],:text[name="dom[]"]').map(function(){ return $(this).val() });
			// Готовим ассоциативный массив [alias]=>domain
			$('tr.noedit').each(function(){
							key = $(this).children('.key').text();
							val = $(this).children('.val').text();
							aliasObj[key] = val;
			});


			// проверка на вхождение в массив интересующих значений
			$(':text[name="domain_name"],:text[name="dom[]"]').not(':hidden').each(function(){

							var domain  = $(this).val();
							lenDomain = $.grep( domainArr, function(val){ return val == domain; }).length;
							lenVal 	  = $.grep( valuesArr, function(val){ return val == domain; }).length;

							if( lenDomain != 0 || lenVal != 1 || ( aliasObj[domain] != undefined && aliasObj[domain] != domain_name)  ) {
									alert('Домен "'+ $(this).val() +'" уже существует');
									$(this).val('');
									return false;
							}
			});
*/
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

			$.post(	'/admin/add/', params , function(response) {

							dom_id = /^\d+$/;

							if( dom_id.test(response) )
								window.location = '/admin/view/?name=' + response;
							else
								$('#ed').empty().html(response);
							});
			return false;

		});

});

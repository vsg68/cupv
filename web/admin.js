$(function(){

	// Добавление alias
	$('.else').live('click',function(event){
					event.preventDefault();
					// Смотрим последний порядковый номер
					num	= $(this).closest('table').find(':hidden[name="num[]"]:last').val() - 0;

					num = isNaN(num) ? 0 : num+1;


					new_tr 	= '<tr class="alias">'											+
								'<td>'														+
									'<div class="up">&#9650;</div>'							+
									'<input type="text" name="fname[]" value="">' 			+
									'</td>'													+
									'<td>'+ ctrl_cell +'</td>'								+
									'<td>'													+
									'<input type="hidden" name="stat[]" value="1">' 		+
									'<input type="hidden" name="fid[]" value="0">' 			+
									'<input type="hidden" name="num[]" value="'+ num +'">'	+
									'<input type="checkbox" name="chk" checked>'			+
								  '</td>'													+
								  '<td><div class="delRow"></div></td>'						+
								  '</tr>';

					$(this).closest('.atable').append(new_tr);

					return false;
				});

	$('.up').live('click', function(){

					var tr_now = $(this).closest('tr');
					var tr_arr = $(this).closest('table').find('tr');

					// Если дошли до хидера - дальше не идем
					if( tr_arr.index($(this).closest('tr').prev('tr')) ) {
						// получение значений
						oldval = tr_now.find(':hidden[name="num[]"]').val();
						newval = tr_now.prev('tr').find(':hidden[name="num[]"]').val();
						// Смена значений
						tr_now.prev('tr').find(':hidden[name="num[]"]').val(oldval);
						tr_now.find(':hidden[name="num[]"]').val(newval);
						// Вствка
						tr_now.prev('tr').before(tr_now);
					}
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
			var optArr = $('tr.alias')
								.not(':hidden')
								.find('option:selected')
								.map(function(){ return $(this).val() });

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

				if( (optVal != 1 && optArr.length ) || ( ! existNameId && existName ) ) {

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

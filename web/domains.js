$(function(){

	// Запрос на редактирование
	$('tr','.domain_box').click( function(){
									// Выбор записи
									$('.selected_key').removeClass('selected_key');
									$(this).addClass('selected_key');
									// Запрос
									name = $(this).attr('id');
									$.get('/domains/single/',{'name':name, 'act':'1'},function(response){ $('#ed').empty().append(response);})
								});
	// Транспорт ?
	$('#path').live('click',function(){
		path = '<input class="formtext path" type="text" name="delivery_to" value="" placeholder="proto:[ip_addr]" />';
		// если уже есть одно поле, то остальные пропускаем
		if( $('.path').length == 0 ) {

			$('#path').parent().append(path);
			$('.path').focus();
			$('#path').text('3');
		}
		else {
			$('.path').remove();
			$('#path').text('4');
		}
		return false;
	});

	 $('#submit_domain').live('click', function(event){

			event.preventDefault();

			var domain = $(':text[name="domain_name"]').val();
			var virtual	= $(':text[name="delivery_to"]').val();
			//var scope	= ( virtual === undefined ) ? '#local' : '#transport';
			var is_ok  = true;

			// проверка только для нового домена
			if( domain != undefined ) {
				// проверка, что такой домен есть
				//$('.key:contains("' + domain + '")', scope).each( function(){
				$('.key:contains("' + domain + '")').each( function(){

						if( $(this).text() == domain ) {
								alert('Домен '+ domain +' уже существует');
								$(':text[name="domain_name"]').val('');
						}
				});
			}

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

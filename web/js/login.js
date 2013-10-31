$(function(){


	$('#logout').click(function(){ window.location = '/login/logout';});

	$('.theme').click(function(){ window.location = $(this).find('a').attr('href') });

	$(':text, :password, select').addClass('ui-widget-content ui-corner-all');

	$('#sb').button({ label: 'Вход'});

	$('#sb').click(function (e) {
		e.preventDefault();

		if (validate) {
			// Работа с запросом
			$.post ( '/login/login/', $('form').serialize(), function(response) {
								// при удачном стечении обстоятельств - переадресация
								if( response )
									window.location='/';

								$('#mesg').empty().append('Такое сочетание логина и пароля в системе отсутствует.');
								$('.ui-state-error').show();

							}
			);
		}
	});

});

function validate () {

			username	= $('form :text[name="username"]').val();
			password	= $('form :text[name="password"]').val();

			if ( ! (username && password) ) {
				 $('.ui-state-error').empty().append('Хотя бы одно поле должно быть заполнено. ');
				 return false;
			}

			return true;
}

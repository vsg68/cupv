$(function(){


	$('#logout').click(function(){ window.location = '/login/logout';});

	$('.theme').hover(
					function(){	$('.logomin, .name a', this).addClass('hover')},
					function(){	$('.logomin, .name a', this).removeClass('hover')}
	)

	$('.theme').click(function(){ window.location = $(this).find('a').attr('href') });

	$(':text, :password, select').addClass('ui-widget-content ui-corner-all');

	$('#sb').button({ label: 'Вход'});

	$('#sb').click(function (e) {
		e.preventDefault();

		if (validate) {
			// Работа с запросом
			$.ajax ({
					url: '/login/login/',
					data: $('form').serialize(),
					type: 'post',
					success: function(str) {
								// при удачном стечении обстоятельств
								if( str ) {
									$('.login').addClass('hidden');
									$('.sections').removeClass('hidden');
								}
								else
									$('.ui-state-error').empty().append('Такое сочетание логина и пароля в системе отсутствует.');
							}
			});
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

$(function(){


	$('#logout').click(function(){ window.location = '/login/logout';});

	$('#usersform').bind('keyup', function(e) {
			var code = e.keyCode || e.which;
			if(code == 13) { //Enter keycode
			   $('#sb').click();
			}
	});

	$('.theme').click(function(){ window.location = $(this).find('a').attr('href') });

	$(':text, :password, select').addClass('ui-widget-content ui-corner-all');

	$('#sb').button({ label: '<a href=#>Вход</a>'});

	$('#sb').click(function (e) {
		e.preventDefault();

		if (validate) {
			// Работа с запросом
			$.post ( '/login/login/', $('form').serialize(), function(response) {
								// при удачном стечении обстоятельств - переадресация
								if( response ) {
									window.location='/';
								}
								else {
									$('#mesg').empty().append('Такое сочетание логина и пароля в системе отсутствует.');
									$('.ui-state-error').show();
								}

							}
			);
		}
	});

	$('.img-pad').each(function(){
			$(this).css('background-url', '/image/' + this.id + '.png');
	});

	$('.img-pad').hover(function(){
						$(this).css('background-position', '0 -128px');
					//	$(this).parent().find('a').css('color','#3f75ff');
					},
					function(){
						$(this).css('background-position', '0 0');
					//	$(this).parent().find('a').css('color','#333');
					})

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

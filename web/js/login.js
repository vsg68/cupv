$(function(){


	$('#logout').click(function(){ window.location = '/login/logout';});

	$('.theme').hover(
					function(){	$('.logomin, .name a', this).addClass('hover')},
					function(){	$('.logomin, .name a', this).removeClass('hover')}
	)

	$('.theme').click(function(){ window.location = $(this).find('a').attr('href') });

	$(':text, select').addClass('ui-widget-content ui-corner-all');

	$('#sb').button({ label: 'Вход'});

	$('#sb').click(function (e) {
		e.preventDefault();

		if (eval(validateFunctionName)) {
			// Работа с запросом
			$.ajax ({
					url: '/login/login/',
					data: $('form').serialize(),
					type: 'post',
					success: function(str) {
								// при удачном стечении обстоятельств
								//if( RowNode != undefined) {
								$('.login').addClass('hidden');
								$('.sections').removeClass('hidden');
							},
					error: function(response) {
								$('.ui-state-error').empty().append(response);
							},
			});
		}
		else
			modWin.showError();
	});
})

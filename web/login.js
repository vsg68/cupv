$(function(){
	$('#logout').click(function(){ window.location = '/login/logout';});

	$('.theme').hover(
					function(){	$('.logomin, .name a', this).addClass('hover')},
					function(){	$('.logomin, .name a', this).removeClass('hover')}
	)

	$('.theme').click(function(){ window.location = $(this).find('a').attr('href') });
})

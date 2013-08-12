$(function(){
	$('#logout').click(function(){ window.location = '/login/logout';});


	$('.theme').click(function(){ window.location = $(this).find('a').attr('href') });
})

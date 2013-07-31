$(function(){
	$('#logout').click(function(){ window.location = '/login/logout';});

	$('.theme').hover(
					function(){	$('.logo, .name a', this).addClass('hover')},
					function(){	$('.logo, .name a', this).removeClass('hover')}
			)
})

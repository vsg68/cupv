$(function(){

	$('#onoff').click(function(){
		$(this).children('img').toggleClass('hidden');
	});

	$("#onoff").hover(function(){$(this).css("background-position",'0 0')},function(){$(this).css("background-position",'0 -24px')});

	$( ".measure").datepicker({ dateFormat: "dd.mm.yy" });

})

/*
		Для установки начальных значений
*/
$(function(){

// onmouseover
	$('#new').hover( function(){ $(this).addClass('hover_new')}, function(){ $(this).removeClass('hover_new')});

	$(".mainmenu ul li").hover(function(){ $(this).addClass("hover_item");}, function(){$(this).removeClass("hover_item")});
	$(".mainmenu ul li a").click(function(){

					$('.selected').removeClass('selected');
					$(this).parent().addClass("selected");
				});

	ctrl = window.location.pathname.split('/')[1];
	ctrl = ( ctrl ) ? ctrl : 'users';
	$('#' + ctrl).addClass('selected');

})

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

	//~ net_tmpl  = "^((\\d+\.){1,3}\\d{1,3}(/\\d{1,2})?,?\\s*)+$";
	//~ reg = new RegExp(net_tmpl,'i');
//~ 
	//~ //value = '192.168.0.1';
	//~ value = '192.168.0.0/27';
	//~ value = '192.168.0.1/12, 127.0.0.0/32';
	//~ value = '192.168.0.1/27, 127.1/11';
	//~ if( reg.test(value) )
		//~ alert('yes');
	//~ else
		//~ alert('no');

})

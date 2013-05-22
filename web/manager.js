
$(function(){
	// $('#portion').dropdowntreeview({'form':'portion','n':'0'},{'oneclick':'true','height':'600px'},{'collapsed':true});
	
	$('#next').click(function(){
				$('#next').before("<div><strong>some: </strong><input class='portion' type='text' value='test' >&nbsp;&nbsp;<input type='checkbox' name='ch1_portion' checked></div> ");
	})
	$('a, .usr').click(function(){
						$('.active').removeClass('active');
						$(this).parent('.usr').addClass('active');
						sometext = $(this).text();
						$('.ufields').replaceWith('<div class="ufields"><strong>'+ sometext +'</strong></div>');
						return false;
					})
 })

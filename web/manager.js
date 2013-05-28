
$(function(){
	// $('#portion').dropdowntreeview({'form':'portion','n':'0'},{'oneclick':'true','height':'600px'},{'collapsed':true});
	
	$('button').live('click',function(){

				alias = $(this).prev().get(0).outerHTML;
				$(this).before( alias );
				return false;
	});
	
	$('a, .usr').click(function(){
						$('.active').removeClass('active');
						$(this).parent('.usr').addClass('active');
						var href = $(this).attr('href');

						$.ajax({
							url: href,
							type: 'post',
							success: function(response) {
								$('.view')
										.empty()
										.html(response);
								$('.alias:even').css('background-color','#b8c8c8');										
							}
						});								
						return false;
					});

	$('#usersform').live('submit', function(event){
			event.preventDefault();
			//var params;
			var params =  $('#usersform').serialize();
			$.post(	'/users/add/', params , function(response) {
												$('.view').empty().html(response);
												$('.alias:even').css('background-color','#eee');
												});
			return false;
	});

 })

function submit_form() {
	
	$('#usersform').submit( function(event){
			event.preventDefault();
			//var params;
			var params =  $('#usersform').serialize();
			$.post(	'/users/add/', params , function(response) {
												$('#ufields').empty().html(response);
												});
			return false;
	});
	}

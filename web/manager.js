
$(function(){
	// $('#portion').dropdowntreeview({'form':'portion','n':'0'},{'oneclick':'true','height':'600px'},{'collapsed':true});
	
	$('button').live('click',function(){

		mailbox = $('input[name="mailbox"]').val();
		inputname = $(this).attr('name') + '[]';
		chkboxname =  $(this).attr('name') + '_chk[]';

		tr = '<tr class="alias"><td>'+mailbox+'</td><td><input type="text" name="'+inputname+'" value=""></td><td><input type="checkbox" name="'+chkboxname+'" value="" checked></td></tr>';
		var tbl = $(this).siblings('table').get(0);
		$(tbl).append(tr);
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

	$('#submit_view').live('submit', function(event){
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

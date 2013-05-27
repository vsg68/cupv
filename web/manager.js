
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
							//~data: {
								//~ q: 'assets/snippets/ajax/getContent.php',
								//~ mainemail: 
							//~ },
							success: function(response) {
								$('.view')
										.empty()
										.html(response);
//								$('a[rel='+id+']').addClass('selected-report');
								return false;
							}
						});								
						return false;
					});
	

 })

function submit_form() {
    // disable the submit button
    //$('#submit_view').attr("disabled", true);  

	params = $('form[name="usersform"]').serialize();

	//~ $('#ufields')
		//~ .empty()
		//~ .load('/users/add/', params, $('#log').text('Готово'));	    
		$.ajax({
			url: '/users/add/',
			type: 'post',
			data: params,
			success: function(response) {
				$('#ufields')
						.empty()
						.html(response);

				return false;
			}
		});								
		return false;
 }

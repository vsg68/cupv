
$(function(){

	$('#passwd').live('click',function(){

			if( $(this).parent().siblings().is(':text') ) {
				$(this).parent().siblings(':text').remove();
				$(this).html('&rArr;');
			}
			else {
				$(this).parent().after("<input  class='formtext' type='text' name='auth_passwd' value='"+ mkpasswd() +"'  />");
				$(this).html('&dArr;');
			}
	});



	$('#submit_auth').live('click', function(event){

			// проверка на совпадающие имена
			var auth_name = $(':text[name="auth_login"]').val();
			var auth_id = $(':hidden[name="auth_id"]').val();

			existNameId = $('tr')
							.filter('[sid="' + auth_id + '"]')
							.filter('[sname="' + auth_name + '"]')
							.length;

			existName = $('tr')
							.filter('[sname="' + auth_name + '"]')
							.length;

			if( ! existNameId && existName )
				$('input[name="auth_login"]').val('');

			try_submit();
			return false;

		});

	$('.mkpwd').live('click', function(){

			$(this).siblings(':text').val(mkpasswd());
		})

});


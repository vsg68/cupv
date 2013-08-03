
$(function(){

	$('#submit_roles').live('click', function(event){

			// проверка на совпадающие имена
			var role_name = $(':text[name="role_name"]').val();
			var role_id = $(':hidden[name="role_id"]').val();

			existNameId = $('tr')
							.filter('[sid="' + role_id + '"]')
							.filter('[sname="' + role_name + '"]')
							.length;

			existName = $('tr')
							.filter('[sname="' + role_name + '"]')
							.length;

			if( ! existNameId && existName )
				$('input[name="section_name"]').val('');

			try_submit();
			return false;

		});


});

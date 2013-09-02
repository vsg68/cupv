
$(function(){

	$('.else').live('click',function(){

		new_tr	= '<tr class="alias">'+
					'<td><input type="text" name="fname[]" value="" placeholder="host name"></td>'+
					'<td>'												+
						'<select name="ftype[]">' 						+
							'<option value="NS">NS'						+
							'<option value="A" selected>A'				+
							'<option value="MX">MX'						+
							'<option value="CNAME">CNAME'				+
							'<option value="TXT">TXT'					+
						'</select>'										+
					  '</td>'											+
					  '<td><input type="text" name="faddr[]" placeholder="IP адрес"></td>'+
					'<td>'												+
						'<input type="hidden" name="stat[]" value="1">' +
						'<input type="hidden" name="fid[]" value="0">' 	+
						'<div class="delRow"></div>'					+
				'</td></tr>';

		$(this).closest('.atable').append(new_tr);


		return false;
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



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


	$('#submit_view').live('click', function(event){

			// для записи SOA создаем контент
			faddr = $(':text[name="faddr[]"]').val() + ' ' + ($(':text[name="contact"]').val()).replace('@','.');
			$(':hidden[name="faddr[]"]').val(faddr);

			// пустые поля fname[] для записей NS должны получать значение zname
			$(':hidden[name="fname[]"]').val( $(':text[name="zname"]').val() );

			// проверка на совпадающие имена
			var _name = $(':text[name="zname"]').val();
			var _id   = $(':hidden[name="domain_id"]').val();

			existNameId = $('tr')
							.filter('[sid="' + _id + '"]')
							.filter('[sname="' + _name + '"]')
							.length;

			existName = $('tr')
							.filter('[sname="' + _name + '"]')
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


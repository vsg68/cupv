
$(function(){

	var ctrl = window.location.pathname.split('/')[1];

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
					  '<td><input type="text" name="faddr[]" placeholder="IP адрес (приоритет)"></td>'+
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

			// пустые поля fname[] для записей NS должны получать значение zname для новой записи
			if( $(':text[name="zname"]').val() )
				$(':hidden[name="fname[]"]').val( $(':text[name="zname"]').val() );


			// проверка на совпадающие имена
			var _name = $(':text[name="zname"]').val();

			existName = $('tr')
							.filter('[sname="' + _name + '"]')
							.length;

			if( existName )
				$('input[name="zname"]').val('');

			try_submit();
			return false;

		});

	$('#new span').click( function(){ createItem(this) });


	$('.ed-0').live('click', function(){
// какой родительский класс вызываем
						$('.fhead, .delRow').toggleClass('hidden');
	});

		//~ tmpl_id = $(this).attr('id').replace('x-','');
//~
		//~ $("#tree").dynatree("getRoot").addChild({"title":"new-node", "key":"00"});
//~
		//~ var node = $("#tree").dynatree("getTree").getNodeByKey('00');
//~
//~
		//~ $.post('/badm/add',{id:0, name:node.data.title, pid:0, tmpl_id:tmpl_id }, function(response){
//~
						//~ tmpl = /^\d+$/;
//~
						//~ if( tmpl.test(response) ) {
							//~ node.data.key = response;
							//~ getData(ctrl, node.data.key);
						//~ }
						//~ else {
							//~ node.remove();
							//~ alert('при сохранении произошла ошибка');
						//~ }
		//~ })
	//~ });



});

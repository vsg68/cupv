
$(function(){

	var ctrl = window.location.pathname.split('/')[1];

	$('.add').live('click',function(){


		if( $(this).prop('id') == 'entry') {

			new_tr = "<tr><td class='fname'>"												+
						"<div class='up'>&#9650;</div>" 									+
						"<input type='text' name='fname[]' placeholder='название'/></td>" 	+
					 "</td><td class='ftext'>"												+
						"<select name='ftype[]'>" 											+
							"<option value='text' selected>text</option>" 					+
							"<option value='textarea'>textarea</option>"					+
						"</select>"															+
					"</td><td><div class='delRow'></div></td>"								+
					"</tr>";
			$(this).closest('table').append(tr);
		}
		else {

		}



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


	$('#tree').dynatree("option","initAjax", {
											url: "/"+ ctrl +"/getTree",
											data: {"page": ctrl}
											});

	$('#tree').dynatree('getTree').reload();

	$('#new').unbind("click");

	$('#new').click( function(){ createItem(this) });

	$('.ed-0').toggle(
					function(){
// какой родительский класс вызываем
							$('.fhead, #alias, .delRow').show();
							},
					function(){
							$('.fhead, #alias, .delRow').hide();
							}
		);
//~
	//~
	//~ $('textarea').live('keydown',function(){
//~
		//~ $(this).css('height', $(this).scrollHeight+'px');
		//~ alert($(this).attr('rows') +'; real: ' + $(this).height() +', line: ' + $(this).css('line-height'));
		//~ })



});

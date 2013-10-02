
$(function(){

	var ctrl = window.location.pathname.split('/')[1];
	// Скидываем предыдущие связи

	$('.add').live('click',function(){


		if( $(this).attr('id') == 'entry') {

			new_tr = "<tr class='alias'><td class='fname'>"									+
						"<input type='text' name='fname[]' placeholder='название'/></td>" 	+
					 "</td><td class='ftext'>"												+
						"<select name='ftype[]'>" 											+
							"<option value='text' selected>text</option>" 					+
							"<option value='textarea'>textarea</option>"					+
						"</select>"															+
					"</td><td><div class='delRow'></div></td>"								+
					"</tr>";
		}
		else {
			new_tr = "<tr><td class='fname'>" 												+
						 "<input type='text' name='tdname[]' placeholder='название'/></td>" +
					 "</td><td><div class='delRow'></div></td>" 							+
					 "</tr>";
		}

		$(this).closest('table').append(new_tr);

		return false;
	});

	$('#submit_view').live('click', function(event){

			try_submit();
			return false;
	});



	$('#tree').dynatree('getTree').reload();

	$('#new').click( function(){ createItem(this) });





	$('.delRow').live('click', function(){

		$(this).closest('tr').remove();
		//return false;
	});
//~
	//~
	//~ $('textarea').live('keydown',function(){
//~
		//~ $(this).css('height', $(this).scrollHeight+'px');
		//~ alert($(this).attr('rows') +'; real: ' + $(this).height() +', line: ' + $(this).css('line-height'));
		//~ })



});

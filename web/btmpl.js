
$(function(){

	enableDnd();
	$("#tree").dynatree("getTree").reload();

	$('.add').live('click',function(){


		if( $(this).attr('id') == 'entry') {

			new_tr = "<tr class='alias'><td class='fname'>"									+
						"<input type='text' name='fname[]' placeholder='название'/>"		+
						"<input type='hidden' name='fval[]' value=''/></td>" 				+
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
						 "<input type='text' name='tdname[0][]' placeholder='название'/>"		+
					 "</td><td><div class='delRow'></div></td>" 							+
					 "</tr>";
		}

		$(this).closest('table').append(new_tr);

		return false;
	});



	$('#new').click( function(){ createItem(this) });







//~
	//~
	//~ $('textarea').live('keydown',function(){
//~
		//~ $(this).css('height', $(this).scrollHeight+'px');
		//~ alert($(this).attr('rows') +'; real: ' + $(this).height() +', line: ' + $(this).css('line-height'));
		//~ })



});

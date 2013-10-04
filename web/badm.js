
$(function(){

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
						 "<input type='text' name='tdname[]' placeholder='название'/>"		+
						 "<input type='hidden' name='tdval[]' value=''/></td>" 				+
					 "</td><td><div class='delRow'></div></td>" 							+
					 "</tr>";
		}

		$(this).closest('table').append(new_tr);

		return false;
	});



	$('#new span').click( function(){ createItem(this) });


	$('.ed-0').live('click', function(){
// какой родительский класс вызываем
						$('.add, .delRow').toggleClass('hidden');
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

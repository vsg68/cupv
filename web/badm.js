
$(function(){

	$('.add').live('click',function(){


		if( $(this).attr('id') == 'entry') {

			var fname = $(':text[name="fname"]').val();
			var ftype = $('option:selected').val();

			if( ! fname ) {
				$('.text[name="fname"]').focus();
				return false;
			}
// можно действовать клонированием, если запретить убирать последнюю строку в таблице
			tr = "<tr class='line'><td></td></tr>" +
			     "<tr><td class='fname'>" 		+
				 "<input type='hidden' name='fname[]' value='" + fname + "'/>" + fname + "</td>"+
				 "<td class='ftext'>";

			if(ftype == 'text')
				tr += "<input type='text' name='fval[]' value=''/>";
			else
				tr += "<textarea name='fval[]'></textarea>";

			tr += "<input type='hidden' name='ftype[]' value='" + ftype + "'/></td>" +
				  "<td class='noborder'><div class='delRow'></div></td></tr>";

			$('.text[name="fname"]').val('');	  // нужное составили

			$('table.entries').append(tr);
		}
		else {

			var tr = $(this).closest('tr').clone();
			var rows = $('.records tr').length;
			var W = $(this).closest('tr').children('td:first').innerWidth()-4;

			tr.find('.t-h')
			  .removeClass('t-h')
			  .not('.else')
			  .html('<textarea name="tdname['+ rows +'][]" rows=2></textarea>')
			  .end()
			  .find('textarea')
			  .css('max-width',W);

			tr.find('.else')
			  .html('<div class="delRow"></div>')
			  .removeClass('else');

			$(this).closest('table').append(tr);
		}



		return false;
	});

	// Устанавливаем ширину поля таблицы
	W = $('.records td:first').innerWidth()-4;
	$('.records textarea').css('max-width', W);


	$('#new span').click( function(){ createItem(this) });


	$('.ed-0').live('click', function(){
				$('.templ, .add, .delRow, .submit').toggleClass('hidden');
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

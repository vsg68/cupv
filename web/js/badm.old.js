
$(function(){

	$('.add').live('click',function(){


		if( $(this).attr('id') == 'entry') {

			var fname = $(':text[name="fname"]').val();
			var ftype = $('option:selected').val();

			if( ! fname ) {
				$('.text[name="fname"]').focus();
				return false;
			}

			tr = "<tr class='line'><td></td></tr>" +
			     "<tr><td class='fname'>" 		+
				 "<input type='hidden' name='fname[]' value='" + fname + "'/>" + fname + "</td>"+
				 "<td class='ftext'>";

			if(ftype == 'text')
				tr += "<input type='text' name='fval[]' value=''/>";
			else if(ftype == 'data')
				tr += "<input type='text' name='fval[]' value='' class='date_field'/>";
			else
				tr += "<textarea name='fval[]'></textarea>";

			tr += "<input type='hidden' name='ftype[]' value='" + ftype + "'/></td>" +
				  "<td class='noborder'><div class='delRow'></div></td></tr>";

			$('.text[name="fname"]').val('');	  // нужное составили

			$('table.entries').append(tr);

			$(".date_field").datepicker({ dateFormat: "yy-mm-dd" });
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


	$('#new span').click( function(){ createItem(this) });

	$('.ed-0').live('click', function(){

								if( $('.templ').hasClass('hidden') ) {

										$('.templ, .add, .delRow, .submit').removeClass('hidden');
										enableDnd();
								}
								else {
										$('.templ, .add, .delRow, .submit').addClass('hidden');
										disableDnd();
								}

								$("#tree").dynatree("getTree").reload();
	});

});
// Устанавливаем ширину поля таблицы - эмпирически
function textareaWidth(){

	tdcount =  $('.records tr:first').children('td').length - 1;
	$('.records textarea').width( Math.ceil( 600/tdcount -6) );

}




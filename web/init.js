/*
		Для установки начальных значений
*/
$(function(){

// onmouseover
	$('#new').hover( function(){ $(this).addClass('hover_new')}, function(){ $(this).removeClass('hover_new')});

	$(".mainmenu ul li").hover(function(){ $(this).addClass("hover_item");}, function(){$(this).removeClass("hover_item")});
	$(".mainmenu ul li a").click(function(){

					$('.selected').removeClass('selected');
					$(this).parent().addClass("selected");
				});

	ctrl = window.location.pathname.split('/')[1];
	ctrl = ( ctrl ) ? ctrl : 'users';
	$('#' + ctrl).addClass('selected');


	// Удаление строк
	$('.delRow').live('click', function(){

		var tr 		   = $(this).closest('tr.alias');
		var input_hide = $(tr).find(':hidden:eq(0)');

		// Если есть таг - то поле создано вручную
		if( $(input_hide).attr('tag') )
			$(tr).remove();
		else {
			$(input_hide).val('2');
			$(tr).addClass('hidden');
		}
		return false;
	});


	// Блокирование поля алиаса (disable)
	$(':checkbox').live('click', function(){

		var input_text = $(this).closest('tr.alias').find(':text:eq(0)');
		var input_hide = $(this).closest('tr.alias').find(':hidden:eq(0)');

		if ( $(this).attr('checked') ) {
			$(input_text).removeAttr('disabled');
			$(input_hide).val('1');
		}
		else {
			$(input_text).attr('disabled', 'true');
			$(input_hide).val('0');
		}
	});

})

// Проверка введенных значений
//function checkfield(name, value) {
function checkfield(obj) {

	name  = $(obj).attr('name');
	value = $(obj).val();

	one_net	  =	"(\\d{1,3}\\.){3}\\d{1,3}(/\\d{1,2})?";
	net_tmpl  = "^\\s*" + one_net + "(\\s*,\\s*" + one_net + ")*\\s*$";
	mail_tmpl = "^[\\w\\.]+@(\\w+\\.){1,}\\w+$";

	switch (name ) {
		case 'allow_nets':
			//value = value.replace(' ',g);
			reg = new RegExp(net_tmpl,'i')
			break
		case 'alias[]':
		// алиас != mailbox
			if( $('#mailbox').val() == value )   return true
		// задаем регулярное выражение
			reg = new RegExp(mail_tmpl,'i')
			break
		case 'fwd[]':
			reg = new RegExp(mail_tmpl,'i')
			break
		default:
			if( ! value )
				return true
			else
				return false
	}

	if( reg.test(value) )
		return false;
	else
		return true;

}

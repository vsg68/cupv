/*
		Для установки начальных значений
*/

$(function(){

	options = { serviceUrl:'/users/searchdomain/',type:'post'};

// onmouseover
	$('#new').hover( function(){ $(this).addClass('hover_new')}, function(){ $(this).removeClass('hover_new')});

	$(".mainmenu ul li").hover(function(){$(this).addClass("hover_item");}, function(){$(this).removeClass("hover_item")});
	$(".mainmenu ul li a").click(function(){
									$('.selected').removeClass('selected');
									$(this).parent().addClass("selected");
								});

	var ctrl = window.location.pathname.split('/')[1];
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
		//var input_hide = $(this).closest('tr.alias').find(':hidden:eq(0)');
		var input_hide = $(this).closest('tr.alias').find(':hidden[name="stat[]"]');

		if ( $(this).attr('checked') ) {
			$(input_text).removeAttr('disabled');
			$(input_hide).val('1');
		}
		else {
			$(input_text).attr('disabled', 'true');
			$(input_hide).val('0');
		}
	});


	//Фильтрайия пользователей по домену
	$('select','#domains_flt').change(function(){

		filter = $('option:selected', '#domains_flt').text();

		$('.hidden').removeClass('hidden');

		if( filter )
			$('td.key:not(:contains("@' + filter+ '"))', '.aliases_box')
					.parent()
					.addClass('hidden');

	});

	//Фильтрайия пользователей по ящикам
	$('#fltr').keyup(function(event){

		$('.hidden_filter').removeClass('hidden_filter');

		search_str = $(this).val();

		if( search_str )
			$('td.val:not(:contains("' + search_str + '"))', '.aliases_box')
			.parent()
			.addClass('hidden_filter');

	});


	// Hover по массиву алиасов
	$('tr','.aliases_box,.domain_box').hover( function(){
									$(this).addClass('hover_tr');
									},
								  function(){
									$(this).removeClass('hover_tr');
								});

	// Выбор записи
	key = window.location.search.split('=')[1];
	//?????
	$('.key','.aliases_box').filter(':contains("' + key + '")').parent().addClass('selected_key');

	$('tr','.domain_box').filter('[sid="' +  key + '"]').addClass('selected_key');

	// Запрос на редактирование
	//$('tr','.aliases_box').click( function(){
	$('#usrs tr').click( function(){
									// Выбор записи
									$('.selected_key').removeClass('selected_key');
									$(this).addClass('selected_key');
									// Запрос
									if( $(this).closest('.aliases_box').length )
										name = $('.key', this).text();
									else if( $(this).closest('.domain_box').length )
										name = $(this).attr('sid');

									$.get('/'+ ctrl + '/single/',{'name':name, 'act':'1'},function(response){
										 $('#ed').empty().append(response);

										 // Если имеем дело с транспортом - блокируем алиасы
										 if( $(':text[name="delivery_to"]').val() != undefined )
										  	$('#alias').attr('disabled','true');
									})
								});

	//Новый пользователь
	$('#new').click(function(){
					$.get('/'+ ctrl +'/new/', function(response) {$('#ed').empty().html(response);});
	});


});


// Общая часть проверки, отсылки и получения данных
function try_submit() {

	var is_ok = true;
	var ctrl  = window.location.pathname.split('/')[1];
	    ctrl = ( ctrl ) ? ctrl : 'users';

	// проверка на пустые поля
	$(':text', '#usersform').each(function(){

		if( checkfield( $(this) ) ) {

			$(this).addClass('badentry');
			is_ok = false;
		}
		else
			$(this).removeClass('badentry');
	});

	// проверка окончена
	if( ! is_ok )	{

		$('.badentry:first').focus();
		return false;
	}

	// удаляем атрибут, чтобы поле ушло на сервер
	// иначе получим рассогласование длины массивов
	$('.alias :text[disabled]').removeAttr('disabled');

	var params =  $('#usersform').serialize();

	$.post(	'/'+ ctrl +'/add/', params , function(response) {

						if( ctrl == 'domain' || ctrl == 'admin' )
							tmpl = /^\d+$/;
						else
							tmpl = /^[\w\.]+@(\w+\.){1,}\w+$/;

						if( tmpl.test(response) )
							window.location = '/'+ ctrl +'/view/?name=' + response;
						else
							$('#ed').empty().html(response);
					});
	return false;
}

// Проверка введенных значений
//function checkfield(name, value) {
function checkfield(obj) {

	name  = $(obj).attr('name');
	value = $(obj).val();

	one_net	  =	"(\\d{1,3}\\.){3}\\d{1,3}(/\\d{1,2})?";
	net_tmpl  = "^\\s*" + one_net + "(\\s*,\\s*" + one_net + ")*\\s*$";
	mail_tmpl = "^[\\w\\.]+@(\\w+\\.){1,}\\w+$";
	word_tmpl = "^[\\w\\.]+$";
	transp_tmpl	= "^\\w+:\\[(\\d{1,3}\\.){3}\\d{1,3}\\]$";
	domain_tmpl	= "^(\\w+\\.)+\\w+$";

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
		case 'dom[]':
			reg = new RegExp(domain_tmpl,'i')
			break
		case 'domain_name':
			reg = new RegExp(domain_tmpl,'i')
			break
		case 'delivery_to':
			reg = new RegExp(transp_tmpl,'i')
			break
		case 'all_email':
		// Если чекбокс есть и не отмечен - пропускаем проверку
			if( ! $('input:checked').is('[name="all_enable"]') ) return false
			reg = new RegExp(word_tmpl,'i')
			break
		case 'section_note':
			// не проверяем
			return false
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

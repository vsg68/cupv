
$(function(){
	// добавление строк	
	$('.else').live('click',function(){
		
		name 		= $(this).attr('id');
		email		= ( $('input[name="mailbox"]').size() == 1) ? $('input[name="mailbox"]').val() : 'mailbox@domain.ru';
		open_tag 	= '<tr class="alias">';
		mail_cell 	= '<td>'+
						'<input type="hidden" tag="1" name="' + name + '_st[]" value="1">' +
						'<input type="hidden" name="' + name + '_id[]" value="0">' + email +
					 '</td>';
		input_cell 	= '<td><input type="text" name="' + name + '[]" value=""></td>';
		chkbox_cell = '<td><input type="checkbox" name="chk" checked></td>';
		button_cell = '<td><img src="/cross.gif" class="delRow" border="0"></td>';
		close_tag 	= '</tr>';

		var tbl = $(this).parents('.atable').get(0);
		tr 	= name == 'alias' ? open_tag + input_cell + mail_cell + chkbox_cell + button_cell + close_tag :
								open_tag + mail_cell + input_cell + chkbox_cell + button_cell + close_tag;
		$(tbl).append( tr );
		
		return false;
	});

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
		
			
		
		
	});

	// подсветка выбранного пользователя
	$('a, .usr').click(function(){
		
			var href = $(this).attr('href');

			if( href === undefined )  return false;
			
			$('.active').removeClass('active');
			$(this).parent('.usr').addClass('active');

			$.ajax({
				url: href,
				type: 'post',
				success: function(response) {
					$('.view')
							.empty()
							.html(response);
					//$('.alias:even').css('background-color','#b8c8c8');										
				}
			});								
			return false;
		});

	// Submit
	$('#submit_view').live('click', function(event){
			event.preventDefault();
			// удаляем атрибут, чтобы поле ушло на сервер
			// иначе получим рассогласование длины массивов
			$('.alias :text[disabled="true"]').removeAttr('disabled');

			var params =  $('#usersform').serialize();
			$.post(	'/users/add/', params , function(response) {
				
								$('.view').empty().html(response);
								// Если добавили нового пользователя
								// - вставляем его адрес в список адресов
								var user_id = $(':hidden[name="user_id"]','#usersform').val();
								var mailbox = $(':hidden[name="mailbox"]','#usersform').val();

								if( ! $('a:contains("'+mailbox+'")', '.usr').length ) {

									var str = $('.usr:last').clone(true);
									$(str).addClass('active').children('a').html('/user/view/'+user_id).text(mailbox);
									$('#ulist').append(str);
								}	
												
							});
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

	//Новый пользователь
	$('#newusr').click(function(event){
			event.preventDefault();
			
			if( href === undefined )  return false;

			$.post('/users/new/', function(response) {
										$('.view')
											.empty()
											.html(response);
										});								
			return false;
			
	});

	$(':text').live('change', function(e){

				var x = e.target;

				if( checkfield(e.target.name, e.target.value ) ) {
					$(x).addClass('badentry').focus();
					$('#submit_view').attr('disabled','true');
				}
				else {
					$('#submit_view').removeAttr('disabled');
					$(x).removeClass('badentry');
				}
		});
 })

// Проверка введенных значений
function checkfield(name, value) {

net_tmpl  = "((\\d+\.)+\\d+(/\\d+)?,?\\s*)+";
mail_tmpl = "(\\w+)@(\\w+\.)+(\\w+)";

	switch (name ) {
		case 'allow_nets':
			reg = new RegExp(net_tmpl,'i')
			break
		case 'alias[]':
		// алиас != mailbox
			if( $('#mailbox').val() == value )   return true
		// задаем регулярное выражение
			reg = new RegExp(mail_tmpl,'i')
		// проверка, что алиас из наших доменов
//			if( legacy_domain(value) ) return true
			break
		case 'fwd[]':
			reg = new RegExp(mail_tmpl,'i');
			break
		case '':
			return true
		default:
			return false
	}

	if( reg.test(value) )
		return false;
	else
		return true;
		
}


// Проверка на существование домена в базе
function legacy_domain( addr ) {

// vvv@domain.net -> domain.net
	domain = addr.replace(/\w+@/,'');
	
	$.ajax({
			url:'/users/chkdomain/',
			data:{'id':domain},
			type: 'post',
			cache: false,
			success: function(response) { $('#mailbox').data('domain',response); }

	});
	if( $('#mailbox').data('domain') == domain )
		return false;
	else	
		return true;
}

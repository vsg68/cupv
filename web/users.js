
$(function(){
	options = { serviceUrl:'/users/searchdomain/',type:'post'};

	// заполняем массив для фильтрации
	var mboxes = $('option','#usrs');

	// добавление строк
	$('.else').live('click',function(){

		name 		= $(this).attr('id');
		email		= ( $('input[name="mailbox"]').size() == 1) ? $('input[name="mailbox"]').val() : 'mailbox@domain.ru';
		open_tag 	= '<tr class="alias">';
		mail_cell 	= '<td>'+
						'<input type="hidden" tag="1" name="' + name + '_st[]" value="1">' +
						'<input type="hidden" name="' + name + '_id[]" value="0">' + email +
					 '</td>';
		alias_cell 	= '<td><input class="autocomp" type="text" name="' + name + '[]" value=""></td>';
		fwd_cell 	= '<td><input type="text" name="' + name + '[]" value=""></td>';
		chkbox_cell = '<td><input type="checkbox" name="chk" checked></td>';
		button_cell = '<td><button class="delRow  web">r</button></td>';
		close_tag 	= '</tr>';

		var tbl = $(this).parents('.atable').get(0);
		tr 	= name == 'alias' ? open_tag + alias_cell + mail_cell + chkbox_cell + button_cell + close_tag :
								open_tag + mail_cell + fwd_cell + chkbox_cell + button_cell + close_tag;
		$(tbl).append( tr );

		$('.autocomp').autocomplete({ serviceUrl:'/users/searchdomain/',type:'post'});

		return false;
	});

	// добавление пути
	$('#path').live('click',function(){
		path = '<input class="formtext path" type="text" name="path" value="" />';
		// если уже есть одно поле, то остальные пропускаем
		if( $('.path').length == 0 ) {

			$('#path').parent().append(path);
			$('.path').focus();
			$('#path').text('3');
		}
		else {
			$('.path').remove();
			$('#path').text('4');
		}
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
		return false;
	});


	// Вывод данных пользователя
	$('select#usrs').change(function(){

			var href = '/users/view/' + $(this).val();

			if( href === undefined )  return false;

			$.ajax({
				url: href,
				type: 'post',
				success: function(response) {
					$('.view')
							.empty()
							.html(response);
				}
			});
			return false;
		});

	//Фильтрайия пользователей по домену (users)
	$('select','#domains_flt').change(function(){

		filter = $('option:selected', '#domains_flt').text();

		if( filter )
			selected_opts = $(mboxes).filter(':contains("@' + filter+ '")');
		else
			selected_opts = $(mboxes);

		$('select#usrs').empty().append(selected_opts);

	});

	// Submit
	$('#submit_view').live('click', function(event){

			event.preventDefault();
			var is_ok = true;

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
			$.post(	'/users/add/', params , function(response) {

								$('.view').empty().html(response);
								// Если добавили нового пользователя
								// - вставляем его адрес в список адресов
								var user_id = $(':hidden[name="user_id"]','#usersform').val();
								var mailbox = $(':hidden[name="mailbox"]','#usersform').val();

								// если имела место ошибка - <div id='log'>xxx</div>
								if( mailbox === undefined )	return false;

								is_exist = $('option:contains("'+mailbox+'")', '#usrs').length;

								if( ! is_exist ) {

									var str = '<option value=' + user_id + '>' + mailbox + '</option>';
									$('#usrs').append(str);
									$('option:last', '#usrs').attr('selected', true).focus();
								}

								// обновляем св-во активности
								is_active = $(':checkbox[name="active"]:checked', '#usersform').length;

								$('option:contains("'+mailbox+'")', '#usrs').toggleClass( 'disabled', is_active==0)

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
	$('#new').click(function(){

		$.post('/users/new/', function(response) {
								$('.view')
									.empty()
									.html(response);
								});
		return false;

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


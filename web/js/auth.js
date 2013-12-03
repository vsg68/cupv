$(function(){

		H 	= $(window).outerHeight();
		eH	= H - 150;	// Скролл главной таблицы

		TTOpts.aButtons[5].sButtonText = 'АВТОРИЗОВАННЫЕ ПОЛЬЗОВАТЕЛИ';
		TTOpts.aButtons.splice(3,2);

		var oTable = $('#tab-auth').dataTable({
								"bJQueryUI": true,
								"sScrollY":  eH + "px",
								"bScrollCollapse": true,
								"bPaginate": false,
								"sDom": '<"H"Tf>t<"F"ip>',
								//"aaSorting": [[3,"asc"]],
								"sAjaxSource": "/" + ctrl + "/showTable/",
								"sServerMethod": "POST",
								"fnInitComplete": function () {
														this.fnAdjustColumnSizing();
														this.fnDraw();
												},
								"aoColumnDefs": [
													{"sClass": "center", "aTargets": [-1] },
													{"bSortable":true, "aTargets": [0] },
													{"bSortable":false, "aTargets": ['_all'] },
												],
								"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
													drawCheckBox(nRow);
													addRowAttr(nRow,'login',0);
												},
								"oTableTools": TTOpts

								});


		$('body').on('click','.mkpwd', function(){
			$(this).closest('tr').find(':text[name="passwd"]').val(mkpasswd());
		});


})

/*
 *  Если НЕ выделена строка в таблице users - создавать для нее алиасы запрещаем
 *  Запещаем редактировать и удалять, если не выделено
 */
function blockNewButton(nodes) {

		if( nodes.length && (tab = nodes[0].offsetParent.id) ) {
			$('#ToolTables_'+tab+'_0').addClass('DTTT_disabled');
			$('#ToolTables_'+tab+'_2').addClass('DTTT_disabled');
		}
}

/*
 *  Если выделена строка в таблице users - разрешаем создавать для нее алиасы
 *  Разрешаем редактировать и удалять, если выделено
 */
function unblockNewButton(node) {

		tab = node[0].offsetParent.id;
		$('#ToolTables_'+tab+'_0').removeClass('DTTT_disabled');
		$('#ToolTables_'+tab+'_2').removeClass('DTTT_disabled');
}


modWin.validate_auth = function () {

			name	= $('form :text[name="login"]').val();
			pass	= $('form :text[name="passwd"]').val();
			id		= '#tab-auth-' + $(':hidden[name="id"]').val();

			if ( ! (id && pass) ) {
				modWin.message = 'В новой записи заполнение полей логин и пароль - обязательно. ';
			}

			if ( ! name ) {
				modWin.message += 'Поле Логин обязательно к заполнению';
			}


			existNameID = 	$('tr').filter('[login="'+ name + '"]')
									.filter( id )
									.length;
			existName = 	$('tr').filter('[login="'+ name + '"]')
									.length;

			if( ! existNameID && existName ) {
					modWin.message += "Такой логин уже существует";
			}

			return modWin.message;
}

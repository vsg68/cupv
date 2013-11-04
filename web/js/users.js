$(document).ready(function() {

		H 	= $(window).outerHeight();
		rH	= 100;	// Скролл таблицы записей
		d_min = rH + 110;  // Расстояние от главной таблицы до дна
		eH	= 550;	// Скролл главной таблицы
		// Настройка скроллинга большой таблицы
		if( eH + d_min > H )
			eH = H - d_min;



		var oTable = $('#tab-users').dataTable({
								"bJQueryUI": true,
								"sScrollY":  eH + "px",
								"bPaginate": false,
								"sDom": '<"H"Tf>t<"F"ip>',
								"aoColumnDefs": [
												{"bSortable":false, "aTargets": [3] },
												{"bSortable":false, "aTargets": [4] },
												{"bSortable":false, "aTargets": [5] },
												{"bSortable":false, "aTargets": [6] },
												{"bSortable":false, "sClass": "center", "aTargets": [7] },
												{"bSortable":false, "sClass": "center", "aTargets": [8] },
												],
								"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
													drawCheckBox(nRow);
												},
								"oTableTools": TTOpts

								});


		TTOpts.aButtons[1].sButtonClass = 'DTTT_button_new DTTT_disabled';
		delete TTOpts.aButtons[3];

		var aTable = $('#tab-aliases').dataTable({
								"bJQueryUI": true,
								"sDom": '<"aliases-header"T>t',
								"sScrollY": rH+"px",
								"aoColumnDefs": [{"sClass": "center","bSortable":false, "aTargets": [2] }],
								"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
													drawCheckBox(nRow);
												},
								"oTableTools": TTOpts,
								});

		var aTable = $('#tab-lists').dataTable({
								"bJQueryUI": true,
								"sDom": '<"aliases-header"T>t',
								"sScrollY": rH+"px",
								"oTableTools": TTOpts,
								});


		$('body').on('click','.mkpwd', function(){

			$(this).closest('tr').find(':text[name="password"]').val(mkpasswd());
		});


});

/*
 *  Если выделена строка в таблице users - показываем связанные с ней алиасы
 */
function showAliasesTable(node) {

		if(node[0].offsetParent.id != 'tab-users')
			return false;

		id = node[0].id.split('-')[2];
		$.ajax({
				type: "GET",
				url: '/'+ ctrl +'/records/' + id,
				dataType: "json",
				success: function(response) {
										$('#tab-aliases').dataTable().fnClearTable();
										$('#tab-aliases').dataTable().fnAddData(response);
										},
				error: function() {
									$('#tab-aliases').dataTable().fnClearTable();
									}
		});
}

/*
 *  Если НЕ выделена строка в таблице users - создавать для нее алиасы запрещаем
 *  Запещаем редактировать и удалять, если не выделено
 */
function blockNewButton(nodes) {

		if( nodes.length && (tab = nodes[0].offsetParent.id) ) {
			$('#ToolTables_'+tab+'_0').addClass('DTTT_disabled');
			$('#ToolTables_'+tab+'_2').addClass('DTTT_disabled');
		}

		if( nodes.length && nodes[0].offsetParent.id == 'tab-users')
			$('#ToolTables_tab-aliases_1').addClass('DTTT_disabled');
}

/*
 *  Если выделена строка в таблице users - разрешаем создавать для нее алиасы
 *  Разрешаем редактировать и удалять, если выделено
 */
function unblockNewButton(node) {

		tab = node[0].offsetParent.id;
		$('#ToolTables_'+tab+'_0').removeClass('DTTT_disabled');
		$('#ToolTables_'+tab+'_2').removeClass('DTTT_disabled');

		if( node[0].offsetParent.id == 'tab-users') {
			$('#ToolTables_tab-aliases_1').removeClass('DTTT_disabled');
		}
}

/*
 *  Получаю выделенную строку в таблице users
 */
function usersRowID(objTT) {

		if( objTT.s.dt.sTableId == 'tab-users' )
			return false;

		return fnGetRowID("tab-users");

}

modWin.validate_users = function () {

			modWin.message = '';
			login = $('form :text[name="login"]').val();
			mailbox =  login + '@' +  $('form option:selected').val();
			allow_nets = $('form :text[name="allow_nets"]').val();

			if ( ! login ) {
				modWin.message += 'Login is required. ';
			}
			else{
				existIdMbox = $(modWin.RowNode).filter('[data="' + mailbox + '"]').length;
				existId     = $(modWin.RowNode).length;

				if( ! existIdMbox && existId ) {
					modWin.message += 'Mailbox exist!'
				}
			}

			if ( ! $('form :text[name="username"]').val()) {
				modWin.message += 'Name is required. ';
			}

			if ( ! $('form :text[name="password"]').val()) {
				modWin.message += 'Message is required.';
			}

			if ( ! $('form :text[name="password"]').val()) {
				modWin.message += 'Message is required.';
			}

			if ( ! fnTestByType(allow_nets,'nets')) {
				modWin.message += 'Поле "разрешенные сети" должно содержать правильную маску сети.\n';
			}
			if (modWin.message.length > 0) {
				return false;
			}
			else {
				return true;
			}
}

modWin.validate_aliases = function () {

			modWin.message = '';
			alias_name	= $('form :text[name="alias_name"]').val();
			delivery_to	= $('form :text[name="delivery_to"]').val();

			if ( ! (alias_name || delivery_to) ) {
				modWin.message += 'Хотя бы одно поле должно быть заполнено. ';
			}

			if ( !(fnTestByType( alias_name, 'mail') && alias_name) ) {
				modWin.message += 'поле должно содержать почтовый адрес';
			}

			if ( !(fnTestByType( delivery_to, 'mail') && delivery_to) ) {
				modWin.message += 'поле должно содержать почтовый адрес';
			}
			if (modWin.message.length > 0) {
				return false;
			}
			else {
				return true;
			}
}

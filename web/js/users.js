$(document).ready(function() {

		H 	= $(window).outerHeight();
		rH	= 100;	// Скролл таблицы записей
		d_min = rH + 110;  // Расстояние от главной таблицы до дна
		eH	= 600;	// Скролл главной таблицы
		// Настройка скроллинга большой таблицы
		if( eH + d_min > H )
			eH = H - d_min;



		var oTable = $('#users').dataTable({
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

		var aTable = $('#aliases').dataTable({
								"bJQueryUI": true,
								"sDom": '<"aliases-header"T>t',
								"sScrollY": rH+"px",
								"aoColumnDefs": [{"sClass": "center","bSortable":false, "aTargets": [3] }],
								"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
													drawCheckBox(nRow);
												},
								"oTableTools": TTOpts
								});

		$('body').on('click','.mkpwd', function(){

			$(this).closest('tr').find(':text[name="password"]').val(mkpasswd());
		})
});

function fnDelete(uid) {

		if( ! uid.length )
			return false;

		if( ! confirm('Уверены, что надо стирать?') )
			return false;

		tab = uid.split('-')[0];
		mbox = 	$('#'+uid).attr('data');

		$.post('/'+ ctrl +'/delEntry/', {mbox: mbox}, function(){
											$('#'+tab).dataTable().fnDeleteRow( $('#'+uid).get(0) );
											if(tab == 'users')
												$('#aliases').dataTable().fnClearTable();
										});

}

function mkpasswd(num_var) {

			if(!num_var)
				num_var = 7;

			passwd = '';
			str = "OPQRSrstuvwxTUVWXYZ0123456789abcdefjhigklmABCDEFJHIGKLMNnopqyz_=-";

			for(i=0;i<num_var;i++) {
				n = Math.floor(Math.random() * str.length);
				passwd += str[n];
			}
			return passwd;
}
modWin.validate = function () {

			modWin.message = '';
			login = $('form :text[name="login"]').val();
			mailbox =  login + '@' +  $('form option:selected').val();

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

			if (modWin.message.length > 0) {
				return false;
			}
			else {
				return true;
			}
}


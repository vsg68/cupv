$(function(){

		H 	= $(window).outerHeight();
		eH	= H - 150;	// Скролл главной таблицы


		 $('#tab').dataTable({
							"bJQueryUI": true,
							"sScrollY":  eH + "px",
							"bPaginate": false,
							"bSort": false,
							"sDom": '<"H"T<"myfilter">>t<"F"ip>',
							//"sAjaxSource": "/" + ctrl + "/showTable/",
							"fnInitComplete": function () {
													$(':text, select', '.editmenu').addClass('ui-widget-content ui-corner-all');
													var filter = $('.editmenu').clone();
													$('.editmenu').remove();
													$('.myfilter').append( filter );
													$('.date_field').datepicker({dateFormat:"yy-mm-dd"});

													this.fnAdjustColumnSizing();
													this.fnDraw();
											},
							"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
												drawCheckBox(nRow);
												drawNA(nRow);
											},
							"oTableTools": {
										"aButtons":[
													//~ {
														//~ "sExtends":"search",
														//~ "sButtonText": ".",
														//~ "fnClick": function( nButton, oConfig ) {
																		//~ $(nButton).hasClass('DTTT_disabled') ? this.fnPrint( false, oConfig ) : this.fnPrint( true, oConfig );
																	//~ }
													//~ },
													{
														"sExtends":    "text",
														"sButtonText": "ЛОГИ",
														"sButtonClass": 'DTTT_label  DTTT_disabled',
													}
												   ],
										}

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


/*
 *  Замена значений чекбоксов на картинку
 */
function drawNA(nRow) {

		// смотрим на активность записи
		$('td', nRow).each(function(){

			if( $(this).text() == '->' )
				$(this).html('<span class="ui-icon ui-icon-arrowthick-1-e"></span>');

			if( $(this).text() == 'N/A' )
				$(this).html('<span class="ui-icon ui-icon-person"></span>');
				//~$(this).addClass('noname');
		});

}


modWin.validate_aliases = function () {

			modWin.message = '';
			alias_name	= $('form :text[name="alias_name"]').val();
			delivery_to	= $('form :text[name="delivery_to"]').val();
			id			= '#tab-aliases-' + $(':hidden[name="id"]').val();

			if ( ! (alias_name && delivery_to) ) {
				modWin.message += 'Поля адресов должны быть заполнены. ';
			}

			if ( ! fnTestByType( alias_name, 'mail') ) {
				modWin.message += 'поле должно содержать почтовый адрес';
			}

			if ( ! fnTestByType( delivery_to, 'mail') ) {
				modWin.message += 'поле должно содержать почтовый адрес';
			}

			existNameID = 	$('tr')
									.filter('[aname="'+ alias_name + '"]')
									.filter('[fname="'+ delivery_to + '"]')
									.filter( id )
									.length;
			existName = 	$('tr')
									.filter('[aname="'+ alias_name + '"]')
									.filter('[fname="'+ delivery_to + '"]')
									.length;

			if( ! existNameID && existName ) {
					modWin.message += "Такие сочетания алиасов уже присутствуют";
					$('form :text[name="delivery_to"]').val('');

			}

			if (modWin.message.length > 0) {
				return false;
			}
			else {
				return true;
			}
}

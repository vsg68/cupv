$(function(){

	H 	= $(window).outerHeight();
	eH	= H - 150;	// Скролл главной таблицы


	var oTable = $('#tab').dataTable({
							"bJQueryUI": true,
							"sScrollY":  eH + "px",
							"bPaginate": false,
							"bSort": false,
							"sDom": '<"H"T>t<"F"ip>',
							//"sAjaxSource": "/" + ctrl + "/showTable/",
							"fnInitComplete": function () {
													$(':text, select', '.editmenu').addClass('ui-widget-content ui-corner-all');
													var filter = $('.editmenu').clone();
													$('.editmenu').remove();
													$('#ToolTables_tab_0').after( filter );
													$('.date_field').datepicker({dateFormat:"yy-mm-dd"});
											},
							"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
												//~ drawCheckBox(nRow);
												//~ drawNA(nRow);
											},
							"oTableTools": {
										"sRowSelect": "single",
										"aButtons":[
													{
														"sExtends": "text",
														"sButtonText": "ПОИСК В ПОЧТОВЫХ ЛОГАХ",
														"sButtonClass": 'DTTT_label  DTTT_disabled',
													},
													{
														"sExtends":"text",
														"sButtonText": ".",
														"sButtonClass": 'DTTT_button_search',
														"fnClick": function( nButton, oConfig ) {
																		if( ! $(nButton).hasClass('DTTT_disabled') ) {
																			fnSearch();
																		}
																	}
													},
												   ],
										}

							});



})


/*
 *  Замена значений чекбоксов на картинку
 */
function fnSearch() {

		$('.DTTT_button_search').addClass('DTTT_disabled');
		var oTable = $('#tab').dataTable();
		oTable.fnClearTable();

		$.get('/logs/show/', $('form').serialize(),function(response){
							//oTable.fnAddData(response);
							//~ // Какую форму вернул запрос ?
							jsonArr = /(\[(".+",){3}".+"\],?)+/;

							if( jsonArr.test(response) ) {
								// Получаем объект из строки, если ответ содержит правильные данные
								objJSON = $.parseJSON(response) ;

								oTable.fnAddData(objJSON);
							}
							else {
								if( $(response).find('.form-alert').length ) {
									$(response).modal( modInfo );
								}
							}

							oTable.fnAdjustColumnSizing();
							oTable.fnDraw();
							$('.DTTT_button_search').removeClass('DTTT_disabled');




			});

}


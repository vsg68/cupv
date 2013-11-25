$(function(){

	H 	= $(window).outerHeight();
	eH	= H - 150;	// Скролл главной таблицы

	var changeClass = 0;
	var prevMsgId = 0;
	var xhr;
	var oTable = $('#tab').dataTable({
							"bJQueryUI": true,
							"sScrollY":  eH + "px",
							"bPaginate": false,
							"bSort": false,
							"sDom": '<"H"T>t<"F"ip>',
							"fnInitComplete": function () {
													$(':text, select', '.editmenu').addClass('ui-widget-content ui-corner-all');
													var filter = $('.editmenu').clone();
													$('.editmenu').remove();
													$('#ToolTables_tab_0').after( filter );
													$('.date_field').datepicker({dateFormat:"yy-mm-dd"});
											},
							"fnCreatedRow": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

												nowMsgId = aData[2];

												if(nowMsgId != prevMsgId) {
													changeClass = ! changeClass;
													prevMsgId = aData[2];
												}

												if (changeClass)
													$(nRow).addClass('msgEven');
												else
													$(nRow).addClass('msgOdd');

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

		var oTable = $('#tab').dataTable();
		oTable.fnClearTable();

		$('.DTTT_button_search').addClass('DTTT_disabled');
		$('.loader').modal( modloader );

		xhr = $.ajax({
						type: "GET",
						url: '/logs/show/',
						data: $('form').serialize(),
						success: function(response) {
									$.modal.close();
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
								},
						error: function(response) {
									$.modal.close();
									mesg = response.statusText;
									if( mesg == 'abort' ) {
										$('.abort').fadeIn(1000, function(){
																	$('.abort').fadeOut(1000);
																	});
									}
									else {
										$('.form-alert').text(mesg);
										$('#errmsg').modal();
									}
									$('.DTTT_button_search').removeClass('DTTT_disabled');
								},
					});

}

var modloader = {
			opacity: 0,
			closeHTML: '',
			onShow: function(){
					$('#ok').button({label: 'Send'});
					$('#ok').click(function(){ $.modal.close() });
				},
			onClose: function() {
					$('.DTTT_button_search').removeClass('DTTT_disabled');
					xhr.abort();
				}
	};
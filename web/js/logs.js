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
							"asStripeClasses": [], // убираем классы для четных/нечетных строк
							"sDom": '<"H"T>t<"F"ip>',
							"fnInitComplete": function () {
													$(':text, select', '.editmenu').addClass('ui-widget-content ui-corner-all');
													var filter = $('.editmenu').clone();
													$('.editmenu').remove();
													$('#ToolTables_tab_1').after( filter );
													$('.date_field').datepicker({dateFormat:"yy-mm-dd"});
											},
							"aoColumns": [
										   {'mData':'ReceivedAt',"sWidth": "10%",},
										   {'mData':'SysLogTag',"sWidth": "12%",},
										   {'mData':'MSGID',"sWidth": "12%",},
										   {'mData':'Message'},
										],
							"fnCreatedRow": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

												nowMsgId = aData.msgid;
												$('td:eq(3)', nRow).text(aData.message); // как текст

												if(nowMsgId != prevMsgId) {
													changeClass = ! changeClass;
													prevMsgId = aData.msgid;
												}

												if (changeClass)
													$(nRow).addClass('even gradeA');
												else
													$(nRow).addClass('odd gradeA');

											},
							"oTableTools": {
										"sRowSelect": "single",
										"aButtons":[
													{
														"sExtends": "text",
														"sButtonText": ".",
														"sButtonClass": 'DTTT_button_vis',
														"fnClick": function( nButton, oConfig ) {
																		if( ! $(nButton).hasClass('DTTT_disabled') ) {
																			fnTail();
																		}
																	}
													},
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

	$.ajax({
			type: "GET",
			url: '/logs/show/',
			data: $('form').serialize(),
			dataType: "json",
			success: function(response) {
						$.modal.close();

						oTable.fnAddData(response);
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

/*
 *  Функция получения логов онлайн
 */
var xhr;

function fnTail() {


		if(xhr && xhr.readystate != 4){
				xhr.abort();
				$('.DTTT_button_search').removeClass('DTTT_disabled');
				//$('.DTTT_button_vis').removeClass('DTTT_disabled');
				xhr = null;
		}
		else {
			$('#tab').dataTable().fnClearTable();
			$('.DTTT_button_search').addClass('DTTT_disabled');
			//$('.DTTT_button_vis').removeClass('DTTT_disabled');

			setTimeout(getLogData(0), 5000);
		}
}

/*
 *  беру логи в цикле
 */
function getLogData(newID) {

		var oTable	= $('#tab').dataTable();
		var id 		= newID ? newID : 0;



		xhr = $.ajax({
				type: "GET",
				url: '/logs/tail/',
				data: {'id': id},
				dataType: "json",
				success: function(response) {
							len = response.length;
							if(len) {
								newID = response[(len-1)].ID;
							}
							oTable.fnAddData(response);
							//setTimeout(getLogData(newID), 5000);
						},

				error: function(response) {
							mesg = response.statusText;
							//~ if( mesg == 'abort' ) {
								//~ $('.abort').fadeIn(1000, function(){
															//~ $('.abort').fadeOut(1000);
															//~ });
							//~ }
							//~ else {
							//~ //	setTimeout(getLogData(id), 5500);
							//~ }
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
	};

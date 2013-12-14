
$(function(){
	$('#tree').dynatree( treeOpts );

	H 	= $(window).outerHeight();
	rH	= 110;	// Скролл таблицы записей
	eH	= H-rH;	// Скролл главной таблицы

	// scroll контейнера
	$('.dynatree-container').css('max-height', eH+'px');

	TTOpts.aButtons.splice(3,3);
	TTOpts.aButtons[1].sButtonClass = 'DTTT_button_new DTTT_disabled';
	var oTable = $('#tab-rec').dataTable({
								//"bJQueryUI": true,
								"sScrollY":  eH + "px",
								"bScrollCollapse": true,
								"bPaginate": false,
								"bSort":	false,
								//"sDom": '<"H"Tf>t<"F"ip>',
								"sDom": '<T>t',
								"aoColumnDefs": [{ "sWidth": "20%", "aTargets": [ 0 ] } ],
								"oTableTools": TTOpts
								});

});

/*
 *  Работает при селекте табличного значения
 */
function unblockNewButton(nodes) {

		$('.DTTT_button', '#tab-rec_wrapper').removeClass('DTTT_disabled');
}

function blockNewButton(nodes) {

		$('.DTTT_button', '#tab-rec_wrapper').addClass('DTTT_disabled');
}


/*
 *  Разрешаем редактировать и удалять, если активно 1) фолдеры - редактируем фолдеры
 *  2) потомки - включаем редактирование потомков
 */
function blockButtons(node) {

		if( node.data.isFolder ) {
			$('.DTTT_button', '#tab-rec_wrapper').addClass('DTTT_disabled');
		}
		else {
			$('#ToolTables_tab-rec_1').removeClass('DTTT_disabled');
		}

		$('#tab-tree_0').removeClass('DTTT_disabled');
		$('#tab-tree_2').removeClass('DTTT_disabled');


}


/*
 *
 */
//~ function validate_tree() {
//~
		//~ in_root = $(':radio[name="in_root"]').val();
		//~ pid	 	= $(':hidden[name="pid"]').val();
		//~ id	 	= $(':hidden[name="id"]').val();
//~ return false;
		//~ if ( id == 0 && pid != 0 && in_root == 1 ) {
			//~ return  modWin.message = 'Определитесь, где будет новый раздел. ';
		//~ }
//~ }



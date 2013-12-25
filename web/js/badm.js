
$(function(){
	$('#tree').dynatree( treeOpts );

	//~ H 	= $(window).outerHeight();
	//~ rH	= 110;	// Скролл таблицы записей
	//~ eH	= H-rH;	// Скролл главной таблицы

	// scroll контейнера
	$('.dynatree-container').css('max-height', eH+'px');

	$('#tab-rec').dataTable(DTOpts);

});

/*
 *  Работает при селекте табличного значения
 */
function unblockNewButton(node) {

		// Случай, когда выбран раздел и кликаю по строке
		treeNode = $("#tree").dynatree("getActiveNode");
		if( treeNode.data.isFolder )
			return false;

		$('.DTTT_button', '#tab-rec_wrapper').removeClass('DTTT_disabled');
}

function blockNewButton(node) {

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
 * Передаем ID записи в качестве PID
 */
function deleteWithParams(uid, tab, params) {

		params.pid = $('#' + uid).attr('pid');
		return  params;
}
/*
 *
 */
modWin.validate_rec = function () {

		name = $(':text[name="fname"]').val();
		value= $('textarea').val();

		if ( ! (name || value) ) {
			return  modWin.message = 'Поля должны быть заполнены.';
		}
		return modWin.message;
}


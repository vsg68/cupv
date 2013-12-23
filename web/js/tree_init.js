
var ctrl = window.location.pathname.split('/')[1];

$(function(){

		/*
		 * Реакция на редактирование дерева
		 */
		$('#tab-tree_0').click( function(e) {

				e.preventDefault();

				if( $(this).hasClass('DTTT_disabled') ) {
					return false;
				}
				// если выделеный узел не фолдер - то беру фолдер выделенного узла
				var node = $("#tree").dynatree("getActiveNode");

				if( node.data.isFolder != true ) {
					alert('Выделите раздел');
					return false;
				}


				$.post('/'+ ctrl +'/showEditForm/'+node.data.key, {t:'tree'}, function(response){
						// Какую форму вернул запрос
						if( $(response).find('.form-alert').length )
							$(response).modal( modInfo );
						else
							$(response).modal( modTree );
				});

		});

		/*
		 * Реакция на добавление папки к дереву
		 */
		$('#tab-tree_1').click( function(e) {

				e.preventDefault();

				if( $(this).hasClass('DTTT_disabled') ) {
					return false;
				}

				var node = $("#tree").dynatree("getActiveNode");

				if( node && node.data.isFolder != true ) {
					node = node.getParent();
				}

				pid = (node) ? node.data.key : 0;

				$.post('/'+ ctrl +'/showEditForm/0', {t:'tree',pid: pid}, function(response){
						// Какую форму вернул запрос
						if( $(response).find('.form-alert').length )
							$(response).modal( modInfo );
						else
							$(response).modal( modTree );
				});
		});


		/*
		 * Реакция на Удаление папки дерева
		 */
		$('#tab-tree_2').click( function(e) {

				e.preventDefault();

				if( $(this).hasClass('DTTT_disabled') ) {
					return false;
				}
				// если выделеный узел не фолдер - то беру фолдер выделенного узла
				var node = $("#tree").dynatree("getActiveNode");

				if( node.hasChildren() == true ) {
					alert('Раздел удалять нельзя, если там есть данные');
					return false;
				}
				if( ! confirm('Уверены, что надо стирать?') )
					return false;

				$.post('/' + ctrl + '/delEntryTree/' + node.data.key, {tab:'names'}, function(response){
						// Какую форму вернул запрос ?
						if( $(response).find('.form-alert').length ) {
								$(response).modal( modInfo );
						}
						else {
							node.remove();
						}
				});
		});

		/*
		 * Добавление нового пункта
		 */
		$('#tab-tree_3').click( function(e) {

				e.preventDefault();

				if( $(this).hasClass('DTTT_disabled') ) {
					return false;
				}
				// если выделеный узел не фолдер - то беру фолдер выделенного узла
				var node = $("#tree").dynatree("getActiveNode");

				folderID = ( node.data.isFolder ) ? node.data.key : node.parent.data.key;

				$.post('/' + ctrl + '/showNewForm/' + folderID, {}, function(response){
						// Какую форму вернул запрос
						if( $(response).find('.form-alert').length )
							$(response).modal( modInfo );
						else
							$(response).modal( modNewTree );
				});
		});

		title = $('.pagetab-selected').closest('.theme').attr('title').toUpperCase();
		$('#tab-tree_4 span').append(title);

});

	H 	= $(window).outerHeight();
	rH	= 110;	// Скролл таблицы записей
var	eH	= H-rH;	// Скролл главной таблицы

/*
 * Опции стандартной таблицы
 */
var DTOpts = {
			"sScrollY":  eH + "px",
			"bScrollCollapse": true,
			"bPaginate": false,
			"bSort":	false,
			"sDom": '<T>t',
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
										node = $("#tree").dynatree("getActiveNode");
										makeRowAttr(nRow,'pid',node.data.key);
									},
			"aoColumnDefs": [{ "sWidth": "15%", "aTargets": [ 0 ] },{ "bVisible": false, "aTargets": [ 1 ] }],
			"oTableTools": TTOpts,
};

var treeOpts = {
		//clickFolderMode: 2,
		fx: { height: "toggle", duration: 200 },
		initAjax: {
			url: "/"+ ctrl +"/getTree",
		},
		onFocus: function(node) {
			node.activate();
		},
		onClick: function(node, event) {
			// показываем данные
			if( node.data.isFolder != true) {
				getData(node.data.key);
			}
		},
		onActivate: function( node) {
			if( function_exists('blockButtons') ) {
					blockButtons(node);
			}
			//node.expand(true);
		},
		debugLevel: 0,
		dnd: {
			  onDragStart: function(node) {	return true; },
			  autoExpandMS: 1000,
			  preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
			  onDragEnter: function(node, sourceNode) {
						// Запрещаем перемещать в своем разделе
						if( node.data.isFolder && !sourceNode.data.isFolder && (node !== sourceNode.parent) ) {
							return true;
						}
						return false;
			  },
			  onDrop: function(node, sourceNode, hitMode, ui, draggable) {
						// This function MUST be defined to enable dropping of items on the tree.
						sourceNode.move(node, hitMode);
						// Послали запрос на изменение
						$.post('/'+ctrl+'/editTree',{id: sourceNode.data.key, pid: node.data.key});
			  },
		}
};

// показываем данные
function getData(id) {

	//	возожны две таблицы
		$.ajax({
				url: '/'+ ctrl +'/records/' + id,
				type: "GET",
				dataType: "json",
				success: function(response) {

								$('#tab-rec').dataTable().fnClearTable();

								if( $('#tab-cont').length ) {
									$('#tab-cont').dataTable().fnClearTable();
								}

								$('#tab-rec').dataTable().fnAddData(response.aaData);
								if(response.records) {
									$('#tab-cont').dataTable().fnAddData(response.records);
								}
						},
				error: function(response) {

							var msg = response.responseText;
							if( $(msg).find('.form-alert').length ) {
								$(msg).modal( modInfo );
							}
						},
				});
}

/*
 *  Опции модального окна
 */
var modTree = {

		onShow: function(dialog){
			message: null;
			PidNode: null;
			ThisNode: null;
			closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>";

			$(':text, select').addClass('ui-widget-content ui-corner-all');

			$('#sb').button({ label: '<a href=#>Send</a>'});

			// Показе документа инициализирую функции
			$('#sb').click(function (e) {
					e.preventDefault();
					// С какими строками какой таблицы работаем
					RowID 	= $('form :hidden[name="id"]').val();
					in_root = 1 * $(':radio:checked[name="in_root"]').val(); // конвертируем в int
					pid	 	= $(':hidden[name="pid"]').val();
					tree	= $("#tree").dynatree("getTree");


					// Новая запись. Определяем ID родителя
					modTree.PidNode  = (in_root ) ? tree.getRoot() : tree.getNodeByKey( pid );
					modTree.ThisNode = tree.getNodeByKey( RowID );

					// Работа с запросом
					$.ajax ({
							url: '/'+ ctrl +'/editTree/',
							data: $('form').serialize(),
							type: 'post',
							dataType: 'json',
							success: function(str) {
										// Если у нас редактирование
										if( modTree.ThisNode ) {
											 modTree.ThisNode.setTitle(str.title);
										}
										else {// Добавляем значение к родителю
											 modTree.PidNode.addChild(str);
										}
										$.modal.close();
									},
							error: function(response) {
										$('.ui-state-error').empty().append(response.responseText).fadeIn('fast');
									},
					});
			})
		},

		showError: function () {
			$('#mesg').empty().append(modTree.message).closest('.ui-state-error').fadeIn('fast');
		},
};

/*
 *  Опции модального окна для новой записи
 */
var modNewTree = {

		onShow: function(dialog){
			PidNode: null;
			closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>";

			$(':text, select').addClass('ui-widget-content ui-corner-all');

			$('.date_field').datepicker({dateFormat:"yy-mm-dd"});

			$('#sb').button({ label: '<a href=#>Send</a>'});

			// Показе документа инициализирую функции
			$('#sb').click(function (e) {
				e.preventDefault();
				// С какими строками какой таблицы работаем
				PID 	= $('form :hidden[name="pid"]').val();
				var tree = $("#tree").dynatree("getTree");
				modNewTree.PidNode  = tree.getNodeByKey( PID );

				// Работа с запросом
				$.ajax ({
						url: '/'+ ctrl +'/addNewItem/',
						data: $('form').serialize(),
						type: 'post',
						dataType: 'json',
						success: function(response) {
									// Если у нас редактирование
									if( response ) {
										 modNewTree.PidNode.addChild(response);
										 tree.activateKey(response.key);
										 getData(response.key);
									}
									$.modal.close();
								},
						error: function(response) {
									$('.ui-state-error').empty().append(response.responseText).fadeIn('fast');
								},
				});

			})
		},

};

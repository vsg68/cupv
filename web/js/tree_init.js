
var ctrl = window.location.pathname.split('/')[1];

$(function(){

	$('#new, #del').unbind("click");




	$('#del').click(function(){

			var node = $("#tree").dynatree("getActiveNode");
			id = node.data.key;

			if( id && confirm('Удалятся так же все дочерние элементы.\n Удаляем?')) {
				//$("#tree").dynatree("getActiveNode").remove();
				$.post('/'+ctrl+'/add',{id:id,stat:2});
				node.remove();
				$('#ed').empty();
			}
	});

	//~ $('.delRow').live('click', function(){
//~
			//~ // последнюю не убираем
			//~ if( $(this).closest('tr').siblings().length > 1)
				//~ $(this).closest('tr').remove();
	//~ });

	//~ $('#submit_view').live('click', function(event){
//~
			//~ var params =  $('#usersform').serialize();
//~
			//~ $.post(	'/'+ ctrl +'/add/', params , function(response) {
//~
								//~ tmpl = /^\d+$/;
//~
								//~ if( tmpl.test(response) )
									//~ window.location = '/'+ ctrl +'/view/' + response;
								//~ else
									//~ $('#ed').empty().html(response);
							//~ });
			//~ return false;
	//~ });

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

			if( node.data.isFolder != true ) {
				alert('Выделите раздел');
				return false;
			}

			if( node.hasChildren() == true ) {
				alert('Раздел удалять нельзя, если там есть данные');
				return false;
			}
			if( ! confirm('Уверены, что надо стирать?') )
				return false;

			$.post('/' + ctrl + '/delEntry/' + node.data.key, {tab:'names'}, function(response){
					// Какую форму вернул запрос ?
					if( $(response).find('.form-alert').length ) {
							$(response).modal( modInfo );
					}
					else {
						node.remove();
					}
			});
	});


});


var id  = window.location.pathname.split('/')[3];
var oldPid;

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
		onPostInit: function(isReloading, isError) {
			//~ // All expand;
			//~ $("#tree").dynatree("getRoot").visit(function(node){
				//~ node.expand(true);
			//~ });
			//~ // select by key
			//~ $('#tree').dynatree("getTree").activateKey(id);


		},
		onActivate: function( node) {
			if( function_exists('blockButtons') ) {
					blockButtons(node);
			}
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
						$.post('/'+ctrl+'/edit',{id: sourceNode.data.key, pid: node.data.key});
			  },
		}
};

// показываем данные
function getData(id) {

	//	var node = $("#tree").dynatree("getTree").getNodeByKey(id);

		$.ajax({
				url: '/'+ ctrl +'/records/' + id,
				type: "GET",
				dataType: "json",
				success: function(response) {
								$('#tab-rec').dataTable().fnClearTable();
								$('#tab-rec').dataTable().fnAddData(response);
						},
				error: function(response) {

							var msg = response.responseText;
							if( $(msg).find('.form-alert').length ) {
								$(msg).modal( modInfo );
							}
						},
				});
}

function createItem(obj) {

		var tmpl = /^\d+$/;
		var tmpl_id;

		tmp = $(obj).attr('id').replace('x-','');

		if( tmpl.test(tmp) )  tmpl_id = tmp;

		$("#tree").dynatree("getRoot").addChild({"title":"new-node", "key":"00"});

		var node = $("#tree").dynatree("getTree").getNodeByKey('00');


		$.post('/'+ ctrl +'/add',{id:0, name:node.data.title, pid:0, tmpl_id:tmpl_id }, function(response){

						if( tmpl.test(response) ) {
							node.data.key = response;
							getData(node.data.key);
						}
						else {
							node.remove();
							alert('при сохранении произошла ошибка');
						}
		})
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

			$('#sb').button({label: 'Send'});

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
				//~ modTree.message = '';
				//~ modTree.message = validate_tree();
				//~ if (! modTree.message ) {
					// Работа с запросом
					$.ajax ({
							url: '/'+ ctrl +'/edit/',
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
				//~ }
				//~ else
					//~ modTree.showError();

			})
		},

		showError: function () {
			$('#mesg').empty().append(modTree.message).closest('.ui-state-error').fadeIn('fast');
		},

};

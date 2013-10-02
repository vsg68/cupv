
var ctrl = window.location.pathname.split('/')[1];

$(function(){

	$('#new, #del').unbind("click");


	var id  = window.location.pathname.split('/')[3];
	var oldPid;

	$('#tree').dynatree({

		initAjax: {
			url: "/"+ ctrl +"/getTree",
		},
		//~ onActivate: function(node) {
			//~ //alert(node.data.key);
			//~ //alert('parent:' + node.getParent().data.key + '\n self: ' + node.data.key);
		//~ },
		onRender: function(node, nodeSpan) {

			if( node.hasChildren() === true ) {

				$(nodeSpan).addClass('dynatree-ico-cf');
			}
		},
		onClick: function(node, event) {
			//~ if( event.shiftKey ){
				//~ editNode(node);
				//~ return false;
			//~ }
			// показываем данные
			getData(node.data.key);

		},
		onKeydown: function(node, event) {
			// [F2]
			if( event.which == 113) {
				editNode(node);
				return false;
			}
		},
		onPostInit: function(isReloading, isError) {
			// All expand;
			$("#tree").dynatree("getRoot").visit(function(node){
				node.expand(true);
			});
			// select by key
			$('#tree').dynatree("getTree").activateKey(id);
		},
		debugLevel: 0,
		dnd: {
			  onDragStart: function(node) {
					oldPid = node.getParent().data.key;
					return true;
			  },
			  onDragStop: function(node) {
					if( oldPid != node.getParent().data.key) {
						sendChange(node);
					}
			  },
			  autoExpandMS: 1000,
			  preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
			  onDragEnter: function(node, sourceNode) { return true; },
			  onDragOver: function(node, sourceNode, hitMode) {
					// Prevent dropping a parent below it's own child
					if(node.isDescendantOf(sourceNode)){
					  return false;
					}
					// Prohibit creating childs in non-folders (only sorting allowed)
					if( !node.data.isFolder && hitMode === "over" ){
					  return "after";
					}
			  },
			  onDrop: function(node, sourceNode, hitMode, ui, draggable) {
					// This function MUST be defined to enable dropping of items on the tree.
					sourceNode.move(node, hitMode);
			  },
		}
	});

	$('#del').click(function(){

		var node = $("#tree").dynatree("getActiveNode");
		id = node.data.key;

		if( id && confirm('Удалятся так же все дочерние элементы.\n Удаляем?')) {
			//$("#tree").dynatree("getActiveNode").remove();
			$.post('/'+ctrl+'/add',{id:id,stat:2});
			node.remove();
		}
	});

	// Menu
	$('#ddmenu li').hover(
		function () {
			 clearTimeout($.data(this,'timer'));
			 $('ul',this).stop(true,true).slideDown(200);
		},
		function () {
			$.data(this,'timer', setTimeout($.proxy(function() {
			  $('ul',this).stop(true,true).slideUp(200);
			}, this), 100));
		});


});

function editNode(node){

	  var prevTitle = node.data.title,
		  tree = node.tree;

	  // Disable dynatree mouse- and key handling
	  tree.$widget.unbind();

	  // Replace node with <input>
	  $(".dynatree-title", node.span).html("<input id='editNode' value='" + prevTitle + "'>");

	  // Focus <input> and bind keyboard handler
	  $("input#editNode")
			.focus()
			.keydown(function(event){
			  switch( event.which ) {
			  case 27: // [esc]
					// discard changes on [esc]
					$("input#editNode").val(prevTitle);
					$(this).blur();
					break;
			  case 13: // [enter]
					// simulate blur to accept new value
					$(this).blur();
					break;
			  }
		}).blur(function(event){
			  // Accept new value, when user leaves <input>
			  var title = $("input#editNode").val();
			  node.setTitle(title);
			  // Re-enable mouse and keyboard handlling
			  tree.$widget.bind();
			  node.focus();
		});


	$("input#editNode").change(function(){

		 if(prevTitle != node.data.title) {

				sendChange(node);
		 }
	});

}

function sendChange(node) {

	  tmpl = /^_/;
	  pid = node.getParent().data.key;

	  if( tmpl.test(pid) )
			pid = 0;

	  $.post('/'+ctrl+'/add',{
						  id: node.data.key,
						  name: node.data.title,
						  pid: pid
						  }
	  );
}
// показываем данные
function getData(id) {

			var node = $("#tree").dynatree("getTree").getNodeByKey(id);

			if(node.getChildren() === true)
				return false;

			$.get('/'+ ctrl + '/single/'+id,{'act':'1'},function(response){
								$('#ed').empty().append(response);
			});
}

function createItem(obj) {

		tmpl_id = $(obj).attr('id').replace('x-','');
		if(! tmpl_id ) (tmpl_id) = 0;

		$("#tree").dynatree("getRoot").addChild({"title":"new-node", "key":"00"});

		var node = $("#tree").dynatree("getTree").getNodeByKey('00');


		$.post('/'+ ctrl +'/add',{id:0, name:node.data.title, pid:0, tmpl_id:tmpl_id }, function(response){

						tmpl = /^\d+$/;

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

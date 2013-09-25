
$(function(){

	$('.else').live('click',function(){

		new_tr	= '<tr class="alias">'+
					'<td><input type="text" name="fname[]" value="" placeholder="host name"></td>'+
					'<td>'												+
						'<select name="ftype[]">' 						+
							'<option value="NS">NS'						+
							'<option value="A" selected>A'				+
							'<option value="MX">MX'						+
							'<option value="CNAME">CNAME'				+
						'</select>'										+
					  '</td>'											+
					  '<td><input type="text" name="faddr[]" placeholder="IP адрес (приоритет)"></td>'+
					'<td>'												+
						'<input type="hidden" name="stat[]" value="1">' +
						'<input type="hidden" name="fid[]" value="0">' 	+
						'<div class="delRow"></div>'					+
				'</td></tr>';

		$(this).closest('.atable').append(new_tr);


		return false;
	});


	$('#submit_view').live('click', function(event){

			// для записи SOA создаем контент
			faddr = $(':text[name="faddr[]"]').val() + ' ' + ($(':text[name="contact"]').val()).replace('@','.');
			$(':hidden[name="faddr[]"]').val(faddr);

			// пустые поля fname[] для записей NS должны получать значение zname для новой записи
			if( $(':text[name="zname"]').val() )
				$(':hidden[name="fname[]"]').val( $(':text[name="zname"]').val() );


			// проверка на совпадающие имена
			var _name = $(':text[name="zname"]').val();

			existName = $('tr')
							.filter('[sname="' + _name + '"]')
							.length;

			if( existName )
				$('input[name="zname"]').val('');

			try_submit();
			return false;

		});


	var oldPid;

	$('#tree').dynatree({
		initAjax: {
			url: "/badm/getTree"
		},
		onActivate: function(node) {
			//alert(node.data.key);
			//alert('parent:' + node.getParent().data.key + '\n self: ' + node.data.key);
		},
		onRender: function(node, nodeSpan) {

			if( node.hasChildren() === true ) {

				$(nodeSpan).addClass('dynatree-ico-cf');
			}
		},
		onClick: function(node, event) {
			if( event.shiftKey ){
				editNode(node);
				return false;
			}
		},
		onKeydown: function(node, event) {
			// [F2]
			if( event.which == 113) {
				editNode(node);
				return false;
			}
		},

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
				/** This function MUST be defined to enable dropping of items on
				 * the tree.
				 */
				sourceNode.move(node, hitMode);
				// expand the drop target
		//        sourceNode.expand(true);
//				alert(node.data.title);
			  },
		}
	});


	$('#new').unbind("click");

	$('#new span').click(function(){

		tmpl_id = $(this).attr('id');

		$("#tree").dynatree("getRoot").addChild({"title":"new-node", "key":"00"});

		var node = $("#tree").dynatree("getTree").getNodeByKey('00');

		$.post('/badm/add',{id:0, name:node.data.title, pid:0, tmpl_id:tmpl_id }, function(response){

						tmpl = /^\d+$/;

						if( tmpl.test(response) )
							node.data.key = response;
						else {
							node.remove();
							alert('при сохранении произошла ошибка');
						}
		})
	});


	$('#del').click(function(){

		var node = $("#tree").dynatree("getActiveNode");
		id = node.data.key;

		if( id && confirm('Удалятся так же все дочерние элементы.\n Удаляем?')) {
			//$("#tree").dynatree("getActiveNode").remove();
			$.post('/badm/add',{id: id,stat:2,name:'',pid:''});
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

	  $.post('/badm/add',{
						  id: node.data.key,
						  name: node.data.title,
						  pid: pid
						  }
	  );

}

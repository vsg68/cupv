
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

	$('.mkpwd').live('click', function(){

			$(this).siblings(':text').val(mkpasswd());
		})

	$('#tree').dynatree({
		initAjax: {
			url: "/badm/getTree"
		},
		onActivate: function(node) {
			//alert(node.data.key);
			alert('parent:' + node.getParent().data.key + '\n self: ' + node.data.key);
		},
		onRender: function(node, nodeSpan) {

			if( node.hasChildren() === true ) {

				$(nodeSpan).addClass('dynatree-ico-cf');//isFolder = true;
				//alert(nodeSpan.isFolder);
			}
		},

		dnd: {
			  onDragStart: function(node) {
					return true;
			  },
			  onDragStop: function(node) {
//					alert(node.getParent().data.key);

			  },
			  autoExpandMS: 1000,
			  preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
			  onDragEnter: function(node, sourceNode) {
				/** sourceNode may be null for non-dynatree droppables.
				 *  Return false to disallow dropping on node. In this case
				 *  onDragOver and onDragLeave are not called.
				 *  Return 'over', 'before, or 'after' to force a hitMode.
				 *  Return ['before', 'after'] to restrict available hitModes.
				 *  Any other return value will calc the hitMode from the cursor position.
				 */
				logMsg("tree.onDragEnter(%o, %o)", node, sourceNode);
				return true;
			  },
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
			  },
		}
	});

	$('#new_min').click(function(){

		$("#tree").dynatree("getRoot").addChild({"title":"new-node", "key":"00"});

		var node = $("#tree").dynatree("getTree").getNodeByKey('00');

		$.post('/badm/addNode','{"id":"0"}', function(responce){

						tmpl = /^\d+$/;

						if( tmpl.test(response) )
							node.data.key = response;
						else {
							node.remove();
							alert('при сохранении произошла ошибка');
						}

			})
	});


	$('#del_min').click(function(){

		$("#tree").dynatree("getActiveNode").remove();
	});

});

function saveChange() {

	//$.post('',)
}


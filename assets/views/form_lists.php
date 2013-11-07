   <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Users</title>
<!--
		<link rel="stylesheet" href="../css/humanity/jquery-ui-1.10.3.custom.min.css" type="text/css" />
-->
		<link rel="stylesheet" href="../css/smoothness/jquery-ui.min.css" type="text/css" />
		<link rel="stylesheet" href="../css/demo_table_jui.css" type="text/css" />
<!--
		<link rel="stylesheet" href="../css/TableTools.css" type="text/css" />
-->
		<link rel="stylesheet" href="../css/TableTools_JUI.css" type="text/css" />
		<link rel="stylesheet" href="../css/style.css" type="text/css" />
		<link rel="stylesheet" href="../css/skin/ui.dynatree.css" type="text/css" />
		<link rel="stylesheet" href="../css/smoothness/images.css" type="text/css" />
<!--
		<link rel="stylesheet" href="../css/basic.css" type="text/css" />
-->


		<script type="text/javascript" language="javascript" src="../js/jquery-1.9.1.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/TableTools.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.simplemodal.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.dynatree.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/init.js"></script>

  <script type="text/javascript">
    $(function(){

var optDT = {
				debugLevel: 0,
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
					  autoExpandMS: 1000,
					  preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
					}
	};

      $("#rest-grp, #have-grp").dynatree(optDT);
  });
  </script>
</head>

<div class='user-form ui-widget ui-corner-all box-shadow'>

<table>
	<tr>
		<td id='rest-grp'>
			<ul>
				<li id="id3.1">Sub-item 3.1</li>
                <li id="id3.1.1">Sub-item 3.1.1</li>
				<li id="id3.1.2">Sub-item 3.1.2</li>
				<li id="id3.2">Sub-item 3.2</li>
				<li id="id3.2.1">Sub-item 3.2.1</li>
				<li id="id3.2.2">Sub-item 3.2.2</li>
            </ul>
         </td>
		<td id='have-grp'>
			<ul>
				<li id="x1">Sub-item 3.1</li>
                <li id="x2">Sub-item 3.1.1</li>
				<li id="x3">Sub-item 3.1.2</li>
				<li id="x4">Sub-item 3.2</li>
				<li id="x5">Sub-item 3.2.1</li>
				<li id="x6">Sub-item 3.2.2</li>
            </ul>

		</td>
	</tr>
</table>



	<div class='submit'><div id='ok'></div></div>
</div>

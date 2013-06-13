<div class="editmenu">
	<div id='lb'>ООО "ГАЗМЕТАЛЛПРОЕКТ"</div>
	<div id='new'></div>
</div>
<div class='usrs'>
	<div id='aliasesplace'>
	<div class='th'>
		<div class='alias_th'>alias</div>
		<div class='mbox_th'>mailbox</div>
	</div>
	<div id='aliases_box'>
		<table class='some'>
		<?php foreach( $aliases_arr as $alias => $delivered ): ?>
		   <tr class="key">
			   <td><?=  $alias.":" ?></td>
			   <?php sort($delivered); ?>
			   <td><?= implode(', ', $delivered); ?></td>
		   </tr>
		<?php endforeach; ?>
		</table>
	</div>
	<div id='ed'>
<!--
	<table class='some1'>
					<tr><th>alias</th><th>on/off</th><th>?</th></tr>

			   <tr class="key">
				   <td><input type='text' name='alias_id[]' value='alias_name1'></td>
				   <td>
					   <input type='checkbox' name='alias_st[]' value=''>
				   </td>

			   </tr>
			   <tr class="key">
				   <td><input type='text' name='alias_id[]' value='alias_name2'></td>
				   <td>
					   <input type='checkbox' name='alias_st[]' value=''>
				   </td>

			   </tr>
			   <tr class="key">
				   <td><input type='text' name='alias_id[]' value='alias_name3'></td>
				   <td>
					   <input type='checkbox' name='alias_st[]' value=''>
				   </td>

			   </tr>
			   <tr class="key">
				   <td><input type='text' name='alias_id[]' value='alias_name'></td>
				   <td>
					   <input type='checkbox' name='alias_st[]' value=''>
				   </td>

			   </tr>

		</table>
-->
	</div>
<!--
	<select id='usrs' class='some' size=32>

			<option value='a' ><div class='key'>mailboxqqqq:</div><div class='value'>aaa</div></option>
			<option value='a' ><span class='key'>mailboxqqqq:</span><span class='value'>eeeeeeeeeeeee</span></option>
			<option value='a' ><span class='key'>mailboxqqqq</span><span class='value'>q222</span></option>
			<option value='a' ><span class='key'>mailbo:</span><span class='value'>1111</span></option>


	</select>
-->
</div>

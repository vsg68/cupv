<div class="editmenu">
	<div id='domains_flt'>Домен:
		<select>
			<option value='' selected></option>
			<?php foreach($domains as $domain):?>
				<option value='<?php echo $domain->domain_name;?>' ><?php echo $domain->domain_name; ?></option>
			<?php endforeach;?>
		</select>
	</div>
	<div class='lb_filter'> Mail (filter):
		<input type='text' id='fltr'>
	</div>
	<div id='new'></div>
</div>
<div class='usrs'>
	<div id='aliasesplace'>
		<div class='th'>
			<div class='alias_th'>alias</div>
			<div class='mbox_th'>mailbox</div>
		</div>
		<div id='aliases_box'>
			<table>
			<?php foreach( $aliases_arr as $alias => $delivered ): ?>
			   <tr>
				   <td class="key"><?=  $alias ?></td>
				   <td class="val"><?php  sort($delivered); echo implode(',<br>', $delivered); ?></td>
			   </tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>
	<div id='ed'>
some text
<!--
				<h4>vsg@tark.com</h4>
				<table class='atable'>
				   <tr><th>alias</th><th>on/off</th><th><button id='alias' class='else'>+</button></th></tr>
				   <tr class="key">
					   <td><input type='text' name='alias_id[]' value='alias_name1'></td>
					   <td><input type='checkbox' name='alias_st[]' value=''></td>
					   <td><button class='delRow  web'>r</button></td>
					</tr>
				   <tr class="key">
					   <td><input type='text' name='alias_id[]' value='alias_name1'></td>
					   <td><input type='checkbox' name='alias_st[]' value=''></td>
					   <td><button class='delRow  web'>r</button></td>
					</tr>
				   <tr class="key">
					   <td><input type='text' name='alias_id[]' value='alias_name1'></td>
					   <td><input type='checkbox' name='alias_st[]' value=''></td>
					   <td><button class='delRow  web'>r</button></td>
					</tr>
				   <tr class="key">
					   <td><input type='text' name='alias_id[]' value='alias_name1'></td>
					   <td><input type='checkbox' name='alias_st[]' value=''></td>
					   <td><button class='delRow  web'>r</button></td>
					</tr>

				</table>
-->
<!--
		</fieldset>
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

<div class="editmenu">
</div>
<div id='usrs'>
	<div class='aliasesplace'>
		<div>
			<div class='th'>Раздел</div>
			<div class='th'>Линк</div>
			<div class='th'>Доступ</div>
		</div>
		<div class='aliases_box'>
			<table>
			<?php foreach( $sections as $section ): ?>
			   <tr>
				   <td class="name"><input type='text' value='' /></td>
				   <td class="link"><input type='text' value='' /></td>
				   <td class="slevel">
					   <input type='radio' name='' value='0' />read
					   <input type='radio' name='' value='1' />write
					   <input type='radio' name='' value='2' />admin
				   </td>
			   </tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>
	<div class='aliasesplace'>
			<div class='th'>пользователи</div>
			<?php foreach( $users as $user ): ?>
			   <div id='<?= $user->login ?>'><?= $user->login ?> </div>
			<?php endforeach; ?>
	</div>
</div>
<div id='ed'>
	<?= $code_block ?>
</div>


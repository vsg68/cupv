	<div class="editmenu">
		<div id='new'></div>
	</div>
<div id='usrs'>
	<div class='aliasesplace'>
		<div>
			<div class='th'>Login</div>
			<div class='th'>Profile</div>
		</div>
		<div class='aliases_box'>
			<table>
			<?php foreach( $users as $user ): ?>
			   <tr sid='<?= $user->id ?>' sname='<?= $user->login ?>' >
				   <td class="key <?= $user->active == 0 ? 'nonactive':''; ?>"><?= $user->login ?></td>
				   <td class="val"><?= $user->role ?></td>
			   </tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>
<!--
<br />
	<h4>Используемые страницы</h4>
	<div class='aliasesplace'>
		<div>
			<div class='th'>Контроллер</div>
			<div class='th'>Название раздела</div>
		</div>
		<div class='domain_box'>
			<table>

			</table>
		</div>
	</div>
-->
</div>

<div id='ed'>
	<?= $auth_block; ?>
</div>


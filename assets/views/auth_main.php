	<div class="editmenu">
		<div id='home'></div>
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
</div>

<div id='ed'>
	<?= $auth_block; ?>
</div>


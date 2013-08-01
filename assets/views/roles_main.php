	<div class="editmenu">
		<div id='new'></div>
	</div>
<div id='usrs'>
	<h4>Роли доступа к страницам</h4>
	<div class='aliasesplace'>
		<div>
			<div class='th'> роль</div>
			<div class='th'>описание</div>
		</div>
		<div class='domain_box' id='local'>
			<table>
			<?php foreach( $roles as $role ): ?>
			   <tr sid="<?= $role->id ?>" dname='<?= $role->name ?>'>
				   <td class="key <?= $role->active == 0 ? 'nonactive':''; ?>"><?= $role->name ?></td>
				   <td class="val"><?= $role->notes ?></td>
			   </tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>

	<h4>Матрица ролей</h4>
	<div class='aliasesplace'>

	</div>
</div>
<div id='ed'>
	<?= $roles_block; ?>
</div>


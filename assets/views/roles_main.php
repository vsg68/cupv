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
			   <tr sid="<?= $role->id ?>" sname='<?= $role->name ?>'>
				   <td class="key <?= $role->active == 0 ? 'nonactive':''; ?>"><?= $role->name ?></td>
				   <td class="val"><?= $role->notes ?></td>
			   </tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>

	<h4>Матрица ролей</h4>
	<div class='aliasesplace'>
		<div class='matrix'>
			<table>
				<tr><td></td>
			<?php foreach($mroles as $mrole) : 	?>
				<td class='rotateText'>
						<svg><text x="-120" y="15" transform="rotate(270)"  class='somet'><?= $mrole ?></text></svg>
				</td>
			<?php endforeach; ?>
				</tr>
			<?php foreach($ctrls as $name=>$ctrl) : 	?>
				<tr>
					<td class='ctrl'><?= $name ?> </td>
				  <?php foreach( $ctrl as $key=>$val): ?>
						<td class='slevel'><?= $val ?> </td>
				  <?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>
</div>
<div id='ed'>
	<?= $roles_block; ?>
</div>


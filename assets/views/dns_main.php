<div class="editmenu">
	<div id='home' title='главная'></div>
	<div id='new' title='новая запись'></div>
</div>
<div id='usrs'>
	<div class='aliasesplace'>
		<div>
			<div class='th'>domain</div>
			<div class='th'>type</div>
		</div>
		<div class='aliases_box'>
			<table>
			<?php foreach( $domains as $domain ): ?>
			   <tr sid="<?= $domain->id ?>" sname="<?= $domain->name ?>" >
				   <td class="key"><?= $domain->name ?></td>
				   <td class="val"><?= $domain->master ?></td>
			   </tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>
</div>
<div id='ed'>
	<?= $dns_block ?>
</div>


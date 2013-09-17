<div class="editmenu">
	<div id='home' title='главная'></div>
	<div id='new' title='новая запись'></div>
	<div id='del' title='удалить запись'></div>
</div>
<div id='usrs'>
<h4>ggg</h4>
	<div class='aliasesplace'>
<!--
		<div class='th'>domain</div>
-->
		<div class='aliases_box'>
			<table>
			<?php foreach( $domains as $domain ): ?>
			   <tr sid="<?= $domain->id ?>" sname="<?= $domain->name ?>" >
				   <td class="key"><?= $domain->name ?></td>
			   </tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>
</div>
<div id='ed'>
	<?= $dns_block ?>
</div>


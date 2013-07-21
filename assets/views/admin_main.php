	<div class="editmenu">
		<div id='new'></div>
	</div>
<div id='usrs'>
	<h4>Разделы сайта</h4>
	<div class='aliasesplace'>
		<div>
			<div class='th'>Раздел</div>
			<div class='th'>Описание</div>
		</div>
		<div class='aliases_box'>
			<table>
			<?php foreach( $sections as $section ): ?>
			   <tr id="i-<?= $section->id ?>" >
				   <td class="key <?= $section->active == 0 ? 'nonactive':''; ?>"><?= $section->name ?></td>
				   <td class="val"><?= $section->note ?></td>
			   </tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>

</div>
<div id='ed'>
	<?= $sections_block; ?>
</div>


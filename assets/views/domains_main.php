	<div class="editmenu">
		<div id='new'></div>
	</div>
<div id='usrs'>
	<h4>Локальные домены</h4>
	<div class='aliasesplace'>
		<div>
			<div class='th'> домен</div>
			<div class='th'>описание</div>
		</div>
		<div class='domain_box' id='local'>
			<table>
			<?php foreach( $domains as $domain ): ?>
			<?php if( $domain->domain_type != '0')  continue; ?>
			   <tr id="i-<?= $domain->domain_id ?>" >
				   <td class="key <?= $domain->active == 0 ? 'nonactive':''; ?>"><?= $domain->domain_name ?></td>
				   <td class="val"><?= $domain->domain_notes ?></td>
			   </tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>
	<h4>Алиасы доменов</h4>
	<div class='aliasesplace noedit'>
		<div>
			<div class='th'>алиас</div>
			<div class='th'> домен</div>
		</div>
		<div class='domain_box'>
			<table>
			<?php foreach( $domains as $domain ): ?>
			<?php if( $domain->domain_type != '1')  continue; ?>
			   <tr class='noedit' >
				   <td class="key <?= $domain->active == 0 ? 'nonactive':''; ?>"><?= $domain->domain_name ?></td>
				   <td class="val"><?= $domain->delivery_to ?></td>
			   </tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>
		<h4>Транспорт</h4>
	<div class='aliasesplace'>
		<div>
			<div class='th'>домен</div>
			<div class='th'>транспорт</div>
		</div>
		<div class='domain_box' id='transport'>
			<table>
			<?php foreach( $domains as $domain ): ?>
			<?php if( $domain->domain_type != '2')  continue; ?>
			   <tr id="i-<?= $domain->domain_id ?>">
				   <td class="key <?= $domain->active == 0 ? 'nonactive':''; ?>"><?= $domain->domain_name ?></td>
				   <td class="val"><?= $domain->delivery_to ?></td>
			   </tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>
</div>
<div id='ed'>
	<?= $domains_block; ?>
</div>


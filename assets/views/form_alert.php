<div class='user-form ui-widget ui-corner-all box-shadow'>
	<?php if( isset($errorMsg) ): ?>
	<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error:</strong></p>
		<div class='alert-msg'>
			<div class='form-alert'><?=$errorMsg ?></div>
		</div>
	<?php else: ?>
		<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span><strong>Info:</strong>Эти адреса при необходимости нужно удалить вручную! </p>
		<?php foreach( $aliases as $alias ): ?>
			<div class='form-alert'> Алиас <strong><?= $alias->alias_name ?></strong> переправляется на адрес <strong><?= $alias->delivery_to?></strong> </div>
		<?php endforeach;
	 endif; ?>

		<div class='submit'><div id='ok'></div></div>
	</div>

</div>



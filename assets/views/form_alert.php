<div class='user-form ui-widget ui-corner-all box-shadow'>
	<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				Эти адреса при необходимости нужно удалить вручную!</p>
	</div>

	<div class='alert-msg'>
   <?php foreach( $aliases as $alias ): ?>
		<div class='form-alert'> Алиас <strong><?= $alias->alias_name ?></strong> переправляется на адрес <strong><?= $alias->delivery_to?></strong> </div>
	<?php endforeach; ?>

	</div>
	<div class='submit'><div id='ok'></div></div>
</div>



<div class="content-center">

		<div class='vertical-80'>
			<div class='gorizont-50'>
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="tab-rec">

					<thead>
						<tr>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</thead>
				</table>
			</div>
			<?php if( $ctrl == 'bcont'): ?>
			<div class='gorizont-50'>
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="tab-cont">
					<thead>
						<tr>
							<th>Контакт</th>
							<th>Должность</th>
							<th>Телефон</th>
							<th>Email</th>
						</tr>
					</thead>
				</table>
			</div>
			<?php endif ?>
		</div>
</div>
		<div class='vertical-20 '>
			<div class="dataTables_wrapper">
				<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix">
					<div class="DTTT_container ui-buttonset ui-buttonset-multi">
						<a class="DTTT_button ui-button ui-state-default DTTT_button_edit DTTT_disabled" id="tab-tree_0"><span>.</span></a>
						<a class="DTTT_button ui-button ui-state-default DTTT_button_foldernew" id="tab-tree_1"><span>.</span></a>
						<a class="DTTT_button ui-button ui-state-default DTTT_button_del DTTT_disabled" id="tab-tree_2"><span>.</span></a>
						<a class="DTTT_button ui-button ui-state-default DTTT_button_new" id="tab-tree_3"><span>.</span></a>
						<a class="DTTT_button ui-button ui-state-default DTTT_label  DTTT_disabled" id="tab-tree_4"><span></span></a>
					</div>
				</div>

				<div id="tree"></div>
				<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
					<div class="dataTables_info" id="tab-tree_info"></div>
				</div>
			</div>
		</div>



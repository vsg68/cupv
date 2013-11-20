<div class="editmenu">
		<form id='filterform' action='#' >
		<div class='filtermenu'>
			<input class='date_field' type='text' name='start_date' value='<?= date("Y-m-d"); ?>' />
			<select name='start_time'>
				<?php
						for($i=0; $i< 24; $i++) {
							foreach( array(0,3) as $min) {
								$time = (($i<10) ? '0'.$i : $i).':'.$min.'0';
								echo "<option ".($time == '00:00' ? 'selected': '').">". $time ."</option>";
							}
						}

				?>
			</select> Start &nbsp;
			<input class='date_field' type='text' name='stop_date' value='<?= date("Y-m-d"); ?>' />
			<select name='stop_time'>
				<?php
						$time_now = date('H:i');
						$install = 0;
						for($i=0; $i< 24; $i++) {
							$h = ($i < 10) ? '0'.$i : $i;
							foreach( array('00','30') as $m) {
								$time = $h.':'.$m;
								$selected = '';
								if($time >= $time_now && $install == 0) {
									$selected = 'selected';
									$install = 1;
								}
								echo "<option value='".$time."' ".$selected.">". $time ."</option>";
							}
						}
				?>
			</select> Stop &nbsp;
			<input class='dir' type='radio' name='direction' value='0' /> To
			<input class='dir' type='radio' name='direction' value='1' checked /> From
			<input id='fltr' type='text' name='fltr' value='' placeholder='name@domain' /> Фильтр &nbsp;

			<select name='server'>
				<option value='' selected></option>
				<option value='mail'>mail</option>
				<option value='relay'>relay</option>
			</select> Сервер &nbsp;

		</div>
<!--
		<div class='Search_active'></div>
-->
		</form>
	</div>
	<div id="demo">
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="tab">
			<thead>
				<tr>
					<th>Date</th>
					<th>Process</th>
					<th>msg ID</th>
					<th>msg</th>
				</tr>
			</thead>
		</table>
	</div>

<div id="errmsg" class='user-form ui-widget ui-corner-all box-shadow hidden'>
	<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error:</strong></p>
		<div class='alert-msg'>
			<div class='form-alert'></div>
		</div>
		<div class='submit'><div id='ok'></div></div>
	</div>
</div>
<div class='loader hidden'></div>


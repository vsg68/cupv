	<div class="editmenu">
		<form id='filterform' action='#' method='post' >
		<div class='filtermenu'>
			<input class='date_field' type='text' name='start_date' value='<?= date("Y-m-d"); ?>' />
			<select name='start_time' class='time_field'>
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
			<select name='stop_time' class='time_field'>
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

			<select name='server' class='time_field'>
				<option value='' selected></option>
				<option value='mail'>mail</option>
				<option value='relay'>relay</option>
			</select> Сервер &nbsp;

		</div>
		<div id='submit_filter'><img src='/loader.gif' class='hidden' border=0></div>
		</form>
	</div>

	<div id='logplace'>

	</div>


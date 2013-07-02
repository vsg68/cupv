	<div class="editmenu">
		<div class='filtermenu'>
			<input class='date_field' type='text' name='start_date' value='<?= date("d.m.Y"); ?>' />
			<select name='time_start' class='time_field'>
				<?php
						for($i=0; $i< 24; $i++) {
							foreach( array(0,3) as $min) {
								$time = (($i<10) ? '0'.$i : $i).':'.$min.'0';
								echo "<option ".($time == '00:00' ? 'selected': '').">". $time ."</option>";
							}
						}
				?>
			</select> Start &nbsp;
			<input class='date_field' type='text' name='stop_date' value='<?= date("d.m.Y"); ?>' />
			<select name='time_stop' class='time_field'>
				<?php
						for($i=0; $i< 24; $i++) {
							foreach( array(0,3) as $min) {
								$time = (($i<10) ? '0'.$i : $i).':'.$min.'0';
								echo "<option ".($time == '00:00' ? 'selected': '').">". $time ."</option>";
							}
						}
				?>
			</select> Stop &nbsp;
			<select name='servers' class='time_field'>
			<option value='' selected></option>
			<option value='mail'>mail</option>
			<option value='relay'>relay</option>
			</select> Сервер &nbsp;
			<input id='fltr' type='text' name='fltr' value='' /> Фильтр
		</div>
		<div id='onoff'><img src='/loader.gif' class='hidden' border=0></div>
	</div>
	<div id='logplace'>
	</div>


<div id='log'><?= $log ?></div>

<form id='usersform' action='#' method='post'>
	<h4><input type='text' class='autocomp' name='newalias' value='' placeholder='введите новый адрес'></h4>
	<table class='atable'>
	   <tr><th>mailbox</th><th>on/off</th><th><button class='else'>+</button></th></tr>
	   <tr class="alias">
		   <td><input type='text' class='autocomp' name='fwd[]' value='' placeholder='введите почтовый адрес'></td>
		   <td>
				<input type='checkbox' name='chk' checked>
				<input type='hidden' name='fwd_st[]' value='1'>
				<input type='hidden' name='fwd_id[]' value='0'>
			</td>
		   <td><span class='delRow web'>a</span></td>
		</tr>
	</table>
	<div class='submit'><input type='submit' id='submit_view' value='Изменить'></div>
</form>
<script type="text/javascript">$('.autocomp').autocomplete(options);</script>

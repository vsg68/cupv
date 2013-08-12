<div id='log'><?= $log ?></div>
<h4>Ввод нового алиаса.</h4>
<form id='usersform' action='#' method='post'>
	<div class='fieldentry'>
		<span class='formlabel'>Алиас:</span>
			<input type='text' class='autocomp formtext' name='alias_name' value='' placeholder='alias@domain.name'>
	</div>
	<br />
	<table class='atable'>
	   <tr><th>mailbox</th><th>on/off</th><th class='else'><div title='Добавить'></div></th></tr>
	   <tr class="alias">
		   <td><input type='text' class='autocomp' name='fname[]' value='' placeholder='mailbox@domain.name'></td>
		   <td>
				<input type='checkbox' name='chk' checked>
				<input type='hidden' name='stat[]' value='1'>
				<input type='hidden' name='fid[]' value='0'>
			</td>
		   <td><div class='delRow' title='удалить'></div></td>
		</tr>
	</table>
	<div class='submit'><input type='submit' id='submit_view' value='Изменить'></div>
</form>
<script type="text/javascript">$('.autocomp').autocomplete( { serviceUrl:'/users/searchdomain/',type:'post'} );</script>

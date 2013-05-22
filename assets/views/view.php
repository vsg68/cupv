<style>
	.formlabel {
		width: 200px;
	}
	 .formtext input[type='text'] {
		width: 280px;
	}
	.formtext, .formlabel {
		float: left;
		height: 24px;
<!--
		vertical-align: bottom;
-->
	}	
	.user1 {
		border: 1px solid blue;
		width: 500px;
	}
	.fieldentry {
<!--
		border: 1px solid red;
-->
		position: relative;
		overflow: auto;
		margin: 2px 0 0 0;
	}
</style>	

    </head>
    <body>

<h1><?= $mainemail ?></h1>
	<form name='usersform' method='post'>
		<fieldset  class="user1">
        <legend>Пользователь</legend>
				<div class='fieldentry'>
			   		<div class='formlabel'>ФИО:</div>
					<div class='formtext'><input type='text' name='fio' /></div>
			   </div>	
			   <div class='fieldentry'>
					<div class='formlabel'>Логин:</div>
					<div class='formtext'><input type='text' name='login' /></div>
			   </div>
			   <div class='fieldentry'>
					<div class='formlabel'>Основной домен:</div>
					<div class='formtext'>
						<input type='radio' name='domains' value='gmpro.ru' checked>gmpro.ru
						<input type='radio' name='domains' value='gmpro1.ru'>gmpro1.ru
						<input type='radio' name='domains' value='gmpro2.ru'>gmpro2.ru
					</div>
			   </div>				   
			   <div class='fieldentry'>
					<div class='formlabel'>Пароль:</div>
					<td class='formtext'><input type='text' name='passwd' /></div>
			   </div>	
			   <div class='fieldentry'>
					<div class='formlabel'>Путь:</div>
					<div class='formtext'><input type='text'  name='path' value='/var/tmp' /></div>
			   </div>
			   <div class='fieldentry'>
					<div class='formlabel'>Сеть:</div>
					<div class='formtext'><input type='text' name='nets' value='10/16, 192.168.0.5/32, 172.28.18.0/24' /></div>
			   </div>	
		</fieldset> 
<!--				
			   <tr>
					<td class='formlabel'>Путь:</td>
					<td class='formtext'><input type='text' name='fio' /></td>
			   </tr>	
			   <tr>
					<td class='formlabel'>Путь:</td>
					<td class='formtext'><input type='text' name='fio' /></td>
			   </tr>	
					
	   
                       <div><strong class='formlabel'>login: </strong></div>
					   <div><strong class='formlabel'>Пароль: </strong><input type='text' name='passwd' /></div>
					   <div><strong class='formlabel'>Путь: </strong><input type='text'  name='path' value='/var/tmp' /></div>
					   <div><strong class='formlabel'>Сеть: <strong><input type='text' name='nets' value='10/16, 192.168.0.5/32, 172.28.18.0/24'> </div>
					   <div><strong class='formlabel'>Основной домен: <strong>
								<input type='radio' size=40 name='domains' value='gmpro.ru'>gmpro.ru
								<input type='radio' name='domains' value='gmpro1.ru'>gmpro1.ru
								<input type='radio' name='domains' value='gmpro2.ru'>gmpro2.ru
						</div>
-->
					   <div><strong>Активность: </strong><input type='checkbox' name='activity' checked></div>
                   </fieldset>
               </td>
           </tr>
			<tr>
               <td>
                    <fieldset class="proto">
                       <legend>Протоколы</legend>
                       <div><strong>IMAP: </strong><input type='checkbox' name='imap' checked >&nbsp;&nbsp;<strong>POP3: </strong><input type='checkbox' name='pop3' checked></div>
                   </fieldset>
               </td>
           </tr>
           <tr>
               <td>
                    <fieldset class="alias">
                       <legend>Алиасы</legend>
                       <div><strong>alias: </strong><input type='text' name='alias[]' value='test' >&nbsp;&nbsp;<input type='checkbox' name='chb_alias[]' checked></div>
					   <div id='next'><a href='#' onclick='false'>Add</a></div>
                   </fieldset>
               </td>
           </tr>
           <tr>
               <td>
                    <fieldset class="forward">
                       <legend>Пересылка</legend>
                       <div><strong>alias: </strong><input type='text' name='forward[] value='test' >&nbsp;&nbsp;<input type='checkbox' name='chb_forward[]' checked></div>
					   <div id='next'><a href='#' onclick='false'>Add</a></div>
                   </fieldset>
               </td>
           </tr>
           
        </table>  
		<input type='submit' value='добавить'>
		</form>

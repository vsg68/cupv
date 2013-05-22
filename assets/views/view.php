<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Users</title>        
            <script type='text/javascript' language='JavaScript'  src='jquery-1.4.2.js' ></script>
			<script type="text/javascript" src='manager.js'></script>
<style>
	.formlabel {
		width: 200px;
		border: green solid 1px;
		}
	 .formtext input[type='text'] {
		width: 300px;
		}
	.user1 {
		border: 1px solid blue;
		width: 500px;
		}	
</style>	

    </head>
    <body>

<h1><?= $mainemail ?></h1>
	<form name='usersform' method='post'>
		<fieldset  class="user1">
        <legend>Пользователь</legend>
			<table >
			   <tr>
					<td class='formlabel'>ФИО:</td>
					<td class='formtext'><input type='text' name='fio' /></td>
			   </tr>	
			   <tr>
					<td class='formlabel'>Логин:</td>
					<td class='formtext'><input type='text' name='login' /></td>
			   </tr>	
			   <tr>
					<td class='formlabel'>Пароль:</td>
					<td class='formtext'><input type='text' name='passwd' /></td>
			   </tr>	
			   <tr>
					<td class='formlabel'>Основной домен:</td>
					<td class='formtext'>
						<input type='radio' name='domains' value='gmpro.ru'>gmpro.ru
						<input type='radio' name='domains' value='gmpro1.ru'>gmpro1.ru
						<input type='radio' name='domains' value='gmpro2.ru'>gmpro2.ru
					</td>
			   </tr>	
			   <tr>
					<td class='formlabel'>Путь:</td>
					<td class='formtext'><input type='text'  name='path' value='/var/tmp' /></td>
			   </tr>
			   <tr>
					<td class='formlabel'>Сеть:</td>
					<td class='formtext'><input type='text' name='nets' value='10/16, 192.168.0.5/32, 172.28.18.0/24' /></td>
			   </tr>	
		  </table>
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
</body>
</html>

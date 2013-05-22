<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>dropdowntreeview</title>        
        <link href='jquery.treeview.css' type='text/css' rel='stylesheet'/>
        <link href='jquery.treeview.dropdown.css' type='text/css' rel='stylesheet'/> <!--  Мой стиль DropDown-->
        <script type='text/javascript' language='JavaScript'  src='jquery-1.4.2.js' ></script>
<!--        <script type='text/javascript' language='JavaScript'  src='jquery.treeview.js' ></script>
        <script type='text/javascript' language='JavaScript'  src='jquery.treeview.edit.js' ></script>
        <script type='text/javascript' language='JavaScript'  src='jquery.treeview.dropdown.js' ></script> 
-->    </head>
    <body>
        <script type="text/javascript">
        $(function(){
            // $('#portion').dropdowntreeview({'form':'portion','n':'0'},{'oneclick':'true','height':'600px'},{'collapsed':true});
			
			$('#next').click(function(){
						$('#next').before("<div><strong>some: </strong><input class='portion' type='text' value='test' >&nbsp;&nbsp;<input type='checkbox' name='ch1_portion' checked></div> ");
			})
        })//$(function(){
         </script>

 <style type="text/css">
      body {
          background-color: #dde;
      }
  
   legend { 
       font-size:  small;
       background-color: #fff;
       border: 1px dimgrey outset;
       padding:3px;
       color: #555;
	   
   }
   fieldset {
       background-color: #eee;
       text-align:  center;
       color: navy;  
       padding:10px;       
       max-width: 1000px;      
   }
   pre {
       text-align: left;
       overflow: auto;
       max-height: 200px;
       max-width: 1000px;
   }
   input[type='text']  {
       margin: 10px 0;
       width: 200px;
	   disabled: true;
   }
   .one {
       background-color: #fff;
   }
   .one legend {
       background-color: #eee;
   }
   h1 {
       font-size: x-large;
       color: darkblue;
   }

  </style>     
  
         <div align="center">
             <h1>Пользаватели</h1>
			 <form name='usersform' method='post'>
        <table border='0' >
           <tr>
               <td text-align='right'>
                    <fieldset class="usr">
                       <legend>Пользователь</legend>
					   <div><strong>ФИО: </strong><input type='text' name='fio' /><?php $users->login; ?></div>
                       <div><strong>login: </strong><input type='text' name='login' /></div>
					   <div><strong>Пароль: </strong><input type='text' name='passwd' /></div>
					   <div><strong>Путь: </strong><input type='text'  name='path' value='/var/tmp' /></div>
					   <div><strong>Сеть: <strong><select name='nets[]' multiple size=1>
								<option>10/16</option>
								<option selected>192.168.0.5/32</option>
								<option>172.28.18.0/24</option>
						</select></div>
					   <div><strong>Домены: <strong><select name='domains' multiple size=1>
								<option>gmpro.ru</option>
								<option selected>gmpro1.ru</option>
								<option>gmpro2.ru</option>
						</select></div>

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
      </div>
    </body>
</html>

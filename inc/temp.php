<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>Отправка файла на сервер</title>
 </head>
 <body>
  <form enctype="multipart/form-data" method="post">
    <p><input type="hidden" name="chack" value='Был пост'>

   <p><input type="file" name="f">
   <input type="submit" value="Отправить"></p>
  </form> 
 </body>
</html>

<?php
print "Пост: ";
	print_r ($_POST);
print "<br />Файлы: ";
	print_r ($_FILES);

?>
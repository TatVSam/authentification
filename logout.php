<?
// Страница разавторизации 
// Удаляем куки
session_start();
session_destroy();
setcookie("id", "", time() - 3600*24*30*12, "/");
setcookie("hash", "", time() - 3600*24*30*12, "/",null,null,true);
// Переадресовываем браузер на страницу проверки нашего скрипта
header("Location: index.php"); exit; 

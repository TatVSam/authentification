<?php
 
define('HOST_NAME', 'localhost');
define('DB_NAME', 'reg_auth');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
//адрес, по которому приложение зарегистрировано VK. Папку проекта следует назвать reg_auth и поместить в папку localhost
define('SITE_URL', 'http://localhost/reg_auth'); 



$link=mysqli_connect(HOST_NAME, DB_USER, DB_PASSWORD, DB_NAME);
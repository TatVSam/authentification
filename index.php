<?
session_start();
include 'config.php';
require_once('vendor/autoload.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('auth_logger');
$logger->pushHandler(new StreamHandler('troubles.log', Logger::DEBUG));

//Для ВК
$clientId     = '51518697';
$clientSecret = 'AlyBHjC0AdYOxrTm35Zs'; 
$redirectUri  = 'http://localhost/reg_auth/oauth.php';


$params = array(
	'client_id'     => $clientId,
	'redirect_uri'  => $redirectUri,
	'response_type' => 'code',
	'v'             => '5.126', // версия API https://vk.com/dev/versions
	'scope'         => 'photos,offline',
);


// Страница авторизации 
// Функция для генерации случайной строки

function generateCode($length=6) {
  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
  $code = "";
  $clen = strlen($chars) - 1;
  while (strlen($code) < $length) {
          $code .= $chars[mt_rand(0,$clen)];
  }
  return $code;
} 


if(isset($_POST['submit'])) {
if($_POST["token"] == $_SESSION["CSRF"])
{
    // Вытаскиваем из БД запись, у которой логин равняется введенному
    $query = mysqli_query($link,"SELECT user_id, user_password FROM users WHERE user_login='".mysqli_real_escape_string($link,$_POST['login'])."' LIMIT 1");
    $data = mysqli_fetch_assoc($query); 
    // Сравниваем пароли
    if($data['user_password'] === md5(md5($_POST['password'])))
    {
        // Генерируем случайное число и шифруем его
        $hash = md5(generateCode(10));
 
  
        // Записываем в БД новый хеш авторизации
        mysqli_query($link, "UPDATE users SET user_hash='".$hash."' WHERE user_id='".$data['user_id']."'"); 
        // Ставим куки
        setcookie("id", $data['user_id'], time()+60*60*24*30, "/");
        setcookie("hash", $hash, time()+60*60*24*30, "/", null, null, true); // httponly !!! 
        // Переадресовываем браузер на страницу проверки нашего скрипта
        header("Location: check.php"); exit();
        
        }
    else
    {
        $no_auth = "Вы ввели неправильный логин/пароль";
        //Записываем неудавшуюся попытку в логи
        $info_str = "Неправильно введены логин/пароль. Введенный логин: " . $_POST['login'];
        $logger->info($info_str);
    }
}
}
//Генерируем токен и сохраняем его в сессию
$token = hash('gost-crypto', random_int(0,999999));
$_SESSION["CSRF"] = $token;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css"> 
    
</head>
<body>


    <div class="form-login">
        <form method="POST">

          <div class="form-group">
            <label for="login"><b>Логин</b></label>
            <input name="login" class="form-control" type="text" placeholder="Введите логин" required>
          </div>

          <div class="form-group">
            <label for="password"><b>Пароль</b></label>
            <input name="password" class="form-control" type="password" placeholder="Введите пароль" required>
          </div>
          <input type="hidden" name="token" value="<?=$token?>">
      <hr>
      <p>
        <input name="submit" type="submit" class="btn btn-primary" value="Войти">
        <?php
            $href="http://oauth.vk.com/authorize?" . urldecode(http_build_query($params));
        ?>
         <a href=<?=$href?> class="btn btn-secondary ml-3">Авторизация через ВКонтакте</a>
      </p>
        <a href="register.php" class="btn btn-outline-primary">Зарегистрироваться</a>
        <?php
        if (!empty($no_auth)) { ?>
            <small class="form-text text-muted">
              <?= $no_auth ?>
            </small>
        <?php
        }
        ?>
      </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

   

</body>
</html>
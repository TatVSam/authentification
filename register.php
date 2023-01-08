<?
// Страница регистрации нового пользователя 
// Соединяемся с БД
include 'config.php';

if(isset($_POST['submit']))
{
    $err = [];
    // проверяем логин
    if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
    {
        $err[] = "Логин может состоять только из букв английского алфавита и цифр";
    } 
    if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
    {
        $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    } 
    if ($_POST['password'] <> $_POST['password_repeat'])
    {
        $err[] = "Пароли не совпадают!";
    } 

    if(strlen($_POST['password']) < 5)
    {
        $err[] = "Пароль содержит менее пяти символов!";
    } 
    // проверяем, не существует ли пользователя с таким именем
    $query = mysqli_query($link, "SELECT user_id FROM users WHERE user_login='".mysqli_real_escape_string($link, $_POST['login'])."'");
    
    if(mysqli_num_rows($query) > 0)
    {
        $err[] = "Пользователь с таким логином уже существует в базе данных";
    } 
    // Если нет ошибок, то добавляем в БД нового пользователя
    if(count($err) == 0)
    {
        $login = $_POST['login'];
        // Убираем лишние пробелы и делаем хэширование
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
        mysqli_query($link,"INSERT INTO users SET user_login='".$login."', user_password='".$password."'");
        header("Location: index.php"); exit();
    }

}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Страница регистрации</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css"> 
    
</head>
<body>

<div class="form-login">
<form method="POST">
    <div class="form-group">
        <input name="login" class="form-control" type="text" placeholder="Логин" required>
    </div>
    <div class="form-group">
        <input name="password" class="form-control" type="password" placeholder="Пароль" required>
    </div>
    <div class="form-group">     
        <input name="password_repeat" class="form-control" type="password" placeholder="Повторите пароль" required>
    </div>      
    
    <hr>
      
    <input name="submit" class ="btn btn-primary" type="submit" value="Зарегистрироваться">
    <a href="index.php" class="btn btn-secondary ml-3">Войти</a>
    <!--<a href="index.php" class="btn btn-secondary ml-3">В галерею</a> -->
    
    <?php

        if (count($err) <> 0) { ?>
            <p>При регистрации произошли следующие ошибки:</p><br>
        <?php    
            foreach($err AS $error) {
        ?>
                <small class="form-text text-muted">
                    <?= $error ?>
                </small>
        <?php
            } 
        }
        ?>
      </form> 
         
    </div>
    
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

   

</body>
</html>
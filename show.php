<?php
session_start();

//Если не выполнен вход, перенаправляем на страницу авторизации
if (!$_SESSION['auth']) {
    header("Location: index.php");  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css"> 
    
</head>
<body>

<?php
if ($_SESSION['auth']) { ?>
    <div class="nav"> 
    <!--При авторизации отображаем приветствие и кнопку для выхода-->
<?php
//Для пользователя ВК отображаем иконку ВК
    if($_SESSION['is_vkUser']) {
   
?>
   
       <img src="images/vk_icon.png" class="icon" alt="vkicon">

    <?php
 }
     ?>
    <p class="welcome">Здравствуйте, <?=$_SESSION['user_login']?></p>
    <a href="logout.php"><button class="open-button btn btn-secondary" type="button">Выйти</button></a>         
</div>

<div class="container-content">
<div class="container">
    <div class="row">
        <div class="col-sm">
            <h1>SPA — это философия жизни</h1>
            <p>
            SPA научит Вас любить свое тело, свою внешность, изменит отношение к окружающему миру.
            SPA — это самые современные достижения медицины и косметологии: талассотерапия, грязе- и водолечение, массажи, турецкая баня, сауна, фито-ванны, паровая кабина, холодный каскадный душ и многое другое для Вашей души и тела.
          
            У нас работают более 300 профессиональных мастеров, которые обладают искусством установления духовной связи с гостем и тонко чувствуют его индивидуальные особенности, 
            т.к. массаж — это в первую очередь духовная практика, тесно связанная с буддистским учением. Сеанс массажа в буддизме понимается как добровольное принесение добра.
           
            Наш салон предоставляет уникальную возможность поделиться добром с близкими, подарить им здоровье, красоту и молодость с помощью подарочных сертификатов, которые действуют в любом салоне независимо от места приобретения, если даже любимый вами человек находится в другом городе.     
            </p>
        </div>
       
        <div class="col-sm">

      <?php
         
    //картинку показываем только пользователям vk
    if($_SESSION['is_vkUser']) {
   
      ?>
        <div class="img-container">
          <img src="images/candles.jpg" class="candles" alt="candles">
        </div>
       <?php
    }
        ?>
        </div>
    </div>
</div>

</div>   
<?php

}
?>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

   

</body>
</html>

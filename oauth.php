<?php

session_start(); 
$clientId     = '51518697';
$clientSecret = 'AlyBHjC0AdYOxrTm35Zs'; 
$redirectUri  = 'http://localhost/reg_auth/oauth.php';


//если от ВК вернулся code, получаем токен
if (isset($_GET['code'])) {

    $params = array(
        'client_id'     => $clientId,
        'client_secret' => $clientSecret,
        'code'          => $_GET['code'],
        'redirect_uri'  => $redirectUri
    );

    if (!$content = @file_get_contents('https://oauth.vk.com/access_token?' . urldecode(http_build_query($params)))) {
        $error = error_get_last();
        throw new Exception('HTTP request failed. Error: ' . $error['message']);
    }

    $response = json_decode($content);


    // Если при получении токена произошла ошибка
    if (isset($response->error)) {
        throw new Exception('При получении токена произошла ошибка. Error: ' . $response->error . '. Error description: ' . $response->error_description);
    } else {
        //А вот здесь выполняем код, если все прошло хорошо
        $token = $response->access_token; // Токен
        $expiresIn = $response->expires_in; // Время жизни токена
        $userId = $response->user_id; // ID авторизовавшегося пользователя

        // Сохраняем токен в сессии
        $_SESSION['token'] = $token;

    }

    if (isset($_SESSION['token'])) {
        $params = array(
            'uids'=> $userId,
            'v'             => '5.126',
            'fields' => 'id,first_name,last_name,screen_name,sex,bdate,photo_big',
            'access_token'=> $_SESSION['token']
        );


        $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);

        if (isset($userInfo['response'][0]['id'])) {
            $userInfo = $userInfo['response'][0];
            $result = true;
        }
    }

    if ($result) {

        $_SESSION['user_login'] = $userInfo['first_name'] . " " . $userInfo['last_name']; 
        $_SESSION['auth'] = true;
        $_SESSION['user_id'] = $userId;
        $_SESSION['is_vkUser'] = true;
        header("Location: show.php"); 
    }
}
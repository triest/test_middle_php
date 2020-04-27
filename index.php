<?php
    /**
     * Created by PhpStorm.
     * User: triest
     * Date: 27.04.2020
     * Time: 14:35
     */

// включим отображение всех ошибок
    error_reporting(E_ALL);
// подключаем конфиг
    include('config.php');

// Соединяемся с БД
// $dbObject = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);

// подключаем ядро сайта
    include(SITE_PATH . DS . 'core' . DS . 'core.php');

    /*
     * создаем гобальную переменную
     * */
    global $mysqli;
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    global $userauth;

    $router = new Router();

    $router->setPath(SITE_PATH . 'controllers');

    $router->route();

    //парсим адрес, как в Yii2 (controller/action)

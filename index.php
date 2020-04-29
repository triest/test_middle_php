<?php
    /**
     * Created by PhpStorm.
     * User: triest
     * Date: 27.04.2020
     * Time: 14:35
     */

    /**
     * generate CSRF token
     *
     * @author  Joe Sexton <joe@webtipblog.com>
     * @param   string $formName
     * @return  string
     */
    function generateToken($formName)
    {
        $secretKey = 'gsfhs154aergz2#';
        if (!session_id()) {
            session_start();
        }
        $sessionId = session_id();

        return sha1($formName . $sessionId . $secretKey);

    }

// включим отображение всех ошибок
    error_reporting(E_ALL);
// подключаем конфиг
    include('config.php');

// Соединяемся с БД
    ob_start();
    session_start([
            'read_and_close' => true
    ]);

    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = base64_encode(openssl_random_pseudo_bytes(32));
    }

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

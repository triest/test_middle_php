<?php
    /**
     * Created by PhpStorm.
     * User: triest
     * Date: 27.04.2020
     * Time: 14:41
     */

    class Router
    {
        public $path = "/controllers";

        public $controller = "";

        public $action;

        public $params = [];

        private $file = "";

        private $args = "";

        private $registry;


        // задаем путь до папки с контроллерами
        function setPath($path)
        {
            $path = trim($path, '/\\');
            $path .= DS;
            // если путь не существует, сигнализируем об этом
            if (is_dir($path) == false) {
                throw new Exception ('Invalid controller path: `' . $path . '`');
            }
            $this->path = $path;
        }

        /*получаекм контроллер*/

        function getController()
        {

            $route = (empty($_GET['route'])) ? '' : $_GET['route'];
            unset($_GET['route']);
            if (empty($route)) {
                $route = 'index';
            }

            $route = trim($route, '/\\');
            $parts = explode('/', $route);

            // Находим контроллер
            $cmd_path = $this->path;
            foreach ($parts as $part) {
                $fullpath = $cmd_path . $part;

                // Проверка существования папки
                if (is_dir($fullpath)) {
                    $cmd_path .= $part . DS;
                    array_shift($parts);
                    continue;
                }

                if (is_file($fullpath . '.php')) {
                    $controller = $part;
                    array_shift($parts);
                    break;
                }
            }

            if (empty($controller)) {
                $this->controller = 'index';
            } else {
                $this->controller = $controller;
            }

            if(isset($_GET["action"])){
                $action=$_GET["action"];
            }else{
                $action="index";
            }

        //    $action = array_shift($parts);
            if (empty($action)) {
                $this->action = 'index';
            }else{
                $this->action=$action;
            }

            $this->file = $cmd_path . $this->controller . '.php';
            $this->args = $parts;
        }

        function route()
        {
            //
            $this->getController();

            if (is_readable($this->file) == false) {
                die ('404 Not Found');
            }

            include($this->file);

            foreach (glob("models/*.php") as $filename)
            {
                include $filename;
            }

            $class = 'Controller_' . $this->controller;


            $controller = new $class($this->registry);

            $action = $this->action;

            // Выполняем экшен
            $controller->$action();
        }


    }
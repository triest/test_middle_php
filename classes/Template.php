<?php
    /**
     * Created by PhpStorm.
     * User: triest
     * Date: 27.04.2020
     * Time: 15:45
     */

    class Template
    {
        private $controller;
        private $layouts;
        private $vars = array();

        function __construct($layouts, $controllerName) {
            $this->layouts = $layouts;
            $arr = explode('_', $controllerName);
            $this->controller = strtolower($arr[1]);
        }

        // установка переменных, для отображения
        function vars($varname, $value) {
            if (isset($this->vars[$varname]) == true) {
                return false;
            }
            $this->vars[$varname] = $value;
            return true;
        }

        // отображение
        function view($name) {
            $pathLayout = SITE_PATH . 'views' . DS . 'layouts' . DS . $this->layouts . '.php';
            $contentPage = SITE_PATH . 'views' . DS . $this->controller . DS . $name . '.php';

            if (file_exists($pathLayout) == false) {
                trigger_error ('Layout `' . $this->layouts . '` does not exist.', E_USER_NOTICE);
                return false;
            }

            if (file_exists($contentPage) == false) {
                trigger_error ('Template `' . $name . '` does not exist.', E_USER_NOTICE);
                return false;
            }

            foreach ($this->vars as $key => $value) {
                $$key = $value;
            }

            include ($pathLayout);
        }

    }
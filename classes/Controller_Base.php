<?php
    /**
     * Created by PhpStorm.
     * User: triest
     * Date: 27.04.2020
     * Time: 15:44
     */

  abstract  class Controller_Base
    {

      protected $registry;
      protected $template;
      protected $layouts; // шаблон

      public $vars = array();

      // в конструкторе подключаем шаблоны
      function __construct($registry) {
          $this->registry = $registry;
          // шаблоны
          $this->template = new Template($this->layouts, get_class($this));
      }

    }
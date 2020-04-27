<?php
    /**
     * Created by PhpStorm.
     * User: triest
     * Date: 27.04.2020
     * Time: 15:26
     */

    class  Controller_Index extends Controller_Base
    {

        public $layouts = "first_layouts";

        public function index()
        {

            $this->template->view('index');
        }
    }
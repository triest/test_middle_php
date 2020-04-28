<?php
    /**
     * Created by PhpStorm.
     * User: triest
     * Date: 27.04.2020
     * Time: 16:04
     */


    class Controller_Auch extends Controller_Base
    {
        public $layouts = "first_layouts";

        public function index()
        {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $email = $_POST["email"];
                $password = $_POST["password"];
                $user = new Model_Users();

                if ($user->login($email, $password)) {
                    header("Location: /");
                } else {
                    $this->template->vars('error', true);
                    $this->template->view('login');
                }
            } else {
                //    $this->template->vars('error', false);
                $this->template->view('login');
            }
        }

        public function login()
        {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $email = $_POST["email"];
                $password = $_POST["password"];
                $user = new Model_Users();

                if ($user->login($email, $password)) {
                    header("Location: /");
                } else {
                    $this->template->vars('error', true);
                    $this->template->view('login');
                }
            } else {
                $this->template->view('login');
            }
        }


        public function logout()
        {

            $_SESSION['auth_user'] = "";

            header("Location: /");
        }


    }
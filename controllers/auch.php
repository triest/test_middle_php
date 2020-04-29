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

                if (!isset($_POST["email"]) || $_POST["email"] == "" || !isset($_POST["password"])) {
                    $this->template->vars('error', "login and password requared");
                    $this->template->view('login');
                    return;
                }

                $email = $_POST["email"];
                $password = $_POST["password"];
                $user = new Model_Users();

                $login = $user->login($email, $password);
                if ($login) {
                    header("Location: /");
                } else {
                    $this->template->vars('error', "wrong login or password");
                    $this->template->view('login');
                }
            } else {
                $this->template->view('login');
            }
        }

        public function login()
        {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $email = $_POST["email"];
                $password = $_POST["password"];
                $user = new Model_Users();
                $login = $user->login($email, $password);

                if ($login) {
                    header("Location: /");
                } else {
                    $this->template->vars('error', "wrong login or password");
                    $this->template->view('login');
                }
            } else {
                $this->template->view('login');
            }
        }


        public function logout()
        {
            session_start();
            $_SESSION['auth_user'] = "";
            session_write_close();

            header("Location: /");
        }


    }
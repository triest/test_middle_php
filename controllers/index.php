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

        function checkToken($token, $formName)
        {
            return $token === generateToken($formName);
        }

        public function index()
        {
            /*
             * полчучить баланс
             * */
            $user = Model_Users::auth();
            if ($user == null) {
                header("Location: /auch");
            }

            $result = null;

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                if (!isset($_POST["csrf_token"]) || !$this->checkToken($_POST["csrf_token"], "protectedForm")) {
                    header("Location: /");
                }

                if (!isset($_POST["id"]) || $_POST['id'] != $user->id) {
                    header("Location: /");
                }

                if (!isset($_POST["money"])) {
                    header("Location: /");
                }
                $money = $_POST["money"];
                $result = $user->write_off($money);
            }
            $balance = $user->getMoney();
            $this->template->vars('transaction', $result);
            $this->template->vars('balance', $balance);
            $this->template->vars('id', $user->id);
            $this->template->view('index');
        }

        public function withdraw()
        {
            if (!isset($_POST["money"])) {
                header("Location: /");
            }
            $money = $_POST["money"];


            $user = Model_Users::auth();

            if ($user == null) {
                header("Location: /auch");
            }

            $rez = $user->write_off($money);

            if ($rez != true) {
                return $rez;
            } else {
                return true;
            }


        }


    }
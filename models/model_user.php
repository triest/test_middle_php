<?php
    /**
     * Created by PhpStorm.
     * User: triest
     * Date: 27.04.2020
     * Time: 16:12
     */

    class Model_Users
    {

        private $SECRET = "SLAdsKS:;/.,WEK:E";

        private $account;

        public $id;


        /**
         * Model_Users constructor.
         * @param string $SECRET
         */
        public function __construct()
        {

        }


        /**
         * @param $name
         * @return null|User
         */
        public function getUserByName($name)
        {
            global $mysqli;

            if ($stmt = $mysqli->prepare("select `id`,`name`,`password` from `users` where `name`=? limit 1")) {
                $stmt->bind_param('s', $name);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($result->num_rows > 0) {
                        $user = new Model_Users();
                        while ($row = $result->fetch_assoc()) {
                            $user->id = $row["id"];
                            $user->name = $row["name"];
                            $user->passowrd = $row["password"];
                        }
                        return $user;
                    }
                } else {
                    return null;
                }

            } else {
                $error = $mysqli->errno . ' ' . $mysqli->error;
                echo $error;
            }
        }


        /**
         * @param $name
         * @return null|User
         */
        public function getUserByEmail($name)
        {
            global $mysqli;

            if ($stmt = $mysqli->prepare("select `id`,`name`,`password` from `users` where `name`=? limit 1")) {
                $stmt->bind_param('s', $name);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result) {

                    if ($result->num_rows > 0) {

                        $user = new Model_Users();
                        while ($row = $result->fetch_assoc()) {
                            $user->id = $row["id"];
                            $user->name = $row["name"];
                            $user->password = $row["password"];
                        }

                        return $user;
                    }
                } else {
                    return null;
                }

            } else {
                $error = $mysqli->errno . ' ' . $mysqli->error;
                echo $error;
            }
        }

        /**
         * @param $login
         * @param $pass , вход на сайт
         * @return int
         */
        public function login($login, $pass)
        {
            /*
             *оздаем
             * */


            $user = $this->getUserByEmail($login);
            if ($user == null) {
                $user = $this->getUserByName($login);
                if ($user == null) {
                    return false;
                }
            }

            $pass = md5($pass);
            if ($login == $user->name && $pass == $user->password) {
                $_SESSION['auth_user'] = $login;
                return true;
            }
            return false;
        }


        public function getMoney()
        {
            global $mysqli;
            $login = $_SESSION['auth_user'];


            if ($login == null || $login == "") {
                header('Location: ' . '/auch?action=login');
            }

            global $mysqli;
            try {
                $mysqli->begin_transaction();
                if ($stmt = $mysqli->prepare("select account from `users` where `name`=? limit 1")) {
                    $stmt->bind_param('s', $login);
                    $stmt->execute();
                    $mysqli->commit();
                    $result = $stmt->get_result();

                    if ($result) {

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $money = $row["account"];
                                return $money;
                            }
                        }
                    } else {
                        return null;
                    }

                } else {
                    $error = $mysqli->errno . ' ' . $mysqli->error;
                    echo $error;
                }
            } catch (Exception $exception) {
                $mysqli->rollback();
                return null;
            }
            return null;
        }

        public static function auth()
        {
            $login = $_SESSION['auth_user'];

            $user = new Model_Users();

            $user = $user->getUserByName($login);

            return $user;
        }

        public function get_account()
        {

        }

        public function write_off($money)
        {

            $validate = $this->validate_money($money);
            // var_dump($validate);
            if (!is_bool($validate)) {
                return $validate;
            }

            $account = $this->getMoney();


            if ($money > $account) {
                return "less money";
            }

            $new_money = $account - $money;

            $this->write_balance($new_money);

            return true;
        }


        private function write_balance($money)
        {
            global $mysqli;
            try {
                $mysqli->begin_transaction();
                if ($stmt = $mysqli->prepare("update users set account=? where id=?")) {
                    $stmt->bind_param('dd', $money, $this->id);
                    $stmt->execute();
                    $mysqli->commit();
                }

            } catch (Exception $exception) {
                $mysqli->rollback();
            }

        }

        public function validate_money($money)
        {
            $money = floatval($money);

            if ($money == false) {
                return "false float";
            }

            /**/
            if (!is_numeric($money)) {
                return "is not numeric";
            }

            //check is positive
            if ($money < 0) {
                return "less zerro";
            }

            return true;

        }

    }
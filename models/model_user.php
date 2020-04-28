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

        public $token;

        public $password;

        public $name;

        /**
         * Model_Users constructor.
         * @param string $SECRET
         */
        public function __construct()
        {

        }


        /**
         * @param $name
         * @return null|Model_Users
         */
        public function getUserByName($name)
        {
            global $mysqli;

            if ($stmt = $mysqli->prepare("select `id`,`name`,`password`,`token` from `users` where `name`=? limit 1")) {
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
                //    echo $error;
                return $error;
            }
        }

        public function getUserByToken()
        {
            global $mysqli;


            if (!isset($_SESSION['auth_user']) || !isset($_SESSION['UID'])) {
                return false;
            }


            $id = $_SESSION['UID'];

            $token = $_SESSION['auth_user'];


            if ($stmt = $mysqli->prepare("select `id`,`name`,`password` from `users` where `token`=? and `id`=? limit 1")) {
                $stmt->bind_param('ss', $token, $id);
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
            global $mysqli;

            $user = $this->getUserByName($login);
            echo "user by name";

            if ($user == null) {
                return false;
            }


            $pass = md5($pass);


            if ($login == $user->name && strcasecmp($pass, $user->passowrd) == 0) {
                /*генерируес токен */


                $token = $user->token;
                if ($user->token == null || $user->token == "") {
                    $token = Model_Users::generateRandomString();
                    $user->token = $token;
                }


                try {
                    $mysqli->begin_transaction();
                    if ($stmt = $mysqli->prepare("update users set `token`=? where `name`=? limit 1")) {
                        $stmt->bind_param('ss', $token, $login);
                        $stmt->execute();
                        $mysqli->commit();
                    }
                    $_SESSION['auth_user'] = $user->token;
                    $_SESSION['UID'] = $user->id;

                    return true;
                } catch (Exception $exception) {
                    $mysqli->rollback();
                    return false;
                }

            } else {

                return false;
            }
        }

        public function getToken($login)
        {
            global $mysqli;
            $mysqli->begin_transaction();


        }


        public function getMoney()
        {
            global $mysqli;


            global $mysqli;
            try {
                $mysqli->begin_transaction();
                if ($stmt = $mysqli->prepare("select account from `users` where `id`=? limit 1")) {
                    $stmt->bind_param('i', $this->id);
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
            if (!isset($_SESSION['auth_user']) && $_SESSION['auth_user'] != "") {
                return false;
            }


            $user = new Model_Users();
            $user = $user->getUserByToken();

            return $user;
        }

        public function get_account()
        {

        }

        public function write_off($money)
        {

            $validate = $this->validate_money($money);
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

            $check = number_format($money, 2);

            if ($check != $money) {
                return "invalid decimal places";
            }

            if ($money == false) {
                return "false float";
            }

            /**/
            if (!is_numeric($money)) {
                return "is not numeric";
            }

            if ($money < 0) {
                return "less zerro";
            }

            return true;

        }

        public static function generateRandomString($length = 50)
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

    }
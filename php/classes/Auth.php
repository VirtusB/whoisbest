<?php
/**
 * Created by PhpStorm.
 * User: Focuz
 * Date: 27-12-2018
 * Time: 06:53
 */

class Auth {
    public static function Logout() {
        session_unset();
        session_destroy();
        header('Location: ' . CONFIG_LOGOUT_PAGE);
        exit;
    }

    public static function Update() {
        unset($_SESSION['steam_uptodate']);
        header('Location: ' . CONFIG_LOGIN_PAGE);
        exit;
    }

    public static function isLoggedIn() {
        if (isset($_SESSION['logged_in'])) {
            return $_SESSION['logged_in'];
        }
        return false;
    }

    public static function Login() {
        try {
            $openId = new LightOpenID(CONFIG_DOMAIN_NAME);

            if (!$openId->mode) {
                $openId->identity = 'https://steamcommunity.com/openid';
                header('Location: ' . $openId->authUrl());
            } elseif ($openId->mode === 'cancel') {
                echo 'User has canceled authentication';
            } else {
                if($openId->validate()) {
                    $id = $openId->identity;
                    $ptn = "/^https?:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
                    preg_match($ptn, $id, $matches);

                    $_SESSION['steamid'] = $matches[1];
                    $_SESSION['logged_in'] = true;


                    if (!headers_sent()) {
                        header('Location: ' . CONFIG_LOGIN_PAGE);
                        exit;
                    }

                    echo '<script type="text/javascript">window.location.href = ' . CONFIG_LOGIN_PAGE . ';</script>';
                    exit;

                }

                echo 'User is not logged in';
            }
        } catch (ErrorException $exception) {
            die('ERROR ON LINE: ' . __LINE__);
        }
    }
}
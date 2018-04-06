<?php

namespace App\Components;


/**
 * Class Auth
 * @package App\Components
 */
class Auth
{
    /**
     * @param $login
     * @return $this
     */
    public function login($login)
    {
        $_SESSION['name'] = $login;
        $_SESSION['auth'] = 'Success';

        return $this;
    }

    /**
     * @return $this
     */
    public function logout()
    {
        unset($_SESSION['auth']);
        unset($_SESSION['name']);
        unset($_SESSION['githubProfile']);

        return $this;
    }

    /**
     * @param $name
     */
    public function findGitHubProfile($name)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.github.com/users/$name");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $res = json_decode(curl_exec($curl), true);
        curl_close($curl);


        if (isset($res['html_url'])) {
            $_SESSION['githubProfile'] = $res['html_url'];
        }
    }
}
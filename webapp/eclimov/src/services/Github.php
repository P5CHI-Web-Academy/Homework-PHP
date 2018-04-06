<?php

namespace Service;

use \Core\Service;

class Github extends Service
{
    private $api_url;

    public function __construct()
    {
        $this->api_url = 'https://api.github.com/users/';
    }

    public function get_user(string $login)
    {
        if($api_result = $this->get_api_result($login)){
            if($result_processed = $this->process_api_result($api_result)){
                return array(
                    'name' => $result_processed['name'],
                    'url' => $result_processed['html_url']
                );
            }
        }
        return false;
    }

    public function get_api_result($login){
        $options = [
            CURLOPT_URL => $this->api_url.$login,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT=>'Test-App'
        ];
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function process_api_result($api_result){
        $result_decoded = json_decode($api_result, true);
        if(isset($result_decoded['message']) && $result_decoded['message'] === 'Not Found'){
            return false;
        }

        return $result_decoded;
    }
}
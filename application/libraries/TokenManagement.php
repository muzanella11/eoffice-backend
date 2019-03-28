<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TokenManagement {

    private $CI;

    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('enem_templates');
    }

    public function getNewToken()
    {
        $length = 110811;
        $token = $this->CI->enem_templates->get_random_string($length);
        
        return $token;
    }

    public function getTokenByTokenId ($token)
    {
        if ($token)
        {
            $this->CI->load->model('enem_user_model');
            $data_token = $this->CI->enem_user_model->getDataTokenUserManagementByToken($token); //get token

            $result = $data_token;        
        }
        else 
        {
            $result = [];    
        }

        return $result;
    }

    public function initToken($config)
    {
        if (is_array($config))
        {
            if ($config['token'] && $config['setting_expired'])
            {
                $this->saveToken($config['token']);
                $this->updateToken($config['token'], $config['setting_expired']);
            }
            else 
            {
                throw new Exception("Must have token and setting expired", 1);
            }
        }
        else 
        {
            throw new Exception("Config must be array", 1);
        }
    }

    public function saveToken($token)
    {
        $this->CI->load->model('enem_user_model');

        $database = array(
            'enem_token' => $token
        );

        $this->CI->enem_user_model->addEnemTokenUserManagement($database); //set token to database
    }

    public function updateToken($token, $settingExpired)
    {
        $data_token = $this->CI->enem_user_model->getDataTokenUserManagementByToken($token); //get token

        if ($settingExpired && is_array($settingExpired))
        {
            $setting_expired = $settingExpired;
        } 
        else 
        {
            // Kalo ga ada setting expired. Auto set
            $setting_expired = array(
                'timeby' => 'hours',
                'value' => 1, // di set 1 jam expired nya
            );
        }

        $token_expired = $this->CI->enem_templates->create_expired_time($data_token[0]->date_created, $setting_expired); // create token expired after 1 hours

        $data_expired = array(
            'enem_token' => $token,
            'enem_token_expired' => $token_expired
        );

        $this->CI->enem_user_model->updateEnemTokenExpired($data_expired); // update token expired
    }

}

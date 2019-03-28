<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RestManager.php';

class Auth extends RestManager {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

        $this->load->model('enem_user_model');
    }

    public function index_get()
    {
        $data = [
            'status' => 'Ok',
            'messages' => 'Hello guys :)'
        ];
        
        return $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function token_get()
    {
        $token = $this->getNewToken();

        $data = [
            'status' => $token ? 'Ok' : 'Problem',
            'token' => $token
        ];

        return $this->response($data, REST_Controller::HTTP_OK);
    }

    public function check_token_get()
    {
        $statusToken = $this->checkToken();

        $data = [
            'status' => 'Problem',
            'messages' => 'Unauthorize'
        ];

        if ($statusToken === 0)
        {
            $data = [
                'status' => 'Ok',
                // 'token' => $getTokenHeader,
                'messages' => $statusToken === 0 ? 'active' : 'not active',
                'tokenStatus' => $statusToken === 0 ? true : false
            ];
        }

        return $this->set_response($data, $statusToken === 0 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_UNAUTHORIZED);
    }

    public function authorization_post()
    {
        $flag = 0;
        $username = $this->post('username');
        $password = $this->enem_templates->enem_secret($this->post('password'));

        if ($this->enem_templates->length($username) == 0)
        {
            $flag = 1;
            $data['status'] = 'Problem';
            $data['messages'] = 'Please insert nik or email or username';
        }
        elseif (strpos($username, '@')) 
        {
            $dataUser = $this->enem_user_model->getEnemUserData('email', $username);
            if ($this->enem_templates->length($username) == 0)
            {
                $flag = 1;
                $data['status'] = 'Problem';
                $data['messages'] = 'Please insert email';
            }
            elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) 
            {
                $flag = 1;
                $data['status'] = 'Problem';
                $data['messages'] = 'Wrong format email';
            }
            elseif (!$dataUser) 
            {
                $flag = 1;
                $data['status'] = 'Problem';
                $data['messages'] = 'Not found email registered';
            }
            elseif ($dataUser[0]->password !== $password) 
            {
                $flag = 1;
                $data['status'] = 'Problem';
                $data['messages'] = 'Credential not match';
            }
            else
            {
                $flag = 0;
            }
        }
        elseif (is_numeric($username))
        {
            $dataUser = $this->enem_user_model->getEnemUserData('nik', $username);
            if (!$dataUser) 
            {
                $flag = 1;
                $data['status'] = 'Problem';
                $data['messages'] = 'Not found nik registered';
            }
            elseif ($dataUser[0]->password !== $password) 
            {
                $flag = 1;
                $data['status'] = 'Problem';
                $data['messages'] = 'Credential not match';
            }
            else
            {
                $flag = 0;
            }
        }
        else 
        {
            $dataUser = $this->enem_user_model->getEnemUserData('username', $username);
            if (!$dataUser) 
            {
                $flag = 1;
                $data['status'] = 'Problem';
                $data['messages'] = 'Not found username registered';
            }
            elseif ($dataUser[0]->password !== $password) 
            {
                $flag = 1;
                $data['status'] = 'Problem';
                $data['messages'] = 'Credential not match';
            }
            else
            {
                $flag = 0;
            }
        }

        if ($flag === 0)
        {
            $data['status'] = 'Ok';
            $data['messages'] = 'Success';
            $data['token'] = $this->getNewToken();
            $data['data_user'] = $dataUser[0];
        }

        return $this->response($data, isset($flag) && $flag === 0 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

}

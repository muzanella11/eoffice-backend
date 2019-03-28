<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/TokenManagement.php';

class RestManager extends REST_Controller {
    private $headerData;
    private $statusRequest;
    private $tokenManagement;

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->library('enem_templates');
        $this->tokenManagement = new TokenManagement();
    }

    public function getNewToken()
    {
        $length = 110811;
        $token = $this->tokenManagement->getNewToken();
        $setting_expired = array(
            'timeby' => 'hours',
            'value' => 1, // di set 1 jam expired nya
        );

        $configToken = array(
            'token' => $token,
            'setting_expired' => $setting_expired
        );

        $this->tokenManagement->initToken($configToken);

        $dataMasterToken = $this->tokenManagement->getTokenByTokenId($token);

        return $dataMasterToken[0]->enem_token;
    }

    public function getHeaderData()
    {
        $this->headerData = getallheaders();
        return $this->headerData;
    }

    public function getTokenHeader()
    {
        $dataHeader = $this->getHeaderData();
        $token = explode(' ', $dataHeader['Authorization'])[1];
        return $token;
    }

    public function checkToken()
    {
        $this->load->model('enem_user_model');

        $tokenHeader = $this->getTokenHeader();

        $checkToken = $this->enem_user_model->getDataTokenUserManagementByToken($tokenHeader);
        $statusToken = $this->enem_templates->check_expired_time($checkToken[0]->token_expired);

        return $statusToken;
    }
}

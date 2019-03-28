<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UserManagement {
    
    private $CI;

    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('enem_templates');
        $this->CI->load->model('enem_user_model');
    }

    public function getListUser ($filter = [])
    {
        $queryString = $this->CI->enem_templates->array_to_query($filter);
        $dataUser = $this->CI->enem_user_model->getEnemUserData('create_sql', $queryString);
        return $dataUser;
    }

    public function createUser ($dataPost = [])
    {
        var_dump($dataPost); exit;
    }
}
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BalanceManagement {

    private $CI;

    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('enem_templates');
        $this->CI->load->model('enem_user_model');
        $this->CI->load->model('BalanceModel');
    }

    public function validateBalance($dataPost = [])
    {
        $dataUser = $this->CI->enem_user_model->getEnemUserData('id', $dataPost['user_id']);
        $dataBalance = $this->CI->BalanceModel->getDataUserBalance('id', $dataPost['user_id']);

        if (!$dataPost['user_id'])
        {
            $flag = 1;
            $data['status'] = 'Problem';
            $data['messages'] = 'Data user id is not found';
        }
        else if (!$dataUser)
        {
            $flag = 1;
            $data['status'] = 'Problem';
            $data['messages'] = 'Data user not found';
        }
        else if ($dataBalance)
        {
            $flag = 1;
            $data['status'] = 'Problem';
            $data['messages'] = 'Data balance already registered';
        }
        else if (!$dataPost['amount_balance'])
        {
            $flag = 1;
            $data['status'] = 'Problem';
            $data['messages'] = 'Data amount is not found';
        }
        else if (!is_numeric($dataPost['amount_balance']))
        {
            $flag = 1;
            $data['status'] = 'Problem';
            $data['messages'] = 'Wrong amount format';
        }
        else 
        {
            $flag = 0;
        }

        $data['flag'] = $flag;

        return $data;
    }

    public function validateUpdateBalance($dataPost = [])
    {
        $dataUser = $this->CI->enem_user_model->getEnemUserData('id', $dataPost['user_id']);
        $dataBalance = $this->CI->BalanceModel->getDataUserBalance('id', $dataPost['user_id']);

        if (!$dataPost['user_id'])
        {
            $flag = 1;
            $data['status'] = 'Problem';
            $data['messages'] = 'Data user id is not found';
        }
        else if (!$dataUser)
        {
            $flag = 1;
            $data['status'] = 'Problem';
            $data['messages'] = 'Data user not found';
        }
        else if (!$dataBalance)
        {
            $flag = 1;
            $data['status'] = 'Problem';
            $data['messages'] = 'Data balance not found';
        }
        else if (!$dataPost['amount_balance'])
        {
            $flag = 1;
            $data['status'] = 'Problem';
            $data['messages'] = 'Data amount is not found';
        }
        else if (!is_numeric($dataPost['amount_balance']))
        {
            $flag = 1;
            $data['status'] = 'Problem';
            $data['messages'] = 'Wrong amount format';
        }
        else 
        {
            $flag = 0;
        }

        $data['flag'] = $flag;

        return $data;
    }

    public function getListBalance ($filter = [])
    {
        $queryString = $this->CI->enem_templates->array_to_query($filter);
        $dataBalance = $this->CI->BalanceModel->getDataUserBalance('create_sql', $queryString);
        return $dataBalance;
    }

    public function parse_raw_http_request(array &$a_data)
    {
        // read incoming data
        $input = file_get_contents('php://input');
        
        // grab multipart boundary from content type header
        preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
        
        // content type is probably regular form-encoded
        if (!count($matches))
        {
            // we expect regular puts to containt a query string containing data
            parse_str(urldecode($input), $a_data);
            return $a_data;
        }
        
        $boundary = $matches[1];
        
        // split content by boundary and get rid of last -- element
        $a_blocks = preg_split("/-+$boundary/", $input);
        array_pop($a_blocks);
        
        // loop data blocks
        foreach ($a_blocks as $id => $block)
        {
            if (empty($block))
            continue;
        
            // you'll have to var_dump $block to understand this and maybe replace \n or \r with a visibile char
        
            // parse uploaded files
            if (strpos($block, 'application/octet-stream') !== FALSE)
            {
            // match "name", then everything after "stream" (optional) except for prepending newlines
            preg_match("/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches);
            $a_data['files'][$matches[1]] = $matches[2];
            }
            // parse all other fields
            else
            {
            // match "name" and optional value in between newline sequences
            preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
            $a_data[$matches[1]] = $matches[2];
            }
        }
    }

}

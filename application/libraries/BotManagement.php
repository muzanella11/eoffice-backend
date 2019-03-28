<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BotManagement {

	private $CI;
	public $BOTTYPE;

    function __construct()
    {
        $this->CI =& get_instance();
		$this->CI->load->library('enem_templates');
		$this->CI->load->model('enem_user_model');

		$this->BOTTYPE = [
			'delete',
			'checktotaldata',
			'tlbot'
		];
	}
	
	public function run ($config, callable $mapDataDb)
	{
		// $config = [
		// 	'enemKey' => 'ebot',
		// 	'enemAmountOrId' => '100',
		// 	'configData' => [
		// 		'tableName' => 'user_role',
		// 		'fieldName' => 'name',
		// 		'keyPrefix' => 'enem',
		//		'callback'  => ['NamaClass', 'publicStaticMethod'],
		//		'callback'  => [$object, 'publicMethod'],
		//		'callback'  => 'public_function',
		//		'callback'  => function ($x) { /** do something */ },
		// 	]
		// ];

		// call_user_func_array('call_enem_something', [$x])

		$flag = 0;
		ini_set('max_execution_time', 0);

		$start = microtime(TRUE);

		$enemKey       		= $config['enemKey'];
		$enemAmountOrId		= $config['enemAmountOrId'];

		$this->checkBotType($enemAmountOrId) ?: $enemAmountOrId = (int) $enemAmountOrId;

		$data = [
			'status' => 'Ok',
			'messages' => ''
		];

		if ($enemKey && $enemAmountOrId) 
		{
			if ($enemKey === 'ebot') 
			{
				if ($enemAmountOrId === 'delete') 
				{
					$dataBot = $this->CI->enem_user_model->deleteBotEnem('enem_user', 'name', 'enem');
					$enem_last_data = count($dataBot);
					$enem_bot_total = $enemAmountOrId;

					$end = microtime(TRUE);
					$getRunTime = ($end-$start).' seconds';

					$data = [
						'status' => 'Ok',
						'messages' => 'Success delete bot user',
						'data' => [
							'lastData' => $enem_last_data,
							'botTotal' => $enem_bot_total,
							'runtime' => $getRunTime,
						],
					];

				} 
				elseif (is_numeric($enemAmountOrId)) 
				{
					/** For Generate Bot User **/
					$enem_prefix = 'enem';
					$enem_password = $this->CI->enem_templates->enem_secret('enem123');
					$enem_role = 3;

					$dataBot = $this->CI->enem_user_model->checkBotEnem($config['configData']['tableName'], $config['configData']['fieldName'], $config['configData']['keyPrefix']);
					
					// $enem_last_data = 0;
					$enem_last_data = $dataBot !== null ? count($dataBot) : 0;
					$enem_bot_total = $enemAmountOrId;

					var_dump($enem_last_data); exit;

					if ($enem_last_data > 0)
					{
						$enem_bot_total_now = $enem_last_data + $enem_bot_total;
						for ($i=$enem_last_data; $i < $enem_bot_total_now; $i++) {

							// $nomer = $i + 1;
							// $name = $enem_prefix.$nomer;
							// $username = $name;
							// $email = $name.'@enem.com';
							// $nik = '000'.$i + 1;

							// $db = array(
							// 	'name' => $name,
							// 	'nik' => $nik,
							// 	'username' => $username,
							// 	'password' => $enem_password,
							// 	'email' => $email,
							// 	'role' => $enem_role,
							// 	'address' => "Di Indonesia Jaya Merdeka !!!"
							// );

							$mapDataDb($i);

							// $this->CI->enem_user_model->addDataUserEnem($db);

							$dataBot = $this->CI->enem_user_model->checkBotEnem('enem_user', 'name', 'enem');
						}
					} 
					else 
					{
						for ($i=0; $i < $enem_bot_total; $i++) {

							$nomer = $i + 1;
							$name = $enem_prefix.$nomer;
							$username = $name;
							$email = $name.'@enem.com';
							$nik = '000'.$i + 1;

							$db = array(
								'name' => $name,
								'nik' => $nik,
								'username' => $username,
								'password' => $enem_password,
								'email' => $email,
								'role' => $enem_role,
								'address' => "Di Indonesia Jaya Merdeka !!!"
							);

							$this->CI->enem_user_model->addDataUserEnem($db);

							$dataBot = $this->CI->enem_user_model->checkBotEnem('enem_user', 'name', 'enem');
						}
					}


					/** End Generate Bot **/

					$end = microtime(TRUE);
					$getRunTime = ($end-$start).' seconds';

					$data = [
						'status' => 'Ok',
						'messages' => 'Success add '.$enem_bot_total.' bot user',
						'data' => [
							'lastData' => $enem_last_data,
							'botTotalAdd' => $enem_bot_total,
							'allBotData' => count($dataBot),
							'runtime' => $getRunTime,
						],
					];

				}
				elseif ($enemAmountOrId === 'checktotaldata')
				{
					$dataBot = $this->CI->enem_user_model->checkBotEnem('enem_user', 'name', 'enem');

					$end = microtime(TRUE);
					$getRunTime = ($end-$start).' seconds';
					
					$data = [
						'status' => 'Ok',
						'messages' => 'Success read '.count($dataBot).' bot user',
						'data' => [
							'allBotData' => count($dataBot),
							'runtime' => $getRunTime,
						],
					];
				} 
				else 
				{
					$flag = 1;
					$data = [
						'status' => 'Problem',
						'messages' => 'Not found enemAmountOrId'
					];
				}

			} 
			elseif ($enemKey === 'tlbot') 
			{
				// For Type Log Bot
				var_dump('type log bot '.$enemAmountOrId); exit();
			} 
			else 
			{
				$flag = 1;
				$data = [
					'status' => 'Problem',
					'messages' => 'Not found enemKey'
				];
			}
		} 
		else 
		{
			$flag = 1;
			$data = [
				'status' => 'Problem',
				'messages' => 'enemKey or enemAmountOrId not found'
			];
		}

		$data['flag'] = $flag;

		return $data;
	}

	public function checkBotType ($value)
	{
		$flag = false;

		foreach ($this->BOTTYPE as $key => $valueBot) {
			$valueBot !== $value ?: $flag = true;
		}

		return $flag;
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

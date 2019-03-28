<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RestManager.php';
require APPPATH . '/libraries/UserManagement.php';
require APPPATH . '/libraries/CrudManagement.php';
require APPPATH . '/libraries/Chart/ChartArea.php';
require APPPATH . '/libraries/Chart/ChartBar.php';
require APPPATH . '/libraries/PdfManagement.php';

use Carbon\Carbon;
use Mpdf\Mpdf;

class EnemBot extends RestManager {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('enem_user_model');
        $this->UserManagement = new UserManagement();
        $this->CrudManagement = new CrudManagement();
        $this->Mpdf = new \Mpdf\Mpdf();
        $this->Carbon = new \Carbon\Carbon();
        $this->ChartArea = new ChartArea();
        $this->ChartBar = new ChartBar();
        $this->pdf = new PdfManagement();
    }

    public function botiseng_get()
    {
        $getContent = file_get_contents('http://smkn1cibinong.sch.id/');
        var_dump($getContent);exit;
    }

    public function botmpdf_get()
    {
        $this->Mpdf->WriteHTML('<h1>Hello world!</h1>');
        $this->Mpdf->Output();
        // var_dump(Carbon::now());exit;
        // Mpdf::WriteHTML('<h1>Hello world!</h1>');
        // Mpdf::Output();
        $data = [
            'status' => 'Ok',
            'messages' => 'Hello guys :)',
            'data' => []
        ];
        
        return $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function botmpdfcustom_get()
    {
        $dateNow = Carbon::now();
        $dateNow->timezone = new DateTimeZone('Asia/Jakarta');
        $dataView = [
            'headerConfig' => [
                'instansi' => [
                    'region' => 'Pemerintah Kota Bogor',
                    'name' => 'Dinas Peternakan dan Kesehatan Hewan',
                    'address' => 'Jl. raya atas bawah bogor <br>
                    Telepon: 021-2222 Fax: 022-8888888888999 <br>
                    website: www.dinkes.com'
                ]
            ],
            'dateMail' => 'Bogor, '.$dateNow->format('d F Y'),
            'contentMain' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse tincidunt eros vel dui aliquet, at porta purus tincidunt. In scelerisque ligula et ligula volutpat, quis vehicula odio facilisis. Ut congue nulla dui, et maximus eros ultricies sed. Aliquam erat volutpat. Pellentesque eu elementum urna. Nam elit dui, maximus sed mattis vel, venenatis iaculis sem. In hac habitasse platea dictumst. Curabitur id erat eget ligula pretium consectetur. Cras varius nunc sem, ut tincidunt erat pharetra ac. Vivamus pretium magna at maximus pharetra. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Etiam lacinia, sapien ac faucibus bibendum, ligula erat ultrices urna, eget viverra lectus neque at ligula. Aliquam erat volutpat. Sed tincidunt bibendum egestas. Donec fermentum eget felis ut tempor. Mauris sit amet consectetur ipsum, vel fringilla mauris. Pellentesque eu purus felis. Fusce vel tellus vitae est hendrerit consequat. Vestibulum volutpat lacus felis, id condimentum metus tincidunt ut. Duis egestas lacus non ullamcorper semper. Curabitur tristique nisl felis, eu ultricies neque fringilla at. Aliquam rutrum commodo felis, et placerat enim viverra non. Curabitur orci quam, iaculis nec mi sed, pulvinar condimentum dolor. Etiam sed auctor orci. Vestibulum lacinia dui id consequat ultricies. Nullam lacinia turpis lectus, non venenatis eros facilisis non. Nulla urna nulla, vestibulum in erat ac, condimentum convallis felis. Phasellus feugiat, ante in dapibus condimentum, enim metus lacinia justo, non aliquam velit lectus vel dui. Interdum et malesuada fames ac ante ipsum primis in faucibus. Sed fermentum felis et nulla eleifend cursus. Nullam eget neque ac nisi pretium imperdiet. Nam at nisl pulvinar, convallis arcu sit amet, tristique lorem. Aenean lacus nulla, maximus varius dapibus quis, fermentum at lorem. Sed pulvinar, est in porta congue, velit dolor auctor ipsum, venenatis dignissim lorem ipsum a justo. Nullam egestas, urna quis aliquam porttitor, ligula felis gravida diam, id dictum enim ante quis mi. Donec arcu lectus, mattis et eros vitae, laoreet molestie ex. In viverra, ex a euismod venenatis, mi est vestibulum ligula, eu feugiat arcu est sed felis. Pellentesque tristique eu massa sit amet rhoncus. Maecenas sit amet sapien ac est sodales volutpat. Donec in finibus neque, vitae volutpat lorem. Mauris sit amet rutrum lacus. Donec semper enim enim, a finibus risus lacinia vel. Quisque ullamcorper quam suscipit enim pharetra bibendum. Vestibulum aliquet tellus est, eget iaculis lectus dictum vel. Vestibulum ut facilisis libero. Sed maximus feugiat dui, sed hendrerit lacus ultricies at. Cras sagittis augue convallis dolor scelerisque, eu imperdiet nisi pretium. Suspendisse sollicitudin mollis finibus. Fusce convallis lobortis magna eget pellentesque. Nam euismod faucibus dui in ultrices. Cras consectetur non ex et tempus. Nullam vel ipsum vel orci volutpat congue. Duis nunc ipsum, dignissim sit amet mauris eu, aliquam vehicula metus. Curabitur ut libero nunc. Donec pulvinar commodo elit non vestibulum. Ut finibus nisi vel orci congue, et laoreet est consequat. Vestibulum eget lacus quis mi consectetur ultricies eget faucibus risus.',
            'footerConfig' => [
                'assign' => [
                    'instansi' => [
                        'name' => 'Kepala Dinas Kesehatan',
                        'region' => 'Kabupaten Bogor'
                    ],
                    'name' => 'Sukonto Legowo',
                    'nik' => '12345678'
                ]
            ]
        ];
        $view = $this->load->view('mails/testmail', $dataView, true);
        $configPdf = [
            // 'title' => 'Surat Perjalanan Dinas',
            'withBreak' => true,
            'html' => [
                $view,
                $view                
            ]
        ];
        $this->pdf->run($configPdf);
        // // var_dump(Carbon::now());exit;
        // // Mpdf::WriteHTML('<h1>Hello world!</h1>');
        // // Mpdf::Output();
        $data = [
            'status' => 'Ok',
            'messages' => 'Hello guys :)',
            'data' => []
        ];
        
        return $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function botchart_get()
    {
        $queryString = $this->get();
        $chart = '';
        if (isset($queryString['q']))
        {
            if ($queryString['q'] === 'area')
            {
                $chart = $this->ChartArea->renderChart();
            }
            else if ($queryString['q'] === 'bar')
            {
                $chart = $this->ChartBar->renderChart();
            }
            else
            {
                $chart = $this->ChartArea->renderChart();
            }
        }
        else
        {
            $chart = $this->ChartArea->renderChart();
        }

        var_dump($chart);exit;
        $data = [
            'status' => 'Ok',
            'messages' => 'Hello guys :)',
            'data' => []
        ];
        
        return $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function getdatauser_get()
    {
        $queryString = $this->input->get();
        $dataUser = $this->UserManagement->getListUser($queryString);

        $data = [
            'status' => 'Ok',
            'messages' => 'Hello guys :)',
            'data' => $dataUser
        ];
        
        return $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function superadmin_get()
    {
        $flag = 0;
        $queryString = $this->input->get(); // Query String for filter data :)

        $config = [
            'catIdSegment' => 3,
            'isEditOrDeleteSegment' => 4
        ];

        $dataModel = [
            [
                'className' => 'User',
                'modelName' => 'UserModel',
                'filter' => '',
                'filterKey' => '',
                'limit' => [
                    'startLimit' => 0,
                    'limitData' => 10000
                ],
                'fieldTarget' => 'name',
                'queryString' => $queryString,
                'dataMaster' => []
            ]
        ];

        if (isset($queryString) && count($queryString) > 0) {
            foreach ($queryString as $key => $value) {
                if (!$value)
                {
                    $queryString[$key] = 'null';
                }
            }

            $dataModel[0]['filter'] = 'create_sql';
            $dataModel[0]['filterKey'] = 'name like "%'.$queryString['q'].'%" or user_role like "%'.$queryString['status_role'].'%"';
            $dataModel[0]['fieldTarget'] = null;
        }
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function superadmin_post()
    {
        $flag = 0;
        $name = $this->post('name');
        $nik = $this->post('nik');
        $username = $this->post('username');
        $password = $this->enem_templates->enem_secret($this->post('password'));
        $email = $this->post('email');
        $user_role = $this->post('user_role');

        $config = [
            'catIdSegment' => 3,
            'isEditOrDeleteSegment' => 4
        ];

        $dataModel = [
            [
                'className' => 'User',
                'modelName' => 'UserModel',
                'filter' => '',
                'filterKey' => '',
                'limit' => [
                    'startLimit' => 0,
                    'limitData' => 10000
                ],
                'dataMaster' => [
                    'name' => $name,
                    'nik' => $nik,
                    'username' => $username,
                    'password' => $password,
                    'email' => $email,
                    'user_role' => $user_role
                ]
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function superadmin_put()
    {
        $flag = 0;
        $name = $this->put('name');
        $nik = $this->put('nik');
        $username = $this->put('username');
        $password = $this->enem_templates->enem_secret($this->put('password'));
        $email = $this->put('email');
        $user_role = $this->put('user_role');

        $config = [
            'catIdSegment' => 3,
            'isEditOrDeleteSegment' => 4
        ];

        $dataModel = [
            [
                'className' => 'User',
                'modelName' => 'UserModel',
                'filter' => '',
                'filterKey' => '',
                'limit' => [
                    'startLimit' => 0,
                    'limitData' => 10000
                ],
                'dataMaster' => [
                    'name' => $name,
                    'nik' => $nik,
                    'username' => $username,
                    'password' => $password,
                    'email' => $email,
                    'user_role' => $user_role
                ]
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function superadmin_delete()
    {
        $flag = 0;
        $config = [
            'catIdSegment' => 3,
            'isEditOrDeleteSegment' => 4
        ];

        $dataModel = [
            [
                'className' => 'User',
                'modelName' => 'UserModel',
                'fieldName' => 'user_id'
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function botuser_post() 
    {
        $flag = 0;
        ini_set('max_execution_time', 0);
        
        $start = microtime(TRUE);

        $enemKey       = $this->enem_templates->anti_injection(strtolower($this->post('enemKey')));
        $enemAmountOrId    = $this->enem_templates->anti_injection(strtolower($this->post('enemAmountOrId')));

        $flag = 0;
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
                    // $this->load->model('enem_user_model');

                    $dataBot = $this->enem_user_model->deleteBotEnem('enem_user', 'name', 'enem');
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
                    $enem_password = $this->enem_templates->enem_secret('enem123');
                    $enem_role = 3;


                    // $this->load->model('enem_user_model');

                    $dataBot = $this->enem_user_model->checkBotEnem('enem_user', 'name', 'enem');
                    // $enem_last_data = 0;
                    $enem_last_data = count($dataBot);
                    $enem_bot_total = $enemAmountOrId;
                    // var_dump(count($dataBot)); exit();

                    if ($enem_last_data) 
                    {
                        $enem_bot_total_now = $enem_last_data + $enem_bot_total;
                        for ($i=$enem_last_data; $i < $enem_bot_total_now; $i++) {

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

                            $this->enem_user_model->addDataUserEnem($db);

                            $dataBot = $this->enem_user_model->checkBotEnem('enem_user', 'name', 'enem');
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

                            $this->enem_user_model->addDataUserEnem($db);

                            $dataBot = $this->enem_user_model->checkBotEnem('enem_user', 'name', 'enem');
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
                    $dataBot = $this->enem_user_model->checkBotEnem('enem_user', 'name', 'enem');

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

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);

    }

}

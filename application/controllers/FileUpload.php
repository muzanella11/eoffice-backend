<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RestManager.php';
require APPPATH . '/libraries/CrudManagement.php';
require APPPATH . '/libraries/PdfManagement.php';

use Carbon\Carbon;

class FileUpload extends RestManager {
    function __construct () 
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('enem_user_model');
		$this->CrudManagement = new CrudManagement();
		$this->Carbon = new \Carbon\Carbon();
        $this->pdf = new PdfManagement();
    }

    public function index_get()
    {
        $flag = 0;
        $queryString = $this->input->get(); // Query String for filter data :)

        $config = [
            'catIdSegment' => 2,
            'isEditOrDeleteSegment' => 3
        ];

        $dataModel = [
            [
                'className' => 'FileUpload',
                'modelName' => 'FileUploadModel',
                'filter' => '',
                'filterKey' => '',
                'limit' => [
                    'startLimit' => isset($queryString['offset']) ? $queryString['offset'] : 0,
                    'limitData' => isset($queryString['limit']) ? $queryString['limit'] : 10000
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
            $dataModel[0]['filterKey'] = $queryString['q'] !== 'null' || $queryString['id'] !== 'null' ? 'WHERE name like "%'.$queryString['q'].'%" or id like "%'.$queryString['id'].'%"' : null;
            $dataModel[0]['fieldTarget'] = null;
        }
        
		$data = $this->CrudManagement->run($config, $dataModel);
		
		// For pagination
        $dataModel[0]['filter'] = 0;
        $dataModel[0]['filterKey'] = null;
        $dataModel[0]['limit'] = null;

        $getTotalData = $this->CrudManagement->run($config, $dataModel);

        $data['totalData'] = count($getTotalData['data']);
        // End pagination

        foreach ($data['data'] as $key => $value) {
            $dataMaster = json_encode($data['data'][$key]);
            $dataMasterEncode = json_decode($dataMaster, TRUE);
            $data['data'][$key] = $dataMasterEncode;
        }

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_post()
    {
		$flag = 0;
		$file = $this->post('file') ? $this->post('file') : null;

        $config = [
            'catIdSegment' => 2,
            'isEditOrDeleteSegment' => 3
        ];

        $dataModel = [
            [
                'className' => 'FileUpload',
                'modelName' => 'FileUploadModel',
                'filter' => '',
                'filterKey' => '',
                'limit' => [
                    'startLimit' => 0,
                    'limitData' => 10000
                ],
                'dataMaster' => [
					'name' => $this->post('name')
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

    public function index_put()
    {
        $flag = 0;

        $config = [
            'catIdSegment' => 2,
            'isEditOrDeleteSegment' => 3
		];
		
		$dataMaster = [
			'name' => $this->put('name')
		];

		foreach($dataMaster as $key => $value) {
			if (!$value) unset($dataMaster[$key]);
		};

        $dataModel = [
            [
                'className' => 'FileUpload',
                'modelName' => 'FileUploadModel',
                'filter' => '',
                'filterKey' => '',
                'limit' => [
                    'startLimit' => 0,
                    'limitData' => 10000
                ],
                'dataMaster' => $dataMaster
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_delete()
    {
        $flag = 0;
        $config = [
            'catIdSegment' => 2,
            'isEditOrDeleteSegment' => 3
        ];

        $dataModel = [
            [
                'className' => 'FileUpload',
                'modelName' => 'FileUploadModel',
                'fieldName' => 'id'
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
	}
	
	public function report_get()
    {
		$dateNow = Carbon::now();
        $dateNow->timezone = new DateTimeZone('Asia/Jakarta');

        $config = [
            'catIdSegment' => 2,
            'isEditOrDeleteSegment' => 3
        ];

        $dataModel = [
            [
                'className' => 'FileUpload',
                'modelName' => 'FileUploadModel',
                'filter' => 'create_sql',
                'filterKey' => '',
                'limit' => '',
                'fieldTarget' => 'name',
                'queryString' => [],
                'dataMaster' => []
            ]
        ];

		$data = $this->CrudManagement->run($config, $dataModel);

		// Mapping data table
		foreach ($data['data'] as $key => $value) {
			$dataMaster = json_encode($data['data'][$key]);
            $dataMasterEncode = json_decode($dataMaster, TRUE);
            $data['data'][$key] = $dataMasterEncode;
            $data['data'][$key]['id'] = (int) $data['data'][$key]['id'];
		}

        $dataContentMain = '';
		$dataTable = $data['data'];

        $dataView = [
            'headerConfig' => [
                'instansi' => [
                    'name' => 'Travlr'
                ]
            ],
            'titleContent' => 'Laporan Data File Upload',
            'dateMail' => 'Bali, '.$dateNow->format('d F Y'),
            'contentMain' => $dataContentMain,
            'tableName' => 'File Upload',
            'contentTable' => $dataTable,
            'footerConfig' => []
        ];
        $view = $this->load->view('mails/templates/DataReport', $dataView, true);
        $configPdf = [
            'setFooterPageNumber' => True,
            'title' => 'Laporan Data File Upload',
            // 'withBreak' => true,
            'html' => [
                $view                
            ]
        ];
        $this->pdf->run($configPdf);

        $location = $_SERVER['HTTP_HOST'].'/uploads/pdf/'.$configPdf['title'].'.pdf';

        $data = [
            'status' => 'Ok',
            'urlData' => $location,
            'messages' => 'Hello guys :)'
        ];
        
        return $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function ping_post() 
    {
        $data = [
            'status' => 'Ok',
            'messages' => 'Hello guys post file upload :)'
        ];
        
        return $this->set_response($data, REST_Controller::HTTP_OK);
    }
}

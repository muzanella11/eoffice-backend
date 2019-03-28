<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CrudManagement {
    
    private $CI;

    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('enem_templates');
        $this->CI->load->model('enem_user_model');
    }

    public function run ($config = [], $model = [])
    {
        // $model = [
        //     [
        //         'modelName' => 'myModel',
        //         'methodName' => 'methodModel',
        //         'filter' => 'something',
        //         'filterKey' => 'something',
        //         'limit' => [
        //             'startLimit' => 0,
        //             'limitData' => 10
        //         ],
        //         'dataMaster' => []
        //     ]
        // ]

        ini_set('max_execution_time', 0);
        
        $start = microtime(TRUE);

        $catIdSegment = $config['catIdSegment'] ? $config['catIdSegment'] : 2;
        $isEditOrDeleteSegment = $config['isEditOrDeleteSegment'] ? $config['isEditOrDeleteSegment'] : 3;
        
        $getCatOrId = strtolower($this->CI->uri->segment($catIdSegment));
        $isEditOrDelete = strtolower($this->CI->uri->segment($isEditOrDeleteSegment));

        if (isset($config['customParam']))
        {
            $getCatOrId = $config['catIdSegment'];
            $isEditOrDelete = $config['isEditOrDeleteSegment'];
        }
        
        $flag = 0;
        $data = [
            'status' => 'Ok',
            'messages' => ''
        ];

        if ($getCatOrId === 'create') // if has a category === create
        {
            $dataModel = $this->runModelPost($model, $getCatOrId);

            if ($dataModel['flag'])
            {
                $flag = $dataModel['flag'];
                unset($dataModel['flag']);

                $data = $dataModel;
            }
            else
            {
                $data = [
                    'status' => 'Ok',
                    'messages' => 'Berhasil Membuat Data',
                    'latest_created' => $dataModel['latest_id'],
                    'data' => [
                        'getCatOrId' => $getCatOrId
                    ]
                ];
            }
        }
        else 
        {
            if ($getCatOrId === null) // if category null
            {
                $flag = 1;
                $data = [
                    'status' => 'Problem',
                    'messages' => 'Something wrong'
                ];
            }
            else
            {
                if (is_numeric($getCatOrId) && $isEditOrDelete && $isEditOrDelete === 'edit' || $isEditOrDelete === 'delete') // if numeric => action edit or delete
                {
                    if ($isEditOrDelete === 'edit')
                    {
                        $dataModel = $this->runModelPost($model, $getCatOrId, $isEditOrDelete);

                        if ($dataModel['flag'])
                        {
                            $flag = $dataModel['flag'];
                            unset($dataModel['flag']);

                            $data = $dataModel;
                        }
                        else
                        {
                            $data = [
                                'status' => 'Ok',
                                'messages' => 'Berhasil Mengubah Data',
                                'data' => [
                                    'getCatOrId' => $getCatOrId
                                ]
                            ];
                        }
                    }
                    elseif ($isEditOrDelete === 'delete')
                    {
                        $dataModel = $this->runModelPost($model, $getCatOrId, $isEditOrDelete);

                        if ($dataModel['flag'])
                        {
                            $flag = $dataModel['flag'];
                            unset($dataModel['flag']);

                            $data = $dataModel;
                        }
                        else
                        {
                            $data = [
                                'status' => 'Ok',
                                'messages' => 'Berhasil Menghapus Data',
                                'data' => [
                                    'getCatOrId' => $getCatOrId
                                ]
                            ];
                        }
                    }

                }
                elseif (is_numeric($getCatOrId) && $isEditOrDelete !== 'edit' || $isEditOrDelete !== 'delete')
                {
                    $newModel = [];
                    foreach ($model as $key => $value) {
                        $value['filter'] = $value['filter'] ? $value['filter'] : 'id';
                        $value['filterKey'] = $value['filterKey'] ? $value['filterKey'] : $getCatOrId;
                        array_push($newModel, $value);
                    }
                    
                    $dataMaster = $this->runModelGet($newModel, $getCatOrId); // read data by id
                    $data = [
                        'status' => 'Ok',
                        'messages' => 'Berhasil',
                        'totalData' => count($dataMaster),
                        'data' => $dataMaster
                    ];
                }
                else
                {
                    $dataMaster = $this->runModelGet($model, $getCatOrId); // read all data and filter
                    $data = [
                        'status' => 'Ok',
                        'messages' => 'Berhasil',
                        'totalData' => count($dataMaster),
                        'data' => $dataMaster
                    ];
                }
            }
        }

        $end = microtime(TRUE);
        $getRunTime = ($end-$start).' seconds';

        $data['status_process'] = [
            'runtime' => $getRunTime,
        ];

        return $data;
    }

    public function runModelPost ($model = [], $getCatOrId = null, $isEditOrDelete = null)
    {
        $flag = 0;

        if ($getCatOrId === 'create')
        {
            $getMethodName = 'addData';
        }
        elseif ($isEditOrDelete === 'edit')
        {
            $getMethodName = 'updateData';
        }
        elseif ($isEditOrDelete === 'delete')
        {
            $getMethodName = 'deleteData';
        }
        else 
        {
            $flag = 1;
        }

        if ($model)
        {
            foreach ($model as $key => $value) {
                $methodName = $getMethodName.$value['className'];
                $value['methodName'] = $methodName;

                $this->CI->load->model($value['modelName']);
                if ($getCatOrId === 'create')
                {
                    $data = $this->CI->{$value['modelName']}->{$value['methodName']}($value['dataMaster']);
                    $flag = $data['flag'];
                    $latestId = $data['latest_create_id'];
                }
                else if ($isEditOrDelete === 'edit')
                {
					$data = $this->CI->{$value['modelName']}->{$value['methodName']}($value['dataMaster'], $getCatOrId);
					$flag = $data['flag'];
                }
                else if ($isEditOrDelete === 'delete')
                {
                    $value['fieldValue'] = $getCatOrId;
                    $data = $this->CI->{$value['modelName']}->{$value['methodName']}($value['fieldName'], $value['fieldValue']);
                    $flag = $data['flag'];
                } else {
                    $flag = 1;
                }
            }
        }
        else
        {
            $flag = 1;
        }

        if (!$flag)
        {
            $data = [
                'flag' => $flag,
                'status' => 'Ok',
                'messages' => 'Berhasil'
            ];

            if (isset($latestId))
            {
                $data['latest_id'] = $latestId;
            }
        }
        else
        {
            $data = [
                'flag' => $flag,
                'status' => 'Problem',
                'messages' => 'Something wrong'
            ];
        }

        return $data;
    }

    public function runModelGet ($model = [])
    {
        $getMethodName = 'getData';
        foreach ($model as $key => $value) {
            $methodName = $getMethodName.$value['className'];
            $value['methodName'] = $methodName;

            $this->CI->load->model($value['modelName']);
            $data = $this->CI->{$value['modelName']}->{$value['methodName']}($value['filter'], $value['filterKey'], $value['limit'], $value['fieldTarget']);
        }

        return $data;
    }
}

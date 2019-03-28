<?php

/**
 * @author f1108k
 * @copyright 2015
 */



?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enem_templates {
    private $folder_css_sign_in;
    private $folder_js_sign_in;

    private $folder_css;
    private $folder_js;
    private $path = "tbgn/";
    private $folder_image;
	private $folder_image_default;

    function __construct(){
        $this->folder_css   		= "/stylesheet/css/";
		$this->folder_js    		= "/stylesheet/javascript/";
        $this->folder_image 		= "http://". $_SERVER['HTTP_HOST'] ."/" . "stylesheet/images/";
		$this->folder_image_default = "http://". $_SERVER['HTTP_HOST'] ."/" . "stylesheet/image/default_avatar/";
		//$this->folder_image 		= "http://". $_SERVER['HTTP_HOST'] ."/" . $this->path . "stylesheet/images/";
		//$this->folder_image_default = "http://". $_SERVER['HTTP_HOST'] ."/" . $this->path . "stylesheet/image/default_avatar/";
		$this->image_dir			= $_SERVER['DOCUMENT_ROOT'] . "/" . $this->path . "stylesheet/images/";
		$this->us_img_dir			= $_SERVER['DOCUMENT_ROOT'] . "/" . $this->path . "media/";
		$this->us_img_path			= "http://". $_SERVER['HTTP_HOST'] ."/". $this->path . "media/";
    }
    
    function folder_css($css=""){
		$data_css = '';
		$css[] = "reset.css";
		foreach ($css as $key => $val){
			$data_css .= "<link href=" . "http://".$_SERVER['HTTP_HOST'] . "/". $this->folder_css. $val ." rel='stylesheet' type='text/css'/>\n";
		}

		return $data_css;
    }
    
    function folder_js($js=""){
		$data_js = '';
		$file = '';
		$file[] = "jquery.js";
		if($js){
			foreach ($js as $key => $val){
				$file[] = $val;
			}
		}
		foreach ($file as $key => $val){
			$data_js .= "<script src=" . "http://".$_SERVER['HTTP_HOST'] . "/" . $this->folder_js. $val ." type='text/javascript'></script>\n";
		}

		return $data_js;
    }
    
	function no_avatar(){
		$file	=	'default.jpg';
		$link	=	$this->folder_image_default;

		$data_image_default	=	$link.$file;

		return $data_image_default;
    }
    
    function enem_theme_url($enem_theme) {
        $url = site_url().'themes/'.$enem_theme.'/media/';
        return $url;
    }

    function enem_inject_js($enem_script, $enem_position) {
        $enem_script = $enem_script;
        $enem_position = $enem_position;

        $inject_js = get_defined_vars();

        // var_dump($inject_js); exit();
        return $this->$inject_js;
        // $this->get_inject_js($inject_js);
    }

    function get_inject_js($jsScript) {
        if($jsScript) {
            $dataJs = $jsScript;
            return $dataJs;
        } else {

        }
    }

    function length($string){
		$str_length	= strlen(trim($string));
		return $str_length;
    }
    
    function enem_secret($pass){
        $enem_pass = md5(sha1(md5($pass)));
        return $enem_pass;
    }

    function anti_injection($var){
        $enem_var = stripslashes(strip_tags(htmlspecialchars($var,ENT_QUOTES)));
        return $enem_var;
    }

    function get_random_string($length){
        $x ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $y = substr(str_shuffle($x),0,$length);
        return $y;
    }

    function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }

    function array_to_query($array) {
        return http_build_query($array);
    }

    function array_to_object($array) {
        $object = (object) $array;

        return $object;
    }

    function object_to_array($object) {
        $array = (object) $object;

        return $array;
    }

    function check_expire_time($sess_time,$hours){
        //session_time gunakan format : yyyy-mm-dd jj:mm:dd
        date_default_timezone_set('Asia/Jakarta');
        $hours      = ($hours*60)*60;       //jam dikalikan menit dikalikan detik
        $expiretime = strtotime($sess_time) + ($hours); //set expire time
        $time_now   = time(); //set time now

        //jika time_now lebih kecil dari expiretime === belum expire
        if ($time_now <= $expiretime) {
            $flag   = 0; //blm expire
        } else {
            $flag   = 1; //expire
        }

        return $flag;
    }

    function create_expired_time_old($sess_time, $hours) {
        //session_time gunakan format : yyyy-mm-dd jj:mm:dd
        date_default_timezone_set('Asia/Jakarta');
        $hours      = ($hours*60)*60;       //jam dikalikan menit dikalikan detik
        $expiretime = strtotime($sess_time) + ($hours); //set expire time

        return $expiretime;
    }

    function create_expired_time($sess_time, $setting) {
        //session_time gunakan format : yyyy-mm-dd jj:mm:dd
        date_default_timezone_set('Asia/Jakarta');
        if(is_array($setting)) {

            if($setting['timeby'] === 'hours') {
                $hours      = ($setting['value']*60)*60;       //jam dikalikan menit dikalikan detik
                $expiretime = strtotime($sess_time) + ($hours); //set expire time
            } elseif($setting['timeby'] === 'minute') {
                $minute     = $setting['value']*60;       //menit dikalikan detik
                $expiretime = strtotime($sess_time) + ($minute); //set expire time
            } else {
                // var_dump('setting not found'); exit();
                throw new Exception("Setting not found in create_expired_time", 1);

            }

            return $expiretime;
        } else {
            // var_dump('parameter 2 timeby must array'); exit();
            throw new Exception("Parameter 2 timeby must array in create_expired_time", 1);

        }
    }

    function check_expired_time($time_expired) {
        $time_now = time();

        if ($time_now <= $time_expired) {
            $flag   = 0; //blm expired
        } else {
            $flag   = 1; //expired
        }

        return $flag;
    }

    function get_user_info() {
        $server = $_SERVER;
        $ip = $server['REMOTE_ADDR'];
        $uri = $server['REQUEST_URI'];
        $server_name = $server['SERVER_NAME'];
        $user_agent = $server['HTTP_USER_AGENT'];
        // $referer = $server['HTTP_REFERER'];

        // var_dump($server['HTTP_REFERER']); exit();

        if(isset($server['HTTP_REFERER'])) {
            $referer = $server['HTTP_REFERER'];
            // var_dump($referer); exit();
        } else {
            $referer = null;
        }

        $data = array(
            'ip' => $ip,
            'server_name' => $server_name,
            'url' => $uri,
            'user_agent' => $user_agent,
            'referer' => $referer,
        );
        // return $ip.'<br>'.$server_name.$uri;
        return $data;
    }

    function check_status_userlog($status) {
        switch ($status) {
            case 1:
                $dataLog = 'Login enem user (secret)';
                break;

            case 2:
                $dataLog = 'Enem user dashboard (secret)';
                break;

            case 3:
                $dataLog = 'Add enem user (secret)';
                break;

            default:
                $dataLog = 'No option log';
                break;
        }

        return $dataLog;
    }

    function check_user_role($role) {
        $dataFlag = true;
        switch ($role) {
            case 1:
                $dataRole = 'Super Admin';
                break;

            case 2:
                $dataRole = 'Admin';
                break;

            default:
                $dataRole = 'Undefined role';
                $dataFlag = false;
                break;
        }

        $retRole = array(
            'flag' => $dataFlag,
            'role' => $dataRole,
        );

        return $retRole;
    }

    function create_pagination($paramPagination) {

        $CI =& get_instance();

        // For Pagination
        // $paramPagination = array(
        //     'model_name' => 'enem_user_model',
        //     'method' => array(
        //         'name' => 'getEnemDataPagination',
        //         'filter' => 'create_sql',
        //         'sql' => '* FROM enem_user WHERE username LIKE "%enem%" ORDER BY user_id',
        //     ),
        //     'limit_data' => 10,
        //     'limit_page' => 5,
        //     'page_now' => 1,
        // );
        // Example Parameter For Pagination :)

        // $model_name = 'enem_user_model';
        // $method_name = 'getEnemUserData';

        $modelName = $paramPagination['model_name'];
        $methodName = $paramPagination['method']['name'];
        $methodFilter = $paramPagination['method']['filter'];
        $methodSql = $paramPagination['method']['sql'];
        $argLimitData = $paramPagination['limit_data'];
        $argLimitPage = $paramPagination['limit_page'];
        $argPageNow = $paramPagination['page_now'];

        if(isset($methodFilter) && isset($methodSql)) {
            $filter = $methodFilter;
            $sql = $methodSql;
        } else {
            $filter = NULL;
            $sql = NULL;
        }


        $CI->load->model($modelName);
        $getDataAll = $CI->$modelName->$methodName($sql, $filter);
        $totalDataAll = count($getDataAll);
        // $limitData = 10;
        if(isset($paramPagination['limit_data'])) {
            $limitData = $paramPagination['limit_data'];
        } else {
            $limitData = 10;
        }
        $totalPage = ceil($totalDataAll/$limitData);
        // $limitPage = 5;
        if(isset($paramPagination['limit_page'])) {
            $limitPage = $paramPagination['limit_page'];
        } else {
            $limitPage = 5;
        }
        $pageNow = $paramPagination['page_now'];
        $startLimit = ($pageNow - 1) * $limitData;
        $limit = array(
            'startLimit' => $startLimit,
            'limitData' => $limitData,
        );

        $rangePage = $limitPage;
        if($pageNow > $totalPage) {
            // $startPagination = $pageNow - $rangePage + 1;
            // // var_dump($startPagination); exit();
            // $endPagination = $pageNow + 1;
            $startPagination = $pageNow;
            $endPagination = $pageNow + $rangePage;

            // var_dump($startPagination); exit();
        } elseif($pageNow > 2) {
            if($pageNow == $totalPage) {
                // Ketika page sama dengan jumlah total page (last page)
                if($pageNow < $rangePage) {
                    $startPagination = 1;
                    $endPagination = $totalPage + 1;
                } else {
                    $startPagination = $pageNow - $rangePage + 1;
                    $endPagination = $totalPage + 1;
                }
                // var_dump('asd');exit();
            } elseif($pageNow == $totalPage-1) {
                // Satu angka sebelum terakhir
                if($pageNow >= $rangePage) {
                    $startPagination = $pageNow - 3;
                    $endPagination = $pageNow + $rangePage - 3;
                } else {
                    $startPagination = 1;
                    $endPagination = $pageNow + $rangePage - 3;
                }
                // var_dump('asd');exit();
            } else {
                $startPagination = $pageNow - 2;
                $endPagination = $pageNow + $rangePage - 2;
            }
        } elseif($pageNow > 1) {
            $startPagination = $pageNow - 1;
            $endPagination = $pageNow + $rangePage - 1;
        } else {
            $startPagination = $pageNow;
            $endPagination = $pageNow + $rangePage;
        }

        // Set Prev Next
        if($pageNow <= 1) {
            if($totalPage > 1) {
                $prev = FALSE;
                $next = $pageNow + 1;
            } else {
                $prev = FALSE;
                $next = FALSE;
            }

        } elseif($pageNow >= $totalPage) {
            $prev = $pageNow - 1;
            $next = FALSE;
        } else {
            $prev = $pageNow - 1;
            $next = $pageNow + 1;
        }
        // $endPagination = $pageNow + $rangePage;
        // var_dump($startPagination); exit();

        $dataQuery = $CI->$modelName->$methodName($sql, $filter, $limit);

        $dataPagination = array(
            'totalDataAll' => $totalDataAll,
            'totalPage' => $totalPage,
            'limitData' => $limitData,
            'limitPage' => $limitPage,
            'pageNow' => $pageNow,
            'startLimit' => $startLimit,
            'prev' => $prev,
            'startPagination' => $startPagination,
            'endPagination' => $endPagination,
            'next' => $next,
            'dataQuery' => $dataQuery,
        );

        return $dataPagination;

    }

    function check_type_log($data = NULL) {
        $CI =& get_instance();

        // Example parameter type log
        // $data = array(
        //     'filter' => 'status_log',
        //     'filter_key' => 1,
        // );

        $CI->load->model('enem_user_model');
        if(isset($data)) {
            $typeLog = $CI->enem_user_model->getDataTypeLog($data);
        } else {
            $typeLog = $CI->enem_user_model->getDataTypeLog();
        }

        return $typeLog;
    }

    function add_log($data = NULL) {
        $CI =& get_instance();
        // For Add Log

        // Example Parameter Log
        // $data = array(
        //     'filter' => 'status_log',
        //     'filter_key' => 1,
        // );

        if(isset($data)) {

            $dataLogFlag = FALSE;
            $CI->load->model('enem_user_model');

            // Check First Log
            $dataFirstLog = array(
                'filter' => 'status_log',
                'filter_key' => 1,
            );
            $check_first_log = $this->check_type_log($dataFirstLog);
            if($check_first_log) {
                $dataLogFlag = TRUE;
            } else {
                $addFirstLog = array(
                    'name' => 'Add type log',
                    'status_log' => 1,
                );
                $CI->enem_user_model->addTypeLog($addFirstLog);

                $info_user = $this->get_user_info();
                $dbLog = array(
                    'ip' => $info_user['ip'],
                    'server_name' => $info_user['server_name'],
                    'url' => $info_user['url'],
                    'user_agent' => $info_user['user_agent'],
                    'referer' => $info_user['referer'],
                    'status_log' => $addFirstLog['status_log'],
                    'title_log' => 'Create first type log (secret)',
                );

                $CI->enem_user_model->addDataUserLog($dbLog);
                // $dataLogFlag = FALSE;
            }

            if($dataLogFlag == TRUE) {
                $check_status_log = $this->check_type_log($data);
                $info_user = $this->get_user_info();
                if($check_status_log != NULL && $data['filter_key'] != NULL) {
                    $status_log = $data['filter_key'];
                    $user_log_status = $check_status_log[0]->name;
                    $dbLog = array(
                        'ip' => $info_user['ip'],
                        'server_name' => $info_user['server_name'],
                        'url' => $info_user['url'],
                        'user_agent' => $info_user['user_agent'],
                        'referer' => $info_user['referer'],
                        'status_log' => $status_log,
                        'title_log' => $user_log_status,
                    );

                    $CI->enem_user_model->addDataUserLog($dbLog);
                } else {
                    $dbLog = array(
                        'ip' => $info_user['ip'],
                        'server_name' => $info_user['server_name'],
                        'url' => $info_user['url'],
                        'user_agent' => $info_user['user_agent'],
                        'referer' => $info_user['referer'],
                        'status_log' => 0,
                        'title_log' => 'Undefined Log',
                    );

                    $CI->enem_user_model->addDataUserLog($dbLog);
                }

            }
        }
    }
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
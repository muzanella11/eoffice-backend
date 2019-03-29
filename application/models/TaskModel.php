<?php

/**
 * @author f1108k
 * @copyright 2018
 */



?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class TaskModel extends MY_Model {
        private $tableName = 'task';

        function __construct(){
            parent::__construct();
        }

        function addDataTask ($data) {
            $sql    =   "INSERT INTO {$this->tableName} (
				name,
				description,
				assignee_to,
				priority,
				deadline_date,
				task_status,
				attachment,
				created_at
			) VALUES (
				'".$data['name']."',
				'".$data['description']."',
				'".$data['assignee_to']."',
				'".$data['priority']."',
				'".$data['deadline_date']."',
				'".$data['task_status']."',
				'".$data['attachment']."',
				now()
			)";
            
            $query  =   $this->db->query($sql);
            $latestId = $this->db->insert_id();

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
            }

            $getError = $this->db->error();

            if (!$getError['message']) {
                return [
                    'latest_create_id' => $latestId,
                    'flag' => 0,
                    'messages' => 'Berhasil menambahkan data'
                ];
            } else {
                return [
                    'flag' => 1,
                    'messages' => 'Gagal menambahkan data'
                ];
            }
        }

        function getDataTask ($filter = NULL, $filter_key = NULL, $limit = NULL, $field_target = NULL) {
            if(!empty($filter) && !empty($filter_key)) {
                if($filter === 'id') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE id='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE id='".$filter_key."'";
                    }
                } elseif ($filter === 'search') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ".$field_target." LIKE '%".$filter_key."%' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ".$field_target." LIKE '%".$filter_key."%'";
                    }
                } elseif ($filter === 'name') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE name='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE name='".$filter_key."'";
                    }
                } elseif ($filter === 'create_sql') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} ".$filter_key." LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} ".$filter_key."";
                    }
                }
            } else {
                if(is_array($limit)) {
                    $sql    =   "SELECT * FROM {$this->tableName} LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                } else {
                    $sql    =   "SELECT * FROM {$this->tableName}";
                }
			}

            $query  =   $this->db->query($sql);
            $getError = $this->db->error();

            if (!$getError['message'] && $query->num_rows() > 0) {
                return $query->result();
            } else {
                return [];
            }
        }

        function updateDataTask ($data, $findByValue = '') {
			$findBy = 'id';
            $query = [];
            foreach ($data as $key => $value) {
				if (isset($key) && $value) {
					$queryVal = $key . ' = "'.$value.'"';
                    array_push($query, $queryVal);
                }
			}

			$queryResult = implode(',', $query);			

            if (!$queryResult)
            {
                return [
                    'flag' => 1,
                    'messages' => 'Gagal mengubah data'
                ];
            }
            
			$sql    =   "UPDATE {$this->tableName} SET ".$queryResult.", updated_at=now() WHERE ".$findBy."={$findByValue}";

			$query  =   $this->db->query($sql);
            $getError = $this->db->error();

            if (!$getError['message']) {
                return [
                    'flag' => 0,
                    'messages' => 'Berhasil mengubah data'
                ];
            } else {
                return [
                    'flag' => 1,
                    'messages' => 'Gagal mengubah data'
                ];
            }
        }

        function deleteDataTask ($field_name, $field_value) {
            $sql    =   "DELETE FROM {$this->tableName} WHERE ".$field_name." = '".$field_value."'";
            
            $query  =   $this->db->query($sql);
            $getError = $this->db->error();

            if (!$getError['message']) {
                return [
                    'flag' => 0,
                    'messages' => 'Berhasil menghapus data'
                ];
            } else {
                return [
                    'flag' => 1,
                    'messages' => 'Gagal menghapus data'
                ];
            }
        }
    }
?>

<?php

/**
 * @author f1108k
 * @copyright 2015
 *

 */



?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class MY_Model extends CI_Model{
        function __construct(){
            parent::__construct();
        }
        function addEnemTokenUserManagement($data){
            $ip     =   $_SERVER['REMOTE_ADDR'];

            $sql    =   "INSERT INTO enem_user_token_management (enem_token, ip, date_created)
                            VALUES('".$data['enem_token']."', '".$ip."', now())";

            $this->db->query($sql);
        }

        function getDataTokenUserManagementByToken($token){
            $sql    =   "SELECT * FROM enem_user_token_management WHERE enem_token='{$token}'";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }

        function updateEnemTokenExpired($data) {
            $enem_token_expired = "token_expired = ".$data['enem_token_expired']."";

            $sql       =   "UPDATE enem_user_token_management SET ".$enem_token_expired." WHERE enem_token ='".$data['enem_token']."'";
            $this->db->query($sql);
        }

        function addTypeLog($data) {
            $sql    =   "INSERT INTO enem_type_log (name, status_log, date_created)
                            VALUES('".$data['name']."', '".$data['status_log']."', now())";

            $this->db->query($sql);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
            }
        }

        function updateTypeLog($data) {
            $name    =   "name='".$data['name']."'";
            $status_log = "status_log='".$data['status_log']."'";

            $sql       =   "UPDATE enem_type_log SET ".$name.", ".$status_log.", date_update=now() WHERE status_log ='".$data['status_log']."'";
            $this->db->query($sql);
        }

        function getDataTypeLog($data = NULL) {
            if(!empty($data['filter']) && !empty($data['filter_key'])) {
                if($data['filter'] == 'name') {
                    $sql    =   "SELECT * FROM enem_type_log WHERE name='".$data['filter_key']."'";
                } elseif($data['filter'] == 'status_log') {
                    $sql    =   "SELECT * FROM enem_type_log WHERE status_log='".$data['filter_key']."'";
                }
            } else {
                $sql    =   "SELECT * FROM enem_type_log";
            }

            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }

        function addDataUserLog($data) {

            $sql    =   "INSERT INTO enem_user_log (ip, status_log, title_log, server_name, uri_log, user_agent, http_referer, date_log)
                            VALUES('".$data['ip']."', '".$data['status_log']."', '".$data['title_log']."', '".$data['server_name']."', '".$data['url']."', '".$data['user_agent']."', '".$data['referer']."', now())";

            $this->db->query($sql);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
            }
        }

        function addDataUserEnem($data) {

            $sql    =   "INSERT INTO enem_user (name, nik, username, password, email, address, user_role, date_created)
                            VALUES('".$data['name']."', '".$data['nik']."', '".$data['username']."', '".$data['password']."', '".$data['email']."', '".$data['address']."', '".$data['role']."', now())";

            $this->db->query($sql);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
            }
        }

        function checkBotEnem($table_name, $field_name, $key_prefix) {
			$sql    =   "SELECT * FROM ".$table_name." WHERE ".$field_name." LIKE '".$key_prefix."%'";
			$query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }

        function deleteBotEnem($table_name, $field_name, $key_prefix) {
            $sql    =   "DELETE FROM ".$table_name." WHERE ".$field_name." LIKE '".$key_prefix."%'";
            $this->db->query($sql);
        }

        function getEnemDataPagination($sql_param = NULL, $filter = NULL, $limit = NULL) {
            // var_dump($filter); exit();
            if(!empty($sql_param) && !empty($filter)) {

                if($filter === 'create_sql') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT ".$sql_param." LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT ".$sql_param."";
                    }
                }

            }

            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }

        function addUserRoleEnem($data) {
            $sql    =   "INSERT INTO enem_user_role (name, status_role, date_created)
                            VALUES('".$data['name']."', '".$data['status_role']."', now())";

            $this->db->query($sql);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
            }
        }

        // For enem user

        function getEnemUserData($filter = NULL, $filter_key = NULL, $limit = NULL) {
            if(!empty($filter) && !empty($filter_key)) {
                if($filter === 'email') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM enem_user WHERE email='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM enem_user WHERE email='".$filter_key."'";
                    }
                } elseif ($filter === 'username') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM enem_user WHERE username='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM enem_user WHERE username='".$filter_key."'";
                    }
                } elseif ($filter === 'nik') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM enem_user WHERE nik='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM enem_user WHERE nik='".$filter_key."'";
                    }
                } elseif ($filter === 'id') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM enem_user WHERE user_id='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM enem_user WHERE user_id='".$filter_key."'";
                    }
                } elseif ($filter === 'create_sql') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM enem_user WHERE ".$filter_key." LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM enem_user WHERE ".$filter_key."";
                    }
                }
            } else {
                if(is_array($limit)) {
                    $sql    =   "SELECT * FROM enem_user LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                } else {
                    $sql    =   "SELECT * FROM enem_user";
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

        /** Old Code **/

        function offTokenUserManagement($token){
            $status    =   "enem_token_status='0'";

            $sql       =   "UPDATE enem_token_user_management SET ".$status.", enem_token_off=now() WHERE enem_token ='".$token."'";
            $this->db->query($sql);
        }
        function addEnemUserAdmin($data){
            $sql    =   "INSERT INTO enem_user_admin (enem_username,enem_password,enem_email,enem_user_status,enem_date_user_created)
                            VALUES('".$data['enem_username']."','".$data['enem_password']."','".$data['enem_email']."','".$data['enem_user_status']."',now())";
            $this->db->query($sql);
        }
        function getDataEnemAdminByEmail($email){
            $sql    =   "SELECT * FROM enem_user_admin WHERE enem_email='".$email."'";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }
        function getDataEnemAdminByUsername($username){
            $sql    =   "SELECT * FROM enem_user_admin WHERE enem_username='".$username."'";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }
        function getDataEnemAdminById($id){
            $sql    =   "SELECT * FROM enem_user_admin WHERE id_enem_user='".$id."'";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }
        function updateStepOne($data){
            $enem_name = "enem_name=".$data['enem_name']."";
            $enem_step_one = "enem_step_one='1'";

            $sql       =   "UPDATE enem_user_admin SET ".$enem_name.", ".$enem_step_one." WHERE id_enem_user ='".$data['enem_id']."'";
            $this->db->query($sql);
        }
        function updateStepTwo($data){
            $enem_step_one = "enem_step_two='1'";

            $sql       =   "UPDATE enem_user_admin SET ".$enem_step_one." WHERE id_enem_user ='".$data['enem_id']."'";
            $this->db->query($sql);
        }
        function addNewItems($data){
            $sql    =   "INSERT INTO items (nama,kategori,harga,deskripsi,date_create)
                            VALUES('".$data['nama']."','".$data['category']."','".$data['price']."','".$data['description']."',now())";
            $this->db->query($sql);

        }
        function setPromo($items_id){
                $promo         =   "status_promo='1'";

                $sql       =   "UPDATE items SET ".$promo." WHERE id_items ='".$items_id."'";
                $this->db->query($sql);
        }
        function unsetPromo($items_id){
            $promo         =   "status_promo='0'";

            $sql       =   "UPDATE items SET ".$promo." WHERE id_items ='".$items_id."'";
            $this->db->query($sql);
        }
        function getDataAllPromo(){
            $sql    =   "SELECT * FROM items WHERE status_promo='1'";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }
        function getDataPromoById($id){
            $sql    =   "SELECT * FROM items WHERE id_items='".$id."' && status_promo='1'";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }
        function getDataHotPromo($limit){
            $sql    =   "SELECT * FROM items WHERE status_promo='1' LIMIT ".$limit."";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }
        function getDataItemsByCategory($c){
            $sql    =   "SELECT * FROM items WHERE kategori='".$c."'";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }
        function getDataItemsById($i){
            $sql    =   "SELECT * FROM items WHERE id_items='".$i."'";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }
        function getDataItemsDetilByCategory($c,$q){
            $sql    =   "SELECT * FROM items WHERE kategori='".$c."' && id_items='".$q."'";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }
        function getDataAllItems(){
            $sql    =   "SELECT * FROM items";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }
        function getDataAllItemsByName($name){
            $sql    =   "SELECT * FROM items WHERE nama LIKE '%".$name."%'";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }
        function getDataAllUser(){
            $sql    =   "SELECT * FROM user";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }
        function getDataCartByIdUser($u){
            $sql    =   "SELECT * FROM cart WHERE id_user='".$u."'";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }
        function addToCart($data){
            $sql    =   "INSERT INTO cart (id_user,id_items,nama,deskripsi,harga,jumlah_order,total,tanggal_order)
                            VALUES('".$data['user_id']."','".$data['id_items']."','".$data['nama']."','".$data['deskripsi']."','".$data['harga']."','".$data['jumlah']."','".$data['total']."',now())";
            $this->db->query($sql);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
            }
        }

        function userOn($user_id){
            $sql    =   "UPDATE user SET status_online='1' WHERE id_user ='".$user_id."'";
            $this->db->query($sql);
        }
        function userOff($user_id){
            $sql    =   "UPDATE user SET status_online='0' WHERE id_user='".$user_id."'";
            $this->db->query($sql);

            /*$ip     =   $_SERVER['REMOTE_ADDR'];
            $sql_insert_log =   "INSERT INTO user_log (user_id,action,date,ip)
                                    VALUES('".$user_id."',2,now(),'".$ip."')";
            $this->db->query($sql_insert_log);*/
        }
        function addNewUser($data){
            $sql    =   "INSERT INTO user (nama,email,username,password,date_join)
                            VALUES('".$data['nama']."','".$data['email']."','".$data['username']."','".$data['password']."',now())";
            $this->db->query($sql);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
            }
        }

        function getDataUserById($id){
            $sql    =   "SELECT * FROM user WHERE id_user='".$id."'";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }
        function getDataUserByEmail($email){
            $sql    =   "SELECT * FROM user WHERE email='".$email."'";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }
        function getDataUserByUsername($username){
            $sql    =   "SELECT * FROM user WHERE username='".$username."'";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }

        function updateDataAccount($data,$user_id){
            $fname     =   "first_name='".$data['first_name']."'";
            $lname     =   "last_name='".$data['last_name']."'";
            $username  =   "username='".$data['username']."'";
            //$email     =   "email='".$data['email']."'";
            //$password  =   "password='".$data['password']."'";
            //$user_id   =   $this->db->insert_id();
            $sql       =   "UPDATE user SET ".$fname.",".$lname.",".$username." WHERE user_id = '".$user_id."'";
            $this->db->query($sql);
            //return 1;
            //die(var_dump($sql));
            //$this->db->query($sql_insert_log);
        }
        /*
        function getDataUserForLogin($variabel,$password){
            $sql    =   "SELECT * FROM user WHERE email='".$variabel."' or username='".$variabel."' && password='".$password."'";
            $query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }*/
        function recordLogin($user_id){
            $ip     =   $_SERVER['REMOTE_ADDR'];
            $sql_insert_log_login =   "INSERT INTO user_log (user_id,action,date,ip)
                                    VALUES('".$user_id."',1,now(),'".$ip."')";
            $this->db->query($sql_insert_log_login);
        }
        function updateProfile($data){
            //$fname     =   "first_name='".$data['first_name']."'";
            //$lname     =   "last_name='".$data['last_name']."'";
            //$username  =   "username='".$data['username']."'";
            $user_id         =   "'".$data['user_id']."'";
            $bio             =   "biography='".$data['bio']."'";
            $location        =   "location='".$data['location']."'";
            $url             =   "url='".$data['url']."'";
            //$step_one  =   "step_one=1";
            //$email     =   "email='".$data['email']."'";
            //$password  =   "password='".$data['password']."'";
            //$user_id   =   $this->db->insert_id();
            $sql       =   "UPDATE user SET ".$bio.",".$location.",".$url." WHERE user_id = ".$user_id."";
            //die($sql);
            $this->db->query($sql);
        }
        function completeProfile($user_id,$option){
                $option_one         =   "step_one='1'";
                $option_two         =   "step_two='1'";

                if($option == 1){
                    $option_result  =   $option_one;
                } else {
                    $option_result  =   $option_two;
                }

                $sql       =   "UPDATE user SET ".$option_result." WHERE user_id ='".$user_id."'";
                $this->db->query($sql);

        }
    }
?>

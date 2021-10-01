<?php


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cogs_Model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }

    function users_list(){
        $list = $this->db->query("SELECT * from users where status=1 ")->result_array();
        $data = array();
        foreach($list as $row){
            $row['name'] = ucwords($row['lastname'].", ".$row['firstname']." ".$row['middlename']);
            $data[] = $row;
        }
        return json_encode($data);
    }
    function log_list(){
        $list = $this->db->query("SELECT l.*,concat(u.lastname,', ',u.firstname,' ',u.middlename) as uname from logs l inner join users u on u.id = l.user_id  order by id desc ")->result_array();
        $data = array();
        foreach($list as $row){
            $row['date_created'] = date("M d, Y H:i",strtotime($row['date_created']));
            $data[] = $row;
        }
        return json_encode($data);
    }

    function save_users(){
        extract($_POST);
        $data['firstname'] =  $firstname;
        $data['lastname'] =  $lastname;
        $data['middlename'] =  $middlename;
        $data['username'] =  $username;
        if(isset($type))
        $data['type'] =  $type;
        if(!empty($password))
            $data['password'] =  md5($password);
            
        if(empty($id)){
            $chk = $this->db->get_where('users',array("username"=>$username,'status'=>1))->num_rows();
            if($chk > 0){
                return json_encode(array("status"=>2,"msg"=>"Username already exist."));
                exit;
            }
            $save = $this->db->insert("users",$data);
        }else{
            $chk = $this->db->get_where('users',array("username"=>$username,'status'=>1,'id!='=>$id))->num_rows();
            if($chk > 0){
                return json_encode(array("status"=>2,"msg"=>"Username already exist."));
                exit;
            }
            $save = $this->db->update("users",$data,array('id'=>$id));
        }
        if($save)
         return json_encode(array("status"=>1));

    }

    function save_log($msg="",$action = ''){
        $data['log_msg'] = $msg;
        $data['action_made'] = $action;
        $data['user_id'] = $_SESSION['login_id'];

        $this->db->insert("logs",$data);

    }
}
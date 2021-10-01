<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cogs extends CI_Controller {
	function __construct() {
        parent::__construct();
		if(!isset($_SESSION['login_id']))
		header('location:'.base_url('login'));
		// $this->load->model("Cogs_Model",'cogs');
        
    }
	
	public function index()
	{
        
    }

    function users(){
        $data['title'] =" Users List";
        $data['page'] = 'cogs/users';
        $this->load->view("index",$data);
    }
    function logs(){
        $data['title'] =" System Logs List";
        $data['page'] = 'cogs/logs';
        $this->load->view("index",$data);
    }

    function manage_users($id=''){
        $data['id'] = $id;
        $this->load->view("cogs/manage_users",$data);

    }

    function save_users(){
        $save = $this->cogs->save_users();
        if($save)
        echo $save;
    }

    function users_list(){
        $list =$this->cogs->users_list();
        if($list)
        echo $list;
    }
    function system_logs(){
        $list =$this->cogs->log_list();
        if($list)
        echo $list;
    }
}
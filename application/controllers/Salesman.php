<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salesman extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Salesman_Model','salesman');
        if(!isset($_SESSION['login_id']))
		header('location:'.base_url('login'));

        
    }
	
	public function index()
	{
        $page['title'] = 'Salesman List';
        $page['page'] = "salesman/list";
		$this->load->view('index',$page);
    }

    function manage($id='')
	{
        
        $page['id'] = $id;
		$this->load->view('salesman/manage_salesman',$page);
    }
    function save_salesman(){

        $save = $this->salesman->save();
        if($save)
        echo 1;

    }
    function load_list(){
        $data = $this->salesman->load_list();
        if($data)
        echo $data;
    }
    function remove(){
        $delete = $this->salesman->remove();
        if($delete)
        echo $delete;
    }
}

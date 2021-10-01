<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Supplier_model','supplier');
        if(!isset($_SESSION['login_id']))
		header('location:'.base_url('login'));

        
    }
	
	public function index()
	{
        $page['title'] = 'Supplier';
        $page['page'] = "supplier/list";
		$this->load->view('index',$page);
    }

    function manage($type = 'add',$id='')
	{
        $page['title'] = ucwords($type). ' Supplier'  ;
        $page['page'] = "supplier/manage_supplier";
        $page['id'] = $id;
		$this->load->view('index',$page);
    }
    function save_supplier(){

        $save = $this->supplier->save();
        if($save)
        echo 1;

    }
    function load_list(){
        $data = $this->supplier->load_list();
        if($data)
        echo $data;
    }
    function remove(){
        $delete = $this->supplier->remove();
        if($delete)
        echo $delete;
    }
}

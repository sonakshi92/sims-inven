<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Customer_Model','customer');
        if(!isset($_SESSION['login_id']))
		header('location:'.base_url('login'));

        
    }
	
	public function index()
	{
        $page['title'] = 'Customer';
        $page['page'] = "customer/list";
		$this->load->view('index',$page);
    }
    function view($id){
        $data['id']= $id;
        $data['title'] = "View Customer";
        $data['page'] = "customer/view_customer";
        $this->load->view("index",$data);
    }

    function manage($type = 'add',$id='')
	{
        $page['title'] = ucwords($type). ' Customer'  ;
        $page['page'] = "customer/manage_customer";
        $page['id'] = $id;
		$this->load->view('index',$page);
    }
    function print_customer($id='')
	{
        $page['id'] = $id;
		$this->load->view('customer/print_customer',$page);
    }
    function save_customer(){

        $save = $this->customer->save();
        if($save)
        echo 1;

    }
    function load_list(){
        $data = $this->customer->load_list();
        if($data)
        echo $data;
    }
    function remove(){
        $delete = $this->customer->remove();
        if($delete)
        echo $delete;
    }
}

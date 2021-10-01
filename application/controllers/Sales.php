<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Sales_Model','sales');
        if(!isset($_SESSION['login_id']))
		header('location:'.base_url('login'));

        
    }
	
	public function index()
	{
        $page['title'] = 'Sales';
        $page['page'] = "sales/sales";
		$this->load->view('index',$page);
    }

    function manage($type = 'add',$id='')
	{
        $page['title'] = ucwords($type). ' Sales'  ;
        $page['page'] = "sales/manage";
        $page['id'] = $id;
		$this->load->view('index',$page);
    }
    function save_sales(){

        $save = $this->sales->save();
        if($save)
        echo $save;

    }
    function view($id){
        $data['id'] = $id;
        $data['title'] = "View Sale";
        $data['page'] = "sales/view_sales";
		$this->load->view('index',$data);
    }
    function print_sales($id){
        $data['id'] = $id;
		$this->load->view('sales/print_sales',$data);
    }
    function print_return($id){
        $data['id'] = $id;
		$this->load->view('sales/print_return',$data);
	}
    function return(){
        $data['title'] = "Sales Retun List";
        $data['page'] = "sales/return";
		$this->load->view('index',$data);
    }
    function manage_return($id=""){
        $data['id'] = $id;
        $data['title'] = "Sales Retun List";
        $data['page'] = "sales/manage_return";
		$this->load->view('index',$data);
    }
    function view_return($id=""){
        $data['id'] = $id;
        $data['title'] = "View Sales Retun";
        $data['page'] = "sales/view_return";
		$this->load->view('index',$data);
    }
    function load_list(){
        $data = $this->sales->load_list();
        if($data)
        echo $data;
    }
    function remove(){
        $delete = $this->sales->remove();
        if($delete)
        echo $delete;
    }
    function check_prod_qty(){
        $chk = $this->sales->check_prod_qty();
        if($chk)
        echo $chk;
    }
    function new_payment($id,$pid = ''){
        $data['id'] = $id;
        $data['pid'] = $pid;
        $this->load->view('sales/new_payment',$data);
    }

    function save_payment(){
        $save = $this->sales->save_payment();
        if($save)
        echo $save;
    }
    function delete_payment(){
        $delete = $this->sales->delete_payment();
        if($delete)
        echo $delete;
    }

    function remove_sales(){
        $delete = $this->sales->remove_sales();
        if($delete)
        echo $delete;
    }

    function save_sales_return(){
        $save = $this->sales->save_sales_return();

        if($save){
            echo $save;
        }
    }
    function return_list(){
        $list = $this->sales->return_list();
        if($list)
            echo $list;
    }
    function remove_sales_rerturn(){
        $remove= $this->sales->remove_sales_rerturn();
        if($remove)
        echo $remove;
    }
}

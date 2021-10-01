<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchases extends CI_Controller {
	function __construct() {
        parent::__construct();
		if(!isset($_SESSION['login_id']))
		header('location:'.base_url('login'));
		$this->load->model("Purchases_Model",'purchases');
        
    }
	
	public function index()
	{
        $page['title'] = 'Home';
        $page['page'] = "purchases/purchases";
		$this->load->view('index',$page);
	}
	function view($id){
        $data['id'] = $id;
        $data['title'] = "View PO";
        $data['page'] = "purchases/view_po";
		$this->load->view('index',$data);
	}
	function print_po($id){
        $data['id'] = $id;
		$this->load->view('purchases/print_po',$data);
	}

	function print_return($id){
        $data['id'] = $id;
		$this->load->view('purchases/print_return',$data);
	}
	function new_payment($id,$pid = ''){
        $data['id'] = $id;
		$data['pid'] = $pid;
		
        $this->load->view('purchases/new_payment',$data);
    }

    function save_payment(){
        $save = $this->purchases->save_payment();
        if($save)
        echo $save;
    }
	function receiving()
	{
        $page['title'] = 'Receiving';
        $page['page'] = "purchases/receiving";
		$this->load->view('index',$page);
	}
	function po_select(){
		$this->load->view('purchases/receive_po');

	}
	function manage($id='')
	{
        $page['title'] = 'Manage Purchase Order';
        $page['page'] = "purchases/manage";
        $page['id'] =$id;
		$this->load->view('index',$page);
	}
	function manage_receiving($id='',$rid='')
	{
        $page['title'] = 'Manage Receiving';
        $page['page'] = "purchases/manage_receiving";
        $page['id'] =$id;
        $page['rid'] =$rid;
		$this->load->view('index',$page);
	}
	function return(){
        $data['title'] = "PO Retun List";
        $data['page'] = "purchases/return";
		$this->load->view('index',$data);
    }
    function manage_return($id=""){
        $data['id'] = $id;
        $data['title'] = "PO Retun List";
        $data['page'] = "purchases/manage_return";
		$this->load->view('index',$data);
	}
	function view_return($id=""){
        $data['id'] = $id;
        $data['title'] = "View PO Retun List";
        $data['page'] = "purchases/view_return";
		$this->load->view('index',$data);
	}
	function remove_purchases_return(){
		$delete = $this->purchases->remove_purchases_return();
		if($delete)
		echo $delete;
	}
	function save_purchases_return(){
		$save = $this->purchases->save_purchases_return();
		if($save)
		echo $save;
	}
	function save_po(){
		$save = $this->purchases->save_po();
		if($save)
		echo $save;
	}
	function save_receiving(){
		$save = $this->purchases->save_receiving();
		if($save)
		echo $save;
	}
	function remove_po(){
		$remove = $this->purchases->remove_po();
		if($remove)
		echo $remove;
	}
	function remove_receiving(){
		$remove = $this->purchases->remove_receiving();
		if($remove)
		echo $remove;
	}
	function load_list(){
		$list = $this->purchases->load_list();
		if($list)
		echo $list;
	}
	function load_list_receiving(){
		$list = $this->purchases->load_list_receiving();
		if($list)
		echo $list;
	}

	function load_po(){
		$list = $this->purchases->load_po();
		if($list)
		echo $list;
	}
	function validate_po($id = ''){
		$data['id'] = $id;
		$this->load->view('purchases/validate_po',$data);

	}
	function save_validation(){
		$save = $this->purchases->save_validation();
		if($save)
			echo $save;
	}
	function return_list(){
        $list = $this->purchases->return_list();
        if($list)
            echo $list;
    }
    function remove_purchases_rerturn(){
        $remove= $this->purchases->remove_purchases_rerturn();
        if($remove)
        echo $remove;
	}
	function delete_payment(){
		$delete= $this->purchases->delete_payment();
		if($delete){
			echo $delete;
		}
	}
}

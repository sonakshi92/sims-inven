<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends CI_Controller {
	function __construct() {
        parent::__construct();
		if(!isset($_SESSION['login_id']))
		header('location:'.base_url('login'));
		$this->load->model("Inventory_Model",'inventory');
        
    }
	
	public function index()
	{
        $page['title'] = 'Home';
        $page['page'] = "inventory/inventory";
		$this->load->view('index',$page);
    }

    function load_inventory(){
        $list = $this->inventory->load_inventory();
        if($list)
        echo $list;
    }

    function print_inventory()
	{
		$this->load->view('inventory/print_inventory');
    }
}
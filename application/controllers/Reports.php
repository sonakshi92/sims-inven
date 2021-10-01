<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {
	function __construct() {
        parent::__construct();
		if(!isset($_SESSION['login_id']))
		header('location:'.base_url('login'));
		$this->load->model("Sales_Model",'sales');
		$this->load->model("Purchases_Model",'purchases');
        
    }
	
	public function index()
	{
        
    }

    function sales_report($from="", $to="", $customer_id="all", $salesman_id=""){
        $data['title'] = "Sales Report";        
        $data['page'] = "reports/sales_report"; 
        $data['from'] = $from;
        $data['to'] = $to;
        $data['customer_id'] = $customer_id;
        $data['salesman_id'] = $salesman_id;
        $this->load->view('index',$data);       
    }
    function purchases_report($from="", $to=""){
        $data['title'] = "Purchases Report";        
        $data['page'] = "reports/purchases_report"; 
        $data['from'] = $from;
        $data['to'] = $to;
        $this->load->view('index',$data);       
    }
    function profit_report($from="", $to=""){
        $data['title'] = "Profit Report";        
        $data['page'] = "reports/profit_report"; 
        $data['from'] = $from;
        $data['to'] = $to;
        $this->load->view('index',$data);       
    }
    function sales_rep_list(){
        $data = $this->sales->get_report_list();
        if($data)
        echo $data;
    }
     function profit_rep_list(){
        $data = $this->sales->profit_rep_list();
        if($data)
        echo $data;
    }
    function purchases_rep_list(){
        $data = $this->purchases->get_report_list();
        if($data)
        echo $data;
    }

}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	function __construct() {
        parent::__construct();
		if(!isset($_SESSION['login_id']))
		header('location:'.base_url('login'));

        
    }
	
	public function index()
	{
        $page['title'] = 'Home';
        $page['page'] = "dashboard";
		$this->load->view('index',$page);
	}
	function stats($month = ""){
		if(!empty($month)){
			$month = $month."-01";
			$cdate_from = date('Y-m',strtotime($month)).'-01';
			$m = date('m',strtotime($month));
			$y = date('Y',strtotime($month));

			$dnumber = cal_days_in_month(CAL_GREGORIAN, $m, $y);
			$cdate_to = date('Y-m',strtotime($month)).'-'.$dnumber;

			for($i = 1 ; $i <= $dnumber ; $i++){
  			  $i = sprintf("%'.02d", $i);
			  $days[] = $y.'-'.$m.'-'.$i;
			  $sday[$y.'-'.$m.'-'.$i] = 0;
  			  $i = number_format($i);
			}

			$sales = $this->db->query("SELECT * FROM sales where date(date_created) between '$cdate_from' and '$cdate_to' and status = 1")->result_array();

			foreach($sales as $row){
				$sday[date('Y-m-d',strtotime($row['date_created']))] += ($row['total_amount']);
			}
			echo json_encode(array('days'=>$days,'sday'=>$sday));
		}

	}
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Product_Model','product');
        if(!isset($_SESSION['login_id']))
		header('location:'.base_url('login'));

        
    }
	
	public function index()
	{
        $page['title'] = 'product';
        $page['page'] = "product/list";
		$this->load->view('index',$page);
    }

    function manage($type = 'add',$id='')
	{
        $page['title'] = ucwords($type). ' product'  ;
        $page['page'] = "product/manage_product";
        $page['id'] = $id;
		$this->load->view('index',$page);
    }
    function save_product(){

        $save = $this->product->save();
        if($save)
        echo 1;

    }
    function view($id=''){
        $data['id'] = $id;
        $data['title'] = 'Product Information';
        $data['page'] = 'product/view';
        $this->load->view('index',$data);
    }
    function load_list(){
        $data = $this->product->load_list();
        if($data)
        echo $data;
    }
    function remove(){
        $delete = $this->product->remove();
        if($delete)
        echo $delete;
    }
    function manage_price($id=''){
        $data['id'] = $id;
        $this->load->view('product/manage_price',$data);
    }
    function save_price(){
        $save = $this->product->save_price();
        if($save)
        echo $save;
    }
}

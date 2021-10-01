<?php


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_Model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }

    function save(){
       extract($_POST);
       
           $data['name'] = $name;
           $data['description'] = $description;
           $data['unit'] = $unit;
           if(isset($is_bulk) && $is_bulk == 'on'){
               $data['is_bulk'] = 1;
               $data['convert_unit'] = $convert_unit;
               $data['convert_qty'] = $convert_qty;
           }else{
            $data['is_bulk'] = 0;
            $data['convert_unit'] = '';
            $data['convert_qty'] = '';
           }
          
       if(empty($id)){
           $sku = sprintf("%'.09d\n", mt_rand(1,999999999999));
           $i = 1;
        while($i == 1){
            $chk = $this->db->get_where('products',array('sku'=>$sku))->num_rows();
            if($chk > 0)
             $sku = sprintf("%'.09d\n", mt_rand(1,999999999999));
             else
             $i= 0;
        }
        $data['sku'] = $sku;
           $save = $this->db->insert('products',$data);
           if($save){
               $this->session->set_flashdata('save_product',1);
            echo $this->db->insert_id();
        }
       }else{
        $save = $this->db->update('products',$data,array('id'=>$id));
            if($save){
            $this->session->set_flashdata('save_product',2);
            if(empty($id)){
                $this->cogs->save_log("added $sku into product list. ","Create");
            }else{
                $pro = $this->db->get_where("products",array("id"=>$id))->row()->sku;
                $this->cogs->save_log("updated $pro product data. ","Update");
            }
         echo $id;
     }
       }
       
    }

    function load_list(){

        $qry = $this->db->query("SELECT * FROM products where status = 1 ");
        $data=array();
        foreach($qry->result_array() as $row){
            $data[] = $row;
        }
        return json_encode($data);
    }

    function remove(){
        extract($_POST);
        $delete = $this->db->update('products',array('status'=>0),array('id'=>$id));
        if($id){
            $pro = $this->db->get_where("products",array("id"=>$id))->row()->sku;
            $this->cogs->save_log("deleted $pro product from the product list. ","Deleted");
        return 1;
        }
    }
    function save_price(){
        extract($_POST);
        $data['product_id'] = $product_id;
        $data['price'] = $price;
        $data['date_effective'] = $date_effective;
        $data['description'] = $description;
        $save = $this->db->insert('price_list',$data);
        if($save){
            $this->session->set_flashdata('action_save_price',1);
            $pro = $this->db->get_where("products",array("id"=>$product_id))->row()->sku;
            $this->cogs->save_log("added $pro product price into the product pirce list. ","Added");
        return 1;
        }
    }

}
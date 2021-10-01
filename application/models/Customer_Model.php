<?php


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customer_Model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }

    function save(){
       extract($_POST);
       
       if(empty($id)){
           $data['name'] = $name;
           $data['address'] = $address;
           $data['contact_person'] = $contact_person;
           $data['type'] = $type;
           $data['contact'] = $contact;
           $save = $this->db->insert('customers',$data);
           if($save){
                $this->session->set_flashdata('customer_save',1);
                $this->cogs->save_log("added $name into customer list. ","Create");
                return 1;
            }
       }else{
        $cus = $this->db->get_where("customers",array('id'=>$id))->row();

        $data['name'] = $name;
        $data['address'] = $address;
        $data['contact_person'] = $contact_person;
        $data['contact'] = $contact;
        $data['type'] = $type;
           $save = $this->db->update('customers',$data,array('id'=>$id));
        if($save){
                $this->cogs->save_log("update ".$cus->name." customer data. ","Update");
                $this->session->set_flashdata('customer_save',2);
             return 1;
         }
       }
       
    }

    function load_list(){

        $qry = $this->db->query("SELECT * FROM customers where status = 1 ");
        $data=array();
        foreach($qry->result_array() as $row){
            $data[] = $row;
        }
        return json_encode($data);
    }

    function remove(){
        extract($_POST);
        $cus = $this->db->get_where("customers",array('id'=>$id))->row();
        $rem = $this->db->update('customers',array('status'=>0),array('id'=>$id));
        if($rem){
                $this->cogs->save_log("deleted ".$cus->name." from customer list. ","Delete");
                return 1;
        }
    }


}
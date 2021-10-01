<?php


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Supplier_Model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }

    function save(){
       extract($_POST);
       
       if(empty($id)){
           $data['name'] = $name;
           $data['address'] = $address;
           $data['contact_person'] = $contact_person;
           $data['contact_number'] = $contact_number;
           $save = $this->db->insert('supplier',$data);
           if($save){
                $this->cogs->save_log("added $name into supplier list. ","Create");
                $this->session->set_flashdata('supplier_save',1);
                return 1;
            }
       }else{
        $sup = $this->db->get_where("supplier",array('id'=>$id))->row();
        $data['name'] = $name;
        $data['address'] = $address;
        $data['contact_person'] = $contact_person;
        $data['contact_number'] = $contact_number;
        $save = $this->db->update('supplier',$data,array('id'=>$id));
        if($save){
                $this->cogs->save_log("update ".$sup->name." supplier data. ","Update");
                $this->session->set_flashdata('supplier_save',2);
             return 1;
         }
       }
       
    }

    function load_list(){

        $qry = $this->db->query("SELECT * FROM supplier where status = 1 ");
        $data=array();
        foreach($qry->result_array() as $row){
            $data[] = $row;
        }
        return json_encode($data);
    }

    function remove(){
        extract($_POST);
        $sup = $this->db->get_where("supplier",array('id'=>$id))->row();
        $rem = $this->db->update('supplier',array('status'=>0),array('id'=>$id));
        if($rem){
                $this->cogs->save_log("deleted ".$sup->name." fram supplier list. ","Deleted");
                return 1;
        }
    }


}
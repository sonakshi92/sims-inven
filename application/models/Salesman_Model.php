<?php


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class salesman_Model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }

    function save(){
       extract($_POST);
       $data['firstname'] = $firstname;
       $data['lastname'] = $lastname;
       $data['middlename'] = $middlename;
       if(empty($id)){
          
           $save = $this->db->insert('salesman',$data);
           if($save){
                $name = ucwords($lastname.', '.$firstname.' '.$middlename);
                $this->session->set_flashdata('salesman_save',1);
                $this->cogs->save_log("added $name into salesman list. ","Create");
                return 1;
            }
       }else{
        $cus = $this->db->get_where("salesman",array('id'=>$id))->row();

      
           $save = $this->db->update('salesman',$data,array('id'=>$id));
        if($save){
                $name = ucwords($cus->lastname.', '.$cus->firstname.' '.$cus->middlename);
                $this->cogs->save_log("update ".$name." salesman data. ","Update");
                $this->session->set_flashdata('salesman_save',2);
             return 1;
         }
       }
       
    }

    function load_list(){

        $qry = $this->db->query("SELECT * FROM salesman where status = 1 ");
        $data=array();
        foreach($qry->result_array() as $row){
                $row['name'] = ucwords($row['lastname'].', '.$row['firstname'].' '.$row['middlename']);
                $data[] = $row;
        }
        return json_encode($data);
    }

    function remove(){
        extract($_POST);
        $cus = $this->db->get_where("salesman",array('id'=>$id))->row();
        $rem = $this->db->update('salesman',array('status'=>0),array('id'=>$id));
        if($rem){
                $name = ucwords($cus->lastname.', '.$cus->firstname.' '.$cus->middlename);
                $this->cogs->save_log("deleted ".$name." from salesman list. ","Delete");
                return 1;
        }
    }


}
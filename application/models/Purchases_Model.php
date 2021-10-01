<?php


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchases_Model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }

    function save_po(){
       extract($_POST);
       
           $data['supplier_id'] = $supplier_id;
           $data['total_amount'] = $total_amount;
           $data['user_id'] = $_SESSION['login_id'];
       if(empty($id)){
           $ref = $this->db->query("SELECT * FROM purchases order by ABS(ref_no) desc");
           if($ref->num_rows() > 0){
               $ref = intval($ref->row()->ref_no) + 1;
           }else{
            $ref = '1';
           }
           $ref = sprintf("%'04d\n", $ref);
           $data['ref_no'] = $ref;
           $ref_no = $ref;
           $save = $this->db->insert('purchases',$data);
           if($save){
               $id = $this->db->insert_id();
              foreach($product_id as $k => $v){
                  $data =array();
                  $data['po_id'] = $id;
                  $data['product_id'] = $product_id[$k];
                  $data['qty'] = $qty[$k];
                  $data['unit'] = $unit[$k];
                  $data['unit_price'] = $unit_price[$k];
                  $data['total_amount'] = $total_amount[$k];
                  $data2[] = $data;
              }
              if(isset($data2))
              $this->db->insert_batch('po_items',$data2);
               $this->session->set_flashdata('save_purchases',1);
                $this->cogs->save_log("added ".$ref_no." purchase order into the purcase order list. ","Added");
            return json_encode(array('status'=>1,'id'=>$id,"type"=>1));
        }
       }else{
        if(isset($payment_mode))
            $data['payment_mode'] = $payment_mode;
           $save = $this->db->update('purchases',$data,array('id'=>$id));
           if($save){
               if(isset($payment_id)){
                    $pay['po_id'] = $id;
                    $pay['amount'] = $amount;
                    $pay['remarks'] = $remarks;
                    $pay['invoice'] = $invoice;
                    $pay['payment_method'] = $payment_method;
                    $pay['ref_no'] = $pref_no;
                    if($payment_id > 0)
                    $this->db->update('po_payments',$pay,array('id'=>$payment_id));
                    else
                    $this->db->insert('po_payments',$pay);
                }
           $this->db->query("DELETE FROM po_items where po_id = $id and id not in (".implode(",",$item_id).") ");
              foreach($product_id as $k => $v){
                  $data =array();
                  $data['po_id'] = $id;
                  $data['product_id'] = $product_id[$k];
                  $data['qty'] = $qty[$k];
                  $data['unit'] = $unit[$k];
                  $data['unit_price'] = $unit_price[$k];
                  $data['total_amount'] = $total_amount[$k];
                  if($item_id[$k] > 0)
                  $data2[] = $this->db->update('po_items',$data,array('id'=>$item_id[$k]));
                  else
                  $data2[] = $this->db->insert('po_items',$data);
                  
              }
              if(isset($data2))
              $pref_no = $this->db->get_where("purchases",array("id"=>$id))->row()->ref_no;
               $this->session->set_flashdata('save_purchases',1);
            $this->cogs->save_log("updated ".$pref_no." purchase order data. ","Update");

            return json_encode(array('status'=>1,'id'=>$id,"type"=>2));
        }
       }
       
    }
    function save_payment(){
        extract($_POST);
            $pay['po_id'] = $po_id;
            $pay['amount'] = $amount;
            $pay['remarks'] = $remarks;
            $pay['invoice'] = $invoice;
            $pay['payment_method'] = $payment_method;
            $pay['ref_no'] = $pref_no;
            $ref = $this->db->get_where("purchases",array("id"=>$po_id))->row()->ref_no;
        if(empty($id)){
            $this->cogs->save_log("added ".$ref." payments. ","Create");
            $save = $this->db->insert('po_payments',$pay);
           
        }else{
            $this->cogs->save_log("updated ".$ref." payments data. ","Updated");
            $save = $this->db->update('po_payments',$pay,array('id'=>$id));
            
        }
        if($save){
            $this->session->set_flashdata('save_purchases',1);
                return 1;
        }
    }

    function save_receiving(){
        extract($_POST);
           $data['po_id'] = $po_id;
           $data['po_ref'] = $po_ref;
           $data['total_amount'] = $total_amount;
           $data['user_id'] = $_SESSION['login_id'];
           $data['receive_through'] = $received_through;
           $data['invoice'] = $invoice;
       if(empty($id)){
           $save = $this->db->insert('receiving',$data);
           if($save){
               $id = $this->db->insert_id();
              foreach($product_id as $k => $v){
                  $data =array();
                  $data['receiving_id'] = $id;
                  $data['product_id'] = $product_id[$k];
                  $data['qty'] = $qty[$k];
                  $data['unit'] = $unit[$k];
                  $data['unit_price'] = $unit_price[$k];
                  $data['remarks'] = "Received from ".$po_ref;
                  $data2[] = $data;
              }
              if(isset($data2))
              $this->db->insert_batch('inventory',$data2);
              $this->db->update('purchases',array('status'=> 2),array('id'=>$po_id));
               $this->session->set_flashdata('save_receiving',1);
            $this->cogs->save_log("received ".$po_ref.". ","Create");
            return json_encode(array('status'=>1,'id'=>$id));
        }
       }else{
        $save = $this->db->update('receiving',$data,array('id'=>$id));
        if($save){
           $this->db->query("DELETE FROM inventory where receiving_id = $id and id not in (".implode(",",$inv_id).") ");
           foreach($product_id as $k => $v){
               $data =array();
               $data['receiving_id'] = $id;
               $data['product_id'] = $product_id[$k];
               $data['qty'] = $qty[$k];
               $data['unit'] = $unit[$k];
               $data['unit_price'] = $unit_price[$k];
               $data['remarks'] = "Received from ".$po_ref;
                if($inv_id > 0)
                  $data2[] = $this->db->update('inventory',$data,array('id'=>$inv_id[$k]));
                else
                  $data2[] = $this->db->insert('inventory',$data);
           }
           if(isset($data2))
           $this->db->update('purchases',array('status'=> 2),array('id'=>$po_id));
            $this->session->set_flashdata('save_receiving',1);
            $this->cogs->save_log("updated ".$po_ref." receiving data. ","Update");
            return json_encode(array('status'=>1,'id'=>$id));
     } 
       }
        
    }

    function load_list(){

        $qry = $this->db->query("SELECT p.*,s.name as sname FROM purchases p inner join supplier s on s.id = p.supplier_id where p.status != 0 ");
        $data=array();
        $po_ids = array_column($qry->result_array(), 'id','id');
        if(count($po_ids) > 0){
        $po_ids = implode(',',$po_ids);
        $validate = $this->db->query("SELECT *,concat(form_id,'_',`type`) as validate FROM validation where `form_type` ='po' and form_id in (".$po_ids.") ");
        $validate_arr = array_column($validate->result_array() ,'validate','validate');
        }
        foreach($qry->result_array() as $row){
                $row['approved'] = 0;
            
            if(isset($validate_arr[$row['id'].'_approved'])){
                $row['approved'] = 2;
            }elseif(isset($validate_arr[$row['id'].'_checked'])){
                $row['approved'] = 1;
            }
            $row['date_created'] = date("M d,Y",strtotime($row['date_created']));
            $data[] = $row;
        }
        return json_encode($data);
    }
    function load_list_receiving(){

        $qry = $this->db->query("SELECT r.*,p.ref_no,p.id as po_id,s.name as sname FROM receiving r inner join purchases p on r.po_id = p.id inner join supplier s on s.id = p.supplier_id where p.status != 0 ");
        $data=array();
        $po_ids = array_column($qry->result_array(), 'id','id');
        $po_ids = implode(',',$po_ids);
        foreach($qry->result_array() as $row){
              
            $row['date_created'] = date("M d,Y",strtotime($row['date_created']));
            $data[] = $row;
        }
        return json_encode($data);
    }
    function load_po(){
        extract($_POST);
		$prod = $this->db->query("SELECT * FROM products ");
		$prod_name_arr = array_column($prod->result_array(),'name','id');
		$prod_desc_arr = array_column($prod->result_array(),'description','id');

        if(isset($rid) && $rid > 0){
            $qry = $this->db->query("SELECT * FROM inventory where receiving_id = $rid ");
        }else{
            $qry = $this->db->query("SELECT * FROM po_items where po_id = $id ");
        }
        $data =array();
        foreach($qry->result_array() as $row){
            $row['pname'] = $prod_name_arr[$row['product_id']];
            $row['pdesc'] = $prod_desc_arr[$row['product_id']];
            $data[] = $row;
        }
        return json_encode($data);
    }
    function remove_po(){
        extract($_POST);
        $delete = $this->db->update('purchases',array('status'=>0),array('id'=>$id));
        $po = $this->db->get_where("purchases",array("id"=>$id))->row()->ref_no;
        if($delete){
            $this->cogs->save_log("deleted ".$po." from purchase order list. ","deleted");
        return 1;
        }
    }

     function remove_receiving(){
        extract($_POST);
        $po_id = $this->db->query("SELECT * FROM receiving")->row()->po_id;
        $delete = $this->db->delete('receiving',array('id'=>$id));
        $delete2 = $this->db->delete('inventory',array('receiving_id'=>$id));
        $this->db->update('purchases',array('status'=>1),array('id'=>$po_id));
        if($delete){
            $po = $this->db->get_where("purchases",array("id"=>$po_id))->row()->ref_no;
            $this->cogs->save_log("deleted ".$po." from purchases receiving list. ","deleted");

        return 1;
        }
    }

    function save_validation(){
        extract($_POST);
        $data['form_type'] = $form_type;
        $data['form_id'] = $form_id;
        $data['type'] = $type;
        $data['user_id'] = $user_id;
        $data['entered_name'] = $entered_name;
        $insert = $this->db->insert('validation',$data);
        if($insert){
            $po = $this->db->get_where("purchases",array("id"=>$form_id))->row()->ref_no;
            $this->cogs->save_log("validated ".$po." into purchases order list. ","Added");
            return 1;
        }
    }
    function save_purchases_return(){
        extract($_POST);
        $data['supplier_id'] = $supplier_id;
        $data['action_type'] = $action_type;
        $data['total_amount'] = $total_amount;
        if(empty($id)){
        $data['user_id'] = $_SESSION['login_id'];
        $ref = $this->db->query("SELECT * FROM po_returns where date(date_created) = '".date('Y-m-d')."' order by ref_no desc");
            if($ref->num_rows() > 0){
                $ref = explode('-',$ref->row()->ref_no);
                $ref = $ref[2]+1;
            }else{
            $ref = date('Ymd').'1';
            }
            $data['ref_no'] = 'PO-Return-'.$ref;
            $ref_no = 'PO-Return-'.$ref;
            if($action_type == '1'){
                $details = array();
                foreach($product_id as $k=>$v){
                    $data2=array();
                    $data2['product_id'] = $product_id[$k];
                    $data2['unit'] = $unit[$k];
                    $data2['qty'] = $qty[$k];
                    $data2['unit_price'] = $unit_price[$k];
                    $data2['issue'] = $issue[$k];
                    $details[] = $data2;
                }
                $data['data_json'] = json_encode($details);
            }elseif($action_type == '2'){
                $details = array();
                foreach($product_id as $k=>$v){
                    $data2=array();
                    $data2['product_id'] = $product_id[$k];
                    $data2['qty'] = $qty[$k];
                    $data2['unit'] = $unit[$k];
                    $data2['unit_price'] = $unit_price[$k];
                    $data2['type'] = 2;
                    $data2['remarks'] = "Return from ".$ref_no;
                    $this->db->insert("inventory",$data2);
                    $data2['inv_id'] = $this->db->insert_id();
                    $data2['issue'] = $issue[$k];
                    $inv_ids[]= $data2['inv_id'];
                    $details[] = $data2;
                }
                $data['data_json'] = json_encode($details);
                if(isset($inv_ids))
                $data['inv_ids'] = json_encode($inv_ids);
            }
            $save = $this->db->insert("po_returns",$data);
            if($save){
                $id = $this->db->insert_id();
                $this->session->set_flashdata('save_purchases',1);
                $this->cogs->save_log("added ".$data['ref_no']." into purchases return list. ","Added");
                return json_encode(array('id'=>$id,'status'=>1));
            }
            
        }else{
            $qry = $this->db->query("SELECT * FROM po_returns where id= $id")->row();
            if($action_type == '1'){
                if(!empty($qry->inv_ids)){
                    $inv_ids = str_replace(array("[","]"),'',$qry->inv_ids);
                    foreach(explode(",",$inv_ids) as $invid){
                        $this->db->delete("inventory",array('id'=>$invid));
                    }
                }
                $details = array();
                foreach($product_id as $k=>$v){
                    $data2=array();
                    $data2['product_id'] = $product_id[$k];
                    $data2['unit'] = $unit[$k];
                    $data2['qty'] = $qty[$k];
                    $data2['unit_price'] = $unit_price[$k];
                    $data2['issue'] = $issue[$k];
                    $details[] = $data2;
                }
                $data['data_json'] = json_encode($details);
            }elseif($action_type == '2'){
                if(!empty($qry->inv_ids)){
                    $inv_ids = str_replace(array("[","]"),'',$qry->inv_ids);
                    foreach(explode(",",$inv_ids) as $invid){
                        if(!in_array($invid,$inv_id) )
                        $this->db->delete("inventory",array('id'=>$invid));
                    }
                }
                $details = array();
                $inv_ids= array();
                    foreach($product_id as $k=>$v){
                    $data2=array();
                    $data2['product_id'] = $product_id[$k];
                    $data2['qty'] = $qty[$k];
                    $data2['unit'] = $unit[$k];
                    $data2['unit_price'] = $unit_price[$k];
                    $data2['type'] = 2;
                    $data2['remarks'] = "Return from ".$qry->ref_no;
                    if($inv_id[$k] > 0){
                    $this->db->update("inventory",$data2,array('id'=>$inv_id[$k]));
                    $data2['inv_id'] = $inv_id[$k];
                    }else{
                    $this->db->insert("inventory",$data2);
                    $data2['inv_id'] = $this->db->insert_id();
                    }
                    $data2['issue'] = $issue[$k];
                    $inv_ids[]= $data2['inv_id'];
                    $details[] = $data2;
                }
                $data['data_json'] = json_encode($details);
                if(isset($inv_ids))
                $data['inv_ids'] = implode(',',$inv_ids);
            }
            $save = $this->db->update("po_returns",$data,array('id'=>$id));
            if($save){
                $this->session->set_flashdata('save_purchases',1);
                $this->cogs->save_log("updated ".$ref_no." into purchases return data. ","Update");

                return json_encode(array('id'=>$id,'status'=>1));
            }
           
        }
    }

    function return_list(){
        $qry = $this->db->query("SELECT * FROM po_returns where status = 1 order by id asc");
        $supplier = $this->db->query("SELECT * FROM supplier where id in (SELECT supplier_id FROM po_returns where status = 1 ) ");
        $sup_arr = array_column($supplier->result_array(),'name','id');
        $data = array();
        foreach($qry->result_array() as $row){
            $row['date_created'] = date("M d, Y",strtotime($row['date_created']));
            $row['sname'] = $sup_arr[$row['supplier_id']];
            $data[]= $row;
        }
        return json_encode($data);
    }

    function remove_purchases_return(){
        extract($_POST);
        $qry = $this->db->get_where("po_returns",array('id'=>$id))->row();
        $inv_ids = str_replace(array("[","]"),'',$qry->inv_ids);
            foreach(explode(",",$inv_ids) as $invid){
                $this->db->delete("inventory",array('id'=>$invid));
            }
        $rem = $this->db->update('po_returns',array('status'=>0),array('id'=>$id));
        
        if($rem){
            $ref =  $this->db->get_where("po_returns",array("id"=>$id))->row()->ref_no;
                $this->cogs->save_log("deleted ".$ref." from purchases return data. ","Deleted");
                $this->session->set_flashdata('save_po_return_del',1);
            return $rem;
        }
    }
    function get_report_list(){
        extract($_POST);
        $receiving = $this->db->query("SELECT r.*,p.supplier_id,p.payment_mode FROM receiving r inner join purchases p on p.id = r.po_id  order by date(r.date_created) ")->result_array();
        $data = array();
        $rid_arr = array_column($receiving,"po_id","id");
        $sid_arr = array_column($receiving,"supplier_id","id");
        if(count($rid_arr) > 0){
            $supplier = $this->db->query("SELECT * FROM supplier where id in (".implode(',',$sid_arr).") ")->result_array();
            $c_arr = array_column($supplier,'name','id');
            $payments = $this->db->query("SELECT * FROM po_payments where po_id in (".implode(',',$rid_arr).") ")->result_array();
            foreach($rid_arr as $row){
                $paid[$row] = 0;
            }
            foreach($payments as $row){
                $paid[$row['po_id']] += $row['amount'];
            }
            foreach($receiving as $row){
                $row['paid'] = $paid[$row['po_id']];
                $row['sname'] = ucwords($c_arr[$row['supplier_id']]);
                $row['date_created'] = date("Y-m-d",strtotime($row['date_created']));
                $data[] = $row;
            }

        }
        return json_encode($data);
    }
    function delete_payment(){
        extract($_POST);
        $p = $this->db->get_where('po_payments',array('id'=>$id))->row()->po_id;
        $po = $this->db->get_where('purchases',array('id'=>$p))->row()->ref_no;
        $delete = $this->db->delete('po_payments',array('id'=>$id));
        if($delete){
            $this->cogs->save_log("deleted  ".$po." from PO payments. ","Delete");
            $this->session->set_flashdata('save_sales_del',1);
                return 1;
        }

    }

}
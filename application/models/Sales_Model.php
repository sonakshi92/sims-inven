<?php


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sales_Model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }

    function save(){
       extract($_POST);
       $data['customer_id'] = $customer_id;
       $data['salesman_id'] = $salesman_id;
       $data['payment_mode'] = $payment_mode;
       $data['delivery_schedule'] = $delivery_schedule;
       $data['total_amount'] = $total_amount;
       $data['payment_mode'] = $payment_mode;
       $data['c_po_no'] = $po_no;
       $data['user_id'] = $_SESSION['login_id'];
       $amount = $amount > 0 ? $amount : 0;
       if(empty($id)){
        $ref = $this->db->query("SELECT * FROM sales where date(date_created) = '".date('Y-m-d')."' order by ref_no desc limit 1");
        $dr = $this->db->query("SELECT * FROM sales order by ABS(dr_no) desc limit 1");
        if($ref->num_rows() > 0){
            $ref = explode('-',$ref->row()->ref_no);
            $ref = $ref[1]+1;
        }else{
         $ref = date('Ymd').'1';
        }
        if($dr->num_rows() > 0){
            $dr = $dr->row()->dr_no;
            $dr = $dr+1;
        }else{
         $dr ='1';
        }
        $data['ref_no'] = 'Sales-'.$ref;
        $data['dr_no'] = $dr;
        $ref_no = 'Sales-'.$ref;
        $dr_no = $dr;
        $save = $this->db->insert("sales",$data);
        if($save){
            $id= $this->db->insert_id();
            $disc_array = array();
            $inv_arr = array();
            if(empty($invoice)){
            $payments = $this->db->query("SELECT * from payments order by ABS(invoice) desc limit 1");
            if($payments->num_rows() > 0){
                $invoice = $payments->row()->invoice +1;
            }else{
                $invoice = 1;
            }}
            $pay['sales_id'] = $id;
            $pay['amount'] = $amount;
            $pay['remarks'] = $remarks;
            $pay['invoice'] = $invoice;
            $pay['payment_method'] = $payment_method;
            $pay['ref_no'] = $pref_no;
            $this->db->insert('payments',$pay);
            foreach($product_id as $k => $v){
                $data= array();
                  $data['receiving_id'] = $id;
                  $data['product_id'] = $product_id[$k];
                  $data['qty'] = $qty[$k];
                  $data['unit'] = $unit[$k];
                  $data['unit_price'] = $unit_price[$k];
                  $data['type'] = 2;
                  $data['remarks'] = $ref;
                  $inv_save = $this->db->insert("inventory",$data);
                  if($inv_save){
                      $inv_id = $this->db->insert_id();
                      $disc_array[] = array($inv_id=>$discount[$k].",".$discount2[$k]);
                      $inv_arr[] = $inv_id;
                  }

            }
            $data = array();
            $data['inventory_ids'] = "[".implode(',',$inv_arr)."]";
            $data['discount_json'] = json_encode($disc_array);
            $this->db->update('sales',$data,array('id'=>$id));

            $this->session->set_flashdata('save_sales',1);
            $this->cogs->save_log("created  ".$ref_no." into sales list. ","Create");

            return json_encode(array('status'=>1,'id'=>$id));

        }

       }else{
           $qry = $this->db->query("SELECT * FROM sales where id= $id")->row();
        $inv_ids = str_replace(array("[","]"),'',$qry->inventory_ids);
        foreach(explode(",",$inv_ids) as $invid){
            if(!in_array($invid,$inv_id))
            $this->db->delete("inventory",array('id'=>$invid));
        }
        $ref = $qry->ref_no;
        $save = $this->db->update("sales",$data,array('id'=>$id));
        if($save){
            $disc_array = array();
            $inv_arr = array();
            if(empty($invoice)){
                $payments = $this->db->query("SELECT * from payments where id !='$payment_id' order by ABS(invoice) desc limit 1");
                if($payments->num_rows() > 0){
                    $invoice = $payments->row()->invoice +1;
                }else{
                    $invoice = 1;
                }}
            $pay['sales_id'] = $id;
            $pay['amount'] = $amount;
            $pay['remarks'] = $remarks;
            $pay['invoice'] = $invoice;
            $pay['payment_method'] = $payment_method;
            $pay['ref_no'] = $pref_no;
            $this->db->update('payments',$pay,array('id'=>$payment_id));
            // echo $this->db->last_query();
            foreach($product_id as $k => $v){
                $data= array();
                  $data['receiving_id'] = $id;
                  $data['product_id'] = $product_id[$k];
                  $data['qty'] = $qty[$k];
                  $data['unit'] = $unit[$k];
                  $data['unit_price'] = $unit_price[$k];
                  $data['type'] = 2;
                  $data['remarks'] = $ref;
                  if($inv_id[$k] > 0){
                  $inv_save = $this->db->update("inventory",$data,array("id"=>$inv_id[$k]));
                  if($inv_save){
                      $disc_array[] = array($inv_id[$k]=>$discount[$k].",".$discount2[$k]);
                      $inv_arr[] = $inv_id[$k];
                  }
                }else{
                    $inv_save = $this->db->insert("inventory",$data);
                  if($inv_save){
                      $inv_id = $this->db->insert_id();
                      $disc_array[] = array($inv_id=>$discount[$k]);
                      $inv_arr[] = $inv_id;
                  }
                }

            }
            $data = array();
            $data['inventory_ids'] = "[".implode(',',$inv_arr)."]";
            $data['discount_json'] = json_encode($disc_array);
            $this->db->update('sales',$data,array('id'=>$id));

            $this->session->set_flashdata('save_sales',1);
            $this->cogs->save_log("updated  ".$qry->ref_no." into sales list. ","Update");
            return json_encode(array('status'=>1,'id'=>$id));

        }
       }
       
    }

    function load_list(){

        $qry = $this->db->query("SELECT * FROM sales where status = 1 order by id desc ");
        $customers = $this->db->query("SELECT * FROM customers where id in (SELECT customer_id FROM sales where status = 1 ) ");
        $cus_arr = array_column($customers->result_array(),'name','id');
        $data=array();
        foreach($qry->result_array() as $row){
            $row['cname'] = isset($cus_arr[$row['customer_id']]) ? ucwords($cus_arr[$row['customer_id']]) : 'N/A';
            $row['date_created'] = date("M d,Y h:i A",strtotime($row['date_created'])); 
            $row['dr_no'] = sprintf("%'04d\n", $row['dr_no']);
            $data[] = $row;
        }
        return json_encode($data);
    }

    function remove(){
        extract($_POST);
        $rem = $this->db->update('saless',array('status'=>0),array('id'=>$id));
        $ref = $this->db->get_where("sales",array("id"=>$id))->row()->ref_no;
        if($rem){
            $this->cogs->save_log("deleted  ".$ref." from sales list. ","Deleted");
            return 1;
            }
    }

    function check_prod_qty(){
        extract($_POST);
        $in = $this->db->query("SELECT sum(qty) as inn from inventory where product_id = $id and `type` = 1 ");
        $in = $in->num_rows() > 0 ? $in->row()->inn : 0;
        $out = $this->db->query("SELECT sum(qty) as `out` from inventory where product_id = $id and `type` = 2 ");
        $out = $out->num_rows() > 0 ? $out->row()->out : 0;
        if($qty > ($in-$out)){
            return json_encode(array('status'=>'decline','max_qty'=>($in-$out)));
        }else{
            return json_encode(array('status'=>'accept','max_qty'=>($in-$out)));
        }
    }

    function save_payment(){
        extract($_POST);
            $pay['sales_id'] = $sales_id;
            $pay['amount'] = $amount;
            $pay['remarks'] = $remarks;
            $pay['payment_method'] = $payment_method;
            $pay['ref_no'] = $pref_no;
             if(empty($id)){
            if(empty($invoice)){
            $payments = $this->db->query("SELECT * from payments order by ABS(invoice) desc limit 1");
            if($payments->num_rows() > 0){
                $invoice = $payments->row()->invoice +1;
            }else{
                $invoice = 1;
            }
            }
            $pay['invoice'] = $invoice;
            $save = $this->db->insert('payments',$pay);
           
        }else{
            if(empty($invoice)){
            $payments = $this->db->query("SELECT * from payments where id != $id order by ABS(invoice) desc limit 1");
            if($payments->num_rows() > 0){
                $invoice = $payments->row()->invoice +1;
            }else{
                $invoice = 1;
            }
            }
            $pay['invoice'] = $invoice;
            $save = $this->db->update('payments',$pay,array('id'=>$id));
            
        }
        if($save){
            $this->session->set_flashdata('save_sales',1);
            $ref = $this->db->get_where('sales',array('id'=>$sales_id))->row()->ref_no;
            if(empty($id)){
            $this->cogs->save_log("added  ".$ref." payment. ","Create");
            }else{
            $this->cogs->save_log("updated  ".$ref." payment. ","Update");
            }

                return 1;
        }
    }
    function delete_payment(){
        extract($_POST);
        $p = $this->db->get_where('payments',array('id'=>$id))->row()->sales_id;
        $sales = $this->db->get_where('sales',array('id'=>$p))->row()->ref_no;
        $delete = $this->db->delete('payments',array('id'=>$id));
        if($delete){
            $this->cogs->save_log("deleted  ".$sales." from payments. ","Delete");
            $this->session->set_flashdata('save_sales_del',1);
                return 1;
        }

    }

    function remove_sales(){
        extract($_POST);
        $sales = $this->db->get_where('sales',array('id'=>$id))->row();
        foreach($sales as $k=> $v){
            $$k = $v;
        }
        $delete = $this->db->delete("sales",array('id'=>$id));
        $delete2 = $this->db->delete("payments",array('sales_id'=>$id));
        $invs = str_replace(array("[","]"),'',$inventory_ids);
        $delete3 = $this->db->query("DELETE FROM inventory where id in (".$invs.")");;
        if($delete && $delete2 && $delete3){
            $this->cogs->save_log("deleted  ".$ref_no." from sales list. ","Delete");
            $this->session->set_flashdata('save_sales_del',1);
            return 1;
        }


    }
    function save_sales_return(){
        extract($_POST);
        $data['customer_id'] = $customer_id;
        $data['action_type'] = $action_type; 
        $data['total_amount'] = $total_amount;
        $data['salesman_id'] = $salesman_id;
        if(empty($id)){
        $data['user_id'] = $_SESSION['login_id'];
        $ref = $this->db->query("SELECT * FROM sales_returns where date(date_created) = '".date('Y-m-d')."' and status =1 order by ABS(ref_no) desc");
            if($ref->num_rows() > 0){
                $ref = explode('-',$ref->row()->ref_no);
                
                $ref = $ref[2] + 1;
            }else{
            $ref = date('Ymd').'1';
            }
            $data['ref_no'] = 'Sales-Return-'.$ref;
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
                    $data2['type'] = 1;
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
            $save = $this->db->insert("sales_returns",$data);
            if($save){
                $id = $this->db->insert_id();
                $this->session->set_flashdata('save_sales',1);
            $this->cogs->save_log("added  ".$data['ref_no']." from sales return list. ","Create");

                return json_encode(array('id'=>$id,'status'=>1));
            }
            
        }else{
            $qry = $this->db->query("SELECT * FROM sales_returns where id= $id")->row();
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
                    $data2['type'] = 1;
                    $data2['remarks'] = "Return from ".$ref_no;
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
            $save = $this->db->update("sales_returns",$data,array('id'=>$id));
            if($save){
                $this->session->set_flashdata('save_sales',1);
            $this->cogs->save_log("updated  ".$ref_no." sales return data. ","Update");
            return json_encode(array('id'=>$id,'status'=>1));
            }
           
        }
    }

    function return_list(){
        $qry = $this->db->query("SELECT * FROM sales_returns where status = 1 order by id asc");
        $customers = $this->db->query("SELECT * FROM customers where id in (SELECT customer_id FROM sales_returns where status = 1 ) ");
        $cus_arr = array_column($customers->result_array(),'name','id');
        $data = array();
        foreach($qry->result_array() as $row){
            $row['date_created'] = date("M d, Y",strtotime($row['date_created']));
            $row['cname'] = $cus_arr[$row['customer_id']];
            $data[]= $row;
        }
        return json_encode($data);
    }

    function remove_sales_rerturn(){
        extract($_POST);
        $qry = $this->db->get_where("sales_returns",array('id'=>$id))->row();
        $inv_ids = str_replace(array("[","]"),'',$qry->inv_ids);
            foreach(explode(",",$inv_ids) as $invid){
                $this->db->delete("inventory",array('id'=>$invid));
            }
        $rem = $this->db->update('sales_returns',array('status'=>0),array('id'=>$id));
        if($rem)
        $sr = $this->db->get_where("sales_returns",array('id'=>$id))->row()->ref_no;
            $this->cogs->save_log("deleted  ".$sr." from sales return list. ","Deleted");
            return $rem;
    }

    // function get_report_list(){
    //     extract($_POST);
    //     $from = date("Y-m-d",strtotime($from));
    //     $to = date("Y-m-d",strtotime($to));
    //     $cwhere = '';
    //     if($customer_id > 0){
    //         $cwhere = " and customer_id = $customer_id ";
    //     }
    //     if($salesman_id > 0){
    //         $cwhere = " and salesman_id = $salesman_id ";
    //     }
    //     $sales = $this->db->query("SELECT * FROM sales where status = 1 and (date(date_created) between '$from' and '$to' ) $cwhere order by date(date_created) ")->result_array();
    //     $data = array();
    //     $sid_arr = array_column($sales,"id","id");
    //     $cid_arr = array_column($sales,"customer_id","id");
    //     if(count($sid_arr) > 0){
    //         $customer = $this->db->query("SELECT * FROM customers where id in (".implode(',',$cid_arr).") ")->result_array();
    //         $c_arr = array_column($customer,'name','id');
    //         $payments = $this->db->query("SELECT * FROM payments where sales_id in (".implode(',',$sid_arr).") ")->result_array();
    //         foreach($sid_arr as $row){
    //             $paid[$row] = 0;
    //         }
    //         foreach($payments as $row){
    //             $paid[$row['sales_id']] += $row['amount'];
    //         }
    //         foreach($sales as $row){
    //             $row['paid'] = $paid[$row['id']];
    //             $row['cname'] = ucwords($c_arr[$row['customer_id']]);
    //             $row['date_created'] = date("Y-m-d",strtotime($row['date_created']));
    //             $data[] = $row;
    //         }

    //     }
    //     return json_encode($data);
    // }

    function get_report_list(){
        extract($_POST);
        $sales = $this->db->query("SELECT * FROM sales where status = 1  ")->result_array();
        $data = array();
        $sid_arr = array_column($sales,"id","id");
        $smid_arr = array_column($sales,"salesman_id","id");
        $cid_arr = array_column($sales,"customer_id","id");
        
        $prod = $this->db->get("products")->result_array();
        $psku = array_column($prod,'sku','id');
        $pname = array_column($prod,'name','id');
        if(count($sid_arr) > 0){
            $salesman = $this->db->query("SELECT *,concat(firstname,' ',middlename,' ',lastname) as name FROM salesman where id in (".implode(',',$smid_arr).") ")->result_array();
            $customer = $this->db->query("SELECT * FROM customers where id in (".implode(',',$cid_arr).") ")->result_array();
            $sm_arr = array_column($salesman,'name','id');
            $c_arr = array_column($customer,'name','id');
            foreach($sales as $row){
                $drow['smname'] = ucwords($sm_arr[$row['salesman_id']]);
               
                $data[$row['salesman_id']]['dtails'] = $drow;
                $invs = str_replace(array("[","]"),"",$row['inventory_ids']);
                $disc = array();
                foreach(json_decode($row['discount_json']) as $k => $v){
                    foreach($v as $key => $val){
                    $val = explode(",",$val);
                    $disc[$key][0] = $val[0];
                    $disc[$key][1] = isset($val[1]) ? $val[1] : 0 ;
                }
                foreach(explode(",",$invs) as $k => $val){
                $cname[$val] = ucwords($c_arr[$row['customer_id']]);
                }
            }
           
                $inv = $this->db->query("SELECT * FROM inventory where id in ($invs) ")->result_array();
            foreach($inv as $irow){
                $price = $this->db->query("SELECT i.* FROM inventory i inner join receiving r on r.id = i.receiving_id where i.product_id ='".$irow['product_id']."' and (date(r.date_created) <= '".date("Y-m-d",strtotime($row['date_created']))."' or date(r.date_updated) <= '".date("Y-m-d",strtotime($row['date_created']))."') and i.type = 1 order by date(r.date_created) desc limit 1");
                $price = $price->num_rows() > 0 ? $price->row()->unit_price:0;
                $aprice  = $price;
                $srow['name'] = $cname[$irow['id']];
                $irow['unit_price'] = ($irow['unit_price'] - ($irow['unit_price'] * ($disc[$irow['id']][0]/100)));
                $irow['unit_price'] = ($irow['unit_price'] - ($irow['unit_price'] * ($disc[$irow['id']][1]/100)));
                $srow['date'] = date("Y-m-d",strtotime($row['date_created']));
                $srow['date_created'] = date("Y-m-d",strtotime($row['date_created']));
                $srow['aprice'] = $aprice;
                $srow['ref_no'] = $row['ref_no'];
                $srow['dr_no'] = sprintf("%'04d\n", $row['dr_no']);
                $srow['sku'] = $psku[$irow['product_id']];
                $srow['pname'] = $pname[$irow['product_id']];
                $srow['qty'] = $irow['qty'];
                $srow['unit'] = $irow['unit'];
                $srow['sprice'] = $irow['unit_price'];
                $srow['t_aprice'] = $aprice * $irow['qty'];
                $srow['t_sprice'] = $irow['unit_price'] * $irow['qty'];
                $srow['profit'] = $srow['t_sprice'] - $srow['t_aprice'];
                $data[$row['salesman_id']]["sales"][] = $srow;
            }
            }

        }
        return json_encode($data);
    }

    function profit_rep_list(){
        extract($_POST);
        $from = date("Y-m-d",strtotime($from));
        $to = date("Y-m-d",strtotime($to));
        $sales = $this->db->query("SELECT * FROM sales where status = 1 and (date(date_created) between '$from' and '$to' ) order by date(date_created) ")->result_array();
        $data = array();
        $prod = $this->db->get("products")->result_array();
        $psku = array_column($prod,'sku','id');
        $pname = array_column($prod,'name','id');
        foreach($sales as $row){
            $invs = str_replace(array("[","]"),"",$row['inventory_ids']);
            $inv = $this->db->query("SELECT * FROM inventory where id in ($invs) ")->result_array();
        foreach($inv as $irow){
            $price = $this->db->query("SELECT i.* FROM inventory i inner join receiving r on r.id = i.receiving_id where i.product_id ='".$irow['product_id']."' and (date(r.date_created) <= '".date("Y-m-d",strtotime($row['date_created']))."' or date(r.date_updated) <= '".date("Y-m-d",strtotime($row['date_created']))."') order by date(r.date_created) desc limit 1");
            $price = $price->num_rows() > 0 ? $price->row()->unit_price:0;
            $aprice  = $price;
            $srow['date'] = date("Y-m-d",strtotime($row['date_created']));
            $srow['aprice'] = $aprice;
            $srow['ref_no'] = $row['ref_no'];
            $srow['sku'] = $psku[$irow['product_id']];
            $srow['pname'] = $pname[$irow['product_id']];
            $srow['qty'] = $irow['qty'];
            $srow['sprice'] = $irow['unit_price'];
            $srow['t_aprice'] = $aprice * $irow['qty'];
            $srow['t_sprice'] = $irow['unit_price'] * $irow['qty'];
            $data[] = $srow;
        }
    }

    return json_encode($data);

    }
}
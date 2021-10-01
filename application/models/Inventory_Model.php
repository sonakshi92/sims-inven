<?php


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inventory_Model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }

    function load_inventory(){
        $prod = $this->db->query("SELECT * FROM products where status = 1 ");
        $data = array();
        $inventory = $this->db->query("SELECT * FROM inventory  ");
        $inn = array();
        foreach($inventory->result_array() as $row){
            if(!isset($inn[$row['product_id']]))
                $inn[$row['product_id']]=0;
                if($row['type'] == 1)
                $inn[$row['product_id']]+=intval($row['qty']);
                else
                $inn[$row['product_id']]-=intval($row['qty']);

                
        } 
        $data = array();
        foreach($prod->result_array() as $row){
            $row['inn'] = isset($inn[$row['id']]) ? $inn[$row['id']] : 0;
            $data[] = $row;
        }
        return json_encode($data);
    }

}
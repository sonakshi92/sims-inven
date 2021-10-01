<?php 
$sales = $this->db->query("SELECT s.*, c.name as cname,concat(u.firstname,' ',u.middlename,' ',u.lastname) as uname FROM sales s inner join users u on u.id = s.user_id inner join customers c on c.id = s.customer_id where s.id = $id");

foreach($sales->row() as $k => $v){
    $$k=$v;
}
$sm = $this->db->get_where("salesman",array('id'=>$salesman_id))->row();
$smname = ucwords($sm->lastname.', '.$sm->firstname.' '.$sm->middlename);

?>
<style>
#details p{
    margin:unset
}
th,td{
    padding:3px 5px !important;
}
tr,th,td,table{
    border:1px solid black !important;
    font-weight:400 !important;

}
tfoot td, thead td{
    font-weight:600 !important;
}
table.table-hover tbody tr{
    cursor:pointer;
}
table.table-hover tbody tr:hover{
    cursor:pointer;
}

</style>

<div class="container-fluid">
    <div class="card col-lg-12">
        <div class="card-body">
        <h4><strong><?php echo $ref_no ?></strong></h4>
        <hr>
        <div class="row">
            <div class="col-md-8" id="details">
                <p><b>Customer : </b><a href="<?php echo base_url('customer/view/').$customer_id ?>"><?php echo ucwords($cname) ?></a></p>
                <p><b>Customer PO : </b><?php echo $c_po_no ?></p>
                <p><b>DR No. : </b><?php echo sprintf("%'04d\n", $dr_no) ?></p>
                <p><b>Delivery Schedule: </b><?php echo date("M d, Y",strtotime($delivery_schedule)) ?></p>
                <p><b>Total Amount: </b><?php echo number_format($total_amount,2) ?></p>
                <p><b>Payment Mode: </b><?php echo $payment_mode ?></p>
                <p><b>Salesman: </b><?php echo ucwords($smname) ?></p>
                <p><b>Processed By: </b><?php echo ucwords($uname) ?></p>
                <p><b>Date/Time Created: </b><?php echo date("M d, Y h:i A",strtotime($date_created)) ?></p>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <a class="btn btn-primary btn-sm" href="<?php echo base_url('sales/manage/edit/').$id ?>"><i class="fa fa-edit"></i> Edit</a>
                    <?php if($_SESSION['login_type'] == 1): ?>
                    <button class="btn btn-primary btn-sm" type="button" id="new_payment"><i class="fa fa-plus"></i> New Payment</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="8">Purchases List</th>
                        </tr>
                        <tr>
                            <th class="text-center" rowspan="2">QTY</th>
                            <th class="text-center" rowspan="2">Unit</th>
                            <th class="text-center" rowspan="2">Product</th>
                            <th class="text-center" rowspan="2">Gross Price</th>
                            <th class="text-center" colspan="2">Dis.(%)</th>
                            <th class="text-center" rowspan="2">Net Price</th>
                            <th class="text-center" rowspan="2">Amount</th>
                        </tr>
                        <tr>
                            <td class="text-center">1st</td>
                            <td class="text-center">2nd</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $invs = str_replace(array("[","]"),'',$inventory_ids);
                        $discount = json_decode($discount_json);
                        foreach($discount as $v){
                            foreach($v as $k => $val){
                                $val = explode(",",$val);
                                $disc[$k][0] = $val[0];
                                $disc[$k][1] = isset($val[1]) ? $val[1] : 0 ;

                            }
                        }
                        $product = $this->db->query("SELECT * FROM products where id in (SELECT product_id FROM inventory where id in (".$invs.") ) ");
                        $prod_arr = array_column($product->result_array(),'sku','id');
                        $pname_arr = array_column($product->result_array(),'name','id');
                        $inventory = $this->db->query("SELECT * FROM inventory where id in (".$invs.") order by id asc");
                        foreach($inventory->result_array() as $row):
                            $amount = ($row['unit_price'] - ($row['unit_price'] * ($disc[$row['id']][0] / 100)));
                            $amount = ($amount - ($amount * ($disc[$row['id']][1] / 100)));
                            $namount = $amount ;
                            $amount = $amount * $row['qty'];
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $row['qty'] ?></td>
                            <td class="text-center"><?php echo $row['unit'] ?></td>
                            <td class="text-center"><?php echo $pname_arr[$row['product_id']] ?></td>
                            <td class="text-center"><?php echo $row['unit_price'] ?></td>
                            <td class="text-center"><?php echo $disc[$row['id']][0] ?></td>
                            <td class="text-center"><?php echo $disc[$row['id']][1] ?></td>
                            <td class="text-center"><?php echo $namount ?></td>
                            <td class="text-center"><?php echo number_format($amount,2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                            <tr>
                                <th class="text-right" colspan="7">Total</th>
                                <th class="text-right"><?php echo number_format($total_amount,2) ?></th>
                            </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-md-6">
            <table class="table table-bordered  table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="7">Payments List</th>
                        </tr>
                        <tr>
                            <th class="text-center">Date</th>
                            <th class="text-center">Invoice</th>
                            <th class="text-center">Method</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">PR no/CR No.</th>
                            <th class="text-center">Remarks</th>
                            <?php if($_SESSION['login_type'] == 1): ?>
                            <th class="text-center"></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $payements = $this->db->query("SELECT * FROM payments where sales_id = $id order by id asc");
                        $paid = 0;
                        foreach($payements->result_array() as $row):
                            $paid += $row['amount'];
                    ?>
                        <tr>
                            <td class="text-center"><?php echo date("Y-m-d",strtotime($row['date_created'])) ?></td>
                            <td class="text-center"><?php echo sprintf("%'04d\n", $row['invoice']) ?></td>
                            <td class="text-center"><?php echo $row['payment_method'] ?></td>
                            <td class="text-center"><?php echo $row['amount'] ?></td>
                            <td class="text-center"><?php echo $row['ref_no'] ?></td>
                            <td class="text-center"><?php echo $row['remarks'] ?></td>
                            <?php if($_SESSION['login_type'] == 1): ?>
                            <td>
                            <span class="p_opt">
                            <div class="btn-group">
                                <button type="button" class="btn_p_opt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bars"></i></button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item edit_payment" data-id="<?php echo $row['id'] ?>" href="javascript:void(0)">Edit</a>
                                    <a class="dropdown-item delete_payment" data-id="<?php echo $row['id'] ?>" href="javascript:void(0)">Delete</a>
                                </div>
                                </div>
                            </span>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table> 
                <p><b>Total Received :</b> <?php echo number_format($paid,2) ?></p>          
                <p><b>Total Receiveable :</b> <?php echo number_format($total_amount - $paid,2) ?></p>          
            </div>
        </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        if('<?php echo $this->session->flashdata('save_sales') ?>' == 1)
            Dtoast("Data successfully added",'success');
        if('<?php echo $this->session->flashdata('save_sales') ?>' == 2)
            Dtoast("Data successfully updated",'success');
    })
    $('#print_sales').click(function(){
        var nw = window.open("<?php echo base_url('sales/print_sales/').$id ?>","_blank","height=600,width=800")
        nw.print()
        setTimeout(function(){
            nw.close()
        },1500)
    })
    $('#new_payment').click(function(){
        frmModal("manage-payments","New Payment","<?php echo base_url('sales/new_payment/').$id ?>")
    })
    $('.edit_payment').click(function(){
        frmModal("manage-payments","Edit Payment","<?php echo base_url('sales/new_payment/').$id ?>/"+$(this).attr('data-id'))
    })
    $('.delete_payment').click(function(){
       delete_data("Are you sure to delete this payment?",'delete_payment',[$(this).attr('data-id')]);
    })
    function delete_payment($id){
        start_load();
        $.ajax({
            url:"<?php echo base_url('sales/delete_payment') ?>",
            method:'POST',
            data:{id:$id},
            success:function(resp){
                if(resp == 1){
                    location.reload()
                }
            }
        })
    }
</script>
<?php 
$purchases = $this->db->query("SELECT p.*, s.name as sname,concat(u.firstname,' ',u.middlename,' ',u.lastname) as uname FROM purchases p inner join users u on u.id = p.user_id inner join supplier s on s.id = p.supplier_id where p.id = $id");

foreach($purchases->row() as $k => $v){
    $$k=$v;
}
    $validations = $this->db->get_where("validation",array('form_id'=>$id,"form_type"=>'po'));
    $user = $this->db->select("id,CONCAT(firstname,' ',middlename,' ',lastname) as name")->get('users');
    $uname_arr = array_column($user->result_array(),'name','id');
    $validate = array();
    foreach($validations->result_array() as $row){
        $validate[$row['type']] = $row['user_id'] > 0 ? ucwords($uname_arr[$row['user_id']]) : ucwords($row['entered_name']);
        $validate[$row['type'].'_date'] = date("M d,Y",strtotime($row['date_created']));
    }
   
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
                <p><b>Supplier : </b><?php echo ucwords($sname) ?></p>
                <p><b>PO : </b><?php echo $ref_no ?></p>
                <p><b>Total Amount: </b><?php echo number_format($total_amount,2) ?></p>
                <p><b>Payment Mode: </b><?php echo empty($payment_mode) ? $payment_mode : "Unconfirmed" ?></p>
                <p><b>Processed By: </b><?php echo ucwords($uname) ?></p>
                <p><b>Date/Time Created: </b><?php echo date("M d, Y h:i A",strtotime($date_created)) ?></p>
                <?php if(isset($validate['checked'])): ?>
                <p><b>Checked By: </b><?php echo ucwords($validate['checked']) ?></p>
                <p><b>Date/Time Checked: </b><?php echo date("M d, Y h:i A",strtotime($validate['checked_date'])) ?></p>
                <?php endif; ?>
                <?php if(isset($validate['approved'])): ?>
                <p><b>Approved By: </b><?php echo ucwords($validate['approved']) ?></p>
                <p><b>Date/Time Approved: </b><?php echo date("M d, Y h:i A",strtotime($validate['approved_date'])) ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <a class="btn btn-primary btn-sm" href="<?php echo base_url('purchases/manage/').$id ?>"><i class="fa fa-edit"></i> Edit</a>
                    <?php if(isset($validate['approved'])): ?>
                    <?php if($_SESSION['login_type'] == 1): ?>
                    <button class="btn btn-primary btn-sm" type="button" id="new_payment"><i class="fa fa-plus"></i> New Payment</button>
                    <?php endif; ?>
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
                            <th class="text-center" colspan="5">Purchases List</th>
                        </tr>
                        <tr>
                            <th class="text-center">QTY</th>
                            <th class="text-center">Unit</th>
                            <th class="text-center">Product</th>
                            <th class="text-center">Unit Price</th>
                            <th class="text-center">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        
                        $product = $this->db->query("SELECT * FROM products where id in (SELECT product_id FROM po_items where po_id = $id) ");
                        $prod_arr = array_column($product->result_array(),'sku','id');
                        $pname_arr = array_column($product->result_array(),'name','id');
                        $items = $this->db->query("SELECT * FROM po_items where po_id = $id order by id asc");
                        foreach($items->result_array() as $row):
                        $amount = ($row['unit_price']) * $row['qty'];
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $row['qty'] ?></td>
                            <td class="text-center"><?php echo $row['unit'] ?></td>
                            <td class="text-center"><?php echo $pname_arr[$row['product_id']] ?></td>
                            <td class="text-center"><?php echo $row['unit_price'] ?></td>
                            <td class="text-center"><?php echo number_format($amount,2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                            <tr>
                                <th class="text-right" colspan="4">Total</th>
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
                        $payements = $this->db->query("SELECT * FROM po_payments where po_id = $id order by id asc");
                        $paid = 0;
                        foreach($payements->result_array() as $row):
                            $paid += $row['amount'];
                    ?>
                        <tr>
                            <td class="text-center"><?php echo date("Y-m-d",strtotime($row['date_created'])) ?></td>
                            <td class="text-center"><?php echo $row['invoice'] ?></td>
                            <td class="text-center"><?php echo $row['payment_method'] ?></td>
                            <td class="text-center"><?php echo $row['amount'] ?></td>
                            <td class="text-center"><?php echo $row['ref_no'] ?></td>
                            <td class="text-center"><?php echo $row['remarks'] ?></td>
                            <?php if($_SESSION['login_type'] == 1): ?>
                            <td class="text-center">
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
                <p><b>Total Paid :</b> <?php echo number_format($paid,2) ?></p>          
                <p><b>Total Payable :</b> <?php echo number_format($total_amount - $paid,2) ?></p>          
            </div>
        </div>
        </div>
    </div>
</div>

<script>
    $('#new_payment').click(function(){
        frmModal("manage-payments","New Payment","<?php echo base_url('purchases/new_payment/').$id ?>")
    })
    $('.edit_payment').click(function(){
        frmModal("manage-payments","Edit Payment","<?php echo base_url('purchases/new_payment/').$id ?>/"+$(this).attr('data-id'))
    })
    $('.delete_payment').click(function(){
       delete_data("Are you sure to delete this payment?",'delete_payment',[$(this).attr('data-id')]);
    })
    $('#print_po').click(function(){
        var nw = window.open("<?php echo base_url('purchases/print_po/').$id ?>","_blank","height=600,width=800")
        nw.print()
        setTimeout(function(){
            nw.close()
        },1500)
    })
    $(document).ready(function(){
        if('<?php echo $this->session->flashdata('save_purchases') ?>' == 1)
            Dtoast("Data successfully added",'success');
        if('<?php echo $this->session->flashdata('save_purchases') ?>' == 2)
            Dtoast("Data successfully updated",'success');
    })
    function delete_payment($id){
        start_load();
        $.ajax({
            url:"<?php echo base_url('purchases/delete_payment') ?>",
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
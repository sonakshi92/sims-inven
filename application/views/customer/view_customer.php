<?php
$customer = $this->db->query("SELECT * FROM customers where id=".$id);
foreach($customer->row() as $k => $v){
    $$k=$v;
}
?>
<div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12">
                <p><b><h4><?php echo ucwords($name) ?></h4></b></p>
                <hr>
                    <div class="row">
                        <div id="customer-details" class=" col-sm-8">
                            <p>Address : <b><?php echo $address ?></b></p>
                            <p>Business Type : <b><?php echo $type ?></b></p>
                            <p>Contact Person : <b><?php echo ucwords($contact_person) ?></b></p>
                            <p>Contact # : <b><?php echo $contact ?></b></p>
                        </div>
                        <div class="col-sm-4">
                            <a class="btn btn-primary btn-sm" href="<?php echo base_url('customer/manage/edit/').$id ?>"><i class="fa fa-edit"></i> Edit</a>
                            <a class="btn btn-primary btn-sm new_sale" href="<?php echo base_url('sales/manage/add/?cid=').$id ?>"><i class="fa fa-plus"></i> New Sales</a>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-7">
                            <table class="table table-bordered">
                                <colgroup>
                                    <col width="20%">
                                    <col width="40%">
                                    <col width="40%">
                                </colgroup>
                                <thead>
                                    <tr class="bg-primary">
                                        <th class="text-center" colspan="3"><b>Purchases List</b></th>
                                    </tr>
                                    <tr class="bg-primary">
                                        <th class="center">Date</th>
                                        <th class="center">DR No.</th>
                                        <th class="center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $total_purchase = 0;
                                        $qry = $this->db->query("SELECT * FROM sales where customer_id =".$id);
                                        foreach($qry->result_array() as $row):
                                            $ids[] = $row['id'];
                                            $total_purchase += $row['total_amount'];
                                    ?>
                                        <tr>
                                            <td><?php echo date("M d, Y",strtotime($row['date_created'])) ?></td>
                                            <td><a href="<?php echo base_url('sales/view/').$row['id'] ?>"><?php echo sprintf("%'04d\n", $row['dr_no']) ?></a></td>
                                            <td class='text-right'><?php echo number_format($row['total_amount'],2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                        <tr class="bg-primary">
                                            <th class="text-center" colspan='2'>Total</th>
                                            <th class="text-right"><?php echo number_format($total_purchase,2) ?></th>
                                        </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="col-sm-5">
                        <?php 
                            $received = 0;
                            if(isset($ids)){
                                $received = $this->db->query("SELECT SUM(amount) as tamount FROM payments  where sales_id in (".implode(",",$ids).") ")->row()->tamount;
                            }
                        ?>
                            <p>
                                <b>
                                    <h5 class="text-center">Total Received</h5>
                                </b>
                            </p>
                            <p><b><h4 class="text-center"><?php echo number_format($received,2) ?></h4></b></p>
                            <p>
                                <b>
                                    <h5 class="text-center">Total Receivable</h5>
                                </b>
                            </p>
                            <p><b><h4 class="text-center"><?php echo number_format($total_purchase - $received,2) ?></h4></b></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<style>
tr, th, td, table {
    border: 1px solid black !important;
    font-weight: 400 !important;
    padding: 3px 5px !important;
}
td a {
    color: blue !important;
    
}
</style>
<script>
 $('#print_customer').click(function(){
        var nw = window.open("<?php echo base_url('customer/print_customer/').$id ?>","_blank","height=600,width=800")
        nw.print()
        setTimeout(function(){
            nw.close()
        },1500)
    })
</script>
<?php 

$prod = $this->db->get_where("products",array('id'=> $id))->row();

?>
<style>
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
</style>
<div class="container-fluid">
<div class="card col-lg-12">
    <div class="card-body">
        <h4><strong><?php echo $prod->name ?></strong></h4>
        <hr>
        <div class="row">
            <div class="col-md-8" id="prod_details">
                <p><b>SKU : </b><?php echo $prod->sku ?></p>
                <p><b>Unit : </b><?php echo $prod->unit ?></p>
                <p><b>is Bulk ? : </b><?php echo $prod->is_bulk == 1 ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-default">No</span>'  ?></p>
                <?php if($prod->is_bulk == 1) : ?>
                <p><b>Convert Unit to : </b><?php echo $prod->convert_unit ?></p>
                <p><b>Convert QTY to : </b><?php echo $prod->convert_qty ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <button class="btn btn-primary btn-sm" id="new_price"><i class="fa fa-plus"></i> New Price</button>
                    <a class="btn btn-primary btn-sm" href="<?php echo base_url('product/manage/edit/').$id ?>"><i class="fa fa-edit"></i> Edit</a>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
        <div class="col-md-6">
            <table class="table table-bordered">
                <thead>
                        <tr>
                            <th class="text-center" colspan='3'>Price List</th>
                        </tr>
                        <tr class="bg-info">
                            <th class="text-center">Effective Date</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Description</th>
                        </tr>
                </thead>
                <tbody>
                <?php
                    $prices = $this->db->query("SELECT * FROM price_list where product_id = $id order by date(date_effective) desc, id desc");
                    foreach($prices->result_array() as $row):
                ?>
                    <tr>
                        <td><?php echo date('M d,Y',strtotime($row['date_effective'])) ?></td>
                        <td><?php echo number_format($row['price'],2) ?></td>
                        <td><?php echo $row['description'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-bordered">
                <thead>
                        <tr>
                            <th class="text-center" colspan='4'>Inventory Track</th>
                        </tr>
                        <tr class="bg-info">
                            <th class="text-center">Date Updated</th>
                            <th class="text-center">IN</th>
                            <th class="text-center">OUT</th>
                            <th class="text-center">Remarks</th>
                        </tr>
                </thead>
                <tbody>
                <?php
                    $inv = $this->db->query("SELECT * FROM inventory where product_id = $id order by id desc");
                    $t_in = 0;
                    $t_out = 0;
                    foreach($inv->result_array() as $row):
                        $t_in += $row['type'] == 1 ? $row['qty']: 0;
                        $t_out += $row['type'] == 2 ? $row['qty']: 0;
                ?>
                    <tr>
                        <td><?php echo date('M d,Y',strtotime($row['date_updated'])) ?></td>
                        <td class="text-right"><?php echo $row['type'] == 1 ? $row['qty']: 0 ?></td>
                        <td class="text-right"><?php echo $row['type'] == 2 ? $row['qty']: 0 ?></td>
                        <td><?php echo $row['remarks'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                        <tr>
                            <td class="text-right bg-info">Total</td>
                            <td class="text-right bg-primary"><?php echo $t_in ?></td>
                            <td class="text-right bg-primary"><?php echo $t_out ?></td>
                            <td class="text-right bg-info"></td>

                        </tr>
                        <tr>
                            <td class="text-center bg-info text-center" colspan="4">Total Availability</td>
                        </tr>
                        <tr>
                            <td class="text-center bg-primary text-center" colspan="4"><?php echo $t_in - $t_out ?></td>
                        </tr>
                        
                </tfoot>
            </table>
        </div>
        </div>
    </div>
</div>

</div>
<style>
#prod_details p {
    margin:unset
}

</style>
<script>
$('#new_price').click(function(){
    var prod = '<?php echo trim($prod->sku).' | '.$prod->name ?>';
    frmModal('manage-price','New Price for '+prod,'<?php echo base_url('product/manage_price/').$id ?>');
})
    $(document).ready(function(){
        if('<?php echo $this->session->flashdata('save_sales') ?>' == 1)
            Dtoast("Data successfully saved",'success');
    })
</script>
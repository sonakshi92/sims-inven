<style>
.borde-bottom{
    border-bottom:1px solid black;
}
</style>
<?php 
if(!empty($rid)){
    // echo "SELECT r.*,p.ref_no,p.date_created as po_date_created,concat(u.firstname,' ',u.lastname) as uname FROM receiving r inner join  purchases p on r.po_id = p.id inner join users u on u.id = p.user_id where r.id= $rid";
    $qry = $this->db->query("SELECT r.*,p.supplier_id,p.date_created as po_date_created,concat(u.firstname,' ',u.lastname) as uname FROM receiving r inner join  purchases p on r.po_id = p.id inner join users u on u.id = r.user_id where r.id= $rid")->row();
    $meta = array();
    foreach($qry as $k => $v){
        $meta[$k] = $v;
    }
}elseif(!empty($id)){
    $qry = $this->db->query("SELECT p.*,concat(u.firstname,' ',u.lastname) as uname FROM purchases p inner join users u on u.id = p.user_id where p.id= $id")->row();
    $meta = array();
    foreach($qry as $k => $v){
        $meta[$k] = $v;
    }

  
}
?>
<style>
    th, td{
        padding:3px 5px !important;
        vertical-align: inherit !important;
    }
    .input-sm {
    padding: 4px 2px;
    }
    .no-border{
        border :unset !important;
    }
    .input-block{
        width:100% !important;
    }
</style>
<div class="container-fluid">
    <div class="card col-md-12">
            <h5 class="card-header info-color white-text text-center py-4">
                <strong><?php echo isset($id) && $id > 0 ? "Receive ".(isset($meta['ref_no']) ? $meta['ref_no'] : $meta['po_ref']) : "New PO" ?></strong>
            </h5>
        <div class="card-body px-lg-5 pt-0">
            
            <form action="" id="manage_receiving">

             <div class="mt-3 col-md-12">
             <div class="row">
            <div class="col-md-6">
                <input type="hidden" name="id" value="<?php echo $rid ?>">
                <input type="hidden" name="po_id" value="<?php echo $id ?>">
                <input type="hidden" name="po_ref" value="<?php echo (isset($meta['ref_no']) ? $meta['ref_no'] : $meta['po_ref']) ?>">
                    <?php 
                        $supplier = $this->db->query("SELECT * FROM supplier where status = 1 and id = ".$meta['supplier_id'])->row()->name;
                    ?>
                    <p><b>Supplier :</b><u><?php echo $supplier ?></u></p>
            </div>
            <?php if(!empty($id)): ?>
            <div class="col-md-6 text-right">
                        <div class=""><b>P.O. Date Created: </b><?php echo isset($meta['po_date_created']) ? date("M d,Y",strtotime($meta['po_date_created'])) : date("M d,Y",strtotime($meta['date_created'])) ?></div>
                        <?php if(isset($rid) && $rid > 0): ?>
                        <div class=""><b>Date Received: </b><?php echo date("M d,Y",strtotime($meta['date_created'])) ?></div>
                        <?php endif; ?>
            </div>
            <?php endif; ?>
            </div>
            </div>
            <div class="col-md-6 mt-3">
                <div class="form-group">
                <label for="invoice" class="control-label">Invoice No.</label>
                <input type="text" class="form-control" name="invoice" id="invoice" required value="<?php echo isset($meta['invoice']) ? $meta['invoice'] : '' ?>">
                </div>
            </div>

            <hr>
            <div class="mt-3 col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <label for="product_id"  required>Product</label>
                        <select name="" id="product_id" class="browser-default custom-select input-sm">
                            <option value=""></option>
                            <?php 
                                $product = $this->db->query("SELECT * FROM products where status = 1");
                                foreach($product->result_array() as $row):
                            ?>
                                <option value="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" data-desc="<?php echo $row['description'] ?>" data-unit="<?php echo $row['unit'] ?>"><?php echo $row['name']. ' ('. $row['unit']. ') ' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="qty"  required>Qty</label>
                        <input type="text" id="qty" class="form-control input-sm">
                    </div>
                     <div class="col-md-3">
                        <label for="unit_price"  required>Unit Price</label>
                        <input type="text" id="unit_price" class="form-control input-sm">
                    </div>
                    <div class="col-md-1">
                        <label for=""  required>&nbsp</label>
                        <button class="btn btn-sm btn-primary btn-block text-center" id="new_list" type="button"><i class="fa fa-plus"></i></button>
                    </div>
                    
                </div>
                
            </div>
            <div class="mt-4">
            <table class="table table-bordered" id="po-list">
            <colgroup>
                        <col width="10%">
                        <col width="15%">
                        <col width="30%">
                        <col width="20%">
                        <col width="20%">
                        <col width="10%">
                </colgroup>
            <thead>
                    <tr>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Articles</th>
                        <th class="text-center">Unit Price</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th class="text-right" colspan='4'>Total</th>
                        <th class="text-right gtotal"></th>
                        <th class=""><input type="hidden" name="total_amount" ></th>
                    </tr>
                </tfoot>
            </table>
            </div>
            <hr>
            <div class="row">
            <div class="form-group col-md-6">
                <label for="received_through" class="control-label">Order made by:</label>
                <select name="received_through" id="received_through" class="custom-select default-browser">
                <option value="fax" <?php echo isset($meta['receive_through']) && $meta['receive_through'] == 'fax' ? "selected" : '' ?>>Fax</option>
                <option value="mail" <?php echo isset($meta['receive_through']) && $meta['receive_through'] == 'mail' ? "selected" : '' ?>>Mail</option>
                <!-- <option value="fax">Fax</option> -->
                </select>
            </div>
            <?php if($rid >0): ?>
            <div class="col-md-6 mt-3 text-right">
            <br>
                    <p><b>Received by: </b><u><?php echo isset($meta['uname']) ? $meta['uname'] : '' ?></u></p>
            </div>
            <?php endif; ?>
            </div>
            <hr>
                <div class="col-md-12">
                    <div class="row">
                    <div class="col-md-10"></div>
                    <button class="btn btn-sm btn-primary waves-effect col-md-2 offset-md-10" type="submit">Save</button>
                    </div>
                </div>
           


            </form>
        </div>
    </div>
</div>

<script>
    $('#validate_btn').click(function(){
        frmModal('validate-po',"PO Validation",'<?php echo base_url("purchases/validate_po/").$id ?>')
    })
    $(document).ready(function(){
        if('<?php echo !empty($id) ? 1 : 0 ?>' == 1)
        load_po();
    if('<?php echo $this->session->flashdata('save_purchases') ?>' == 1)
            Dtoast("Data successfully added",'success');
    if('<?php echo $this->session->flashdata('save_purchases') ?>' == 2)
            Dtoast("Data successfully updated",'success');
        $('input, textarea').trigger('focus')
        $('input, textarea').trigger('blur')
        $('#manage_receiving').submit(function(e){
            e.preventDefault()
            start_load()
        if($('#po-list tbody tr').length <= 0){
            Dtoast("Please add atleast 1 item.",'warning')
            return false;
        }
            var frmData = $(this).serialize();
            start_load();
            $.ajax({
                url:'<?php echo base_url('purchases/save_receiving') ?>',
                method:'POST',
                data:frmData,
                error:err=>{ console.log(err)},
                success:function(resp){
                    resp  = JSON.parse(resp)
                    if(resp.status == 1){
                        location.replace('<?php echo base_url('purchases/receiving/') ?>'+resp.id)
                    }
                }
            })
        })
    })
    $('#new_list').click(function(){
        var prod = $('#product_id').val();
        var qty = $('#qty').val();
        var unit_price = $('#unit_price').val();
        if(prod == ''  || qty == '' || unit_price==''){
            Dtoast("Please fill all item list.",'warning')
            return false;
        }
        if($('#po-list').find('tr[data-id="'+prod+'"]').length > 0){
            Dtoast("Item already exist.",'warning')
            return false;
        }
        $('#product_id').val('')
        $('#qty').val('')
        $('#unit_price').val('')
        
        var pdet = $('#product_id option[value="'+prod+'"]');
        var tr =$('<tr class="item-list"></tr>');
        var article = '<p>'+pdet.attr('data-name')+'</p>';

            tr.attr('data-id',prod)
        tr.append('<td><input type="hidden" class="input-block no-border text-center" name="inv_id[]" value="0" /><input type="text" class="input-block no-border text-center" name="qty[]" value="'+qty+'" /></td>')
        tr.append('<td><input type="text" class="input-block no-border" name="unit[]" value="'+pdet.attr('data-unit')+'"  readonly/></td>')
        tr.append('<td><input type="hidden" class="input-block no-border" name="product_id[]" value="'+prod+'" />'+article+'</td>')
        tr.append('<td><input type="text" class="input-block no-border text-right" name="unit_price[]" value="'+unit_price+'" /></td>')
        tr.append('<td><input type="text" class="input-block no-border text-right" name="amount[]" readonly value="'+(qty * unit_price)+'" /></td>')
        tr.append('<td><button class="btn btn-sm btn-danger" type="button" onclick="remove_list($(this))"><i class="fa fa-trash"></i></button></td>')
        // console.log(tr.html())
        $('#po-list tbody').append(tr)

        $('.input-block').each(function(){
            $(this).height($(this).parent().height())
        })
        compute_total()
    })
    function remove_list(_this){
        _this.closest('tr').remove();
        compute_total()
    }
    function compute_total(){
        var total = 0;
        $('[name="qty[]"] , [name="unit_price[]"]').keyup(function(){
            $(this).closest('tr').find('[name="amount[]"]').val(parseFloat($(this).closest('tr').find('[name="qty[]"]').val()) * parseFloat($(this).closest('tr').find('[name="unit_price[]"]').val()))
            compute_total()
        })
        $('[name="amount[]"]').each(function(){
             total += parseFloat($(this).val());
        })
        $('#po-list tfoot .gtotal').html(parseFloat(total).toLocaleString('en-us',{ style:'decimal', maximumFractionDigits: 2,minimumFractionDigits: 2 }))
        $('[name="total_amount"]').val(total);
    }
    window.load_po = function(){
        start_load()
        $.ajax({
            
        url:'<?php echo base_url('purchases/load_po') ?>',
        method:'POST',
        data:{id:'<?php echo $id ?>',rid:'<?php echo $rid ?>'},
        error:err=>{
            console.log(err)
            Dtoast("An error occured","danger")
        },
        success:function(resp){
            if(typeof resp != undefined){
                resp = JSON.parse(resp)
                if(Object.keys(resp).length > 0 ){
                    Object.keys(resp).map(k=>{
                        var tr =$('<tr class="item-list"></tr>');
                        var article = '<p>'+resp[k].pname+'</p>';

                            tr.attr('data-id',resp[k].product_id)
                        tr.append('<td><input type="hidden" class="input-block no-border text-center" name="inv_id[]" value="'+('<?php echo $rid ?>' > 0 ? resp[k].id : '')+'" /><input type="text" class="input-block no-border text-center" name="qty[]" value="'+resp[k].qty+'" /></td>')
                        tr.append('<td><input type="text" class="input-block no-border" name="unit[]" value="'+resp[k].unit+'" readonly /></td>')
                        tr.append('<td><input type="hidden" class="input-block no-border" name="product_id[]" value="'+resp[k].product_id+'" />'+article+'</td>')
                        tr.append('<td><input type="text" class="input-block no-border text-right" name="unit_price[]" value="'+resp[k].unit_price+'" /></td>')
                        tr.append('<td><input type="text" class="input-block no-border text-right" name="amount[]" value="'+(resp[k].qty * resp[k].unit_price)+'"  readonly/></td>')
                        tr.append('<td><button class="btn btn-sm btn-danger" type="button" onclick="remove_list($(this))"><i class="fa fa-trash"></i></button></td>')
                        // console.log(tr.html())
                        $('#po-list tbody').append(tr)
                    })
                }
            }
        },
        complete:function(){
            compute_total()
            $('.input-block').each(function(){
            $(this).height($(this).parent().height())
        })
            end_load()
        }
        })
    }
</script>
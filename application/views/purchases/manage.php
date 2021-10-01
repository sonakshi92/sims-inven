<style>
.borde-bottom{
    border-bottom:1px solid black;
}
</style>
<?php 
if(!empty($id)){
    $qry = $this->db->query("SELECT p.*,concat(u.firstname,' ',u.lastname) as uname FROM purchases p inner join users u on u.id = p.user_id where p.id= $id")->row();
    $meta = array();
    foreach($qry as $k => $v){
        $meta[$k] = $v;
    }

    $validations = $this->db->get_where("validation",array('form_id'=>$id,"form_type"=>'po'));
    $user = $this->db->select("id,CONCAT(firstname,' ',middlename,' ',lastname) as name")->get('users');
    $uname_arr = array_column($user->result_array(),'name','id');
    $validate = array();
    foreach($validations->result_array() as $row){
        $validate[$row['type']] = $row['user_id'] > 0 ? ucwords($uname_arr[$row['user_id']]) : ucwords($row['entered_name']);
        $validate[$row['type'].'_date'] = date("M d,Y",strtotime($row['date_created']));
    }

    $payment  = $this->db->query("SELECT * FROM po_payments where po_id = $id order by id asc limit 1");
    if($payment->num_rows() > 0){
    foreach($payment->row() as $k=> $v){
        $pay[$k] = $v;
    }
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
                <strong><?php echo isset($id) && $id > 0 ? "Manage ".$meta['ref_no'] : "New PO" ?></strong>
            </h5>
        <div class="card-body px-lg-5 pt-0">
            
            <form action="" id="manage-po">

             <div class="mt-3 col-md-12">
             <div class="row">
            <div class="col-md-6">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <label for="supplier_id"  required>Supplier</label>
                <select name="supplier_id" id="supplier_id" class="browser-default custom-select">
                    <option value=""></option>
                    <?php 
                        $supplier = $this->db->query("SELECT * FROM supplier where status = 1");
                        foreach($supplier->result_array() as $row):
                    ?>
                        <option value="<?php echo $row['id'] ?>" <?php echo isset($meta['supplier_id']) && $meta['supplier_id'] == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if(!empty($id)): ?>
            <div class="col-md-6 text-right">
            <br>
                        <div class="mt-3"><b>Date Created: </b><?php echo date("M d,Y",strtotime($meta['date_created'])) ?></div>
            </div>
            <?php endif; ?>
            </div>
            </div>
            <hr>
            <div class="mt-3 col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <label for="product_id"  required>Product</label>
                        <select name="" id="product_id" class="browser-default custom-select input-sm select2">
                            <option value=""></option>
                            <?php 
                                $product = $this->db->query("SELECT * FROM products where status = 1");
                                foreach($product->result_array() as $row):
                            ?>
                                <option value="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" data-desc="<?php echo $row['description'] ?>" data-unit="<?php echo $row['unit'] ?>"><?php echo $row['name'] . ' ('. $row['unit']. ') ' ?></option>
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
                        <th class="text-center">Description</th>
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
            <?php if(!empty($id)): ?>
            <?php if(isset($validate['approved'])): ?>
                <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                    <label for="payment_mode" class="control-label">Payment Mode</label>
                    <select name="payment_mode" id="payment_mode" class="custom-select custom-select-sm">
                            <option <?php echo isset($meta['payment_mode']) && $meta['payment_mode'] == 'Full' ? 'selected' : '' ?>>Full</option>
                            <option <?php echo isset($meta['payment_mode']) && $meta['payment_mode'] == 'Partial' ? 'selected' : '' ?>>Partial</option>
                    </select>
                    </div>
                </div>
                
                <div class="row">
                <input type="hidden" name="payment_id" value="<?php echo isset($pay['id']) ? $pay['id'] : '' ?>">
                    <div class="col-md-12 ">
                        <label for="invoice" class="control-label">Invoice #</label>
                        <input type="text" id="invoice" name="invoice" class="text-right form-control" value="<?php echo isset($pay['invoice']) ? $pay['invoice'] : '' ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 ">
                        <label for="amount" class="control-label">Amount</label>
                        <input type="number" id="amount" name="amount" class="text-right form-control" required value="<?php echo isset($pay['amount']) ? $pay['amount'] : '' ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                    <label for="payment_method" class="control-label">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="custom-select custom-select-sm">
                            <option <?php echo isset($pay['payment_method']) && $pay['payment_method'] == 'Cash' ? 'selected' : '' ?>>Cash</option>
                            <option <?php echo isset($pay['payment_method']) && $pay['payment_method'] == 'Check' ? 'selected' : '' ?>>Check</option>
                            <option <?php echo isset($pay['payment_method']) && $pay['payment_method'] == 'Credit Card' ? 'selected' : '' ?>>Credit Card</option>
                    </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 ">
                        <label for="pref_no" class="control-label">Payment Reference</label>
                        <input type="text" id="pref_no" name="pref_no" class="text-right form-control" value="<?php echo isset($pay['ref_no']) ? $pay['ref_no'] : '' ?>">
                        <small><i>Leave this blank if cash.</i></small>
                    </div>
                </div>
                
            </div>
                    <div class="col-md-6">
                        <label for="remarks" class="control-label">Remarks for Payment</label>
                        <textarea type="text" id="remarks" name="remarks" class="form-control" rows="3"><?php echo isset($pay['remarks']) ? $pay['remarks'] : '' ?></textarea>
                    </div>
                </div>
            <?php endif; ?>
            <hr>
                <table class="col-md-7">
                    <colgroup>
                        <col width="30%">
                        <col width="70%">
                    </colgroup>
                    <tr>
                        <td><b>Order Placed By :</b></td>
                        <td class="text-center border-bottom"><b><?php echo $meta['uname'] ?></b></td>
                    </tr>
                    <tr>
                        <td><b></b></td>
                        <td class="text-center "><b><?php echo date('M d,Y',strtotime($meta['date_created'])) ?></b></td>
                    </tr>
                    <?php if(isset($validate['checked'])): ?>
                    <tr>
                        <td colspan='2'>&nbsp;</td>
                    </tr>
                        <tr>
                        <td><b>Order Checked By :</b></td>
                        <td class="text-center border-bottom"><b><?php echo $validate['checked'] ?></b></td>
                    </tr>
                    <tr>
                        <td><b></b></td>
                        <td class="text-center "><b><?php echo $validate['checked_date'] ?></b></td>
                    </tr>
                    <?php endif; ?>
                    <?php if(isset($validate['approved'])): ?>
                    <tr>
                        <td colspan='2'>&nbsp;</td>
                    </tr>
                        <tr>
                        <td><b>Order approved By :</b></td>
                        <td class="text-center border-bottom"><b><?php echo $validate['approved'] ?></b></td>
                    </tr>
                    <tr>
                        <td><b></b></td>
                        <td class="text-center "><b><?php echo $validate['approved_date'] ?></b></td>
                    </tr>
                    <?php endif; ?>
                </table>
            <?php endif; ?>
            <hr>
                <div class="col-md-12">
                    <div class="row">
                    <?php if(!empty($id)): ?>
                    <div class="col-md-7"></div>
                        <button class="btn btn-sm btn-success waves-effect col-md-2" id="validate_btn" type="button">Validate</button>
                        <?php else: ?>
                    <div class="col-md-10"></div>
                        <?php endif; ?>
                    <button class="btn btn-sm btn-primary waves-effect col-md-2" type="submit">Save</button>
                    </div>
                </div>
           


            </form>
        </div>
    </div>
</div>

<script>
$('.select2').select2({
    placeholder:"Please select here",
    width:'100%'
})
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
        $('#manage-po').submit(function(e){
            e.preventDefault()
            
        if($('#po-list tbody tr').length <= 0){
            Dtoast("Please add atleast 1 item.",'warning')
            return false;
        }
            var frmData = $(this).serialize();
            start_load();
            $.ajax({
                url:'<?php echo base_url('purchases/save_po') ?>',
                method:'POST',
                data:frmData,
                error:err=>{ console.log(err)},
                success:function(resp){
                    resp  = JSON.parse(resp)
                    if(resp.status == 1){
                    if(resp.type == 1){
                        var nw = window.open("<?php echo base_url('purchases/print_po/') ?>"+resp.id,"_blank","height=600,width=800")
                            nw.print()
                            setTimeout(function(){
                                nw.close()
                        location.replace('<?php echo base_url('purchases/view/') ?>'+resp.id)
                            },1500)
                    }else{
                        location.replace('<?php echo base_url('purchases/view/') ?>'+resp.id)
                    }
                    }
                }
            })
        })
    })
    $('#new_list').click(function(){
        var prod = $('#product_id').val();
        var qty = $('#qty').val();
        var unit_price = $('#unit_price').val();
        if(prod == '' || qty == '' || unit_price==''){
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
        tr.append('<td><input type="hidden" class="input-block no-border text-center" name="item_id[]" value="0" /><input type="text" class="input-block no-border text-center" name="qty[]" value="'+qty+'" /></td>')
        tr.append('<td><input type="text" class="input-block no-border" name="unit[]" value="'+pdet.attr('data-unit')+'" readonly /></td>')
        tr.append('<td><input type="hidden" class="input-block no-border" name="product_id[]" value="'+prod+'" />'+article+'</td>')
        tr.append('<td><input type="text" class="input-block no-border text-right" name="unit_price[]" value="'+unit_price+'" /></td>')
        tr.append('<td><input type="text" class="input-block no-border text-right" name="amount[]" value="'+(qty * unit_price)+'" readonly /></td>')
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
        data:{id:'<?php echo $id ?>'},
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
                        tr.append('<td><input type="hidden" class="input-block no-border text-center" name="item_id[]" value="'+resp[k].id+'" /><input type="text" class="input-block no-border text-center" name="qty[]" value="'+resp[k].qty+'" /></td>')
                        tr.append('<td><input type="text" class="input-block no-border" name="unit[]" value="'+resp[k].unit+'"  readonly/></td>')
                        tr.append('<td><input type="hidden" class="input-block no-border" name="product_id[]" value="'+resp[k].product_id+'" />'+article+'</td>')
                        tr.append('<td><input type="text" class="input-block no-border text-right" name="unit_price[]" value="'+resp[k].unit_price+'" /></td>')
                        tr.append('<td><input type="text" class="input-block no-border text-right" name="amount[]" value="'+(resp[k].qty * resp[k].unit_price)+'" readonly/></td>')
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
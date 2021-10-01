<style>
.borde-bottom{
    border-bottom:1px solid black;
}
</style>
<?php 
if(!empty($id)){
    $sales = $this->db->query("SELECT s.*, c.name as cname,concat(u.firstname,' ',u.middlename,' ',u.lastname) as uname FROM sales s inner join users u on u.id = s.user_id inner join customers c on c.id = s.customer_id where s.id = $id");

    foreach($sales->row() as $k => $v){
        $$k=$v;
    }
    $invs = str_replace(array("[","]"),'',$inventory_ids);
    $discount = json_decode($discount_json);
    foreach($discount as $v){
        foreach($v as $k => $val){
            $val = explode(",",$val);
        $disc[$k][0] = $val[0];
        $disc[$k][1] = isset($val[1]) ? $val[1] : 0 ;

        }
    }
    $product = $this->db->query("SELECT * FROM products where id in (SELECT product_id FROM inventory where id in (".$invs.") ) ")->result_array();
    $prod_arr = array_column($product,'sku','id');
    $prod_name_arr = array_column($product,'name','id');
    $prod_desc_arr = array_column($product,'description','id');
    $inventory = $this->db->query("SELECT * FROM inventory where id in (".$invs.") order by id asc");
    $payment  = $this->db->query("SELECT * FROM payments where sales_id = $id order by id asc limit 1")->row();
    foreach($payment as $k=> $v){
        $pay[$k] = $v;
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
                <strong><?php echo isset($id) && $id > 0 ? "Manage Sales ".$ref_no : "New Sales" ?></strong>
            </h5>
        <div class="card-body px-lg-5 pt-0">
            
            <form action="" id="manage-sales">
            <input type="hidden" name="id" value="<?php echo $id ?>">

             <div class="mt-3">
             <div class="row">
            <div class="col-md-6">
                <label for="customer_id"  required>Customer</label>
                <select name="customer_id" id="customer_id" class="browser-default custom-select select2" <?php echo isset($_GET['cid']) ? "readonly" : '' ?>>
                    <option value="" <?php echo isset($_GET['cid']) ? "disabled" : '' ?>></option>
                    <?php 
                        $customer = $this->db->query("SELECT * FROM customers where status = 1");
                        foreach($customer->result_array() as $row):
                    ?>
                        <option value="<?php echo $row['id'] ?>" <?php echo isset($customer_id) && $customer_id == $row['id'] ? 'selected' : '' ?>  <?php echo isset($_GET['cid']) &&$_GET['cid'] != $row['id'] ? "disabled" : '' ?>><?php echo $row['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
                <label for="salesman_id"  required>Salesman</label>
                <select name="salesman_id" id="salesman_id" class="browser-default custom-select select2" <?php echo isset($_GET['cid']) ? "readonly" : '' ?>>
                    <option value="" <?php echo isset($_GET['cid']) ? "disabled" : '' ?>></option>
                    <?php 
                        $salesman = $this->db->query("SELECT * FROM salesman where status = 1");
                        foreach($salesman->result_array() as $row):
                    ?>
                        <option value="<?php echo $row['id'] ?>" <?php echo isset($salesman_id) && $salesman_id == $row['id'] ? 'selected' : '' ?>  <?php echo isset($_GET['salesman_id']) &&$_GET['salesman_id'] != $row['id'] ? "disabled" : '' ?>><?php echo  ucwords($row['lastname'].', '.$row['firstname'].' '.$row['middlename']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if(!empty($id)): ?>
            <div class="col-md-6 text-right">
            <br>
                        <div class="mt-3"><b>Date Created: </b><?php echo date("M d,Y",strtotime($date_created)) ?></div>
            </div>
            <?php endif; ?>
            </div>
            </div>
            <div class="row">
                <div class="form-group col-md-5">
                    <label for="po_no" class="control-label">Customer PO No.</label>
                    <input type="text" name="po_no" id="po_no" class="form-control" value="<?php echo isset($c_po_no) ? $c_po_no : '' ?>">
                </div>
                <div class="form-group col-md-5">
                    <label for="delivery_schedule" class="control-label">Delivery Date.</label>
                    <input type="date" name="delivery_schedule" value="<?php echo isset($delivery_schedule) ? date('Y-m-d',strtotime($delivery_schedule)) : date('Y-m-d') ?>" id="delivery_schedule" class="form-control" required>
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
                                    $price  = $this->db->query("SELECT * from price_list where product_id = '".$row['id']."' order by date(date_effective) desc, id desc limit 1 ");
                                    $price = $price->num_rows() > 0 ? $price->row()->price : 0;
                            ?>
                                <option value="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" data-desc="<?php echo $row['description'] ?>" data-unit="<?php echo $row['unit'] ?>" data-price="<?php echo $price ?>"><?php echo $row['name'] . ' ('. $row['unit']. ') ' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="qty"  required>Qty</label>
                        <input type="number" min="1" step="any" id="qty" class="form-control input-sm text-right">
                    </div>
                    <div class="col-md-1">
                        <label for=""  required>&nbsp</label>
                        <button class="btn btn-sm btn-primary btn-block text-center" id="new_list" type="button"><i class="fa fa-plus"></i></button>
                    </div>
                    
                </div>
                
            </div>
            <div class="mt-4">
            <table class="table table-bordered" id="sales-list">
            <colgroup>
                        <col width="10%">
                        <col width="15%">
                        <col width="20%">
                        <col width="20%">
                        <col width="5%">
                        <col width="5%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                </colgroup>
            <thead>
                    <tr>
                        <th class="text-center" rowspan="2">Qty</th>
                        <th class="text-center" rowspan="2">Unit</th>
                        <th class="text-center" rowspan="2">Description</th>
                        <th class="text-center" rowspan="2">Gross Price</th>
                        <th class="text-center" colspan="2">Discount(%)</th>
                        <th class="text-center" rowspan="2">Net Price</th>
                        <th class="text-center" rowspan="2">Amount</th>
                        <th class="text-center" rowspan="2"></th>
                    </tr>
                    <tr>
                        <th class="text-center">1st</th>
                        <th class="text-center">2nd</th>
                    </tr>
                </thead>
                <tbody>

                <?php 
                if(!empty($id)):
                    foreach($inventory->result_array() as $row):
                        $amount = ($row['unit_price'] - ($row['unit_price'] * ($disc[$row['id']][0] / 100)));
                        $amount = ($amount - ($amount * ($disc[$row['id']][1] / 100)));
                        $namount = $amount;
                        $amount = $amount * $row['qty'];
                ?>
                <tr data-id="<?php echo $row['product_id'] ?>">
                    <td><input type="text" class="input-block no-border text-center" name="qty[]" value="<?php echo $row['qty'] ?>" /><input type="hidden" name="inv_id[]" value="<?php echo $row['id'] ?>"></td>
                    <td><input type="text" class="input-block no-border" name="unit[]" value="<?php echo $row['unit'] ?>" readonly /></td>
                    <td><input type="hidden" class="input-block no-border" name="product_id[]" value="<?php echo $row['product_id'] ?>" />
                        <p><?php echo $prod_name_arr[$row['product_id']] ?></p>
                    </td>
                    <td><input type="text" class="input-block no-border text-right" name="unit_price[]" value="<?php echo $row['unit_price'] ?>" /></td>
                    <td>
                    <input type="text" class="input-block no-border text-right" name="discount[]" value="<?php echo $disc[$row['id']][0] ?>"  />
                    </td>
                    <td>
                    <input type="text" class="input-block no-border text-right" name="discount2[]" value="<?php echo $disc[$row['id']][1] ?>"  />
                    </td>
                    <td><input type="text" class="input-block no-border text-right" name="nprice[]" value="<?php echo $namount ?>" readonly/></td>
                    <td><input type="text" class="input-block no-border text-right" name="amount[]" value="<?php echo $amount ?>" readonly /></td>
                    <td><button class="btn btn-sm btn-danger" type="button" onclick="remove_list($(this))"><i class="fa fa-trash"></i></button></td>
                </tr>

                <?php endforeach; ?>
                <?php endif; ?>

                
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-right" colspan='7'>Total</th>
                        <th class="text-right gtotal"></th>
                        <th class=""><input type="hidden" name="total_amount" ></th>
                    </tr>
                </tfoot>
            </table>
            </div>
            <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                    <label for="payment_mode" class="control-label">Payment Mode</label>
                    <select name="payment_mode" id="payment_mode" class="custom-select custom-select-sm">
                            <option <?php echo isset($payment_mode) && $payment_mode == 'Full' ? 'selected' : '' ?>>Full</option>
                            <option <?php echo isset($payment_mode) && $payment_mode == 'Partial' ? 'selected' : '' ?>>Partial</option>
                            <option <?php echo isset($payment_mode) && $payment_mode == 'Terms' ? 'selected' : '' ?>>Terms</option>
                    </select>
                    </div>
                </div>
                
                <input type="hidden" name="payment_id" value="<?php echo isset($pay['id']) ? $pay['id'] : '' ?>">
                <div class="row">
                    <div class="col-md-12 ">
                        <label for="invoice" class="control-label">Invoice #</label>
                        <input type="text" id="invoice" name="invoice" class="text-right form-control" value="<?php echo isset($pay['invoice']) ? $pay['invoice'] : '' ?>">
                        <small><i>Leave this blank to auto generate otherwise enter invoice #.</i></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 ">
                        <label for="amount" class="control-label">Amount</label>
                        <input type="number" id="amount" name="amount" class="text-right form-control" value="<?php echo isset($pay['amount']) ? $pay['amount'] : '' ?>">
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
            <div class="row mt-3 ">
                
            </div>
            
            <hr>
                <div class="col-md-12">
                    <div class="row">
                    <div class="col-md-10"></div>
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
        $('input, textarea').each(function(){
            if($(this).val() != '')
            $(this).siblings('label').addClass('active')
          })
        if('<?php echo !empty($id) ? 1 : 0 ?>' == 1)
        compute_total();
    if('<?php echo $this->session->flashdata('save_sales') ?>' == 1)
            Dtoast("Data successfully added",'success');
    if('<?php echo $this->session->flashdata('save_sales') ?>' == 2)
            Dtoast("Data successfully updated",'success');
        $('input, textarea').trigger('focus')
        $('input, textarea').trigger('blur')
        $('#manage-sales').submit(function(e){
            e.preventDefault()
            
        if($('#sales-list tbody tr').length <= 0){
            Dtoast("Please add atleast 1 item.",'warning')
            return false;
        }
            var frmData = $(this).serialize();
            start_load();
            $.ajax({
                url:'<?php echo base_url('sales/save_sales') ?>',
                method:'POST',
                data:frmData,
                error:err=>{ console.log(err)},
                success:function(resp){
                    resp  = JSON.parse(resp)
                    if(resp.status == 1){
                        var nw = window.open("<?php echo base_url('sales/print_sales/') ?>"+resp.id,"_blank","height=600,width=800")
                        nw.print()
                        setTimeout(function(){
                            nw.close()
                            location.replace('<?php echo base_url('sales/view/') ?>'+resp.id)
                        },1500)
                    }
                }
            })
        })
    })
    $('#new_list').click(function(){
        var prod = $('#product_id').val();
        var qty = $('#qty').val();
        if(prod == '' || qty == ''){
            Dtoast("Please fill all item list.",'warning')
            return false;
        }
        if($('#sales-list').find('tr[data-id="'+prod+'"]').length > 0){
            Dtoast("Item already exist.",'warning')
            return false;
        }
        $.ajax({
            url:'<?php echo base_url('sales/check_prod_qty') ?>',
            method:'POST',
            data:{id:prod,qty:qty},
            success:function(resp){
                resp = JSON.parse(resp)
                if(resp.status != 'accept'){
                    Dtoast("Quantity is higher than available stock.",'error')
                    return false;
                }else{
                    $('#product_id').val('').select2()
                    $('#qty').val('')
                    
                    var pdet = $('#product_id option[value="'+prod+'"]');
                    var tr =$('<tr class="item-list"></tr>');
                    var article = '<p>'+pdet.attr('data-name')+'</p>';

                        tr.attr('data-id',prod)
                    tr.append('<td><input type="text" class="input-block no-border text-center" name="qty[]" value="'+qty+'" /><input type="hidden" name="inv_id[]" value=""></td>')
                    tr.append('<td><input type="text" class="input-block no-border" name="unit[]" value="'+pdet.attr('data-unit')+'" readonly /></td>')
                    tr.append('<td><input type="hidden" class="input-block no-border" name="product_id[]" value="'+prod+'" />'+article+'</td>')
                    tr.append('<td><input type="text" class="input-block no-border text-right" name="unit_price[]" value="'+pdet.attr('data-price')+'"  /></td>')
                    tr.append('<td><input type="text" class="input-block no-border text-right" name="discount[]" value="0"  /></td>')
                    tr.append('<td><input type="text" class="input-block no-border text-right" name="discount2[]" value="0"  /></td>')
                    tr.append('<td><input type="text" class="input-block no-border text-right" name="nprice[]" value="'+pdet.attr('data-price')+'" readonly /></td>')
                    tr.append('<td><input type="text" class="input-block no-border text-right" name="amount[]" value="'+(qty * pdet.attr('data-price'))+'" readonly /></td>')
                    tr.append('<td><button class="btn btn-sm btn-danger" type="button" onclick="remove_list($(this))"><i class="fa fa-trash"></i></button></td>')
                    // console.log(tr.html())
                    $('#sales-list tbody').append(tr)

                    $('.input-block').each(function(){
                        $(this).height($(this).parent().height())
                    })
                    compute_total()
                }
            }
        })
       
    })
    function remove_list(_this){
        _this.closest('tr').remove();
        compute_total()
    }
    function compute_total(){
        var total = 0;
        $('[name="qty[]"]').keyup(function(e){
            e.preventDefault()
            var prod = $(this).closest('tr').attr('data-id');
            var qty = $(this).closest('tr').find('[name="qty[]"]').val();
            _this = $(this)
            $.ajax({
                url:'<?php echo base_url('sales/check_prod_qty') ?>',
                method:'POST',
                data:{id:prod,qty:qty},
                error:err=>{
                    console.log(err)
                    Dtoast('An error occured.','error')
                },
                success:function(resp){
                    resp = JSON.parse(resp)
                    if(resp.status == 'accept'){
                        var unit_price = parseFloat(_this.closest('tr').find('[name="unit_price[]"]').val());
                        var discount = parseFloat(_this.closest('tr').find('[name="discount[]"]').val());
                        var discount2 = parseFloat(_this.closest('tr').find('[name="discount2[]"]').val());
                        var amount = (unit_price - (unit_price * (discount /100)));
                        var namount = (amount - (amount * (discount2 /100)));
                        amount = (amount - (amount * (discount2 /100))) * qty;

                        _this.closest('tr').find('[name="amount[]"]').val(amount.toFixed(2))
                        _this.closest('tr').find('[name="nprice[]"]').val(namount.toFixed(2))
                        compute_total()
                    }else{
                        var qty = parseFloat(resp.max_qty)
                        _this.val(qty)
                        _this.select();
                        Dtoast("Product has only "+qty+" remaining stock","warning")
                        var unit_price = parseFloat(_this.closest('tr').find('[name="unit_price[]"]').val());
                        var discount = parseFloat(_this.closest('tr').find('[name="discount[]"]').val());
                        var discount2 = parseFloat(_this.closest('tr').find('[name="discount2[]"]').val());
                        var amount = (unit_price - (unit_price * (discount /100)));
                        var namount = (amount - (amount * (discount2 /100)));
                        amount = (amount - (amount * (discount2 /100))) * qty;

                        _this.closest('tr').find('[name="amount[]"]').val(amount.toFixed(2))
                        _this.closest('tr').find('[name="nprice[]"]').val(namount.toFixed(2))
                        compute_total()
                    }
                }
            })
            
        })
        $(' [name="unit_price[]"], [name="discount[]"], [name="discount2[]"]').keyup(function(){
           
            var qty = parseFloat($(this).closest('tr').find('[name="qty[]"]').val());
            var unit_price = parseFloat($(this).closest('tr').find('[name="unit_price[]"]').val());
            var discount = parseFloat($(this).closest('tr').find('[name="discount[]"]').val());
            var discount2 = parseFloat($(this).closest('tr').find('[name="discount2[]"]').val());
            var amount = (unit_price - (unit_price * (discount /100)));
            var namount = (amount - (amount * (discount2 /100)));
            amount = (amount - (amount * (discount2 /100))) * qty;

            $(this).closest('tr').find('[name="amount[]"]').val(amount.toFixed(2))
            $(this).closest('tr').find('[name="nprice[]"]').val(namount.toFixed(2))
            compute_total()
        })
        $('[name="amount[]"]').each(function(){
             total += parseFloat($(this).val());
        })
        $('#sales-list tfoot .gtotal').html(parseFloat(total).toLocaleString('en-us',{ style:'decimal', maximumFractionDigits: 2,minimumFractionDigits: 2 }))
        $('[name="total_amount"]').val(total);
    }
    
</script>
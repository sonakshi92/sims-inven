<style>
.borde-bottom{
    border-bottom:1px solid black;
}
</style>
<?php 
if(!empty($id)){
    $qry=$this->db->query("SELECT s.*, c.name as cname,concat(u.firstname,' ',u.middlename,' ',u.lastname) as uname FROM sales_returns s inner join customers c on c.id =s.customer_id inner join users u on u.id = s.user_id where s.id =".$id);
foreach($qry->row() as $k => $v){
    $$k =$v;
}
    $data = json_decode($data_json);
        foreach($data as $k => $val){
            $v = array();
            foreach($val as $key=>$value){
                $v[$key] = $value;
            }
        $data[$k] = $v;

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
                <strong><?php echo isset($id) && $id > 0 ? "Manage Sales Return ".$ref_no : "New Sales Return" ?></strong>
            </h5>
        <div class="card-body px-lg-5 pt-0">
            
            <form action="" id="manage-sales">

             <div class="mt-3">
             <div class="row">
            <div class="col-md-6">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <input type="hidden" name="ref_no" value="<?php echo isset($ref_no) ? $ref_no: '' ?>">
                <label for="customer_id"  required>Customer</label>
                <select name="customer_id" id="customer_id" class="browser-default custom-select select2">
                    <option value=""></option>
                    <?php 
                        $customer = $this->db->query("SELECT * FROM customers where status = 1");
                        foreach($customer->result_array() as $row):
                    ?>
                        <option value="<?php echo $row['id'] ?>" <?php echo isset($customer_id) && $customer_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="action_type"  required>Action Type</label>
                <select name="action_type" id="action_type" class="browser-default custom-select select2">
                        <option value="1" <?php echo isset($action_type) && $action_type == 1 ? 'selected' : '' ?>>Replace Item</option>
                        <option value="2" <?php echo isset($action_type) && $action_type == 2 ? 'selected' : '' ?>>Refund Item</option>
                </select>
            </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="salesman_id"  required>Salesman</label>
                    <select name="salesman_id" id="salesman_id" class="browser-default custom-select select2">
                    <option value=""></option>
                    <?php 
                        $customer = $this->db->query("SELECT *,concat(firstname,' ',middlename,' ',lastname) as name FROM salesman where status = 1");
                        foreach($customer->result_array() as $row):
                    ?>
                        <option value="<?php echo $row['id'] ?>" <?php echo isset($salesman_id) && $salesman_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                </div>
            </div>
            </div>
            
            <hr>
            <div class="mt-3 col-md-12">
                <div class="row">
                    <div class="col-md-3">
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
                    <div class="col-md-2">
                        <label for="qty"  required>Price</label>
                        <input type="number" min="1" step="any" id="price" class="form-control input-sm text-right">
                        <small>Refer From Receipt</small>
                    </div>
                    <div class="col-md-4">
                        <label for="qty"  required>Issue</label>
                        <textarea name="" id="issue" cols="30" rows="2"  class="form-control"></textarea>
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
                        <col width="25%">
                        <col width="25%">
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
                <tbody>

                <?php 
                if(!empty($id)):
                    foreach($data as $row):
                        $amount = $row['unit_price'] * $row['qty'];
                        $prod = $this->db->query("SELECT * FROM products where id =".$row['product_id'])->row();
                        ?>
                <tr data-id="<?php echo $row['product_id'] ?>">
                    <td><input type="text" class="input-block no-border text-center" name="qty[]" value="<?php echo $row['qty'] ?>" /><input type="hidden" name="inv_id[]" value="<?php echo isset($row['inv_id'])? $row['inv_id'] : '' ?>"></td>
                    <td><input type="text" class="input-block no-border" name="unit[]" value="<?php echo $row['unit'] ?>" readonly /></td>
                    <td><input type="hidden" class="input-block no-border" name="product_id[]" value="<?php echo $row['product_id'] ?>" />
                    <input type="hidden" class="input-block no-border" name="issue[]" value="<?php echo $row['issue'] ?>" />
                        <p><?php echo $prod->name ?></p>
                    </td>
                    <td><input type="text" class="input-block no-border text-right" name="unit_price[]" value="<?php echo $row['unit_price'] ?>"  readonly/></td>
                    <td><input type="text" class="input-block no-border text-right" name="amount[]" value="<?php echo $amount ?>" readonly /></td>
                    <td><button class="btn btn-sm btn-danger" type="button" onclick="remove_list($(this))"><i class="fa fa-trash"></i></button></td>
                </tr>

                <?php endforeach; ?>
                <?php endif; ?>

                
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-right" colspan='4'>Total</th>
                        <th class="text-right gtotal"></th>
                        <th class=""><input type="hidden" name="total_amount" ></th>
                    </tr>
                </tfoot>
            </table>
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
<style>
td p {
    margin:unset
}
</style>
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
    if('<?php echo $this->session->flashdata('save_sales_return') ?>' == 1)
            Dtoast("Data successfully added",'success');
    if('<?php echo $this->session->flashdata('save_sales_return') ?>' == 2)
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
                url:'<?php echo base_url('sales/save_sales_return') ?>',
                method:'POST',
                data:frmData,
                error:err=>{ console.log(err)},
                success:function(resp){
                    resp  = JSON.parse(resp)
                    if(resp.status == 1){
                        location.replace('<?php echo base_url('sales/view_return/') ?>'+resp.id)
                    }
                }
            })
        })
    })
    $('#new_list').click(function(){
        var prod = $('#product_id').val();
        var qty = $('#qty').val();
        var price = $('#price').val();
        var issue = $('#issue').val();
        if(prod == '' || qty == '' || price == '' || issue == ''){
            Dtoast("Please fill all item list.",'warning')
            return false;
        }
        if($('#sales-list').find('tr[data-id="'+prod+'"]').length > 0){
            Dtoast("Item already exist.",'warning')
            return false;
        }
        $('#product_id').val('').select2()
        $('#qty').val('')
        $('#price').val('')
        $('#issue').val('')
        
        var pdet = $('#product_id option[value="'+prod+'"]');
        var tr =$('<tr class="item-list"></tr>');
        var article = '<p>'+pdet.attr('data-name')+'</p>';

            tr.attr('data-id',prod)
        tr.append('<td><input type="text" class="input-block no-border text-center" name="qty[]" value="'+qty+'" /><input type="hidden" name="inv_id[]" value=""></td>')
        tr.append('<td><input type="text" class="input-block no-border" name="unit[]" value="'+pdet.attr('data-unit')+'" readonly /></td>')
        tr.append('<td><input type="hidden" class="input-block no-border" name="product_id[]" value="'+prod+'" /><input type="hidden" class="input-block no-border" name="issue[]" value="'+issue+'" />'+article+'</td>')
        tr.append('<td><input type="text" class="input-block no-border text-right" name="unit_price[]" value="'+price+'"  readonly/></td>')
        tr.append('<td><input type="text" class="input-block no-border text-right" name="amount[]" value="'+(qty * price)+'" readonly /></td>')
        tr.append('<td><button class="btn btn-sm btn-danger" type="button" onclick="remove_list($(this))"><i class="fa fa-trash"></i></button></td>')
        // console.log(tr.html())
        $('#sales-list tbody').append(tr)

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
            $(this).closest('tr').find('[name="amount[]"]').val($(this).closest('tr').find('[name="qty[]"]').val() * $(this).closest('tr').find('[name="unit_price[]"]').val())
            compute_total()
        })
        $('[name="amount[]"]').each(function(){
             total += parseFloat($(this).val());
        })
        $('#sales-list tfoot .gtotal').html(parseFloat(total).toLocaleString('en-us',{ style:'decimal', maximumFractionDigits: 2,minimumFractionDigits: 2 }))
        $('[name="total_amount"]').val(total);
    }
    
</script>
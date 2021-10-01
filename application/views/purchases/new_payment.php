<?php
if(!empty($pid)){
    $payment = $this->db->get_where('po_payments',array('id'=>$pid))->row();
    foreach($payment as $k=> $v){
        $pay[$k] = $v;
    }
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
        <input type="hidden" name="id" value="<?php echo $pid ?>">
        <label for="payment_method" class="control-label">Payment Method</label>
        <select name="payment_method" id="payment_method" class="custom-select browser-default custom-select-sm">
                <option <?php echo isset($pay['payment_method']) && $pay['payment_method'] == 'Cash' ? 'selected' : '' ?>>Cash</option>
                <option <?php echo isset($pay['payment_method']) && $pay['payment_method'] == 'Check' ? 'selected' : '' ?>>Check</option>
                <option <?php echo isset($pay['payment_method']) && $pay['payment_method'] == 'Credit Card' ? 'selected' : '' ?>>Credit Card</option>
        </select>
        </div>
    </div>
    <div class="row">
        <input type="hidden" name="po_id" value="<?php echo $id ?>">
        <div class="col-md-12 ">
            <label for="invoice" class="control-label">Invoice #</label>
            <input type="text" id="invoice" name="invoice" class="text-right form-control" required value="<?php echo isset($pay['invoice']) ? $pay['invoice'] : '' ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 ">
            <label for="amount" class="control-label">Amount</label>
            <input type="number" id="amount" name="amount" class="text-right form-control" required  value="<?php echo isset($pay['amount']) ? $pay['amount'] : '' ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 ">
            <label for="pref_no" class="control-label">Payment Reference</label>
            <input type="text" id="pref_no" name="pref_no" class="text-right form-control" value="<?php echo isset($pay['ref_no']) ? $pay['ref_no'] : '' ?>">
            <small><i>Leave this blank if cash.</i></small>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <label for="remarks" class="control-label">Remarks for Payment</label>
            <textarea type="text" id="remarks" name="remarks" class="form-control" rows="3"><?php echo isset($pay['remarks']) ? $pay['remarks'] : '' ?></textarea>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#manage-payments').submit(function(e){
        e.preventDefault();
        start_load()
        $.ajax({
            url:"<?php echo base_url('purchases/save_payment') ?>",
            method:'POST',
            data:$(this).serialize(),
            success:function(resp){
                if(resp == 1){
                    location.reload()
                }
            }
        })
    })
})
</script>
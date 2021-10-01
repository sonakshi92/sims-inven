<div class="form-group">
    <select name="po_id" id="po_id" class="default-browser custom-select select2">
        <option value=""></option>

        <?php 
            $po = $this->db->query("SELECT * FROM purchases where id in (SELECT form_id from validation where form_type = 'po' and `type`='approved' ) and status = 1  ");
            foreach($po->result_array() as $row):
        ?>
        <option value="<?php echo $row['id'] ?>"><?php echo $row['ref_no'] ?></option>
    <?php endforeach; ?>
    </select>
</div>
<script>
$('.select2').select2({
    placeholder:"Please select PO here",
    width:'100%'
})
$(document).ready(function(){
    $('#receive-po').submit(function(e){
        e.preventDefault()
        location.replace('<?php echo base_url('purchases/manage_receiving/') ?>'+$(this).find('[name="po_id"]').val())
    })
})
</script>
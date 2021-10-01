<div class="container-fluid">
        <div class="mb-2 form-group">
        <input type="hidden" name="form_type" value="po">
        <input type="hidden" name="form_id" value="<?php echo $id ?>">
            <label for="type">Validation Type</label>
            <select name="type" id="type" class="custom-select browser-default">
                <option value="checked">Checked</option>
                <option value="approved">Approved</option>
            </select>
        </div>
        <div class="mb-2 form-group">
            <label for="user_id">Validated by:</label>
            <select name="user_id" id="user_id" class="custom-select browser-default">
                <option value=""></option>
                <option value="0">Enter Name Manually</option>
                <?php 
                    $user = $this->db->get_where('users',array('status'=>1));
                    foreach($user->result_array() as $row):
                ?>
                    <option value="<?php echo $row['id'] ?>"><?php echo ucwords($row['firstname'].' '. (!empty($row['middlename'] ? $row['middlename'].' ':'')).$row['lastname']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="entered_name" class="form-control">
        </div>
</div>
<script>
$('#user_id').select2({
    placeholder:"Please Select Here",
    width:'100%'
}).on('change',function(e){
    if($('#user_id').val() == 0 ){
        $('#user_id').parent().find('.selection').hide()
        $('[name="entered_name"]').removeAttr('type').attr('type','text')
    }
})

$(document).ready(function(){
    $('#validate-po').submit(function(e){
    e.preventDefault()
    start_load()
    $.ajax({
        url:'<?php echo base_url('purchases/save_validation') ?>',
        method:'POST',
        data:$(this).serialize(),
        error:err=>console.log(err),
        success:function(resp){
            if(resp == 1){
                Dtoast('Data successfully saved.','success');
                setTimeout(function(){
                    location.reload()
                },2000)
            }
        }
    })
})
})
</script>
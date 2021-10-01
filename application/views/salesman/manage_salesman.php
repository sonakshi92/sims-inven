<?php 
if(!empty($id)){
    $qry = $this->db->get_where("salesman",array("id"=>$id))->row();
    $meta = array();
    foreach($qry as $k => $v){
        $meta[$k] = $v;
    }
}
?>

<div class="container-fluid">
            <div class="md-form mt-3">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo isset($meta['firstname']) ? $meta['firstname'] : '' ?>">
                <label for="firstname"  required>First Name</label>
            </div>
            <div class="md-form mt-3">
                <input type="text" id="middlename" name="middlename" class="form-control" value="<?php echo isset($meta['middlename']) ? $meta['middlename'] : '' ?>">
                <label for="middlename"  required>M.I</label>
            </div>
            <div class="md-form mt-3">
                <input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo isset($meta['lastname']) ? $meta['lastname'] : '' ?>">
                <label for="lastname"  required>Last Name</label>
            </div>

</div>

<script>
    $(document).ready(function(){
        $('input, textarea').trigger('focus')
        $('input, textarea').trigger('blur')
        $('#manage-salesman').submit(function(e){
            e.preventDefault()
            var frmData = $(this).serialize();
            start_load();
            $.ajax({
                url:'<?php echo base_url('salesman/save_salesman') ?>',
                method:'POST',
                data:frmData,
                error:err=>{ console.log(err)},
                success:function(resp){
                    if(resp == 1){
                        location.reload()
                    }
                }
            })
        })
    })
</script>
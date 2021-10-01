<?php
if(!empty($id)){
$user = $this->db->get_where('users',array('id'=>$id));
// echo $this->db->last_query();
foreach($user->row() as $k=> $v){
    $$k = $v;
}
}
?>

<div class="container-fluid">
    <div class="col-lg-12">
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <div id="msg"></div>
        <div class="form-group">
            <label for="" class="control-label">First Name</label>
            <input type="text" class="form-control" name="firstname" value="<?php echo isset($firstname) ? $firstname : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Middle Name</label>
            <input type="text" class="form-control" name="middlename" value="<?php echo isset($middlename) ? $middlename : '' ?>">
        </div>
        <div class="form-group">
            <label for="" class="control-label">Last Name</label>
            <input type="text" class="form-control" name="lastname" value="<?php echo isset($lastname) ? $lastname : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Username</label>
            <input type="text" class="form-control" name="username" value="<?php echo isset($username) ? $username : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Password</label>
            <input type="password" class="form-control" name="password" value="" required>
            <?php if($id > 0): ?>
                <small><i>Leave this blank if you dont want to update the password</i></small>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="" class="control-label">User Type</label>
            <select name="type" id="" class="custom-select browser-default">
                <option value="1" <?php echo isset($type) && $type == 1 ? "selected": '' ?>>Admin</option>
                <option value="2" <?php echo isset($type) && $type == 2 ? "selected": '' ?>>Staff</option>
            </select>
        </div>

    </div>
</div>

<script>
   $(document).ready(function(){
    $('#manage-users').submit(function(e){
        e.preventDefault()
        start_load()
        $('#msg').html('')
        $.ajax({
            url:'<?php echo base_url('cogs/save_users') ?>',
            method:'POST',
            data:$(this).serialize(),
            error:err=>{
                console.log(err)
                Dtoast("An error occured","error")
                    end_load()
                
            },
            success:function(resp){
                resp = JSON.parse(resp)
                if(resp.status == 1){
                    Dtoast("User's Data Successfully Saved","success")
                    setTimeout(function(){
                        location.reload();
                    },1000)
                }else{
                    $('#msg').html('<div class="alert alert-danger">'+resp.msg+'</div>')
                    end_load()
                }
            }
        })
    })
   })
</script>
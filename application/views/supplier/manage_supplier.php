<?php 
if(!empty($id)){
    $qry = $this->db->get_where("supplier",array("id"=>$id))->row();
    $meta = array();
    foreach($qry as $k => $v){
        $meta[$k] = $v;
    }
}
?>

<div class="col-md-12 mb2 mt2">
    <div class="card">
            <h5 class="card-header info-color white-text text-center py-4">
                <strong><?php echo isset($id) && $id > 0 ? "Manage Supplier" : "New Supplier" ?></strong>
            </h5>
        <div class="card-body px-lg-5 pt-0">
            
            <form action="" id="manage-supplier">

             
            <div class="md-form mt-3">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <input type="text" id="name" name="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>">
                <label for="name"  required>Name</label>
            </div>

            <div class="md-form">
                <textarea id="address" class="form-control md-textarea" rows="2" name="address" required><?php echo isset($meta['address']) ? htmlentities($meta['address']) : '' ?></textarea>
                <label for="address">Address</label>
            </div>

            <div class="md-form mt-3">
                <input type="text" id="contact_person" name="contact_person" class="form-control" value="<?php echo isset($meta['contact_person']) ? $meta['contact_person'] : '' ?>">
                <label for="contact_person" >Contact Peson (optional)</label>
            </div>

            <div class="md-form mt-3">
                <input type="text" id="contact_number" name="contact_number" class="form-control" value="<?php echo isset($meta['contact_number']) ? $meta['contact_number'] : '' ?>">
                <label for="contact_number" required>Contact Number</label>
            </div>

            

            <center><button class="btn btn-outline-info btn-block z-depth-0 my-4 waves-effect col-md-2" type="submit">Save</button></center>


            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('input, textarea').trigger('focus')
        $('input, textarea').trigger('blur')
        $('#manage-supplier').submit(function(e){
            e.preventDefault()
            var frmData = $(this).serialize();
            start_load();
            $.ajax({
                url:'<?php echo base_url('supplier/save_supplier') ?>',
                method:'POST',
                data:frmData,
                error:err=>{ console.log(err)},
                success:function(resp){
                    if(resp == 1){
                        location.replace('<?php echo base_url('supplier') ?>')
                    }
                }
            })
        })
    })
</script>
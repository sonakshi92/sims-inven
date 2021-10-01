<?php 
if(!empty($id)){
    $qry = $this->db->get_where('products',array('id'=> $id ))->row();
    // echo $this->db->last_query();
    $meta=array();
    foreach($qry as $k => $v){
        $meta[$k] = $v;
    }
}

?>
<style>
input[type="checkbox"]{
    cursor:pointer
}
#bulk-only{
    display:none
}
</style>
<div class="col-md-12 mb2 mt2">
    <div class="card">
            <h5 class="card-header info-color white-text text-center py-4">
                <strong><?php echo isset($id) && $id > 0 ? "Manage Product" : "New Product" ?></strong>
            </h5>
        <div class="card-body px-lg-5 pt-0">
            
            <form action="" id="manage-product">

             
            <div class="md-form mt-3 col-md-5">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <input type="text" id="name" name="name" class="form-control" value ="<?php echo isset($meta['name']) ? $meta['name']:'' ?>">
                <label for="name"  required>Name</label>
            </div>
            <div class="md-form mt-3 col-md-5">
                <input type="text" id="unit" name="unit" class="form-control" value ="<?php echo isset($meta['unit']) ? $meta['unit']:'' ?>">
                <label for="unit"  required>Unit</label>
            </div>
            <div class="">
                <label for=""><input type="checkbox" name="is_bulk" id="is_bulk" <?php echo isset($meta['is_bulk']) && $meta['is_bulk'] == 1 ? 'checked' : '' ?>><small><i>Bulk Product</i></small></label>
            </div>
            <div id="bulk-only" class=" col-lg-12" <?php echo isset($meta['is_bulk']) && $meta['is_bulk'] == 1 ? 'style="display:block"' : '' ?>>
            <div class="row">
            <div class="md-form mt-2 col-md-4">
                <label for="convert_unit" class="control-label">Convert Unit</label>
                <input type="text" class="form-control" name="convert_unit" id="convert_unit"  value ="<?php echo isset($meta['convert_unit']) ? $meta['convert_unit']:'' ?>" <?php echo isset($meta['is_bulk']) && $meta['is_bulk'] == 1 ? 'disabled' : '' ?>>
            </div>
            <div class="md-form mt-2 col-md-3">
                <label for="convert_qty" class="control-label">Convert Qty</label>
                <input type="number" min="2" max="99999" class="form-control" name="convert_qty"  id="convert_qty"  value ="<?php echo isset($meta['convert_qty']) && $meta['convert_qty'] >0 ? $meta['convert_qty']:'' ?>" <?php echo isset($meta['is_bulk']) && $meta['is_bulk'] == 1 ? 'disabled' : '' ?>>
            </div>
            </div>
            </div>

            <div class="md-form">
                <textarea id="description" class="form-control md-textarea" rows="2" name="description" required><?php echo isset($meta['description']) ? $meta['description']:'' ?></textarea>
                <label for="description">Description</label>
            </div>
           

            

            <center><button class="btn btn-outline-info btn-block z-depth-0 my-4 waves-effect col-md-2" type="submit">Save</button></center>


            </form>
        </div>
    </div>
</div>

<script>
$('#is_bulk').change(function(){
    if($(this).prop('checked') == true){
    $('#bulk-only').show('slideDown')
    $('[name="convert_qty"],[name="convert_unit"]').attr('disbled',true)
    }else{
    $('#bulk-only').hide('slideUp')
    $('[name="convert_qty"],[name="convert_unit"]').attr('disbled',false)
    }
})
    $(document).ready(function(){
        $('input, textarea').trigger('change')
        $('#manage-product').submit(function(e){
            e.preventDefault()
            var frmData = $(this).serialize();
            start_load()
            $('button[type="submit"]').attr('disable',true).html('Saving ...')
            $.ajax({
                url:'<?php echo base_url('product/save_product') ?>',
                method:'POST',
                data:frmData,
                error:err=>{ console.log(err)},
                success:function(resp){
                    if(resp > 0){
                        location.replace('<?php echo base_url('product/view/') ?>'+resp)
                    }
                }
            })
        })
    })
</script>
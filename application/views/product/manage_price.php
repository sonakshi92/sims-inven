<div class="container-fluid">
    <div class="col-md-12">
    <input type="hidden" name="product_id" value="<?php echo $id ?>">
        <div class="row">
            <div class="md-form col-sm-8">
                <label for="" class="control-label">Price</label>
                <input type="number" name="price" id="" class="form-control text-right" step="any" required>
            </div>
        </div>
        <div class="row">
            <div class="md-form col-sm-8">
                <label for="date_effect" class="control-label">Date Effective</label>
                <input type="date" name="date_effective" id="date_effect" class="form-control" step="any" value="<?php echo date("Y-m-d") ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="md-form col-sm-12">
                <label for="" class="control-label">Description</label>
                <textarea name="description" id="" cols="30" rows="3" class="form-control md-textarea"></textarea>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
   $('#manage-price').submit(function(e){
       e.preventDefault();
       start_load();
       $.ajax({
           url:'<?php echo base_url('product/save_price') ?>',
           method:'POST',
           data:$(this).serialize(),
           success:function(resp){
               if(resp == 1){
                   location.reload();
               }
           }
       })
   })
})
 
</script>
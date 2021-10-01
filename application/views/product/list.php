<div class="col-md-12 mt1 mb1">
    <div class="row">
        <a href="<?php echo base_url('product/manage') ?>" class="btn btn-sm btn-info float-right"><i class="fa fa-plus"></i> New Product</a>
    </div>
</div>

<div class="col-md-12 mt1 mb1">
    <div class="row">

        <div class="card col-md-12">
            <div class="card-body">

                <table class="table table-bordered table-striped" width="100%" id="product-field">

                    <colgroup>
                        <col width="5%">
                        <col width="20%">
                        <col width="25%">
                        <col width="30%">
                        <col width="5%">
                        <col width="15%">
                    </colgroup>
                    
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>SKU</th>
                            <th>Name (unit)</th>
                            <th>Description</th>
                            <th>is_bulk?</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
        </div>

    </div>
</div>
<style>
td a{
    color:blue !important;
}
</style>
<script>
window.load_product = function (){

    $('#product-field tbody').html('<tr><td colspan="6">Loading data.</td></tr>')
    $.ajax({
        url:'<?php echo base_url('product/load_list') ?>',
        method:'POST',
        data:{},
        error:err=>console.log(err),
        success:function(resp){
            if(resp){
                if(typeof resp != undefined){
                $('#product-field tbody').html('')
                resp = JSON.parse(resp)
                var i = 0;
                Object.keys(resp).map(k=>{
                    i++;
                    var tr = $('<tr>')
                    tr.append('<td>'+i+'</td>')
                    tr.append('<td><a href="<?php echo base_url('product/view/') ?>'+resp[k].id+'">'+resp[k].sku+'</a></td>')
                    tr.append('<td>'+resp[k].name+' ('+resp[k].unit+')</td>')
                    tr.append('<td>'+resp[k].description+'</td>')
                    if(resp[k].is_bulk == 1)
                    tr.append('<td class="text-center"><div class="badge badge-success">Yes</div></td>')
                    else
                    tr.append('<td class="text-center"><div class="badge badge-default">No</div></td>')
                    tr.append('<td><center><button type="button" class="btn btn-sm btn-outline-primary btn-rounded waves-effec edit_product" data-id="'+resp[k].id+'"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-sm btn-outline-danger btn-rounded waves-effec remove_product" data-id="'+resp[k].id+'"><i class="fa fa-trash"></i></button></center></td>')

                $('#product-field tbody').append(tr)

                    
                })
                if(Object.keys(resp).length <= 0)
                $('#product-field tbody').html('<tr><td colspan="6">Loading data.</td></tr>')

                

                }
            }
        },
        complete:()=>{
            $('.edit_product').each(function(e){
                $(this).click(function(){
                    location.replace('<?php echo base_url() ?>product/manage/edit/'+$(this).attr('data-id'))
                })
            })
            $('.remove_product').each(function(e){
                $(this).click(function(){
                    delete_data('Are you sure to delete this data?','remove_product',[$(this).attr('data-id')])
                })
            })
            $('#product-field').dataTable()
            
        }
    })
}
function remove_product($id=''){
    $.ajax({
        url:'<?php echo base_url() ?>product/remove',
        method:'POST',
        data:{id:$id},
        error:err=>{
            console.log(err)
            Dtoast('An error occured.','error')
        },
        success:function(resp){
            if(resp == 1){
             Dtoast('Data successfully deleted.','success')
             load_product()
             $('.modal').modal('hide')
            }
        }
    })
}
$(document).ready(function(){
    if('<?php echo $this->session->flashdata('save_product') ?>' == 1)
            Dtoast("Data successfully added",'success');
    if('<?php echo $this->session->flashdata('save_product') ?>' == 2)
            Dtoast("Data successfully updated",'success');
    load_product();
})
</script>
<div class="col-md-12 mt1 mb1">
    <div class="row">
        <a href="<?php echo base_url('supplier/manage') ?>" class="btn btn-info float-right"><i class="fa fa-plus"></i> New Supplier</a>
    </div>
</div>

<div class="col-md-12 mt1 mb1">
    <div class="row">

        <div class="card col-md-12">
            <div class="card-body">

                <table class="table table-bordered table-striped" width="100%" id="supplier-field">

                    <colgroup>
                        <col width="5%">
                        <col width="20%">
                        <col width="25%">
                        <col width="20%">
                        <col width="15%">
                        <col width="15%">
                    </colgroup>
                    
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Contact Person</th>
                            <th>Contact</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
        </div>

    </div>
</div>

<script>
window.load_supplier = function (){

    $('#supplier-field tbody').html('<tr><td colspan="6">Loading data.</td></tr>')
    $.ajax({
        url:'<?php echo base_url('supplier/load_list') ?>',
        method:'POST',
        data:{},
        error:err=>console.log(err),
        success:function(resp){
            if(resp){
                if(typeof resp != undefined){
                $('#supplier-field tbody').html('')
                resp = JSON.parse(resp)
                var i = 0;
                Object.keys(resp).map(k=>{
                    i++;
                    var tr = $('<tr>')
                    tr.append('<td>'+i+'</td>')
                    tr.append('<td>'+resp[k].name+'</td>')
                    tr.append('<td>'+resp[k].address+'</td>')
                    tr.append('<td>'+resp[k].contact_person+'</td>')
                    tr.append('<td>'+resp[k].contact_number+'</td>')
                    tr.append('<td><button type="button" class="btn btn-sm btn-outline-primary btn-rounded waves-effec edit_supplier" data-id="'+resp[k].id+'"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-sm btn-outline-danger btn-rounded waves-effec remove_supplier" data-id="'+resp[k].id+'"><i class="fa fa-trash"></i></button></td>')

                $('#supplier-field tbody').append(tr)

                    
                })
                

                }
            }
        },
        complete:()=>{
            $('.edit_supplier').each(function(e){
                $(this).click(function(){
                    location.replace('<?php echo base_url() ?>supplier/manage/edit/'+$(this).attr('data-id'))
                })
            })
            $('.remove_supplier').each(function(e){
                $(this).click(function(){
                    delete_data('Are you sure to delete this data?','remove_supplier',[$(this).attr('data-id')])
                })
            })
            $('#supplier-field').dataTable()
            
        }
    })
}
function remove_supplier($id=''){
    $.ajax({
        url:'<?php echo base_url() ?>supplier/remove',
        method:'POST',
        data:{id:$id},
        error:err=>{
            console.log(err)
            Dtoast('An error occured.','error')
        },
        success:function(resp){
            if(resp == 1){
             Dtoast('Data successfully deleted.','success')
             load_supplier()
             $('.modal').modal('hide')
            }
        }
    })
}
$(document).ready(function(){
    if('<?php echo $this->session->flashdata('supplier_save') ?>' == 1)
            Dtoast("Data successfully added",'success');
    if('<?php echo $this->session->flashdata('supplier_save') ?>' == 2)
            Dtoast("Data successfully updated",'success');
    load_supplier();
})
</script>
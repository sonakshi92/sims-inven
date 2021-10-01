<div class="col-md-12 mt1 mb1">
    <div class="row">
        <a href="javascrip:void(0)" id="new_salesman" class="btn btn-sm btn-info float-right"><i class="fa fa-plus"></i> New Salesman</a>
    </div>
</div>

<div class="col-md-12 mt1 mb1">
    <div class="row">

        <div class="card col-md-12">
            <div class="card-body">

                <table class="table table-bordered table-striped" width="100%" id="salesman-field">

                  
                    
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
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
window.load_salesman = function (){
    $('#salesman-field').dataTable().fnDestroy()
    $('#salesman-field tbody').html('<tr><td colspan="3">Loading data.</td></tr>')
    $.ajax({
        url:'<?php echo base_url('salesman/load_list') ?>',
        method:'POST',
        data:{},
        error:err=>console.log(err),
        success:function(resp){
            if(resp){
                if(typeof resp != undefined){
                $('#salesman-field tbody').html('')
                resp = JSON.parse(resp)
                var i = 0;
                Object.keys(resp).map(k=>{
                    i++;
                    var tr = $('<tr>')
                    tr.append('<td>'+i+'</td>')
                    tr.append('<td>'+resp[k].name+'</td>')
                    tr.append('<td class="text-center"><button type="button" class="btn btn-sm btn-outline-primary waves-effec edit_salesman" data-id="'+resp[k].id+'"><i class="fa fa-edit"> Edit</i></button><button type="button" class="btn btn-sm btn-outline-danger waves-effec remove_salesman" data-id="'+resp[k].id+'"><i class="fa fa-trash"></i> Delete</button></td>')

                $('#salesman-field tbody').append(tr)

                    
                })
                

                }
            }
        },
        complete:()=>{
            $('.edit_salesman').each(function(e){
                $(this).click(function(){
                    frmModal("manage-salesman","Edit Salesman","<?php echo base_url() ?>salesman/manage/"+$(this).attr('data-id'))
                })
            })
            $('.remove_salesman').each(function(e){
                $(this).click(function(){
                    delete_data('Are you sure to delete this data?','remove_salesman',[$(this).attr('data-id')])
                })
            })
            $('#salesman-field').dataTable()
        }
    })
}
$('#new_salesman').click(function(){
    frmModal("manage-salesman","New Salesman","<?php echo base_url() ?>salesman/manage/")
})
function remove_salesman($id=''){
    $.ajax({
        url:'<?php echo base_url() ?>salesman/remove',
        method:'POST',
        data:{id:$id},
        error:err=>{
            console.log(err)
            Dtoast('An error occured.','error')
        },
        success:function(resp){
            if(resp == 1){
             Dtoast('Data successfully deleted.','success')
             load_salesman()
             $('.modal').modal('hide')
            }
        }
    })
}
$(document).ready(function(){
    if('<?php echo $this->session->flashdata('salesman_save') ?>' == 1)
            Dtoast("Data successfully added",'success');
    if('<?php echo $this->session->flashdata('salesman_save') ?>' == 2)
            Dtoast("Data successfully updated",'success');
    load_salesman();
})
</script>
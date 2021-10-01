<div class="col-md-12 mt1 mb1">
    <div class="row">
        <a href="javascript:void(0)" class="btn btn-info btn-sm float-right" id="receive_po"><i class="fa fa-plus"></i> Receive</a>
    </div>
</div>

<div class="col-md-12 mt1 mb1">
    <div class="row">

        <div class="card col-md-12">
            <div class="card-body">

                <table class="table table-bordered table-striped" width="100%" id="purchases-field">

                    <!-- <colgroup>
                        <col width="5%">
                        <col width="20%">
                        <col width="25%">
                        <col width="20%">
                        <col width="15%">
                        <col width="15%">
                    </colgroup> -->
                    
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Invoice</th>
                            <th>REf</th>
                            <th>Supplier</th>
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
$('#receive_po').click(function(){
    frmModal('receive-po',"Select P.O. to receive",'<?php echo base_url('purchases/po_select') ?>')
})
window.load_receivings = function (){

    $('#purchases-field tbody').html('<tr><td colspan="6">Loading data.</td></tr>')
    $.ajax({
        url:'<?php echo base_url('purchases/load_list_receiving') ?>',
        method:'POST',
        data:{},
        error:err=>console.log(err),
        success:function(resp){
            if(resp){
                if(typeof resp != undefined){
                $('#purchases-field tbody').html('')
                resp = JSON.parse(resp)
                var i = 0;
                Object.keys(resp).map(k=>{
                    i++;
                    var tr = $('<tr>')
                    tr.append('<td>'+i+'</td>')
                    tr.append('<td>'+resp[k].date_created+'</td>')
                    tr.append('<td>'+resp[k].invoice+'</td>')
                    tr.append('<td>'+resp[k].ref_no+'</td>')
                    tr.append('<td>'+resp[k].sname+'</td>')
                   
                    tr.append('<td><center><button type="button" class="btn btn-sm btn-outline-primary btn-rounded waves-effec edit_receiving" data-id="'+resp[k].id+'" data-po="'+resp[k].po_id+'"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-sm btn-outline-danger btn-rounded waves-effec remove_receiving" data-id="'+resp[k].id+'"  data-po="'+resp[k].po_id+'"><i class="fa fa-trash"></i></button></center></td>')

                $('#purchases-field tbody').append(tr)

                    
                })
                if(Object.keys(resp).length <= 0)
                $('#purchases-field tbody').html('<tr><td colspan="6">Loading data.</td></tr>')

                

                }
            }
        },
        complete:()=>{
            $('.edit_receiving').each(function(e){
                $(this).click(function(){
                    location.replace('<?php echo base_url() ?>purchases/manage_receiving/'+$(this).attr('data-po')+'/'+$(this).attr('data-id'))
                })
            })
            $('.remove_receiving').each(function(e){
                $(this).click(function(){
                    delete_data('Are you sure to delete this data?','remove_receiving',[$(this).attr('data-id')])
                })
            })
            $('#purchases-field').dataTable()
            
        }
    })
}
function remove_receiving($id=''){
    $.ajax({
        url:'<?php echo base_url() ?>purchases/remove_receiving',
        method:'POST',
        data:{id:$id},
        error:err=>{
            console.log(err)
            Dtoast('An error occured.','error')
        },
        success:function(resp){
            if(resp == 1){
             Dtoast('Data successfully deleted.','success')
             load_receivings()
             $('.modal').modal('hide')
            }
        }
    })
}
$(document).ready(function(){
    if('<?php echo $this->session->flashdata('save_receiving') ?>' == 1)
            Dtoast("Data successfully added",'success');
    if('<?php echo $this->session->flashdata('save_receiving') ?>' == 2)
            Dtoast("Data successfully updated",'success');
    load_receivings();
})
</script>
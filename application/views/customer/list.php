<div class="col-md-12 mt1 mb1">
    <div class="row">
        <a href="<?php echo base_url('customer/manage') ?>" class="btn btn-info float-right"><i class="fa fa-plus"></i> New Customer</a>
    </div>
</div>

<div class="col-md-12 mt1 mb1">
    <div class="row">

        <div class="card col-md-12">
            <div class="card-body">

                <table class="table table-bordered table-striped" width="100%" id="customer-field">

                    <colgroup>
                        <col width="3%">
                        <col width="20%">
                        <col width="25%">
                        <col width="10%">
                        <col width="20%">
                        <col width="10%">
                        <col width="12%">
                    </colgroup>
                    
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Type</th>
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
window.load_customer = function (){

    $('#customer-field tbody').html('<tr><td colspan="6">Loading data.</td></tr>')
    $.ajax({
        url:'<?php echo base_url('customer/load_list') ?>',
        method:'POST',
        data:{},
        error:err=>console.log(err),
        success:function(resp){
            if(resp){
                if(typeof resp != undefined){
                $('#customer-field tbody').html('')
                resp = JSON.parse(resp)
                var i = 0;
                Object.keys(resp).map(k=>{
                    i++;
                    var tr = $('<tr>')
                    tr.append('<td>'+i+'</td>')
                    tr.append('<td>'+resp[k].name+'</td>')
                    tr.append('<td>'+resp[k].address+'</td>')
                    tr.append('<td>'+resp[k].type+'</td>')
                    tr.append('<td>'+resp[k].contact_person+'</td>')
                    tr.append('<td>'+resp[k].contact+'</td>')
                    tr.append('<td><button type="button" class="btn btn-sm btn-outline-primary btn-rounded waves-effec view_customer" data-id="'+resp[k].id+'"><i class="fa fa-eye"></i></button><button type="button" class="btn btn-sm btn-outline-danger btn-rounded waves-effec remove_customer" data-id="'+resp[k].id+'"><i class="fa fa-trash"></i></button></td>')

                $('#customer-field tbody').append(tr)

                    
                })
                

                }
            }
        },
        complete:()=>{
            $('.view_customer').each(function(e){
                $(this).click(function(){
                    location.href = ('<?php echo base_url() ?>customer/view/'+$(this).attr('data-id'))
                })
            })
            $('.remove_customer').each(function(e){
                $(this).click(function(){
                    delete_data('Are you sure to delete this data?','remove_customer',[$(this).attr('data-id')])
                })
            })
            $('#customer-field').dataTable()
            
        }
    })
}
function remove_customer($id=''){
    $.ajax({
        url:'<?php echo base_url() ?>customer/remove',
        method:'POST',
        data:{id:$id},
        error:err=>{
            console.log(err)
            Dtoast('An error occured.','error')
        },
        success:function(resp){
            if(resp == 1){
             Dtoast('Data successfully deleted.','success')
             load_customer()
             $('.modal').modal('hide')
            }
        }
    })
}
$(document).ready(function(){
    if('<?php echo $this->session->flashdata('customer_save') ?>' == 1)
            Dtoast("Data successfully added",'success');
    if('<?php echo $this->session->flashdata('customer_save') ?>' == 2)
            Dtoast("Data successfully updated",'success');
    load_customer();
})
</script>
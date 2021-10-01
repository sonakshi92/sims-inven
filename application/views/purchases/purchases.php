<div class="col-md-12 mt1 mb1">
    <div class="row">
        <a href="<?php echo base_url('purchases/manage') ?>" class="btn btn-info btn-sm float-right"><i class="fa fa-plus"></i> New PO</a>
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
                            <th>REf</th>
                            <th>Supplier</th>
                            <th>Status</th>
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
window.load_purchases = function (){

    $('#purchases-field tbody').html('<tr><td colspan="6">Loading data.</td></tr>')
    $.ajax({
        url:'<?php echo base_url('purchases/load_list') ?>',
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
                    tr.append('<td>'+resp[k].ref_no+'</td>')
                    tr.append('<td>'+resp[k].sname+'</td>')
                    if(resp[k].status == 2){
                        tr.append('<td><div class="badge badge-success">Received<div></td>')
                    }else if(resp[k].approved == 1){
                        tr.append('<td><div class="badge badge-warning">Checked<div></td>')
                    }else if(resp[k].approved == 2){
                        tr.append('<td><div class="badge badge-info">Approved<div></td>')
                    }else{
                        tr.append('<td><div class="badge badge-primary">Pending<div></td>')
                    }
                    tr.append('<td><center><button type="button" class="btn btn-sm btn-outline-primary btn-rounded waves-effec view_purchase" data-id="'+resp[k].id+'"><i class="fa fa-eye"></i></button><button type="button" class="btn btn-sm btn-outline-danger btn-rounded waves-effec remove_purchase" data-id="'+resp[k].id+'"><i class="fa fa-trash"></i></button></center></td>')

                $('#purchases-field tbody').append(tr)

                    
                })

                

                }
            }
        },
        complete:()=>{
            $('.edit_purchase').each(function(e){
                $(this).click(function(){
                    location.replace('<?php echo base_url() ?>purchases/manage/'+$(this).attr('data-id'))
                })
            })
            $('.view_purchase').each(function(e){
                $(this).click(function(){
                    location.href = '<?php echo base_url() ?>purchases/view/'+$(this).attr('data-id')
                })
            })
            $('.remove_purchase').each(function(e){
                $(this).click(function(){
                    delete_data('Are you sure to delete this data?','remove_purchase',[$(this).attr('data-id')])
                })
            })
            $('#purchases-field').dataTable()
            
        }
    })
}
function remove_purchase($id=''){
    $.ajax({
        url:'<?php echo base_url() ?>purchases/remove_po',
        method:'POST',
        data:{id:$id},
        error:err=>{
            console.log(err)
            Dtoast('An error occured.','error')
        },
        success:function(resp){
            if(resp == 1){
             Dtoast('Data successfully deleted.','success')
             load_purchases()
             $('.modal').modal('hide')
            }
        }
    })
}
$(document).ready(function(){
    if('<?php echo $this->session->flashdata('save_purchases') ?>' == 1)
            Dtoast("Data successfully added",'success');
    if('<?php echo $this->session->flashdata('save_purchases') ?>' == 2)
            Dtoast("Data successfully updated",'success');
    load_purchases();
})
</script>
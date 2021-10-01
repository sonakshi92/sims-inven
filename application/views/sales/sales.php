<div class="col-md-12 mt1 mb1">
    <div class="row">
        <a href="<?php echo base_url('sales/manage') ?>" class="btn btn-info btn-sm float-right"><i class="fa fa-plus"></i> New Sale</a>
    </div>
</div>

<div class="col-md-12 mt1 mb1">
    <div class="row">

        <div class="card col-md-12">
            <div class="card-body">

                <table class="table table-bordered table-striped" width="100%" id="sales-fieldd">

                    <colgroup>
                        <col width="5%">
                        <col width="15%">
                        <col width="20%">
                        <col width="20%">
                        <col width="20%">
                        <col width="20%">
                    </colgroup>
                    
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>REf</th>
                            <th>DR. No.</th>
                            <th>Customer</th>
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
window.load_sales = function (){

    $('#sales-fieldd tbody').html('<tr><td colspan="5">Loading data.</td></tr>')
    $.ajax({
        url:'<?php echo base_url('sales/load_list') ?>',
        method:'POST',
        data:{},
        error:err=>console.log(err),
        success:function(resp){
            if(resp){
                if(typeof resp != undefined){
                $('#sales-fieldd tbody').html('')
                resp = JSON.parse(resp)
                var i = 0;
                Object.keys(resp).map(k=>{
                    i++;
                    var tr = $('<tr>')
                    tr.append('<td class="text-center">'+i+'</td>')
                    tr.append('<td>'+resp[k].date_created+'</td>')
                    tr.append('<td>'+resp[k].ref_no+'</td>')
                    tr.append('<td>'+resp[k].dr_no+'</td>')
                    tr.append('<td>'+resp[k].cname+'</td>')
                    tr.append('<td><center><button type="button" class="btn btn-sm btn-outline-primary btn-rounded waves-effec view_sales" data-id="'+resp[k].id+'"><i class="fa fa-eye"></i></button></button><button type="button" class="btn btn-sm btn-outline-danger btn-rounded waves-effec remove_sales" data-id="'+resp[k].id+'"><i class="fa fa-trash"></i></button></center></td>')

                $('#sales-fieldd tbody').append(tr)

                    
                })
                if(Object.keys(resp).length <= 0)
                $('#sales-fieldd tbody').html('')

                

                }
            }
        },
        complete:()=>{
            $('.view_sales').each(function(e){
                $(this).click(function(){
                    location.href = '<?php echo base_url() ?>sales/view/'+$(this).attr('data-id');
                })
            })
            $('.remove_sales').each(function(e){
                $(this).click(function(){
                    delete_data('Are you sure to delete this data?','remove_sales',[$(this).attr('data-id')])
                })
            })
            $('#sales-fieldd').dataTable()
            
        }
    })
}
function remove_sales($id=''){
    $.ajax({
        url:'<?php echo base_url() ?>sales/remove_sales',
        method:'POST',
        data:{id:$id},
        error:err=>{
            console.log(err)
            Dtoast('An error occured.','error')
        },
        success:function(resp){
            if(resp == 1){
             Dtoast('Data successfully deleted.','success')
             load_sales()
             $('.modal').modal('hide')
            }
        }
    })
}
$(document).ready(function(){
    if('<?php echo $this->session->flashdata('save_sales') ?>' == 1)
            Dtoast("Data successfully added",'success');
    if('<?php echo $this->session->flashdata('save_sales') ?>' == 2)
            Dtoast("Data successfully updated",'success');
    if('<?php echo $this->session->flashdata('save_sales_del') ?>' == 1)
            Dtoast("Data successfully deleted",'success');
    load_sales();
})
</script>
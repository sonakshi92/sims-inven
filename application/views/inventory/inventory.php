<div class="container-fluid">
<div class="col-md-12">
<div class="card">
<div class="card-header">
     <div class="card-title">
        <h4>Inventory</h4>
     </div>
</div>
    <div class="card-body">
    <div class="row">
        <div class="col-md-12">
        <button class="btn btn-primary btn-sm" type="button" id="print_inventory"><i class="fa fa-print"></i> Print</button>
        </div>
    </div>
    <hr>
        <table class="table table-bordered" id="inv-tbl">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">SKU</th>
                    <th class="text-center">Product</th>
                    <th class="text-center">Unit</th>
                    <th class="text-center">In Stock</th>
                </tr>
            </thead>

            <tbody></tbody>
        </table>
    </div>
</div>
</div>
</div>
<script>
window.load_invent = function(){
    start_load()
    $('#inv-tbl tbody').html('')
    $.ajax({
        url:'<?php echo base_url('inventory/load_inventory') ?>',
        error:err=>console.log(err),
        success:function(resp){
            if(resp){
                resp = JSON.parse(resp)
                if(Object.keys(resp).length <= 0 ){
                 $('#inv-tbl tbody').html('<tr><td colspan="5">No data...</td></tr>')
                }else{
                    var i = 1;
                    Object.keys(resp).map(k=>{
                        var tr = $("<tr></tr>")
                        tr.append('<td class="text-center">'+(i++)+'</td>')
                        tr.append('<td>'+resp[k].sku+'</td>')
                        tr.append('<td>'+resp[k].name+'</td>')
                        tr.append('<td class="text-center">'+resp[k].unit+'</td>')
                        tr.append('<td class="text-right">'+resp[k].inn+'</td>')
                        console.log(tr.html())
                    $('#inv-tbl tbody').append(tr)

                    })
                    
                }
            }
        },
        complete:function(){
            $('#inv-tbl').dataTable()
            end_load()
        }
    })
}
$(document).ready(function(){
    load_invent()
})
$('#print_inventory').click(function(){
        var nw = window.open("<?php echo base_url('inventory/print_inventory/') ?>","_blank","height=600,width=800")
        // setTimeout(function(){
        //     nw.print()
        //     setTimeout(function(){
        //         // nw.close()
        //     },1500)
        // },1000)
    })
</script>
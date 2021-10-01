<script type="text/javascript" src="<?php echo base_url().'assets/' ?>js/jquery-3.4.1.min.js"></script>
<style>
.text-center{
    text-align:-webkit-center;
}
.text-right{
    text-align:right;
}
.bborder{
    border-bottom:1px solid black;
}
p{
    margin:3px
}
.table{
    width:100%;
    border-collapse:collapse
}
.table2{
    width:100%;
    border-collapse:collapse
}
.wborder{
    border:1px solid black;
}
.table tr,.table td,.table th{
      border: 1px solid black  
    }
    .table2 tr,.table2 td,.table2 th{
      vertical-align:top
    }
</style>
<p class="text-center" style="font-size:15px"><b>RICA AUTO AND MOTORCYCLE PARTS TRADING</b></p>
<p class="text-center" style=""><b>Purok Mars Araneta St. Singcang-Airport, Bacolod City</b></p>
<p class="text-center" style=""><b>Tel. #: (034) 433-0617 / (034) 435-3015 </b></p>
<br>
<p class="text-center" style="font-size:15px"><b>Inventory List as of <?php echo date("F, Y") ?></b></p>
<br>
<table width="100%" class="table2" id="inv-tbl">
    <thead>
        <tr>
            <th class="text-center wborder">#</th>
            <th class="text-center wborder">SKU</th>
            <th class="text-center wborder">Product</th>
            <th class="text-center wborder">Unit</th>
            <th class="text-center wborder">In Stock</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
<script>
window.load_invent = function(){
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
                        tr.append('<td class="text-center wborder">'+(i++)+'</td>')
                        tr.append('<td class="wborder">'+resp[k].sku+'</td>')
                        tr.append('<td class="wborder">'+resp[k].name+'</td>')
                        tr.append('<td class="text-center wborder">'+resp[k].unit+'</td>')
                        tr.append('<td class="text-right wborder">'+resp[k].inn+'</td>')
                        console.log(tr.html())
                    $('#inv-tbl tbody').append(tr)

                    })
                    
                }
            }
        },
        complete:function(){
            window.print()
            setTimeout(function(){
                window.close()
            },750)
        }
    })
}
    load_invent()
</script>
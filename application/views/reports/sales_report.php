<script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.table2excel.min.js' ?>"></script>
<?php 
$from = empty($from)? date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y'))) : $from;
$to = empty($to)? date('Y-m-d', mktime(0, 0, 0, date('m')+1, 0, date('Y'))) : $to;
?>
<div class="conteiner-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
               
                <div class="row">
                    <table class="table table-bordered" id="report">
                        <thead>
                            <tr>
                                <th class="text-center">Date</th>
                                <th class="text-center">Customer</th>
                                <th class="text-center">DR No</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Unit</th>
                                <th class="text-center">Cost</th>
                                <th class="text-center">Total Cost</th>
                                <th class="text-center">Sales</th>
                                <th class="text-center">Total Sales</th>
                                <th class="text-center">Profit</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<noscript>
<style>
table{
    width:100%;
    border-collapse:collapse
}
td,th{
    padding:3px 5px !important
}

tr,td,th{
    border:1px solid black
}
.text-center{
    text-align:-webkit-center;
}
.text-right{
    text-align:right;
}
p{
    margin:unset
}
.print_hidden{
    display:none
}
</style>
<p class="text-center" style="font-size:15px"><b>RICA AUTO AND MOTORCYCLE PARTS TRADING</b></p>
<p class="text-center" style=""><b>Purok Mars Araneta St. Singcang-Airport, Bacolod City</b></p>
<!-- <p class="text-center" style=""><b>Tel. No. </b></p> -->
<br>
<p class="text-center" style="font-size:15px"><b><?php echo $sname[$salesman_id] ?></b><span><?php echo " Sales (".date("Y-m-d",strtotime($from)) .' - '.date("Y-m-d",strtotime($to)).")" ?></b></p>
<br>
</noscript>
<style>
td,th{
    padding:3px 5px !important
}
.onexport{
    display:none
}
</style>
<script>
window.load_list = function(){
    start_load()
    $.ajax({
        url:"<?php echo base_url("reports/sales_rep_list") ?>",
        method:"POST",
        error:err=>{
            console.log(err)
            Dtoast("An error occured","error")
            end_load()
        },
        success:function(resp){
            if(typeof resp !=undefined){
                resp = JSON.parse(resp)
                if(Object.keys(resp).length <= 0){
                    $('#report tbody').html("<tr><td class='text-center' colspan='10'>No Result</td></tr>")
                }else{
                    var cost = 0;
                    var tcost = 0;
                    var sales = 0;
                    var tsales = 0;
                    var profit = 0;
                    Object.keys(resp).map(k=>{
                        // console.log(resp[k].dtails)
                    Object.keys(resp[k].sales).map(i=>{
                        cost += parseFloat(resp[k].sales[i].aprice);
                        tcost += parseFloat(resp[k].sales[i].t_aprice);
                        sales += parseFloat(resp[k].sales[i].sprice);
                        tsales += parseFloat(resp[k].sales[i].t_sprice);
                        profit += parseFloat(resp[k].sales[i].profit);
                        var tr = $('<tr></tr>')
                        tr.append('<td class="text-center">'+resp[k].sales[i].date_created+'</td>')
                        tr.append('<td>'+resp[k].sales[i].name+'</td>')
                        tr.append('<td>'+resp[k].sales[i].dr_no+'</td>')
                        tr.append('<td>'+resp[k].sales[i].pname+'</td>')
                        tr.append('<td>'+resp[k].sales[i].qty+'</td>')
                        tr.append('<td>'+resp[k].sales[i].unit+'</td>')
                        tr.append('<td class="text-right">'+(parseFloat(resp[k].sales[i].aprice).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))+'</td>')
                        tr.append('<td class="text-right">'+(parseFloat(resp[k].sales[i].t_aprice).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))+'</td>')
                        tr.append('<td class="text-right">'+(parseFloat(resp[k].sales[i].sprice).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))+'</td>')
                        tr.append('<td class="text-right">'+(parseFloat(resp[k].sales[i].t_sprice).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))+'</td>')
                        tr.append('<td class="text-right">'+(parseFloat(resp[k].sales[i].profit).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))+'</td>')
                        $('#report tbody').append(tr)
                        
                    })
                    })
                        var tr = $('<tr></tr>')
                        var tf = $('<tfoot></tfoot>')
                    tr.append("<th colspan='6' class='text-right'>Total</th>")
                    tr.append('<td class="text-right">'+(parseFloat(cost).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))+'</td>')
                        tr.append('<td class="text-right">'+(parseFloat(tcost).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))+'</td>')
                        tr.append('<td class="text-right">'+(parseFloat(sales).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))+'</td>')
                        tr.append('<td class="text-right">'+(parseFloat(tsales).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))+'</td>')
                        tr.append('<td class="text-right">'+(parseFloat(profit).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))+'</td>')
                        $('#report tbody').append(tr)
                    tf.append(tr)
                    $('#report').append(tf)
                    // $('#tsales').html(parseFloat(amount).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))
                    // $('#treceivable').html(parseFloat(receivable).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))
                }
            }
        },
        complete:function(){
            end_load()
        }
    })
}
$(document).ready(function(){
load_list()
})
</script>
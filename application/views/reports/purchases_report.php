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
                <div class="col-md-5">
                    <h4><b>Total Purchases: <span id="tsales">0.00</span></b></h4>
                </div>
                <div class="col-md-5">
                    <h4><b>Total Payable: <span id="tpayable">0.00</span></b></h4>
                </div>
                </div>
                <hr>
                <div class="row">
                    <table class="table table-bordered" id="report">
                        <thead>
                            <tr class='onexport'>
                                <th colspan="7">Purchases Report</th>
                            </tr>
                            <tr class='onexport'>
                                <th colspan="7">From : <?php echo date("Y-m-d",strtotime($from)) ?></th>
                            </tr>
                            <tr class='onexport'>
                                <th colspan="7">To : <?php echo date("Y-m-d",strtotime($to)) ?></th>
                            </tr>
                            <tr class='onexport'>
                                <th colspan="7"></th>
                            </tr>
                            <tr>
                                <th class="text-center">Date</th>
                                <th class="text-center">Reference No.</th>
                                <th class="text-center">Customer</th>
                                <th class="text-center">Payment Mode</th>
                                <th class="text-center">Total Amount</th>
                                <th class="text-center">Total Amount Paid</th>
                                <th class="text-center">Total Amount Payable</th>
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
td,th{
    padding:3px 5px !important
}

tr.td.th{
    border:1px solid black
}
.text-center{
    text-align:-webkit-center;
}
.text-right{
    text-align:right;
}
</style>
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
        url:"<?php echo base_url("reports/purchases_rep_list") ?>",
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
                    $('#report tbody').html("<tr><td class='text-center' colspan='7'>No Result</td></tr>")
                }else{
                    var paid = 0;
                    var amount = 0;
                    var payable = 0;
                    Object.keys(resp).map(k=>{
                        paid += parseFloat(resp[k].paid);
                        amount += parseFloat(resp[k].total_amount);
                        var ramount = parseFloat(resp[k].total_amount) - parseFloat(resp[k].paid);
                        payable += parseFloat(ramount);
                        var tr = $('<tr></tr>')
                        tr.append('<td>'+resp[k].date_created+'</td>')
                        tr.append('<td>'+resp[k].po_ref+'</td>')
                        tr.append('<td>'+resp[k].sname+'</td>')
                        tr.append('<td>'+resp[k].payment_mode+'</td>')
                        tr.append('<td class="text-right">'+(parseFloat(resp[k].total_amount).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))+'</td>')
                        tr.append('<td class="text-right">'+(parseFloat(resp[k].paid).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))+'</td>')
                        tr.append('<td class="text-right">'+(parseFloat(ramount).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))+'</td>')
                        $('#report tbody').append(tr)
                        
                    })
                        var tr = $('<tr></tr>')
                        var tf = $('<tfoot></tfoot>')
                    tr.append("<th colspan='4' class='text-right'>Total</th>")
                    tr.append("<th class='text-right'>"+parseFloat(amount).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2})+"</th>")
                    tr.append("<th class='text-right'>"+parseFloat(paid).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2})+"</th>")
                    tr.append("<th class='text-right'>"+parseFloat(payable).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2})+"</th>")
                    tf.append(tr)
                    $('#report').append(tf)
                    $('#tsales').html(parseFloat(amount).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))
                    $('#tpayable').html(parseFloat(payable).toLocaleString('en-US',{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))
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
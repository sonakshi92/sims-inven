<?php

$payment = $this->db->query("SELECT SUM(amount) as total FROM payments where sales_id in (SELECT id from sales where date(date_created) = '".date("Y-m-d")."' ) ");
$tst = $payment->num_rows() > 0 ? number_format($payment->row()->total,2) : "0.00";
$payment = $this->db->query("SELECT SUM(amount) as total FROM payments where sales_id in (SELECT id from sales where status =1)");
$sales = $this->db->query("SELECT SUM(total_amount) as total FROM sales where status = 1");
$p = $payment->num_rows() > 0 ? $payment->row()->total : 0;
$s = $sales->num_rows() > 0 ? $sales->row()->total : 0;
$tr = number_format($s - $p,2);

$po = $this->db->query("SELECT SUM(amount) as total FROM po_payments where po_id in (SELECT id from purchases where status = 2)");
$receiving = $this->db->query("SELECT SUM(total_amount) as total FROM receiving where po_id in (SELECT id from purchases where status =2)");
$p2 = $po->num_rows() > 0 ? $po->row()->total : 0;
$s2 = $receiving->num_rows() > 0 ? $receiving->row()->total : 0;
$tr2 = number_format($s2 - $p2,2);

$cdate_from = date('Y-m').'-01';
$dnumber = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
$cdate_to = date('Y-m').'-'.$dnumber;

for($i = 1 ; $i <= $dnumber ; $i++){
  $i = sprintf("%'.02d", $i);
  $days[] = date('Y').'-'.date('m').'-'.$i;
  $sday[date('Y').'-'.date('m').'-'.$i] = 0;
  $i = number_format($i);
}
$sales = $this->db->query("SELECT * FROM sales where date(date_created) between '$cdate_from' and '$cdate_to' and status = 1")->result_array();
foreach($sales as $row){
$sday[date('Y-m-d',strtotime($row['date_created']))] += ($row['total_amount']);
}

?>
<style>
 #lineChart{
  height: 25vw !important;
 }
</style>
<div class="container-fluid">
  <section class="mt-md-4 pt-md-2 mb-5 pb-4">
  <div class="row">
    <div class="col-xl-4 col-md-6 mb-xl-0 mb-4">
      <div class="card card-cascade cascading-admin-card">
        <div class="admin-up">
          <span><i class="far fa-money-bill-alt primary-color mr-3 z-depth-2"></i></span>
          <div class="data">
            <p class="text-uppercase">total sales today (paid)</p>
            <h4 class="font-weight-bold dark-grey-text">&#8369; <?php echo $tst ?></h4>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-xl-0 mb-4">
      <div class="card card-cascade cascading-admin-card">
        <div class="admin-up">
          <span><i class="fa fa-hand-holding warning-color mr-3 z-depth-2"></i></span>
          <div class="data">
            <p class="text-uppercase">total Receivable (sales)</p>
            <h4 class="font-weight-bold dark-grey-text">&#8369; <?php echo $tr ?></h4>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-xl-0 mb-4">
      <div class="card card-cascade cascading-admin-card">
        <div class="admin-up">
          <span><i class="fa fa-money-bill-wave info-color mr-3 z-depth-2"></i></span>
          <div class="data">
            <p class="text-uppercase">total Payable (purchases)</p>
            <h4 class="font-weight-bold dark-grey-text">&#8369; <?php echo $tr2 ?></h4>
          </div>
        </div>
      </div>
    </div>

   

  </div>

  </section>
    <div class="row">
  <div class="col-lg-12">
        <div class="card">
          <div class="card-body" id="chartField">
            <div class="row justify-content-end">
              <div class="col-md-4">
                <input type="month" id="month_of" class="form-control form-control-sm" value="<?php echo date("Y-m") ?>"/>
              </div>
            </div>
            <canvas id="lineChart"></canvas>
          </div>
        </div>
    </div>
  </div>

  
</div>

<script>
  var ctxL = document.getElementById("lineChart").getContext('2d');
var myLineChart = new Chart(ctxL, {
type: 'line',
data: {
labels: ["<?php echo implode('","',$days) ?>"],
datasets: [{
label: "Daily Sales for the month of <?php echo date("F, Y",strtotime($cdate_from)) ?>",
data: [<?php echo implode(',',$sday) ?>],
backgroundColor: [
'#007bffa3',
],
borderColor: [
'#007bff',
],
borderWidth: 2
}
]
},
options: {
responsive: true,
scales:{
    yAxes:[{
      ticks:{
        callback:function(value){
          console.log(value)
          return parseFloat(value).toLocaleString('en-US',{style:'decimal',maximumFractionDigit:2})
        }
      }
    }]
  }
}
});

$('#month_of').change(function(){
  start_load();
  $('#lineChart').remove()
  $('#chartField').append('<canvas id="lineChart"></canvas>')
  $.ajax({
    url:'<?php echo base_url() ?>admin/stats/'+$(this).val(),
    error:err=>{
      console.log(err)
      Dtoast("An error occured.",'danger')
    },
    success:function(resp){
      if(resp && (typeof JSON.parse(resp) != undefined) ){
        resp = JSON.parse(resp)
        console.log(Object.keys(resp.sday).map(k=>resp.sday[k]))
        var ctxL = document.getElementById("lineChart").getContext('2d');

        var dynamicLineChart = new Chart(ctxL, {
        type: 'line',
        data: {
        labels: resp.days,
        datasets: [{
        label: "Daily Sales for the month of <?php echo date("F, Y",strtotime($cdate_from)) ?>",
        data: Object.keys(resp.sday).map(k=>resp.sday[k]),
        backgroundColor: [
        '#007bffa3',
        ],
        borderColor: [
        '#007bff',
        ],
        borderWidth: 2
        }
        ]
        },
        options: {
        responsive: true,
        scales:{
          yAxes:[{
            ticks:{
              callback:function(value){
                console.log(value)
                return parseFloat(value).toLocaleString('en-US',{style:'decimal',maximumFractionDigit:2})
              }
            }
          }]
        }
        }
        });


      }

      end_load();
    }
  })
})
</script>
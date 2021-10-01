<!-- Sidebar -->
<style>
#sidebar-nav{
  background-image: url('<?php echo base_url('assets/images/warehouse-portrait.jpg') ?>');
  background-repeat: no-repeat;
  background-size: cover;
  padding: 0 !important;
}
#sidebar-list a {
    min-height: 7vh;
    /*background: radial-gradient(circle, rgb(0 0 0 / 70%) 0%, rgb(0 0 0 / 34%) 48%, rgba(0,0,0,0.8423125877808989) 100%);*/
    background: linear-gradient(90deg, rgba(0,0,0,0.9630991046348315) 0%, rgba(0,0,0,0.8591665203651686) 14%, rgba(0,0,0,0.7608519136235955) 25%, rgba(0,0,0,0.7468069698033708) 38%, rgba(0,0,0,0.6288294417134832) 57%, rgba(0,0,0,0.8142227001404494) 75%, rgba(0,0,0,0.8423125877808989) 91%, rgba(0,0,0,1) 100%);
    color: white;
}
#sidebar-list a:hover{
    background: #ffffff85  !important;
    color: black !important;
    font-weight: 600;
}
#sidebar-list a.active{
    /*color: #fff;
    background:unset;
    background-color: #007bff;
    border-color: #007bff;*/
    background: #ffffff85  !important;
    color: black !important;
    font-weight: 600;
    border-color: unset;
    border-radius: unset;
}
</style>
  


    <div class="sidebar-fixed position-fixed" id="sidebar-nav">

      <a class="logo-wrapper waves-effect">
        <img src="<?php echo base_url('assets/images/sample-company-logo.png') ?>" class="" alt=""  style="width:100%;max-height : 15vh !important">
      </a>

      <div class="list-group list-group-flush" id="sidebar-list">
        <a href="<?php echo base_url('admin') ?>" class="list-group-item list-group-item-action waves-effect ">
          <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
        </a>
          <a href="<?php echo base_url('supplier') ?>" class="list-group-item list-group-item-action waves-effect ">
          <i class="fas fa-truck-loading mr-3"></i>Supplier</a>
          <a href="<?php echo base_url('customer') ?>" class="list-group-item list-group-item-action waves-effect ">
          <i class="fas fa-user-friends mr-3"></i>Customer</a>
          <a href="<?php echo base_url('product') ?>" class="list-group-item list-group-item-action waves-effect ">
          <i class="fas fa-boxes mr-3"></i>Product</a>

          <a href="javascript:void(0)" class="list-group-item list-group-item-action waves-effect " data-toggle="collapse" data-target="#sales_collapse" aria-expanded="false" aria-controls="sales_collapse" data-colid="sales_collapse">
          <i class="fas fa-coins mr-3"></i>Sales <span class="float-right"><i class="fa fa-angle-right"></i></span>
          </a>
          <div class="collapse" id="sales_collapse">
            <a href="<?php echo base_url('sales/index') ?>" class="list-group-item list-group-item-action waves-effect ">
            <i></i>List</a>
          </div>

          
          <a href="javascript:void(0)" class="list-group-item list-group-item-action waves-effect " data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" data-colid="collapseExample">
          <i class="fas fa-money-bill-alt mr-3"></i>Purchases <span class="float-right"><i class="fa fa-angle-right"></i></span></a>
          <div class="collapse" id="collapseExample">
          <a href="<?php echo base_url('purchases/index') ?>" class="list-group-item list-group-item-action waves-effect ">
          <i></i>Purchase Order</a>
          <a href="<?php echo base_url('purchases/receiving') ?>" class="list-group-item list-group-item-action waves-effect ">
          <i></i>Receiving</a>
          </div>

          <a href="<?php echo base_url('inventory') ?>" class="list-group-item list-group-item-action waves-effect ">
          <i class="fas fa-list mr-3"></i>Inventory</a>

          <a href="javascript:void(0)" class="list-group-item list-group-item-action waves-effect " data-toggle="collapse" data-target="#reports_collapse" aria-expanded="false" aria-controls="reports_collapse" data-colid="reports_collapse">
          <i class="fas fa-table mr-3"></i>Reports <span class="float-right"><i class="fa fa-angle-right"></i></span>
          </a>
          <div class="collapse" id="reports_collapse">
            <a href="<?php echo base_url('reports/sales_report') ?>" class="list-group-item list-group-item-action waves-effect ">
            <i></i>Sales</a>
            <a href="<?php echo base_url('reports/purchases_report') ?>" class="list-group-item list-group-item-action waves-effect ">
            <i></i>Purchases</a>
          </div>
          <a href="<?php echo base_url('salesman') ?>" class="list-group-item list-group-item-action waves-effect ">
          <i class="fas fa-user-tie mr-3"></i>Salesman</a>
          <?php if($_SESSION['login_type'] == 1): ?>
          <a href="javascript:void(0)" class="list-group-item list-group-item-action waves-effect " data-toggle="collapse" data-target="#cogs_collapse" aria-expanded="false" aria-controls="cogs_collapse" data-colid="cogs_collapse">
          <i class="fas fa-cogs mr-3"></i>System <span class="float-right"><i class="fa fa-angle-right"></i></span>
          </a>
          <div class="collapse" id="cogs_collapse">
            <a href="<?php echo base_url('cogs/users') ?>" class="list-group-item list-group-item-action waves-effect ">
            <i></i>Users</a>
          </div>

          <?php endif; ?>

      </div>

    </div>
    
    <style>
    #sidebar-list a{
      min-height:7vh
    }
    #sidebar-list{
      height: calc(100% - 13rem);
      overflow:auto
    }
    </style>
    <!-- Sidebar -->
    <script>
    $('.collapse').each(function(){
      $(this).on('show.bs.collapse',function(){
        var _this = $(this).attr('id')
       $('[aria-controls="'+_this+'"]').addClass('rot-ang')
        // $('a[fata-colid="'+_this+'"]').
      })
      $(this).on('hide.bs.collapse',function(){
        var _this = $(this).attr('id')
       $('[aria-controls="'+_this+'"]').removeClass('rot-ang')
        // $('a[fata-colid="'+_this+'"]').
      })
    })
    console.log(location.href)
    $('#sidebar-list .list-group-item').each(function(){
      if($(this).attr('href') == location.href || $(this).attr('href').toString().includes(location.href) ){
        $(this).addClass('active')
        if($(this).parent().hasClass('collapse') == true)
          $('[data-target="#'+$(this).parent().attr('id')+'"]').trigger('click')
      }
    })
    </script>
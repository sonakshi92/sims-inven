<style>
  #top-nav{
    background:#0a0a1c !important;
  }
</style>
    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-primary white scrolling-navbar" id="top-nav">
      <div class="container-fluid">

        <!-- Brand -->
        <a class="navbar-brand waves-effect" href="./admin">
          <strong class="white-text">Sales and Inventory Management System</strong>
        </a>

        <!-- Collapse -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Links -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <div class="navbar-nav mr-auto"></div>
          <!-- Left -->
          <!-- <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link waves-effect" href="#">Home
                <span class="sr-only">(current)</span>
              </a>
            </li>
          </ul> -->

          <!-- Right -->
          <ul class="navbar-nav nav-flex-icons">
            <li class="nav-item">
            <a class="nav-link dropdown-toggle white-text" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-user btn-ronded"></span> <?php echo ucwords($_SESSION['login_firstname'].' '.$_SESSION['login_lastname']) ?> </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
              <a class="dropdown-item" href="javascript:void(0)" id="manage_myaccount">
                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                My Account
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="<?php echo base_url('login/logout') ?>" >
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Logout
              </a>
            </div>
            </li>
          </ul>

        </div>

      </div>
    </nav>
    <script>
      $(document).ready(function(){
        $('#manage_myaccount').click(function(){
        frmModal("manage-users","Manage My Account","<?php echo base_url("cogs/manage_users/").$_SESSION['login_id'] ?>")

        })
      })
    </script>
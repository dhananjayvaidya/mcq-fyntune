<div class="bmd-layout-container bmd-drawer-f-l bmd-drawer-in" style="position:fixed">
  <header class="bmd-layout-header">
    <div class="navbar navbar-light bg-primary">
      <button class="navbar-toggler" type="button" aria-expanded="true" data-toggle="drawer" data-target="#dw-s1">
        <span class="sr-only">Toggle drawer</span>
        <i class="material-icons">menu</i>
      </button>
      <ul class="nav navbar-nav">
        <li class="nav-item"><a href='<?php echo base_url('admin/logout');?>' class='btn bg-white'>Logout</a></li>
      </ul>
    </div>
  </header>
  <div id="dw-s1" class="bmd-layout-drawer bg-faded" style="position:fixed">
    <header>
      <a class="navbar-brand">FynTune</a>
    </header>
    <ul class="list-group">
      <a class="list-group-item" href="<?php echo base_url('admin');?>">dashboard</a>
      <a class="list-group-item" href='<?php echo base_url("admin/guests/list");?>'>Guests</a>
      
    </ul>
  </div>
  <main class="bmd-layout-content">
    <div class="container-fluid" style="padding-top:20px;padding-bottom:20px;">
     
    
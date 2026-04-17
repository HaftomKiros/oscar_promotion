<?php
    $CI =& get_instance();
    $CI->load->model('Web_settings');
    $CI->load->model('Reports');
    $CI->load->model('Users');
    $Web_settings = $CI->Web_settings->retrieve_setting_editdata();
    $users = $CI->Users->profile_edit_data();
    $out_of_stock = $CI->Reports->out_of_stock_count();
    $out_of_date  = $CI->Reports->out_of_date_count();
?>
<!-- Admin header end -->
<style type="text/css">
   .navbar .btn-success{
        margin: 13px 2px;
   }
   /* Sidebar branding */
   .skin-blue .main-header .logo {
       background: linear-gradient(135deg, #1a3a5c 0%, #2d5278 100%) !important;
   }
   .skin-blue .main-header .navbar {
       background: linear-gradient(135deg, #1a3a5c 0%, #2d5278 100%) !important;
   }
   .skin-blue .main-sidebar {
       background: #0f2438 !important;
   }
   .skin-blue .sidebar-menu > li.active > a,
   .skin-blue .sidebar-menu > li:hover > a {
       border-left-color: #f5a623 !important;
       background: rgba(245, 166, 35, 0.1) !important;
   }
   .skin-blue .sidebar-menu > li > a {
       color: #c8d6e5 !important;
   }
   .skin-blue .sidebar-menu > li.active > a {
       color: #f5a623 !important;
   }
   .skin-blue .main-header .logo:hover {
       background: #2d5278 !important;
   }
   .skin-blue .main-header li.user-header {
       background: linear-gradient(135deg, #1a3a5c 0%, #f5a623 100%) !important;
   }
</style>
<header class="main-header">
    <a href="<?php echo base_url()?>" class="logo"> <!-- Logo -->
        <span class="logo-mini">
            <!--<b>A</b>BD-->
            <img src="<?php if (isset($Web_settings[0]['favicon'])) {
               echo $Web_settings[0]['favicon']; }?>" alt="">
        </span>
        <span class="logo-lg">
            <!--<b>Admin</b>BD-->
            <img src="<?php if (isset($Web_settings[0]['logo'])) {
               echo $Web_settings[0]['logo']; }?>" alt="">
        </span>
    </a>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top text-center">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <!-- Sidebar toggle button-->
            <span class="sr-only"><?php echo display('toggle_navigation')?></span>

            <span class="pe-7s-keypad"></span>
        </a>

        <?php
          $urcolp = '0';
          if($this->uri->segment(2) =="gui_pos" ){
            $urcolp = "gui_pos";
          }
          if($this->uri->segment(2) =="pos_invoice" ){
            $urcolp = "pos_invoice";
          }

           if($this->uri->segment(2) != $urcolp ){
          if($this->permission1->method('manage_job','read')->access()){ ?>
           <a href="<?php echo base_url('Cjob/manage_job')?>" class="btn btn-success btn-outline" style=""><i class="fa fa-briefcase"></i> <?php echo display('manage_job') ?></a>
         <?php } ?>

        <?php
        if($this->permission1->method('manage_candidate','read')->access()){ ?>
           <a href="<?php echo base_url('Ccandidate/manage_candidate')?>" class="btn btn-success btn-outline" style=""><i class="fa fa-users"></i> <?php echo display('manage_candidate')?></a>
        <?php } ?>

        <?php
        if($this->permission1->method('manage_employer','read')->access()){ ?>
          <a href="<?php echo base_url('Cemployer/manage_employer')?>" class="btn btn-success btn-outline" style=""><i class="fa fa-building" aria-hidden="true"></i> <?php echo display('manage_employer')?></a>
        <?php } ?>

       

        <?php
        if($this->permission1->method('manage_educational_level','read')->access()){ ?>
          <a href="<?php echo base_url('Ceducational_level/manage_educational_level')?>" class="btn btn-success btn-outline" style=""><i class="fa fa-graduation-cap"></i> <?php echo display('manage_educational_level') ?></a>
        <?php } ?>

        <?php
        if($this->permission1->method('manage_emp_type','read')->access()){ ?>
          <a href="<?php echo base_url('Cemployment/manage_emp_type')?>" class="btn btn-success btn-outline" style=""><i class="fa fa-id-card-o"></i> <?php echo display('employment_type') ?></a>
        <?php } ?>

        <?php
        if($this->permission1->method('manage_sal_range','read')->access()){ ?>
          <a href="<?php echo base_url('Cemployment/manage_sal_range')?>" class="btn btn-success btn-outline" style=""><i class="fa fa-money"></i> <?php echo display('salary_range') ?></a>
        <?php } ?>


        <?php } ?>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <!-- ── Notification Bell ── -->
                <li class="dropdown" id="notif-bell-li" style="position:relative;">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="notif-bell-btn" style="position:relative;padding:15px 12px;">
                        <i class="fa fa-bell-o" style="font-size:18px;"></i>
                        <span id="notif-badge" style="display:none;position:absolute;top:8px;right:4px;background:#e74c3c;color:#fff;border-radius:50%;width:18px;height:18px;font-size:10px;font-weight:700;line-height:18px;text-align:center;">0</span>
                    </a>
                    <ul class="dropdown-menu" id="notif-dropdown" style="width:360px;max-height:420px;overflow-y:auto;right:0;left:auto;padding:0;">
                        <li style="background:#1a3a5c;padding:10px 16px;border-radius:4px 4px 0 0;">
                            <span style="color:#fff;font-weight:700;font-size:13px;">📋 Pending Registrations</span>
                            <span id="notif-count-label" style="color:#f5a623;font-size:12px;margin-left:6px;"></span>
                        </li>
                        <li id="notif-list-body">
                            <div style="padding:20px;text-align:center;color:#9ca3af;font-size:13px;">No pending registrations</div>
                        </li>
                    </ul>
                </li>

                <!-- settings -->
                <li class="dropdown dropdown-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="pe-7s-settings"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url('Admin_dashboard/edit_profile')?>"><i class="pe-7s-users"></i><?php echo display('user_profile') ?></a></li>
                        <li><a href="<?php echo base_url('Admin_dashboard/change_password_form')?>"><i class="pe-7s-settings"></i><?php echo display('change_password') ?></a></li>
                        <li><a href="<?php echo base_url('Admin_dashboard/logout')?>"><i class="pe-7s-key"></i><?php echo display('logout') ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<aside class="main-sidebar">
    <!-- sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel text-center">
            <div class="image">
                <img src="<?php echo $users[0]['logo']?>" class="img-circle" alt="User Image">
            </div>
            <div class="info">
                <p><?php echo $this->session->userdata('user_name')?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> <?php echo display('online') ?></a>
            </div>
        </div>
        <!-- sidebar menu -->
        <ul class="sidebar-menu">

            <li class="<?php if ($this->uri->segment('1') == ("")) { echo "active";}else{ echo " ";}?>">
                <a href="<?php echo base_url()?>"><i class="ti-dashboard"></i> <span><?php echo display('dashboard') ?></span>
                    <span class="pull-right-container">
                        <span class="label label-success pull-right"></span>
                    </span>
                </a>
            </li>
           
             <!-- jobs menu start -->
              <?php
             if($this->permission1->module('add_job')->access() || $this->permission1->module('manage_job')->access()) { ?>
                <li class="treeview <?php if ($this->uri->segment('1') == ("Cjob")) { echo "active";}else{ echo " ";}?>">
                    <a href="#">
                        <i class="fa fa-briefcase"></i><span><?php echo display('job') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php
                          if($this->permission1->method('add_job','create')->access()) { ?>
                            <li  class="treeview <?php if ($this->uri->segment('1') == ("Cjob") && $this->uri->segment('2') == ("")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Cjob') ?>"><?php echo display('add_job') ?></a></li>
                        <?php } ?>

                        <?php
                          if($this->permission1->method('manage_job','read')->access() || $this->permission1->method('manage_job','update')->access() || $this->permission1->method('manage_job','delete')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('2') == ("manage_job")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Cjob/manage_job')?>"><?php echo display('manage_job') ?></a></li>
                        <?php } ?>

                        
                    </ul>
                </li>
            <?php } ?>

              <!-- jobs menu end-->

            <!-- candidate -->
              <?php
             if($this->permission1->module('add_candidate')->access() || $this->permission1->module('manage_candidate')->access()) { ?>
                <li class="treeview <?php if ($this->uri->segment('1') == ("Ccandidate")) { echo "active";}else{ echo " ";}?>">
                    <a href="#">
                        <i class="ti-user"></i><span><?php echo display('candidate') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php
                          if($this->permission1->method('add_candidate','create')->access()) { ?>
                            <li  class="treeview <?php if ($this->uri->segment('1') == ("Ccandidate") && $this->uri->segment('2') == ("")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Ccandidate') ?>"><?php echo display('add_candidate') ?></a></li>
                        <?php } ?>

                        <?php
                          if($this->permission1->method('manage_candidate','read')->access() || $this->permission1->method('manage_candidate','update')->access() || $this->permission1->method('manage_candidate','delete')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('2') == ("manage_candidate")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Ccandidate/manage_candidate')?>"><?php echo display('manage_candidate') ?></a></li>
                        <?php } ?>

                        
                    </ul>
                </li>
            <?php } ?>
             <!-- end of candidate-->

             
              <!-- company menu start -->
               <?php
             if($this->permission1->module('add_empployer')->access() || $this->permission1->module('manage_employer')->access()) { ?>
                <li class="treeview <?php if ($this->uri->segment('1') == ("Cemployer")) { echo "active";}else{ echo " ";}?>">
                    <a href="#">
                        <i class="fa fa-building"></i><span><?php echo display('employer') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php
                          if($this->permission1->method('add_empployer','create')->access()) { ?>
                            <li  class="treeview <?php if ($this->uri->segment('1') == ("Cemployer") && $this->uri->segment('2') == ("")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Cemployer') ?>"><?php echo display('add_employer') ?></a></li>
                        <?php } ?>

                        <?php
                          if($this->permission1->method('manage_employer','read')->access() || $this->permission1->method('manage_employer','update')->access() || $this->permission1->method('manage_employer','delete')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('2') == ("manage_employer")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Cemployer/manage_employer')?>"><?php echo display('manage_employer') ?></a></li>
                        <?php } ?>

                        
                    </ul>
                </li>
            <?php } ?>

               <!-- company end menu -->

               <!-- educational level menu start-->
                 <?php
             if($this->permission1->module('add_educational_level')->access() || $this->permission1->module('manage_educational_level')->access()) { ?>
                <li class="treeview <?php if ($this->uri->segment('1') == ("Ceducational_level")) { echo "active";}else{ echo " ";}?>">
                    <a href="#">
                        <i class="fa fa-graduation-cap"></i><span><?php echo display('educational_level') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php
                          if($this->permission1->method('add_educational_level','create')->access()) { ?>
                            <li  class="treeview <?php if ($this->uri->segment('1') == ("Ceducational_level") && $this->uri->segment('2') == ("")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Ceducational_level') ?>"><?php echo display('add_educational_level') ?></a></li>
                        <?php } ?>

                        <?php
                          if($this->permission1->method('manage_educational_level','read')->access() || $this->permission1->method('manage_educational_level','update')->access() || $this->permission1->method('manage_educational_level','delete')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('2') == ("manage_educational_level")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Ceducational_level/manage_educational_level')?>"><?php echo display('manage_educational_level') ?></a></li>
                        <?php } ?>

                        
                    </ul>
                </li>
            <?php } ?>

                <!-- educational level menu end-->

                <!-- employment menu start-->
                  <?php
              if($this->permission1->module('add_emp_type')->access() || $this->permission1->module('manage_emp_type')->access() || $this->permission1->module('add_sal_range')->access() || $this->permission1->module('manage_sal_range')->access()) { ?>
                 <li class="treeview <?php if ($this->uri->segment('1') == ("Cemployment")) { echo "active";}else{ echo " ";}?>">
                     <a href="#">
                         <i class="fa fa-briefcase"></i><span><?php echo display('employment') ?></span>
                         <span class="pull-right-container">
                             <i class="fa fa-angle-left pull-right"></i>
                         </span>
                     </a>
                     <ul class="treeview-menu">
                         <?php
                           if($this->permission1->method('add_emp_type','create')->access() || $this->permission1->method('manage_emp_type','read')->access() || $this->permission1->method('manage_emp_type','update')->access() || $this->permission1->method('manage_emp_type','delete')->access()) { ?>
                             <li class="treeview <?php if ($this->uri->segment('2') == ("manage_emp_type")){
                         echo "active";
                     } else {
                         echo " ";
                     }?>"><a href="<?php echo base_url('Cemployment/manage_emp_type')?>"><?php echo display('employment_type') ?></a></li>
                         <?php } ?>

                         <?php
                           if($this->permission1->method('manage_sal_range','read')->access() || $this->permission1->method('manage_sal_range','update')->access() || $this->permission1->method('manage_sal_range','delete')->access()) { ?>
                             <li class="treeview <?php if ($this->uri->segment('2') == ("manage_sal_range")){
                         echo "active";
                     } else {
                         echo " ";
                     }?>"><a href="<?php echo base_url('Cemployment/manage_sal_range')?>"><?php echo display('salary_range') ?></a></li>
                         <?php } ?>

                         
                    </ul>
                </li>
            <?php } ?>

                <!-- employment menu end-->


                <!-- fild of studies menu start-->
                  <?php
             if($this->permission1->module('add_field_of_study')->access() || $this->permission1->module('manage_field_of_study')->access()) { ?>
                <li class="treeview <?php if ($this->uri->segment('1') == ("Cfield_of_study")) { echo "active";}else{ echo " ";}?>">
                    <a href="#">
                        <i class="fa fa-book"></i><span><?php echo display('field_of_study') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php
                          if($this->permission1->method('add_field_of_study','create')->access()) { ?>
                            <li  class="treeview <?php if ($this->uri->segment('1') == ("Cfield_of_study") && $this->uri->segment('2') == ("")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Cfield_of_study') ?>"><?php echo display('add_field_of_study') ?></a></li>
                        <?php } ?>

                        <?php
                          if($this->permission1->method('manage_field_of_study','read')->access() || $this->permission1->method('manage_field_of_study','update')->access() || $this->permission1->method('manage_field_of_study','delete')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('2') == ("manage_field_of_study")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Cfield_of_study/manage_field_of_study')?>"><?php echo display('manage_field_of_study') ?></a></li>
                        <?php } ?>

                        
                    </ul>
                </li>
            <?php } ?>

                 <!-- end of fild of studies menu-->

                 <!-- zone menu start-->
                  <?php
             if($this->permission1->module('add_zone')->access() || $this->permission1->module('manage_zone')->access()) { ?>
                <li class="treeview <?php if ($this->uri->segment('1') == ("Czone")) { echo "active";}else{ echo " ";}?>">
                    <a href="#">
                        <i class="fa fa-map-marker"></i><span><?php echo display('zone') ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php
                          if($this->permission1->method('add_zone','create')->access()) { ?>
                            <li  class="treeview <?php if ($this->uri->segment('1') == ("Czone") && $this->uri->segment('2') == ("")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Czone') ?>"><?php echo display('add_zone') ?></a></li>
                        <?php } ?>

                        <?php
                          if($this->permission1->method('manage_zone','read')->access() || $this->permission1->method('manage_zone','update')->access() || $this->permission1->method('manage_zone','delete')->access()) { ?>
                            <li class="treeview <?php if ($this->uri->segment('2') == ("manage_zone")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Czone/manage_zone')?>"><?php echo display('manage_zone') ?></a></li>
                        <?php } ?>

                        
                    </ul>
                </li>
            <?php } ?>

                 
              
            <!-- Report menu start -->
             <?php
            if($this->permission1->module('shortlisted_report')->access() || $this->permission1->module('interviewed_report')->access() || $this->permission1->module('hired_report')->access() || $this->permission1->module('rejected_report')->access() || $this->permission1->module('applied_report')->access() ){ ?>
                <!-- Report menu start -->
            <li class="treeview <?php if ($this->uri->segment('2') == ("all_report") || $this->uri->segment('2') == ("todays_sales_report") || $this->uri->segment('2') == ("todays_purchase_report") || $this->uri->segment('2') == ("product_sales_reports_date_wise") || $this->uri->segment('2') == ("total_profit_report") || $this->uri->segment('2') == ("profit_manufacturer_form") || $this->uri->segment('2') == ("profit_productwise_form") || $this->uri->segment('2') == ("profit_productwise") || $this->uri->segment('2') == ("profit_manufacturer") ) { echo "active";}else{ echo " ";}?>">
                <a href="#">
                    <i class="ti-book"></i><span><?php echo display('report') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    
                    <?php
                    if($this->permission1->method('shortlisted_report','read')->access()){ ?>
                       <li class="treeview <?php  if ($this->uri->segment('2') == ("shortlisted_report")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Admin_dashboard/shortlisted_report')?>"><?php echo display('shortlisted_report') ?></a></li>
                    <?php } ?>

                    <?php
                    if($this->permission1->method('interviewed_report','read')->access()){ ?>
                      <li class="treeview <?php  if ($this->uri->segment('2') == ("interviewed_report")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Admin_dashboard/interviewed_report')?>"><?php echo display('interviewed_report') ?></a></li>
                    <?php } ?>

                    <?php
                    if($this->permission1->method('hired_report','read')->access()){ ?>
                       <li class="treeview <?php  if ($this->uri->segment('2') == ("hired_report")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Admin_dashboard/hired_report')?>"><?php echo display('hired_report') ?></a></li>
                     <?php } ?>
                     <!-- expense-->
                       <?php
                    if($this->permission1->method('rejected_report','read')->access()){ ?>
                      <li class="treeview <?php  if ($this->uri->segment('2') == ("rejected_report")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Admin_dashboard/rejected_report')?>"><?php echo display('rejected_report') ?></a></li>
                    <?php } ?>

                    <?php
                    if($this->permission1->method('applied_report','read')->access()){ ?>
                       <li class="treeview <?php  if ($this->uri->segment('2') == ("applied_report")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Admin_dashboard/applied_report')?>">Applied Report</a></li>
                    <?php } ?>
                    <!-- grand/net profit-->
                     
                      


                </ul>
            </li>
            <?php } ?>
            <!-- Report menu end -->
            
            <!-- Software Settings menu start -->
            <?php
            if($this->permission1->module('manage_company')->access() || $this->permission1->module('add_user')->access() || $this->permission1->module('manage_users')->access() || $this->permission1->module('language')->access() || $this->permission1->module('setting')->access() || $this->permission1->module('user_assign_role')->access() || $this->permission1->module('permission')->access() || $this->permission1->module('add_role')->access() || $this->permission1->module('role_list')->access() || $this->permission1->method('configure_sms','create')->access() || $this->permission1->method('configure_sms','update')->access() || $this->permission1->module('data_setting')->access() || $this->permission1->module('synchronize')->access() || $this->permission1->module('backup_and_restore')->access()){ ?>

                 <li class="treeview <?php if ($this->uri->segment('1') == ("Company_setup") || $this->uri->segment('1') == ("User") || $this->uri->segment('1') == ("Cweb_setting") || $this->uri->segment('1') == ("Language")|| $this->uri->segment('1') == ("Currency") || $this->uri->segment('1') == ("Permission") || $this->uri->segment('1') == ("Csms") || $this->uri->segment('1') == ("Backup_restore")) { echo "active";}else{ echo " ";}?>">
                <a href="#">
                    <i class="ti-settings"></i><span><?php echo display('settings') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                               <li class="treeview <?php if ($this->uri->segment('1') == ("Company_setup") || $this->uri->segment('1') == ("User") || $this->uri->segment('1') == ("Cweb_setting") || $this->uri->segment('1') == ("Language")|| $this->uri->segment('1') == ("Currency") ) { echo "active";}else{ echo " ";}?>">
                <a href="#">
                    <span><?php echo display('web_settings') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <?php
                    if($this->permission1->method('manage_company','read')->access() || $this->permission1->method('manage_company','update')->access()){ ?>
                      <li class="treeview <?php  if ($this->uri->segment('2') == ("manage_company")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Company_setup/manage_company')?>"><?php echo display('manage_company') ?></a></li>
                    <?php } ?>

                    <?php
                    if($this->permission1->method('add_user','create')->access()){ ?>
                      <li class="treeview <?php  if ($this->uri->segment('1') == ("User") && $this->uri->segment('2') == ("")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('User')?>"><?php echo display('add_user') ?></a></li>
                    <?php } ?>

                    <?php
                    if($this->permission1->method('manage_users','read')->access() || $this->permission1->method('manage_users','update')->access() || $this->permission1->method('manage_users','delete')->access()){ ?>
                      <li class="treeview <?php  if ($this->uri->segment('2') == ("manage_user")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('User/manage_user')?>"><?php echo display('manage_users') ?> </a></li>
                    <?php } ?>

                    <?php
                    if($this->permission1->method('language','create')->access() || $this->permission1->method('language','read')->access() || $this->permission1->method('add_phrase','read')->access() || $this->permission1->method('add_phrase','update')->access()){ ?>
                        <li class="treeview <?php  if ($this->uri->segment('1') == ("Language") && $this->uri->segment('2') == ("")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Language')?>"><?php echo display('language') ?> </a></li>
                    <?php } ?>
                    <?php
                    if($this->permission1->method('currency','create')->access()){ ?>
                       <li  class="treeview <?php  if ($this->uri->segment('1') == ("Currency") && $this->uri->segment('2') == ("")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Currency') ?>"><?php echo display('currency') ?> </a></li>
                   <?php }?>
                    <?php
                    if($this->permission1->method('soft_setting','read')->access() || $this->permission1->method('soft_setting','update')->access()){ ?>
                        <li  class="treeview <?php  if ($this->uri->segment('1') == ("Cweb_setting") && $this->uri->segment('2') == ("")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Cweb_setting')?>"><?php echo display('setting') ?> </a></li>
                    <?php } ?>


                </ul>
            </li>


            <?php
            if($this->permission1->module('user_assign_role')->access() || $this->permission1->module('permission')->access() || $this->permission1->module('add_role')->access() || $this->permission1->module('role_list')->access()){ ?>
            <!-- Role-permission menu start -->
            <li class="treeview <?php if ($this->uri->segment('1') == ("Permission")) { echo "active";}else{ echo " ";}?>">
                <a href="#">
                    <span><?php echo display('role_permission') ?></span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php
                    if($this->permission1->method('add_role','create')->access() || $this->permission1->method('add_role','read')->access() || $this->permission1->method('add_role','update')->access() || $this->permission1->method('add_role','delete')->access()){ ?>
                        <li class="treeview <?php  if ($this->uri->segment('2') == ("add_role")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Permission/add_role')?>"><?php echo display('add_role') ?></a></li>
                    <?php } ?>

                    <?php
                    if($this->permission1->method('role_list','read')->access() || $this->permission1->method('role_list','update')->access() || $this->permission1->method('role_list','delete')->access()){ ?>
                        <li class="treeview <?php  if ($this->uri->segment('2') == ("role_list")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Permission/role_list')?>"><?php echo display('role_list') ?></a></li>
                    <?php } ?>



                    <?php
                    if($this->permission1->method('user_assign_role','create')->access() || $this->permission1->method('user_assign_role','read')->access()){ ?>
                        <li class="treeview <?php  if ($this->uri->segment('2') == ("user_assign")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Permission/user_assign')?>"><?php echo display('user_assign_role')?></a></li>
                    <?php } ?>


                </ul>
            </li>
            <?php } ?>


                        <!-- Sms setting start -->
             <?php if($this->permission1->method('configure_sms','create')->access() || $this->permission1->method('configure_sms','update')->access()){?>
            
         <li class="treeview <?php if ($this->uri->segment('1') == ("Csms")) { echo "active";}else{ echo " ";}?>">
                <a href="#">
                    <span><?php echo display('sms'); ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                
                      <li class="treeview <?php  if ($this->uri->segment('2') == ("configure")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Csms/configure')?>"><?php echo display('sms_configure'); ?></a></li>
                     
 
                </ul>
             </li>
         <?php }?>
         
            <!-- Sms Setting end -->

            <!-- Synchronizer setting start -->
            <?php
            if($this->permission1->module('data_setting')->access() || $this->permission1->module('synchronize')->access() || $this->permission1->module('backup_and_restore')->access()){ ?>
                <a href="#">
                    <span><?php echo display('data_synchronizer') ?></span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php
                        $localhost=false;
                        if (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', 'localhost'))) {
                    ?>
                    <?php
                        if($this->permission1->method('data_setting','read')->access() || $this->permission1->method('data_setting','update')->access()){ ?>
                           <li  class="treeview <?php  if ($this->uri->segment('1') == ("data_synchronizer") && $this->uri->segment('2') == ("form")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('data_synchronizer/form')?>"><?php echo display('setting') ?></a></li>
                        <?php } ?>
                    <?php } ?>


                    <?php
                    if($this->permission1->method('synchronize','read')->access() || $this->permission1->method('synchronize','update')->access()){ ?>
                    <!--  <li><a href="<?php echo base_url('data_synchronizer/synchronize')?>"><?php echo display('synchronize') ?></a></li>-->
                    <!--<?php } ?>-->

                    <?php
                    if($this->permission1->method('backup_and_restore','read')->access() || $this->permission1->method('backup_and_restore','update')->access() || $this->permission1->method('backup_and_restore','delete')->access()){ ?>
                        <li class="treeview <?php  if ($this->uri->segment('1') == ("Backup_restore") && $this->uri->segment('2') == ("")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Backup_restore')?>"><?php echo display('backup') ?></a></li>
                    <?php } ?>
                      <li class="treeview <?php  if ($this->uri->segment('1') == ("Backup_restore") && $this->uri->segment('2') == ("import")){
                        echo "active";
                    } else {
                        echo " ";
                    }?>"><a href="<?php echo base_url('Backup_restore/import_form') ?>"><?php echo display('import') ?></a></li>

                </ul>
            </li>
            <?php } ?>
            <!-- Synchronizer setting end -->
             <li><a href="https://forum.bdtask.com/Pharmacare-software" target="blank"><i class="fa fa-question-circle"></i> Support</a></li>
                </ul>
            </li>
 
            <?php } ?>
            <!-- Software Settings menu end -->

        
        
        </ul>
    </div> <!-- /.sidebar -->
</aside>
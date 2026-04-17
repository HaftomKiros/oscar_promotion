<!-- Admin Home Start -->
 <div class="content-wrapper">
    <!-- Content Header(Page header)-->
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-world"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('dashboard')?></h1>
            <small><?php echo display('home')?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home')?></a></li>
                <li class="active"><?php echo display('dashboard')?></li>
            </ol>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Alert Message -->
        <?php
            $message = $this->session->userdata('message');
            if (isset($message)) {
        ?>
        <div class="alert alert-info alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $message ?>                    
        </div>
        <?php 
            $this->session->unset_userdata('message');
            }
            $error_message = $this->session->userdata('error_message');
            if (isset($error_message)) {
        ?>
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $error_message ?>                    
        </div>
        <?php 
            $this->session->unset_userdata('error_message');
            }
        ?>
        <!-- First Counter -->
        <div class="row">

           <?php
            if($this->permission1->method('manage_employer','read')->access()){ ?>
               <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="panel panel-bd">
                    <div class="panel-body">
                        <div class="statistic-box">
                              <a href="<?php echo base_url('Cemployer/manage_employer')?>" style="color:#000">
                            <h2><span class="count-number"><?php echo $total_employer ?></span> <span class="slight"><i class="fa fa-play fa-rotate-270 text-warning"> </i></span></h2></a>
                            <div class="small"><?php echo display('total_employer')?></div>
                            <div class="sparkline3 text-center"></div>
                        </div>
                    </div>
                </div>
               </div>
            <?php } ?>

             <?php
             if($this->permission1->method('manage_candidate','read')->access()) { ?>
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                 <div class="panel panel-bd">
                    <div class="panel-body">
                        <div class="statistic-box">
                              <a href="<?php echo base_url('Ccandidate/manage_candidate')?>" style="color:#000">
                            <h2><span class="count-number"><?php echo $total_candidate ?></span> <span class="slight"><i class="fa fa-play fa-rotate-270 text-warning"> </i> </span></h2></a>
                            <div class="small"><?php echo display('total_candidate')?></div>
                            <div class="sparkline2 text-center"></div>
                        </div>
                    </div>
                 </div>
                </div>
             <?php } ?>

            <?php
            if($this->permission1->method('shortlisted_report','read')->access()){ ?>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="panel panel-bd">
                    <div class="panel-body">
                        <div class="statistic-box">
                              <a href="<?php echo base_url('Admin_dashboard/shortlisted_report')?>" style="color:#000">
                            <h2><span class="count-number"><?php echo $total_short_list?></span> <span class="slight"><i class="fa fa-play fa-rotate-270 text-warning"> </i></span></h2></a>
                            <div class="small"><?php echo display('total_shortlisted')?></div>
                            <div class="sparkline4 text-center"></div>
                        </div>
                    </div>
                </div>
            </div>
           <?php } ?>


          
                  <?php
                if($this->permission1->method('interviewed_report','read')->access()) { ?>
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                <div class="panel panel-bd">
                    <div class="panel-body">
                        <div class="statistic-box">
                            <a href="<?php echo base_url('Admin_dashboard/interviewed_report')?>" style="color:#000">
                            <h2><span class="count-number"><?php echo $total_interview_list; ?></span><span class="slight"> <i class="fa fa-play fa-rotate-270 text-warning"> </i> </span></h2></a>
                            <div class="small"><?php echo display('total_interviewed')?></div>
                            <div class="sparkline2 text-center"></div>
                        </div>
                    </div>
                </div>
                </div>
                <?php } ?>
                      <?php
                if($this->permission1->method('hired_report','read')->access()) { ?>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-2">
                <div class="panel panel-bd">
                    <div class="panel-body">
                        <div class="statistic-box">
                            <a href="<?php echo base_url('Admin_dashboard/hired_report')?>" style="color:#000">
                            <h2><span class="count-number"><?php echo $total_hired_list ?></span><span class="slight"> <i class="fa fa-play fa-rotate-270 text-warning"> </i> </span></h2></a>
                            <div class="small"><?php echo display('total_hired')?></div>
                            <div class="sparkline4 text-center"></div>
                        </div>
                    </div>
                </div>
                </div>
                <?php } ?>
                 <?php
                if($this->permission1->method('rejected_report','read')->access()) { ?>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-2">
                <div class="panel panel-bd">
                    <div class="panel-body">
                        <div class="statistic-box">
                            <a href="<?php echo base_url('Admin_dashboard/rejected_report')?>" style="color:#000">
                            <h2><span class="count-number"><?php echo $total_rejected_list ?></span><span class="slight"> <i class="fa fa-play fa-rotate-270 text-warning"> </i> </span></h2></a>
                            <div class="small"><?php echo display('total_rejected')?></div>
                            <div class="sparkline4 text-center"></div>
                        </div>
                    </div>
                </div>
                </div>
                <?php } ?>

        </div>
        <hr>
        <!-- Second Counter -->
        <div class="row">

         <?php
         if($this->permission1->method('add_job','create')->access()) { ?>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <div class="panel panel-bd">
                    <div class="panel-body">
                        <div class="statistic-box">
                            <h2><span class="slight" style="margin-left: 70px;">
                                <img src="<?php echo base_url('my-assets/image/invoice.png');?>" height="40" width="40" >
                             </span></h2>
                            <div class="small" style="font-size: 17px;margin-top: 20px;text-align: center;"><a href="<?php echo base_url('Cjob')?>"><?php echo display('add_job')?></a></div>
                        </div>
                    </div>
                </div>
            </div>
          <?php } ?>





                <?php
                if($this->permission1->method('add_candidate','create')->access()) { ?>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <div class="panel panel-bd">
                    <div class="panel-body">
                        <div class="statistic-box">
                         <h2><span class="slight" style="margin-left: 70px;"><img src="<?php echo base_url('my-assets/image/invoice.png');?>" height="40" width="40" > </span></h2>
                            <div class="small" style="font-size: 17px;margin-top: 20px;text-align: center;"><a href="<?php echo base_url('Ccandidate')?>"><?php echo display('add_candidate')?></a></div>
                        </div>
                    </div>
                </div>
            </div>
                <?php } ?>


                <?php
                if($this->permission1->method('add_empployer','create')->access()) { ?>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <div class="panel panel-bd">
                    <div class="panel-body">
                        <div class="statistic-box">
                         <h2><span class="slight" style="margin-left: 70px;"><img src="<?php echo base_url('my-assets/image/invoice.png');?>" height="40" width="40" > </span></h2>
                            <div class="small" style="font-size: 17px;margin-top: 20px;text-align: center;"><a href="<?php echo base_url('Cemployer')?>"><?php echo display('add_employer')?></a></div>
                        </div>
                    </div>
                </div>
                </div>
                <?php } ?>


          <?php
           if($this->permission1->method('add_educational_level','create')->access()) { ?>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                <div class="panel panel-bd">
                    <div class="panel-body">
                        <div class="statistic-box">
                         <h2><span class="slight" style="margin-left: 70px;"><img src="<?php echo base_url('my-assets/image/invoice.png');?>" height="40" width="40" > </span></h2>
                            <div class="small" style="font-size: 17px;margin-top: 20px;text-align: center;"><a href="<?php echo base_url('Ceducational_level')?>"><?php echo display('add_educational_level')?></a></div>
                        </div>
                    </div>
                </div>
            </div>
         <?php } ?>

        </div>

        <!-- Third Counter -->
        <div class="row">

         
        </div>
        <hr>
        <!-- this is for chart-->

    </section> <!-- /.content -->

     <?php if ($this->session->userdata('user_type') == '1') { ?>
<div id="stockmodal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo display('out_of_stock_and_date_expired_medicine') ?></h4>
            </div>
            <div class="modal-body">
                <?php
                $date = date('Y-m-d');
                $this->db->select("b.*, b.expeire_date as expdate, a.product_name, a.strength,
                    ((SELECT IFNULL(SUM(quantity), 0) FROM product_purchase_details WHERE product_id = a.product_id) -
                     (SELECT IFNULL(SUM(quantity), 0) FROM invoice_details WHERE product_id = a.product_id)) AS stock");
                $this->db->from('product_information a');
                $this->db->join('product_purchase_details b', 'b.product_id = a.product_id', 'left');
                $this->db->where('b.expeire_date <=', $date);
                $this->db->having('stock > 0');
                $this->db->group_by(['b.batch_id', 'a.product_id']);
                $this->db->limit(20);
                $expired = $this->db->get()->result_array();
                ?>

                <table class="table table-bordered table-striped table-hover">
                    <caption><h4 class="text-center">Date Expired Medicine</h4></caption>
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo display('product_name') ?></th>
                            <th class="text-center"><?php echo display('batch_id') ?></th>
                            <th class="text-center"><?php echo display('expeire_date') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($expired) {
                            foreach ($expired as $row) { ?>
                                <tr>
                                    <td class="text-center">
                                        <a href="<?= base_url('Cproduct/product_details/' . $row['product_id']); ?>">
                                            <?= $row['product_name']; ?>
                                        </a>
                                    </td>
                                    <td class="text-center"><?= $row['batch_id']; ?></td>
                                    <td class="text-center"><?= $row['expdate']; ?></td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>

                <?php
                $this->db->select("a.*, b.manufacturer_name, a.product_name, a.generic_name, a.strength,
                    ((SELECT IFNULL(SUM(quantity), 0) FROM product_purchase_details WHERE product_id = a.product_id) -
                     (SELECT IFNULL(SUM(quantity), 0) FROM invoice_details WHERE product_id = a.product_id)) AS stock");
                $this->db->from('product_information a');
                $this->db->join('manufacturer_information b', 'b.manufacturer_id = a.manufacturer_id', 'left');
                $this->db->having('stock < 10');
                $this->db->group_by('a.product_id');
                $this->db->order_by('a.product_name', 'asc');
                $this->db->limit(20);
                $out_of_stock = $this->db->get()->result_array();
                ?>

                <table class="table table-bordered table-striped table-hover">
                    <caption><h4 class="text-center">Out of Stock Medicine</h4></caption>
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo display('product_name') ?></th>
                            <th class="text-center"><?php echo display('product_type') ?></th>
                            <th class="text-center"><?php echo display('unit') ?></th>
                            <th class="text-center"><?php echo display('stock') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($out_of_stock) {
                            foreach ($out_of_stock as $row) { ?>
                                <tr>
                                    <td class="text-center">
                                        <a href="<?= base_url('Cproduct/product_details/' . $row['product_id']); ?>">
                                            <?= $row['product_name']; ?>
                                        </a>
                                    </td>
                                    <td class="text-center"><?= $row['product_model']; ?></td>
                                    <td class="text-center"><?= $row['unit']; ?></td>
                                    <td class="text-center">
                                        <span style="color:red"><?= $row['stock']; ?></span>
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="background-color:green; color:white;">
                    <?= display('close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<?php } ?>


</div> <!-- /.content-wrapper -->
<!-- Admin Home end -->

<!-- ── Real-time Candidate Notification System ── -->
<style>
/* Bell dropdown items */
.notif-item{display:flex;align-items:flex-start;gap:10px;padding:10px 14px;border-bottom:1px solid #f0f0f0;cursor:pointer;transition:background 0.15s;}
.notif-item:hover{background:#f8fafc;}
.notif-item-icon{width:36px;height:36px;border-radius:9px;background:#1a3a5c;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:15px;color:#f5a623;}
.notif-item-body{flex:1;min-width:0;}
.notif-item-name{font-size:13px;font-weight:700;color:#1a3a5c;}
.notif-item-phone{font-size:11px;color:#6b7280;}
.notif-item-assign{font-size:11px;color:#f5a623;font-weight:600;margin-top:2px;}
.notif-item-time{font-size:10px;color:#9ca3af;margin-top:2px;}
/* Toast alert */
#oscar-notif-container{position:fixed;top:70px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;max-width:340px;}
.oscar-notif{background:#fff;border-radius:12px;padding:14px 16px;box-shadow:0 8px 32px rgba(0,0,0,0.15);border-left:4px solid #f5a623;display:flex;align-items:flex-start;gap:10px;animation:slideIn 0.35s cubic-bezier(0.34,1.56,0.64,1);cursor:pointer;}
@keyframes slideIn{from{transform:translateX(120%);opacity:0}to{transform:translateX(0);opacity:1}}
@keyframes slideOut{from{transform:translateX(0);opacity:1}to{transform:translateX(120%);opacity:0}}
.oscar-notif.removing{animation:slideOut 0.3s ease forwards;}
.oscar-notif-close{font-size:16px;color:#9ca3af;cursor:pointer;flex-shrink:0;line-height:1;}
</style>

<div id="oscar-notif-container"></div>

<script>
(function(){
  var API_URL  = '<?php echo base_url("Notification_api/check_new_candidates"); ?>';
  var EDIT_URL = '<?php echo base_url("Ccandidate/edit/"); ?>';
  // Key is unique per user — admin and data clerk each track their own shown alerts
  var SHOWN_KEY = 'oscar_shown_notif_ids_user_<?php echo $this->session->userdata("user_id"); ?>';

  function getShownIds(){
    try{ return JSON.parse(localStorage.getItem(SHOWN_KEY)||'[]'); }catch(e){ return []; }
  }
  function markShown(id){
    var ids = getShownIds();
    if(ids.indexOf(id)===-1){ ids.push(id); localStorage.setItem(SHOWN_KEY, JSON.stringify(ids)); }
  }

  function playSound(){
    try{
      var ctx=new(window.AudioContext||window.webkitAudioContext)();
      [[880,0],[1100,0.15],[1320,0.3]].forEach(function(f){
        var o=ctx.createOscillator(),g=ctx.createGain();
        o.connect(g);g.connect(ctx.destination);
        o.frequency.value=f[0];o.type='sine';
        g.gain.setValueAtTime(0.3,ctx.currentTime+f[1]);
        g.gain.exponentialRampToValueAtTime(0.001,ctx.currentTime+f[1]+0.2);
        o.start(ctx.currentTime+f[1]);o.stop(ctx.currentTime+f[1]+0.25);
      });
    }catch(e){}
  }

  function removeNotif(el){
    el.classList.add('removing');
    setTimeout(function(){ if(el.parentNode) el.parentNode.removeChild(el); },300);
  }

  function showToast(c){
    var container=document.getElementById('oscar-notif-container');
    var el=document.createElement('div');
    el.className='oscar-notif';
    el.innerHTML=
      '<div style="font-size:22px;">👤</div>'+
      '<div style="flex:1;min-width:0;">'+
        '<div style="font-size:13px;font-weight:700;color:#1a3a5c;">New Registration!</div>'+
        '<div style="font-size:12px;color:#374151;"><strong>'+c.full_name+'</strong> &mdash; '+c.phone_number+'</div>'+
        (c.assigned_name?'<div style="font-size:11px;color:#f5a623;font-weight:600;">Assigned to: '+c.assigned_name+'</div>':'')+
        '<div style="font-size:11px;color:#9ca3af;">ID: '+c.seeker_id+'</div>'+
      '</div>'+
      '<div class="oscar-notif-close" title="Dismiss">&times;</div>';
    el.addEventListener('click',function(e){
      if(!e.target.classList.contains('oscar-notif-close')) window.location.href=EDIT_URL+c.id;
    });
    el.querySelector('.oscar-notif-close').addEventListener('click',function(e){
      e.stopPropagation(); removeNotif(el);
    });
    container.appendChild(el);
  }

  function updateBell(data){
    var badge=document.getElementById('notif-badge');
    var body=document.getElementById('notif-list-body');
    var label=document.getElementById('notif-count-label');

    // Update badge count
    if(data.total>0){
      badge.style.display='block';
      badge.textContent=data.total>99?'99+':data.total;
      label.textContent='('+data.total+' pending)';
    } else {
      badge.style.display='none';
      label.textContent='';
    }

    // Update dropdown list
    if(!data.candidates||data.candidates.length===0){
      body.innerHTML='<div style="padding:20px;text-align:center;color:#9ca3af;font-size:13px;">No pending registrations</div>';
      return;
    }
    var html='';
    var limit = Math.min(data.candidates.length, 5);
    for(var i=0; i<limit; i++){
      var c=data.candidates[i];
      var timeAgo=c.created_at||'';
      html+='<li><div class="notif-item" onclick="window.location.href=\''+EDIT_URL+c.id+'\'">'+
        '<div class="notif-item-icon">👤</div>'+
        '<div class="notif-item-body">'+
          '<div class="notif-item-name">'+c.full_name+'</div>'+
          '<div class="notif-item-phone">📱 '+c.phone_number+' &nbsp;|&nbsp; ID: '+c.seeker_id+'</div>'+
          (c.assigned_name?'<div class="notif-item-assign">👤 Assigned to: '+c.assigned_name+'</div>':'')+
          '<div class="notif-item-time">'+timeAgo+'</div>'+
        '</div>'+
      '</div></li>';
    }
    if(data.candidates.length > 5){
      html+='<li style="border-top:2px solid #e5e7eb;"><a href="<?php echo base_url("Ccandidate/manage_candidate"); ?>" style="display:block;padding:10px 14px;text-align:center;color:#1a3a5c;font-weight:600;font-size:12px;text-decoration:none;">📋 View All '+data.total+' Registrations →</a></li>';
    }
    body.innerHTML=html;
  }

  function poll(){
    fetch(API_URL,{credentials:'same-origin'})
      .then(function(r){return r.json();})
      .then(function(data){
        if(data.status!=='success') return;
        updateBell(data);

        // Show toast only for NEW IDs not yet shown
        if(data.has_new && data.new_ids && data.new_ids.length>0){
          var shown=getShownIds();
          var truly_new=data.new_ids.filter(function(id){ return shown.indexOf(id)===-1; });
          if(truly_new.length>0){
            playSound();
            // Find candidate objects for new IDs
            truly_new.forEach(function(id){
              var c=data.candidates.find(function(x){ return parseInt(x.id)===parseInt(id); });
              if(c){ showToast(c); markShown(id); }
            });
          }
        }
      })
      .catch(function(){});
  }

  setTimeout(function(){ poll(); setInterval(poll,10000); },2000);
})();
</script>
 
<!-- ChartJs JavaScript -->
<script src="<?php echo base_url()?>assets/plugins/chartJs/Chart.min.js" type="text/javascript"></script>

<script type="text/javascript"> 
    //line chart
    var ctx = document.getElementById("lineChart");
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["January", "February", "March", "April", "May", "June", "July","August","September","October","November","December"],
            datasets: [
                {
                    label: "Sales",
                    borderColor: "#2C3136",
                    borderWidth: "1",
                    backgroundColor: "rgba(0,0,0,.07)",
                    pointHighlightStroke: "rgba(26,179,148,1)",
                    data: [<?php foreach ($inv_jan as  $value){echo $value['invoice_id'];} ?>,<?php foreach ($inv_feb as  $value){echo $value['invoice_id'];} ?>,<?php foreach ($inv_mar as  $value){echo $value['invoice_id'];} ?>,<?php foreach ($inv_apr as  $value){echo $value['invoice_id'];} ?>,<?php foreach ($inv_may as  $value){echo $value['invoice_id'];} ?>,<?php foreach ($inv_jun as  $value){echo $value['invoice_id'];} ?>,<?php foreach ($inv_jul as  $value){echo $value['invoice_id'];} ?>,<?php foreach ($inv_aug as  $value){echo $value['invoice_id'];} ?>,<?php foreach ($inv_sep as  $value){echo $value['invoice_id'];} ?>,<?php foreach ($inv_oct as  $value){echo $value['invoice_id'];} ?>,<?php foreach ($inv_nov as  $value){echo $value['invoice_id'];} ?>,<?php foreach ($inv_dec as  $value){echo $value['invoice_id'];} ?>
                    ]
                },
                {
                    label: "Purchase",
                    borderColor: "#73BC4D",
                    borderWidth: "1",
                    backgroundColor: "#73BC4D",
                    pointHighlightStroke: "rgba(26,179,148,1)",
                    data: [<?php foreach ($pur_jan as  $value){echo $value['purchase_id'];} ?>,<?php foreach ($pur_feb as  $value){echo $value['purchase_id'];} ?>,<?php foreach ($pur_mar as  $value){echo $value['purchase_id'];} ?>,<?php foreach ($pur_apr as  $value){echo $value['purchase_id'];} ?>,<?php foreach ($pur_may as  $value){echo $value['purchase_id'];} ?>,<?php foreach ($pur_jun as  $value){echo $value['purchase_id'];} ?>,<?php foreach ($pur_jul as  $value){echo $value['purchase_id'];} ?>,<?php foreach ($pur_aug as  $value){echo $value['purchase_id'];} ?>,<?php foreach ($pur_sep as  $value){echo $value['purchase_id'];} ?>,<?php foreach ($pur_oct as  $value){echo $value['purchase_id'];} ?>,<?php foreach ($pur_nov as  $value){echo $value['purchase_id'];} ?>,<?php foreach ($pur_dec as  $value){echo $value['purchase_id'];} ?>
                    ]
                }
            ]
        },
        options: {
            responsive: true,
            tooltips: {
                mode: 'index',
                intersect: false
            },
            hover: {
                mode: 'nearest',
                intersect: true
            }

        }
    }); 
 var  dismodl=$('#is_modal_shown').val();
 var  stockqt=$('#stpcount').val();
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd = '0'+dd
} 

if(mm<10) {
    mm = '0'+mm
} 
today = yyyy + '-' + mm + '-' + dd;

 var  expdate=$('#expdate').val();
 var is_modal_shown = 1;
 if (dismodl == '' && stockqt > 0 || dismodl == '' && new Date(expdate) < new Date(today)){
     $(window).load(function(){        
     $('#stockmodal').modal('show');
    });
   $.ajax({
    type: "POST",
    url: '<?php echo base_url('user/modaldisplay'); ?>',
    data: { is_modal_shown: 1 },
    success: function (response) {
        console.log('Modal display flag set.');
    },
    error: function (jqXHR, textStatus, errorThrown) {
        console.error('AJAX error:', textStatus, errorThrown);
        alert('An error occurred while setting modal display flag: ' + errorThrown);
    },
    complete: function(jqXHR, textStatus) {
        console.log('AJAX request finished with status: ' + textStatus);
    }
});


     }


</script>
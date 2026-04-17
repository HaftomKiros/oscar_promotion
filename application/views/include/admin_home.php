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
#oscar-notif-container{position:fixed;top:70px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;max-width:340px;}
.oscar-notif{
  background:#fff;border-radius:12px;padding:16px 18px;
  box-shadow:0 8px 32px rgba(0,0,0,0.15);
  border-left:4px solid #f5a623;
  display:flex;align-items:flex-start;gap:12px;
  animation:slideIn 0.35s cubic-bezier(0.34,1.56,0.64,1);
  cursor:pointer;
}
@keyframes slideIn{from{transform:translateX(120%);opacity:0}to{transform:translateX(0);opacity:1}}
@keyframes slideOut{from{transform:translateX(0);opacity:1}to{transform:translateX(120%);opacity:0}}
.oscar-notif.removing{animation:slideOut 0.3s ease forwards;}
.oscar-notif-icon{width:40px;height:40px;border-radius:10px;background:#1a3a5c;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:18px;}
.oscar-notif-body{flex:1;min-width:0;}
.oscar-notif-title{font-size:13px;font-weight:700;color:#1a3a5c;margin-bottom:3px;}
.oscar-notif-text{font-size:12px;color:#6b7280;line-height:1.4;}
.oscar-notif-time{font-size:11px;color:#9ca3af;margin-top:4px;}
.oscar-notif-close{font-size:16px;color:#9ca3af;cursor:pointer;flex-shrink:0;line-height:1;padding:2px;}
.oscar-notif-close:hover{color:#374151;}

/* Call center badge on dashboard */
.incomplete-badge{
  display:inline-flex;align-items:center;gap:6px;
  background:#fef3c7;border:1px solid #f59e0b;
  color:#92400e;font-size:12px;font-weight:600;
  padding:4px 12px;border-radius:20px;
}
</style>

<div id="oscar-notif-container"></div>

<script>
(function(){
  var API_URL = '<?php echo base_url("Notification_api/check_new_candidates"); ?>';
  var EDIT_URL = '<?php echo base_url("Ccandidate/edit/"); ?>';
  var POLL_INTERVAL = 5000; // 5 seconds

  // Generate notification sound using Web Audio API
  function playNotifSound(){
    try {
      var ctx = new (window.AudioContext || window.webkitAudioContext)();
      var times = [0, 0.15, 0.3];
      var freqs = [880, 1100, 1320];
      times.forEach(function(t, i){
        var osc = ctx.createOscillator();
        var gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.frequency.value = freqs[i];
        osc.type = 'sine';
        gain.gain.setValueAtTime(0.3, ctx.currentTime + t);
        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + t + 0.2);
        osc.start(ctx.currentTime + t);
        osc.stop(ctx.currentTime + t + 0.25);
      });
    } catch(e){}
  }

  function removeNotif(el){
    el.classList.add('removing');
    setTimeout(function(){ if(el.parentNode) el.parentNode.removeChild(el); }, 300);
  }

  function showNotif(candidate){
    var container = document.getElementById('oscar-notif-container');
    var el = document.createElement('div');
    el.className = 'oscar-notif';
    el.innerHTML =
      '<div class="oscar-notif-icon">👤</div>' +
      '<div class="oscar-notif-body">' +
        '<div class="oscar-notif-title">New Registration!</div>' +
        '<div class="oscar-notif-text"><strong>' + candidate.full_name + '</strong><br>' + candidate.phone_number + '</div>' +
        '<div class="oscar-notif-time">ID: ' + candidate.seeker_id + ' &mdash; Just now</div>' +
        '<div style="margin-top:8px;"><span style="font-size:11px;color:#f5a623;font-weight:600;">Click to complete profile →</span></div>' +
      '</div>' +
      '<div class="oscar-notif-close" title="Dismiss">&times;</div>';

    // Click anywhere except close = go to edit
    el.addEventListener('click', function(e){
      if(!e.target.classList.contains('oscar-notif-close')){
        window.location.href = EDIT_URL + candidate.id;
      }
    });

    // Close button — only dismiss, don't navigate
    el.querySelector('.oscar-notif-close').addEventListener('click', function(e){
      e.stopPropagation();
      removeNotif(el);
    });

    container.appendChild(el);
    // No auto-remove — stays until manually dismissed
  }

  function poll(){
    fetch(API_URL, {credentials:'same-origin'})
      .then(function(r){ return r.json(); })
      .then(function(data){
        if(data.has_new && data.candidates){
          playNotifSound();
          data.candidates.forEach(function(c){ showNotif(c); });
        }
      })
      .catch(function(){});
  }

  // Start polling after 3 seconds
  setTimeout(function(){
    poll();
    setInterval(poll, POLL_INTERVAL);
  }, 3000);
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
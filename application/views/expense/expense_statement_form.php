<style type="text/css">
    .prints{
        background-color: #31B404;
        color:#fff;
    }
</style>
<!-- Manage Invoice Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div> 
        <div class="header-title">
            <h1><?php echo display('expense') ?></h1>
            <small><?php echo display('total_expense_ammount') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('invoice') ?></a></li>
                <li class="active"><?php echo display('manage_invoice') ?></li>
            </ol>
        </div>
    </section>

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

        <div class="row">
            <div class="col-sm-12">
                <div class="column">
    <?php if($this->permission1->method('add_expense','create')->access()){ ?>
                    <a href="<?php echo base_url('Cexpense/add_expense') ?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('add_expese') ?> </a>
                <?php }?>

                </div>
            </div>
        </div>

        <!-- date between search -->
        <div class="row">
            
                <div class="panel panel-default">
                    <div class="panel-body"> 
                        <div class="col-sm-7">
                        <?php echo form_open('', array('class' => 'form-inline', 'method' => 'get')) ?>
                        <?php
                      
                        $today = date('Y-m-d');
                        ?>
                        <div class="form-group">
                            <label class="" for="from_date"><?php echo display('start_date') ?></label>
                            <input type="text" name="from_date" class="form-control datepicker" id="from_date" value="" placeholder="<?php echo display('start_date') ?>" >
                        </div> 

                        <div class="form-group">
                            <label class="" for="to_date"><?php echo display('end_date') ?></label>
                            <input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>" value="">
                        </div>  

                        <button type="button" id="btn-filter" class="btn btn-success"><?php echo display('find') ?></button>

                        <?php echo form_close() ?>
                    </div>
                  
          
                </div>
            </div>
            
        </div>
        <div class="row"> 
        </div>
        <!-- Manage Invoice report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive" >
                            <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="ExpList"> 
                                <thead>
                                    <tr>
                                        <th><?php echo display('sl') ?></th>
                                        <th><?php echo display('expense_type') ?></th>
                                        <th><?php echo display('total_expense_ammount') ?></th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
             
                                </tbody>
                                <tfoot>
                    <th  colspan="2" style="text-align:right">Total:</th>
                
                  <th></th>  
                  
                  
                                </tfoot>
                            </table>
                            
                        </div>
                       

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Manage Invoice End -->

<!-- Manage Product End -->
<script type="text/javascript">
$(document).ready(function() { 
 var mydatatable = $('#ExpList').DataTable({ 
             responsive: true,

             "aaSorting": [[ 1, "desc" ]],
             "columnDefs": [
                { "bSortable": false, "aTargets": [0,1] },

            ],
           'processing': true,
           'serverSide': true,

          
           'lengthMenu':[[10, 25, 50,100,250,500, "<?php echo $total_invoice;?>"], [10, 25, 50,100,250,500, "All"]],

             dom:"'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip", buttons:[ {
                extend: "copy",exportOptions: {
                       columns: [ 0, 1, 2 ] //Your Colume value those you want
                           }, className: "btn-sm prints"
            }
            , {
                extend: "csv", title: "ExpList",exportOptions: {
                       columns: [ 0, 1, 2] //Your Colume value those you want print
                           }, className: "btn-sm prints"
            }
            , {
                extend: "excel",exportOptions: {
                       columns: [ 0, 1, 2 ] //Your Colume value those you want print
                           }, title: "ExpList", className: "btn-sm prints"
            }
            , {
                extend: "pdf",exportOptions: {
                       columns: [ 0, 1, 2 ] //Your Colume value those you want print
                           }, title: "Expense List", className: "btn-sm prints"
            }
            , {
                extend: "print",exportOptions: {
                       columns: [ 0, 1, 2, 3,4,5 ] //Your Colume value those you want print
                           }, title: "Expense List", className: "btn-sm prints"
            }
            ],

            
            'serverMethod': 'post',
            'ajax': {
               'url':'<?=base_url()?>Cexpense/CheckExpList',
                 "data": function ( data) {
         data.fromdate = $('#from_date').val();
         data.todate = $('#to_date').val();

//data.status = $('#status').val();
}
            },
          'columns': [
    { data: 'sl' },
    { data: 'type' },
    { data: 'total_amount', class: "total_expense" },
    
    // add more columns if needed, e.g., voucher_no
],


  "footerCallback": function(row, data, start, end, display) {
    var api = this.api();

    // Sum Total Amount
    api.columns('.total_expense', { page: 'current' }).every(function() {
        var sum = this
            .data()
            .reduce(function(a, b) {
                var x = parseFloat(a) || 0;
                var y = parseFloat(b) || 0;
                return x + y;
            }, 0);
        $(this.footer()).html(sum.toFixed(2));
    });

   
}



    });


$('#btn-filter').click(function(){ 
mydatatable.ajax.reload();  
});

});


 

</script>
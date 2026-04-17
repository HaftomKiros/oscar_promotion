<style type="text/css">
    .prints {
        background-color: #31B404;
        color: #fff;
    }

    /* Make table scrollable on small devices */
    @media (max-width: 768px) {
        .panel-body-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
        }

        .panel-body-scroll table {
            min-width: 900px; /* Adjust as needed */
            white-space: nowrap;
        }
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-note2"></i></div>
        <div class="header-title">
            <h1><?php echo display('manage_purchase') ?></h1>
            <small><?php echo display('manage_your_purchase') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('purchase') ?></a></li>
                <li class="active"><?php echo display('manage_purchase') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
        <!-- Alert Messages -->
        <?php
        if ($message = $this->session->userdata('message')) {
            echo '<div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>'.$message.'</div>';
            $this->session->unset_userdata('message');
        }
        if ($error_message = $this->session->userdata('error_message')) {
            echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>'.$error_message.'</div>';
            $this->session->unset_userdata('error_message');
        }
        ?>

        <!-- Filter Form -->
        <div class="panel panel-default">
            <div class="panel-body">
                <form class="form-inline" method="post">
                    <div class="form-group">
                        <label for="from_date"><?php echo display('from') ?></label>
                        <input type="text" name="from_date" class="form-control datepicker" id="from_date" placeholder="<?php echo display('start_date') ?>">
                    </div>
                    <div class="form-group">
                        <label for="to_date"><?php echo display('to') ?></label>
                        <input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>">
                    </div>
                    <button type="button" id="btn-filter" class="btn btn-success"><?php echo display('find') ?></button>
                </form>
            </div>
        </div>

        <!-- Manage Purchase Table -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title"><h4><?php echo display('manage_purchase') ?></h4></div>
                    </div>
                    <div class="panel-body panel-body-scroll">
                        <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="PurList">
                            <thead>
                                <tr>
                                    <th><?php echo display('sl') ?></th>
                                    <th><?php echo display('invoice_no') ?></th>
                                    <th><?php echo display('purchase_id') ?></th>
                                    <th><?php echo display('stock_name') ?></th>
                                    <th><?php echo display('manufacturer_name') ?></th>
                                    <th><?php echo display('purchase_date') ?></th>
                                    <th><?php echo display('total_ammount') ?></th>
                                    <th><?php echo display('action') ?></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" style="text-align:right"><?php echo display('total') ?>:</th>
                                    <th id="total_purchase"></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- DataTables Script -->
<script type="text/javascript">
$(document).ready(function() { 
    var mydatatable = $('#PurList').DataTable({ 
        responsive: false,
        scrollX: true,
        autoWidth: false,
        "aaSorting": [[4, "desc"]],
        "columnDefs": [
            { "bSortable": false, "aTargets": [0,1,2,3,5,6,7] }
        ],
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        ajax: {
            url: '<?=base_url()?>Cpurchase/CheckPurchaseList',
            data: function ( data ) {
                data.fromdate = $('#from_date').val();
                data.todate = $('#to_date').val();
            }
        },
        lengthMenu: [[10, 25, 50, 100, 250, 500, "<?php echo $total_purhcase;?>"], [10, 25, 50, 100, 250, 500, "All"]],
        dom: "<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
        buttons: [
            { extend: "copy", className: "btn-sm prints", exportOptions: { columns: [0,1,2,3,4,5,6] } },
            { extend: "csv", title: "PurchaseList", className: "btn-sm prints", exportOptions: { columns: [0,1,2,3,4,5,6] } },
            { extend: "excel", title: "PurchaseList", className: "btn-sm prints", exportOptions: { columns: [0,1,2,3,4,5,6] } },
            { extend: "pdf", title: "PurchaseList", className: "btn-sm prints", exportOptions: { columns: [0,1,2,3,4,5,6] } },
            { extend: "print", title: "PurchaseList", className: "btn-sm prints", exportOptions: { columns: [0,1,2,3,4,5,6] } }
        ],
        columns: [
            { data: 'sl' },
            { data: 'chalan_no' },
            { data: 'purchase_id' },
            { data: 'stock_name' },
            { data: 'manufacturer_name' },
            { data: 'purchase_date' },
            { data: 'total_amount', className: "total_purchase" },
            { data: 'button' }
        ],
        footerCallback: function(row, data, start, end, display) {
            var api = this.api();
            var total = api.column('.total_purchase', { page: 'current' }).data().reduce(function(a, b) {
                var x = parseFloat(a) || 0;
                var y = parseFloat(b) || 0;
                return x + y;
            }, 0);
            $('#total_purchase').html(total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        }
    });

    // Filter button
    $('#btn-filter').click(function(){ 
        mydatatable.ajax.reload();  
    });
});
</script>

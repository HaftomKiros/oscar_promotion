<style type="text/css">
    .prints {
        background-color: #31B404;
        color: #fff;
    }

    /* Make table scrollable on mobile devices */
    @media (max-width: 768px) {
        .panel-body-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
        }

        .panel-body-scroll table {
            min-width: 1000px; /* Adjust to fit your columns */
            white-space: nowrap;
        }
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-note2"></i></div>
        <div class="header-title">
            <h1><?php echo display('manage_purchase') ?></h1>
            <small><?php echo display('detail_pur') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('purchase') ?></a></li>
                <li class="active"><?php echo display('detail_pur') ?></li>
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
                <form class="form-inline">
                    <div class="form-group">
                        <label for="from_date"><?php echo display('from') ?></label>
                        <input type="text" name="fromdate" class="form-control datepicker" id="from_date" placeholder="<?php echo display('start_date') ?>">
                    </div>
                    <div class="form-group">
                        <label for="to_date"><?php echo display('to') ?></label>
                        <input type="text" name="todate" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>">
                    </div>
                    <button type="button" id="btn-filter" class="btn btn-success"><?php echo display('find') ?></button>
                </form>
            </div>
        </div>

        <!-- Purchase Details Table -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title"><h4><?php echo display('detail_pur') ?></h4></div>
                    </div>
                    <div class="panel-body panel-body-scroll">
                        <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="PurDetailList">
                            <thead>
                                <tr>
                                    <th><?php echo display('sl') ?></th>
                                    <th><?php echo display('product_name') ?></th>
                                    <th><?php echo display('invoice_no') ?></th>
                                    <th><?php echo display('stock_name') ?></th>
                                    <th><?php echo display('manufacturer_name') ?></th>
                                    <th><?php echo display('purchase_date') ?></th>
                                    <th><?php echo display('quantity') ?></th>
                                    <th><?php echo display('rate') ?></th>
                                    <th><?php echo display('total_amount') ?></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" style="text-align:right"><?php echo display('total') ?>:</th>
                                    <th id="total_quantity"></th>
                                    <th id="total_rate"></th>
                                    <th id="total_amount"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
$(document).ready(function() { 
    var mydatatable = $('#PurDetailList').DataTable({ 
        responsive: false, // CSS handles mobile scroll
        scrollX: false,
        autoWidth: false,
        "aaSorting": [[5, "desc"]],
        "columnDefs": [
            { "orderable": false, "targets": [0,1,2,3,4,5] }
        ],
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        ajax: {
            url: '<?=base_url()?>Cpurchase/CheckPurchaseDetailList',
            data: function ( data ) {
                data.fromdate = $('#from_date').val();
                data.todate = $('#to_date').val();
            }
        },
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tip",
        buttons: [
            { extend: "copy", className: "btn-sm prints", exportOptions: { columns: [0,1,2,3,4,5,6,7,8] } },
            { extend: "csv", className: "btn-sm prints", exportOptions: { columns: [0,1,2,3,4,5,6,7,8] } },
            { extend: "excel", className: "btn-sm prints", exportOptions: { columns: [0,1,2,3,4,5,6,7,8] } },
            { extend: "pdf", className: "btn-sm prints", exportOptions: { columns: [0,1,2,3,4,5,6,7,8] } },
            { extend: "print", className: "btn-sm prints", exportOptions: { columns: [0,1,2,3,4,5,6,7,8] } }
        ],
        columns: [
            { data: 'sl' },
            { data: 'product_name' },
            { data: 'chalan_no' },
            { data: 'stock_name' },
            { data: 'manufacturer_name' },
            { data: 'purchase_date' },
            { data: 'quantity', className: 'total_quantity' },
            { data: 'rate', className: 'total_rate' },
            { data: 'total_amount', className: 'total_amount' }
        ],
        footerCallback: function(row, data, start, end, display) {
            var api = this.api();
            var parseFloatSafe = function(i) {
                return typeof i === 'string' ? parseFloat(i.replace(/,/g, '')) || 0 : typeof i === 'number' ? i : 0;
            };

            var quantitySum = api.column('.total_quantity', {page:'current'}).data().reduce(function(a,b){
                return parseFloatSafe(a) + parseFloatSafe(b);
            },0);

            var rateSum = api.column('.total_rate', {page:'current'}).data().reduce(function(a,b){
                return parseFloatSafe(a) + parseFloatSafe(b);
            },0);

            var totalSum = api.column('.total_amount', {page:'current'}).data().reduce(function(a,b){
                return parseFloatSafe(a) + parseFloatSafe(b);
            },0);

            $('#total_quantity').html(quantitySum.toFixed(2));
            $('#total_rate').html(rateSum.toFixed(2));
            $('#total_amount').html(totalSum.toFixed(2));
        }
    });

    $('#btn-filter').click(function(){ 
        mydatatable.ajax.reload();  
    });
});
</script>

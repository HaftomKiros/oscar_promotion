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
        min-width: 1000px; /* Adjust based on your columns */
        white-space: nowrap;
    }
}
</style>

<!-- Manage Invoice Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-note2"></i></div>
        <div class="header-title">
            <h1><?php echo display('manage_stock') ?></h1>
            <small><?php echo display('manage_your_stocks') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('stock') ?></a></li>
                <li class="active"><?php echo display('manage_stock') ?></li>
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
                        <label for="from_date"><?php echo display('start_date') ?></label>
                        <input type="text" name="from_date" class="form-control datepicker" id="from_date" placeholder="<?php echo display('start_date') ?>">
                    </div>
                    <div class="form-group">
                        <label for="to_date"><?php echo display('end_date') ?></label>
                        <input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>">
                    </div>
                    <button type="button" id="btn-filter" class="btn btn-success"><?php echo display('find') ?></button>
                </form>
            </div>
        </div>

        <!-- Stock Table -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('manage_stock') ?></h4>
                        </div>
                    </div>

                    <div class="panel-body panel-body-scroll">
                        <table id="StockList" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo display('sl') ?></th>
                                    <th><?php echo display('stock_name') ?></th>
                                    <th><?php echo display('address') ?></th>
                                    <th><?php echo display('assign_users') ?></th>
                                    <th><?php echo display('created_at') ?></th>
                                    <th><?php echo display('action') ?></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                           
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<!-- Manage Product End -->
<script type="text/javascript">
$(document).ready(function() { 
    var mydatatable = $('#StockList').DataTable({ 
        responsive: false, // Let CSS handle scroll
        scrollX: false,
        autoWidth: false,
        "aaSorting": [[4, "desc"]],
        "columnDefs": [
            { "orderable": false, "targets": [0,2,3,4,5] }
        ],
        processing: true,
        serverSide: true,
        serverMethod: 'post',

        ajax: {
            url: '<?=base_url()?>Creport/CheckStockList',
            data: function ( data ) {
                data.fromdate = $('#from_date').val();
                data.todate = $('#to_date').val();
            }
        },

        lengthMenu: [[10, 25, 50, 100, 250, 500], [10, 25, 50, 100, 250, 500]],
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tip",
        buttons: [
            { extend: "copy", className: "btn-sm prints", exportOptions: { columns: [0,1,2,3,4,5] } },
            { extend: "csv", className: "btn-sm prints", title: "Stock List", exportOptions: { columns: [0,1,2,3,4,5] } },
            { extend: "excel", className: "btn-sm prints", title: "Stock List", exportOptions: { columns: [0,1,2,3,4,5] } },
            { extend: "pdf", className: "btn-sm prints", title: "Stock List", exportOptions: { columns: [0,1,2,3,4,5] } },
            { extend: "print", className: "btn-sm prints", title: "Stock List", exportOptions: { columns: [0,1,2,3,4,5] } }
        ],

        columns: [
            { data: 'sl' },
            { data: 'stock_name' },
            { data: 'address' },
            { data: 'assign_users' },
            { data: 'created_at', className: 'total_stock' },
            { data: 'button' }
        ],

        footerCallback: function(row, data, start, end, display) {
            var api = this.api();

            var parseFloatSafe = function(i) {
                return typeof i === 'string' ? parseFloat(i.replace(/,/g, '')) || 0 : typeof i === 'number' ? i : 0;
            };

            // Example: if you have a numeric stock value, sum it here
            var stockCount = api.column('.total_stock', {page:'current'}).data().reduce(function(a,b){
                return parseFloatSafe(a) + parseFloatSafe(b);
            },0);

            $('#total_stock_count').html(stockCount.toFixed(2));
        }
    });

    $('#btn-filter').click(function(){ 
        mydatatable.ajax.reload();  
    });
});
</script>

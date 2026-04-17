<style type="text/css">
    /* Ensure that the scrollX is applied to the table */
.panel-body-scroll {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    width: 100%;
    position: relative;
}

/* Fix footer alignment issue */
.table th, .table td {
    white-space: nowrap;
}

tfoot {
    background-color: #f9f9f9;  /* Optional, to differentiate */
    font-weight: bold;
}

.table-fixed {
    table-layout: fixed;
    width: 100%;
}

tfoot td {
    text-align: right;
}

</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-note2"></i></div>
        <div class="header-title">
            <h1><?php echo display('manage_invoice') ?></h1>
            <small><?php echo display('manage_your_invoice') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('invoice') ?></a></li>
                <li class="active"><?php echo display('manage_invoice') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">

        <!-- Alert Messages -->
        <?php
        if($message = $this->session->userdata('message')){
            echo '<div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>'.$message.'</div>';
            $this->session->unset_userdata('message');
        }
        if($error_message = $this->session->userdata('error_message')){
            echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>'.$error_message.'</div>';
            $this->session->unset_userdata('error_message');
        }
        ?>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <?php if($this->permission1->method('new_invoice','create')->access()){ ?>
                        <a href="<?php echo base_url('Cinvoice') ?>" class="btn btn-info m-b-5 m-r-2">
                            <i class="ti-plus"></i> <?php echo display('new_invoice') ?>
                        </a>
                    <?php } ?>
                    <?php if($this->permission1->method('pos_invoice','create')->access()){ ?>
                        <a href="<?php echo base_url('Cinvoice/pos_invoice') ?>" class="btn btn-primary m-b-5 m-r-2">
                            <i class="ti-align-justify"></i> <?php echo display('pos_invoice') ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Date Filter -->
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-sm-12">
                        <?php echo form_open('', ['class'=>'form-inline','method'=>'get']); ?>
                        <div class="form-group">
                            <label for="from_date"><?php echo display('start_date') ?></label>
                            <input type="text" name="from_date" class="form-control datepicker" id="from_date" placeholder="<?php echo display('start_date') ?>">
                        </div>
                        <div class="form-group">
                            <label for="to_date"><?php echo display('end_date') ?></label>
                            <input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>">
                        </div>
                        <button type="button" id="btn-filter" class="btn btn-success"><?php echo display('find') ?></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Table -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-body panel-body-scroll">
                        <table class="table table-striped table-bordered" id="InvList" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo display('sl') ?></th>
                                    <th><?php echo display('invoice_no') ?></th>
                                    <th><?php echo display('stock_name') ?></th>
                                    <th><?php echo display('customer_name') ?></th>
                                    <th><?php echo display('date') ?></th>
                                    <th><?php echo display('grand_total') ?></th>
                                    <th><?php echo display('paid') ?></th>
                                    <th><?php echo display('due') ?></th>
                                    <th><?php echo display('manufacturer_total') ?></th>
                                    <th><?php echo display('grand_profit') ?></th>
                                    <th><?php echo display('paid_profit') ?></th>
                                    <th><?php echo display('sales_by') ?></th>
                                    <th><?php echo display('action') ?></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" style="text-align:right">Total:</th>
                                    <th class="total_sale"></th>
                                    <th class="paid_amount"></th>
                                    <th class="due_amount"></th>
                                    <th class="total_manufacturer"></th>
                                    <th class="grand_profit"></th>
                                    <th class="paid_profit"></th>
                                    <th colspan="2"></th>
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
    var mydatatable = $('#InvList').DataTable({ 
        responsive: false,
        scrollX: true,
        autoWidth: false,
        "aaSorting": [[1, "desc"]],
        "columnDefs": [{ "bSortable": false, "aTargets": [0,2,3,4,5,12] }],
        processing: true,
        serverSide: true,
        lengthMenu:[[10, 25, 50, 100, 250, 500, "<?php echo $total_invoice;?>"], [10, 25, 50, 100, 250, 500, "All"]],
        dom:"<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
        buttons: [
            { extend: "copy", className: "btn-sm prints" },
            { extend: "csv", title: "InvoiceList", className: "btn-sm prints" },
            { extend: "excel", title: "InvoiceList", className: "btn-sm prints" },
            { extend: "pdf", title: "Invoice List", className: "btn-sm prints" },
            { extend: "print", title: "Invoice List", className: "btn-sm prints" }
        ],
        serverMethod: 'post',
        ajax: {
            url:'<?=base_url()?>Cinvoice/CheckInvoiceList',
            data: function ( data ) {
                data.fromdate = $('#from_date').val();
                data.todate = $('#to_date').val();
            }
        },
        columns: [
            { data: 'sl' },
            { data: 'invoice' },
            { data: 'stock_name' },
            { data: 'customer_name' },
            { data: 'final_date' },
            { data: 'total_amount', class: "total_sale" },
            { data: 'paid_amount', class: "paid_amount" },
            { data: 'due_amount', class: "due_amount" },
            { data: 'total_manufacturer', class: "total_manufacturer" },
            { data: 'grand_profit', class: "grand_profit" },
            { data: 'paid_profit', class: "paid_profit" },
            { data: 'full_name' },
            { data: 'button' }
        ],
        footerCallback: function(row, data, start, end, display) {
            var api = this.api();
            // Footer row totals calculation
            ['total_sale', 'paid_amount', 'due_amount', 'total_manufacturer', 'grand_profit', 'paid_profit'].forEach(function(cls){
                var sum = api.column('.'+cls, { page: 'current' }).data().reduce(function(a,b){
                    return (parseFloat(a)||0) + (parseFloat(b)||0);
                }, 0);
                $(api.column('.'+cls).footer()).html(sum.toFixed(2));
            });
        },
        initComplete: function(settings, json) {
            // Sync the width of the footer with the table header and body
            var table = $(this).closest('.dataTables_wrapper').find('.dataTable');
            table.find('thead').css('width', '100%');
            table.find('tfoot').css('width', '100%');
        }
    });

    $('#btn-filter').click(function() { 
        mydatatable.ajax.reload();  
    });
});

</script>

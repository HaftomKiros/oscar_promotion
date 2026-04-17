<!-- Customer JS -->
<script src="<?php echo base_url()?>my-assets/js/admin_js/json/customer.js.php"></script>

<style>
.prints {
    background-color: #31B404;
    color: #fff;
}
.action {
    color: #fff;
}
.dropdown-menu > li > a {
    color: #fff;
}

/* Responsive table scroll */
@media (max-width: 768px) {
    .panel-body-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }
    .panel-body-scroll table {
        min-width: 900px;
        white-space: nowrap;
    }
}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-note2"></i></div>
        <div class="header-title">
            <h1><?php echo display('customer') ?></h1>
            <small><?php echo display('paid_customer') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('customer') ?></a></li>
                <li class="active"><?php echo display('paid_customer') ?></li>
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

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <?php if($this->permission1->method('add_customer','create')->access()){ ?>
                        <a href="<?php echo base_url('Ccustomer')?>" class="btn btn-info m-b-5 m-r-2">
                            <i class="ti-plus"></i> <?php echo display('add_customer') ?>
                        </a>
                    <?php } ?>

                    <?php if($this->permission1->method('manage_customer','read')->access() || $this->permission1->method('manage_customer','update')->access() || $this->permission1->method('manage_customer','delete')->access()){ ?>
                        <a href="<?php echo base_url('Ccustomer/manage_customer')?>" class="btn btn-primary m-b-5 m-r-2">
                            <i class="ti-align-justify"></i> <?php echo display('manage_customer') ?>
                        </a>
                    <?php } ?>

                    <?php if($this->permission1->method('paid_customer','read')->access()){ ?>
                        <a href="<?php echo base_url('Ccustomer/credit_customer')?>" class="btn btn-warning m-b-5 m-r-2">
                            <i class="ti-align-justify"></i> <?php echo display('credit_customer') ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Paid Customer Table -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title"><h4><?php echo display('paid_customer') ?></h4></div>
                    </div>
                    <div class="panel-body panel-body-scroll">
                        <table id="paidCustomerList" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo display('sl') ?></th>
                                    <th><?php echo display('customer_name') ?></th>
                                    <th><?php echo display('stock_name') ?></th>
                                    <th><?php echo display('address') ?></th>
                                    <th><?php echo display('mobile') ?></th>
                                    <th><?php echo display('balance') ?></th>
                                    <th><?php echo display('action') ?></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" style="text-align:right"><?php echo display('total') ?>:</th>
                                    <th id="total_balance"></th>
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

<!-- DataTables Setup -->
<script type="text/javascript">
$(document).ready(function() {

    var table = $('#paidCustomerList').DataTable({
        responsive: false,
        scrollX: true,
        autoWidth: false,
        "aaSorting": [[1, "asc"]],
        "columnDefs": [
            { "bSortable": false, "aTargets": [0,2,3,4,5,6] }
        ],
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        ajax: {
            url: '<?= base_url() ?>Ccustomer/CheckPaidCustomerList'
        },
        lengthMenu: [[10, 25, 50, 100, 250, 500, "<?php echo $total_customer;?>"], [10, 25, 50, 100, 250, 500, "All"]],
        dom: "<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
        buttons: [
            { extend: "copy", className: "btn-sm prints" },
            { extend: "csv", title: "Paid Customer List", exportOptions: { columns: [0,1,2,3,4,5] }, className: "btn-sm prints" },
            { extend: "excel", title: "Paid Customer List", exportOptions: { columns: [0,1,2,3,4,5] }, className: "btn-sm prints" },
            { extend: "pdf", title: "Paid Customer List", exportOptions: { columns: [0,1,2,3,4,5] }, className: "btn-sm prints" },
            { extend: "print", title: "<center>Paid Customer List</center>", exportOptions: { columns: [0,1,2,3,4,5] }, className: "btn-sm prints" }
        ],
        columns: [
            { data: 'sl' },
            { data: 'customer_name' },
            { data: 'stock_name' },
            { data: 'address' },
            { data: 'mobile' },
            { data: 'balance', class: "balance" },
            { data: 'button' }
        ],

        // Footer total balance
        footerCallback: function(row, data, start, end, display) {
            var api = this.api();

            var total = api.column('.balance', { page: 'current' }).data().reduce(function(a, b) {
                var x = parseFloat((a + '').replace(/,/g, '')) || 0;
                var y = parseFloat((b + '').replace(/,/g, '')) || 0;
                return x + y;
            }, 0);

            $('#total_balance').html(total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        }
    });

});
</script>

<!-- Product Scripts -->
<script src="<?php echo base_url()?>my-assets/js/admin_js/json/product_invoice.js.php"></script>
<script src="<?php echo base_url()?>my-assets/js/admin_js/invoice.js" type="text/javascript"></script>

<style>
.prints {
    background-color: #31B404;
    color: #fff;
}
.dropdown-menu > li > a {
    color: #fff;
}

/* Responsive table improvements */
@media (max-width: 768px) {
    .panel-body-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }
    .panel-body-scroll table {
        min-width: 1000px;
        white-space: nowrap;
    }
}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-note2"></i></div>
        <div class="header-title">
            <h1><?php echo display('manage_product') ?></h1>
            <small><?php echo display('manage_your_product') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('product') ?></a></li>
                <li class="active"><?php echo display('manage_product') ?></li>
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
                    <?php if ($this->permission1->method('add_medicine','create')->access()) { ?>
                        <a href="<?php echo base_url('Cproduct')?>" class="btn btn-info m-b-5 m-r-2">
                            <i class="ti-plus"></i> <?php echo display('add_product') ?>
                        </a>
                    <?php } ?>

                    <?php if ($this->permission1->method('import_medicine_csv','create')->access()) { ?>
                        <a href="<?php echo base_url('Cproduct/add_product_csv')?>" class="btn btn-success m-b-5 m-r-2">
                            <i class="ti-upload"></i> <?php echo display('import_product_csv') ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Product Table -->
        <?php if ($this->permission1->method('manage_medicine','read')->access() || $this->permission1->method('manage_medicine','update')->access() || $this->permission1->method('manage_medicine','delete')->access()) { ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('manage_product') ?></h4>
                            <p class="text-right">
                                <a href="<?php echo base_url('Cproduct/exportCSV'); ?>" class="btn btn-success">
                                    <i class="ti-export"></i> <?php echo display('export_csv')?>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="panel-body panel-body-scroll">
                        <table id="productList" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo display('sl') ?></th>
                                    <th><?php echo display('product_name') ?></th>
                                    <th><?php echo display('generic_name') ?></th>
                                    <th><?php echo display('model') ?></th>
                                    <th><?php echo display('manufacturer') ?></th>
                                    <th><?php echo display('shelf') ?></th>
                                    <th><?php echo display('sell_price') ?></th>
                                    <th><?php echo display('purchase_price') ?></th>
                                    <th><?php echo display('strength') ?></th>
                                    <th><?php echo display('image') ?></th>
                                    <th style="width:130px;"><?php echo display('action') ?></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php } else { ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <h4><?php echo display('You do not have permission to access. Please contact with administrator.'); ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </section>
</div>

<!-- DataTable Setup -->
<script type="text/javascript">
$(document).ready(function() {

    var table = $('#productList').DataTable({
        responsive: false,
        scrollX: true,
        autoWidth: false,
        "aaSorting": [[1, "asc"]],
        "columnDefs": [
            { "bSortable": false, "aTargets": [0, 2, 3, 4, 5, 9, 10] }
        ],
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        ajax: {
            url: '<?= base_url() ?>Cproduct/CheckProductList'
        },
        lengthMenu: [[10, 25, 50, 100, 250, 500, "<?php echo $total_product;?>"], [10, 25, 50, 100, 250, 500, "All"]],
        dom: "<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
        buttons: [
            { extend: "copy", title: "Product List", className: "btn-sm prints" },
            { extend: "csv", title: "Product List", className: "btn-sm prints" },
            { extend: "excel", title: "Product List", className: "btn-sm prints" },
            { extend: "pdf", title: "Product List", className: "btn-sm prints" },
            { extend: "print", title: "<center>Product List</center>", className: "btn-sm prints" }
        ],
        columns: [
            { data: 'sl' },
            { data: 'product_name' },
            { data: 'generic_name' },
            { data: 'product_model' },
            { data: 'manufacturer_name' },
            { data: 'product_location' },
            { data: 'price' },
            { data: 'purchase_p' },
            { data: 'strength' },
            { data: 'image' },
            { data: 'button' }
        ]
    });

});
</script>

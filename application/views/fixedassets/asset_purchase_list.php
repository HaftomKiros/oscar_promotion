<!-- Manage Product Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('fixed_assets') ?></h1>
            <small><?php echo display('fixed_assets_purchase_manage') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('fixed_assets') ?></a></li>
                <li class="active"><?php echo display('fixed_assets_purchase_manage') ?></li>
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


        <!-- Manage Product report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('fixed_assets_purchase_manage') ?></h4>


                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row" style="margin-bottom: 10px;">
    <div class="col-sm-3">
        <input type="date" id="minDate" class="form-control" placeholder="From Date">
    </div>
    <div class="col-sm-3">
        <input type="date" id="maxDate" class="form-control" placeholder="To Date">
    </div>
    <div class="col-sm-3">
        <button id="filterDate" class="btn btn-success">Filter</button>
    </div>
</div>

                        <div class="table-responsive">
                            <table id="dataTableExample2" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th><?php echo display('sl') ?></th>
            <th><?php echo display('stock_name') ?></th>
            <th><?php echo display('date') ?></th>
            <th><?php echo display('supplier_name') ?></th>
            <th><?php echo display('item_name')?></th>
            <th><?php echo display('qty')?></th>
            <th><?php echo display('price') ?></th>
            <th style="width : 130px"><?php echo display('action') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if ($purchase_list) { ?>
            {purchase_list}
            <tr>
                <td>{sl}</td>
                <td>{stock_name}</td>
                <td>{p_date}</td>
                <td>{supplier_name}</td>
                <td>{item_name}</td>
                <td>{qty}</td>
                <td style="text-align: right;">
                    <?php echo (($position == 0) ? "$currency {grand_total}" : "{grand_total} $currency") ?>
                </td>
                <td>
                    <a href="<?php echo base_url() . 'Fixedassets/purchase_pad_print/{id}'; ?>" class="btn btn-primary btn-sm" title="Pad Print Purchase"><i class="fa fa-fax"></i></a>
                    <a href="<?php echo base_url() . 'Fixedassets/asset_purchase_update_form/{id}'; ?>" class="btn btn-info btn-sm" title="<?php echo display('update') ?>"><i class="fa fa-pencil"></i></a>
                    <a href="" class="deleteAssets btn btn-danger btn-sm" name="{id}" title="<?php echo display('delete') ?>"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
            {/purchase_list}
        <?php } ?>
    </tbody>
  <tfoot>
    <tr>
        <th colspan="5" style="text-align:right">Total:</th>
        <th style="text-align:right"></th> <!-- Qty total -->
        <th style="text-align:right"></th> <!-- Price total -->
        <th></th> <!-- Empty for actions -->
    </tr>
</tfoot>

</table>
                            <div class="text-right"><?php
                                if ($links) {
                                    echo $links;
                                }
                                ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Manage Product End -->

<!-- Delete Product ajax code -->
<script type="text/javascript">
    $(".deleteAssets").click(function ()
    {
        var item_code = $(this).attr('name');
        var csrf_test_name = $("[name=csrf_test_name]").val();
        var x = confirm("Are You Sure,Want to Delete ?");
        if (x == true) {
            $.ajax
                    ({
                        type: "POST",
                        url: '<?php echo base_url('Fixedassets/delete_assets') ?>',
                        data: {item_code: item_code, csrf_test_name: csrf_test_name},
                        cache: false,
                        success: function (datas)
                        {
                            // alert(datas);
                            // location.reload();
                        }
                    });
        }
    });
</script>
<script type="text/javascript">
$(document).ready(function() {

    // === Delete Fixed Asset ===
    $(".deleteAssets").click(function(e) {
        e.preventDefault();
        var item_code = $(this).attr('name');
        var csrf_test_name = $("[name=csrf_test_name]").val();
        var x = confirm("Are you sure you want to delete?");
        if (x) {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('Fixedassets/delete_assets') ?>",
                data: { item_code: item_code, csrf_test_name: csrf_test_name },
                success: function(datas) {
                    location.reload();
                }
            });
        }
    });

    // === DataTable Initialization ===
    var table = $('#dataTableExample2').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        order: [[0, 'asc']],
        lengthMenu: [10, 25, 50, 100],
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        footerCallback: function(row, data, start, end, display) {
            var api = this.api();
            var parseValue = function(i) {
                if (typeof i === 'string') {
                    i = i.replace(/[^0-9.\-]/g, '');
                    return parseFloat(i) || 0;
                }
                return typeof i === 'number' ? i : 0;
            };
            var totalQty = api.column(5, { page: 'current' }).data()
                .reduce(function(a, b) { return parseValue(a) + parseValue(b); }, 0);
            var totalPrice = api.column(6, { page: 'current' }).data()
                .reduce(function(a, b) { return parseValue(a) + parseValue(b); }, 0);
            var currency = "<?php echo $currency; ?>";
            var position = <?php echo $position; ?>;
            var formattedPrice = position === 0
                ? currency + totalPrice.toFixed(2)
                : totalPrice.toFixed(2) + ' ' + currency;
            $(api.column(5).footer()).html(totalQty.toLocaleString());
            $(api.column(6).footer()).html(formattedPrice);
        }
    });

    // === Date Range Filter ===
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var min = $('#minDate').val();
            var max = $('#maxDate').val();
            var dateCol = data[2]; // 'Date' column index

            if (!dateCol) return true;

            var date = new Date(dateCol);

            if (
                (min === '' && max === '') ||
                (min === '' && date <= new Date(max)) ||
                (new Date(min) <= date && max === '') ||
                (new Date(min) <= date && date <= new Date(max))
            ) {
                return true;
            }
            return false;
        }
    );

    // Trigger Filter Buttons
    $('#filterDate').on('click', function() {
        table.draw();
    });
    $('#resetDate').on('click', function() {
        $('#minDate').val('');
        $('#maxDate').val('');
        table.draw();
    });

});
</script>

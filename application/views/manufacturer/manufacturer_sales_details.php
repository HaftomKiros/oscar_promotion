<!-- Manufacturer Sales Report Start -->
<script type="text/javascript">
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    document.body.style.marginTop = "0px";
    window.print();
    document.body.innerHTML = originalContents;
}
</script>

<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-note2"></i></div>
        <div class="header-title">
            <h1><?php echo display('manufacturer_sales_details') ?></h1>
            <small><?php echo display('manufacturer_sales_details') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('manufacturer') ?></a></li>
                <li class="active"><?php echo display('manufacturer_sales_details') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <?php if($this->permission1->method('manage_manufacturer','create')->access()) { ?>
                        <a href="<?php echo base_url('Cmanufacturer')?>" class="btn btn-info m-b-5 m-r-2">
                            <i class="ti-plus"></i> <?php echo display('add_manufacturer')?>
                        </a>
                    <?php } ?>
                    <?php if($this->permission1->method('manage_manufacturer','read')->access()) { ?>
                        <a href="<?php echo base_url('Cmanufacturer/manage_manufacturer')?>" class="btn btn-primary m-b-5 m-r-2">
                            <i class="ti-align-justify"></i> <?php echo display('manage_manufacturer')?>
                        </a>
                    <?php } ?>
                    <?php if($this->permission1->method('manufacturer_ledger','read')->access()) { ?>
                        <a href="<?php echo base_url('Cmanufacturer/manufacturer_ledger_report')?>" class="btn btn-success m-b-5 m-r-2">
                            <i class="ti-align-justify"></i> <?php echo display('manufacturer_ledger')?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php if($this->permission1->method('manufacturer_sales_details','read')->access()) { ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <h4><?php echo display('manufacturer_sales_details') ?></h4>
                    </div>
                    <div class="panel-body">
                        <div class="text-right">
                            <a class="btn btn-warning" href="#" onclick="printDiv('printableArea')">
                                <?php echo display('print') ?>
                            </a>
                        </div>

                        <div id="printableArea" style="margin-left:2px;">
                            <?php if ($manufacturer_name) { ?>
                            <div class="text-center">
                                <h3>{manufacturer_name}</h3>
                                <h4><?php echo display('address') ?> : {manufacturer_address}</h4>
                                <h4><?php echo display('print_date') ?> : <?php echo date("d/m/Y h:i:s"); ?></h4>
                            </div>
                            <?php } ?>

                            <div class="table-responsive">
                                <table id="salesTable" class="table table-bordered table-striped table-hover nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo display('date') ?></th>
                                            <th><?php echo display('stock_name') ?></th>
                                            <th><?php echo display('product_name') ?></th>
                                            <th class="text-right"><?php echo display('quantity') ?></th>
                                            <th class="text-right"><?php echo display('rate') ?></th>
                                            <th class="text-right"><?php echo display('ammount') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($sales_info) { ?>
                                        {sales_info}
                                        <tr>
                                            <td>{date}</td>
                                            <td>{stock_name}</td>
                                            <?php if($this->permission1->method('manage_medicine','read')->access()) { ?>
                                                <td>
                                                    <a href="<?php echo base_url() . 'Cproduct/product_details/{product_id}'; ?>">
                                                        {product_name} - {product_model}
                                                    </a>
                                                </td>
                                            <?php } else { ?>
                                                <td>{product_name} - {product_model}</td>
                                            <?php } ?>
                                            <td class="text-right qty">{quantity}</td>
                                            <td class="text-right rate"><?php echo (($position==0)?"$currency {manufacturer_rate}":"{manufacturer_rate} $currency") ?></td>
                                            <td class="text-right amt"><?php echo (($position==0)?"$currency {total}":"{total} $currency") ?></td>
                                        </tr>
                                        {/sales_info}
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right"><b><?php echo display('grand_total') ?></b> :</th>
                                            <th class="text-right"><b id="totalQty">0</b></th>
                                            <th class="text-right"><b id="avgRate">0.00</b></th>
                                            <th class="text-right"><b id="totalAmt">0.00</b></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </section>
</div>

<script>
$(document).ready(function() {
    var table = $('#salesTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        responsive: false,
        scrollX: true,
        fixedHeader: true,
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            { extend: "copy", title: "Manufacturer Sales Details", className: "btn-sm prints" },
            { extend: "csv", title: "Manufacturer Sales Details", className: "btn-sm prints" },
            { extend: "excel", title: "Manufacturer Sales Details", className: "btn-sm prints" },
            { extend: "pdf", title: "Manufacturer Sales Details", className: "btn-sm prints" },
            { extend: "print", title: "<center>Manufacturer Sales Details</center>", className: "btn-sm prints" }
        ],
        footerCallback: function (row, data, start, end, display) {
    var api = this.api();

    // Helper function to parse values to float
    var parseValue = function (i) {
        if (typeof i === 'string') i = i.replace(/[^0-9.\-]+/g, '');
        var f = parseFloat(i);
        return isNaN(f) ? 0 : f;
    };

    var totalQty = 0;
    var totalRate = 0;
    var totalAmt = 0;

    // Loop through all rows
    api.rows().every(function () {
        var row = $(this.node());
        var qty = parseValue(row.find('td.qty').text());
        var rate = parseValue(row.find('td.rate').text());
        var amt = parseValue(row.find('td.amt').text());

        totalQty += qty;
        totalRate += rate;  // simple sum of unit prices
        totalAmt += amt;
    });

    // Update footer cells
    $('#totalQty').html(totalQty.toLocaleString());
    $('#avgRate').html(totalRate.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    $('#totalAmt').html(totalAmt.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
}

    });
});
</script>

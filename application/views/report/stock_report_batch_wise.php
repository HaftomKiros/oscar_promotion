<script src="<?php echo base_url()?>my-assets/js/admin_js/json/product_invoice.js.php" ></script>

<!-- Stock report start -->
<script type="text/javascript">
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
	document.body.style.marginTop="0px";
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
<style type="text/css">
.prints {
    background-color: #31B404;
    color: #fff;
}

/* Scrollable table for mobile */
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


<!-- Stock List manufacturer Wise Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-note2"></i></div>
        <div class="header-title">
            <h1><?php echo display('stock_report_batch_wise') ?></h1>
            <small><?php echo display('stock_report_batch_wise') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('stock') ?></a></li>
                <li class="active"><?php echo display('stock_report_batch_wise') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <?php if ($this->permission1->method('stock_report','read')->access()) { ?>
                        <a href="<?php echo base_url('Creport')?>" class="btn btn-primary m-b-5 m-r-2">
                            <i class="ti-align-justify"></i> <?php echo display('stock_report') ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php if ($this->permission1->method('stock_report_batch_wise','read')->access()) { ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('stock_report_batch_wise') ?></h4>
                        </div>
                    </div>

                    <div class="panel-body panel-body-scroll">
                        <div id="printableArea" style="margin-left:2px;">
                            <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="checkList">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?php echo display('sl') ?></th>
                                        <th class="text-center"><?php echo display('stock_name') ?></th>
                                        <th class="text-center"><?php echo display('product_name') ?></th>
                                        <th class="text-center"><?php echo display('strength') ?></th>
                                        <th class="text-center"><?php echo display('batch_id') ?></th>
                                        <th class="text-center"><?php echo display('expire_date') ?></th>
                                        <th class="text-center"><?php echo display('in_qnty') ?></th>
                                        <th class="text-center"><?php echo display('out_qnty') ?></th>
                                        <th class="text-center"><?php echo display('stock') ?></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" style="text-align:right"><?php echo display('total') ?>:</th>
                                        <th id="total_inqnty"></th>
                                        <th id="total_outqnty"></th>
                                        <th id="total_stock"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php } ?>
    </section>
</div>

<script type="text/javascript">
$(document).ready(function() {
    var table = $('#checkList').DataTable({
        responsive: false, // Let CSS handle scrolling
        scrollX: false,
        autoWidth: false,
        "aaSorting": [[1, "asc"]],
        "columnDefs": [
            { "orderable": false, "targets": [0,1,2,3,4,5,6,7,8] }
        ],
        processing: true,
        serverSide: true,
        serverMethod: 'post',

        ajax: {
            url: '<?=base_url()?>Creport/Checkbatchstock'
        },

        lengthMenu: [[10, 25, 50, 100, 250, 500, -1], [10, 25, 50, 100, 250, 500, "All"]],
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tip",
        buttons: [
            { extend: "copy", className: "btn-sm prints", exportOptions: { columns: [0,1,2,3,4,5,6,7,8] } },
            { extend: "csv", className: "btn-sm prints", title: "Batch Wise Stock List", exportOptions: { columns: [0,1,2,3,4,5,6,7,8] } },
            { extend: "excel", className: "btn-sm prints", title: "Batch Wise Stock List", exportOptions: { columns: [0,1,2,3,4,5,6,7,8] } },
            { extend: "pdf", className: "btn-sm prints", title: "Batch Wise Stock List", exportOptions: { columns: [0,1,2,3,4,5,6,7,8] } },
            { extend: "print", className: "btn-sm prints", title: "Batch Wise Stock List", exportOptions: { columns: [0,1,2,3,4,5,6,7,8] } }
        ],

        columns: [
            { data: 'sl' },
            { data: 'stock_name' },
            { data: 'product_name' },
            { data: 'strength' },
            { data: 'batch_id' },
            { data: 'expeire_date' },
            { data: 'inqty', className: 'inQty' },
            { data: 'outqty', className: 'outQty' },
            { data: 'stock', className: 'stock' }
        ],

        footerCallback: function(row, data, start, end, display) {
            var api = this.api();

            var parseNum = function(i) {
                return typeof i === 'string' ? parseFloat(i.replace(/,/g, '')) || 0 : typeof i === 'number' ? i : 0;
            };

            var inQtyTotal = api.column('.inQty', {page:'current'}).data().reduce(function(a,b){ return parseNum(a)+parseNum(b); },0);
            var outQtyTotal = api.column('.outQty', {page:'current'}).data().reduce(function(a,b){ return parseNum(a)+parseNum(b); },0);
            var stockTotal = api.column('.stock', {page:'current'}).data().reduce(function(a,b){ return parseNum(a)+parseNum(b); },0);

            $('#total_inqnty').html(inQtyTotal.toFixed(2));
            $('#total_outqnty').html(outQtyTotal.toFixed(2));
            $('#total_stock').html(stockTotal.toFixed(2));
        }
    });
});
</script>


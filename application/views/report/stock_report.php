<!-- Stock report start -->
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
        min-width: 1000px; /* Adjust width based on your columns */
        white-space: nowrap;
    }
}
</style>


<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-note2"></i></div>
        <div class="header-title">
            <h1><?php echo display('stock_report') ?></h1>
            <small><?php echo display('all_stock_report') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('stock') ?></a></li>
                <li class="active"><?php echo display('stock_report') ?></li>
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

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title text-right">
                            <a class="btn btn-warning" href="#" onclick="printDiv('printableArea')"><?php echo display('print') ?></a>
                            <a class="btn btn-success" href="<?php echo base_url('Creport/exportCSV') ?>"><?php echo 'Export Csv'; ?></a>
                        </div>
                    </div>

                    <div class="panel-body panel-body-scroll">
                        <div id="printableArea" style="margin-left:2px;">
                            <table id="checkList" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?php echo display('sl') ?></th>
                                        <th class="text-center"><?php echo display('medicine_name') ?></th>
                                        <th class="text-center"><?php echo display('manufacturer_name') ?></th>
                                        <th class="text-center"><?php echo display('product_model') ?></th>
                                        <th class="text-center"><?php echo display('in_qnty') ?></th>
                                        <th class="text-center"><?php echo display('out_qnty') ?></th>
                                        <th class="text-center"><?php echo display('stock') ?></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" style="text-align:right"><?php echo display('total') ?>:</th>
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

    </section>
</div>


<script type="text/javascript">
$(document).ready(function() {
    var table = $('#checkList').DataTable({
        responsive: false, // Let CSS handle mobile scroll
        scrollX: false,
        autoWidth: false,
        "aaSorting": [[1, "asc"]],
        "columnDefs": [
            { "orderable": false, "targets": [0,1,2,3,4,5,6] }
        ],
        processing: true,
        serverSide: true,
        serverMethod: 'post',

        ajax: {
            url: '<?= base_url() ?>Creport/CheckList'
        },

        lengthMenu: [[10, 25, 50, 100, 250, 500], [10, 25, 50, 100, 250, 500]],
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tip",
        buttons: [
            { extend: "copy", className: "btn-sm prints", exportOptions: { columns: [0,1,2,3,4,5,6] } },
            { extend: "csv", className: "btn-sm prints", title: "Stock Report", exportOptions: { columns: [0,1,2,3,4,5,6] } },
            { extend: "excel", className: "btn-sm prints", title: "Stock Report", exportOptions: { columns: [0,1,2,3,4,5,6] } },
            { extend: "pdf", className: "btn-sm prints", title: "Stock Report", exportOptions: { columns: [0,1,2,3,4,5,6] } },
            { extend: "print", className: "btn-sm prints", title: "Stock Report", exportOptions: { columns: [0,1,2,3,4,5,6] } }
        ],

        columns: [
            { data: 'sl' },
            { data: 'product_name' },
            { data: 'manufacturer_name' },
            { data: 'product_model' },
            { data: 'totalPurchaseQnty', className: "inQty" },
            { data: 'totalSalesQnty', className: "outQty" },
            { data: 'stok_quantity', className: "stock" }
        ],

        footerCallback: function(row, data, start, end, display) {
            var api = this.api();

            function parseNum(val) {
                return typeof val === 'string'
                    ? parseFloat(val.replace(/,/g, '')) || 0
                    : typeof val === 'number'
                    ? val
                    : 0;
            }

            var inQtyTotal = api.column('.inQty', {page:'current'}).data().reduce((a,b)=>parseNum(a)+parseNum(b),0);
            var outQtyTotal = api.column('.outQty', {page:'current'}).data().reduce((a,b)=>parseNum(a)+parseNum(b),0);
            var stockTotal = api.column('.stock', {page:'current'}).data().reduce((a,b)=>parseNum(a)+parseNum(b),0);

            $('#total_inqnty').html(inQtyTotal.toFixed(2));
            $('#total_outqnty').html(outQtyTotal.toFixed(2));
            $('#total_stock').html(stockTotal.toFixed(2));
        }
    });
});
</script>




<!-- Print function -->
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

<style>
/* === Responsive Scroll (same as Stock Report) === */
@media (max-width: 768px) {
    .panel-body-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }

    .panel-body-scroll table {
        min-width: 1200px; /* adjust based on column count */
        white-space: nowrap;
    }
}

/* For buttons (optional consistent styling) */
.prints {
    background-color: #31B404;
    color: #fff;
}
</style>

<!-- ====================== SALES REPORT ====================== -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('sales_report') ?></h1>
            <small><?php echo display('total_sales_report') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('report') ?></a></li>
                <li class="active"><?php echo display('sales_report') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <?php if ($this->permission1->method('todays_report', 'read')->access()) { ?>
                        <a href="<?php echo base_url('Admin_dashboard/all_report') ?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-align-justify"></i> <?php echo display('todays_report') ?></a>
                    <?php } ?>
                    <?php if ($this->permission1->method('purchase_report', 'read')->access()) { ?>
                        <a href="<?php echo base_url('Admin_dashboard/todays_purchase_report') ?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"></i> <?php echo display('purchase_report') ?></a>
                    <?php } ?>
                    <?php if ($this->permission1->method('sales_report', 'read')->access()) { ?>
                        <a href="<?php echo base_url('Admin_dashboard/todays_sales_report') ?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"></i> <?php echo display('sales_report') ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php if ($this->permission1->method('sales_report', 'read')->access()) { ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php echo form_open('Admin_dashboard/retrieve_dateWise_SalesReports', array('class' => 'form-inline')) ?>
                            <?php date_default_timezone_set("Asia/Dhaka");
                            $today = date('Y-m-d'); ?>
                            <div class="form-group">
                                <label for="from_date"><?php echo display('start_date') ?></label>
                                <input type="text" name="from_date" class="form-control datepicker" id="from_date" placeholder="<?php echo display('start_date') ?>">
                            </div>

                            <div class="form-group">
                                <label for="to_date"><?php echo display('end_date') ?></label>
                                <input type="text" name="to_date" class="form-control datepicker" id="to_date" value="<?php echo $today ?>" placeholder="<?php echo display('end_date') ?>">
                            </div>

                            <button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
                            <a class="btn btn-warning" href="#" onclick="printDiv('printableArea')"><?php echo display('print') ?></a>
                            <?php echo form_close() ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================== SALES TABLE ================== -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-bd lobidrag">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4><?php echo display('sales_report') ?></h4>
                            </div>
                        </div>
                        <div class="panel-body panel-body-scroll">
                            <div id="printableArea">
                                <div class="text-center">
                                    {company_info}
                                    <h3>{company_name}</h3>
                                    <h4>{address}</h4>
                                    {/company_info}
                                    <h4><?php echo display('print_date') ?>: <?php echo date("d/m/Y h:i:s"); ?></h4>
                                </div>

                                <table id="SalesReportTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo display('date'); ?></th>
                                            <th><?php echo display('invoice'); ?></th>
                                            <th><?php echo display('stock_name'); ?></th>
                                            <th><?php echo display('customer_name'); ?></th>
                                            <th style="text-align:right;"><?php echo display('grand_total'); ?></th>
                                            <th style="text-align:right;"><?php echo display('paid_amount'); ?></th>
                                            <th style="text-align:right;"><?php echo display('due_amount'); ?></th>
                                            <th style="text-align:right;"><?php echo display('manufacturer_total'); ?></th>
                                            <th style="text-align:right;"><?php echo display('grand_profit'); ?></th>
                                            <th style="text-align:right;"><?php echo display('paid_profit'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($sales_report)) { ?>
                                            <?php foreach ($sales_report as $report) { ?>
                                                <tr>
                                                    <td><?php echo html_escape($report['sales_date']); ?></td>
                                                    <td><a href="<?php echo base_url() . 'Cinvoice/invoice_inserted_data/' . $report['invoice_id']; ?>"><?php echo html_escape($report['invoice']); ?></a></td>
                                                    <td><?php echo html_escape($report['stock_name']); ?></td>
                                                    <td><a href="<?php echo base_url() . 'Ccustomer/customerledger/' . $report['customer_id']; ?>"><?php echo html_escape($report['customer_name']); ?></a></td>
                                                    <td class="text-right"><?php echo ($position == 0) ? $currency . ' ' . number_format($report['total_amount'], 2) : number_format($report['total_amount'], 2) . ' ' . $currency; ?></td>
                                                    <td class="text-right"><?php echo ($position == 0) ? $currency . ' ' . number_format($report['paid_amount'], 2) : number_format($report['paid_amount'], 2) . ' ' . $currency; ?></td>
                                                    <td class="text-right"><?php echo ($position == 0) ? $currency . ' ' . number_format($report['due_amount'], 2) : number_format($report['due_amount'], 2) . ' ' . $currency; ?></td>
                                                    <td class="text-right"><?php echo ($position == 0) ? $currency . ' ' . number_format($report['total_manufacturer'], 2) : number_format($report['total_manufacturer'], 2) . ' ' . $currency; ?></td>
                                                    <td class="text-right"><?php echo ($position == 0) ? $currency . ' ' . number_format($report['grand_profit'], 2) : number_format($report['grand_profit'], 2) . ' ' . $currency; ?></td>
                                                    <td class="text-right"><?php echo ($position == 0) ? $currency . ' ' . number_format($report['paid_profit'], 2) : number_format($report['paid_profit'], 2) . ' ' . $currency; ?></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-right"><b>Total</b></th>
                                            <th id="total_grand_total" class="text-right"></th>
                                            <th id="total_paid" class="text-right"></th>
                                            <th id="total_due" class="text-right"></th>
                                            <th id="total_manufacturer" class="text-right"></th>
                                            <th id="total_grand_profit" class="text-right"></th>
                                            <th id="total_paid_profit" class="text-right"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="text-right"><?php echo $links ?></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </section>
</div>

<!-- ========== DataTables Script ========== -->
<script type="text/javascript">
$(document).ready(function() {
    const currency = "<?php echo $currency; ?>";
    const position = <?php echo $position; ?>;

    function formatCurrency(amount) {
        amount = parseFloat(amount).toFixed(2);
        return position === 0 ? currency + ' ' + amount : amount + ' ' + currency;
    }

    var table = $('#SalesReportTable').DataTable({
        responsive: false,
        scrollX: false,
        autoWidth: false,
        language: {
            emptyTable: "<?php echo display('no_data_found'); ?>"
        },
        footerCallback: function(row, data, start, end, display) {
            var api = this.api();

            function parseNum(val) {
                return typeof val === 'string'
                    ? parseFloat(val.replace(/[^0-9.-]+/g, '')) || 0
                    : typeof val === 'number'
                    ? val
                    : 0;
            }

            const total = idx => api.column(idx, { page: 'current' }).data().reduce((a,b)=>parseNum(a)+parseNum(b),0);

            $('#total_grand_total').html('<b>' + formatCurrency(total(4)) + '</b>');
            $('#total_paid').html('<b>' + formatCurrency(total(5)) + '</b>');
            $('#total_due').html('<b>' + formatCurrency(total(6)) + '</b>');
            $('#total_manufacturer').html('<b>' + formatCurrency(total(7)) + '</b>');
            $('#total_grand_profit').html('<b>' + formatCurrency(total(8)) + '</b>');
            $('#total_paid_profit').html('<b>' + formatCurrency(total(9)) + '</b>');
        }
    });
});
</script>

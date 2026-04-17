<!-- Product details page start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('product_report'); ?></h1>
            <small><?php echo display('product_sales_and_purchase_report'); ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home'); ?></a></li>
                <li><a href="#"><?php echo display('report'); ?></a></li>
                <li class="active"><?php echo display('product_report'); ?></li>
            </ol>
        </div>
    </section>

    <section class="content">

        <!-- Alert Message -->
        <?php if ($message = $this->session->userdata('message')): ?>
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?= $message ?>
            </div>
            <?php $this->session->unset_userdata('message'); ?>
        <?php endif; ?>

        <?php if ($error_message = $this->session->userdata('error_message')): ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?= $error_message ?>
            </div>
            <?php $this->session->unset_userdata('error_message'); ?>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <a href="<?= base_url('Cproduct') ?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-plus"></i> <?= display('add_product') ?></a>
                    <a href="<?= base_url('Cproduct/add_product_csv') ?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-plus"></i> <?= display('add_product_csv') ?></a>
                    <a href="<?= base_url('Cproduct/manage_product') ?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"></i> <?= display('manage_product') ?></a>
                </div>
            </div>
        </div>

        <?php if ($this->permission1->method('manage_medicine','read')->access()): ?>

        <!-- Product Details -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?= display('product_details'); ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <h2><span style="font-weight:normal;"><?= display('product_name') ?>: </span><span style="color:#005580;"><?= $product_name ?? '' ?></span></h2>
                        <h4><span style="font-weight:normal;"><?= display('model') ?>:</span> <span style="color:#005580;"><?= $product_model ?? '' ?></span></h4>
                        <table class="table">
                            <tr>
                                <th><?= display('total_purchase') ?> = <span style="color:#ff0000;"><?= $total_purchase ?? '0' ?></span></th>
                                <th><?= display('total_sales') ?> = <span style="color:#ff0000;"><?= $total_sales ?? '0' ?></span></th>
                                <th><?= display('stock') ?> = <span style="color:#ff0000;"><?= $stock ?? '0' ?></span></th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Purchase Report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?= display('purchase_report'); ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="purchaseTable" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><?= display('date'); ?></th>
                                        <th><?= display('invoice_no'); ?></th>
                                        <th><?= display('invoice_id'); ?></th>
                                        <th><?= display('stock_name'); ?></th>
                                        <th><?= display('manufacturer_name'); ?></th>
                                        <th><?= display('quantity'); ?></th>
                                        <th><?= display('purchase_price'); ?></th>
                                        <th style="text-align:right;"><?= display('total_ammount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($purchaseData)): ?>
                                        <?php foreach ($purchaseData as $purchase): ?>
                                            <tr>
                                                <td><?= $purchase['final_date']; ?></td>
                                                <td><a href="<?= base_url("Cpurchase/purchase_details_data/{$purchase['purchase_id']}") ?>"><?= $purchase['chalan_no'] ?></a></td>
                                                <td><a href="<?= base_url("Cpurchase/purchase_details_data/{$purchase['purchase_id']}") ?>"><?= $purchase['purchase_id'] ?></a></td>
                                                <td><?= $purchase['stock_name'] ?></td>
                                                <td><a href="<?= base_url("Cmanufacturer/manufacturer_details/{$purchase['manufacturer_id']}") ?>"><?= $purchase['manufacturer_name'] ?></a></td>
                                                <td style="text-align:right;"><?= $purchase['quantity'] ?></td>
                                                <td style="text-align:right;"><?= ($position==0?"$currency {$purchase['rate']}":"{$purchase['rate']} $currency") ?></td>
                                                <td style="text-align:right;"><?= ($position==0?"$currency {$purchase['total_amount']}":"{$purchase['total_amount']} $currency") ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" style="text-align:right;"><b><?= display('total'); ?></b></th>
                                        <th style="text-align:right;"></th>
                                        <th style="text-align:right;"></th>
                                        <th style="text-align:right;"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?= display('sales_report'); ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="salesTable" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><?= display('date'); ?></th>
                                        <th><?= display('invoice_id'); ?></th>
                                        <th><?= display('invoice_no'); ?></th>
                                        <th><?= display('stock_name'); ?></th>
                                        <th><?= display('customer_name'); ?></th>
                                        <th><?= display('quantity'); ?></th>
                                        <th><?= display('rate'); ?></th>
                                        <th><?= display('manufacturer_rate'); ?></th>
                                        <th style="text-align:right;"><?= display('total_ammount'); ?></th>
                                        <th><?= display('grand_profit'); ?></th>
                                         <th><?= display('sales_by'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($salesData)): ?>
                                        <?php foreach ($salesData as $sale): ?>
                                            <tr>
                                                <td><?= $sale['final_date'] ?></td>
                                                <td><a href="<?= base_url("Cinvoice/invoice_inserted_data/{$sale['invoice_id']}") ?>"><?= $sale['invoice_id'] ?></a></td>
                                                <td><a href="<?= base_url("Cinvoice/invoice_inserted_data/{$sale['invoice_id']}") ?>"><?= $sale['invoice'] ?></a></td>
                                                <td><?= $sale['stock_name'] ?></td>
                                                <td><a href="<?= base_url("Ccustomer/customer_ledger/{$sale['customer_id']}") ?>"><?= $sale['customer_name'] ?></a></td>
                                                <td style="text-align:right;"><?= $sale['quantity'] ?></td>
                                                <td style="text-align:right;"><?= ($position==0?"$currency {$sale['rate']}":"{$sale['rate']} $currency") ?></td>
                                                <td style="text-align:right;"><?= ($position==0?"$currency {$sale['manufacturer_price_formatted']}":"{$sale['manufacturer_price_formatted']} $currency") ?></td>
                                                <td style="text-align:right;"><?= ($position==0?"$currency {$sale['total_price']}":"{$sale['total_price']} $currency") ?></td>
                                                <td style="text-align:right;"><?= ($position==0?"$currency {$sale['grand_profit']}":"{$sale['grand_profit']} $currency") ?></td>
                                                <td style="text-align:left;"><?= !empty($sale['sale_by']) ? $sale['sale_by'] : '-' ?></td>

                                                
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" style="text-align:right;"><?= display('total'); ?></th>
                                        <th style="text-align:right;"></th>
                                        <th style="text-align:right;"></th>
                                        <th style="text-align:right;"></th>
                                        <th style="text-align:right;"></th>
                                        <th style="text-align:left;" colspan="2"></th>
                                       
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php else: ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-bd lobidrag">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4><?= display('You do not have permission to access. Please contact with administrator.'); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </section>
</div>

<!-- DataTables Scripts -->
<script>
function parseValue(i) {
    if (typeof i === 'string') i = i.replace(/[^0-9.\-]+/g,'');
    var f = parseFloat(i);
    return isNaN(f) ? 0 : f;
}

$(document).ready(function() {
    $('#purchaseTable').DataTable({
        "footerCallback": function(row, data, start, end, display){
            var api = this.api();
            [5,6,7].forEach(function(idx){
                var total = api.column(idx, {page:'current'}).data().reduce(function(a,b){
                    return parseValue(a)+parseValue(b);
                }, 0);
                $(api.column(idx).footer()).html(total.toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2}));
            });
        }
    });

    $('#salesTable').DataTable({
        "footerCallback": function(row, data, start, end, display){
            var api = this.api();
            [5,6,7,8,9].forEach(function(idx){
                var total = api.column(idx, {page:'current'}).data().reduce(function(a,b){
                    return parseValue(a)+parseValue(b);
                }, 0);
                $(api.column(idx).footer()).html(total.toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2}));
            });
        }
    });
});
</script>
<!-- Product details page end -->

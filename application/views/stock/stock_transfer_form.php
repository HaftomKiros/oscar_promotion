<!-- Stock Transfer Page -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('stock'); ?></h1>
            <small><?php echo display('stock_transfer'); ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home'); ?></a></li>
                <li><a href="#"><?php echo display('stock'); ?></a></li>
                <li class="active"><?php echo display('stock_transfer'); ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
        <?php
        if ($message = $this->session->userdata('message')) {
            echo '<div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'
                    . $message . '</div>';
            $this->session->unset_userdata('message');
        }
        if ($error_message = $this->session->userdata('error_message')) {
            echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'
                    . $error_message . '</div>';
            $this->session->unset_userdata('error_message');
        }
        ?>

        <?php if ($this->permission1->method('stock_transfer', 'create')->access()) { ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-bd lobidrag">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4><?php echo display('stock_transfer'); ?></h4>
                            </div>
                        </div>

                        <?php echo form_open_multipart('Creport/insert_stock_transfer', ['id' => 'insert_stock_transfer']); ?>
                        <div class="panel-body">

                            <!-- From Stock -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?php echo display('from_stock'); ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <select name="stock_name" id="stock_id" class="form-control" required>
                                        <option value=""><?php echo display('select_stock'); ?></option>
                                        <?php
                                        if (!empty($all_stock)) {
                                            foreach ($all_stock as $stock) {
                                                echo "<option value='{$stock['id']}'>{$stock['stock_name']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <!-- To Stock -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?php echo display('to_stock'); ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <select name="to_stock_id" id="to_stock" class="form-control" required>
                                        <option value=""><?php echo display('select_stock'); ?></option>
                                        <?php
                                        if (!empty($to_stocks)) {
                                            foreach ($to_stocks as $stock) {
                                                echo "<option value='{$stock['id']}'>{$stock['stock_name']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Product -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?php echo display('product_name'); ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <select name="product_id" id="product_id" class="form-control" required>
                                        <option value=""><?php echo display('select_product'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <!-- Batch -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?php echo display('batch_id'); ?><i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <select name="batch_id" id="batch_id" class="form-control" required>
                                        <option value=""><?php echo display('select_batch'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <!-- Invoice Id -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?php echo display('invoice'); ?><i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <select name="invoice_id" id="invoice_id" class="form-control" required>
                                        <option value=""><?php echo display('select_invoice_id'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <!-- Available Quantity -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?php echo display('available_quantity'); ?></label>
                                <div class="col-sm-9">
                                    <input type="number" name="available_qty" id="available_qty" class="form-control" readonly>
                                </div>
                            </div>

                            <!-- Transfer Quantity -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?php echo display('transfer_quantity'); ?> <i class="text-danger">*</i></label>
                                <div class="col-sm-9">
                                    <input type="number" name="transfer_qty" id="transfer_qty" class="form-control" placeholder="<?php echo display('enter_quantity'); ?>" min="1" required>
                                </div>
                            </div>
                            <!-- Note -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?php echo display('note'); ?></label>
                                <div class="col-sm-9">
                                    <textarea name="transfer_note" id="transfer_note" class="form-control" placeholder="<?php echo display('optional_note'); ?>"></textarea>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="form-group row">
                                <div class="col-sm-9 offset-sm-3">
                                    <input type="submit" id="transfer-stock" class="btn btn-primary btn-large" name="transfer-stock" value="<?php echo display('save'); ?>">
                                </div>
                            </div>

                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="alert alert-danger">
                <h4><?php echo display('You do not have permission to access. Please contact the administrator.'); ?></h4>
            </div>
        <?php } ?>
    </section>
</div>

<!-- JavaScript -->
<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    // Prevent selecting the same stock
    $('#to_stock').on('change', function() {
        var fromStock = $('#stock_id').val();
        var toStock = $(this).val();
        if (fromStock === toStock && fromStock !== "") {
            alert("The 'From Stock' and 'To Stock' cannot be the same. Please select different stocks.");
            $(this).val('').trigger('change');
        }
    });

    // Fetch products when From Stock changes
    $('#stock_id').on('change', function() {
        var stockId = $(this).val();
        if(stockId) {
            $.ajax({
                url: '<?=base_url("Creport/fetch_products")?>',
                type: 'POST',
                data: { stock_id: stockId },
                dataType: 'json',
                success: function(products) {
                    var productSelect = $('#product_id');
                    productSelect.empty().append("<option value=''><?php echo display('select_product'); ?></option>");
                    $.each(products, function(index, product) {
                        productSelect.append($('<option>', { value: product.product_id, text: product.product_name }));
                    });
                    // Clear batches and invoices
                    $('#batch_id').html("<option value=''><?php echo display('select_batch'); ?></option>");
                    $('#invoice_id').html("<option value=''><?php echo display('select_invoice_id'); ?></option>");
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching products:', error);
                }
            });
        } else {
            $('#product_id').html("<option value=''><?php echo display('select_product'); ?></option>");
            $('#batch_id').html("<option value=''><?php echo display('select_batch'); ?></option>");
            $('#invoice_id').html("<option value=''><?php echo display('select_invoice_id'); ?></option>");
        }
    });

    // Fetch batches when Product changes
    $('#product_id').on('change', function() {
        var productId = $(this).val();
        var stockId   = $('#stock_id').val();

        if(productId && stockId) {
            $.ajax({
                url: '<?=base_url("Creport/fetch_batches")?>',
                type: 'POST',
                data: { product_id: productId, stock_id: stockId },
                dataType: 'json',
                success: function(batches) {
                    var batchSelect = $('#batch_id');
                    batchSelect.empty().append("<option value=''><?php echo display('select_batch'); ?></option>");
                    $.each(batches, function(index, batch) {
                        batchSelect.append($('<option>', {
                            value: batch.batch_id,
                            text: batch.batch_id
                        }));
                    });
                    // Clear invoice dropdown
                    $('#invoice_id').html("<option value=''><?php echo display('select_invoice_id'); ?></option>");
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching batches:', error);
                }
            });
        } else {
            $('#batch_id').html("<option value=''><?php echo display('select_batch'); ?></option>");
            $('#invoice_id').html("<option value=''><?php echo display('select_invoice_id'); ?></option>");
        }
    });

    // Fetch invoices when Batch changes
    $('#batch_id').on('change', function() {
        var productId = $('#product_id').val();
        var stockId   = $('#stock_id').val();
        var batchId   = $(this).val();

        if(productId && stockId && batchId) {
            $.ajax({
                url: '<?=base_url("Creport/fetch_invoices")?>',
                type: 'POST',
                data: { product_id: productId, stock_id: stockId, batch_id: batchId },
                dataType: 'json',
                success: function(invoices) {
                    var invoiceSelect = $('#invoice_id');
                    invoiceSelect.empty().append("<option value=''><?php echo display('select_invoice_id'); ?></option>");
                    $.each(invoices, function(index, invoice) {
                        invoiceSelect.append($('<option>', {
                            value: invoice.invoice_id,
                            text: invoice.invoice_id
                        }));
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching invoices:', error);
                }
            });
        } else {
            $('#invoice_id').html("<option value=''><?php echo display('select_invoice_id'); ?></option>");
        }
    });
    // Fetch available quantity when invoice changes
$('#invoice_id').on('change', function() {
    var productId = $('#product_id').val();
    var stockId   = $('#stock_id').val();
    var batchId   = $('#batch_id').val();
    var invoiceId = $(this).val();

    if(productId && stockId && batchId && invoiceId) {
        $.ajax({
            url: '<?=base_url("Creport/fetch_available_quantity")?>',
            type: 'POST',
            data: { 
                product_id: productId,
                stock_id: stockId,
                batch_id: batchId,
                invoice_id: invoiceId
            },
            dataType: 'json',
            success: function(data) {
                if(data.available_qty !== undefined) {
                    $('#available_qty').val(data.available_qty);
                } else {
                    $('#available_qty').val(0);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching available quantity:', error);
                $('#available_qty').val(0);
            }
        });
    } else {
        $('#available_qty').val(0);
    }
});
 $('#transfer_qty').on('focusout', function() {
    var availableQty = parseFloat($('#available_qty').val()) || 0;
    var transferQty  = parseFloat($(this).val()) || 0;

    if (transferQty <= 0) {
        alert("Transfer quantity must be greater than 0.");
        $(this).val(''); // clear input
        // optionally add a CSS highlight instead of refocusing
        $(this).addClass('is-invalid');
    } else if (transferQty > availableQty) {
        alert("Transfer quantity cannot be greater than available quantity (" + availableQty + ").");
        $(this).val(''); // clear input
        $(this).addClass('is-invalid');
    } else {
        $(this).removeClass('is-invalid'); // remove highlight if valid
    }
});



});
</script>


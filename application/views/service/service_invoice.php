<!-- Manage service Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('service') ?></h1>
            <small><?php echo display('manage_service_invoice') ?></small>
            <ol class="breadcrumb">
                <li><a href=""><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('service') ?></a></li>
                <li class="active"><?php echo display('manage_service_invoice') ?></li>
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

        <!-- Manage service -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-3">
                            <input type="text" id="from_date" class="form-control datepicker" placeholder="From Service Date">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="to_date" class="form-control datepicker" placeholder="To Service Date">
                        </div>
                        <div class="col-sm-3">
                            <button id="btn_filter" class="btn btn-success">Filter</button>
                        </div>
                        <br><br><br>
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?php echo display('sl') ?></th>
                                        <th class="text-center"><?php echo display('stock_name') ?></th>
                                        <th class="text-center"><?php echo display('customer_name') ?></th>
                                        <th class="text-center"><?php echo display('total_amount') ?></th>
                                        <th class="text-center"><?php echo display('paid_amount') ?></th>
                                        <th class="text-center"><?php echo display('due') ?></th>
                                        <th class="text-center"><?php echo display('employee') ?></th>
                                        <th class="text-center"><?php echo display('created_by')?></th>
                                        <th class="text-center"><?php echo display('date') ?></th>
                                        <th class="text-center"><?php echo display('description') ?></th>
                                        <th class="text-center"><?php echo display('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($service) {
                                        $sl=1;
                                        foreach ($service as $services) {
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $sl;?></td>
                                            <td class="text-center"><?php echo $services['stock_name'];?></td>
                                            <td class="text-center"><?php echo $services['customer_name'];?></td>
                                            <td class="text-center"><?php echo $services['total_amount'];?></td>
                                            <td class="text-center"><?php echo $services['paid_amount'];?></td>
                                            <td class="text-center"><?php echo $services['total_amount']-$services['paid_amount'];?></td>
                                            <td class="text-center"><?php echo html_escape($services['fname'] . ' ' . $services['lname']); ?></td>
                                            <td class="text-center"><?php echo html_escape($services['first_name'] . ' ' . $services['last_name']); ?></td>
                                            <td class="text-center"><?php echo $services['date'];?></td>
                                            <td class="text-center"><?php echo $services['details'];?></td>
                                            <td>
                                                <center>
                                                    <?php echo form_open() ?>
                                                    <?php if($this->permission1->method('manage_service','update')->access()){ ?>
                                                        <a href="<?php echo base_url() . 'Cservice/service_invoice_update_form/'.$services['voucher_no']; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                    <?php }?>
                                                    <?php if($this->permission1->method('manage_service','delete')->access()){ ?>
                                                        <a href="<?php echo base_url() . 'Cservice/service_invoic_delete/'.$services['voucher_no']; ?>" class="btn btn-danger btn-sm" name="<?php echo $services['voucher_no'];?>" onclick="return confirm('<?php echo display('are_you_sure') ?>')" data-original-title="<?php echo display('delete') ?> "><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                    <?php }?>
                                                    <a href="<?php echo base_url() . 'Cservice/service_invoice_data/'.$services['voucher_no']; ?>" class="btn btn-success btn-sm" name="<?php echo $services['voucher_no'];?>"  data-original-title="<?php echo display('details') ?> "><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                    <?php echo form_close() ?>
                                                </center>
                                            </td>
                                        </tr>
                                        <?php $sl++; }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">Total:</th>
                                        <th class="text-center" id="total_amount_sum"></th>
                                        <th class="text-center" id="paid_amount_sum"></th>
                                        <th class="text-center" id="due_sum"></th>
                                        <th colspan="5"></th>
                                    </tr>
                                </tfoot>
                            </table>
                            <?php echo $links ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Manage service End -->

<script>
$(document).ready(function() {
    // Function to update footer totals
    function updateTotals() {
        var total_amount_sum = 0;
        var paid_amount_sum = 0;
        var due_sum = 0;

        $('#dataTableExample tbody tr').each(function(){
            var total = parseFloat($(this).find('td').eq(3).text()) || 0;
            var paid = parseFloat($(this).find('td').eq(4).text()) || 0;
            total_amount_sum += total;
            paid_amount_sum += paid;
            due_sum += (total - paid);
        });

        $('#total_amount_sum').text(total_amount_sum.toFixed(2));
        $('#paid_amount_sum').text(paid_amount_sum.toFixed(2));
        $('#due_sum').text(due_sum.toFixed(2));
    }

    // Initialize DataTable
    var table = $('#dataTableExample').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "order": [[0, "asc"]],
        "lengthMenu": [10, 25, 50, 100],
        "columnDefs": [
            { "orderable": false, "targets": -1 } // Action column
        ]
    });

    // Update totals on page load
    updateTotals();

    // Filter button click
    $('#btn_filter').click(function() {
        var from_date = $('#from_date').val();
        var to_date   = $('#to_date').val();

        if(from_date == '' || to_date == ''){
            alert('Please select both From and To dates!');
            return;
        }

        $.ajax({
            url: "<?php echo base_url('Cservice/manage_service_invoice') ?>",
            type: "POST",
            dataType: "json",
            data: {from_date: from_date, to_date: to_date},
            success: function(data){
                table.clear().draw();

                var sl = 1;
                data.forEach(function(row){
                    var actions = `
                        <center>
                            <?php if($this->permission1->method('manage_service','update')->access()){ ?>
                                <a href="<?php echo base_url() ?>Cservice/service_invoice_update_form/${row.voucher_no}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
                            <?php } ?>
                            <?php if($this->permission1->method('manage_service','delete')->access()){ ?>
                                <a href="<?php echo base_url() ?>Cservice/service_invoic_delete/${row.voucher_no}" class="btn btn-danger btn-sm" onclick="return confirm('<?php echo display('are_you_sure') ?>')"><i class="fa fa-trash-o"></i></a>
                            <?php } ?>
                            <a href="<?php echo base_url() ?>Cservice/service_invoice_data/${row.voucher_no}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a>
                        </center>
                    `;

                    table.row.add([
                        sl,
                        row.stock_name,
                        row.customer_name,
                        row.total_amount,
                        row.paid_amount,
                        row.total_amount - row.paid_amount,
                        row.fname + ' ' + row.lname,
                        row.first_name + ' ' + row.last_name,
                        row.date,
                        row.details,
                        actions
                    ]).draw(false);

                    sl++;
                });

                // Update totals after filtering
                updateTotals();
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert('Failed to fetch filtered data!');
                console.log(jqXHR.responseText);
            }
        });
    });
});
</script>

<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/employee.js.php"></script>
<!-- Manage Expense Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('expense') ?></h1>
            <small><?php echo display('manage_expense') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('expense') ?></a></li>
                <li class="active"><?php echo display('manage_expense') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
        <?php
        $message = $this->session->userdata('message');
        if (isset($message)) {
            echo '<div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$message.'
                  </div>';
            $this->session->unset_userdata('message');
        }

        $error_message = $this->session->userdata('error_message');
        if (isset($error_message)) {
            echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$error_message.'
                  </div>';
            $this->session->unset_userdata('error_message');
        }
        ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('manage_expense') ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">

                        <!-- 🔍 Date Range Filter -->
                        <div class="row" style="margin-bottom: 15px;">
                            <div class="col-sm-3">
                                <input type="date" id="minDate" class="form-control" placeholder="From Date">
                            </div>
                            <div class="col-sm-3">
                                <input type="date" id="maxDate" class="form-control" placeholder="To Date">
                            </div>
                            <div class="col-sm-3">
                                <button id="filterDate" class="btn btn-success">Filter</button>
                                <button id="resetDate" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                        <!-- 🔍 End Date Filter -->

                        <div class="table-responsive">
                            <table id="dataTableExample2" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><?php echo display('date') ?></th>
                                        <th><?php echo display('stock_name') ?></th>
                                        <th><?php echo display('type') ?></th>
                                        <th class="text-right"><?php echo display('amount') ?></th>
                                        <th><?php echo display('description') ?></th>
                                        <th><?php echo display('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($expense_list) {
                                        foreach ($expense_list as $expense) { ?>
                                            <tr>
                                                <td><?php echo date('Y-m-d', strtotime($expense['date'])); ?></td>
                                                <td><?php echo $expense['stock_name']; ?></td>
                                                <td><?php echo $expense['type'].' Expense'; ?></td>
                                                <td class="text-right"><?php echo number_format($expense['amount'],2); ?></td>
                                                <td><?php echo $expense['description']; ?></td>
                                                <td>
                                                    <?php if($this->permission1->method('manage_expense','delete')->access()){ ?>
                                                      <a href="<?php echo base_url("Cexpense/delete_expense/".$expense['voucher_no']) ?>" 
                                                         class="btn btn-sm btn-danger" 
                                                         onclick="return confirm('<?php echo display('are_you_sure') ?>') ">
                                                         <i class="fa fa-trash"></i>
                                                      </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                    <?php }
                                    } else { ?>
                                        <tr><td colspan="6" class="text-center">No Record Found</td></tr>
                                    <?php } ?>
                                </tbody>

                                <!-- ✅ Dynamic Footer -->
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">Total:</th>
                                        <th class="text-right"></th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="text-center"><?php echo $links ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {

    var table = $('#dataTableExample2').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        ordering: true,
        searching: true,
        paging: true,
        info: true,
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries"
        },
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();

            // 🔢 Helper: Parse numbers
            var parseValue = function (i) {
                if (typeof i === 'string') {
                    return parseFloat(i.replace(/[^0-9.\-]/g, '')) || 0;
                } else if (typeof i === 'number') {
                    return i;
                } else {
                    return 0;
                }
            };

            // 💰 Calculate total for "Amount" column (index 3)
            var total = api
                .column(3, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return parseValue(a) + parseValue(b);
                }, 0);

            // 🧮 Display total formatted in footer
            $(api.column(3).footer()).html(total.toLocaleString(undefined, { minimumFractionDigits: 2 }));
        }
    });

    // === Custom Date Range Filter ===
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var min = $('#minDate').val();
            var max = $('#maxDate').val();
            var dateCol = data[0]; // date column
            if (!dateCol) return true;
            var date = new Date(dateCol);
            if (isNaN(date)) return true;

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

    // Filter and Reset buttons
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

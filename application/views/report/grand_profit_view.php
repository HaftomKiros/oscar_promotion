<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<div class="content-wrapper">
    <section class="content-header">
        <div class="header-title">
            <h1><?php echo display('grand_profit_summary'); ?></h1>
        </div>
    </section>

    <section class="content">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <h4><?php echo display('summary_report'); ?></h4>
            </div>

            <div class="panel-body">
                <table id="grandProfitTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('report'); ?></th>
                            <th><?php echo display('amount'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo display('total_purchase'); ?></td>
                            <td><?php echo ($position == 0) ? "$currency $dashboard_purchase" : "$dashboard_purchase $currency"; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo display('total_sales'); ?></td>
                            <td><?php echo ($position == 0) ? "$currency $dashboard_sales" : "$dashboard_sales $currency"; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo display('paid_amount'); ?></td>
                            <td><?php echo ($position == 0) ? "$currency $dashboard_paid_total" : "$dashboard_paid_total $currency"; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo display('total_expense_ammount'); ?></td>
                            <td><?php echo ($position == 0) ? "$currency $dashboard_expense" : "$dashboard_expense $currency"; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo display('total_paid_salary'); ?></td>
                            <td><?php echo ($position == 0) ? "$currency $dashboard_salary" : "$dashboard_salary $currency"; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo display('net_profit'); ?></td>
                            <td><?php echo ($position == 0) ? "$currency $dashboard_net_profit" : "$dashboard_net_profit $currency"; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo display('paid_profit'); ?></td>
                            <td><?php echo ($position == 0) ? "$currency $dashboard_paid_profit" : "$dashboard_paid_profit $currency"; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('#grandProfitTable').DataTable({
        paging: false,
        info: false,
        searching: true,
        ordering: false
    });
});
</script>

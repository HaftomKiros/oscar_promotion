<style>
    .prints {
        background-color: #31B404;
        color: #fff;
    }
</style>

<!-- Expense Type View -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-title">
            <h1>Expense Type</h1>
            <small>Date & Total Amount Summary</small>
        </div>
    </section>

    <section class="content">

        <!-- Date Filter -->
        <div class="panel panel-default">
           
        </div>

        <!-- Expense Table -->
        <div class="panel panel-bd lobidrag">
            <div class="panel-body">
                <div class="table-responsive">
                    


                    <table id="tableSearch" class="table table-striped table-bordered" width="100%">
    <thead>
        <tr>
            <th>SL</th>
             <th>Stock_name</th>
            <th>Date</th>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $total_amount = 0;
        if (!empty($details)) {
            foreach ($details as $key => $row) {
                $total_amount += $row->amount; // or $row['total_amount'] depending on your result format
                ?>
                <tr>
                    <td><?php echo $key + 1; ?></td>
                     <td><?php echo $row->stock_name; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($row->date)); ?></td>
                    <td><?php echo number_format($row->amount, 2); ?></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="3" class="text-center">No records found</td>
            </tr>
            <?php
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" style="text-align:right">Total:</th>
            <th><?php echo number_format($total_amount, 2); ?></th>
        </tr>
    </tfoot>
</table>

                </div>
            </div>
        </div>

    </section>
</div>

<!-- Scripts -->
<script>
$(document).ready(function() {
    var table = $('#tableSearch').DataTable();

    function updateTotal() {
        var total = 0;

        // Use the 4th column (index 3) for 'Total Amount'
        table.column(3, { search: 'applied' }).data().each(function(value, index) {
            // Remove commas and parse float
            var amount = parseFloat(value.toString().replace(/,/g, '')) || 0;
            total += amount;
        });

        // Update footer total with formatted number
        $(table.column(3).footer()).html(total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    }

    // Initial total update
    updateTotal();

    // Update total whenever table is drawn (pagination, search, sort)
    table.on('draw', function() {
        updateTotal();
    });
});
</script>




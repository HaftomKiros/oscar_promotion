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


<!-- All Report Start  -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('todays_report') ?></h1>
	        <small><?php echo display('todays_sales_and_purchase_report') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('report') ?></a></li>
	            <li class="active"><?php echo display('todays_report') ?></li>
	        </ol>
	    </div>
	</section>

	<div class="content">
		<div class="row">
            <div class="col-sm-12">
                <div class="column">

                 <?php
                    if($this->permission1->method('sales_report','read')->access()){ ?>
                        <a href="<?php echo base_url('Admin_dashboard/todays_sales_report')?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('sales_report')?> </a>
                    <?php } ?>

                    <?php
                    if($this->permission1->method('purchase_report','read')->access()){ ?>
                        <a href="<?php echo base_url('Admin_dashboard/todays_purchase_report')?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i>  <?php echo display('purchase_report')?> </a>
                    <?php } ?>

                    <?php
                    if($this->permission1->method('sales_report_medicine_wise','read')->access()){ ?>
                        <a href="<?php echo base_url('Admin_dashboard/product_sales_reports_date_wise')?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-align-justify"> </i>  <?php echo display('sales_report_product_wise')?> </a>
                    <?php } ?>

                </div>
            </div>
        </div>
        <?php
        if($this->permission1->method('todays_report','read')->access()){ ?>
		<!-- Todays sales report -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('todays_sales_report') ?> </h4>
		                    <p class="text-right">
		                    <a  class="btn btn-warning btn-sm" href="#" onclick="printDiv('printableArea')"><?php echo display('print') ?></a>
		                </p>
		                </div>
		            </div>
		            <div class="panel-body">
		            	<div id="printableArea" style="margin-left:2px;">
			            	<div class="text-center">
								{company_info}
								<h3> {company_name} </h3>
								<h4 >{address} </h4>
								{/company_info}
								<h4> <?php echo display('print_date') ?>: <?php echo date("d/m/Y h:i:s"); ?> </h4>
							</div>
			                <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="SalesReportTable">
    <thead>
        <tr>
            <th><?php echo display('date'); ?></th>
            <th><?php echo display('invoice'); ?></th>
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
                    <td>
                        <a href="<?php echo base_url().'Cinvoice/invoice_inserted_data/'.$report['invoice_id']; ?>">
                            <?php echo html_escape($report['invoice']); ?>
                        </a>
                    </td>
                    <td>
                        <a href="<?php echo base_url().'Ccustomer/customerledger/'.$report['customer_id']; ?>">
                            <?php echo html_escape($report['customer_name']); ?>
                        </a>
                    </td>
                    <td style="text-align: right;">
                        <?php echo ($position == 0) ? $currency . ' ' . number_format($report['total_amount'], 2) : number_format($report['total_amount'], 2) . ' ' . $currency; ?>
                    </td>
                    <td style="text-align: right;">
                        <?php echo ($position == 0) ? $currency . ' ' . number_format($report['paid_amount'], 2) : number_format($report['paid_amount'], 2) . ' ' . $currency; ?>
                    </td>
                    <td style="text-align: right;">
                        <?php echo ($position == 0) ? $currency . ' ' . number_format($report['due_amount'], 2) : number_format($report['due_amount'], 2) . ' ' . $currency; ?>
                    </td>
                    <td style="text-align: right;">
                        <?php echo ($position == 0) ? $currency . ' ' . number_format($report['total_manufacturer'], 2) : number_format($report['total_manufacturer'], 2) . ' ' . $currency; ?>
                    </td>
                    <td style="text-align: right;">
                        <?php echo ($position == 0) ? $currency . ' ' . number_format($report['grand_profit'], 2) : number_format($report['grand_profit'], 2) . ' ' . $currency; ?>
                    </td>
                    <td style="text-align: right;">
                        <?php echo ($position == 0) ? $currency . ' ' . number_format($report['paid_profit'], 2) : number_format($report['paid_profit'], 2) . ' ' . $currency; ?>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="9" class="text-center text-danger"><?php echo display('no_data_found'); ?></td></tr>
        <?php } ?>
    </tbody>
  <tfoot>
    <tr>
        <td colspan="3" style="text-align: right;"><b>Total</b></td>
        <td style="text-align: right;"></td> <!-- total_sales -->
        <td style="text-align: right;"></td> <!-- total_paid -->
        <td style="text-align: right;"></td> <!-- total_due -->
        <td style="text-align: right;"></td> <!-- manufacturer_total -->
        <td style="text-align: right;"></td> <!-- grand_profit -->
        <td style="text-align: right;"></td> <!-- paid_profit -->
    </tr>
</tfoot>


</table>

</div>

			            </div>
		            </div>
		        </div>
		    </div>
		</div>

		<!-- Todays purchase report -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('todays_purchase_report') ?></h4>
		                    	<p class="text-right">
		                    <a  class="btn btn-warning" href="#" onclick="printDiv('purchase_div')"><?php echo display('print') ?></a>
		                </p>
		                </div>
		            </div>
		            <div class="panel-body">
		            

		                <div id="purchase_div" style="margin-left:2px;">
		                	<div class="text-center">
								{company_info}
								<h3> {company_name} </h3>
								<h4 >{address} </h4>
								{/company_info}
								<h4> <?php echo display('stock_date') ?> : {date} </h4>
								<h4> <?php echo display('print_date') ?>: <?php echo date("d/m/Y h:i:s"); ?> </h4>
							</div>
			                <div class="table-responsive">
			                    <table id="purchaseTable" class="table table-bordered table-striped table-hover">
			                        <thead>
			                            <tr>
			                                <th><?php echo display('purchase_date') ?></th>
											<th><?php echo display('invoice_no') ?></th>
											<th><?php echo display('manufacturer_name') ?></th>
											<th><?php echo display('total_amount') ?></th>
			                            </tr>
			                        </thead>
			                        <tbody>
			                        	<?php
			                        		if ($purchase_report) {
			                        	?>
			                            {purchase_report}
											<tr>
												<td>{prchse_date}</td>
												<td>
													<a href="<?php echo base_url().'Cpurchase/purchase_details_data/{purchase_id}'; ?>">
														{chalan_no}
													</a>
												</td>
												<td>{manufacturer_name}</td>
												<td style="text-align: right;"><?php echo (($position==0)?"$currency {grand_total_amount}":"{grand_total_amount} $currency") ?></td>
											</tr>
										{/purchase_report}
										<?php
											}
										?>
			                        </tbody>
			                        <tfoot>
										<tr>
											<td colspan="3" align="right" style="text-align:right;font-size:14px !Important">&nbsp; <b><?php echo display('total_purchase') ?>: </b></td>
											<td style="text-align: right;"><b><?php echo (($position==0)?"$currency {purchase_amount}":"{purchase_amount} $currency") ?></b></td>
										</tr>
									</tfoot>
			                    </table>
			                </div>
		            	</div>
		            </div>
		        </div>
		    </div>
		</div>
        <?php
        }
        else{
            ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-bd lobidrag">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4><?php echo display('You do not have permission to access. Please contact with administrator.');?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php
        }
        ?>
	</section>
</div>
</div>
</div>
<!-- All Report End -->
 <script>
$(document).ready(function() {
    const currency = "<?php echo $currency; ?>";
    const position = <?php echo $position; ?>;

    function formatCurrency(amount) {
        amount = parseFloat(amount).toFixed(2);
        return position === 0 ? currency + ' ' + amount : amount + ' ' + currency;
    }

    $('#SalesReportTable').DataTable({
        // Add other DataTables options here as needed
        responsive: true,
        autoWidth: false,
        footerCallback: function(row, data, start, end, display) {
            let api = this.api();

            // Helper to sum a column
            const getColumnTotal = function(index) {
                return api.column(index, { page: 'current' }).data().reduce(function(a, b) {
                    a = typeof a === 'string' ? parseFloat(a.replace(/[^0-9.-]+/g, '')) || 0 : a;
                    b = typeof b === 'string' ? parseFloat(b.replace(/[^0-9.-]+/g, '')) || 0 : b;
                    return a + b;
                }, 0);
            };

            // Calculate totals for each column
            const total_sales        = getColumnTotal(3);
            const total_paid         = getColumnTotal(4);
            const total_due          = getColumnTotal(5);
            const total_manufacturer = getColumnTotal(6);
            const grand_profit       = getColumnTotal(7);
            const paid_profit        = getColumnTotal(8);

            // Inject totals into footer cells
            $(api.column(3).footer()).html('<b>' + formatCurrency(total_sales) + '</b>');
            $(api.column(4).footer()).html('<b>' + formatCurrency(total_paid) + '</b>');
            $(api.column(5).footer()).html('<b>' + formatCurrency(total_due) + '</b>');
            $(api.column(6).footer()).html('<b>' + formatCurrency(total_manufacturer) + '</b>');
            $(api.column(7).footer()).html('<b>' + formatCurrency(grand_profit) + '</b>');
            $(api.column(8).footer()).html('<b>' + formatCurrency(paid_profit) + '</b>');
        }
    });
});
</script>

<script>
$(document).ready(function() {
    $('#purchaseTable').DataTable({
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api();

            // Helper function to parse currency values
            var parseValue = function (i) {
                return typeof i === 'string' ?
                    parseFloat(i.replace(/[^0-9.-]+/g, '')) :
                    typeof i === 'number' ? i : 0;
            };

            // Total over all pages
            total = api
                .column(3, { search: 'applied' })
                .data()
                .reduce(function (a, b) {
                    return parseValue(a) + parseValue(b);
                }, 0);

            // Update footer
            $(api.column(3).footer()).html(
                `<b><?php echo (($position==0)?"$currency ":"") ?>${total.toFixed(2)}<?php echo (($position==0)?"":" $currency") ?></b>`
            );
        }
    });
});
</script>




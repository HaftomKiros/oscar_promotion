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
@media (max-width: 768px) {
    .table-responsive-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }
    .table-responsive-scroll table {
        min-width: 1200px; /* Adjust width based on your columns */
        white-space: nowrap;
    }
}
</style>

<!-- Product Report Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('sales_report_product_wise') ?></h1>
	        <small><?php echo display('sales_report_product_wise') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="index.html"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('report') ?></a></li>
	            <li class="active"><?php echo display('sales_report_product_wise') ?></li>
	        </ol>
	    </div>
	</section>

	<section class="content">
		<div class="row">
            <div class="col-sm-12">
                <div class="column">

                    <?php
                    if($this->permission1->method('todays_report','read')->access()){ ?>
                        <a href="<?php echo base_url('Admin_dashboard/all_report')?>" class="btn btn-info m-b-5 m-r-2"><i class="ti-align-justify"> </i> <?php echo display('todays_report')?> </a>
                    <?php } ?>

                    <?php
                    if($this->permission1->method('sales_report','read')->access()){ ?>
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
        if($this->permission1->method('sales_report_medicine_wise','read')->access()){ ?>
		<!-- Product report -->
		<div class="row">
			<div class="col-sm-12">
		        <div class="panel panel-default">
		            <div class="panel-body"> 
		                <?php echo form_open('Admin_dashboard/product_sales_search_reports',array('class' => 'form-inline','method' => 'get'))?>
		                <?php date_default_timezone_set("Asia/Dhaka"); $today = date('Y-m-d'); ?>
		                    <div class="form-group">
		                        <label class="" for="from_date"><?php echo display('start_date') ?></label>
		                        <input type="text" name="from_date" class="form-control datepicker" id="from_date" placeholder="<?php echo display('start_date') ?>" value="<?php echo $start ;?>">
		                    </div> 

		                    <div class="form-group">
		                        <label class="" for="to_date"><?php echo display('end_date') ?></label>
		                        <input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo display('end_date') ?>" value="<?php echo $end ;?>">
		                    </div>  

		                    <button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
		                    <a  class="btn btn-warning" href="#" onclick="printDiv('purchase_div')"><?php echo display('print') ?></a>

		               <?php echo form_close()?>
		            </div>
		        </div>
		    </div>
	    </div>

		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('sales_report_product_wise') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
		            	<div id="purchase_div" style="margin-left:2px;">
			            	<div class="text-center">
								{company_info}
								<h3> {company_name} </h3>
								<h4 >{address} </h4>
								{/company_info}
								<h4> <?php echo display('print_date') ?>: <?php echo $start ;?>  To  <?php echo $end ;?></h4>
							</div>

			                <div class="table-responsive">
			                    <table id="productSalesReportTable" class="table table-bordered table-striped table-hover">

			                       <thead>
										<tr>
											<th><?php echo display('sales_date') ?></th>
											<th><?php echo display('product_name') ?></th>
                                            <th><?php echo display('stock_name') ?></th>
											<th><?php echo display('product_model') ?></th>
											<th><?php echo display('customer_name') ?></th>
											<th><?php echo display('quantity') ?></th>
											<th><?php echo display('rate') ?></th>
											<th><?php echo display('grand_total') ?></th>
											<th><?php echo display('manufacturer_rate') ?></th>
										
											<th><?php echo display('grand_profit') ?></th>
											
											
										</tr>
									</thead>
									<tbody>
									<?php
										if ($product_report) {
									?>
									<?php
                                       $sub_totalp = 0;
									 foreach($product_report as $rep){
                                        $sub_totalp +=$rep['total_price'];
										?>
                                        
										<tr>
											<td><?php echo $rep['sales_date'] ?></td>
											<td><?php echo $rep['product_name'] ?></td>
                                            <td><?php echo $rep['stock_name'] ?></td>
											<td><?php echo $rep['product_model'] ?></td>
											<td><?php echo $rep['customer_name'] ?></td>
											<td><?php echo $rep['quantity'] ?></td>
											<td style="text-align: right;"><?php echo (($position==0)?"$currency":"$currency");echo $rep['rate'] ?></td>
											<td style="text-align: right;"><?php echo (($position==0)?"$currency":"$currency");echo $rep['total_price'] ?></td>
											<td style="text-align: right;">
                                              <?php 
                                                  $manufacturer_total = $rep['quantity'] * $rep['manufacturer_rate'];
                                                  echo ($position == 0) ? "$currency " . number_format($manufacturer_total, 2) : number_format($manufacturer_total, 2) . " $currency";
                                              ?>
                                          </td>
											
											 
										  <td style="text-align: right;">
                                               <?php 
                                                   $manufacturer_total = $rep['quantity'] * $rep['manufacturer_rate'];
                                                   $grand_profit = $rep['total_price'] - $manufacturer_total;
                                                   echo ($position == 0) ? "$currency " . number_format($grand_profit, 2) : number_format($grand_profit, 2) . " $currency";
                                               ?>
                                         </td>
										 




										</tr>
									<?php
										}
									?>
									<?php
										}
									?>
									</tbody>
					<tfoot>
    <tr>
        <th colspan="5" style="text-align:right;"><?php echo display('totals') ?></th>
        <th style="text-align:right;"></th> <!-- Quantity -->
        <th style="text-align:right;"></th> <!-- Rate -->
        <th style="text-align:right;"></th> <!-- Grand Total -->
        <th style="text-align:right;"></th> <!-- Manufacturer Total -->
        <th style="text-align:right;"></th> <!-- Profit -->
    </tr>
</tfoot>




			                    </table>
			                </div>
			            </div>
		                <div class="text-right"><?php echo $links?></div>
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
 <!-- Product Report End -->

<script>
$(document).ready(function () {
    // Initialize DataTable
    var table = $('#productSalesReportTable').DataTable({
        responsive: false, // Disable DataTables responsive; use CSS scroll
        scrollX: false,    // Handled by CSS
        autoWidth: false,
        "aaSorting": [[0, "asc"]], // Sort by sales_date by default
        "columnDefs": [
            { "orderable": false, "targets": [0,1,2,3,4,5,6,7,8,9] }
        ],
        lengthMenu: [[10, 25, 50, 100, 250, 500], [10, 25, 50, 100, 250, 500]],
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tip",
        buttons: [
            { extend: "copy", className: "btn-sm prints", exportOptions: { columns: ':visible' } },
            { extend: "csv", className: "btn-sm prints", title: "Product Sales Report", exportOptions: { columns: ':visible' } },
            { extend: "excel", className: "btn-sm prints", title: "Product Sales Report", exportOptions: { columns: ':visible' } },
            { extend: "pdf", className: "btn-sm prints", title: "Product Sales Report", exportOptions: { columns: ':visible' } },
            { extend: "print", className: "btn-sm prints", title: "Product Sales Report", exportOptions: { columns: ':visible' } }
        ],
        footerCallback: function (row, data, start, end, display) {
    var api = this.api();

    // Helper to parse numbers from table cells
    var parseValue = function (i) {
        return typeof i === 'string' ?
            parseFloat(i.replace(/[^0-9.-]+/g, '')) || 0 :
            typeof i === 'number' ? i : 0;
    };

    // Column indices
    var quantityIdx = 5;
    var rateIdx = 6;
    var grandTotalIdx = 7;
    var manufacturerIdx = 8;
    var profitIdx = 9;

    // Calculate totals for current page
    var totalQuantity = api.column(quantityIdx, { page: 'current' }).data().reduce((a,b) => parseValue(a)+parseValue(b), 0);
    var totalRate = api.column(rateIdx, { page: 'current' }).data().reduce((a,b) => parseValue(a)+parseValue(b), 0);
    var totalGrand = api.column(grandTotalIdx, { page: 'current' }).data().reduce((a,b) => parseValue(a)+parseValue(b), 0);
    var totalManufacturer = api.column(manufacturerIdx, { page: 'current' }).data().reduce((a,b) => parseValue(a)+parseValue(b), 0);
    var totalProfit = api.column(profitIdx, { page: 'current' }).data().reduce((a,b) => parseValue(a)+parseValue(b), 0);

    // Display totals in footer
    $(api.column(quantityIdx).footer()).html(totalQuantity);
    $(api.column(rateIdx).footer()).html(totalRate.toFixed(2));
    $(api.column(grandTotalIdx).footer()).html(totalGrand.toFixed(2));
    $(api.column(manufacturerIdx).footer()).html(totalManufacturer.toFixed(2));
    $(api.column(profitIdx).footer()).html(totalProfit.toFixed(2));
}

    });
});
</script>


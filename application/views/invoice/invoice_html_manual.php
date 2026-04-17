<?php
    $CI =& get_instance();
    $CI->load->model('Web_settings');
    $Web_settings = $CI->Web_settings->retrieve_setting_editdata();
?>

<!-- Printable area start -->
  <style>
        .underline-input {
            border: none;
            border-bottom: 1px solid #333;
            outline: none;
            padding: 5px;
            width: 200px;
        }
    </style>
<script type="text/javascript">
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
	// document.body.style.marginTop="-45px";
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
<!-- Printable area end -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('invoice_details') ?></h1>
            <small><?php echo display('invoice_details') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('invoice') ?></a></li>
                <li class="active"><?php echo display('invoice_details') ?></li>
            </ol>
        </div>
    </section>
    <!-- Main content -->
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
        <?php
        if($this->permission1->method('manage_invoice','read')->access() ){ ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd">
	                <div id="printableArea">
	                    <div class="panel-body">
	                        <div class="row">
	                        	{company_info}
	                            <div class="col-sm-8" style="display: inline-block;width: 64%">
	                                <address style="margin-top:10px">
	                                    <strong>{company_name}</strong><br>
	                                    {address}<br>
	                                    <abbr><b><?php echo display('mobile') ?>:</b></abbr> {mobile}
	                                   &nbsp;
										<abbr><b>TIN NO:</b>{tin_number}</abbr>
	                                    {/company_info}<br>
	                                     <abbr>{tax_regno}</abbr>
	                                </address>
	                            </div>
	                             </div> 
								 <div class="row">
									<div class="row"><div class="col-sm-12"><h2><u><center>Cash Sales Attachement</center></u></h2></div></div>
									
									<div class="col-sm-6 text-left" style="display: inline-block; margin-left: 5px;">

    <div style="border: 1px solid #444; padding: 15px 15px 10px; position: relative; width: fit-content;">

        <!-- Caption (top-left inside the border) -->
        <div style="
            position: absolute;
            top: -12px;
            left: 10px;
            background-color: #fff;
            padding: 0 8px;
            font-weight: bold;
            font-size: 14px;
        ">
            <?php echo display('customer_information'); ?>
        </div>

       <address style="margin-top:10px; width: 350px;">
            <strong>Buyer's Name: <u>{customer_name}</u></strong><br>
			<strong>Buyer's TIN: <u>{tin_number}</u></strong> &nbsp;&nbsp;<strong>Wor/Keb:</strong>  <?php if ($customer_address) { ?>
                <u>{customer_address}</u><br>
            <?php } else {?><u>_______________</u> <?php } ?><br>
			<strong>Buyer's VAT: <u>{vat_number}</u></strong> &nbsp;&nbsp;<strong>H.NO</strong>: <u>{house_number}</u><br>
			
            <abbr><b><?php echo display('mobile') ?>:</b></abbr>
            <?php if ($customer_mobile) { ?>
                {customer_mobile}<br>
            <?php } ?>
        </address>

    </div>
	

</div>
<div class="col-sm-4 text-left" style="display: inline-block; margin-left: 5px;">

    <div style="border: 1px solid #444; padding: 15px 15px 10px; position: relative; width: fit-content;">

        <!-- Caption (top-left inside the border) -->
        <div style="
            position: absolute;
            top: -12px;
            left: 10px;
            background-color: #fff;
            padding: 0 8px;
            font-weight: bold;
            font-size: 14px;
        ">
            References
        </div>

        <address style="margin-top:10px; width: 350px;">
            <strong>Date <u>{final_date}</u></strong><br>
			<strong>FS NO. <u><span id="fs_nooo"></span></u></strong> &nbsp;&nbsp;<br>
			<strong>Computer Invoice No.: <u>{invoice_no}</u></strong> <br>
			
            <strong>References: <u>_______________</u></strong> &nbsp;&nbsp;<strong><br>Ref Note</strong>: <u><span id="fs_nooo1"></span></u><br>
        </address>

    </div>
	

</div>

		                       </div>
	                            <br>
	                            
	                       

	                        <div class="table-responsive m-b-20">
	                            <table class="table table-striped table-bordered">
	                                <thead>
	                                    <tr>
	                                        <th class="text-center"><?php echo display('sl') ?></th>
											<th class="text-center"><?php echo display('item_code')?></th>
	                                        <th class="text-center"><?php echo display('description') ?></th>
											<th class="text-center"><?php echo display('batch_number') ?></th>
											<th class="text-center"><?php echo display('exp_date') ?></th>
											<th class="text-center"><?php echo display('uom') ?></th>
	                                        <th class="text-center"><?php echo display('quantity') ?></th>

	                                        <th class="text-center"><?php echo display('rate') ?></th>
	                                        <th class="text-center"><?php echo display('ammount') ?></th>
	                                    </tr>
	                                </thead>
	                                <tbody>
										{invoice_all_data}
										<tr>
	                                    	<td class="text-center">{sl}</td>
											<td class="text-center">{product_id}</td>
	                                        <td class="text-center"><div>{product_name} - ({strength})</div></td>
											<td class="text-center"><div>{batch_id}</div></td>
											<td class="text-center"><div>{expiry_date}</div></td>
											<td class="text-center"><div>{unit}</div></td>
	                                        <td align="center">{quantity}</td>
	                                        

	                                        <td align="center"><?php echo (($position==0)?" {rate}":"{rate} ") ?></td>
	                                        <td align="right"><?php echo (($position==0)?" {total_price}":"{total_price} ") ?></td>
	                                    </tr>
	                                    {/invoice_all_data}
										<tr><td colspan="7"></td> <td colspan="2">
											<table class="table">
                                        <?php
                                        if ($invoice_all_data[0]['total_discount'] != 0) {
                                            ?>
                                            <tr>
                                                <th style="border-top: 0; border-bottom: 0;"><?php echo display('total_discount') ?> : </th>
                                                <td class="text-right" style="border-top: 0; border-bottom: 0;"><?php echo (($position == 0) ? " {total_discount}" : "{total_discount} ") ?> </td>
                                            </tr>
                                            <?php
                                        }
                                        if ($invoice_all_data[0]['total_tax'] != 0) {
                                            ?>
                                            <tr>
                                                <th class="text-left" style="border-top: 0; border-bottom: 0;"><?php echo display('tax') ?> : </th>
                                                <td  class="text-right" style="border-top: 0; border-bottom: 0;"><?php echo (($position == 0) ? "{total_tax}" : "{total_tax}") ?> </td>
                                            </tr>
                                        <?php } ?>
										<tr>
                                            <th class="text-left grand_total"><?php echo display('sub_total') ?> :</th>
                                            <td class="text-right grand_total"><?php echo (($position == 0) ? "{total_amount}" : "{total_amount}") ?></td>
                                        </tr>
                                       
                                        <tr>
                                            <th class="text-left grand_total"><?php echo display('vat'); ?> :</th>
                                            <td class="text-right grand_total"><?php echo (($position == 0) ? "{total_tax}" : "{total_tax} ") ?></td>
                                        </tr>
                                        
                                        <tr>
                                            <th class="text-left grand_total" style="border-top: 0; border-bottom: 0;"><?php echo display('grand_total') ?> : </th>
                                            <td class="text-right grand_total" style="border-top: 0; border-bottom: 0;"><?php echo (($position == 0) ? "{total_amount}" : "{total_amount}") ?></td>
											
                                        </tr>				 
                                      
                                    </table>
										</td></tr>
										<tr><td colspan="9" align="center"><p><strong>Birr :</strong> <?php echo $paid_amount_in_words; ?></p></td></tr>
										
										
	                                </tbody>
	                                <tfoot>
	                                	
	                                </tfoot>
	                            </table>
	                        </div>
	                        <div class="row">

		                        	<div class="col-xs-8" style="display: inline-block;width: 66%">

		                                <p></p>
		                                <p><strong>{invoice_details}</strong></p>
		                               
		                            </div>
		                            <div class="col-xs-4" style="display: inline-block;">

				                        

		                              

		                        </div>
		                        <div class="row">
		                        	<div class="col-sm-4">
		                        		
										</div>
		                        	</div>
									<div class="col-sm-4">
		                        		 <div  style="float:left;width:50%;text-align:center;border-top:1px solid #e4e5e7;margin-top: 20px;font-weight: bold;">
												Prepared By.
										</div>
		                        	</div>
									<div class="col-sm-4">
		                        		 <div  style="float:left;width:50%;text-align:center;border-top:1px solid #e4e5e7;margin-top: 20px;font-weight: bold;">
												Approved By.
										</div>
		                        	</div>
		                        	
		                        	<div class="col-sm-4">  <div  style="float:right;width:50%;text-align:center;border-top:1px solid #e4e5e7;margin-top: 20px;font-weight: bold;">
												Received By.
										</div></div>
		                        </div>
	                        </div>
	                    </div>
	                </div>

                     <div class="panel-footer text-left">
                     	<a  class="btn btn-danger" href="<?php echo base_url('Cinvoice');?>"><?php echo display('cancel') ?></a>
						<button  class="btn btn-info" onclick="printDiv('printableArea')"><span class="fa fa-print"></span></button>

                    </div>

                    <div><span>RF NO</span><input type="text" class="form-control" name="rf_no" id="rf_no"></div>
                    <div><span>Reference Note</span><input type="text" class="form-control" name="rf_no1" id="rf_no1"></div>
                </div>
            </div>
        </div>
        <?php
        }
        else{
        ?>
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('You do not have permission to access. Please contact with administrator.');?></h4>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->
<script type="text/javascript">
	$("#rf_no").focusout(function(){
		var rf_no=$("#rf_no").val();
		$('#fs_nooo').html(rf_no);


	});
	$("#rf_no1").focusout(function(){
		var rf_no1=$("#rf_no1").val();
		$('#fs_nooo1').html(rf_no1);


	});
	
	
</script>




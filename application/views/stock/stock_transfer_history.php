<!-- Product details page start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('stock_report') ?></h1>
	        <small><?php echo display('stock_transfer_history') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('stock') ?></a></li>
	            <li class="active"><?php echo display('stock_transfer_history') ?></li>
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

	    <div class="row">
            <div class="col-sm-12">
               
            </div>
        </div>


        <?php
        if($this->permission1->method('stock_transfer_history','read')->access() ) { ?>
		
		<!-- Total Purchase report -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('transfered_product') ?> </h4>
		                </div>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                    <table id="transferredTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th><?php echo display('product'); ?></th>
            <th><?php echo display('from_stock'); ?></th>
            <th><?php echo display('to_stock'); ?></th>
            <th><?php echo display('batch'); ?></th>
            <th><?php echo display('invoice'); ?></th>
            <th><?php echo display('invoice_id'); ?></th>
            <th><?php echo display('transfer_qty'); ?></th>
            <th><?php echo display('note'); ?></th>
            <th><?php echo display('date'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($transfers)) { ?>
            <?php foreach($transfers as $t) { ?>
                <tr>
                    <td><?php echo $t['product_name']; ?></td>
                    <td><?php echo $t['from_stock']; ?></td>
                    <td><?php echo $t['to_stock']; ?></td>
                    <td><?php echo $t['batch_id']; ?></td>
                    <td><?php echo $t['invoice_id']; ?></td>
                    <td><?php echo $t['chalan_no']; ?></td>
                    <td><?php echo $t['transfer_qty']; ?></td>
                    <td><?php echo $t['transfer_note']; ?></td>
                    <td><?php echo $t['created_at']; ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="8" class="text-center"><?php echo display('no_record_found'); ?></td>
            </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th><?php echo display('product'); ?></th>
            <th><?php echo display('from_stock'); ?></th>
            <th><?php echo display('to_stock'); ?></th>
            <th><?php echo display('batch'); ?></th>
            <th><?php echo display('invoice'); ?></th>
            <th><?php echo display('invoice_id'); ?></th>
            <th><?php echo display('transfer_qty'); ?></th>
            <th><?php echo display('note'); ?></th>
            <th><?php echo display('date'); ?></th>
        </tr>
    </tfoot>
</table>


		                </div>
		            </div>
		        </div>
		    </div>
		</div>

		<!--Total sales report -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('received_product') ?> </h4>
		                </div>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                   <h4>Received Stock</h4>
<table id="receivedTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
                 <th><?php echo display('product'); ?></th>
            <th><?php echo display('from_stock'); ?></th>
            <th><?php echo display('to_stock'); ?></th>
            <th><?php echo display('batch'); ?></th>
            <th><?php echo display('invoice'); ?></th>
            <th><?php echo display('invoice_id'); ?></th>
            <th><?php echo display('transfer_qty'); ?></th>
            <th><?php echo display('note'); ?></th>
            <th><?php echo display('date'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($recevied)) { ?>
            <?php foreach ($recevied as $r) { ?>
                <tr>
                    <td><?php echo $r['product_name']; ?></td>
                    <td><?php echo $r['from_stock']; ?></td>
                    <td><?php echo $r['to_stock']; ?></td>
                    <td><?php echo $r['batch_id']; ?></td>
                    <td><?php echo $r['invoice_id']; ?></td>
                    <td><?php echo $r['chalan_no']; ?></td>
                    <td><?php echo $r['transfer_qty']; ?></td>
                    <td><?php echo $r['transfer_note']; ?></td>
                    <td><?php echo $r['created_at']; ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="8" class="text-center">No received stock found</td></tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
                <th><?php echo display('product'); ?></th>
            <th><?php echo display('from_stock'); ?></th>
            <th><?php echo display('to_stock'); ?></th>
            <th><?php echo display('batch'); ?></th>
            <th><?php echo display('invoice'); ?></th>
            <th><?php echo display('invoice_id'); ?></th>
            <th><?php echo display('transfer_qty'); ?></th>
            <th><?php echo display('note'); ?></th>
            <th><?php echo display('date'); ?></th>
        </tr>
    </tfoot>
</table>

		                </div>
		            </div>
		        </div>
		    </div>
		</div>
        <?php
        }else{
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

<script>
$(document).ready(function() {
    function initTable(tableId) {
        // Remove rows with fewer TD than TH
        $('#' + tableId + ' tbody tr').each(function() {
            if ($(this).find('td').length < $('#' + tableId + ' thead th').length) {
                $(this).remove();
            }
        });

        // Initialize DataTable
        $('#' + tableId).DataTable({
            paging: true,
            lengthChange: true,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            pageLength: 10,
            order: [[7, "desc"]] // Sort by Date column
        });
    }

    initTable('transferredTable');
    initTable('receivedTable');
});
</script>



<!-- Product details page end -->
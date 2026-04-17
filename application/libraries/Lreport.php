<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lreport {
	
	// Retrieve All Stock Report
	public function stock_report($limit,$page,$links)
	{
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$stok_report = $CI->Reports->stock_report($limit,$page);
	
		if(!empty($stok_report)){
			$i=$page;
			foreach($stok_report as $k=>$v){$i++;
			   $stok_report[$k]['sl']=$i;
			}
			foreach($stok_report as $k=>$v){$i++;
			   $stok_report[$k]['stok_quantity'] = $stok_report[$k]['totalBuyQnty']-$stok_report[$k]['totalSalesQnty'];
			   $stok_report[$k]['totalSalesCtn'] = $stok_report[$k]['totalSalesQnty']/$stok_report[$k]['cartoon_quantity'];
			   $stok_report[$k]['totalPrhcsCtn'] = $stok_report[$k]['totalBuyQnty']/$stok_report[$k]['cartoon_quantity'];

			$stok_report[$k]['stok_quantity_cartoon'] = $stok_report[$k]['stok_quantity']/$stok_report[$k]['cartoon_quantity'];

			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title'    => display('stock_list'),
				'stok_report' => $stok_report,
				'links'    => $links,
				'currency' => $currency_details[0]['currency'],
				'position' => $currency_details[0]['currency_position'],
				
			);
		$reportList = $CI->parser->parse('report/stock_report',$data,true);
		return $reportList;
	}

	//Out of stock
	public function out_of_stock(){
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');

		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title' 	  => display('out_of_stock'),
				'totalnumber' => $CI->Reports->out_of_stock_count(),
			);

		$reportList = $CI->parser->parse('report/out_of_stock',$data,true);
		return $reportList;
	}
// Date expire Medicine list
	public function out_of_date(){
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		//$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title' 	  => display('out_of_date'),
				'totalnumber'      => $CI->Reports->out_of_date_count(),
			);

		$reportList = $CI->parser->parse('report/out_of_date',$data,true);
		return $reportList;
	}
	// Retrieve Single Item Stock Stock Report
		public function stock_report_single_item()
	{  
		$CI =& get_instance();
		$CI->load->model('Reports');
		$data['title'] = 'stock';
	    $data['totalnumber'] =$CI->Reports->totalnumberof_product(); 
		$reportList = $CI->parser->parse('report/stock_report',$data,true);
		return $reportList;
	}

	// Retrieve daily Report
	public function retrieve_all_reports($per_page = null, $page = null)
{
    $CI =& get_instance();

    // Load required resources
    $CI->load->model('Reports');
    $CI->load->model('Web_settings');
    $CI->load->library('occational');

    // Get today's sales and purchase reports
    $sales_report    = $CI->Reports->todays_sales_report($per_page, $page);
    $purchase_report = $CI->Reports->todays_purchase_report($per_page, $page);

    // Initialize sales totals
    $sales_amount       = 0;
    $paid_total         = 0;
    $due_total          = 0;
    $manufacturer_total = 0;

    // Process sales report data
    if (!empty($sales_report)) {
        $i = 0;
        foreach ($sales_report as $k => $v) {
            $i++;
            $sales_report[$k]['sl'] = $i;

            // Format date
            $sales_report[$k]['sales_date'] = $CI->occational->dateConvert($v['date']);

            // Safe extraction of values
            $total_amount       = !empty($v['total_amount']) ? $v['total_amount'] : 0;
            $paid_amount        = !empty($v['paid_amount']) ? $v['paid_amount'] : 0;
            $manufacturer_cost  = !empty($v['total_manufacturer']) ? $v['total_manufacturer'] : 0;

            // Due calculation
            $due_amount = $total_amount - $paid_amount;
            $sales_report[$k]['due_amount'] = $due_amount;

            // Profit calculations
            $sales_report[$k]['grand_profit'] = $total_amount - $manufacturer_cost;
            $sales_report[$k]['paid_profit']  = $paid_amount - $manufacturer_cost;

            // Accumulate totals
            $sales_amount       += $total_amount;
            $paid_total         += $paid_amount;
            $due_total          += $due_amount;
            $manufacturer_total += $manufacturer_cost;
        }
    }

    // Initialize purchase total
    $purchase_amount = 0;

    // Process purchase report data
    if (!empty($purchase_report)) {
        foreach ($purchase_report as $k => $v) {
            $purchase_report[$k]['prchse_date'] = $CI->occational->dateConvert($v['purchase_date']);
            $purchase_amount += !empty($v['grand_total_amount']) ? $v['grand_total_amount'] : 0;
        }
    }

    // Retrieve currency and company info
    $currency_details = $CI->Web_settings->retrieve_setting_editdata();
    $company_info     = $CI->Reports->retrieve_company();

    // Prepare data for view
    $data = array(
        'title'              => display('sales_report'),
        'sales_amount'       => number_format($sales_amount, 2, '.', ','), // Total Sales
        'paid_total'         => number_format($paid_total, 2, '.', ','),   // Total Paid
        'due_total'          => number_format($due_total, 2, '.', ','),    // Total Due
        'manufacturer_total' => number_format($manufacturer_total, 2, '.', ','), // Manufacturer Cost
        'grand_profit'       => number_format($sales_amount - $manufacturer_total, 2, '.', ','), // Gross Profit
        'paid_profit'        => number_format($paid_total - $manufacturer_total, 2, '.', ','),   // Paid Profit
        'sales_report'       => $sales_report,
        'purchase_report'    => $purchase_report,
        'purchase_amount'    => number_format($purchase_amount, 2, '.', ','), // Total Purchase
        'company_info'       => $company_info,
        'currency'           => $currency_details[0]['currency'],
        'position'           => $currency_details[0]['currency_position'],
        'links'              => '', // For pagination (if needed)
        'date'               => date('Y-m-d') // Optional: current date
    );

    // Return rendered view (grand_profit_view or all_report based on your setup)
    return $CI->parser->parse('report/grand_profit_view', $data, true);
}





	// Retrieve todays_sales_report
public function todays_sales_report($links=null, $per_page=null, $page=null)
{
    $CI =& get_instance();
    $CI->load->model('Reports');
    $CI->load->model('Web_settings');
    $CI->load->library('occational');

    $sales_report = $CI->Reports->todays_sales_report($per_page, $page);
    $purchase_report = $CI->Reports->todays_purchase_report($per_page, $page);

    // Initialize totals for sales
    $sales_amount       = 0;
    $paid_total         = 0;
    $due_total          = 0;
    $manufacturer_total = 0;

    if (!empty($sales_report)) {
        $i = 0;
        foreach ($sales_report as $k => $v) {
            $i++;
            $sales_report[$k]['sl'] = $i;

            // Format sales date
            $sales_report[$k]['sales_date'] = $CI->occational->dateConvert($v['date']);

            // Values with fallback
            $total_amount       = !empty($v['total_amount']) ? $v['total_amount'] : 0;
            $paid_amount        = !empty($v['paid_amount']) ? $v['paid_amount'] : 0;
            $manufacturer_cost  = !empty($v['total_manufacturer']) ? $v['total_manufacturer'] : 0;

            // Calculate due
            $due_amount = $total_amount - $paid_amount;
            $sales_report[$k]['due_amount'] = $due_amount;

            // Profit calculations
            $sales_report[$k]['grand_profit'] = $total_amount - $manufacturer_cost;
            $sales_report[$k]['paid_profit']  = $paid_amount - $manufacturer_cost;
			$sales_report[$k]['stock_name'] = !empty($v['stock_name']) ? $v['stock_name'] : 'N/A';

            // Accumulate totals
            $sales_amount       += $total_amount;
            $paid_total         += $paid_amount;
            $due_total          += $due_amount;
            $manufacturer_total += $manufacturer_cost;
        }
    }

    // Initialize totals for purchase
    $purchase_amount = 0;
    if (!empty($purchase_report)) {
        foreach ($purchase_report as $k => $v) {
            // Format purchase date similarly
            $purchase_report[$k]['prchse_date'] = $CI->occational->dateConvert($v['purchase_date']);

            // Accumulate total purchase amount
            $purchase_amount += !empty($v['grand_total_amount']) ? $v['grand_total_amount'] : 0;
        }
    }

    // Get currency and company info
    $currency_details = $CI->Web_settings->retrieve_setting_editdata();
    $company_info     = $CI->Reports->retrieve_company();

    // Prepare data array for view
    $data = array(
        'title'              => display('sales_report'),
        'sales_amount'       => number_format($sales_amount, 2, '.', ','),
        'paid_total'         => number_format($paid_total, 2, '.', ','),
        'due_total'          => number_format($due_total, 2, '.', ','),
        'manufacturer_total' => number_format($manufacturer_total, 2, '.', ','),
        'grand_profit'       => number_format($sales_amount - $manufacturer_total, 2, '.', ','),
        'paid_profit'        => number_format($paid_total - $manufacturer_total, 2, '.', ','),
        'sales_report'       => $sales_report,
        'purchase_report'    => $purchase_report,
		'stock_name'    => $stock_name,
        'purchase_amount'    => number_format($purchase_amount, 2, '.', ','),
        'company_info'       => $company_info,
        'currency'           => $currency_details[0]['currency'],
        'position'           => $currency_details[0]['currency_position'],
        'links'              => '',
        'date'               => date('Y-m-d'), // For purchase date display if needed
    );

    return $CI->parser->parse('report/sales_report', $data, true);
}





	public function retrieve_dateWise_SalesReports($start_date, $end_date)
{
    $CI =& get_instance();
    $CI->load->model('Reports');
    $CI->load->model('Web_settings');
    $CI->load->library('occational');

    // Get filtered sales report between date range
    $sales_report = $CI->Reports->range_sales_report($start_date, $end_date);

    $sales_amount       = 0;
    $paid_total         = 0;
    $due_total          = 0;
    $manufacturer_total = 0;

    if (!empty($sales_report)) {
        $i = 0;
        foreach ($sales_report as $k => $v) {
            $i++;
            $sales_report[$k]['sl'] = $i;
            $sales_report[$k]['sales_date'] = $CI->occational->dateConvert($v['date']);

            $total_amount       = !empty($v['total_amount']) ? $v['total_amount'] : 0;
            $paid_amount        = !empty($v['paid_amount']) ? $v['paid_amount'] : 0;
            $manufacturer_cost  = !empty($v['total_manufacturer']) ? $v['total_manufacturer'] : 0;
            $due_amount         = $total_amount - $paid_amount;

            $sales_report[$k]['due_amount']    = $due_amount;
            $sales_report[$k]['grand_profit']  = $total_amount - $manufacturer_cost;
            $sales_report[$k]['paid_profit']   = $paid_amount - $manufacturer_cost;
			$sales_report[$k]['stock_name'] = !empty($v['stock_name']) ? $v['stock_name'] : 'N/A';

            $sales_amount       += $total_amount;
            $paid_total         += $paid_amount;
            $due_total          += $due_amount;
            $manufacturer_total += $manufacturer_cost;
        }
    } else {
        // no data found, send empty array to view
        $sales_report = [];
    }

    $currency_details = $CI->Web_settings->retrieve_setting_editdata();
    $company_info     = $CI->Reports->retrieve_company();

    $data = array(
        'title'              => display('sales_report'),
        'sales_amount'       => number_format($sales_amount, 2, '.', ','),
        'paid_total'         => number_format($paid_total, 2, '.', ','),
        'due_total'          => number_format($due_total, 2, '.', ','),
        'manufacturer_total' => number_format($manufacturer_total, 2, '.', ','),
        'grand_profit'       => number_format($sales_amount - $manufacturer_total, 2, '.', ','),
        'paid_profit'        => number_format($paid_total - $manufacturer_total, 2, '.', ','),
        'sales_report'       => $sales_report,
        'company_info'       => $company_info,
		'stock_name'       => $stock_name,
        'currency'           => $currency_details[0]['currency'],
        'position'           => $currency_details[0]['currency_position'],
        'links'              => '',
    );

    return $CI->parser->parse('report/sales_report', $data, true);
}

	// Retrieve todays_purchase_report
	public function todays_purchase_report($links = null, $per_page = null, $page = null)
{
    $CI =& get_instance();
    $CI->load->model('Reports');
    $CI->load->model('Web_settings');
    $CI->load->library('occational');

    $purchase_report = $CI->Reports->todays_purchase_report($per_page, $page);
    $purchase_amount = 0;

    if (!empty($purchase_report)) {
        $i = 0;
        foreach ($purchase_report as $k => $v) {
            $i++;
            $purchase_report[$k]['sl'] = $i;
            $purchase_report[$k]['prchse_date'] = $CI->occational->dateConvert($purchase_report[$k]['purchase_date']);
            $purchase_report[$k]['stock_name'] = $purchase_report[$k]['stock_name']; // add this line if you want to explicitly handle it
            $purchase_amount += $purchase_report[$k]['grand_total_amount'];
        }
    }

    $currency_details = $CI->Web_settings->retrieve_setting_editdata();
    $company_info = $CI->Reports->retrieve_company();

    $data = array(
        'title'           => display('todays_purchase_report'),
        'purchase_amount' => number_format($purchase_amount, 2, '.', ','),
        'purchase_report' => $purchase_report,
        'company_info'    => $company_info,
		"stock_name"     =>$stock_name,
        'currency'        => $currency_details[0]['currency'],
        'position'        => $currency_details[0]['currency_position'],
        'links'           => $links,
    );

    $reportList = $CI->parser->parse('report/purchase_report', $data, true);
    return $reportList;
}


	//Retrive date wise purchase report
	public function retrieve_dateWise_PurchaseReports($start_date,$end_date)
	{
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$purchase_report = $CI->Reports->retrieve_dateWise_PurchaseReports($start_date,$end_date);
		$purchase_amount = 0;
		if(!empty($purchase_report)){
			$i=0;
			foreach($purchase_report as $k=>$v){$i++;
			    $purchase_report[$k]['sl']=$i;
			    $purchase_report[$k]['prchse_date'] = $CI->occational->dateConvert($purchase_report[$k]['purchase_date']);
				$purchase_report[$k]['stock_name'] = $purchase_report[$k]['stock_name'];
				$purchase_amount = $purchase_amount+$purchase_report[$k]['grand_total_amount'];
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$company_info = $CI->Reports->retrieve_company();
		$data = array(
				'title' 		=> display('purchase_report'),
				'purchase_amount'=>  $purchase_amount,
				'purchase_report'=> $purchase_report,
				'company_info' 	=> $company_info,
				'stock_name' 	=> $stock_name,
				'currency' 		=> $currency_details[0]['currency'],
				'position' 		=> $currency_details[0]['currency_position'],
				'links' 		=> '',

			);
		$reportList = $CI->parser->parse('report/purchase_report',$data,true);
		return $reportList;
	}
	//Product report sales wise
	public function get_products_report_sales_view($links,$per_page,$page)
	 {
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$product_report = $CI->Reports->retrieve_product_sales_report($per_page,$page);
	
		if(!empty($product_report)){
			$i=0;
			foreach($product_report as $k=>$v){$i++;
			    $product_report[$k]['sl']=$i;
			}
		}
		$sub_total = 0;
		if(!empty($product_report)){
			foreach($product_report as $k=>$v){
			    $product_report[$k]['sales_date'] = $CI->occational->dateConvert($product_report[$k]['date']);
				$sub_total +=$product_report[$k]['total_amount'];
				$product_report[$k]['stock_name'] = $product_report[$k]['stock_name']; 
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$company_info = $CI->Reports->retrieve_company();
		$data = array(
				'title' 	     => display('sales_report_product_wise'),
				'sub_total'      =>  number_format($sub_total, 2, '.', ','),
				'product_report' => $product_report,
				'links' 	     => $links,
				'start'          => date('Y-m-d'),
				'end'            => date('Y-m-d'),
				'company_info'   => $company_info,
				'stock_name'   => $stock_name,
				'currency' 	     => $currency_details[0]['currency'],
				'position' 	     => $currency_details[0]['currency_position'],
			);
		$reportList = $CI->parser->parse('report/product_report',$data,true);
		return $reportList;
	}
public function expense_report() {
    $CI =& get_instance();
    $CI->load->model('Reports');      // Load your Reports model (if needed)
    $CI->load->model('Payment');      // Load your Payment model (if needed)
    $CI->load->library('parser');     // Load parser library

    // Retrieve and process data
    $total_expense = $CI->Reports->sum_expenses();
    $total_payment = $CI->Payment->sum_payments();

    // Prepare data for the view
    $data = [
        'title' => display('expense_report'),
        'total_expense' => number_format($total_expense, 2, '.', ','),  // format numbers if needed
        'total_payment' => number_format($total_payment, 2, '.', ','),
    ];

    // Parse the view and return as a string
    $content = $CI->parser->parse('expense/expense_report', $data, true);

    return $content;
}




	//expense and payment count
	public function get_total_expense() {
    $CI =& get_instance();
    $CI->load->model('Reports');
    return $CI->Reports->sum_expenses();
}

public function get_total_payment() {
    $CI =& get_instance();
    $CI->load->model('Payment');
    return $CI->Payment->sum_payments();
}

	//Get Product Report Search
	public function get_products_search_report( $from_date,$to_date )
	 {
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$product_report = $CI->Reports->retrieve_product_search_sales_report( $from_date,$to_date );

		if(!empty($product_report)){
			$i=0;
			foreach($product_report as $k=>$v){$i++;
			    $product_report[$k]['sl']=$i;
			}
		}
		$sub_total = 0;
		if(!empty($product_report)){
			foreach($product_report as $k=>$v){
			    $product_report[$k]['sales_date'] = $CI->occational->dateConvert($product_report[$k]['date']);
				$sub_total = $sub_total+$product_report[$k]['total_price'];
				$product_report[$k]['sales_date'] = $CI->occational->dateConvert($v['date']);
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$company_info = $CI->Reports->retrieve_company();
		$data = array(
				'title' 	     => display('sales_report_product_wise'),
				'sub_total'      =>  number_format($sub_total, 2, '.', ','),
				'product_report' => $product_report,
				'stock_name' => $stock_name,
				'links' 	     => '',
				'start'          => $from_date,
				'end'            => $to_date,
				'company_info'   => $company_info,
				'currency' 	     => $currency_details[0]['currency'],
				'position' 	     => $currency_details[0]['currency_position'],
			);
		$reportList = $CI->parser->parse('report/product_report',$data,true);
		return $reportList;
	}
	//Total profit report
	public function total_profit_report($links,$per_page,$page){
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$total_profit_report = $CI->Reports->total_profit_report($per_page,$page);	

	
		$profit_ammount = 0;
		$SubTotalSupAmnt = 0;
		$SubTotalSaleAmnt = 0;
		if(!empty($total_profit_report)){
			$i=0;
			foreach($total_profit_report as $k=>$v){
				$total_profit_report[$k]['sl']=$i;
			    $total_profit_report[$k]['prchse_date'] = $CI->occational->dateConvert($total_profit_report[$k]['date']);

				$profit_ammount = $profit_ammount+$total_profit_report[$k]['total_profit'];

				$SubTotalSupAmnt = $SubTotalSupAmnt+$total_profit_report[$k]['total_manufacturer_rate'];

				$SubTotalSaleAmnt = $SubTotalSaleAmnt+$total_profit_report[$k]['total_sale'];

			}
		}

		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$company_info = $CI->Reports->retrieve_company();
		$data = array(
				'title' 			=> display('profit_report'),
				'profit_ammount' 	=>  number_format($profit_ammount, 2, '.', ','),
				'total_profit_report' => $total_profit_report,
				'SubTotalSupAmnt' 	=> number_format($SubTotalSupAmnt, 2, '.', ','),
				'SubTotalSaleAmnt' 	=> number_format($SubTotalSaleAmnt, 2, '.', ','),
				'links' 			=> $links,
				'company_info' 		=> $company_info,
				'currency' 			=> $currency_details[0]['currency'],
				'position' 			=> $currency_details[0]['currency_position'],
			);
		$reportList = $CI->parser->parse('report/profit_report',$data,true);
		return $reportList;
	}
	
	//Retrive date wise total profit report
	public function retrieve_dateWise_profit_report($start_date,$end_date,$links,$per_page,$page)
	{
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$total_profit_report = $CI->Reports->retrieve_dateWise_profit_report($start_date,$end_date,$per_page,$page);

		$profit_ammount = 0;
		$SubTotalSupAmnt = 0;
		$SubTotalSaleAmnt = 0;
		if(!empty($total_profit_report)){
			$i=0;
			foreach($total_profit_report as $k=>$v){
				$total_profit_report[$k]['sl']=$i;
			    $total_profit_report[$k]['prchse_date'] = $CI->occational->dateConvert($total_profit_report[$k]['date']);

				$profit_ammount = $profit_ammount+$total_profit_report[$k]['total_profit'];

				$SubTotalSupAmnt = $SubTotalSupAmnt+$total_profit_report[$k]['total_manufacturer_rate'];

				$SubTotalSaleAmnt = $SubTotalSaleAmnt+$total_profit_report[$k]['total_sale'];

			}
		}

		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$company_info = $CI->Reports->retrieve_company();
		$data = array(
				'title' 			=> display('profit_report'),
				'profit_ammount' 	=>  number_format($profit_ammount, 2, '.', ','),
				'total_profit_report' => $total_profit_report,
				'SubTotalSupAmnt' 	=> number_format($SubTotalSupAmnt, 2, '.', ','),
				'SubTotalSaleAmnt' 	=> number_format($SubTotalSaleAmnt, 2, '.', ','),
				'links' 			=> $links,
				'company_info' 		=> $company_info,
				'currency' 			=> $currency_details[0]['currency'],
				'position' 			=> $currency_details[0]['currency_position'],
			);
		$reportList = $CI->parser->parse('report/profit_report',$data,true);
		return $reportList;
	}


		public function stock_report_batch_wise()
	{   
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('manufacturers');
		$CI->load->library('occational');
        $company_info = $CI->Reports->retrieve_company();
        $data['title'] = 'Batch Wise Stock';
		$reportList = $CI->parser->parse('report/stock_report_batch_wise',$data,true);
		return $reportList;
	}
	
	//profit report manufacturer wise
	public function profit_report_manufacturer($manufacturer_id,$from_date,$to_date)
	{   
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('manufacturers');
		$CI->load->library('occational');
		$profit_purchase = $CI->Reports->profit_report_manufacturer($manufacturer_id,$from_date,$to_date);
		$profit_sale = $CI->Reports->profit_report_manufacturer_sale($manufacturer_id,$from_date,$to_date);
		$manufacturer_list = $CI->manufacturers->manufacturer_list_report();
		$manufacturer_info = $CI->manufacturers->retrieve_manufacturer_editdata($manufacturer_id);
        $company_info = $CI->Reports->retrieve_company();
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$company_info = $CI->Reports->retrieve_company();
		foreach($profit_purchase as $k=>$v){}
		foreach($profit_sale as $k=>$v){}
		$data = array(
				'title' 		=> 	display('profit_report_manufacturer_wise'),
				'currency' 		=> 	$currency_details[0]['currency'],
				'position' 		=> 	$currency_details[0]['currency_position'],
				'quantity'      =>  $profit_purchase[0]['quantity'],
				'tpurchase'     =>  $profit_sale[0]['quantity']*$profit_purchase[0]['avg_r'],
				'manufacturer'  =>  $manufacturer_list,
				'from'          =>  $from_date,
				'to'            =>  $to_date,
				'logo'          =>  $currency_details[0]['logo'],
				'manufacturer_info' => $manufacturer_info,
				'total_sale_qty'=> $profit_sale[0]['quantity'],
				'total_sale'    => $profit_sale[0]['quantity']*$profit_sale[0]['avg_r'],

			);
	
		$reportList = $CI->parser->parse('report/profit_report_manufacturer',$data,true);
		return $reportList;
	}
	public function profit_report_manufacturer_form()
	{   
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('manufacturers');
		$CI->load->library('occational');
        $company_info = $CI->Reports->retrieve_company();
        $manufacturer_list = $CI->manufacturers->manufacturer_list_report();
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$company_info = $CI->Reports->retrieve_company();
		$data = array(
				'title' 		=> 	display('profit_report_manufacturer_wise'),
				'manufacturer'      =>  $manufacturer_list,
			);
	
		$reportList = $CI->parser->parse('report/profit_lose_report',$data,true);
		return $reportList;
	}
	// product wise profit reporf form information
	public function profit_productwise_form()
	{   
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('manufacturers');
		$CI->load->library('occational');
        $company_info = $CI->Reports->retrieve_company();
        $manufacturer_list = $CI->manufacturers->manufacturer_list_report();
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$company_info = $CI->Reports->retrieve_company();
		$data = array(
				'title' 		=> 	display('profit_report_manufacturer_wise'),
				'manufacturer'      =>  $manufacturer_list,
			);
	
		$reportList = $CI->parser->parse('report/profit_product_report',$data,true);
		return $reportList;
	}
	// product wise profit report 
	public function profit_productwise($product_id,$from_date,$to_date)
	{   
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Products');
		$CI->load->library('occational');
		$profit_purchase = $CI->Reports->profit_report_productwise($product_id,$from_date,$to_date);
		$profit_sale = $CI->Reports->profit_report_product_salesss($product_id,$from_date,$to_date);
		$product_detail = $CI->Products->retrieve_product_editdata($product_id);
        $company_info = $CI->Reports->retrieve_company();
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$company_info = $CI->Reports->retrieve_company();
		
		foreach($profit_sale as $k=>$v){}
			foreach($profit_purchase as $k=>$v){}
		$data = array(
				'title' 		=> 	display('profit_report_product_wise'),
				'currency' 		=> 	$currency_details[0]['currency'],
				'position' 		=> 	$currency_details[0]['currency_position'],
				'quantity'      =>  $profit_purchase[0]['quantity'],
				'tpurchase'     =>  $profit_sale[0]['quantity']*$profit_purchase[0]['avg_r'],
				'from'          =>  $from_date,
				'to'            =>  $to_date,
				'logo'          =>  $currency_details[0]['logo'],
				'product_detail'=> $product_detail,
				'product_info'  => $product_detail,
				'total_sale_qty'=> $profit_sale[0]['quantity'],
				'total_sale'    => $profit_sale[0]['quantity']*$profit_sale[0]['avg_r'],

			);
	
		$reportList = $CI->parser->parse('report/productwise_profit_view',$data,true);
		return $reportList;
	}
	// stock add form
	public function stock_add_form(){
		
		$CI =& get_instance();
		$CI->load->model('Stocks');
		$CI->load->model('Web_settings');
		$all_users = $CI->Stocks->select_all_users();
		$data = array(
				'title' 		=> display('create_stock'),
				'all_users' 	=> $all_users,
			);
		$stockForm = $CI->parser->parse('stock/add_stock_form',$data,true);
		return $stockForm;

	}
	//stock transfer form
	public function stock_transfer_form(){
		$CI =& get_instance();
		$CI->load->model('Stocks');
		$user_id = $CI->session->userdata('user_id');
		//$user_id = $CI->session->userdata('user_id');
		// ✅ Load only stocks assigned to this user
		$all_stock = $CI->Stocks->get_stocks_assigned_to_user($user_id);
		$to_stocks= $CI->Stocks->get_all_stocks();
		$CI->load->model('Web_settings');
		
		$data = array(
				'title' 		=> display('stock_transfer'),
				'all_stock' 	=> $all_stock,
				'to_stocks'    =>$to_stocks,
			);
		$stockForm = $CI->parser->parse('stock/stock_transfer_form',$data,true);
		return $stockForm;

	}

	public function stock_list() {

        $CI = & get_instance();
        $CI->load->model('Stocks');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $data = array(
            'title'         => display('manage_stock'),
            
        );
        $stockList = $CI->parser->parse('stock/manage_stock', $data, true);
        return $stockList;
    }
	public function stock_update_form($id)
{
    $CI =& get_instance();
    $CI->load->model('Stocks');
    $CI->load->model('Users');

    $stock_detail = $CI->Stocks->retrieve_stock_editdata($id);
    $all_users = $CI->Users->get_all_users();

    if (!empty($stock_detail)) {
        $stock = $stock_detail[0];

        $data = array(
            'title'         => display('stock_edit'),
            'id'            => $stock['id'],
            'stock_name'    => $stock['stock_name'],
            'stock_address' => $stock['address'],
            'assign_users'  => $stock['assign_users'], // array of assigned user IDs
            'all_users'     => $all_users
        );

        $chapterList = $CI->parser->parse('stock/update_stock_form', $data, true);
        return $chapterList;
    } else {
        return "Stock not found!";
    }
}
public function stock_transfer_history() {
    $CI =& get_instance();
    $CI->load->model('Stocks');
    $CI->load->library('occational');
    $CI->load->model('Web_settings');

    // Fetch all stock transfers
    $transfers = $CI->Stocks->get_all_stock_transfers();
	$recevied = $CI->Stocks->get_all_stock_recevied();

    // Pass data to the view
    $data = array(
        'title'     => display('stock_transfer_history'),
        'transfers' => $transfers,
		'recevied' => $recevied
		
    );

    // Load view with standard PHP
    $productList = $CI->load->view('stock/stock_transfer_history', $data, true);
    return $productList;
}
public function shortlisted_report(){
	$CI =& get_instance();
	$CI->load->model('Reports');
	$CI->load->model('Web_settings');
	$CI->load->library('occational');

	// Get shortlisted products report
	$shortlisted_report = $CI->Reports->get_shortlisted_products_report();

	// Prepare data for view
	$data = array(
    'title'               => display('shortlisted_products_report'),
    'shortlisted_report'  => $shortlisted_report, // Passing the report data
);


	// Return rendered view
	return $CI->parser->parse('report/shortlisted_products_report', $data, true);

}

public function interviewed_report(){
	$CI =& get_instance();
	$CI->load->model('Reports');
	$CI->load->model('Web_settings');
	$CI->load->library('occational');

	// Get shortlisted products report
	$interviewed_report = $CI->Reports->get_interviwed_products_report();

	// Prepare data for view
	$data = array(
    'title'               => display('interviewed_report'),
    'interviewed_report'  => $interviewed_report, // Passing the report data
);


	// Return rendered view
	return $CI->parser->parse('report/interviewed_products_report', $data, true);

}

//hiered
public function hired_report(){
    $CI =& get_instance();
    $CI->load->model('Reports');
    $CI->load->model('Web_settings');
    $CI->load->library('occational');

    // Get hired products report
    $hired_report = $CI->Reports->get_hired_products_report(); // Fixed variable name

    // Prepare data for view
    $data = array(
        'title'         => display('hired_report'),
        'hired_report'  => $hired_report, // Passing the correct report data
    );

    // Return rendered view
    return $CI->parser->parse('report/hired_products_report', $data, true);
}
// Controller Method to fetch and pass rejected report data to the view
public function rejected_report() {
    $CI =& get_instance();
    $CI->load->model('Reports');
    $CI->load->model('Web_settings');
    $CI->load->library('occational');

    // Get rejected candidates report
    $rejected_report = $CI->Reports->get_rejected_products_report();

    // Prepare data for the view
    $data = array(
        'title' => display('rejected_report'),
        'rejected_report' => $rejected_report, // Passing the report data to the view
    );

    // Return rendered view with the data
    return $CI->parser->parse('report/rejected_products_report', $data, true);
}

// Applied Report
public function applied_report() {
    $CI =& get_instance();
    $CI->load->model('Reports');
    $CI->load->model('Web_settings');
    $CI->load->library('occational');

    // Get applied candidates report
    $applied_report = $CI->Reports->get_applied_products_report();

    // Prepare data for the view
    $data = array(
        'title' => display('applied_report'),
        'applied_report' => $applied_report,
    );

    // Return rendered view with the data
    return $CI->parser->parse('report/applied_products_report', $data, true);
}




}
?>
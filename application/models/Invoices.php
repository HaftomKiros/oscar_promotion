<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Invoices extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('auth');
		$this->load->library('lcustomer');
		$this->load->library('session');
		$this->load->model('Customers');
		$this->load->model('Web_settings');
		$this->auth->check_admin_auth();
	}
	//Count invoice
	public function count_invoice()
{
    $CI =& get_instance();
    $user_id = $CI->session->userdata('user_id');

    $this->db->from('invoice b');  // assuming 'invoice' table alias is 'b'
    $this->db->join('stock s', 's.id = b.stock_id', 'left');
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);

    return $this->db->count_all_results();
}




	public function getInvoiceList($postData = null)
{
    $this->load->library('occational');

    $CI =& get_instance();
    $user_id = $CI->session->userdata('user_id');

    $response = array();
    $fromdate = $this->input->post('fromdate');
    $todate   = $this->input->post('todate');

    $datbetween = "";
    if (!empty($fromdate) && !empty($todate)) {
        $datbetween = "(a.date BETWEEN '$fromdate' AND '$todate')";
    }

    // DataTables variables
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length'];
    $columnIndex = $postData['order'][0]['column'];
    $columnName = $postData['columns'][$columnIndex]['data'];
    $columnSortOrder = $postData['order'][0]['dir'];
    $searchValue = $postData['search']['value'];

    // -----------------------
    // Search query
    // -----------------------
    $searchQuery = "";
    if ($searchValue != '') {
        $searchQuery = "(
            b.customer_name LIKE '%".$searchValue."%' 
            OR a.invoice LIKE '%".$searchValue."%' 
            OR a.date LIKE '%".$searchValue."%' 
            OR a.invoice_id LIKE '%".$searchValue."%' 
            OR s.stock_name LIKE '%".$searchValue."%' 
            OR u.first_name LIKE '%".$searchValue."%' 
            OR u.last_name LIKE '%".$searchValue."%' 
            OR CONCAT(u.first_name, ' ', u.last_name) LIKE '%".$searchValue."%'
        )";
    }

    // -----------------------
    // Total records without filtering
    // -----------------------
    $this->db->select('COUNT(a.invoice_id) as allcount');
    $this->db->from('invoice a');
    $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
    $this->db->join('stock s', 's.id = a.stock_id', 'left');
    $this->db->join('users u', 'a.sales_by = u.user_id', 'left');

    if (!empty($fromdate) && !empty($todate)) {
        $this->db->where($datbetween);
    }

    if ($searchValue != '') {
        $this->db->where($searchQuery, null, false);
    }

    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);
    $records = $this->db->get()->result();
    $totalRecords = isset($records[0]->allcount) ? (int)$records[0]->allcount : 0;

    // -----------------------
    // Total records with filtering
    // -----------------------
    $this->db->select('COUNT(a.invoice_id) as allcount');
    $this->db->from('invoice a');
    $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
    $this->db->join('stock s', 's.id = a.stock_id', 'left');
    $this->db->join('users u', 'a.sales_by = u.user_id', 'left');

    if (!empty($fromdate) && !empty($todate)) {
        $this->db->where($datbetween);
    }

    if ($searchValue != '') {
        $this->db->where($searchQuery, null, false);
    }

    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);
    $records = $this->db->get()->result();
    $totalRecordwithFilter = isset($records[0]->allcount) ? (int)$records[0]->allcount : 0;

    // -----------------------
    // Fetch filtered records
    // -----------------------
    $this->db->select("
        a.*, 
        CONCAT(u.first_name, ' ', u.last_name) AS full_name,
        b.customer_name, 
        s.stock_name, 
        d.paid_amount, 
        d.due_amount, 
        d.total_manufacturer
    ");
    $this->db->from('invoice a');
    $this->db->join('users u', 'a.sales_by = u.user_id', 'left');
    $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
    $this->db->join('stock s', 's.id = a.stock_id', 'left');
    $this->db->join("(
        SELECT 
            invoice_id,
            MAX(paid_amount) AS paid_amount,
            MAX(due_amount) AS due_amount,
            SUM(quantity * manufacturer_rate) AS total_manufacturer
        FROM invoice_details
        GROUP BY invoice_id
    ) d", 'd.invoice_id = a.invoice_id', 'left');

    if (!empty($fromdate) && !empty($todate)) {
        $this->db->where($datbetween);
    }

    if ($searchValue != '') {
        $this->db->where($searchQuery, null, false);
    }

    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);
    $this->db->order_by($columnName, $columnSortOrder);
    $this->db->limit($rowperpage, $start);

    $records = $this->db->get()->result();

    // -----------------------
    // Build data for DataTables
    // -----------------------
    $data = array();
    $sl = 1;
    $base_url = base_url();
    $jsaction = "return confirm('Are You Sure ?')";

    foreach ($records as $record) {
        $button = '';
        $button .= '<a href="'.$base_url.'Cinvoice/invoice_inserted_data/'.$record->invoice_id.'" class="btn btn-success btn-sm" title="'.display('invoice').'"><i class="fa fa-window-restore"></i></a>';
        $button .= '<a href="'.$base_url.'Cinvoice/pos_invoice_inserted_data/'.$record->invoice_id.'" class="btn btn-warning btn-sm" title="'.display('pos_invoice').'"><i class="fa fa-fax"></i></a>';

        if ($this->permission1->method('manage_invoice', 'update')->access()) {
            $button .= '<a href="'.$base_url.'Cinvoice/invoice_update_form/'.$record->invoice_id.'" class="btn btn-info btn-sm" title="'.display('update').'"><i class="fa fa-pencil"></i></a>';
        }

        if ($this->permission1->method('manage_invoice', 'delete')->access()) {
            $button .= '<a href="'.$base_url.'Cinvoice/invoice_delete/'.$record->invoice_id.'" class="btn btn-danger btn-sm" onclick="'.$jsaction.'" title="'.display('delete').'"><i class="fa fa-trash"></i></a>';
        }

        $data[] = array(
            'sl'                 => $sl,
            'invoice'            => $record->invoice,
            'customer_name'      => $record->customer_name,
            'stock_name'         => $record->stock_name,
            'final_date'         => $record->date,
            'full_name'          => $record->full_name,
            'total_amount'       => $record->total_amount,
            'paid_amount'        => $record->paid_amount,
            'due_amount'         => $record->total_amount - $record->paid_amount,
            'total_manufacturer' => $record->total_manufacturer,
            'grand_profit'       => $record->total_amount - $record->total_manufacturer,
            'paid_profit'        => $record->paid_amount - $record->total_manufacturer,
            'button'             => $button,
        );
        $sl++;
    }

    // -----------------------
    // DataTables Response
    // -----------------------
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecordwithFilter,
        "iTotalDisplayRecords" => $totalRecords,
        "aaData" => $data
    );

    return $response;
}


	//invoice List
	public function invoice_list($perpage,$page)
	{
		$this->db->select('a.*,b.customer_name');
		$this->db->from('invoice a');
		$this->db->join('customer_information b','b.customer_id = a.customer_id');
		$this->db->order_by('a.invoice','desc');
		$this->db->limit($perpage,$page);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}	
	
	// invoice search by invoice id
		public function invoice_list_invoice_id($invoice_no)
	{
		$this->db->select('a.*,b.customer_name');
		$this->db->from('invoice a');
		$this->db->join('customer_information b','b.customer_id = a.customer_id');
	
		$this->db->where('invoice',$invoice_no);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}	
	// date to date invoice list
	public function invoice_list_date_to_date($from_date,$to_date,$perpage,$page)
	{
		$dateRange = "a.date BETWEEN '$from_date%' AND '$to_date%'";
		$this->db->select('a.*,b.customer_name');
		$this->db->from('invoice a');
		$this->db->join('customer_information b','b.customer_id = a.customer_id');
		$this->db->where($dateRange, NULL, FALSE); 	
		$this->db->order_by('a.invoice','desc');
		$this->db->limit($perpage,$page);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}	
	//invoice List
	public function invoice_list_count()
	{
		$this->db->select('a.*,b.customer_name');
		$this->db->from('invoice a');
		$this->db->join('customer_information b','b.customer_id = a.customer_id');
		$this->db->order_by('a.invoice','desc');
		$this->db->limit('500');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->num_rows();	
		}
		return false;
	}

	//invoice Search Item
	public function search_inovoice_item($customer_id)
	{
		$this->db->select('a.*,b.customer_name');
		$this->db->from('invoice a');
		$this->db->join('customer_information b','b.customer_id = a.customer_id');
		$this->db->where('b.customer_id',$customer_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}

	//POS invoice entry
public function pos_invoice_setup($product_id){
		$product_information = $this->db->select('a.*,c.*')
						->from('product_information a')
						->join('product_purchase_details c','a.product_id=c.product_id','left')
						->where('a.product_id',$product_id)
						->get()
						->row();

		if ($product_information != null) {

			$this->db->select('SUM(a.quantity) as total_purchase');
			$this->db->from('product_purchase_details a');
			$this->db->where('a.product_id',$product_id);
			$total_purchase = $this->db->get()->row();

			$this->db->select('SUM(b.quantity) as total_sale');
			$this->db->from('invoice_details b');
			$this->db->where('b.product_id',$product_id);
			$total_sale = $this->db->get()->row();

			$available_quantity = ($total_purchase->total_purchase - $total_sale->total_sale);

			$data2 = (object)array(
				'total_product' 	=> $available_quantity,
				'manufacturer_price' => $product_information->manufacturer_price, 
				'price' 			=> $product_information->price, 
				'batch_id'          => $product_information->batch_id,
				'strength'          => $product_information->strength,
				'expeire_date'      => $product_information->expeire_date,
				'manufacturer_id' 	=> $product_information->manufacturer_id, 
				'product_id' 		=> $product_information->product_id, 
				'discount' 		    => $product_information->product_id,
				'product_name' 		=> $product_information->product_name, 
				'product_model' 	=> $product_information->product_model,
				'unit' 				=> $product_information->unit,
				'tax' 				=> $product_information->tax,
				);

			return $data2;
		}else{
			return false;
		}
	}
	//POS customer setup
	public function pos_customer_setup(){
		$query= $this->db->select('*')
						->from('customer_information')
						->like('customer_name','Walking Customer','after')
						->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}

	//Count invoice
	public function invoice_entry()
	{
		$tablecolumn = $this->db->list_fields('tax_collection');
        $num_column = count($tablecolumn)-4;
		$invoice_id = $this->generator(10);
		$invoice_id = strtoupper($invoice_id);
		$quantity = $this->input->post('product_quantity');
		$available_quantity = $this->input->post('available_quantity');
		$cartoon = $this->input->post('cartoon');
        $transection_id=$this->auth->generator(15);
        $createby=$this->session->userdata('user_id');
        $createdate=date('Y-m-d H:i:s');
        // bank info
        $bank_id = $this->input->post('bank_id');
        if(!empty($bank_id)){
       $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id',$bank_id)->get()->row()->bank_name;
    
       $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName',$bankname)->get()->row()->HeadCode;
        }else{
   	   $bankcoaid = '';
       }

		$result = array();
		foreach($available_quantity as $k => $v)
		{
		    if($v < $quantity[$k])
		    {
		       $this->session->set_userdata(array('error_message'=>display('you_can_not_buy_greater_than_available_qnty')));
		       redirect('Cinvoice');
		    }
		}

		
		$product_id = $this->input->post('product_id');
		if ($product_id == null) {
			$this->session->set_userdata(array('error_message'=>display('please_select_product')));
			redirect('Cinvoice/pos_invoice');
		}

		if (($this->input->post('customer_name_others') == null) && ($this->input->post('customer_id') == null ) && ($this->input->post('customer_name') == null )) {
			$this->session->set_userdata(array('error_message'=>display('please_select_customer')));
			redirect(base_url().'Cinvoice');
		}

		
		if(($this->input->post('customer_id') == null ) && ($this->input->post('customer_name') == null ))
		{
			
			
		  	//Customer  basic information adding.
			$data=array(
				'customer_name' 	=> 	$this->input->post('customer_name_others'),
				'customer_address' 	=>	$this->input->post('customer_name_others_address'),
				'customer_mobile' 	=> "",
				'customer_email' 	=> "",
				'status' 			=> 2
				);
              $this->db->insert('customer_information',$data);
			$customer_id=$this->db->insert_id();
			 $c_acc=$this->input->post('customer_name_others').'-'.$customer_id;
            $createby=$this->session->userdata('user_id');
            $createdate=date('Y-m-d H:i:s');
             $coa = $this->Customers->headcode();
           if($coa->HeadCode!=NULL){
                $headcode=$coa->HeadCode+1;
           }else{
                $headcode="10203000001";
            }
			// acc coa for customer
		  $customer_coa = [
             'HeadCode'         => $headcode,
             'HeadName'         => $c_acc,
             'PHeadName'        => 'Customer Receivable',
             'HeadLevel'        => '4',
             'IsActive'         => '1',
             'IsTransaction'    => '1',
             'IsGL'             => '0',
             'HeadType'         => 'A',
             'IsBudget'         => '0',
             'IsDepreciation'   => '0',
             'DepreciationRate' => '0',
             'CreateBy'         => $createby,
             'CreateDate'       => $createdate,
        ];
			
			

			 $this->db->insert('acc_coa',$customer_coa);
			$this->db->select('*');
			$this->db->from('customer_information');
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$json_customer[] = array('label'=>$row->customer_name,'value'=>$row->customer_id);
			}
			$cache_file ='./my-assets/js/admin_js/json/customer.json';
			$customerList = json_encode($json_customer);
			file_put_contents($cache_file,$customerList);
			


			
		  	//Previous balance adding -> Sending to customer model to adjust the data.
			$this->Customers->previous_balance_add(0,$customer_id);
		}
		else{
			$customer_id=$this->input->post('customer_id');
			
			if(empty($customer_id)){
				$this->session->set_userdata(array('error_message'=>'Please Select Customer'));
			redirect(base_url('Cinvoice'));
			}
		}

	
		//Full or partial Payment record.
	

		//Data inserting into invoice table
		$datainv=array(
			'invoice_id'		=>	$invoice_id,
			'customer_id'		=>	$customer_id,
			'date'				=>	$this->input->post('invoice_date'),
			'total_amount'		=>	$this->input->post('grand_total_price'),
			'total_tax'			=>	$this->input->post('total_tax'),
			'stock_id'			=>	$this->input->post('stock_name'),
			'invoice'			=>	$this->number_generator(),
			'invoice_details'   =>  $this->input->post('inva_details'),
			'total_discount' 	=> 	$this->input->post('total_discount'),
			'invoice_discount' 	=> 	$this->input->post('invdcount'),
			'prevous_due'       =>  $this->input->post('previous'),
            'sales_by'          =>  $this->session->userdata('user_id'),
			'status'			=>	1,
			'payment_type'      =>  $this->input->post('paytype'),
			'bank_id'           =>  (!empty($this->input->post('bank_id'))?$this->input->post('bank_id'):null),
		);
		$this->db->insert('invoice',$datainv);
		 for($j=0;$j<$num_column;$j++){
                $taxfield = 'tax'.$j;
                $taxvalue = 'total_tax'.$j;
              $taxdata[$taxfield]=$this->input->post($taxvalue);
            }
            $taxdata['customer_id'] = $customer_id;
            $taxdata['date']        = (!empty($this->input->post('invoice_date'))?$this->input->post('invoice_date'):date('Y-m-d'));
            $taxdata['relation_id'] = $invoice_id;
            if($num_column > 0){
            $this->db->insert('tax_collection',$taxdata);
        }
		
		// Insert to customer_ledger Table 
		$data4 = array(
			'transaction_id'	=>	$transection_id,
			'stock_id'			=>	$this->input->post('stock_name'),
			'customer_id'		=>	$customer_id,
			'invoice_no'		=>	$invoice_id,
			'date'				=>	$this->input->post('invoice_date'),
			'amount'			=>	$this->input->post('paid_amount'),
			'description'		=>	'Cash Paid By Customer',
			'status'			=>	1,
			'd_c'			    =>	'c'
		);
		$this->db->insert('customer_ledger',$data4);
			$paid_amount=$this->input->post('paid_amount');

			$this->db->set('status', '1');
			$this->db->where('customer_id', $customer_id);
			$this->db->update('customer_information');

          

			//Insert to customer_ledger Table 
			$data2 = array(
				'transaction_id'	=>	$transection_id,
				'stock_id'			=>	$this->input->post('stock_name'),
				'customer_id'		=>	$customer_id,
				'receipt_no'		=>	$this->auth->generator(10),
				'date'				=>	$this->input->post('invoice_date'),
				'amount'			=>	$this->input->post('n_total')-(!empty($this->input->post('previous'))?$this->input->post('previous'):0),
				'payment_type'		=>	1,
				'description'		=>	'Medicine Received By Customer',
				'status'			=>	1,
				'd_c'			    =>	'd'

			);
			$this->db->insert('customer_ledger',$data2);
			  //$transection_id=$this->auth->generator(15);
		
		
	   $prinfo  = $this->db->select('product_id,Avg(rate) as product_rate')->from('product_purchase_details')->where_in('product_id',$product_id)->group_by('product_id')->get()->result(); 
      $purchase_ave = [];
      $i=0;
       foreach ($prinfo as $avg) {
      $purchase_ave [] =  $avg->product_rate*$quantity[$i];
      $i++;
      }
        $sumval = array_sum($purchase_ave);

        $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id',$customer_id)->get()->row();
         $headn = $cusifo->customer_name.'-'.$customer_id;
         $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName',$headn)->get()->row();
         $customer_headcode = $coainfo->HeadCode;
            // Cash in Hand debit
      $cc = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INVOICE',
      'VDate'          =>  $createdate,
      'COAID'          =>  1020101,
      'Narration'      =>  'Cash in Hand For Invoice No'.$invoice_id,
      'Debit'          =>  $this->input->post('paid_amount'),
      'Credit'         =>  0,
      'IsPosted'       =>  1,
      'CreateBy'       =>  $createby,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
       ); 
     // bank ledger
     $bankc = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INVOICE',
      'VDate'          =>  $createdate,
      'COAID'          =>  $bankcoaid,
      'Narration'      =>  'Paid amount for Invoice No '.$invoice_id,
      'Debit'          =>  $this->input->post('paid_amount'),
      'Credit'         =>  0,
      'IsPosted'       =>  1,
      'CreateBy'       =>  $createby,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
      ); 
     $banksummary = array(
			'date'			=>	$createdate,
			'ac_type'		=>	'Debit(+)',
			'bank_id'		=>	$this->input->post('bank_id'),
			'description'	=>	'product sale',
			'deposite_id'	=>	$invoice_id,
			'dr'			=>	$this->input->post('paid_amount'),
			'cr'			=>	null,
			'ammount'		=>	$this->input->post('paid_amount'),
			'status'		=>	1
		
		);
       ///Inventory credit
       $inv_credit = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INVOICE',
      'VDate'          =>  $createdate,
      'COAID'          =>  10107,
      'Narration'      =>  'Inventory credit For Invoice No'.$invoice_id,
      'Debit'          =>  0,
      'Credit'         =>  $sumval,//purchase price asbe
      'IsPosted'       => 1,
      'CreateBy'       => $createby,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
     ); 
       $this->db->insert('acc_transaction',$inv_credit);
       
     //Customer debit for Product Value
     $cosdr = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INVOICE',
      'VDate'          =>  $createdate,
      'COAID'          =>  $customer_headcode,
      'Narration'      =>  'Customer debit For Invoice No'.$invoice_id,
      'Debit'          =>  $this->input->post('n_total')-(!empty($this->input->post('previous'))?$this->input->post('previous'):0),
      'Credit'         =>  0,
      'IsPosted'       => 1,
      'CreateBy'       => $createby,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
     ); 
       $this->db->insert('acc_transaction',$cosdr);
       //Product Sale income on acc transaction
     $pro_sale_income = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INVOICE',
      'VDate'          =>  $createdate,
      'COAID'          =>  304,
      'Narration'      =>  'Customer debit For Invoice No'.$invoice_id,
      'Debit'          =>  0,
      'Credit'         =>  $this->input->post('n_total')-(!empty($this->input->post('previous'))?$this->input->post('previous'):0),
      'IsPosted'       => 1,
      'CreateBy'       => $createby,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
     ); 
       $this->db->insert('acc_transaction',$pro_sale_income);

       ///Customer credit for Paid Amount 
       $cuscredit = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INVOICE',
      'VDate'          =>  $createdate,
      'COAID'          =>  $customer_headcode,
      'Narration'      =>  'Customer credit for Paid Amount For Invoice No'.$invoice_id,
      'Debit'          =>  0,
      'Credit'         =>  $this->input->post('paid_amount'),
      'IsPosted'       => 1,
      'CreateBy'       => $createby,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
     ); 
       if(!empty($this->input->post('paid_amount'))){
       		$this->db->insert('acc_transaction',$cuscredit);
       	if($this->input->post('paytype') == 2){
       	$this->db->insert('acc_transaction',$bankc);
       	$this->db->insert('bank_summary',$banksummary);	
       	}
       		if($this->input->post('paytype') == 1){
       	$this->db->insert('acc_transaction',$cc);
       	}
       
     }

		
		$customerinfo = $this->db->select('*')->from('customer_information')->where('customer_id',$customer_id)->get()->row();
		$rate = $this->input->post('product_rate');
		$p_id = $this->input->post('product_id');
		$total_amount   = $this->input->post('total_price');
		$discount_rate  = $this->input->post('discount');
		$tax_amount 	= $this->input->post('tax');
		$stock_id 	= $this->input->post('stock_name');
		$manufacturer_rate_input = $this->input->post('manufacturer_rate'); // ← manufacturer_rate[]
		$batch_id 	    = $this->input->post('batch_id');
		$expiry_date 	    = $this->input->post('expiry_date');
		$inv_ids      = $this->input->post('inv_id'); 
		

		for ($i=0, $n=count($quantity); $i < $n; $i++) {
			$cartoon_quantity = $cartoon[$i];
			$product_quantity = $quantity[$i];
			$product_rate = $rate[$i];
			$pinvoice_id = $inv_ids[$i];
			$product_id = $p_id[$i];
			$total_price = $total_amount[$i];
			$manufacturer_rate = $manufacturer_rate_input[$i];
			$discount = $discount_rate[$i];
			$tax = $tax_amount[$i];
			$batch= $batch_id[$i];
			$expiry= $expiry_date[$i];

			$data1 = array(
				'invoice_details_id'	=>	$this->generator(15),
				'invoice_id'			=>	$invoice_id,
				'product_id'			=>	$product_id,
				'stock_id'			=>	$stock_id,
				'batch_id'              =>  $batch,
				'expiry_date'              =>  $expiry,
				'quantity'				=>	$product_quantity,
				'rate'					=>	$product_rate,
				'discount'           	=>	$discount,
				'tax'           		=>	$tax,
				'paid_amount'           =>	$this->input->post('paid_amount'),
				'due_amount'           	=>	$this->input->post('due_amount'),
				'manufacturer_rate'     =>	$manufacturer_rate,
				'total_price'           =>	$total_price,
				'status'				=>	1,
				'pinvoice_id'				=>	$pinvoice_id
			);

			
			if(!empty($quantity))
			{

				$this->db->insert('invoice_details',$data1);
			}
		}
	
	   $currency_details = $this->Web_settings->retrieve_setting_editdata();
		 $message = 'Mr/Mrs.'.$customerinfo->customer_name.',
        '.'You have purchase  '.$this->input->post('grand_total_price').' '. $currency_details[0]['currency'].' You have paid .'.$this->input->post('paid_amount').' '. $currency_details[0]['currency'];
       
        $this->send_sms($customerinfo->customer_mobile,$message);
		return $invoice_id;
	}

     public function send_sms($phone=null,$msg=null){
        $config_data = $this->db->select('*')->from('sms_settings')->get()->row();
        if($config_data->isinvoice == 0){
            return true;
        }else{
        $recipients=$phone;
         $url      = $config_data->url;//"http://sms.bdtask.com/smsapi"; 
         $senderid =$config_data->sender_id;//"8801847169884";
         $apikey= $config_data->api_key;//"C20029865c42c504afc711.77492546";
         $message=$msg;
 //echo "$url?api_key=$apikey&type=text&contacts=$recipients&senderid=$senderid&msg=$message";
         $urltopost = $config_data->url;//"http://sms.bdtask.com/smsapi";
        $datatopost = array (
        "api_key"  => $apikey,
        "type"     => 'text',
        "senderid" => $senderid,
        "msg"      => $message,
        "contacts" => $recipients
);

$ch = curl_init ($urltopost);
curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
//print_r($result);
 if ($result === false)
{
echo sprintf('<span>%s</span>CURL error:', curl_error($ch));
return;
}
        curl_close($ch);
        return $result;
    }
    }
	//Get manufacturer rate of a product
	public function manufacturer_rate($product_id)
	{
		$this->db->select('manufacturer_price');
		$this->db->from('product_information');
		$this->db->where(array('product_id' => $product_id)); 
		$query = $this->db->get();
		return $query->result_array();
	
	}
	//Retrieve invoice Edit Data
public function retrieve_invoice_editdata($invoice_id)
{
    $this->db->select('
        a.*,
        c.*,
        c.manufacturer_rate,
        a.total_tax,
        b.customer_name,
        c.batch_id,
        c.tax as t_p_tax,
        c.product_id,
		c.stock_id,
        s.stock_name,
        d.product_name,
        d.product_model,
        d.tax,
        d.unit
    ');
    $this->db->from('invoice a');
    $this->db->join('customer_information b','b.customer_id = a.customer_id');
    $this->db->join('invoice_details c','c.invoice_id = a.invoice_id');
    $this->db->join('stock s','s.id = c.stock_id');
    $this->db->join('product_information d','d.product_id = c.product_id');
    $this->db->where('a.invoice_id',$invoice_id);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        return $query->result_array();    
    }
    return false;
}

	//invoice wise prouduct list
	
	//update_invoice
	public function update_invoice()
{
    $tablecolumn = $this->db->list_fields('tax_collection');
    $num_column = count($tablecolumn) - 4;

    $invoice_id         = $this->input->post('invoice_id');
    $customer_id        = $this->input->post('customer_id');
    $product_id         = $this->input->post('product_id');
	$stock_id         = $this->input->post('stock_name');
    $expiry_dates       = $this->input->post('expiry_date');  // this will be an array
    $quantity           = $this->input->post('product_quantity');
    $rate               = $this->input->post('product_rate');
    $total_amount       = $this->input->post('total_price');
    $discount_rate      = $this->input->post('discount');
    $batch_id           = $this->input->post('batch_id');
    $tax_amount         = $this->input->post('tax');
    $manufacturer_rate  = $this->input->post('manufacturer_rate');
    $inv_id             = $this->input->post('inv_id'); // Correct variable for pinvoice_id
	$expiry_dates = $this->input->post('expiry_date'); // array of expiry dates


    $createby           = $this->session->userdata('user_id');
    $createdate         = date('Y-m-d H:i:s');

    // Bank info
    $bank_id    = $this->input->post('bank_id');
    $bankname   = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $bank_id)->get()->row()->bank_name ?? '';
    $bankcoaid  = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode ?? null;

    // Get previous customer ledger transaction ID
    $ab = $this->db->select('transaction_id')->from('customer_ledger')->where('invoice_no', $invoice_id)->get()->result();
    $tran = $ab[0]->transaction_id ?? null;

    // Delete old financial records
    $this->db->where('VNo', $invoice_id)->delete('acc_transaction');
    if ($tran) {
        $this->db->where('transaction_id', $tran)->delete('customer_ledger');
    }
    $this->db->where('deposite_id', $invoice_id)->delete('bank_summary');
    $this->db->where('relation_id', $invoice_id)->delete('tax_collection');

    // Generate new transaction ID
    $tran = $this->auth->generator(15);

    // Receipt data (Credit)
    $datarcpt = array(
        'transaction_id' => $tran,
        'customer_id'    => $customer_id,
        'receipt_no'     => $this->auth->generator(10),
        'date'           => $this->input->post('invoice_date'),
        'amount'         => $this->input->post('paid_amount'),
        'payment_type'   => 1,
        'description'    => 'Cash Paid By Customer',
        'status'         => 1,
        'd_c'            => 'c'
    );

    // Update invoice table
    $data = array(
        'invoice_id'        => $invoice_id,
        'customer_id'       => $customer_id,
        'date'              => $this->input->post('invoice_date'),
        'total_amount'      => $this->input->post('grand_total_price'),
        'total_tax'         => $this->input->post('total_tax'),
        'invoice_details'   => $this->input->post('inva_details'),
        'total_discount'    => $this->input->post('total_discount'),
        'invoice_discount'  => $this->input->post('invdcount'),
        'prevous_due'       => $this->input->post('previous'),
        'sales_by'          => $createby,
        'status'            => 1,
        'payment_type'      => $this->input->post('paytype'),
        'bank_id'           => !empty($bank_id) ? $bank_id : null,
    );

    if (!empty($invoice_id)) {
        $this->db->where('invoice_id', $invoice_id)->update('invoice', $data);

        // Insert tax_collection
        $taxdata = [];
        for ($j = 0; $j < $num_column; $j++) {
            $taxfield = 'tax' . $j;
            $taxvalue = 'total_tax' . $j;
            $taxdata[$taxfield] = $this->input->post($taxvalue);
        }
        $taxdata['customer_id'] = $customer_id;
        $taxdata['date']        = $this->input->post('invoice_date') ?? date('Y-m-d');
        $taxdata['relation_id'] = $invoice_id;
        $this->db->insert('tax_collection', $taxdata);

        // Insert customer ledger (Debit)
        $data2 = array(
            'transaction_id' => $tran,
			'stock_id'			=>	$this->input->post('stock_name'),
            'customer_id'    => $customer_id,
            'invoice_no'     => $invoice_id,
            'date'           => $this->input->post('invoice_date'),
            'amount'         => $this->input->post('n_total') - ($this->input->post('previous') ?? 0),
            'payment_type'   => 1,
            'description'    => 'Medicine Received By Customer',
            'status'         => 1,
            'd_c'            => 'd'
        );

        $this->db->insert('customer_ledger', $data2);
        $this->db->insert('customer_ledger', $datarcpt);
    }

    // Optionally delete old invoice details
    $this->db->where('invoice_id', $invoice_id)->delete('invoice_details');

    // Insert updated invoice details
    for ($i = 0, $n = count($product_id); $i < $n; $i++) {
        $data1 = array(
            'invoice_details_id' => $this->generator(15),
            'invoice_id'         => $invoice_id,
            'product_id'         => $product_id[$i],
            'batch_id'           => $batch_id[$i],
            'quantity'           => $quantity[$i],
            'rate'               => $rate[$i],
			'stock_id'               => $stock_id,
            'manufacturer_rate'  => $manufacturer_rate[$i] ?? 0,
            'discount'           => $discount_rate[$i],
            'total_price'        => $total_amount[$i],
            'tax'                => $tax_amount[$i],
            'paid_amount'        => $this->input->post('paid_amount'),
            'due_amount'         => $this->input->post('due_amount'),
            'pinvoice_id'        => $inv_id[$i] ?? null, // ✅ Correct usage
             'stock_id'         => $this->input->post('stock_name'),
			'expiry_date'        =>$expiry_dates[$i]??null,
        );

        // Uncomment to debug one record
     //echo '<pre>'; var_dump($data1); echo '</pre>'; exit;

        $this->db->insert('invoice_details', $data1);
    }

    return $invoice_id;
}


	//Retrieve invoice_html_data
	public function retrieve_invoice_html_data($invoice_id)
	{
		$this->db->select('a.total_tax,
						a.*,
						b.tin_number,
						b.vat_number,
						b.vat_number,
						b.*,
						c.*,
						d.product_id,
						d.product_name,
						d.unit,
						d.strength,
						d.product_details,
						d.product_model');
		$this->db->from('invoice a');
		$this->db->join('invoice_details c','c.invoice_id = a.invoice_id');
		$this->db->join('customer_information b','b.customer_id = a.customer_id');
		$this->db->join('product_information d','d.product_id = c.product_id');
		$this->db->where('a.invoice_id',$invoice_id);
		//$this->db->where('c.quantity >',0);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	// Delete invoice Item
	public function retrieve_product_data($product_id)
	{
		$this->db->select('manufacturer_price,price,manufacturer_id,tax');
		$this->db->from('product_information a');
		$this->db->join('manufacturer_product b','a.product_id=b.product.id');
		$this->db->where(array('a.product_id' => $product_id,'a.status' => 1)); 
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	//Retrieve company Edit Data
	public function retrieve_company()
	{
		$this->db->select('*');
		$this->db->from('company_information');
		$this->db->limit('1');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	// Delete invoice Item
	public function delete_invoice($invoice_id)
	{	
		//Delete Invoice table
		$this->db->where('invoice_id',$invoice_id);
		$this->db->delete('invoice'); 
		//Delete invoice_details table
		$this->db->where('invoice_id',$invoice_id);
		$this->db->delete('invoice_details'); 
		return true;
	}
	public function invoice_search_list($cat_id,$company_id)
	{
		$this->db->select('a.*,b.sub_category_name,c.category_name');
		$this->db->from('invoices a');
		$this->db->join('invoice_sub_category b','b.sub_category_id = a.sub_category_id');
		$this->db->join('invoice_category c','c.category_id = b.category_id');
		$this->db->where('a.sister_company_id',$company_id);
		$this->db->where('c.category_id',$cat_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	// GET TOTAL PURCHASE PRODUCT
	public function get_total_purchase_item($product_id)
	{
		$this->db->select('SUM(quantity) as total_purchase');
		$this->db->from('product_purchase_details');
		$this->db->where('product_id',$product_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	// GET TOTAL SALES PRODUCT
	public function get_total_sales_item($product_id)
	{
		$this->db->select('SUM(quantity) as total_sale');
		$this->db->from('invoice_details');
		$this->db->where('product_id',$product_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}

	//Get total product
	public function get_total_product($product_id,$manufacturer_id,$stock_id){
		$this->db->select('SUM(a.quantity) as total_purchase,b.*');
		$this->db->from('product_purchase_details a');
		$this->db->join('product_information b','a.product_id=b.product_id');
		$this->db->where('a.product_id',$product_id);
        $this->db->where('a.stock_id',$stock_id);
		$this->db->where('b.manufacturer_id',$manufacturer_id);
		$total_purchase = $this->db->get()->row();

		$this->db->select('SUM(b.quantity) as total_sale');
		$this->db->from('invoice_details b');
		$this->db->where('b.product_id',$product_id);
        $this->db->where('b.stock_id',$stock_id);
		$total_sale = $this->db->get()->row();

		$this->db->select('*');
		$this->db->from('product_information');
		$this->db->where(array('product_id' => $product_id,'status' => 1)); 
		$this->db->where('manufacturer_id',$manufacturer_id);
		$product_information = $this->db->get()->row();

		$available_quantity = ($total_purchase->total_purchase - $total_sale->total_sale);

		$CI =& get_instance();
		$CI->load->model('Web_settings');
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		
		$data2 = array(
			'total_product'  => $available_quantity, 
			'manufacturer_price' => $product_information->manufacturer_price, 
			'price' 	     => $product_information->price, 
			'manufacturer_id' 	 => $product_information->manufacturer_id,
			'unit' 	 		 => $product_information->unit,
			'tax' 	 		 => $product_information->tax,
			'discount_type'  => $currency_details[0]['discount_type'],
			);

		return $data2;
	}
// product information retrieve by product id
	public function get_total_product_invoic($product_id,$stock_id){
		$this->db->select('SUM(a.quantity) as total_purchase');
		$this->db->from('product_purchase_details a');
		$this->db->where('a.product_id',$product_id);
        $this->db->where('a.stock_id',$stock_id);
		$total_purchase = $this->db->get()->row();

		$this->db->select('SUM(b.quantity) as total_sale');
		$this->db->from('invoice_details b');
		$this->db->where('b.product_id',$product_id);
         $this->db->where('b.stock_id',$stock_id);
		$total_sale = $this->db->get()->row();

		$this->db->select('*');
		$this->db->from('product_information');
		$this->db->where(array('product_id' => $product_id,'status' => 1)); 
		$product_information = $this->db->get()->row();

		$available_quantity = ($total_purchase->total_purchase - $total_sale->total_sale);

		$CI =& get_instance();
		$CI->load->model('Web_settings');
		$CI->load->model('Products');
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $content = $CI->Products->batch_search_item($product_id,$stock_id);

       $html = "";
if (empty($content)) {
    $html .= "No Product Found!";
} else {
    $html .= "<select name=\"batch_id[]\" class=\"batch_id_1 form-control\" id=\"batch_id_1\" required=\"required\">";
    $html .= "<option>" . display('select_one') . "</option>";

    foreach ($content as $product) {
        $batch_id = $product['batch_id'];
        $product_id = $product['product_id'];

        // Calculate available quantity for this batch
        $total_purchase = $this->db->select_sum('quantity')
                                   ->where(['product_id' => $product_id, 'batch_id' => $batch_id, 'stock_id' => $stock_id])
                                   ->get('product_purchase_details')
                                   ->row()->quantity ?? 0;

        $total_sale = $this->db->select_sum('quantity')
                               ->where(['product_id' => $product_id, 'batch_id' => $batch_id, 'stock_id' => $stock_id])
                               ->get('invoice_details')
                               ->row()->quantity ?? 0;

        $available_qty = $total_purchase - $total_sale;

        // Only display if available quantity > 0
        if ($available_qty > 0) {
            $html .= "<option value=\"" . $batch_id . "\">" . $batch_id . " (Qty: " . $available_qty . ")</option>";
        }
    }

    $html .= "</select>";
}

	      $tablecolumn = $this->db->list_fields('tax_collection');
               $num_column = count($tablecolumn)-4;
  $taxfield='';
  $taxvar = [];
   for($i=0;$i<$num_column;$i++){
    $taxfield = 'tax'.$i;
    $data2[$taxfield] = $product_information->$taxfield;
    $taxvar[$i]       = $product_information->$taxfield;
    $data2['taxdta']  = $taxvar;
    //
   }

		
			$data2['total_product']      = $available_quantity; 
			$data2['manufacturer_price'] = $product_information->manufacturer_price; 
			$data2['price'] 	         = $product_information->price; 
			$data2['manufacturer_id'] 	 = $product_information->manufacturer_id;
			$data2['unit'] 	 		     = $product_information->unit;
			$data2['tax'] 	 		     = $product_information->tax;
			$data2['batch'] 	 	     = $html;
			$data2['discount_type']      = $currency_details[0]['discount_type'];
			$data2['txnmber']            = $num_column;
			

		return $data2;
	}
	//This function is used to Generate Key
	public function generator($lenth)
	{
		$number=array("1","2","3","4","5","6","7","8","9");
	
		for($i=0; $i<$lenth; $i++)
		{
			$rand_value=rand(0,8);
			$rand_number=$number["$rand_value"];
		
			if(empty($con))
			{ 
			$con=$rand_number;
			}
			else
			{
			$con="$con"."$rand_number";}
		}
		return $con;
	}
	//NUMBER GENERATOR
	public function number_generator()
	{
		$this->db->select_max('invoice', 'invoice_no');
		$query = $this->db->get('invoice');	
		$result = $query->result_array();	
		$invoice_no = $result[0]['invoice_no'];
		if ($invoice_no !='') {
			$invoice_no = $invoice_no + 1;	
		}else{
			$invoice_no = 1000;
		}
		return $invoice_no;		
	}
	// stock availavel by batch id
	public function get_total_product_batch($batch_id, $product_id,$stock_id)
{
    $CI =& get_instance();
    $CI->load->model('Web_settings');

    // 🔹 Get total purchase
    $this->db->select('a.expeire_date, SUM(a.quantity) as total_purchase');
    $this->db->from('product_purchase_details a');
    $this->db->where('a.batch_id', $batch_id);
    $this->db->where('a.product_id', $product_id); // ✅ fixed alias
     $this->db->where('a.stock_id', $stock_id); 
    $this->db->order_by('a.id', 'desc');
    $total_purchase = $this->db->get()->row();

    // 🔹 Get total sales
    $this->db->select('SUM(b.quantity) as total_sale');
    $this->db->from('invoice_details b');
    $this->db->where('b.batch_id', $batch_id);
    $this->db->where('b.product_id', $product_id);
    $this->db->where('b.stock_id', $stock_id); 
    $total_sale = $this->db->get()->row();

    // 🔹 Safe values if no rows found
    $purchase_qty = !empty($total_purchase->total_purchase) ? $total_purchase->total_purchase : 0;
    $sale_qty     = !empty($total_sale->total_sale) ? $total_sale->total_sale : 0;

    $available_quantity = $purchase_qty - $sale_qty;

    $currency_details = $CI->Web_settings->retrieve_setting_editdata();

    $data['total_product'] = $available_quantity;
    $data['expire_date']   = !empty($total_purchase->expeire_date) ? $total_purchase->expeire_date : null;

    return $data;
}

	
     public function service_invoice_taxinfo($invoice_id){
       return $this->db->select('*')   
            ->from('tax_collection')
            ->where('relation_id',$invoice_id)
            ->get()
            ->result_array(); 
    }

     public function allproduct(){
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->limit(100);
        $query = $this->db->get();
        $itemlist=$query->result();
        return $itemlist;
        }

         public function searchprod($category_id = null,$pname= null)
    { 
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->like('category_id',$category_id);
        $this->db->like('product_name',$pname);
        $this->db->limit(50);
        $query = $this->db->get();
        $itemlist=$query->result();
        return $itemlist;
    }

       public function type_dropdown()
    {
        $data = $this->db->select("*")
            ->from('product_type')
            ->get()
            ->result();

        $list[''] = 'Select '.display('type_name');
        if (!empty($data)) {
            foreach($data as $value)
                $list[$value->type_name] = $value->type_name;
            return $list;
        } else {
            return false; 
        }
    }

   public function category_dropdown()
    {
        $data = $this->db->select("*")
            ->from('product_category')
            ->get()
            ->result();

        $list = array('' => 'select_category');
        if (!empty($data)) {
            foreach($data as $value)
                $list[$value->category_id] = $value->category_name;
            //print_r($list);exit();
            return $list;
        } else {
            return false; 
        }
    }
      public function todays_invoice(){
        $this->db->select('a.*,b.customer_name');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
        $this->db->where('a.date',date('Y-m-d'));
        $this->db->order_by('a.invoice', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

	public function autocompletproductdata($product_name, $stock_id) {
		$this->db->select('pi.*, ppd.stock_id, ppd.purchase_id, ppd.quantity, ppd.rate')
    ->from('product_information pi')
    ->join('product_purchase_details ppd', 'ppd.product_id = pi.product_id')
    ->like('pi.product_name', $product_name, 'after') // search products starting with name
    ->where('ppd.stock_id', $stock_id) // filter by stock
    ->group_by('pi.product_id') // ensure each product appears once
    ->order_by('pi.product_name', 'asc')
    ->limit(15);

$query = $this->db->get();
return $query->result_array();

	
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}
	
	
}
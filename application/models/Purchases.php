<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Purchases extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	//Count purchase
	public function count_purchase()
	{
		$this->db->select('a.*,b.manufacturer_name');
		$this->db->from('product_purchase a');
		$this->db->join('manufacturer_information b','b.manufacturer_id = a.manufacturer_id');
		$this->db->order_by('a.purchase_date','desc');
		$query = $this->db->get();
		
		$last_query =  $this->db->last_query();
		if ($query->num_rows() > 0) {
			return $query->num_rows();	
		}
		return false;
	}

    public function getPurchaseList($postData=null){
        $this->load->library('occational');
        $this->load->model('Web_settings');
        $currency_details = $this->Web_settings->retrieve_setting_editdata();
    
        $CI =& get_instance(); // Get CI instance
        $user_id = $CI->session->userdata('user_id'); // Current logged-in user
    
        $response = array();
        $fromdate = $this->input->post('fromdate');
        $todate   = $this->input->post('todate');
        $datbetween = !empty($fromdate) ? "(a.purchase_date BETWEEN '$fromdate' AND '$todate')" : "";
    
        ## Read DataTables parameters
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length'];
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'];
        $searchValue = $postData['search']['value'];
    
        ## Search Query
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery = " (b.manufacturer_name LIKE '%".$searchValue."%' 
                            OR a.chalan_no LIKE '%".$searchValue."%' 
                            OR a.purchase_date LIKE '%".$searchValue."%' 
                            OR s.stock_name LIKE '%".$searchValue."%')";
        }
    
        ## Total number of records without filtering
        $this->db->select('COUNT(a.purchase_id) as allcount');
        $this->db->from('product_purchase a');
        $this->db->join('manufacturer_information b', 'b.manufacturer_id = a.manufacturer_id','left');
        $this->db->join('stock s', 's.id = a.stock_id','left'); // join stock table
    
        if(!empty($datbetween)) $this->db->where($datbetween);
        if($searchValue != '') $this->db->where($searchQuery);
    
        // ✅ Filter only purchases assigned to current user
        $this->db->where("s.assign_users LIKE '%\"$user_id\"%'");
    
        $records = $this->db->get()->result();
        $totalRecords = isset($records[0]->allcount) ? (int)$records[0]->allcount : 0;
    
        ## Total number of record with filtering
        $this->db->select('COUNT(a.purchase_id) as allcount');
        $this->db->from('product_purchase a');
        $this->db->join('manufacturer_information b', 'b.manufacturer_id = a.manufacturer_id','left');
        $this->db->join('stock s', 's.id = a.stock_id','left');
    
        if(!empty($datbetween)) $this->db->where($datbetween);
        if($searchValue != '') $this->db->where($searchQuery);
        $this->db->where("s.assign_users LIKE '%\"$user_id\"%'");
    
        $records = $this->db->get()->result();
        $totalRecordwithFilter = isset($records[0]->allcount) ? (int)$records[0]->allcount : 0;
    
        ## Fetch records
        $this->db->select('a.*, b.manufacturer_name, s.stock_name');
        $this->db->from('product_purchase a');
        $this->db->join('manufacturer_information b', 'b.manufacturer_id = a.manufacturer_id','left');
        $this->db->join('stock s', 's.id = a.stock_id','left');
    
        if(!empty($datbetween)) $this->db->where($datbetween);
        if($searchValue != '') $this->db->where($searchQuery);
        $this->db->where("s.assign_users LIKE '%\"$user_id\"%'");
    
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
    
        $data = array();
        $sl = 1;
        foreach($records as $record){
            $button = '';
            $base_url = base_url();
            $jsaction = "return confirm('Are You Sure ?')";
    
            $button .= '<a href="'.$base_url.'Cpurchase/purchase_details_data/'.$record->purchase_id.'" class="btn btn-success btn-sm" title="'.display('purchase_details').'"><i class="fa fa-window-restore"></i></a>';
            if($this->permission1->method('manage_purchase','update')->access()){
                $button .= '<a href="'.$base_url.'Cpurchase/purchase_update_form/'.$record->purchase_id.'" class="btn btn-info btn-sm" title="'.display('update').'"><i class="fa fa-pencil"></i></a>';
            }
            if($this->permission1->method('manage_purchase','delete')->access()){
                $button .= '<a href="'.$base_url.'Cpurchase/delete_purchase/'.$record->purchase_id.'" class="btn btn-danger btn-sm" title="'.display('delete').'" onclick="'.$jsaction.'"><i class="fa fa-trash"></i></a>';
            }
    
            $data[] = array(
                'sl' => $sl,
                'chalan_no' => $record->chalan_no,
                'purchase_id' => $record->purchase_id,
                'manufacturer_name' => $record->manufacturer_name,
                'stock_name' => $record->stock_name, // ✅ Added stock_name
                'purchase_date' => $record->purchase_date,
                'total_amount' => $record->grand_total_amount,
                'button' => $button,
            );
    
            $sl++;
        }
    
        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
        );
    
        return $response;
    }
    

    //purchese detail
    public function getDetailPurchaseList($postData = null)
{
    $this->load->library('occational');
    $this->load->model('Web_settings');
    $currency_details = $this->Web_settings->retrieve_setting_editdata();

    $CI =& get_instance();
    $user_id = $CI->session->userdata('user_id'); // Current logged-in user

    $fromdate = $this->input->post('fromdate');
    $todate   = $this->input->post('todate');

    // DataTables variables
    $draw = isset($postData['draw']) ? intval($postData['draw']) : 1;
    $start = isset($postData['start']) ? intval($postData['start']) : 0;
    $rowperpage = isset($postData['length']) ? intval($postData['length']) : 10;
    $columnIndex = isset($postData['order'][0]['column']) ? intval($postData['order'][0]['column']) : 4;
    $columnSortOrder = isset($postData['order'][0]['dir']) ? $postData['order'][0]['dir'] : 'desc';
    $searchValue = isset($postData['search']['value']) ? $postData['search']['value'] : '';

    $columns = [
        'product_purchase_details.purchase_id',
        'pi.product_name',
        'product_purchase_details.quantity',
        'product_purchase_details.rate',
        'product_purchase_details.total_amount',
        'mi.manufacturer_name',
        's.stock_name'
    ];
    $columnName = isset($columns[$columnIndex]) ? $columns[$columnIndex] : 'product_purchase_details.total_amount';

    // -----------------------------------
    // 1. Total Records (no filtering)
    // -----------------------------------
    $this->db->select('COUNT(*) as allcount');
    $this->db->from('product_purchase_details');
    $this->db->join('product_information pi', 'product_purchase_details.product_id = pi.product_id', 'left');
    $this->db->join('product_purchase p', 'product_purchase_details.purchase_id = p.purchase_id', 'left');
    $this->db->join('manufacturer_information mi', 'p.manufacturer_id = mi.manufacturer_id', 'left');
    $this->db->join('stock s', 's.id = p.stock_id', 'left'); // join stock table

    // Filter by stocks assigned to the user
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'");

    $totalRecords = $this->db->get()->row()->allcount;

    // -----------------------------------
    // 2. Total Records With Filtering
    // -----------------------------------
    $this->db->select('COUNT(*) as allcount');
    $this->db->from('product_purchase_details');
    $this->db->join('product_information pi', 'product_purchase_details.product_id = pi.product_id', 'left');
    $this->db->join('product_purchase p', 'product_purchase_details.purchase_id = p.purchase_id', 'left');
    $this->db->join('manufacturer_information mi', 'p.manufacturer_id = mi.manufacturer_id', 'left');
    $this->db->join('stock s', 's.id = p.stock_id', 'left'); // join stock table

    if (!empty($fromdate) && !empty($todate)) {
        $this->db->where('p.purchase_date >=', $fromdate);
        $this->db->where('p.purchase_date <=', $todate);
    }

    if ($searchValue != '') {
        $this->db->group_start();
        $this->db->like('mi.manufacturer_name', $searchValue);
        $this->db->or_like('pi.product_name', $searchValue);
        $this->db->or_like('product_purchase_details.purchase_id', $searchValue);
        $this->db->or_like('s.stock_name', $searchValue); // include stock_name in search
        $this->db->group_end();
    }

    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'");

    $totalRecordwithFilter = $this->db->get()->row()->allcount;

    // -----------------------------------
    // 3. Fetch Records with Filtering and Pagination
    // -----------------------------------
    $this->db->select('
        product_purchase_details.*, 
        pi.product_name, 
        pi.manufacturer_id, 
        mi.manufacturer_name,
        p.purchase_date,
        p.manufacturer_id,
        p.chalan_no,
        s.stock_name
    ');
    $this->db->from('product_purchase_details');
    $this->db->join('product_information pi', 'product_purchase_details.product_id = pi.product_id', 'left');
    $this->db->join('product_purchase p', 'product_purchase_details.purchase_id = p.purchase_id', 'left');
    $this->db->join('manufacturer_information mi', 'p.manufacturer_id = mi.manufacturer_id', 'left');
    $this->db->join('stock s', 's.id = p.stock_id', 'left'); // join stock table

    if (!empty($fromdate) && !empty($todate)) {
        $this->db->where('p.purchase_date >=', $fromdate);
        $this->db->where('p.purchase_date <=', $todate);
    }

    if ($searchValue != '') {
        $this->db->group_start();
        $this->db->like('mi.manufacturer_name', $searchValue);
        $this->db->or_like('pi.product_name', $searchValue);
        $this->db->or_like('product_purchase_details.purchase_id', $searchValue);
        $this->db->or_like('s.stock_name', $searchValue);
        $this->db->group_end();
    }

    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'");

    $this->db->order_by($columnName, $columnSortOrder);
    $this->db->limit($rowperpage, $start);
    $records = $this->db->get()->result();

    // -----------------------------------
    // 4. Format Data for DataTables
    // -----------------------------------
    $data = [];
    $sl = $start + 1;

    foreach ($records as $record) {
        $data[] = array(
            'sl'                => $sl++,
            'purchase_id'       => $record->purchase_id,
            'product_name'      => $record->product_name,
            'quantity'          => $record->quantity,
            'rate'              => $record->rate,
            'chalan_no'        =>$record->chalan_no,
            'total_amount'      => $record->total_amount,
            'manufacturer_name' => $record->manufacturer_name,
            'stock_name'        => $record->stock_name, // ✅ stock_name included
            'purchase_date'     => $record->purchase_date,
        );
    }

    // -----------------------------------
    // 5. Return JSON Response
    // -----------------------------------
    $response = array(
        "draw" => $draw,
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    );

    return $response;
}





	//purchase List
	public function purchase_list($per_page,$page)
	{
		$this->db->select('a.*,b.manufacturer_name');
		$this->db->from('product_purchase a');
		$this->db->join('manufacturer_information b','b.manufacturer_id = a.manufacturer_id');
		$this->db->order_by('a.purchase_date','desc');
		$this->db->order_by('purchase_id','desc');
		$this->db->limit($per_page,$page);
		$query = $this->db->get();
		
		$last_query =  $this->db->last_query();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}
//purchase info by invoice id
 public function purchase_list_invoice_id($invoice_no)
	{
		$this->db->select('a.*,b.manufacturer_name');
		$this->db->from('product_purchase a');
		$this->db->join('manufacturer_information b','b.manufacturer_id = a.manufacturer_id');
		$this->db->where('a.chalan_no',$invoice_no);
		$this->db->order_by('a.purchase_date','desc');
		$this->db->order_by('purchase_id','desc');
		$query = $this->db->get();
		
		$last_query =  $this->db->last_query();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//Select All manufacturer List
	public function select_all_manufacturer()
	{
		$query = $this->db->select('*')
					->from('manufacturer_information')
					->where('status','1')
					->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}

	//purchase Search  List
	public function purchase_by_search($manufacturer_id)
	{
		$this->db->select('a.*,b.manufacturer_name');
		$this->db->from('product_purchase a');
		$this->db->join('manufacturer_information b','b.manufacturer_id = a.manufacturer_id');
		$this->db->where('b.manufacturer_id',$manufacturer_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//Count purchase
	public function purchase_entry()
{
    $purchase_id = date('YmdHis');
    $p_id = $this->input->post('product_id');
    $chalan_no = $this->input->post('chalan_no');
    $manufacturer_id = $this->input->post('manufacturer_id');
    $stock_id = $this->input->post('stock_name');

    // Manufacturer COA head
    $manufacturer_info = $this->db->select('*')
        ->from('manufacturer_information')
        ->where('manufacturer_id', $manufacturer_id)
        ->get()
        ->row();

    $manuf_head = $manufacturer_info->manufacturer_name . '-' . $manufacturer_id;
    $manuf_coa = $this->db->select('*')
        ->from('acc_coa')
        ->where('HeadName', $manuf_head)
        ->get()
        ->row();

    $receive_by = $this->session->userdata('user_id');
    $receive_date = date('Y-m-d');
    $createdate = date('Y-m-d H:i:s');
    $bank_id = $this->input->post('bank_id');

    if (!empty($bank_id)) {
        $bankname = $this->db->select('bank_name')
            ->from('bank_add')
            ->where('bank_id', $bank_id)
            ->get()
            ->row()
            ->bank_name;

        $bankcoaid = $this->db->select('HeadCode')
            ->from('acc_coa')
            ->where('HeadName', $bankname)
            ->get()
            ->row()
            ->HeadCode;
    }

    // Check duplicate
    $this->db->select('*');
    $this->db->from('product_purchase');
    $this->db->where('status', 1);
    $this->db->where('chalan_no', $chalan_no);
    $this->db->where('manufacturer_id', $manufacturer_id);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        $this->session->set_flashdata('error_message', display('Choose_another_invno'));
        redirect(base_url('Cpurchase'));
        exit();
    }

    // Insert into product_purchase
    $data = array(
        'purchase_id'       => $purchase_id,
        'chalan_no'         => $chalan_no,
        'manufacturer_id'   => $manufacturer_id,
        'stock_id'   => $stock_id,
        'grand_total_amount'=> $this->input->post('grand_total_price'),
        'total_discount'    => $this->input->post('total_discount'),
        'purchase_date'     => $this->input->post('purchase_date'),
        'purchase_details'  => $this->input->post('purchase_details'),
        'status'            => 1,
        'bank_id'           => $bank_id,
        'payment_type'      => $this->input->post('paytype'),
    );
    $this->db->insert('product_purchase', $data);

    // Transactions
    $purchasecoatran = array(
        'VNo'         => $purchase_id,
        'Vtype'       => 'Purchase',
        'VDate'       => $this->input->post('purchase_date'),
        'COAID'       => $manuf_coa->HeadCode,
        'Narration'   => 'Purchase No.' . $purchase_id,
        'Debit'       => 0,
        'Credit'      => $this->input->post('grand_total_price'),
        'IsPosted'    => 1,
        'CreateBy'    => $receive_by,
        'CreateDate'  => $receive_date,
        'IsAppove'    => 1
    );

    $coscr = array(
        'VNo'         => $purchase_id,
        'Vtype'       => 'Purchase',
        'VDate'       => $this->input->post('purchase_date'),
        'COAID'       => 10107,
        'Narration'   => 'Inventory Debit For Purchase No' . $purchase_id,
        'Debit'       => $this->input->post('grand_total_price'),
        'Credit'      => 0,
        'IsPosted'    => 1,
        'CreateBy'    => $receive_by,
        'CreateDate'  => $createdate,
        'IsAppove'    => 1
    );

    $expense = array(
        'VNo'         => $purchase_id,
        'Vtype'       => 'Purchase',
        'VDate'       => $this->input->post('purchase_date'),
        'COAID'       => 402,
        'Narration'   => 'Company Credit For Purchase No' . $purchase_id,
        'Debit'       => $this->input->post('grand_total_price'),
        'Credit'      => 0,
        'IsPosted'    => 1,
        'CreateBy'    => $receive_by,
        'CreateDate'  => $createdate,
        'IsAppove'    => 1
    );

    $cashinhand = array(
        'VNo'         => $purchase_id,
        'Vtype'       => 'Purchase',
        'VDate'       => $this->input->post('purchase_date'),
        'COAID'       => 1020101,
        'Narration'   => 'Cash in Hand For Purchase No' . $purchase_id,
        'Debit'       => 0,
        'Credit'      => $this->input->post('grand_total_price'),
        'IsPosted'    => 1,
        'CreateBy'    => $receive_by,
        'CreateDate'  => $createdate,
        'IsAppove'    => 1
    );

    $manufacurerdebit = array(
        'VNo'         => $purchase_id,
        'Vtype'       => 'Purchase',
        'VDate'       => $this->input->post('purchase_date'),
        'COAID'       => $manuf_coa->HeadCode,
        'Narration'   => 'Purchase No.' . $purchase_id,
        'Debit'       => $this->input->post('grand_total_price'),
        'Credit'      => 0,
        'IsPosted'    => 1,
        'CreateBy'    => $receive_by,
        'CreateDate'  => $receive_date,
        'IsAppove'    => 1
    );

    $bankc = array(
        'VNo'         => $purchase_id,
        'Vtype'       => 'Purchase',
        'VDate'       => $this->input->post('purchase_date'),
        'COAID'       => $bankcoaid,
        'Narration'   => 'Paid amount for Purchase No ' . $purchase_id,
        'Debit'       => 0,
        'Credit'      => $this->input->post('grand_total_price'),
        'IsPosted'    => 1,
        'CreateBy'    => $receive_by,
        'CreateDate'  => $createdate,
        'IsAppove'    => 1
    );

    $banksummary = array(
        'date'         => $this->input->post('purchase_date'),
        'ac_type'      => 'Credit(-)',
        'bank_id'      => $bank_id,
        'description'  => 'product purchase',
        'deposite_id'  => $purchase_id,
        'dr'           => null,
        'cr'           => $this->input->post('grand_total_price'),
        'ammount'      => $this->input->post('grand_total_price'),
        'status'       => 1
    );

    $ledger = array(
        'transaction_id'   => $purchase_id,
        'stock_id'   => $stock_id,
        'chalan_no'        => $chalan_no,
        'manufacturer_id'  => $manufacturer_id,
        'amount'           => $this->input->post('grand_total_price'),
        'date'             => $this->input->post('purchase_date'),
        'description'      => 'Purchase From Manufacturer. ' . $this->input->post('purchase_details'),
        'status'           => 1,
        'd_c'              => 'c',
    );

    $ledger_debit = $ledger;
    $ledger_debit['d_c'] = 'd';

    // Insert transactions
    $this->db->insert('manufacturer_ledger', $ledger);
    $this->db->insert('acc_transaction', $coscr);
    $this->db->insert('acc_transaction', $purchasecoatran);
    $this->db->insert('acc_transaction', $expense);

    if ($this->input->post('paytype') == 2) {
        $this->db->insert('acc_transaction', $bankc);
        $this->db->insert('bank_summary', $banksummary);
        $this->db->insert('manufacturer_ledger', $ledger_debit);
        $this->db->insert('acc_transaction', $manufacurerdebit);
    }

    if ($this->input->post('paytype') == 1) {
        $this->db->insert('acc_transaction', $cashinhand);
        $this->db->insert('manufacturer_ledger', $ledger_debit);
        $this->db->insert('acc_transaction', $manufacurerdebit);
    }

    // Product Purchase Details
    $rate = $this->input->post('product_rate');
    $quantity = $this->input->post('product_quantity');
    $t_price = $this->input->post('total_price');
    $discount = $this->input->post('discount');
    $sell_rate = $this->input->post('sale_rate'); // input name in form must be 'sale_rate'
    $batch = $this->input->post('batch_id');
    $exp_date = $this->input->post('expeire_date');

    // Generate new invoice number
    $this->db->select_max('invoice_id');
    $latest = $this->db->get('product_purchase_details')->row();
    $last_invoice = isset($latest->invoice_id) ? $latest->invoice_id : null;

    $num = $last_invoice ? ((int) substr($last_invoice, 3)) + 1 : 1;
    $new_invoice = 'INV' . str_pad($num, 2, '0', STR_PAD_LEFT);

    for ($i = 0, $n = count($p_id); $i < $n; $i++) {
        $data1 = array(
            'purchase_detail_id'=> $this->generator(15),
            'purchase_id'       => $purchase_id,
            'product_id'        => $p_id[$i],
            'quantity'          => $quantity[$i],
            'rate'              => $rate[$i],
            'total_amount'      => $t_price[$i],
            'sell_price'        => $sell_rate[$i],
            'discount'          => $discount[$i],
            'batch_id'          => $batch[$i],
            'invoice_id'        => $new_invoice,
            'stock_id'        => $stock_id,
            'expeire_date' => !empty($exp_date[$i]) ? $exp_date[$i] : '2075-01-01',
            'status'            => 1
        );

        if (!empty($quantity[$i])) {
            $this->db->insert('product_purchase_details', $data1);
        }
    }

    // Send SMS
    $message = 'Mr/Mrs. ' . $manufacturer_info->manufacturer_name . ', You have Sold ' . $this->input->post('grand_total_price');
    $this->send_sms($manufacturer_info->mobile, $message);

    return $purchase_id;
}

	//send sms 
     public function send_sms($phone=null,$msg=null){
        $config_data = $this->db->select('*')->from('sms_settings')->get()->row();
        if($config_data->ispurchase == 0){
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
	//Retrieve purchase Edit Data
	public function retrieve_purchase_editdata($purchase_id)
	{
		$this->db->select('a.*,
						b.*,
						c.product_id,
						c.product_name,
						c.product_model,
						d.manufacturer_id,
                        b.stock_id,
						d.manufacturer_name'
						);
		$this->db->from('product_purchase a');
		$this->db->join('product_purchase_details b','b.purchase_id =a.purchase_id');
		$this->db->join('product_information c','c.product_id =b.product_id');
		$this->db->join('manufacturer_information d','d.manufacturer_id = a.manufacturer_id');
		$this->db->where('a.purchase_id',$purchase_id);
		$this->db->order_by('a.purchase_details','asc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
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
	//Update Categories
public function update_purchase()
{
    $this->db->trans_start(); // Begin DB transaction

    $purchase_id = $this->input->post('purchase_id');
    $bank_id = $this->input->post('bank_id');
    $manufacturer_id = $this->input->post('manufacturer_id');
    $receive_by = $this->session->userdata('user_id');
    $receive_date = date('Y-m-d');
    $createdate = date('Y-m-d H:i:s');
    $purchase_date = $this->input->post('purchase_date');
    $grand_total_price = $this->input->post('grand_total_price');

    // Get bank chart of account ID
    if (!empty($bank_id)) {
        $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $bank_id)->get()->row()->bank_name;
        $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
    }

    // Manufacturer chart of account
    $manufacturer_info = $this->db->select('*')->from('manufacturer_information')->where('manufacturer_id', $manufacturer_id)->get()->row();
    $manuf_head = $manufacturer_info->manufacturer_name . '-' . $manufacturer_id;
    $manuf_coa = $this->db->select('*')->from('acc_coa')->where('HeadName', $manuf_head)->get()->row();

    // Update product_purchase table
    $data = array(
        'purchase_id'        => $purchase_id,
        'chalan_no'          => $this->input->post('chalan_no'),
        'manufacturer_id'    => $manufacturer_id,
        'grand_total_amount' => $grand_total_price,
        'total_discount'     => $this->input->post('total_discount'),
        'purchase_date'      => $purchase_date,
        'purchase_details'   => $this->input->post('purchase_details'),
        'bank_id'            => $bank_id,
        'payment_type'       => $this->input->post('paytype'),
    );

    // Ledger and transaction data
    $ledger = array(
        'transaction_id'  => $purchase_id,
        'chalan_no'       => $this->input->post('chalan_no'),
        'manufacturer_id' => $manufacturer_id,
        'amount'          => $grand_total_price,
        'date'            => $purchase_date,
        'description'     => 'Purchase From Manufacturer. ' . $this->input->post('purchase_details'),
        'status'          => 1,
        'd_c'             => 'c',
    );

    $ledger_debit = $ledger;
    $ledger_debit['d_c'] = 'd';

    $cashinhand = array(
        'VNo'        => $purchase_id,
        'Vtype'      => 'Purchase',
        'VDate'      => $purchase_date,
        'COAID'      => 1020101,
        'Narration'  => 'Cash in Hand For Purchase No ' . $purchase_id,
        'Debit'      => 0,
        'Credit'     => $grand_total_price,
        'IsPosted'   => 1,
        'CreateBy'   => $receive_by,
        'CreateDate' => $createdate,
        'IsAppove'   => 1
    );

    $bankc = array(
        'VNo'        => $purchase_id,
        'Vtype'      => 'Purchase',
        'VDate'      => $purchase_date,
        'COAID'      => $bankcoaid,
        'Narration'  => 'Paid amount for Purchase No ' . $purchase_id,
        'Debit'      => 0,
        'Credit'     => $grand_total_price,
        'IsPosted'   => 1,
        'CreateBy'   => $receive_by,
        'CreateDate' => $createdate,
        'IsAppove'   => 1
    );

    $banksummary = array(
        'date'         => $purchase_date,
        'ac_type'      => 'Credit(-)',
        'bank_id'      => $bank_id,
        'description'  => 'product purchase',
        'deposite_id'  => $purchase_id,
        'dr'           => null,
        'cr'           => $grand_total_price,
        'ammount'      => $grand_total_price,
        'status'       => 1
    );

    $purchasecoatran = array(
        'VNo'        => $purchase_id,
        'Vtype'      => 'Purchase',
        'VDate'      => $purchase_date,
        'COAID'      => $manuf_coa->HeadCode,
        'Narration'  => 'Purchase No. ' . $purchase_id,
        'Debit'      => 0,
        'Credit'     => $grand_total_price,
        'IsPosted'   => 1,
        'CreateBy'   => $receive_by,
        'CreateDate' => $receive_date,
        'IsAppove'   => 1
    );

    $manufacurerdebit = array(
        'VNo'        => $purchase_id,
        'Vtype'      => 'Purchase',
        'VDate'      => $purchase_date,
        'COAID'      => $manuf_coa->HeadCode,
        'Narration'  => 'Purchase No. ' . $purchase_id,
        'Debit'      => $grand_total_price,
        'Credit'     => 0,
        'IsPosted'   => 1,
        'CreateBy'   => $receive_by,
        'CreateDate' => $receive_date,
        'IsAppove'   => 1
    );

    $coscr = array(
        'VNo'        => $purchase_id,
        'Vtype'      => 'Purchase',
        'VDate'      => $purchase_date,
        'COAID'      => 10107,
        'Narration'  => 'Inventory Debit For Purchase No ' . $purchase_id,
        'Debit'      => $grand_total_price,
        'Credit'     => 0,
        'IsPosted'   => 1,
        'CreateBy'   => $receive_by,
        'CreateDate' => $createdate,
        'IsAppove'   => 1
    );

    $expense = array(
        'VNo'        => $purchase_id,
        'Vtype'      => 'Purchase',
        'VDate'      => $purchase_date,
        'COAID'      => 402,
        'Narration'  => 'Company Credit For Purchase No ' . $purchase_id,
        'Debit'      => $grand_total_price,
        'Credit'     => 0,
        'IsPosted'   => 1,
        'CreateBy'   => $receive_by,
        'CreateDate' => $createdate,
        'IsAppove'   => 1
    );

    // Update core table
    $this->db->where('purchase_id', $purchase_id);
    $this->db->update('product_purchase', $data);

    // Remove old records
    $this->db->where('transaction_id', $purchase_id)->delete('manufacturer_ledger');
    $this->db->where('purchase_id', $purchase_id)->delete('product_purchase_details');
    $this->db->where('VNo', $purchase_id)->delete('acc_transaction');
    $this->db->where('deposite_id', $purchase_id)->delete('bank_summary');

    // Insert new records
    $this->db->insert('manufacturer_ledger', $ledger);
    $this->db->insert('acc_transaction', $coscr);
    $this->db->insert('acc_transaction', $purchasecoatran);
    $this->db->insert('acc_transaction', $expense);

    if ($this->input->post('paytype') == 2) { // Bank payment
        $this->db->insert('acc_transaction', $bankc);
        $this->db->insert('bank_summary', $banksummary);
        $this->db->insert('manufacturer_ledger', $ledger_debit);
        $this->db->insert('acc_transaction', $manufacurerdebit);
    }

    if ($this->input->post('paytype') == 1) { // Cash payment
        $this->db->insert('acc_transaction', $cashinhand);
        $this->db->insert('manufacturer_ledger', $ledger_debit);
        $this->db->insert('acc_transaction', $manufacurerdebit);
    }

    // --- Handle product purchase details ---
    $rate = $this->input->post('product_rate');
    $p_id = $this->input->post('product_id');
    $quantity = $this->input->post('product_quantity');
    $stock_id = $this->input->post('stock_id');
    $t_price = $this->input->post('total_price');
    $sell_rate = $this->input->post('sale_rate');
    $invoice_id = $this->input->post('invoice_id');
    $discount = $this->input->post('discount');
    $batch = $this->input->post('batch_id');
    $exp_date = $this->input->post('expeire_date');

    for ($i = 0, $n = count($p_id); $i < $n; $i++) {
        $product_quantity = $quantity[$i];
        $product_rate = $rate[$i];
        $sell_price_item = $sell_rate[$i];
        $product_id = $p_id[$i];
        //$stock_id = $stock_id[$i];
        $total_price = $t_price[$i];
        $batch_id = $batch[$i];
        $expre_date = $exp_date[$i];
        $disc = $discount[$i];

        if (!empty($product_quantity)) {
            $data1 = array(
                'purchase_detail_id' => $this->generator(15),
                'purchase_id'        => $purchase_id,
                'product_id'         => $product_id,
                'quantity'           => $product_quantity,
                'rate'               => $product_rate,
                'batch_id'           => $batch_id,
                'stock_id'           => $stock_id,
                'expeire_date'       => $expre_date,
                'total_amount'       => $total_price,
                'invoice_id'         => $invoice_id,
                'sell_price'         => $sell_price_item,
                'discount'           => $disc,
            );

            $this->db->insert('product_purchase_details', $data1);
        }
    }

    $this->db->trans_complete(); // End transaction

    if ($this->db->trans_status() === FALSE) {
        log_message('error', 'Purchase update failed for ID ' . $purchase_id);
        return false;
    }

    return $purchase_id;
}

	// Delete purchase Item
	
	public function purchase_search_list($cat_id,$company_id)
	{
		$this->db->select('a.*,b.sub_category_name,c.category_name');
		$this->db->from('purchases a');
		$this->db->join('purchase_sub_category b','b.sub_category_id = a.sub_category_id');
		$this->db->join('purchase_category c','c.category_id = b.category_id');
		$this->db->where('a.sister_company_id',$company_id);
		$this->db->where('c.category_id',$cat_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//Retrieve purchase_details_data
	public function purchase_details_data($purchase_id)
	{
	$this->db->select('a.*,b.*,c.*,e.purchase_details,d.product_id,d.product_name,d.strength,d.product_model');
		$this->db->from('product_purchase a');
		$this->db->join('manufacturer_information b','b.manufacturer_id = a.manufacturer_id');
		$this->db->join('product_purchase_details c','c.purchase_id = a.purchase_id');
		$this->db->join('product_information d','d.product_id = c.product_id');
		$this->db->join('product_purchase e','e.purchase_id = c.purchase_id');
		$this->db->where('a.purchase_id',$purchase_id);
		$this->db->group_by('d.product_id');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	
	//This function will check the product & manufacturer relationship.
	public function product_manufacturer_check($product_id,$manufacturer_id)
	{
	 $this->db->select('*');
	  $this->db->from('product_information');
	  $this->db->where('product_id',$product_id);
	  $this->db->where('manufacturer_id',$manufacturer_id);	
	  $query = $this->db->get();
		if ($query->num_rows() > 0) {
			return true;	
		}
		return 0;
	}
	//This function is used to Generate Key
	public function generator($lenth)
	{
		$number=array("A","B","C","D","E","F","G","H","I","J","K","L","N","M","O","P","Q","R","S","U","V","T","W","X","Y","Z","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","1","2","3","4","5","6","7","8","9","0");
	
		for($i=0; $i<$lenth; $i++)
		{
			$rand_value=rand(0,61);
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

	public function purchase_delete($purchase_id = null)
	{
			//Delete product_purchase table
		$this->db->where('purchase_id',$purchase_id);
		$this->db->delete('product_purchase'); 
		//Delete product_purchase_details table
		$this->db->where('purchase_id',$purchase_id);
		$this->db->delete('product_purchase_details');
		//Delete manufacturer ledger
		$this->db->where('transaction_id',$purchase_id);
		$this->db->delete('manufacturer_ledger');
		return true;
		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
}
//purchase list date to date
	public function purchase_list_date_to_date($start,$end)
	{
		$this->db->select('a.*,b.manufacturer_name');
		$this->db->from('product_purchase a');
		$this->db->join('manufacturer_information b','b.manufacturer_id = a.manufacturer_id');
		$this->db->order_by('a.purchase_date','desc');
     	$this->db->where('a.purchase_date >=', $start);
        $this->db->where('a.purchase_date <=', $end);
		$query = $this->db->get();
		
		$last_query =  $this->db->last_query();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}
	
	public function purchasedatabyid($purchase_id){
        $this->db->select('a.*,b.manufacturer_name');
		$this->db->from('product_purchase a');
		$this->db->join('manufacturer_information b','b.manufacturer_id = a.manufacturer_id','left');
		$this->db->where('a.purchase_id',$purchase_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//purchase details by id
	public function purchase_detailsbyid($purchase_id){
		$this->db->select('a.*,b.*');
		$this->db->from('product_purchase_details a');//
		$this->db->join('product_information b','b.product_id = a.product_id','left');
		$this->db->where('a.purchase_id',$purchase_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	
	}

	// manufacturer info
	public function manufacturer_info($id){
        $this->db->select('*');
		$this->db->from('manufacturer_information');
		$this->db->where('manufacturer_id',$id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	
}
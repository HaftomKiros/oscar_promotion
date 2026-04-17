<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Customers extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	//Count customer
	public function count_customer()
{
	$CI =& get_instance();
    $user_id = $CI->session->userdata('user_id');
    $this->db->from('customer_information ci');
    // Example: join another table, e.g., 'orders'
    $this->db->join('stock s', 's.id = ci.stock_id', 'left');
	$this->db->where("s.assign_users LIKE '%\"$user_id\"%'",null,false);
	
    // Optional: add conditions if needed
    // $this->db->where('o.status', 'completed');

    return $this->db->count_all_results(); // counts rows after join and conditions
}

	//customer List
	public function customer_list_count()
	{
		$this->db->select('*');
		$this->db->from('customer_information');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->num_rows();	
		}
		return false;
	}
	//customer List
	public function customer_list($per_page=null,$page=null)
	{
		$this->db->select('*');
		$this->db->from('customer_information');
		$this->db->limit($per_page,$page);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}

	 public function getCustomerList($postData = null) {
    $CI =& get_instance();
    $user_id = $CI->session->userdata('user_id');

    $response = array();

    // DataTables variables
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length']; 
    $columnIndex = $postData['order'][0]['column'];
    $columnName = $postData['columns'][$columnIndex]['data'];
    $columnSortOrder = $postData['order'][0]['dir'];
    $searchValue = $postData['search']['value'];

    // -----------------------
    // Total records without filtering (only user-assigned stocks)
    // -----------------------
    $this->db->select('COUNT(c.customer_id) as allcount');
    $this->db->from('customer_information c');
    $this->db->join('stock s', 's.id = c.stock_id', 'left');
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);
    $records = $this->db->get()->result();
    $totalRecords = isset($records[0]->allcount) ? (int)$records[0]->allcount : 0;

    // -----------------------
    // Total records with filtering
    // -----------------------
    $this->db->select('COUNT(c.customer_id) as allcount');
    $this->db->from('customer_information c');
    $this->db->join('stock s', 's.id = c.stock_id', 'left');
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);

    if (!empty($searchValue)) {
        $this->db->group_start();
        $this->db->like('c.customer_name', $searchValue);
        $this->db->or_like('c.customer_mobile', $searchValue);
        $this->db->or_like('c.customer_email', $searchValue);
		 $this->db->or_like('s.stock_name', $searchValue);
        $this->db->group_end();
    }

    $records = $this->db->get()->result();
    $totalRecordwithFilter = isset($records[0]->allcount) ? (int)$records[0]->allcount : 0;

    // -----------------------
    // Fetch records with limit, order, and search
    // -----------------------
    $this->db->select("c.*,s.stock_name");
    $this->db->from('customer_information c');
    $this->db->join('stock s', 's.id = c.stock_id', 'left');
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);

    if (!empty($searchValue)) {
       $this->db->group_start();
$this->db->like('c.customer_name', $searchValue);
$this->db->or_like('c.customer_mobile', $searchValue);
$this->db->or_like('c.customer_email', $searchValue);
$this->db->or_like('s.stock_name', $searchValue);
$this->db->group_end();

    }

    $this->db->order_by($columnName, $columnSortOrder);
    $this->db->limit($rowperpage, $start);
    $records = $this->db->get()->result();

    $data = array();
    $sl = $start + 1;

    foreach ($records as $record) {
        $button = '';
        $base_url = base_url();
        $jsaction = "return confirm('Are You Sure ?')";

        // Calculate balance
        $total_debit = (float)$this->db->select('SUM(amount) AS totaldebit')
            ->from('customer_ledger')
            ->where('customer_id', $record->customer_id)
            ->where('d_c', 'd')
            ->get()
            ->row()
            ->totaldebit;

        $total_credit = (float)$this->db->select('SUM(amount) AS totalcredit')
            ->from('customer_ledger')
            ->where('customer_id', $record->customer_id)
            ->where('d_c', 'c')
            ->get()
            ->row()
            ->totalcredit;

        $balance = $total_debit - $total_credit;

        // Action buttons
        $button .= '<div class="btn-group">
            <button type="button" class="btn btn-success" data-toggle="dropdown">Action <span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu">';

        if ($this->permission1->method('manage_supplier', 'update')->access()) {
            $button .= '<li class="action">
                <a href="'.$base_url.'Ccustomer/customer_update_form/'.$record->customer_id.'" class="btn btn-info" title="'.display('update').'">Edit</a>
            </li>';
        }

        if ($this->permission1->method('manage_supplier', 'delete')->access()) {
            $button .= '<li class="action">
                <a href="'.$base_url.'Ccustomer/customer_delete/'.$record->customer_id.'" class="btn btn-danger" onclick="'.$jsaction.'">DELETE</a>
            </li>';
        }

        $button .= '</ul></div>';

        $data[] = array(
    'sl'            => $sl,
    'customer_name' => $record->customer_name,
    'address'       => $record->customer_address,
    'mobile'        => $record->customer_mobile,
    'balance'       => number_format($balance, 2),
    'stock_name'    => $record->stock_name, // added stock name
    'button'        => $button
);


        $sl++;
    }

    // -----------------------
    // Response
    // -----------------------
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,             // total without filtering
        "iTotalDisplayRecords" => $totalRecordwithFilter, // total with filter
        "aaData" => $data
    );

    return $response;
}



     public function getCreditCustomerList($postData = null)
      {
    $response = [];
	$CI =& get_instance();
    $user_id = $CI->session->userdata('user_id');

    // Read DataTables request parameters
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length'];
    $columnIndex = $postData['order'][0]['column'];
    $columnName = $postData['columns'][$columnIndex]['data'];
    $columnSortOrder = $postData['order'][0]['dir'];
    $searchValue = $postData['search']['value'];

    // Ledger + Invoice Balance Calculation (for filtering)
    $subSelectLedger = "(SELECT IFNULL(SUM(amount), 0) FROM customer_ledger WHERE customer_id = a.customer_id AND d_c = 'd') - 
                        (SELECT IFNULL(SUM(amount), 0) FROM customer_ledger WHERE customer_id = a.customer_id AND d_c = 'c')";

    $subSelectInvoice = "(SELECT IFNULL(SUM(total_price), 0) FROM invoice_details WHERE invoice_id IN 
                          (SELECT invoice_id FROM invoice WHERE customer_id = a.customer_id)) - 
                         (SELECT IFNULL(SUM(paid_amount), 0) FROM invoice_details WHERE invoice_id IN 
                          (SELECT invoice_id FROM invoice WHERE customer_id = a.customer_id))";

    $totalBalance = "($subSelectLedger + $subSelectInvoice)";

    // Count total records with out filter
	if ($searchValue != '') {
    $this->db->group_start();
    $this->db->like('a.customer_name', $searchValue);
    $this->db->or_like('a.customer_mobile', $searchValue);
    $this->db->or_like('a.customer_email', $searchValue);
    $this->db->or_like('s.stock_name', $searchValue); // <-- added stock_name
    $this->db->group_end();
}

   $this->db->select('COUNT(a.customer_id) as allcount');
$this->db->from('customer_information a');
$this->db->join('stock s', 's.id = a.stock_id', 'left');
$this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);

$query = $this->db->get()->row();
$totalRecords = isset($query->allcount) ? (int)$query->allcount : 0;


    // Count filtered records
	if ($searchValue != '') {
    $this->db->group_start();
    $this->db->like('a.customer_name', $searchValue);
    $this->db->or_like('a.customer_mobile', $searchValue);
    $this->db->or_like('a.customer_email', $searchValue);
    $this->db->or_like('s.stock_name', $searchValue); // <-- added stock_name
    $this->db->group_end();
}

    $this->db->select("a.customer_id, $subSelectLedger AS ledger_balance, $subSelectInvoice AS invoice_balance, $totalBalance AS total_balance");
$this->db->from('customer_information a');
$this->db->join('stock s', 's.id = a.stock_id', 'left');
$this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);
$this->db->having('total_balance > 0');
$this->db->group_by('a.customer_id');
$query2 = $this->db->get();
$totalRecordwithFilter = $query2->num_rows();


    // Fetch paginated records
	if ($searchValue != '') {
    $this->db->group_start();
    $this->db->like('a.customer_name', $searchValue);
    $this->db->or_like('a.customer_mobile', $searchValue);
    $this->db->or_like('a.customer_email', $searchValue);
    $this->db->or_like('s.stock_name', $searchValue); // <-- added stock_name
    $this->db->group_end();
}
    $this->db->select("
    a.*,
	s.stock_name,
    $subSelectLedger AS ledger_balance,
    $subSelectInvoice AS invoice_balance,
    ($subSelectLedger + $subSelectInvoice) AS total_balance
");
$this->db->from('customer_information a');
$this->db->join('stock s', 's.id = a.stock_id', 'left');
$this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);

$this->db->having('total_balance > 0');
$this->db->group_by('a.customer_id');
$this->db->order_by($columnName, $columnSortOrder);
$this->db->limit($rowperpage, $start);

$records = $this->db->get()->result();

    $data = [];
    $sl = $start + 1;
    $base_url = base_url();
    $jsaction = "return confirm('Are You Sure ?')";

    foreach ($records as $record) {
        $ledger_balance = (float) $record->ledger_balance;
        $invoice_balance = (float) $record->invoice_balance;
        $prev = $ledger_balance - $invoice_balance;

        $button = '<div class="btn-group">
            <button type="button" class="btn btn-success" data-toggle="dropdown">Action <span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu">';

        if ($this->permission1->method('manage_supplier', 'update')->access()) {
            $button .= '<li class="action"><a href="' . $base_url . 'Ccustomer/customer_update_form/' . $record->customer_id . '" class="btn btn-info">' . display('update') . '</a></li>';
        }

        if ($this->permission1->method('manage_supplier', 'delete')->access()) {
            $button .= '<li class="action"><a href="' . $base_url . 'Ccustomer/customer_delete/' . $record->customer_id . '" class="btn btn-danger" onclick="' . $jsaction . '"><i class="fa fa-trash"></i> DELETE</a></li>';
        }

        $button .= '</ul></div>';

        $data[] = [
            'sl' => $sl++,
            'customer_name' => $record->customer_name,
            'address' => $record->customer_address,
			'stock_name' => $record->stock_name,
            'mobile' => $record->customer_mobile,
            'balance' => number_format($ledger_balance, 2),          // ledger_balance as balance
            'invoice_balance' => number_format($invoice_balance, 2),
            'prev' => number_format($prev, 2),                       // prev = ledger_balance - invoice_balance
            'button' => $button
        ];
    }

    // Response for DataTables
    $response = [
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    ];

    return $response;
}


         public function getPaidCustomerList($postData = null) {
    $CI =& get_instance();
    $user_id = $CI->session->userdata('user_id');

    $response = array();

    // DataTables variables
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length'];
    $columnIndex = $postData['order'][0]['column'];
    $columnName = $postData['columns'][$columnIndex]['data'];
    $columnSortOrder = $postData['order'][0]['dir'];
    $searchValue = $postData['search']['value'];

    // Subquery for customer balance
    $subBalance = "(
        (SELECT IFNULL(SUM(amount),0) FROM customer_ledger WHERE customer_id = a.customer_id AND d_c='d') -
        (SELECT IFNULL(SUM(amount),0) FROM customer_ledger WHERE customer_id = a.customer_id AND d_c='c')
    )";

    // -----------------------------
    // Total records without filtering
    // -----------------------------
    $this->db->select("COUNT(a.customer_id) AS allcount");
    $this->db->from('customer_information a');
    $this->db->join('stock s', 's.id = a.stock_id', 'left');
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false); // filter by user
    $this->db->having("$subBalance <= 0", null, false);
    $this->db->group_by('a.customer_id');
    $totalRecords = $this->db->get()->num_rows();

    // -----------------------------
    // Total records with filtering
    // -----------------------------
    $this->db->select("COUNT(a.customer_id) AS allcount");
    $this->db->from('customer_information a');
    $this->db->join('stock s', 's.id = a.stock_id', 'left');
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);

    if (!empty($searchValue)) {
        $this->db->group_start();
        $this->db->like('a.customer_name', $searchValue);
        $this->db->or_like('a.customer_mobile', $searchValue);
        $this->db->or_like('a.customer_email', $searchValue);
        $this->db->or_like('s.stock_name', $searchValue);
        $this->db->group_end();
    }

    $this->db->having("$subBalance <= 0", null, false);
    $this->db->group_by('a.customer_id');
    $totalRecordwithFilter = $this->db->get()->num_rows();

    // -----------------------------
    // Fetch paginated records
    // -----------------------------
    $this->db->select("a.*, $subBalance AS customer_balance, s.stock_name");
    $this->db->from('customer_information a');
    $this->db->join('stock s', 's.id = a.stock_id', 'left');
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);

    if (!empty($searchValue)) {
        $this->db->group_start();
        $this->db->like('a.customer_name', $searchValue);
        $this->db->or_like('a.customer_mobile', $searchValue);
        $this->db->or_like('a.customer_email', $searchValue);
        $this->db->or_like('s.stock_name', $searchValue);
        $this->db->group_end();
    }

    $this->db->having('customer_balance <= 0', null, false);
    $this->db->group_by('a.customer_id');
    $this->db->order_by($columnName, $columnSortOrder);
    $this->db->limit($rowperpage, $start);
    $records = $this->db->get()->result();

    $data = array();
    $sl = $start + 1;
    $base_url = base_url();
    $jsaction = "return confirm('Are You Sure ?')";

    foreach ($records as $record) {
        $balance = (float)$record->customer_balance;
        $button = '<div class="btn-group">
            <button type="button" class="btn btn-success" data-toggle="dropdown">Action <span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu">';

        if ($this->permission1->method('manage_supplier','update')->access()) {
            $button .= '<li class="action">
                <a href="'.$base_url.'Ccustomer/customer_update_form/'.$record->customer_id.'" class="btn btn-info" title="'.display('update').'">Edit</a>
            </li>';
        }
        if ($this->permission1->method('manage_supplier','delete')->access()) {
            $button .= '<li class="action">
                <a href="'.$base_url.'Ccustomer/customer_delete/'.$record->customer_id.'" class="btn btn-danger" onclick="'.$jsaction.'">DELETE</a>
            </li>';
        }

        $button .= '</ul></div>';

        $data[] = array(
            'sl'            => $sl++,
            'customer_name' => $record->customer_name,
            'address'       => $record->customer_address,
            'mobile'        => $record->customer_mobile,
            'stock_name'    => $record->stock_name,
            'balance'       => number_format($balance, 2),
            'button'        => $button
        );
    }

    // -----------------------------
    // Response
    // -----------------------------
    $response = array(
        "draw"                 => intval($draw),
        "iTotalRecords"        => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData"               => $data
    );

    return $response;
}

       public function count_credit_customer() {
		// this is from customer_leger
     $this->db->select("a.*,((select sum(amount) from customer_ledger where customer_id= `a`.`customer_id` and d_c='d') -(select sum(amount) from customer_ledger where customer_id= `a`.`customer_id` and d_c='c')) as 'customer_balance'");
        $this->db->from('customer_information a');
         $this->db->group_by('a.customer_id');
        $this->db->having('customer_balance > 0'); 
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }


       public function count_paid_customer() {
     $this->db->select("a.*,((select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='d') -(select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='c')) as 'customer_balance'");
        $this->db->from('customer_information a');
         $this->db->group_by('a.customer_id');
        $this->db->having('customer_balance <= 0'); 
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }
	//all customer List
	public function all_customer_list()
	{
		$this->db->select('*');
		$this->db->from('customer_information');
		//$this->db->group_by('customer_information.customer_id');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	
	//Credit customer List
	public function credit_customer_list($per_page,$page)
	{
		  $this->db->select("a.*,((select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='d') -(select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='c')) as 'customer_balance'");
        $this->db->from('customer_information a');
        $this->db->group_by('a.customer_id');
        $this->db->having('customer_balance < 0'); 
		$this->db->limit($per_page,$page);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->result_array();	
		}
		return false;
	}
	//All Credit customer List
	public function all_credit_customer_list()
	{
		$this->db->select("a.*,((select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='d') -(select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='c')) as 'customer_balance'");
        $this->db->from('customer_information a');
        $this->db->group_by('a.customer_id');
        $this->db->having('customer_balance < 0'); 
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//Credit customer List
	public function credit_customer_list_count()
	{
		$this->db->select("a.*,((select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='d') -(select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='c')) as 'customer_balance'");
        $this->db->from('customer_information a');
        $this->db->group_by('a.customer_id');
        $this->db->having('customer_balance < 0'); 
		$query = $this->db->get();
	
		if ($query->num_rows() > 0) {
			return $query->num_rows();	
		}
		return false;
	}

	//Paid Customer list
	public function paid_customer_list($per_page=null,$page=null)
	{
		$this->db->select("a.*,((select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='d') -(select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='c')) as 'customer_balance'");
        $this->db->from('customer_information a');
        $this->db->group_by('a.customer_id');
        $this->db->having('customer_balance >= 0'); 
		$this->db->limit($per_page,$page);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//All Paid Customer list
	public function all_paid_customer_list()
	{
			$this->db->select("a.*,((select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='d') -(select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='c')) as 'customer_balance'");
        $this->db->from('customer_information a');
        $this->db->group_by('a.customer_id');
        $this->db->having('customer_balance >= 0'); 
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}	
	//Paid Customer list
	public function paid_customer_list_count(){
		$this->db->select("a.*,((select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='d') -(select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='c')) as 'customer_balance'");
        $this->db->from('customer_information a');
        $this->db->group_by('a.customer_id');
        $this->db->having('customer_balance >= 0'); 
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->num_rows();	
		}
		return false;
	}
	
	//Customer Search List
	public function customer_search_item($customer_id)
	{
		$this->db->select("a.*,((select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='d') -(select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='c')) as 'customer_balance'");
        $this->db->from('customer_information a');
		$this->db->where('a.customer_id',$customer_id);
		$this->db->group_by('a.customer_id');
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}	
	//Credit Customer Search List
	public function credit_customer_search_item($customer_id)
	{

		$this->db->select("a.*,((select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='d') -(select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='c')) as 'customer_balance'");
        $this->db->from('customer_information a');
		$this->db->group_by('a.customer_id');
		$this->db->where('a.customer_id',$customer_id);
		$this->db->having('customer_balance < 0'); 
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}else{
			$this->session->set_userdata(array('error_message'=>display('this_is_not_credit_customer')));
			redirect('Ccustomer/credit_customer');
		}
	}	
	//Paid Customer Search List
	public function paid_customer_search_item($customer_id)
	{

		$this->db->select("a.*,((select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='d') -(select ifnull(sum(amount),0) from customer_ledger where customer_id= `a`.`customer_id` and d_c='c')) as 'customer_balance'");
        $this->db->from('customer_information a');
		$this->db->having('customer_balance >= 0');
		$this->db->where('a.customer_id',$customer_id);
		$this->db->group_by('a.customer_id');
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//Count customer
	public function customer_entry($data)
	{

		$this->db->select('*');
		$this->db->from('customer_information');
		$this->db->where('customer_name',$data['customer_name']);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return FALSE;
		}else{
			$this->db->insert('customer_information',$data);
		
			$this->db->select('*');
			$this->db->from('customer_information');
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$json_customer[] = array('label'=>$row->customer_name,'value'=>$row->customer_id);
			}
			$cache_file ='./my-assets/js/admin_js/json/customer.json';
			$customerList = json_encode($json_customer);
			file_put_contents($cache_file,$customerList);
			return TRUE;
		}
	}

	//Customer Previous balance adjustment
	  public function previous_balance_add($balance,$customer_id)
	  {
	  $this->load->library('auth');
	  $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id',$customer_id)->get()->row();
    $headn = $cusifo->customer_name.'-'.$customer_id;
    $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName',$headn)->get()->row();
    $customer_headcode = $coainfo->HeadCode;
	  $transaction_id=$this->auth->generator(10);
	$data=array(
			'transaction_id' => $transaction_id,
			'customer_id' 	 => $customer_id,
			'invoice_no'     => "NA",
			'receipt_no' 	 => NULL,
			'amount' 		 => $balance,
			'description' 	 => "Previous adjustment with software",
			'payment_type' 	 => "NA",
			'cheque_no' 	 => "NA",
			'date' 		     => date("Y-m-d"),
			'status' 		 => 1,
			'd_c' 		     => "d"
			);
				// Customer debit for previous balance
      $cosdr = array(
		      'VNo'            =>  $transaction_id,
		      'Vtype'          =>  'PR Balance',
		      'VDate'          =>  date("Y-m-d"),
		      'COAID'          =>  $customer_headcode,
		      'Narration'      =>  'Customer debit For Transaction No'.$transaction_id,
		      'Debit'          =>  $balance,
		      'Credit'         =>  0,
		      'IsPosted'       => 1,
		      'CreateBy'       => $this->session->userdata('user_id'),
		      'CreateDate'     => date('Y-m-d H:i:s'),
		      'IsAppove'       => 1
    );
       $inventory = array(
		      'VNo'            =>  $transaction_id,
		      'Vtype'          =>  'PR Balance',
		      'VDate'          =>  date("Y-m-d"),
		      'COAID'          =>  10107,
		      'Narration'      =>  'Inventory credit For Old sale For'.$customer_id,
		      'Debit'          =>  0,
		      'Credit'         =>  $balance,//purchase price asbe
		      'IsPosted'       => 1,
		      'CreateBy'       => $this->session->userdata('user_id'),
		      'CreateDate'     => date('Y-m-d H:i:s'),
		      'IsAppove'       => 1
    ); 	


		$this->db->insert('customer_ledger',$data);
		 if(!empty($balance)){
           $this->db->insert('acc_transaction', $cosdr); 
           $this->db->insert('acc_transaction', $inventory); 
        }
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
	//Retrieve customer Edit Data
	public function retrieve_customer_editdata($customer_id)
	{
		$this->db->select('*');
		$this->db->from('customer_information');
		$this->db->where('customer_id',$customer_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//Retrieve customer Personal Data 
	public function customer_personal_data($customer_id)
	{
		$this->db->select('*');
		$this->db->from('customer_information');
		$this->db->where('customer_id',$customer_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//Retrieve customer Invoice Data 
	public function customer_invoice_data($customer_id)
	{
		$this->db->select('*');
		$this->db->from('customer_ledger');
		$this->db->where(array('customer_id'=>$customer_id,'receipt_no'=>NULL,'status'=>1));
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}	
	//Retrieve customer Receipt Data 
	public function customer_receipt_data($customer_id)
	{
		$this->db->select('*');
		$this->db->from('customer_ledger');
		$this->db->where(array('customer_id'=>$customer_id,'invoice_no'=>NULL,'status'=>1));
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
//Retrieve customer All data 
	public function customerledger_tradational($customer_id)
	{
		$this->db->select('
			customer_ledger.*,
			invoice.invoice as invoce_n
			');
		$this->db->from('customer_ledger');
		$this->db->join('invoice','customer_ledger.invoice_no = invoice.invoice_id','LEFT');
		$this->db->where(array('customer_ledger.customer_id'=>$customer_id,'customer_ledger.status'=>1));
		$this->db->order_by('customer_ledger.id','asc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//Retrieve customer total information
	public function customer_transection_summary($customer_id)
	{
		$result=array();
		$this->db->select_sum('amount','total_credit');
		$this->db->from('customer_ledger');
		$this->db->where(array('customer_id'=>$customer_id,'status'=>1,'d_c'=>'c'));
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$result[]=$query->result_array();	
		}
		
		$this->db->select_sum('amount','total_debit');
		$this->db->from('customer_ledger');
		$this->db->where(array('customer_id'=>$customer_id,'status'=>1,'d_c'=>'d'));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			$result[]=$query->result_array();	
		}
		return $result;
	
	}
	
	//Update Categories
	public function update_customer($data,$customer_id)
	{
		$this->db->where('customer_id',$customer_id);
		$this->db->update('customer_information',$data);
		

		$this->db->select('*');
		$this->db->from('customer_information');
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$json_customer[] = array('label'=>$row->customer_name,'value'=>$row->customer_id);
		}
		$cache_file = './my-assets/js/admin_js/json/customer.json';
		$customerList = json_encode($json_customer);
		file_put_contents($cache_file,$customerList);		
		return true;
	}
	
	// custromer transection delete


	// custromer invoicedetails delete
	public function delete_invoicedetails($customer_id){
		$this->db->where('customer_id',$customer_id);
		$this->db->delete('invoice_details'); 

	}

	// custromer invoice delete
	public function delete_invoic($customer_id){
		$this->db->where('customer_id',$customer_id);
		$this->db->delete('invoice'); 

	}
	// delete customer ledger 
		public function delete_customer_ledger($customer_id,$customer_head){
		$this->db->where('customer_id',$customer_id);
		$this->db->delete('customer_ledger'); 
		$this->db->where('HeadName', $customer_head);
        $this->db->delete('acc_coa');

	}
	// Delete customer Item
	public function delete_customer($customer_id)
	{
		$this->db->where('customer_id',$customer_id);
		$this->db->delete('customer_information'); 

		$this->db->select('*');
		$this->db->from('customer_information');
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$json_customer[] = array('label'=>$row->customer_name,'value'=>$row->customer_id);
		}
		$cache_file = './my-assets/js/admin_js/json/customer.json';
		$customerList = json_encode($json_customer);
		file_put_contents($cache_file,$customerList);		
		return true;
	}
	public function customer_search_list($cat_id,$company_id)
	{
		$this->db->select('a.*,b.sub_category_name,c.category_name');
		$this->db->from('customers a');
		$this->db->join('customer_sub_category b','b.sub_category_id = a.sub_category_id');
		$this->db->join('customer_category c','c.category_id = b.category_id');
		$this->db->where('a.sister_company_id',$company_id);
		$this->db->where('c.category_id',$cat_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	    public function headcode(){
        $query=$this->db->query("SELECT MAX(HeadCode) as HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020300%'");
        return $query->row();

    }


    //autocomplete part
    public function customer_search($customer_id) {
    $CI =& get_instance();
    $user_id = $CI->session->userdata('user_id');

    $query = $this->db->select('c.*')
        ->from('customer_information c')
        ->join('stock s', 's.id = c.stock_id', 'left')
        ->group_start()
            ->like('c.customer_name', $customer_id)
            ->or_like('c.customer_mobile', $customer_id)
        ->group_end()
        ->where("s.assign_users LIKE '%\"$user_id\"%'", null, false)
        ->limit(20)
        ->get();

    if ($query->num_rows() > 0) {
        return $query->result_array();  
    }

    return false;
}

}
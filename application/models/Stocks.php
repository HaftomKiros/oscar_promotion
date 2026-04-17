<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Stocks extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	public function select_all_users()
	{
		$query = $this->db->select('*')
					->from('users')
					->where('status','1')
					->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	 public function insert_stock($data)
    {
        $this->db->insert('stock', $data);
        return $this->db->insert_id();
    }

	//get stock list
	public function getStockList($postData=null){
    $this->load->library('occational');
    $response = array();
    $fromdate = $this->input->post('fromdate');
    $todate   = $this->input->post('todate');
    if(!empty($fromdate)){
        $datbetween = "(created_at BETWEEN '$fromdate' AND '$todate')";
    } else {
        $datbetween = "";
    }

    ## Read value
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length']; // Rows display per page
    $columnIndex = $postData['order'][0]['column']; // Column index
    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
    $searchValue = $postData['search']['value']; // Search value

    ## Search 
    $searchQuery = "";
    if($searchValue != ''){
        $searchQuery = $searchValue; // we'll use like() below
    }

    ## Total number of records without filtering
    $this->db->from('stock');
    if(!empty($fromdate) && !empty($todate)){
        $this->db->where('created_at >=', $fromdate);
        $this->db->where('created_at <=', $todate);
    }
    if(!empty($searchQuery)){
        $this->db->group_start();        // open bracket for grouping
    $this->db->like('stock_name', $searchQuery);
    $this->db->or_like('address', $searchQuery);
    $this->db->group_end();          // close bracket
    }
    $totalRecords = $this->db->count_all_results();

    ## Total number of records with filtering
    $this->db->from('stock');
    if(!empty($fromdate) && !empty($todate)){
        $this->db->where('created_at >=', $fromdate);
        $this->db->where('created_at <=', $todate);
    }
    if(!empty($searchQuery)){
         $this->db->group_start();        // open bracket for grouping
    $this->db->like('stock_name', $searchQuery);
    $this->db->or_like('address', $searchQuery);
    $this->db->group_end();          // close bracket
    }
    $totalRecordwithFilter = $this->db->count_all_results();

    ## Fetch records
    $this->db->select('*');
    $this->db->from('stock');
    if(!empty($fromdate) && !empty($todate)){
        $this->db->where('created_at >=', $fromdate);
        $this->db->where('created_at <=', $todate);
    }
    if(!empty($searchQuery)){
         $this->db->group_start();        // open bracket for grouping
    $this->db->like('stock_name', $searchQuery);
    $this->db->or_like('address', $searchQuery);
    $this->db->group_end();          // close bracket
    }
    $this->db->order_by($columnName, $columnSortOrder);
    $this->db->limit($rowperpage, $start);
    $records = $this->db->get()->result();

    $data = array();
    $sl = 1;

    foreach($records as $record){
        $button = '';
        $base_url = base_url();
        $jsaction = "return confirm('Are You Sure ?')";

        if($this->permission1->method('manage_stock','update')->access()){
            $button .=' <a href="'.$base_url.'Creport/stock_update_form/'.$record->id.'" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="'. display('update').'"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
        }

        if($this->permission1->method('manage_stock','delete')->access()){
            $button .= '<a href="'.$base_url.'Creport/stock_delete/'.$record->id.'" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="left" title="'. display('delete').'"  onclick="'.$jsaction.'"><i class="fa fa-trash"></i></a>';
        }

        // ✅ Count total assigned users
        $assignedUsers = json_decode($record->assign_users, true);
        $totalAssign = is_array($assignedUsers) ? count($assignedUsers) : 0;

        $data[] = array(
            'sl'            => $sl,
            'stock_name'    => $record->stock_name,
            'address'       => $record->address,
            'assign_users'  => $totalAssign,
            'created_at'    => $record->created_at,
            'button'        => $button,
        );

        $sl++;
    }

    ## Response
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    );

    return $response;
}

// fetch update data from stock for edit
 public function retrieve_stock_editdata($id)
{
    $this->db->select('*');
    $this->db->from('stock');
    $this->db->where('id', $id);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        $stock = $query->row_array();

        // Convert assign_users JSON string to array
        if (!empty($stock['assign_users'])) {
            $decodedUsers = json_decode($stock['assign_users'], true);

            // Ensure we have an array of integers for matching
            $stock['assign_users'] = is_array($decodedUsers) ? array_map('intval', $decodedUsers) : [];
        } else {
            $stock['assign_users'] = [];
        }

        return [$stock]; // array of arrays to match controller
    } else {
        return [];
    }
}

     public function update_stock($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('stock', $data);
    }
    public function get_stocks_assigned_to_user($user_id) {
        $this->db->select('*');
        $this->db->from('stock');
        $query = $this->db->get();
    
        $result = $query->result_array();
        $filtered = [];
    
        foreach ($result as $row) {
            $assigned_users = json_decode($row['assign_users'], true);
    
            if (json_last_error() === JSON_ERROR_NONE && is_array($assigned_users)) {
                // convert all to strings
                $assigned_users = array_map('strval', $assigned_users);
    
                if (in_array((string)$user_id, $assigned_users)) {
                    $filtered[] = $row;
                }
            }
        }
    
        return $filtered;
    }
    public function get_all_stocks()
{
    $this->db->select('*');
    $this->db->from('stock');
    $query = $this->db->get();
    return $query->result_array();
}
public function get_products_by_stock($stock_id)
{
    $this->db->select('
        a.product_id,
        b.product_name
    ');
    $this->db->from('product_purchase_details a');
    $this->db->join('product_information b', 'b.product_id = a.product_id', 'left');
    $this->db->where('a.stock_id', $stock_id);
    $this->db->group_by('a.product_id'); // ensures distinct products
    $this->db->order_by('b.product_name', 'asc');

    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        return $query->result_array();
    }

    return [];
}
public function get_batches_by_product_stock($product_id, $stock_id) {
    $this->db->select('batch_id');  // Add batch_name if needed
    $this->db->from('product_purchase_details');
    $this->db->where('product_id', $product_id);
    $this->db->where('stock_id', $stock_id);
    $this->db->group_by('batch_id'); 
    $this->db->order_by('batch_id', 'asc');
    return $this->db->get()->result_array();
}

public function get_invoice_by_product_stock($product_id, $stock_id, $batch_id) {
    $this->db->select('invoice_id'); // You can add more fields if needed, e.g., quantity
    $this->db->from('product_purchase_details');
    $this->db->where('product_id', $product_id);
    $this->db->where('stock_id', $stock_id);
    $this->db->where('batch_id', $batch_id);
    $this->db->order_by('invoice_id', 'asc'); // order by invoice_id
    return $this->db->get()->result_array();
}
public function get_available_quantity($product_id, $stock_id, $batch_id, $invoice_id) {
    // 1. Get purchased quantity from product_purchase_details
    $this->db->select('quantity');
    $this->db->from('product_purchase_details');
    $this->db->where('product_id', $product_id);
    $this->db->where('stock_id', $stock_id);
    $this->db->where('batch_id', $batch_id);
    $this->db->where('invoice_id', $invoice_id);
    $row = $this->db->get()->row();
    $purchased_qty = $row ? $row->quantity : 0;

    // 2. Get sold quantity from invoice_details
    $this->db->select('SUM(quantity) as sold_qty');
    $this->db->from('invoice_details');
    $this->db->where('product_id', $product_id);
    $this->db->where('batch_id', $batch_id);
    $this->db->where('pinvoice_id', $invoice_id);
    $sold_row = $this->db->get()->row();
    $sold_qty = $sold_row ? $sold_row->sold_qty : 0;

    // 3. Calculate available quantity
    $available_qty = $purchased_qty - $sold_qty;
    return max($available_qty, 0); // prevent negative values
}

public function insert_stock_transfer($data) {
        return $this->db->insert('stock_transfers', $data);
    }
    public function update_available_quantity_after_transfer($product_id, $batch_id, $invoice_id, $transfer_qty) {
    // 1️⃣ Get current detail record
    $this->db->where('product_id', $product_id);
    $this->db->where('batch_id', $batch_id);
    $this->db->where('invoice_id', $invoice_id);
    $row = $this->db->get('product_purchase_details')->row();

    if ($row) {
        // 2️⃣ Calculate new quantity and total_amount
        $new_qty = $row->quantity - $transfer_qty;
        if ($new_qty < 0) $new_qty = 0; // prevent negative quantity
        $new_total_amount = $new_qty * $row->rate;

        // 3️⃣ Update product_purchase_details
        $this->db->where('product_id', $product_id);
        $this->db->where('batch_id', $batch_id);
        $this->db->where('invoice_id', $invoice_id);
        $this->db->update('product_purchase_details', [
            'quantity' => $new_qty,
            'total_amount' => $new_total_amount
        ]);

        // 4️⃣ Update grand_total_amount in product_purchase
        // Sum all total_amount for this purchase_id
        $this->db->select_sum('total_amount');
        $this->db->where('purchase_id', $row->purchase_id);
        $sum_row = $this->db->get('product_purchase_details')->row();
        $grand_total = $sum_row ? $sum_row->total_amount : 0;

        // Update main purchase
        $this->db->where('purchase_id', $row->purchase_id);
        $this->db->update('product_purchase', [
            'grand_total_amount' => $grand_total
        ]);
    }
}

// Insert transferred stock into destination stock with new invoice_id
public function insert_transferred_stock_to_destination($product_id, $to_stock_id, $batch_id, $transfer_qty, $original_invoice_id) {
    // 1️⃣ Get original purchase_detail record
    $this->db->where('product_id', $product_id);
    $this->db->where('batch_id', $batch_id);
    $this->db->where('invoice_id', $original_invoice_id);
    $original_detail = $this->db->get('product_purchase_details')->row();

    if (!$original_detail) return false;

    // 2️⃣ Get the main purchase record using purchase_id from detail
    $this->db->where('purchase_id', $original_detail->purchase_id);
    $original_purchase = $this->db->get('product_purchase')->row();

    if (!$original_purchase) return false;

    // 3️⃣ Generate new chalan_no
    $this->db->select_max('chalan_no');
    $max_row = $this->db->get('product_purchase')->row();
    $max_chalan = $max_row ? $max_row->chalan_no : null;

    if ($max_chalan) {
        $num = (int) str_replace('PRODUCT_PURCHASE', '', $max_chalan);
        $new_chalan_no = $num + 1;
    } else {
        $new_chalan_no = 'PRODUCT_PURCHASE1';
    }

    // 4️⃣ Generate new invoice_id
    $this->db->select_max('invoice_id', 'max_invoice');
    $max_invoice_row = $this->db->get('product_purchase_details')->row();
    $max_invoice_id = $max_invoice_row ? $max_invoice_row->max_invoice : null;

    $new_invoice_num = $max_invoice_id ? ((int) str_replace('INV','', $max_invoice_id) + 1) : 1;
    $new_invoice_id = ($new_invoice_num < 10 ? 'INV0' : 'INV') . $new_invoice_num;

    // 5️⃣ Generate new purchase_id
    $this->db->select_max('purchase_id', 'max_purchase');
    $max_purchase_row = $this->db->get('product_purchase')->row();
    $new_purchase_id = $max_purchase_row ? $max_purchase_row->max_purchase + 1 : 1;

    // 6️⃣ Insert new record into product_purchase (main table)
    $purchase_data = [
        'chalan_no'        => $new_chalan_no,
        'manufacturer_id'  => $original_purchase->manufacturer_id,
        'stock_id'         => $to_stock_id,
        'grand_total_amount'=> $original_detail->rate * $transfer_qty,
        'total_discount'   => $original_purchase->total_discount,
        'purchase_date'    => date('Y-m-d'),
        'purchase_details' => 1, // optional
        'status'           => 1,
        'purchase_id'      => $new_purchase_id,
        'bank_id'          => $original_purchase->bank_id,
        'payment_type'     => $original_purchase->payment_type
    ];
    $this->db->insert('product_purchase', $purchase_data);

    // 7️⃣ Insert new record into product_purchase_details
    $detail_data = [
        'purchase_detail_id' => uniqid(),
        'purchase_id'        => $new_purchase_id,
        'product_id'         => $original_detail->product_id,
        'quantity'           => $transfer_qty,
        'rate'               => $original_detail->rate,
        'total_amount'       => $original_detail->rate * $transfer_qty,
        'sell_price'         => $original_detail->sell_price,
        'discount'           => $original_detail->discount,
        'batch_id'           => $original_detail->batch_id,
        'expeire_date'       => $original_detail->expeire_date,
        'status'             => 1,
        'invoice_id'         => $new_invoice_id,
        'stock_id'           => $to_stock_id
    ];
    $this->db->insert('product_purchase_details', $detail_data);

    return true;
}

public function get_all_stock_transfers() {
    $CI =& get_instance();
    $user_id = $CI->session->userdata('user_id');
    $this->db->select('st.*, 
                       p.product_name, 
                       s_from.stock_name from_stock, 
                       s_to.stock_name to_stock');
    $this->db->from('stock_transfers st');
    $this->db->join('product_information p', 'p.product_id = st.product_id', 'left');
    $this->db->join('stock s_from', 's_from.id = st.from_stock_id', 'left');
    $this->db->join('stock s_to', 's_to.id = st.to_stock_id', 'left');
    $this->db->where("s_from.assign_users LIKE '%\"$user_id\"%'",null,false);
    $this->db->order_by('st.created_at', 'desc');
    $query = $this->db->get();

    if (!$query) {
        // Debug: print last query and DB error
        log_message('error', 'Stock transfer query failed: ' . $this->db->last_query());
        return [];
    }

    return $query->result_array();
}

public function get_all_stock_recevied() {
    $CI =& get_instance();
    $user_id = $CI->session->userdata('user_id');
    $this->db->select('st.*, 
                       p.product_name, 
                       s_from.stock_name from_stock, 
                       s_to.stock_name to_stock');
    $this->db->from('stock_transfers st');
    $this->db->join('product_information p', 'p.product_id = st.product_id', 'left');
    $this->db->join('stock s_from', 's_from.id = st.from_stock_id', 'left');
    $this->db->join('stock s_to', 's_to.id = st.to_stock_id', 'left');
    $this->db->where("s_to.assign_users LIKE '%\"$user_id\"%'", null, false);
    $this->db->order_by('st.created_at', 'desc');
    $query = $this->db->get();

    if (!$query) {
        // Debug: print last query and DB error
        log_message('error', 'Stock transfer query failed: ' . $this->db->last_query());
        return [];
    }

    return $query->result_array();
}








}
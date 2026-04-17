<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Creport extends CI_Controller {
	
	function __construct() {
     	parent::__construct();
      	$CI =& get_instance();
      	$CI->load->model('Web_settings');
    }
	public function index()
    {
       $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');
       
        $content = $CI->lreport->stock_report_single_item();
        
        $this->template->full_admin_html_view($content); 
    }


public function CheckList(){
        // GET data
        $this->load->model('Reports');
        $postData = $this->input->post();
        $data = $this->Reports->getCheckList($postData);
        echo json_encode($data);
    } 

        public function exportCSV(){ 
   // file name 
    $this->load->model('Reports');
    $usersData = $this->Reports->stock_csv_file();
   $filename = 'stock_'.date('Ymd').'.csv'; 
   header("Content-Description: File Transfer"); 
   header("Content-Disposition: attachment; filename=$filename"); 
   header("Content-Type: application/csv; ");
   
   // get data 
   $usersData = $this->Reports->stock_csv_file();

   // file creation 
   $file = fopen('php://output', 'w');
 
   $header = array('Product Id','Product Name','Product Model','Sell Price','Purchase Price','Total In Qty','Total Out Qty','Stock','Stock Purhcase Amount','Stock Sale Amount'); 
   fputcsv($file, $header);
   foreach ($usersData as $line){ 
     fputcsv($file,$line); 
   }
   fclose($file); 
   exit; 
  }

	
	public function out_of_stock(){
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreport');

		$content = $CI->lreport->out_of_stock();
        
		$this->template->full_admin_html_view($content);
	}

    public function CheckStockOutList(){
        // GET data
        $this->load->model('Reports');
        $postData = $this->input->post();
        $data = $this->Reports->getStockOutList($postData);
        echo json_encode($data);
    } 
	// Date Expire Medicine list
	public function out_of_date(){
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreport');

		$content = $CI->lreport->out_of_date();
        
		$this->template->full_admin_html_view($content);
	}

public function CheckExpireList(){
        // GET data
        $this->load->model('Reports');
        $postData = $this->input->post();
        $data = $this->Reports->getExpireList($postData);
        echo json_encode($data);
    } 



	//Get product by manufacturer
	public function get_product_by_manufacturer(){
		$manufacturer_id = $this->input->post('manufacturer_id');

		$product_info_by_manufacturer = $this->db->select('a.*,b.*')
											->from('product_information a')
											->join('manufacturer_product b','a.product_id=b.product_id')
											->where('b.manufacturer_id',$manufacturer_id)
											->get()
											->result();

		if ($product_info_by_manufacturer) {
			echo "<select class=\"form-control\" id=\"manufacturer_id\" name=\"manufacturer_id\">
	                <option value=\"\">".display('select_one')."</option>";
			foreach ($product_info_by_manufacturer as $product) {
				echo "<option value='".$product->product_id."'>".$product->product_name.'-('.$product->product_model.')'." </option>";
			}
			echo " </select>";
		}

	}


	#===============Report paggination=============#
	public function pagination($per_page,$page)
	{
		$CI =& get_instance();
		$CI->load->model('Reports');
		$product_id=$this->input->post('product_id');	
		
		$config = array();
		$config["base_url"] = base_url().$page;
		$config["total_rows"] = $this->Reports->product_counter($product_id);
		$config["per_page"] = $per_page;
		$config["uri_segment"] = 4;	
        $config["num_links"] = 5; 
        /* This Application Must Be Used With BootStrap 3 * */
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tag_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";



		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$limit = $config["per_page"];
	    return $links = $this->pagination->create_links();	
	}


        public function stock_report_batch_wise(){
        $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');  
        $CI->load->model('Reports');
        $content =$this->lreport->stock_report_batch_wise();
        $this->template->full_admin_html_view($content);
    }



    public function Checkbatchstock(){
        // GET data
        $this->load->model('Reports');
        $postData = $this->input->post();
        $data = $this->Reports->getCheckBatchStock($postData);
        echo json_encode($data);
    } 
// create stock
public function create_stock(){
    $CI =& get_instance();
	$CI->auth->check_admin_auth();
	$CI->load->library('lreport');
	$content = $CI->lreport->stock_add_form();
	$this->template->full_admin_html_view($content);

}
//insert stock
public function insert_stock()
{
    $this->load->library('form_validation');
    $this->form_validation->set_rules('stock_name', 'Stock Name', 'required');

    if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata('error', validation_errors());
        redirect('Creport/insert_stock');
    } else {
        $this->load->model('Stocks');

        // Collect basic data
        $data = array(
            'stock_name' => $this->input->post('stock_name', TRUE),
            'address'    => $this->input->post('stock_address', TRUE),
            'created_at' => date('Y-m-d H:i:s'),
        );

        // Handle assigned users as JSON
        $user_ids = $this->input->post('user_id'); // expects array
        if (!empty($user_ids)) {
            $data['assign_users'] = json_encode($user_ids);
        } else {
            $data['assign_users'] = json_encode([]);
        }
        // Insert into DB
        $this->Stocks->insert_stock($data);

        $this->session->set_flashdata('message', 'Stock added successfully!');
        redirect('Creport/manage_stock');
    }
}


// manage stock

     public function manage_stock() {
        $CI = & get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');
        $CI->load->model('Stocks');
        $content = $this->lreport->stock_list();
        $this->template->full_admin_html_view($content);
    }
      public function CheckStockList(){
        // GET data
        $this->load->model('Stocks');
        $postData = $this->input->post();
        $data = $this->Stocks->getStockList($postData);
        echo json_encode($data);
    } 
   
      public function stock_update_form($id)
	{	
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('lreport');
		 //$CI->permission1->method('invoice','update')->redirect();
		$content = $CI->lreport->stock_update_form($id);
		$this->template->full_admin_html_view($content);
	}
    public function update_stock($id = null)
{
    $this->load->library('form_validation');

    // Validation rules
    $this->form_validation->set_rules('stock_name', 'Stock Name', 'required|trim');
    $this->form_validation->set_rules('address', 'Address', 'trim');
    $this->form_validation->set_rules('user_id[]', 'Assign Users', 'required');

    if ($this->form_validation->run() === true) {
        $data = array(
            'stock_name'   => $this->input->post('stock_name', true),
            'address'      => $this->input->post('stock_address', true),
            'assign_users' => json_encode($this->input->post('user_id')), // store as JSON
            'created_at'   => date('Y-m-d H:i:s') // optional if you want to update timestamp
        );

        $this->db->where('id', $id);
        $update = $this->db->update('stock', $data);

        if ($update) {
            $this->session->set_flashdata('message', 'Stock updated successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to update stock');
        }

        redirect('Creport/manage_stock'); // replace with your listing page
    } else {
        // Reload the form with current data if validation fails
        $data['stock_name']    = $this->input->post('stock_name');
        $data['address']       = $this->input->post('address');
        $data['assign_users']  = $this->input->post('user_id');
        $data['all_users']     = $this->get_all_users();
        $data['id']            = $id;

        $this->load->view('update_stock_form', $data);
    }
}


public function stock_delete($id)
{
    // Delete the stock record
    $this->db->where('id', $id);
    $delete = $this->db->delete('stock');

    if ($delete) {
        $this->session->set_flashdata('success', 'Stock deleted successfully.');
    } else {
        $this->session->set_flashdata('error', 'Failed to delete stock.');
    }

    redirect('Creport/manage_stock'); // redirect to stock list
}

public function stock_transfer(){
      $CI =& get_instance();
	$CI->auth->check_admin_auth();
	$CI->load->library('lreport');
	$content = $CI->lreport->stock_transfer_form();
	$this->template->full_admin_html_view($content);

}

     public function fetch_products() {
    $stock_id = $this->input->post('stock_id'); // Get POST data
    $this->load->model('Stocks');
    $products = $this->Stocks->get_products_by_stock($stock_id);
    echo json_encode($products);
}
public function fetch_batches() {
    $product_id = $this->input->post('product_id');
    $stock_id   = $this->input->post('stock_id');

    if (!$product_id || !$stock_id) {
        echo json_encode([]);
        return;
    }

    $this->load->model('Stocks');
    $batches = $this->Stocks->get_batches_by_product_stock($product_id, $stock_id);
    echo json_encode($batches);
}

public function fetch_invoices() {
    $product_id = $this->input->post('product_id');
    $stock_id   = $this->input->post('stock_id');
    $batch_id   = $this->input->post('batch_id');

    if (!$product_id || !$stock_id || !$batch_id) {
        echo json_encode([]);
        return;
    }

    $this->load->model('Stocks');
    $invoices = $this->Stocks->get_invoice_by_product_stock($product_id, $stock_id, $batch_id);
    echo json_encode($invoices);
}
public function fetch_available_quantity() {
    $product_id = $this->input->post('product_id');
    $stock_id   = $this->input->post('stock_id');
    $batch_id   = $this->input->post('batch_id');
    $invoice_id = $this->input->post('invoice_id');

    if(!$product_id || !$stock_id || !$batch_id || !$invoice_id) {
        echo json_encode(['available_qty' => 0]);
        return;
    }

    $this->load->model('Stocks');
    $available_qty = $this->Stocks->get_available_quantity($product_id, $stock_id, $batch_id, $invoice_id);
    echo json_encode(['available_qty' => $available_qty]);
}
 public function insert_stock_transfer() {
    $this->load->model('Stocks');

    $from_stock_id = $this->input->post('stock_name');
    $to_stock_id   = $this->input->post('to_stock_id');
    $product_id    = $this->input->post('product_id');
    $batch_id      = $this->input->post('batch_id');
    $invoice_id    = $this->input->post('invoice_id');
    $available_qty = $this->input->post('available_qty');
    $transfer_qty  = $this->input->post('transfer_qty');
    $transfer_note = $this->input->post('transfer_note');

   // Fetch purchase_id and chalan_no based on stock, product, batch, and invoice
$this->db->select('pp.purchase_id, pp1.chalan_no');
$this->db->from('product_purchase_details pp');
$this->db->join('product_purchase pp1', 'pp1.purchase_id = pp.purchase_id', 'left');
$this->db->where('pp.stock_id', $from_stock_id);
$this->db->where('pp.product_id', $product_id);
$this->db->where('pp.batch_id', $batch_id);
$this->db->where('pp.invoice_id', $invoice_id); // make sure invoice_id exists in purchase_details

$purchase = $this->db->get()->row();


if ($purchase) {
    $purchase_id = $purchase->purchase_id;
    $chalan_no   = $purchase->chalan_no;
} else {
    $purchase_id = null;
    $chalan_no   = null;
}

    $data = [
        'from_stock_id' => $from_stock_id,
        'to_stock_id'   => $to_stock_id,
        'product_id'    => $product_id,
        'batch_id'      => $batch_id,
        'invoice_id'    => $invoice_id,
        'chalan_no'    => $chalan_no,
        'available_qty' => $available_qty,
        'transfer_qty'  => $transfer_qty,
        'transfer_note' => $transfer_note,
        'created_by'    => $this->session->userdata('user_id'),
        'created_at'    => date('Y-m-d H:i:s')
    ];

    $inserted = $this->Stocks->insert_stock_transfer($data);

    if ($inserted) {
        // 1. Update the quantity in the original stock
        $this->Stocks->update_available_quantity_after_transfer($product_id, $batch_id, $invoice_id, $transfer_qty);

        // 2. Insert a new record for the destination stock with new invoice_id
       $this->Stocks->insert_transferred_stock_to_destination($product_id, $to_stock_id, $batch_id, $transfer_qty, $invoice_id);
       // 3. insert into product purchese

        $this->session->set_flashdata('message', 'Stock transferred successfully!');
    } else {
        $this->session->set_flashdata('error_message', 'Failed to transfer stock.');
    }

    redirect('Creport/stock_transfer');
}
 public function stock_transfer_history(){
    //$this->product_id=$product_id;
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreport');
        $content = $CI->lreport->stock_transfer_history();
		$this->template->full_admin_html_view($content);

  }



 

	
}
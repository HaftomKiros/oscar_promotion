<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class reports extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	//Count report
	public function count_stock_report()
	{
		$this->db->select("a.product_name,a.product_id,a.cartoon_quantity,a.price,a.product_model,sum(b.quantity) as 'totalSalesQnty',(select sum(product_purchase_details.quantity) from product_purchase_details where product_id= `a`.`product_id`) as 'totalBuyQnty'");
		$this->db->from('product_information a');
		$this->db->join('invoice_details b','b.product_id = a.product_id');
		$this->db->where(array('a.status'=>1,'b.status'=>1));
		$this->db->group_by('a.product_id');
		$query = $this->db->get();		
		return $query->num_rows();

	}
		//Out of stock
	public function out_of_stock(){

	  $this->db->select("a.unit,a.product_name,a.product_id,a.price,a.product_model,(select sum(quantity) from invoice_details where product_id= `a`.`product_id`) as 'totalSalesQnty',(select sum(quantity) from product_purchase_details where product_id= `a`.`product_id`) as 'totalBuyQnty'");
        $this->db->from('product_information a');
        $this->db->where(array('a.status' => 1));
        $this->db->group_by('a.product_id');
        $query = $this->db->get();
         $result = $query->result_array();
         $stock = [];
         $i = 0;
         foreach ($result as $stockproduct) {
            $stokqty = $stockproduct['totalBuyQnty']-$stockproduct['totalSalesQnty'];
            if($stokqty < 10){

             $stock[$i]['stock']         = $stockproduct['totalBuyQnty']-$stockproduct['totalSalesQnty'];
             $stock[$i]['product_id']    = $stockproduct['product_id'];
             $stock[$i]['product_name']  = $stockproduct['product_name'];
             $stock[$i]['product_model'] = $stockproduct['product_model'];
             $stock[$i]['unit']          = $stockproduct['unit'];
         }
             $i++;
         }
        return $stock;
	}


	public function getStockOutList($postData = null)
{
    $response = array();
    $CI =& get_instance();
    $user_id = $this->session->userdata('user_id'); // logged-in user

    ## Read DataTable params
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length'];
    $columnIndex = $postData['order'][0]['column'];
    $columnName = $postData['columns'][$columnIndex]['data'];
    $columnSortOrder = $postData['order'][0]['dir'];
    $searchValue = $postData['search']['value'];

    ## Base query: calculate stock per purchase detail
    $this->db->select('
        pd.product_id,
        pd.batch_id,
        pd.stock_id,
        (pd.quantity - IFNULL(idt.quantity, 0)) AS stock,
        a.product_name,
        a.generic_name,
        s.stock_name,
        pd.batch_id,
        pd.invoice_id,
        a.strength,
        b.manufacturer_name
    ');
    $this->db->from('product_purchase_details pd');
    $this->db->join('invoice_details idt', 
        'pd.product_id = idt.product_id 
         AND pd.batch_id = idt.batch_id 
         AND pd.invoice_id = idt.pinvoice_id 
         AND pd.stock_id = idt.stock_id', 
        'left'
    );
    $this->db->join('stock s', 's.id = pd.stock_id', 'left');
    $this->db->join('product_information a', 'a.product_id = pd.product_id', 'left');
    $this->db->join('manufacturer_information b', 'b.manufacturer_id = a.manufacturer_id', 'left');
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);

    // Search filter
    if(!empty($searchValue)){
    $this->db->group_start();
    $this->db->like('a.product_name', $searchValue);
    $this->db->or_like('b.manufacturer_name', $searchValue);
    $this->db->or_like('a.generic_name', $searchValue);
    $this->db->or_like('s.stock_name', $searchValue); // added stock_name search
    $this->db->group_end();
}


    // Only low stock
    $this->db->having('stock <', 10);

    // Order by expiry date
    $this->db->order_by('pd.expeire_date', 'ASC');

    $query = $this->db->get();
    $allRows = $query->result();

    // Total records after filter
    $totalRecordwithFilter = count($allRows);

    // Pagination
    $records = array_slice($allRows, $start, $rowperpage);

    // Prepare data for DataTables
    $data = [];
    $sl = $start + 1;
    foreach($records as $record){
        $data[] = [
            'sl' => $sl,
            'product_name' => $record->product_name.'('.$record->strength.')',
            'manufacturer_name' => $record->manufacturer_name,
            'generic_name' => $record->generic_name,
            'batch_id' => $record->batch_id,
            'stock_name' => $record->stock_name,
            'invoice_id' => $record->invoice_id,
            'stock' => $record->stock,
        ];
        $sl++;
    }

    // Response
    $response = [
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecordwithFilter,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    ];

    return $response;
}




		public function stock_csv_file()
	{
		$this->db->select("a.product_id,
				a.product_name,
				a.product_model,
				 a.price,
				a.manufacturer_price
				");
		$this->db->from('product_information a');
		$query = $this->db->get();
		$stok_report = $query->result_array();
		
         $i = 1;
		foreach($stok_report as $k=>$v){$i++;
				$stockin = $this->db->select('sum(quantity) as totalSalesQnty')->from('invoice_details')->where('product_id',$stok_report[$k]['product_id'])->get()->row();
				$stockout = $this->db->select('sum(quantity) as totalPurchaseQnty')->from('product_purchase_details')->where('product_id',$stok_report[$k]['product_id'])->get()->row();
				
			 $stok_report[$k]['totalPurchaseQnty'] = $stockout->totalPurchaseQnty;	
			  $stok_report[$k]['totalSalesQnty'] = $stockin->totalSalesQnty;
			 $stok_report[$k]['stok_quantity_cartoon'] = ($stockout->totalPurchaseQnty-$stockin->totalSalesQnty);
			  $stok_report[$k]['purchase_total']=$stok_report[$k]['stok_quantity_cartoon']*$stok_report[$k]['manufacturer_price'];
			   
			      $stok_report[$k]['total_sale_price']=$stok_report[$k]['stok_quantity_cartoon']*$stok_report[$k]['price'];
			    
             


			}
			return $stok_report;
		
	}	

		public function count_stock_report_bydate()
	{	
		$this->db->select("a.*,
				a.product_name,
				a.product_id,
				a.product_model,
				a.manufacturer_price
				");
		$this->db->from('product_information a');
	
		
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->num_rows();	
		}
		return false;
	}




		public function getExpireList($postData=null){
            $CI =& get_instance();
    $CI->load->model('Stocks');
    $user_id = $CI->session->userdata('user_id');
    $date = date('Y-m-d');
    $response = array();
    
    // Read value
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length']; // Rows display per page
    $columnIndex = $postData['order'][0]['column']; // Column index
    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
    $searchValue = $postData['search']['value']; // Search value

    // Search query
    $searchQuery = "";
    if ($searchValue != '') {
        $searchQuery = " (a.product_name like '%" . $searchValue . "%' or b.batch_id like '%" . $searchValue . "%' or b.expeire_date like '%" . $searchValue . "%') ";
    }

    // Total number of records without filtering
    $this->db->select("count(*) as allcount, ((select ifnull(sum(quantity),0) from product_purchase_details where product_id = `a`.`product_id`) - (select ifnull(sum(quantity),0) from invoice_details where product_id = `a`.`product_id`)) as 'stock'");
    $this->db->from('product_information a');
    $this->db->join('product_purchase_details b', 'b.product_id = a.product_id', 'left');
    $this->db->join('stock s', 's.id = b.stock_id', 'left'); // Join stock table to filter by user
    if ($searchValue != '') {
        $this->db->where($searchQuery);
    }
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false); // Filter by user assignment to stock
    $this->db->where('b.expeire_date <=', $date);
    $this->db->having('stock > 0');
    $this->db->group_by('b.batch_id');
    $this->db->group_by('a.product_id');
    $totalRecords = $this->db->get()->num_rows();

    // Total number of records with filtering
    $this->db->select("count(*) as allcount, ((select ifnull(sum(quantity),0) from product_purchase_details where product_id = `a`.`product_id`) - (select ifnull(sum(quantity),0) from invoice_details where product_id = `a`.`product_id`)) as 'stock'");
    $this->db->from('product_information a');
    $this->db->join('product_purchase_details b', 'b.product_id = a.product_id', 'left');
    $this->db->join('stock s', 's.id = b.stock_id', 'left'); // Join stock table to filter by user
    if ($searchValue != '') {
        $this->db->where($searchQuery);
    }
     $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false); // Filter by user assignment to stock
    $this->db->where('b.expeire_date <=', $date);
    $this->db->having('stock > 0');
    $this->db->group_by('b.batch_id');
    $this->db->group_by('a.product_id');
    $totalRecordwithFilter = $this->db->get()->num_rows();

    // Fetch records
    $this->db->select("b.*, a.product_name, a.strength, ((select ifnull(sum(quantity),0) from product_purchase_details where product_id = `a`.`product_id`) - (select ifnull(sum(quantity),0) from invoice_details where product_id = `a`.`product_id`)) as 'stock'");
    $this->db->from('product_information a');
    $this->db->join('product_purchase_details b', 'b.product_id = a.product_id', 'left');
    $this->db->join('stock s', 's.id = b.stock_id', 'left'); // Join stock table to filter by user
    if ($searchValue != '') {
        $this->db->where($searchQuery);
    }
    $this->db->where('b.expeire_date <=', $date);
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false); // Filter by user assignment to stock
    $this->db->having('stock > 0');
    $this->db->group_by('b.batch_id');
    $this->db->group_by('a.product_id');
    $this->db->order_by($columnName, $columnSortOrder);
    $this->db->limit($rowperpage, $start);
    $records = $this->db->get()->result();

    // Process records
    $data = array();
    $sl = 1;
    $base_url = base_url();
    foreach ($records as $record) {
        $medicine_name = '<a href="' . $base_url . 'Cproduct/product_details/' . $record->product_id . '" class="" data-toggle="tooltip" data-placement="left" >' . $record->product_name . '(' . $record->strength . ')</a>';
        $data[] = array(
            'sl' => $sl,
            'product_id' => $medicine_name,
            'batch_id' => $record->batch_id,
            'expeire_date' => $record->expeire_date,
            'stock' => $record->stock,
        );
        $sl++;
    }

    // Return response
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecordwithFilter,
        "iTotalDisplayRecords" => $totalRecords,
        "aaData" => $data
    );

    return $response;
}



	//Out of stock count
	public function out_of_stock_count()
{
    $CI =& get_instance();
    $user_id = $this->session->userdata('user_id'); // logged-in user

    $CI->db->select("pd.product_id, SUM(pd.quantity - IFNULL(idt.quantity,0)) AS stock");
    $CI->db->from('product_purchase_details pd');

    // Join invoice_details to calculate sold quantities
    $CI->db->join('invoice_details idt', 
        'pd.product_id = idt.product_id AND pd.stock_id = idt.stock_id',
        'left'
    );

    // Join stock table to filter assigned stocks
    $CI->db->join('stock s', 's.id = pd.stock_id', 'left');

    // Filter only stocks assigned to logged-in user
    $CI->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);

    // Group by product_id
    $CI->db->group_by('pd.product_id');

    // Having low stock (<10)
    $CI->db->having('stock <', 10);

    $query = $CI->db->get();

    return $query->num_rows(); // return count of products with low stock
}




	// out of date count
	public function out_of_date_count(){
        $CI =& get_instance();
        $user_id = $CI->session->userdata('user_id');
		  $date=date('Y-m-d');
         $this->db->select("b.*,a.product_name,a.strength,((select ifnull(sum(quantity),0) from product_purchase_details where product_id= `a`.`product_id`)-(select ifnull(sum(quantity),0) from invoice_details where product_id= `a`.`product_id`)) as 'stock'");
         $this->db->from('product_information a');
         $this->db->join('product_purchase_details b','b.product_id=a.product_id','left');
         $this->db->join('stock s', 's.id = b.stock_id','left');
         $this->db->where('b.expeire_date <=', $date);
         $this->db->where("s.assign_users LIKE '%\"$user_id\"%'",null,false);
         $this->db->having('stock > 0');
         $this->db->group_by('b.batch_id');
         $this->db->group_by('a.product_id');
        return $records = $this->db->get()->num_rows();


	}
	//Retrieve Single Item Stock Stock Report
	public function stock_report($limit,$page)
	{
		//$today = date('d-m-Y');
		$this->db->select("a.product_name,a.product_id,a.cartoon_quantity,a.price,a.product_model,sum(b.quantity) as 'totalSalesQnty',(select sum(product_purchase_details.quantity) from product_purchase_details where product_id= `a`.`product_id`) as 'totalBuyQnty'");
		$this->db->from('product_information a');
		$this->db->join('invoice_details b','b.product_id = a.product_id');
		$this->db->where(array('a.status'=>1,'b.status'=>1));
		$this->db->group_by('a.product_id');
		$this->db->order_by('a.product_id','desc');
		$this->db->limit($limit, $page);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//Retrieve Single Item Stock Stock Report
	public function stock_report_single_item($product_id){
		$this->db->select("a.product_name,a.cartoon_quantity,a.price,a.product_model,sum(b.quantity) as 'totalSalesQnty',sum(c.quantity) as 'totalBuyQnty'");
		$this->db->from('product_information a');
		$this->db->join('invoice_details b','b.product_id = a.product_id');
		$this->db->join('product_purchase_details c','c.product_id = a.product_id');
		$this->db->where(array('a.product_id'=>$product_id,'a.status'=>1,'b.status'=>1));
		$this->db->group_by('a.product_id');
		$this->db->order_by('a.product_id','desc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}

	//Stock Report by date
public function stock_report_bydate($product_id,$date,$limit,$page)
	{	
		$this->db->select("a.*,
				a.product_name,
				a.product_id,
				a.product_model,
				a.manufacturer_price
				");
		$this->db->from('product_information a');
	
		if(empty($product_id))
		{
			$this->db->where(array('a.status'=>1));
		}
		else
		{
			//Single product information 
			$this->db->where(array('a.status'=>1,'a.product_id'=>$product_id));	
		}
		

		$this->db->limit($limit, $page);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	public function totalnumberof_product(){

		$this->db->select("a.*,
				a.product_name,
				a.product_id,
				a.product_model,
				a.manufacturer_price
				");
		$this->db->from('product_information a');

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->num_rows();	
		}
		return false;

	}


	public function getCheckList($postData=null){
        $CI =& get_instance();
       $user_id = $CI->session->userdata('user_id');

         $response = array();

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
            $searchQuery = " (a.product_name like '%".$searchValue."%' or a.product_model like '%".$searchValue."%' or a.price like'%".$searchValue."%' or a.manufacturer_price like'%".$searchValue."%' or m.manufacturer_name like'%".$searchValue."%') ";
         }

         ## Total number of records without filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('product_information a');
         $this->db->join('manufacturer_information m','m.manufacturer_id = a.manufacturer_id','left');
         $this->db->join('product_purchase_details ppd','ppd.product_id = a.product_id', 'left');
         $this->db->join('stock s','s.id = ppd.stock_id', 'left');
         $this->db->where("s.assign_users LIKE '%\"$user_id\"%'",null,false);
          if($searchValue != '')
         $this->db->where($searchQuery);
        
         $records = $this->db->get()->result();
         $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('product_information a');
         $this->db->join('manufacturer_information m','m.manufacturer_id = a.manufacturer_id','left');
            $this->db->join('product_purchase_details ppd','ppd.product_id = a.product_id', 'left');
         $this->db->join('stock s','s.id = ppd.stock_id', 'left');
         $this->db->where("s.assign_users LIKE '%\"$user_id\"%'",null,false);
         if($searchValue != '')
            $this->db->where($searchQuery);
         $records = $this->db->get()->result();
         $totalRecordwithFilter = $records[0]->allcount;

         ## Fetch records
         $this->db->select("a.*,
				a.product_name,
				a.product_id,
				a.product_model,
				a.manufacturer_price,
				m.manufacturer_name
				");
         $this->db->from('product_information a');
         $this->db->join('manufacturer_information m','m.manufacturer_id = a.manufacturer_id','left');
          $this->db->join('product_purchase_details ppd','ppd.product_id = a.product_id', 'left');
         $this->db->join('stock s','s.id = ppd.stock_id', 'left');
         $this->db->where("s.assign_users LIKE '%\"$user_id\"%'",null,false);
         $this->db->group_by('a.product_id');
         if($searchValue != '')
         $this->db->where($searchQuery);
         $this->db->order_by($columnName, $columnSortOrder);
         $this->db->limit($rowperpage, $start);
         $records = $this->db->get()->result();
        // echo $this->db->last_query();
         $data = array();
         $sl =1;
         $base_url = base_url();
         foreach($records as $record ){
         $stockin = $this->db
    ->select('SUM(quantity) AS totalSalesQnty')
    ->from('invoice_details a')
    ->join('stock s','s.id=a.stock_id','left')
      ->where("s.assign_users LIKE '%\"$user_id\"%'",null,false)
    ->where('product_id', $record->product_id)
    ->get()
    ->row();

$stockout = $this->db
    ->select('SUM(quantity) AS totalPurchaseQnty')
    ->from('product_purchase_details d')
    ->join('stock s','s.id=d.stock_id','left')
    ->where("s.assign_users LIKE '%\"$user_id\"%'",null,false)
    ->where('product_id', $record->product_id)
    ->get()
    ->row();

			 $medicine_name = '<a href="'.$base_url.'Cproduct/product_details/'.$record->product_id.'" class="" data-toggle="tooltip" data-placement="left" >'.$record->product_name.'('.$record->strength.')'.'</a>';
               
            $data[] = array( 
            	'sl'            =>   $sl,
                'product_name' 	=> 	$medicine_name,
                'manufacturer_name'=> $record->manufacturer_name,
				'product_model' => 	$record->product_model,
				'sales_price'   =>  $record->price,
				'purchase_p'	=>	$record->manufacturer_price,
				'totalPurchaseQnty'=>$stockout->totalPurchaseQnty,
				'totalSalesQnty'=>	$stockin->totalSalesQnty,
				'stok_quantity'	=>	$stockout->totalPurchaseQnty-$stockin->totalSalesQnty,
				'total_sale_price'=> ($stockout->totalPurchaseQnty-$stockin->totalSalesQnty)*$record->price,
				'purchase_total' =>  ($stockout->totalPurchaseQnty-$stockin->totalSalesQnty)*$record->manufacturer_price,
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
	//Stock report manufacturer by date
	public function stock_report_manufacturer_bydate($product_id=null,$manufacturer_id=null,$date=null,$perpage=null,$page=null){

		$this->db->select("*");
		$this->db->from('product_information ');
		$this->db->limit($perpage,$page);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
// manufacturer stock report id wise
	public function stock_report_manufacturer_byid($manufacturer_id=null,$date=null,$perpage=null,$page=null){

		$this->db->select("*");
		$this->db->from('product_information');
    	$this->db->where('manufacturer_id',$manufacturer_id);
		
		$this->db->limit($perpage,$page);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//Counter of unique product histor which has been affected
	public function product_counter_by_manufacturer($manufacturer_id)
	{		
		$this->db->select('DISTINCT(a.product_id)');  
	    $this->db->from('product_information a');
			if(!empty($manufacturer_id))
			{$this->db->where('a.manufacturer_id =',$manufacturer_id);  }
	    $query=$this->db->get(); 
	    return $query->num_rows();
	}

	//Stock report manufacturer by date




	//Counter of unique product histor which has been affected
	public function product_counter($product_id)
	{		
		$this->db->select('DISTINCT(product_id)');  
	    $this->db->from('product_information');
			if(!empty($product_id))
			{$this->db->where('product_id =',$product_id);  }
	    $query=$this->db->get(); 
	    return $query->num_rows();
	}

	//Retrieve todays_total_sales_report
	public function todays_total_sales_report()
	{
        $today = date('Y-m-d');
        $CI =& get_instance();
         $user_id = $CI->session->userdata('user_id');
		$this->db->select('sum(total_price) as total_sale');
		$this->db->from('invoice_details pp');
         $this->db->join('stock s', 's.id = pp.stock_id','left');
        $this->db->join('invoice p','p.invoice_id=pp.invoice_id','left');
        $this->db->where("s.assign_users LIKE '%\"$user_id\"%'",null,false);
		$this->db->where('p.date',$today);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;


	}
	// total purchase info
		public function todays_total_purchase()
	{
        
		$today = date('Y-m-d');
        $CI =& get_instance();
         $user_id = $CI->session->userdata('user_id');
		$this->db->select('sum(total_amount) as total_purchase');
		$this->db->from('product_purchase_details pp');
        $this->db->join('stock s', 's.id = pp.stock_id','left');
        $this->db->join('product_purchase p','p.purchase_id=pp.purchase_id','left');
        $this->db->where("s.assign_users LIKE '%\"$user_id\"%'",null,false);
		$this->db->where('p.purchase_date',$today);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}		

	// todays sales product
	public function todays_sale_product(){
		$today = date('Y-m-d');
		$this->db->select("c.product_name,c.price");
		$this->db->from('invoice a');
		$this->db->join('invoice_details b','b.invoice_id = a.invoice_id');
		$this->db->join('product_information c','c.product_id = b.product_id');
		$this->db->order_by('a.date','desc');
		$this->db->where('a.date',$today);
		$this->db->limit('3');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}

	public function range_sales_report($start_date = null, $end_date = null, $per_page = null, $page = null)
{
    $CI =& get_instance();
    $user_id = $CI->session->userdata('user_id'); // get logged-in user ID

    // Use today's date if dates are not provided
    $start_date = !empty($start_date) ? $start_date : date('Y-m-d');
    $end_date   = !empty($end_date)   ? $end_date   : date('Y-m-d');

    $this->db->select("
        a.*, 
        s.stock_name,
        b.customer_name, 
        d.paid_amount, 
        d.due_amount, 
        d.total_manufacturer
    ");
    $this->db->from('invoice a');

    // Join customer and stock tables
    $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
    $this->db->join('stock s', 's.id = a.stock_id', 'left');

    // Subquery to aggregate invoice details
    $this->db->join("(
        SELECT 
            invoice_id,
            MAX(paid_amount) AS paid_amount,
            MAX(due_amount) AS due_amount,
            SUM(quantity * manufacturer_rate) AS total_manufacturer
        FROM invoice_details
        GROUP BY invoice_id
    ) d", 'd.invoice_id = a.invoice_id', 'left');

    // Filter invoices by logged-in user's assigned stocks
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);

    // Date filtering
    $this->db->where('a.date >=', $start_date);
    $this->db->where('a.date <=', $end_date);

    $this->db->order_by('a.date', 'desc');

    // Pagination (if applicable)
    if (!is_null($per_page) && !is_null($page)) {
        $this->db->limit((int)$per_page, (int)$page);
    }

    $query = $this->db->get();

    return ($query->num_rows() > 0) ? $query->result_array() : false;
}


	
	//Retrieve todays_sales_report
	public function todays_sales_report($per_page = null, $page = null)
{
    $CI =& get_instance();
    $user_id = $CI->session->userdata('user_id'); // current logged-in user
    $today = date('Y-m-d'); // today's date

    // -----------------------
    // Build query
    // -----------------------
    $this->db->select("
        a.*, 
        b.customer_name, 
        s.stock_name,
        d.paid_amount, 
        d.due_amount, 
        d.total_manufacturer
    ");
    $this->db->from('invoice a');

    // Join customer information
    $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');

    // Join stock table to filter by assigned users
    $this->db->join('stock s', 's.id = a.stock_id', 'left');

    // Subquery for invoice details
    $this->db->join("
        (
            SELECT 
                invoice_id,
                MAX(paid_amount) AS paid_amount,
                MAX(due_amount) AS due_amount,
                SUM(quantity * manufacturer_rate) AS total_manufacturer
            FROM invoice_details
            GROUP BY invoice_id
        ) d", 'd.invoice_id = a.invoice_id', 'left');

    // Filter by today and user assignment
    $this->db->where("DATE(a.date)", $today);
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false); // only assigned stocks

    // Order by date descending
    $this->db->order_by('a.date', 'desc');

    // Apply pagination
    if ($per_page !== null && $page !== null) {
        $this->db->limit($per_page, $page);
    }

    // Execute query
    $query = $this->db->get();

    return ($query->num_rows() > 0) ? $query->result_array() : false;
}


// retrive all report


public function dashboard_totals()
{
    $CI =& get_instance(); // Needed to use DB
    $user_id = $CI->session->userdata('user_id');

    // 1. Total purchase
    $CI->db->select('SUM(l.total_amount) AS total_purchase');
$CI->db->from('product_purchase_details l');
$CI->db->join('stock s', 's.id = l.stock_id', 'left');
$CI->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);

$purchase = $CI->db->get()->row_array();
$total_purchase = $purchase['total_purchase'];

    // 2. Total sales
   $CI->db->select('SUM(id.total_price) AS total_sales');
$CI->db->from('invoice_details id');
$CI->db->join('stock s', 's.id = id.stock_id', 'left'); // if you have stock_id in invoice_details
$CI->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false); // optional filter if needed

$sales = $CI->db->get()->row_array();
$total_sales = $sales['total_sales'];

    // 3. Total expense
   $CI->db->select('SUM(e.amount) AS total_expense');
$CI->db->from('expense e');
$CI->db->join('stock s', 's.id = e.stock_id', 'left'); // only if needed
$CI->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false); // optional
$expense = $CI->db->get()->row_array();
$total_expense = $expense['total_expense'];

    // 4. Total paid salaries
 $CI->db->select('SUM(sal.total_salary) AS total_salary_paid');
$CI->db->from('employee_salary_payment sal'); // alias for salary table
$CI->db->join('employee_history e', 'e.id = sal.employee_id', 'left'); // join employee_history
$CI->db->join('stock st', 'st.id = e.stock_id', 'left'); // alias 'st' for stock table
$CI->db->where('sal.payment_due', 'paid');
$CI->db->where("st.assign_users LIKE '%\"$user_id\"%'", null, false); // optional filter

$salary = $CI->db->get()->row_array();
$total_salary_paid = $salary['total_salary_paid'];

    // 5. Total paid amount per distinct invoice_id
    $CI =& get_instance();
$user_id = $CI->session->userdata('user_id');

$CI->db->select('SUM(sub.paid_amount) AS total_paid');
$CI->db->from('(SELECT invoice_id, paid_amount, stock_id FROM invoice_details GROUP BY invoice_id) AS sub');
$CI->db->join('stock st', 'st.id = sub.stock_id', 'left'); // link invoice to stock
$CI->db->where("st.assign_users LIKE '%\"$user_id\"%'", null, false); // filter by assigned user
$paid = $CI->db->get()->row_array();
$total_paid = $paid['total_paid'];


    // Calculate net profit
    $net_profit  = ($sales['total_sales'] ?? 0)
                 - (($purchase['total_purchase'] ?? 0)
                 + ($expense['total_expense'] ?? 0)
                 + ($salary['total_salary_paid'] ?? 0));

    $paid_profit = ($paid['total_paid'] ?? 0)
                 - (($purchase['total_purchase'] ?? 0)
                 + ($expense['total_expense'] ?? 0)
                 + ($salary['total_salary_paid'] ?? 0));

    return [
        'dashboard_purchase'    => $purchase['total_purchase'] ?? 0,
        'dashboard_sales'       => $sales['total_sales'] ?? 0,
        'dashboard_expense'     => $expense['total_expense'] ?? 0,
        'dashboard_salary'      => $salary['total_salary_paid'] ?? 0,
        'dashboard_paid_total'  => $paid['total_paid'] ?? 0,
        'dashboard_net_profit'  => $net_profit,
        'dashboard_paid_profit' => $paid_profit
    ];
}






	

	//Retrieve todays_sales_report_count
	public function todays_sales_report_count()
	{
		$today = date('Y-m-d');
		$this->db->select("a.*,b.customer_id,b.customer_name");
		$this->db->from('invoice a');
		$this->db->join('customer_information b','b.customer_id = a.customer_id');
		$this->db->where('a.date',$today);
		$this->db->order_by('a.invoice_id','desc');
		$query = $this->db->get();	
		return $query->num_rows();
	}	

	//Retrieve todays_purchase_report
	public function todays_purchase_report($per_page = null, $page = null)
{
    $user_id = $this->session->userdata('user_id'); // current logged-in user
    $today = date('Y-m-d');

    $this->db->select("a.*, b.manufacturer_id, b.manufacturer_name,s.stock_name");
    $this->db->from('product_purchase a');
    $this->db->join('manufacturer_information b', 'b.manufacturer_id = a.manufacturer_id', 'left');
    $this->db->join('stock s', 's.id = a.stock_id', 'left'); // join stock table

    // Filter by today's date
    $this->db->where('a.purchase_date', $today);

    // Filter only stocks assigned to the current user
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);

    $this->db->order_by('a.purchase_id', 'desc');

    if (!is_null($per_page) && !is_null($page)) {
        $this->db->limit($per_page, $page);
    }

    $query = $this->db->get();
    return ($query->num_rows() > 0) ? $query->result_array() : false;
}


	//Retrieve todays_purchase_report count
	public function todays_purchase_report_count()
	{
		$today = date('Y-m-d');
		$this->db->select("a.*,b.manufacturer_id,b.manufacturer_name");
		$this->db->from('product_purchase a');
		$this->db->join('manufacturer_information b','b.manufacturer_id = a.manufacturer_id');
		$this->db->where('a.purchase_date',$today);
		$this->db->order_by('a.purchase_id','desc');
		$this->db->limit('500');
		$query = $this->db->get();	
		return $query->num_rows();
	}

	//Total profit report
	public function total_profit_report($perpage,$page){

		$this->db->select("a.date,a.invoice,b.invoice_id,
			CAST(sum(total_price) AS DECIMAL(16,2)) as total_sale");
		$this->db->select('CAST(sum(`quantity`*`manufacturer_rate`) AS DECIMAL(16,2)) as total_manufacturer_rate', FALSE);
		$this->db->select("CAST(SUM(total_price) - SUM(`quantity`*`manufacturer_rate`) AS DECIMAL(16,2)) AS total_profit");
		$this->db->from('invoice a');
		$this->db->join('invoice_details b','b.invoice_id = a.invoice_id');
		$this->db->group_by('b.invoice_id');
		$this->db->order_by('a.invoice','desc');
		$this->db->limit($perpage,$page);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//Total profit report
	public function total_profit_report_count(){

		$this->db->select("a.date,a.invoice,b.invoice_id,sum(total_price) as total_sale");
		$this->db->select('sum(`quantity`*`manufacturer_rate`) as total_manufacturer_rate', FALSE);
		$this->db->select("(SUM(total_price) - SUM(`quantity`*`manufacturer_rate`)) AS total_profit");
		$this->db->from('invoice a');
		$this->db->join('invoice_details b','b.invoice_id = a.invoice_id');
		$this->db->group_by('b.invoice_id');
		$this->db->order_by('a.invoice','desc');
		$query = $this->db->get();
		return $query->num_rows();
	}

	//Retrieve Monthly Sales Report
// 	public function monthly_sales_report()
// 	{
// 		$query1 = $this->db->query("
// 			SELECT 
// 				date,
// 				EXTRACT(MONTH FROM STR_TO_DATE(date,'%Y-%m-%d')) as month, 
// 				COUNT(invoice_id) as total
// 			FROM 
// 				invoice
// 			WHERE 
// 				EXTRACT(YEAR FROM STR_TO_DATE(date,'%Y-%m-%d'))  >= EXTRACT(YEAR FROM NOW())
// 			GROUP BY 
// 				EXTRACT(YEAR_MONTH FROM STR_TO_DATE(date,'%Y-%m-%d'))
// 			ORDER BY
// 				month ASC
// 		")->result();

// 		$query2 = $this->db->query("
// 			SELECT 
// 				purchase_date,
// 				EXTRACT(MONTH FROM STR_TO_DATE(purchase_date,'%Y-%m-%d')) as month, 
// 				COUNT(purchase_id) as total_month
// 			FROM 
// 				product_purchase
// 			WHERE 
// 				EXTRACT(YEAR FROM STR_TO_DATE(purchase_date,'%Y-%m-%d'))  >= EXTRACT(YEAR FROM NOW())
// 			GROUP BY 
// 				EXTRACT(YEAR_MONTH FROM STR_TO_DATE(purchase_date,'%Y-%m-%d'))
// 			ORDER BY
// 				month ASC
// 		")->result();
// //print_r($query1);exit;
// 		return [$query1,$query2];
// 	}



	//Retrieve all Report
	public function retrieve_dateWise_SalesReports($start_date, $end_date)
{
    $dateRange = "a.date BETWEEN '$start_date' AND '$end_date'";

    $this->db->select("
        a.*, 
        b.customer_name, 
        d.paid_amount, 
        d.due_amount, 
        d.total_manufacturer
    ");
    $this->db->from('invoice a');
    $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
    $this->db->join("
        (
            SELECT 
                invoice_id,
                MAX(paid_amount) AS paid_amount,
                MAX(due_amount) AS due_amount,
                SUM(quantity * manufacturer_rate) AS total_manufacturer
            FROM invoice_details
            GROUP BY invoice_id
        ) d", 'd.invoice_id = a.invoice_id', 'left');

    $this->db->where($dateRange, NULL, FALSE);
    $this->db->order_by('a.date', 'desc');
    $this->db->limit(500);

    $query = $this->db->get();
    return ($query->num_rows() > 0) ? $query->result_array() : false;
}

	//Retrieve all Report
	public function retrieve_dateWise_PurchaseReports($start_date, $end_date)
{
    $user_id = $this->session->userdata('user_id'); // current logged-in user
    $dateRange = "a.purchase_date BETWEEN '$start_date' AND '$end_date'";

    $this->db->select("a.*, b.manufacturer_id, b.manufacturer_name,s.stock_name");
    $this->db->from('product_purchase a');
    $this->db->join('manufacturer_information b', 'b.manufacturer_id = a.manufacturer_id', 'left');
    $this->db->join('stock s', 's.id = a.stock_id', 'left'); // join stock table

    // Filter by date range
    $this->db->where($dateRange, NULL, FALSE);

    // Filter only stocks assigned to the current user
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", NULL, FALSE);

    $this->db->order_by('a.purchase_date', 'desc');
    $this->db->limit(500);

    $query = $this->db->get();
    return ($query->num_rows() > 0) ? $query->result_array() : false;
}

	//Retrieve date wise profit report
	public function retrieve_dateWise_profit_report($start_date,$end_date,$per_page,$page)
	{
		$this->db->select("a.date,a.invoice,b.invoice_id,
			CAST(sum(total_price) AS DECIMAL(16,2)) as total_sale");
		$this->db->select('CAST(sum(`quantity`*`manufacturer_rate`) AS DECIMAL(16,2)) as total_manufacturer_rate', FALSE);
		$this->db->select("CAST(SUM(total_price) - SUM(`quantity`*`manufacturer_rate`) AS DECIMAL(16,2)) AS total_profit");

		$this->db->from('invoice a');
		$this->db->join('invoice_details b','b.invoice_id = a.invoice_id');

		// $this->db->where($dateRange, NULL, FALSE); 
		$this->db->where('a.date >=', $start_date); 
		$this->db->where('a.date <=', $end_date); 

		$this->db->group_by('b.invoice_id');
		$this->db->order_by('a.invoice','desc');
		$this->db->limit($per_page,$page);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}	
	//Retrieve date wise profit report
	public function retrieve_dateWise_profit_report_count($start_date,$end_date)
	{
		// $dateRange = "a.date BETWEEN '$start_date%' AND '$end_date%'";


		$this->db->select("a.date,a.invoice,b.invoice_id,sum(total_price) as total_sale");
		$this->db->select('sum(`quantity`*`manufacturer_rate`) as total_manufacturer_rate', FALSE);
		$this->db->select("(SUM(total_price) - SUM(`quantity`*`manufacturer_rate`)) AS total_profit");

		$this->db->from('invoice a');
		$this->db->join('invoice_details b','b.invoice_id = a.invoice_id');

		// $this->db->where($dateRange, NULL, FALSE); 
		$this->db->where('a.date >=', $start_date); 
		$this->db->where('a.date <=', $end_date); 

		$this->db->group_by('b.invoice_id');
		$this->db->order_by('a.invoice','desc');
		$query = $this->db->get();
		return $query->num_rows();
	}
	//Product wise sales report
	public function product_wise_report()
	{
		$today = date('Y-m-d');
		$this->db->select("a.*,b.customer_id,b.customer_name");
		$this->db->from('invoice a');
		$this->db->join('customer_information b','b.customer_id = a.customer_id');
		$this->db->where('a.date',$today);
		$this->db->order_by('a.invoice_id','desc');
		$this->db->limit('500');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//sum of expense
	public function sum_expenses() {
    $CI =& get_instance();
    $user_id = $CI->session->userdata('user_id');

    $this->db->select_sum('e.amount', 'total_amount'); // sum with alias
    $this->db->from('expense e');
    $this->db->join('stock s', 's.id = e.stock_id', 'left');
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", null, false);

    $query = $this->db->get();
    return (float) $query->row()->total_amount ?? 0; // force float
}


	//RETRIEVE DATE WISE SINGE PRODUCT REPORT
	public function retrieve_product_sales_report($perpage, $page)
{
    $user_id = $this->session->userdata('user_id'); // current logged-in user

    $this->db->select("
        a.*, 
        b.product_name, 
        b.product_model, 
		s.stock_name,
        c.date, 
        c.total_amount, 
        d.customer_name
    ");
    $this->db->from('invoice_details a');
    $this->db->join('product_information b', 'b.product_id = a.product_id', 'left');
    $this->db->join('invoice c', 'c.invoice_id = a.invoice_id', 'left');
    $this->db->join('customer_information d', 'd.customer_id = c.customer_id', 'left');
    $this->db->join('stock s', 's.id = a.stock_id', 'left'); // join stock table

    // Filter only products in stocks assigned to the current user
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", NULL, FALSE);

    $this->db->order_by('c.date', 'desc');

    if (!is_null($perpage) && !is_null($page)) {
        $this->db->limit($perpage, $page);
    }

    $query = $this->db->get();
    return ($query->num_rows() > 0) ? $query->result_array() : false;
}

	//RETRIEVE DATE WISE SINGE PRODUCT REPORT
	public function retrieve_product_sales_report_count()
	{
		$this->db->select("a.*,b.product_name,b.product_model,c.date,c.total_amount,d.customer_name");
		$this->db->from('invoice_details a');
		$this->db->join('product_information b','b.product_id = a.product_id');
		$this->db->join('invoice c','c.invoice_id = a.invoice_id');
		$this->db->join('customer_information d','d.customer_id = c.customer_id');
		$this->db->order_by('c.date','desc');
		$query = $this->db->get();	
		return $query->num_rows();
	}
	//RETRIEVE DATE WISE SEARCH SINGLE PRODUCT REPORT
	public function retrieve_product_search_sales_report($start_date, $end_date)
{
    $user_id = $this->session->userdata('user_id'); // current logged-in user
    $dateRange = "c.date BETWEEN '$start_date' AND '$end_date'";

    $this->db->select("
        a.*, 
        b.product_name, 
        b.product_model, 
        c.date, 
		s.stock_name,
        d.customer_name
    ");
    $this->db->from('invoice_details a');
    $this->db->join('product_information b', 'b.product_id = a.product_id', 'left');
    $this->db->join('invoice c', 'c.invoice_id = a.invoice_id', 'left');
    $this->db->join('customer_information d', 'd.customer_id = c.customer_id', 'left');
    $this->db->join('stock s', 's.id = a.stock_id', 'left'); // join stock table

    // Filter by date range
    $this->db->where($dateRange, NULL, FALSE);

    // Filter only products in stocks assigned to the current user
    $this->db->where("s.assign_users LIKE '%\"$user_id\"%'", NULL, FALSE);

    $this->db->order_by('c.date', 'desc');

    $query = $this->db->get();
    return ($query->num_rows() > 0) ? $query->result_array() : false;

    //$this->db->group_by('b.product_model'); // optional grouping if needed
}
	
	//RETRIEVE DATE WISE SEARCH SINGLE PRODUCT REPORT
	public function retrieve_product_search_sales_report_count( $start_date,$end_date )
	{
		$dateRange = "c.date BETWEEN '$start_date%' AND '$end_date%'";
		$this->db->select("a.*,b.product_name,b.product_model,c.date,d.customer_name");
		$this->db->from('invoice_details a');
		$this->db->join('product_information b','b.product_id = a.product_id');
		$this->db->join('invoice c','c.invoice_id = a.invoice_id');
		$this->db->join('customer_information d','d.customer_id = c.customer_id');
		$this->db->where($dateRange, NULL, FALSE); 
		$this->db->order_by('c.date','desc');
		$query = $this->db->get();	
		return $query->num_rows();
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
	//

	// stock report batch wise 

public function stock_report_batch_bydate($perpage,$page){

		
		$this->db->select("b.*,
				sum(b.sell) as 'totalSalesQnty',
				sum(b.Purchase) as 'totalPurchaseQnty',
				b.batch_id
				");
		$this->db->from('view_k_stock_batch_qty b');
		$this->db->group_by('b.batch_id');
		$this->db->group_by('b.product_id');
		$this->db->limit($perpage,$page);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}


	public function getCheckBatchStock($postData=null){
        $CI =& get_instance();
    $user_id = $CI->session->userdata('user_id');

         $response = array();

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
    $searchQuery = " (
        m.product_name LIKE '%".$searchValue."%' OR 
        a.batch_id LIKE '%".$searchValue."%' OR 
        a.expeire_date LIKE '%".$searchValue."%' OR
        s.stock_name LIKE '%".$searchValue."%'
    ) ";
}


         ## Total number of records without filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('product_purchase_details a');
         $this->db->join('stock s', 's.id = a.stock_id','left');
         $this->db->join('product_information m','m.product_id = a.product_id','left');
         $this->db->where("s.assign_users LIKE '%\"$user_id\"%'",null,false);
          if($searchValue != '')
         $this->db->where($searchQuery);
         $this->db->group_by('a.batch_id');
         $this->db->group_by('a.product_id');
         //$records = $this->db->get()->result();
         $totalRecords = $this->db->get()->num_rows();

         ## Total number of record with filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('product_purchase_details a');
         $this->db->join('stock s', 's.id = a.stock_id','left');
         $this->db->join('product_information m','m.product_id = a.product_id','left');
         $this->db->where("s.assign_users LIKE '%\"$user_id\"%'",null,false);
         if($searchValue != '')
         $this->db->where($searchQuery);
         $this->db->group_by('a.batch_id');
         $this->db->group_by('a.product_id');
         //$records = $this->db->get()->result();
         $totalRecordwithFilter = $this->db->get()->num_rows();

         ## Fetch records
         $this->db->select("a.*,
				m.product_name,
				m.strength,
                s.stock_name,
				");
         $this->db->from('product_purchase_details a');
          $this->db->join('stock s', 's.id = a.stock_id','left');
         $this->db->join('product_information m','m.product_id = a.product_id','left');
          $this->db->where("s.assign_users LIKE '%\"$user_id\"%'",null,false);
         if($searchValue != '')
         $this->db->where($searchQuery);
         $this->db->group_by('a.batch_id');
         $this->db->group_by('a.product_id');
         $this->db->order_by($columnName, $columnSortOrder);
         $this->db->limit($rowperpage, $start);
         $records = $this->db->get()->result();
        // echo $this->db->last_query();
         $data = array();
         $sl =1;
         $base_url = base_url();
         foreach($records as $record ){
          $stockout = $this->db->select('sum(quantity) as totalSalesQnty')->from('invoice_details')->where('product_id',$record->product_id)->where('batch_id',$record->batch_id)->get()->row();
		 $stockin = $this->db->select('sum(quantity) as totalPurchaseQnty')->from('product_purchase_details')->where('product_id',$record->product_id)->where('batch_id',$record->batch_id)->get()->row();
          $medicine_name = '<a href="'.$base_url.'Cproduct/product_details/'.$record->product_id.'" class="" data-toggle="tooltip" data-placement="left" >'.$record->product_name.'('.$record->strength.')'.'</a>';
			
               
            $data[] = array( 
            	'sl'               =>   $sl,
                'product_name'     =>  $medicine_name,
                'stock_name'     =>  $record->stock_name,
                'strength'         =>  $record->strength,
				'batch_id'         =>  $record->batch_id,
				'expeire_date'     =>  $record->expeire_date,
				'inqty'            =>  (!empty($stockin->totalPurchaseQnty)?$stockin->totalPurchaseQnty:0),
				'outqty'           =>  (!empty($stockout->totalSalesQnty)?$stockout->totalSalesQnty:0),
				'stock'	           =>  $stockin->totalPurchaseQnty-$stockout->totalSalesQnty,
				
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

	// count batch stock
	 public function stock_report_batch_count(){

		$this->db->select("b.*,
				sum(b.sell) as 'totalSalesQnty',
				sum(b.Purchase) as 'totalPurchaseQnty',
				b.batch_id
				");
		$this->db->from('view_k_stock_batch_qty b');
		$this->db->group_by('b.batch_id');
		 $query = $this->db->get();		
		return $query->num_rows();
	 }

	
	//profit report manufacturer wise purchse
	 public function profit_report_manufacturer($manufacturer_id,$from_date,$to_date){
		$this->db->select("
				AVG(a.rate) as avg_r,
				sum(a.quantity) as quantity
				");
		$this->db->from('product_purchase_details a');
		$this->db->join('product_information b','b.product_id = a.product_id');
		$this->db->join('product_purchase c','c.purchase_id = a.purchase_id');
		$this->db->where('b.manufacturer_id',$manufacturer_id);
		$this->db->where('c.purchase_date >=', $from_date); 
		$this->db->where('c.purchase_date <=', $to_date); 
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}

	//profit report manufacturer wise purchse
	 public function profit_report_manufacturer_sale($manufacturer_id,$from_date,$to_date){
		$this->db->select("
				AVG(a.rate) as avg_r,
				sum(a.quantity) as quantity
				");
		$this->db->from('invoice_details a');
		$this->db->join('product_information b','b.product_id = a.product_id');
		$this->db->join('invoice c','c.invoice_id = a.invoice_id');
		$this->db->where('b.manufacturer_id',$manufacturer_id);
		$this->db->where('c.date >=', $from_date); 
		$this->db->where('c.date <=', $to_date); 
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}

	//profit report manufacturer wise purchse
	 public function profit_report_productwise($product_id,$from_date,$to_date){
		$this->db->select("
				AVG(a.rate) as avg_r,
				sum(a.quantity) as quantity
				");
		$this->db->from('product_purchase_details a');
		$this->db->join('product_purchase c','c.purchase_id = a.purchase_id');
		$this->db->where('a.product_id',$product_id);
		$this->db->where('c.purchase_date >=', $from_date); 
		$this->db->where('c.purchase_date <=', $to_date); 
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}

	//profit report product wise purchse
	 public function profit_report_product_salesss($product_id,$from_date,$to_date){
		$this->db->select("
				AVG(a.rate) as avg_r,
				sum(a.quantity) as quantity
				");
		$this->db->from('invoice_details a');
		$this->db->join('invoice c','a.invoice_id = c.invoice_id');
		$this->db->where('a.product_id',$product_id);
		$this->db->where('c.date >=', $from_date); 
		$this->db->where('c.date <=', $to_date); 
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
		// chart information invoice data
public function inv_jan(){
  $month=1;
  $year=date('Y');
  $this->db->select('COUNT(invoice_id) as invoice_id');
  $this->db->from('invoice');
  $this->db->where(array('MONTH(date)='=>$month,'YEAR(date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	public function inv_feb(){
  $month=2;
  $year=date('Y');
  $this->db->select('COUNT(invoice_id) as invoice_id');
  $this->db->from('invoice');
  $this->db->where(array('MONTH(date)='=>$month,'YEAR(date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	public function inv_mar(){
  $month=3;
  $year=date('Y');
  $this->db->select('COUNT(invoice_id) as invoice_id');
  $this->db->from('invoice');
  $this->db->where(array('MONTH(date)='=>$month,'YEAR(date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
 public function inv_apr(){
  $month=4;
  $year=date('Y');
  $this->db->select('COUNT(invoice_id) as invoice_id');
  $this->db->from('invoice');
  $this->db->where(array('MONTH(date)='=>$month,'YEAR(date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
 public function inv_may(){
  $month=5;
  $year=date('Y');
  $this->db->select('COUNT(invoice_id) as invoice_id');
  $this->db->from('invoice');
  $this->db->where(array('MONTH(date)='=>$month,'YEAR(date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	 public function inv_jun(){
  $month=6;
  $year=date('Y');
  $this->db->select('COUNT(invoice_id) as invoice_id');
  $this->db->from('invoice');
  $this->db->where(array('MONTH(date)='=>$month,'YEAR(date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	 public function inv_jul(){
  $month=7;
  $year=date('Y');
  $this->db->select('COUNT(invoice_id) as invoice_id');
  $this->db->from('invoice');
  $this->db->where(array('MONTH(date)='=>$month,'YEAR(date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	 public function inv_aug(){
  $month=8;
  $year=date('Y');
  $this->db->select('COUNT(invoice_id) as invoice_id');
  $this->db->from('invoice');
  $this->db->where(array('MONTH(date)='=>$month,'YEAR(date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	 public function inv_sep(){
  $month=9;
  $year=date('Y');
  $this->db->select('COUNT(invoice_id) as invoice_id');
  $this->db->from('invoice');
  $this->db->where(array('MONTH(date)='=>$month,'YEAR(date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	 public function inv_oct(){
  $month=10;
  $year=date('Y');
  $this->db->select('COUNT(invoice_id) as invoice_id');
  $this->db->from('invoice');
  $this->db->where(array('MONTH(date)='=>$month,'YEAR(date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	 public function inv_nov(){
  $month=11;
  $year=date('Y');
  $this->db->select('COUNT(invoice_id) as invoice_id');
  $this->db->from('invoice');
  $this->db->where(array('MONTH(date)='=>$month,'YEAR(date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	 public function inv_dec(){
  $month=12;
  $year=date('Y');
  $this->db->select('COUNT(invoice_id) as invoice_id');
  $this->db->from('invoice');
  $this->db->where(array('MONTH(date)='=>$month,'YEAR(date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
//purchase chart data
	public function pur_jan(){
  $month=1;
  $year=date('Y');
  $this->db->select('COUNT(purchase_id) as purchase_id');
  $this->db->from('product_purchase');
  $this->db->where(array('MONTH(purchase_date)='=>$month,'YEAR(purchase_date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	public function pur_feb(){
  $month=2;
  $year=date('Y');
  $this->db->select('COUNT(purchase_id) as purchase_id');
  $this->db->from('product_purchase');
  $this->db->where(array('MONTH(purchase_date)='=>$month,'YEAR(purchase_date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	public function pur_mar(){
  $month=3;
  $year=date('Y');
  $this->db->select('COUNT(purchase_id) as purchase_id');
  $this->db->from('product_purchase');
  $this->db->where(array('MONTH(purchase_date)='=>$month,'YEAR(purchase_date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
 public function pur_apr(){
  $month=4;
  $year=date('Y');
  $this->db->select('COUNT(purchase_id) as purchase_id');
  $this->db->from('product_purchase');
  $this->db->where(array('MONTH(purchase_date)='=>$month,'YEAR(purchase_date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
 public function pur_may(){
  $month=5;
  $year=date('Y');
  $this->db->select('COUNT(purchase_id) as purchase_id');
  $this->db->from('product_purchase');
  $this->db->where(array('MONTH(purchase_date)='=>$month,'YEAR(purchase_date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	 public function pur_jun(){
  $month=6;
  $year=date('Y');
  $this->db->select('COUNT(purchase_id) as purchase_id');
  $this->db->from('product_purchase');
  $this->db->where(array('MONTH(purchase_date)='=>$month,'YEAR(purchase_date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	 public function pur_jul(){
  $month=7;
  $year=date('Y');
  $this->db->select('COUNT(purchase_id) as purchase_id');
  $this->db->from('product_purchase');
  $this->db->where(array('MONTH(purchase_date)='=>$month,'YEAR(purchase_date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	 public function pur_aug(){
  $month=8;
  $year=date('Y');
  $this->db->select('COUNT(purchase_id) as purchase_id');
  $this->db->from('product_purchase');
  $this->db->where(array('MONTH(purchase_date)='=>$month,'YEAR(purchase_date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	 public function pur_sep(){
  $month=9;
  $year=date('Y');
  $this->db->select('COUNT(purchase_id) as purchase_id');
  $this->db->from('product_purchase');
  $this->db->where(array('MONTH(purchase_date)='=>$month,'YEAR(purchase_date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	 public function pur_oct(){
  $month=10;
  $year=date('Y');
  $this->db->select('COUNT(purchase_id) as purchase_id');
  $this->db->from('product_purchase');
  $this->db->where(array('MONTH(purchase_date)='=>$month,'YEAR(purchase_date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	 public function pur_nov(){
  $month=11;
  $year=date('Y');
  $this->db->select('COUNT(purchase_id) as purchase_id');
  $this->db->from('product_purchase');
  $this->db->where(array('MONTH(purchase_date)='=>$month,'YEAR(purchase_date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
	 public function pur_dec(){
  $month=12;
  $year=date('Y');
  $this->db->select('COUNT(purchase_id) as purchase_id');
  $this->db->from('product_purchase');
  $this->db->where(array('MONTH(purchase_date)='=>$month,'YEAR(purchase_date)='=>$year));
  $query = $this->db->get()->result_array();
  return $query;

	}
   public function get_shortlisted_products_report() {
    // Query to get the company details, count of shortlisted candidates (status = 3),
    // and the total number of jobs posted by each company
    $this->db->select('company.id, company.company_name, 
                       COUNT(DISTINCT candidate_report.candidate_id) AS total_shortlisted, 
                       COUNT(DISTINCT jobs.id) AS total_jobs_posted'); // Adding job count
    $this->db->from('company'); // Assuming the company table is called 'company'
    
    // Join with the candidate_report to get shortlisted candidates
    $this->db->join('candidate_report', 'company.id = candidate_report.company_id', 'left');
    
    // Join with the jobs table to get posted jobs
    $this->db->join('jobs', 'company.id = jobs.company_id', 'left');
    
    // Filter for shortlisted candidates (status = 3)
    $this->db->where('candidate_report.status', 3); // Filter for shortlisted candidates

    // Group by company to get the count per company
    $this->db->group_by('company.id'); 

    // Execute the query
    $query = $this->db->get();

    // Check if data is foundf
    if ($query->num_rows() > 0) {
        // Return data as an array of results
        return $query->result_array();
    } else {
        // Return an empty array if no results
        return [];
    }
}

//interviewed
public function get_interviwed_products_report() {
    // Query to get the company details, count of shortlisted candidates (status = 3),
    // and the total number of jobs posted by each company
    $this->db->select('company.id, company.company_name, 
                       COUNT(DISTINCT candidate_report.candidate_id) AS total_interviewed, 
                       COUNT(DISTINCT jobs.id) AS total_jobs_posted'); // Adding job count
    $this->db->from('company'); // Assuming the company table is called 'company'
    
    // Join with the candidate_report to get shortlisted candidates
    $this->db->join('candidate_report', 'company.id = candidate_report.company_id', 'left');
    
    // Join with the jobs table to get posted jobs
    $this->db->join('jobs', 'company.id = jobs.company_id', 'left');
    
    // Filter for shortlisted candidates (status = 3)
    $this->db->where('candidate_report.status', 4); // Filter for shortlisted candidates

    // Group by company to get the count per company
    $this->db->group_by('company.id'); 

    // Execute the query
    $query = $this->db->get();

    // Check if data is foundf
    if ($query->num_rows() > 0) {
        // Return data as an array of results
        return $query->result_array();
    } else {
        // Return an empty array if no results
        return [];
    }
}

//
public function get_hired_products_report() {
    // Query to get the company details, count of hired candidates (status = 5),
    // and the total number of jobs posted by each company
    $this->db->select('company.id, company.company_name, 
                       COUNT(DISTINCT candidate_report.candidate_id) AS total_hired, 
                       COUNT(DISTINCT jobs.id) AS total_jobs_posted'); // Adding job count
    $this->db->from('company'); // Assuming the company table is called 'company'
    
    // Join with the candidate_report to get hired candidates
    $this->db->join('candidate_report', 'company.id = candidate_report.company_id', 'left');
    
    // Join with the jobs table to get posted jobs
    $this->db->join('jobs', 'company.id = jobs.company_id', 'left');
    
    // Filter for hired candidates (status = 5)
    $this->db->where('candidate_report.status', 5); // Filter for hired candidates

    // Group by company to get the count per company
    $this->db->group_by('company.id'); 

    // Execute the query
    $query = $this->db->get();

    // Check if data is found
    if ($query->num_rows() > 0) {
        // Return data as an array of results
        return $query->result_array();
    } else {
        // Return an empty array if no results
        return [];
    }
}

//rejected
// Model Method for fetching rejected candidates report
public function get_rejected_products_report() {
    // Query to get the company details, count of rejected candidates (status = 6),
    // and the total number of jobs posted by each company
    $this->db->select('company.id, company.company_name, 
                       COUNT(DISTINCT candidate_report.candidate_id) AS total_rejected, 
                       COUNT(DISTINCT jobs.id) AS total_jobs_posted'); // Adding job count
    $this->db->from('company'); // Assuming the company table is called 'company'
    
    // Join with the candidate_report to get rejected candidates
    $this->db->join('candidate_report', 'company.id = candidate_report.company_id', 'left');
    
    // Join with the jobs table to get posted jobs
    $this->db->join('jobs', 'company.id = jobs.company_id', 'left');
    
    // Filter for rejected candidates (status = 6)
    $this->db->where('candidate_report.status', 6); // Status for rejected candidates

    // Group by company to get the count per company
    $this->db->group_by('company.id'); 

    // Execute the query
    $query = $this->db->get();

    // Check if data is found
    if ($query->num_rows() > 0) {
        // Return data as an array of results
        return $query->result_array();
    } else {
        // Return an empty array if no results
        return [];
    }
}
public function total_short_list() {
        $this->db->where('status', 3); // Filter for shortlisted candidates (status = 3)
        $query = $this->db->get('candidate_report'); // Query the candidate_report table
        return $query->num_rows(); // Return the total count
    }

    // Method to get the total interviewed candidates (status = 4)
    public function total_interview_list() {
        $this->db->where('status', 4); // Filter for interviewed candidates (status = 4)
        $query = $this->db->get('candidate_report');
        return $query->num_rows(); // Return the total count
    }

    // Method to get the total hired candidates (status = 5)
    public function total_hired_list() {
        $this->db->where('status', 5); // Filter for hired candidates (status = 5)
        $query = $this->db->get('candidate_report');
        return $query->num_rows(); // Return the total count
    }

    // Method to get the total rejected candidates (status = 6)
    public function total_rejected_list() {
        $this->db->where('status', 6); // Filter for rejected candidates (status = 6)
        $query = $this->db->get('candidate_report');
        return $query->num_rows(); // Return the total count
    }

    // Get jobs posted by a company
    public function get_jobs_by_company($company_id) {
        $this->db->select('id, job_title');
        $this->db->from('jobs');
        $this->db->where('company_id', $company_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get shortlisted candidates by company and optionally by job
    public function get_shortlisted_by_company($company_id, $job_id = null) {
        $this->db->select('c.*, cr.status as application_status, j.job_title');
        $this->db->from('candidate_report cr');
        $this->db->join('candidates c', 'c.id = cr.candidate_id', 'left');
        $this->db->join('jobs j', 'j.id = cr.job_id', 'left');
        $this->db->where('cr.company_id', $company_id);
        $this->db->where('cr.status', 3); // Shortlisted
        if ($job_id) {
            $this->db->where('cr.job_id', $job_id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get interviewed candidates by company and optionally by job
    public function get_interviewed_by_company($company_id, $job_id = null) {
        $this->db->select('c.*, cr.status as application_status, j.job_title');
        $this->db->from('candidate_report cr');
        $this->db->join('candidates c', 'c.id = cr.candidate_id', 'left');
        $this->db->join('jobs j', 'j.id = cr.job_id', 'left');
        $this->db->where('cr.company_id', $company_id);
        $this->db->where('cr.status', 4); // Interviewed
        if ($job_id) {
            $this->db->where('cr.job_id', $job_id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get hired candidates by company and optionally by job
    public function get_hired_by_company($company_id, $job_id = null) {
        $this->db->select('c.*, cr.status as application_status, j.job_title');
        $this->db->from('candidate_report cr');
        $this->db->join('candidates c', 'c.id = cr.candidate_id', 'left');
        $this->db->join('jobs j', 'j.id = cr.job_id', 'left');
        $this->db->where('cr.company_id', $company_id);
        $this->db->where('cr.status', 5); // Hired
        if ($job_id) {
            $this->db->where('cr.job_id', $job_id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get rejected candidates by company and optionally by job
    public function get_rejected_by_company($company_id, $job_id = null) {
        $this->db->select('c.*, cr.status as application_status, j.job_title');
        $this->db->from('candidate_report cr');
        $this->db->join('candidates c', 'c.id = cr.candidate_id', 'left');
        $this->db->join('jobs j', 'j.id = cr.job_id', 'left');
        $this->db->where('cr.company_id', $company_id);
        $this->db->where('cr.status', 6); // Rejected
        if ($job_id) {
            $this->db->where('cr.job_id', $job_id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get applied candidates (all candidates who applied, status = 1 or 2)
    public function get_applied_products_report() {
        $this->db->select('company.id, company.company_name, 
                           COUNT(DISTINCT candidate_report.candidate_id) AS total_applied, 
                           COUNT(DISTINCT jobs.id) AS total_jobs_posted');
        $this->db->from('company');
        $this->db->join('candidate_report', 'company.id = candidate_report.company_id', 'left');
        $this->db->join('jobs', 'company.id = jobs.company_id', 'left');
        $this->db->where_in('candidate_report.status', [1, 2]); // Applied (pending or screening)
        $this->db->group_by('company.id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }

    // Get applied candidates by company and optionally by job
    public function get_applied_by_company($company_id, $job_id = null) {
        $this->db->select('c.*, cr.status as application_status, j.job_title');
        $this->db->from('candidate_report cr');
        $this->db->join('candidates c', 'c.id = cr.candidate_id', 'left');
        $this->db->join('jobs j', 'j.id = cr.job_id', 'left');
        $this->db->where('cr.company_id', $company_id);
        $this->db->where_in('cr.status', [1, 2]); // Applied (pending or screening)
        if ($job_id) {
            $this->db->where('cr.job_id', $job_id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }


}
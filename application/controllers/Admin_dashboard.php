<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_dashboard extends CI_Controller {
	
	function __construct() {
      	parent::__construct();
	  	$this->template->current_menu = 'home';
	  	$this->load->model('Web_settings');
    }

    public function index()
{
    $CI =& get_instance();
    $CI->load->library('lreport');
    $CI->load->library('occational');

    if (!$this->auth->is_logged()) {
        $this->output->set_header("Location: ".base_url().'Admin_dashboard/login', TRUE, 302);
        return;
    }
    $this->auth->check_admin_auth();

    // Load models
    $CI->load->model('Customers');
    $CI->load->model('Products');
    $CI->load->model('Manufacturers');
    $CI->load->model('Invoices');
    $CI->load->model('Purchases');
    $CI->load->model('Company_model');
    $CI->load->model('Candidate_model');
    $CI->load->model('Reports');
    $CI->load->model('Web_settings');

    // Dashboard data
    $total_employer = $CI->Company_model->count_employer();
    $total_candidate = $CI->Candidate_model->count_candidate();
    //$total_employers      = $CI->Customers->count_customer();
    $total_product       = $CI->Products->count_product();
    // report
    $total_short_list       = $CI->Reports->total_short_list();
    $total_interview_list       = $CI->Reports->total_interview_list();
    $total_hired_list       = $CI->Reports->total_hired_list();
    $total_rejected_list       = $CI->Reports->total_rejected_list();
    //report
    $total_manufacturers = $CI->Manufacturers->count_manufacturer();
    $total_sales         = $CI->Invoices->count_invoice();
    $total_purchase      = $CI->Reports->todays_total_purchase();
    $out_of_stock        = $CI->Reports->out_of_stock_count();
    $out_of_date         = $CI->Reports->out_of_date_count();
    $sales_report        = $CI->Reports->todays_total_sales_report();
    $currency_details    = $CI->Web_settings->retrieve_setting_editdata();

    // **Dashboard totals**
    $dashboard_totals = $CI->Reports->dashboard_totals();

    $data = [
        'title'                 => display('dashboard'),
        'total_customer'        => $total_customer,
        'total_product'         => $total_product,
        'total_manufacturers'   => $total_manufacturers,
    'total_short_list'=>$total_short_list,
    'total_interview_list'=>$total_interview_list,
    'total_hired_list'=>$total_hired_list,
    'total_rejected_list'=>$total_rejected_list,
        'total_sales'           => $total_sales,
        'total_candidate'       => $total_candidate,
        'total_employer'           => $total_employer,
        'total_purchase'        => $total_purchase,
        'stockout'              => (!empty($out_of_stock) ? $out_of_stock : 0),
        'expired'               => (!empty($out_of_date) ? $out_of_date : 0),
        'purchase_amount'       => number_format($total_purchase[0]['total_purchase'], 2, '.', ','),
        'sales_amount'          => number_format($sales_report[0]['total_sale'], 2, '.', ','),
        'currency'              => $currency_details[0]['currency'],
        'position'              => $currency_details[0]['currency_position'],
        'dashboard_purchase'    => number_format($dashboard_totals['dashboard_purchase'], 2, '.', ','),
        'dashboard_sales'       => number_format($dashboard_totals['dashboard_sales'], 2, '.', ','),
        'dashboard_expense'     => number_format($dashboard_totals['dashboard_expense'], 2, '.', ','),
        'dashboard_salary'      => number_format($dashboard_totals['dashboard_salary'], 2, '.', ','),
        'dashboard_paid_total'  => number_format($dashboard_totals['dashboard_paid_total'], 2, '.', ','),
        'dashboard_net_profit'  => number_format($dashboard_totals['dashboard_net_profit'], 2, '.', ','),
        'dashboard_paid_profit' => number_format($dashboard_totals['dashboard_paid_profit'], 2, '.', ','),
        // Monthly sales and purchase
        'inv_jan' => $CI->Reports->inv_jan(),
        'inv_feb' => $CI->Reports->inv_feb(),
        'inv_mar' => $CI->Reports->inv_mar(),
        'inv_apr' => $CI->Reports->inv_apr(),
        'inv_may' => $CI->Reports->inv_may(),
        'inv_jun' => $CI->Reports->inv_jun(),
        'inv_jul' => $CI->Reports->inv_jul(),
        'inv_aug' => $CI->Reports->inv_aug(),
        'inv_sep' => $CI->Reports->inv_sep(),
        'inv_oct' => $CI->Reports->inv_oct(),
        'inv_nov' => $CI->Reports->inv_nov(),
        'inv_dec' => $CI->Reports->inv_dec(),
        'pur_jan' => $CI->Reports->pur_jan(),
        'pur_feb' => $CI->Reports->pur_feb(),
        'pur_mar' => $CI->Reports->pur_mar(),
        'pur_apr' => $CI->Reports->pur_apr(),
        'pur_may' => $CI->Reports->pur_may(),
        'pur_jun' => $CI->Reports->pur_jun(),
        'pur_jul' => $CI->Reports->pur_jul(),
        'pur_aug' => $CI->Reports->pur_aug(),
        'pur_sep' => $CI->Reports->pur_sep(),
        'pur_oct' => $CI->Reports->pur_oct(),
        'pur_nov' => $CI->Reports->pur_nov(),
        'pur_dec' => $CI->Reports->pur_dec(),
    ];

    $content = $CI->parser->parse('include/admin_home', $data, true);
    $this->template->full_admin_html_view($content);
}

    //Today All Report
	public function all_report()
	{
		$CI =& get_instance();
		$CI->load->library('lreport');
		$content = $CI->lreport->retrieve_all_reports();
		$this->template->full_admin_html_view($content);
	}

	public function grand_profit() {
     $CI =& get_instance();
    $CI->load->library('lreport');
    $CI->load->library('occational');

    if (!$this->auth->is_logged()) {
        $this->output->set_header("Location: ".base_url().'Admin_dashboard/login', TRUE, 302);
        return;
    }
    $this->auth->check_admin_auth();

    // Load models
    $CI->load->model('Customers');
    $CI->load->model('Products');
    $CI->load->model('Manufacturers');
    $CI->load->model('Invoices');
    $CI->load->model('Purchases');
    $CI->load->model('Reports');
    $CI->load->model('Web_settings');

    // Dashboard data
    $total_customer      = $CI->Customers->count_customer();
    $total_product       = $CI->Products->count_product();
    $total_manufacturers = $CI->Manufacturers->count_manufacturer();
    $total_sales         = $CI->Invoices->count_invoice();
    $total_purchase      = $CI->Reports->todays_total_purchase();
    $out_of_stock        = $CI->Reports->out_of_stock_count();
    $out_of_date         = $CI->Reports->out_of_date_count();
    $sales_report        = $CI->Reports->todays_total_sales_report();
    $currency_details    = $CI->Web_settings->retrieve_setting_editdata();

    // **Dashboard totals**
    $dashboard_totals = $CI->Reports->dashboard_totals();

    $data = [
        'title'                 => display('dashboard'),
        'total_customer'        => $total_customer,
        'total_product'         => $total_product,
        'total_manufacturers'   => $total_manufacturers,
        'total_sales'           => $total_sales,
        'total_purchase'        => $total_purchase,
        'stockout'              => (!empty($out_of_stock) ? $out_of_stock : 0),
        'expired'               => (!empty($out_of_date) ? $out_of_date : 0),
        'purchase_amount'       => number_format($total_purchase[0]['total_purchase'], 2, '.', ','),
        'sales_amount'          => number_format($sales_report[0]['total_sale'], 2, '.', ','),
        'currency'              => $currency_details[0]['currency'],
        'position'              => $currency_details[0]['currency_position'],
        'dashboard_purchase'    => number_format($dashboard_totals['dashboard_purchase'], 2, '.', ','),
        'dashboard_sales'       => number_format($dashboard_totals['dashboard_sales'], 2, '.', ','),
        'dashboard_expense'     => number_format($dashboard_totals['dashboard_expense'], 2, '.', ','),
        'dashboard_salary'      => number_format($dashboard_totals['dashboard_salary'], 2, '.', ','),
        'dashboard_paid_total'  => number_format($dashboard_totals['dashboard_paid_total'], 2, '.', ','),
        'dashboard_net_profit'  => number_format($dashboard_totals['dashboard_net_profit'], 2, '.', ','),
        'dashboard_paid_profit' => number_format($dashboard_totals['dashboard_paid_profit'], 2, '.', ','),
        // Monthly sales and purchase
        'inv_jan' => $CI->Reports->inv_jan(),
        'inv_feb' => $CI->Reports->inv_feb(),
        'inv_mar' => $CI->Reports->inv_mar(),
        'inv_apr' => $CI->Reports->inv_apr(),
        'inv_may' => $CI->Reports->inv_may(),
        'inv_jun' => $CI->Reports->inv_jun(),
        'inv_jul' => $CI->Reports->inv_jul(),
        'inv_aug' => $CI->Reports->inv_aug(),
        'inv_sep' => $CI->Reports->inv_sep(),
        'inv_oct' => $CI->Reports->inv_oct(),
        'inv_nov' => $CI->Reports->inv_nov(),
        'inv_dec' => $CI->Reports->inv_dec(),
        'pur_jan' => $CI->Reports->pur_jan(),
        'pur_feb' => $CI->Reports->pur_feb(),
        'pur_mar' => $CI->Reports->pur_mar(),
        'pur_apr' => $CI->Reports->pur_apr(),
        'pur_may' => $CI->Reports->pur_may(),
        'pur_jun' => $CI->Reports->pur_jun(),
        'pur_jul' => $CI->Reports->pur_jul(),
        'pur_aug' => $CI->Reports->pur_aug(),
        'pur_sep' => $CI->Reports->pur_sep(),
        'pur_oct' => $CI->Reports->pur_oct(),
        'pur_nov' => $CI->Reports->pur_nov(),
        'pur_dec' => $CI->Reports->pur_dec(),
    ];

    $content = $CI->parser->parse('report/grand_profit_view', $data, true);
    $this->template->full_admin_html_view($content);
}


	#==============Todays_sales_report============#
	public function todays_sales_report()
	{
		$CI =& get_instance();
		$CI->load->library('lreport');
		$this->auth->check_admin_auth();
		$content = $CI->lreport->todays_sales_report();
		$this->template->full_admin_html_view($content);
	}
	#================todays_purchase_report========#
	public function todays_purchase_report()
	{
		$CI =& get_instance();
		$CI->load->library('lreport');
		$this->auth->check_admin_auth();
		$content = $CI->lreport->todays_purchase_report();
		$this->template->full_admin_html_view($content);
	}
	#=============Total profit report===================#
	public function total_profit_report(){
		if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
		$CI =& get_instance();
		$CI->load->library('lreport');
		$CI->load->model('Reports');
		$this->auth->check_admin_auth();
		#
        #pagination starts
        #
        $config["base_url"] = base_url('Admin_dashboard/total_profit_report/');
        $config["total_rows"] = $this->Reports->total_profit_report_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
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
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $links = $this->pagination->create_links();
        #
        #pagination ends
        #  
        $content =$this->lreport->total_profit_report($links,$config["per_page"],$page);

		$this->template->full_admin_html_view($content);
	}
	#==============Date wise profit report=============#
	public function retrieve_dateWise_profit_report()
	{
		if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreport');
		$start_date = $this->input->post('from_date');		
		$end_date = $this->input->post('to_date');	
        $content = $CI->lreport->retrieve_dateWise_profit_report($start_date,$end_date);
		$this->template->full_admin_html_view($content);
	}
	#============Date wise sales report==============#
	public function retrieve_dateWise_SalesReports()
	{
		if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreport');
		$from_date = $this->input->post('from_date');		
		$to_date = $this->input->post('to_date');	
        $content = $CI->lreport->retrieve_dateWise_SalesReports($from_date,$to_date);
		$this->template->full_admin_html_view($content);
	}	
	#==============Date wise purchase report=============#
	public function retrieve_dateWise_PurchaseReports()
	{
		if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreport');
		$start_date = $this->input->post('from_date');		
		$end_date = $this->input->post('to_date');	
        $content = $CI->lreport->retrieve_dateWise_PurchaseReports($start_date,$end_date);
		$this->template->full_admin_html_view($content);
	}
	#==============Product sales report date wise===========#
	public function product_sales_reports_date_wise()
	 {
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreport');	
		$CI->load->model('Reports');
		#
        #pagination starts
        #
        $config["base_url"] = base_url('Admin_dashboard/product_sales_reports_date_wise/');
        $config["total_rows"] = $this->Reports->retrieve_product_sales_report_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
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
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $links = $this->pagination->create_links();
        #
        #pagination ends
        #  
        $content =$this->lreport->get_products_report_sales_view($links,$config["per_page"],$page);

		$this->template->full_admin_html_view($content);
	}
	#==============Product sales search reports============#
	public function product_sales_search_reports()
	{
		if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreport');
		$from_date = $this->input->get('from_date');		
		$to_date = $this->input->get('to_date');	
        $content = $CI->lreport->get_products_search_report($from_date,$to_date);
		$this->template->full_admin_html_view($content);
	}
	#============User login=========#
	public function login()
	{	
		if ($this->auth->is_logged() )
		{
			$this->output->set_header("Location: ".base_url().'Admin_dashboard', TRUE, 302);
			return;
		}
		$data['title'] = display('admin_login_area');
        $content = $this->parser->parse('user/admin_login_form',$data,true);
		$this->template->full_admin_html_view($content);
	}
	#==============Valid user check=======#
	public function do_login(){

		$error = '';
		$setting_detail = $this->Web_settings->retrieve_setting_editdata(); 

		if ($setting_detail[0]['captcha'] == 0 && $setting_detail[0]['secret_key'] != null && $setting_detail[0]['site_key'] != null) {

			$this->form_validation->set_rules('g-recaptcha-response', 'recaptcha validation', 'required|callback_validate_captcha');
			$this->form_validation->set_message('validate_captcha', 'Please check the the captcha form');

			if ($this->form_validation->run() == FALSE){
				$this->session->set_userdata(array('error_message'=>display('please_enter_valid_captcha')));
				$this->output->set_header("Location: ".base_url().'Admin_dashboard/login', TRUE, 302);
			}
			else{
				$username = $this->input->post('username');
				$password = $this->input->post('password');
				if ( $username == '' || $password == '' || $this->auth->login($username, $password) === FALSE ){
					$error = display('wrong_username_or_password');
				}
				if ( $error != '' ){
					$this->session->set_userdata(array('error_message'=>$error));
					$this->output->set_header("Location: ".base_url().'Admin_dashboard/login', TRUE, 302);
				}
				else{
					$this->output->set_header("Location: ".base_url().'Admin_dashboard', TRUE, 302);
		        }
			}
		}
		else{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			if ( $username == '' || $password == '' || $this->auth->login($username, $password) === FALSE ){
				$error = display('wrong_username_or_password');
			}
			if ( $error != '' ){
				$this->session->set_userdata(array('error_message'=>$error));
				$this->output->set_header("Location: ".base_url().'Admin_dashboard/login', TRUE, 302);
			}else{
				$this->output->set_header("Location: ".base_url().'Admin_dashboard', TRUE, 302);
	        }
		}
	}
	public function expense_report() {
    $CI =& get_instance();
    $CI->load->library('lreport');
    $content = $CI->lreport->expense_report(); // let lreport handle view and data
    $this->template->full_admin_html_view($content);
    //$content = $CI->lreport->expense_report(); // <-- this line here does nothing after full_admin_html_view()
}



	//Valid captcha check
	function validate_captcha() { 
	  	$captcha = $this->input->post('g-recaptcha-response'); 
	  	// $response = file_get_contents("//www.google.com/recaptcha/api/siteverify?secret=6LdiKhsUAAAAABH4BQCIvBar7Oqe-2LwDKxMSX-t&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']); 

		$url = "www.google.com/recaptcha/api/siteverify?secret=6LdiKhsUAAAAABH4BQCIvBar7Oqe-2LwDKxMSX-t&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR'];
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		$contents = curl_exec($ch);
		if (curl_errno($ch)) {
		  echo curl_error($ch);
		  echo "\n<br />";
		  $contents = '';
		} else {
		  curl_close($ch);
		}
	 	if ($contents . 'success' == false) { return FALSE; } else { return TRUE; } 
	}

	#===============Logout=======#
	public function logout()
	{	
		if ($this->auth->logout())
		$this->output->set_header("Location: ".base_url().'Admin_dashboard/login', TRUE, 302);
	}
	#=============Edit Profile======#
	public function edit_profile()
	{	
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('luser');
		$content = $CI->luser->edit_profile_form();
		$this->template->full_admin_html_view($content);
	}
	#=============Update Profile========#
	public function update_profile()
	{	
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Users');
		$this->Users->profile_update();
		$this->session->set_userdata(array('message'=> display('successfully_updated')));
		redirect(base_url('Admin_dashboard/edit_profile'));
	}
	#=============Change Password=========# 
	public function change_password_form()
	{	
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$content = $CI->parser->parse('user/change_password',array('title'=>display('change_password')),true);
		$this->template->full_admin_html_view($content);
	}
	#============Change Password===========#
	public function change_password()
	{	
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Users'); 

		$error = '';
		$email = $this->input->post('email');
		$old_password = $this->input->post('old_password');
		$new_password = $this->input->post('password');
		$repassword = $this->input->post('repassword');
		if ( $email == '' || $old_password == '' || $new_password == '')
		{
			$error = display('blank_field_does_not_accept');
		}else if($email != $this->session->userdata('user_email')){
			$error = display('you_put_wrong_email_address');
		}else if(strlen($new_password)<6 ){
			$error = display('new_password_at_least_six_character');
		}else if($new_password != $repassword ){
			$error = display('password_and_repassword_does_not_match');
		}else if($CI->Users->change_password($email,$old_password,$new_password) === FALSE ){
			$error = display('you_are_not_authorised_person');
		}
		if ( $error != '' )
		{
			$this->session->set_userdata(array('error_message'=>$error));
			$this->output->set_header("Location: ".base_url().'Admin_dashboard/change_password_form', TRUE, 302);
		}else{
			$this->session->set_userdata(array('message'=>display('successfully_changed_password')));
			$this->output->set_header("Location: ".base_url().'Admin_dashboard/change_password_form', TRUE, 302);
        }
	}
	 public function profit_manufacturer_form(){
        $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');
        $content = $CI->lreport->profit_report_manufacturer_form();
        $this->template->full_admin_html_view($content);
        

    } 
	// profit report manufacturer wise
        public function profit_manufacturer(){
        $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');
        $manufacturer_id=$this->input->post('manufacturer_id');
        $from_date=$this->input->post('from_date');
        $to_date=$this->input->post('to_date');
        $content = $CI->lreport->profit_report_manufacturer($manufacturer_id,$from_date,$to_date);
        $this->template->full_admin_html_view($content);
        

    } 
// product wise profit report form
     public function profit_productwise_form(){
        $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');
        $content = $CI->lreport->profit_productwise_form();
        $this->template->full_admin_html_view($content);
        

    } 
	// profit report manufacturer wise
       public function profit_productwise(){
        $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');
        $product_id=$this->input->post('product_id');
        $from_date=$this->input->post('from_date');
        $to_date=$this->input->post('to_date');
        $content = $CI->lreport->profit_productwise($product_id,$from_date,$to_date);
        $this->template->full_admin_html_view($content);
        

    } 
    public function shortlisted_report(){
        $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');
        $content = $CI->lreport->shortlisted_report();
        $this->template->full_admin_html_view($content);
    }
    public function interviewed_report(){
        $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');
        $content = $CI->lreport->interviewed_report();
        $this->template->full_admin_html_view($content);

    }
     public function hired_report(){
        $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');
        $content = $CI->lreport->hired_report();
        $this->template->full_admin_html_view($content);
    }

    // Export all candidates for a given status across all companies
    public function export_report_all($type = 'hired') {
        $this->auth->check_admin_auth();
        $this->load->model('Reports');

        $statusMap = [
            'hired'       => [5, 'Hired'],
            'shortlisted' => [3, 'Shortlisted'],
            'interviewed' => [4, 'Interviewed'],
            'rejected'    => [6, 'Rejected'],
            'applied'     => [2, 'Applied'],
        ];

        if (!isset($statusMap[$type])) { show_404(); return; }
        list($statusCode, $label) = $statusMap[$type];

        // Get all candidates with this status across all companies - ALL FIELDS
        $this->db->select('
            c.seeker_id, c.full_name, c.sex, c.martial_status, c.dob_ethiopian, c.age,
            c.total_family_size, c.hh_male, c.hh_female, c.household_type,
            c.disability_status, c.disability_male, c.disability_female,
            c.phone_number, c.email, c.location_text, c.woreda, c.tabia,
            e.level AS education_level, f.field AS field_of_study,
            c.gpa, c.qualification_skills, c.graduated_year, c.experience,
            c.resume, c.created_at,
            co.company_name, j.job_title, cr.status
        ');
        $this->db->from('candidate_report cr');
        $this->db->join('candidates c', 'c.id = cr.candidate_id', 'left');
        $this->db->join('educational_level e', 'e.id = c.education_level', 'left');
        $this->db->join('field_of_study f', 'f.id = c.field_of_study', 'left');
        $this->db->join('company co', 'co.id = cr.company_id', 'left');
        $this->db->join('jobs j', 'j.id = cr.job_id', 'left');
        $this->db->where('cr.status', $statusCode);
        $this->db->order_by('co.company_name', 'ASC');
        $candidates = $this->db->get()->result_array();

        // Build xlsx with ALL candidate fields
        $rows = [[
            'SL', 'Seeker ID', 'Full Name', 'Sex', 'Martial Status', 'DOB (Ethiopian)', 'Age',
            'Family Size', 'HH Male', 'HH Female', 'Household Type', 'Disability Status',
            'Disability Male', 'Disability Female', 'Phone', 'Email', 'Location',
            'Woreda', 'Tabia', 'Education Level', 'Field of Study', 'GPA', 'Qualification/Skills',
            'Graduated Year', 'Experience', 'Resume', 'Created At', 'Company', 'Job Title', 'Status'
        ]];
        $sl = 1;
        foreach ($candidates as $c) {
            $rows[] = [
                $sl++, $c['seeker_id'], $c['full_name'], $c['sex'],
                $c['martial_status'] ?? 'Single', $c['dob_ethiopian'] ?? '', $c['age'] ?? '',
                $c['total_family_size'] ?? '', $c['hh_male'] ?? '', $c['hh_female'] ?? '',
                $c['household_type'] ?? '', $c['disability_status'] ?? '',
                $c['disability_male'] ?? '', $c['disability_female'] ?? '',
                $c['phone_number'], $c['email'] ?? '', $c['location_text'] ?? '',
                $c['woreda'] ?? '', $c['tabia'] ?? '',
                $c['education_level'] ?? '', $c['field_of_study'] ?? '',
                $c['gpa'] ?? '', $c['qualification_skills'] ?? '',
                $c['graduated_year'] ?? '', $c['experience'] ?? '',
                !empty($c['resume']) ? 'Yes' : 'No', $c['created_at'],
                $c['company_name'] ?? '', $c['job_title'] ?? '', $label
            ];
        }

        $tmp = $this->_build_xlsx($rows);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $type . '_report_all_' . date('Y-m-d') . '.xlsx');
        header('Content-Length: ' . filesize($tmp));
        header('Cache-Control: max-age=0');
        readfile($tmp);
        unlink($tmp);
        exit;
    }

    // Pure PHP xlsx builder (no dependencies)
    private function _build_xlsx($rows) {
        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';
        foreach ($rows as $ri => $row) {
            $sheetXml .= '<row r="' . ($ri + 1) . '">';
            foreach ($row as $ci => $val) {
                $col = ''; $n = $ci;
                do { $col = chr(65 + ($n % 26)) . $col; $n = intval($n / 26) - 1; } while ($n >= 0);
                $cell = $col . ($ri + 1);
                $safe = htmlspecialchars((string)$val, ENT_XML1, 'UTF-8');
                $sheetXml .= '<c r="' . $cell . '" t="inlineStr"><is><t>' . $safe . '</t></is></c>';
            }
            $sheetXml .= '</row>';
        }
        $sheetXml .= '</sheetData></worksheet>';
        $rels = '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>';
        $workbook = '<?xml version="1.0" encoding="UTF-8"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Sheet1" sheetId="1" r:id="rId1"/></sheets></workbook>';
        $ct = '<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>';
        $pkgRels = '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>';
        $tmp = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip = new ZipArchive();
        $zip->open($tmp, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', $ct);
        $zip->addFromString('_rels/.rels', $pkgRels);
        $zip->addFromString('xl/workbook.xml', $workbook);
        $zip->addFromString('xl/_rels/workbook.xml.rels', $rels);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
        $zip->close();
        return $tmp;
    }

     public function rejected_report(){
        $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');
        $content = $CI->lreport->rejected_report();
        $this->template->full_admin_html_view($content);

    }
    
    // Applied Report
    public function applied_report(){
        $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');
        $content = $CI->lreport->applied_report();
        $this->template->full_admin_html_view($content);
    }
    
    // Shortlisted candidates by company and job
    public function shortlisted_by_company($company_id, $job_id = null) {
        $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Reports');
        
        $data['company_id'] = $company_id;
        $data['job_id'] = $job_id;
        $data['jobs'] = $CI->Reports->get_jobs_by_company($company_id);
        $data['candidates'] = $CI->Reports->get_shortlisted_by_company($company_id, $job_id);
        $data['title'] = 'Shortlisted Candidates';
        $data['report_type'] = 'shortlisted';
        
        $content = $CI->parser->parse('report/candidate_detail_report', $data, true);
        $this->template->full_admin_html_view($content);
    }
    
    // Interviewed candidates by company and job
    public function interviewed_by_company($company_id, $job_id = null) {
        $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Reports');
        
        $data['company_id'] = $company_id;
        $data['job_id'] = $job_id;
        $data['jobs'] = $CI->Reports->get_jobs_by_company($company_id);
        $data['candidates'] = $CI->Reports->get_interviewed_by_company($company_id, $job_id);
        $data['title'] = 'Interviewed Candidates';
        $data['report_type'] = 'interviewed';
        
        $content = $CI->parser->parse('report/candidate_detail_report', $data, true);
        $this->template->full_admin_html_view($content);
    }
    
    // Hired candidates by company and job
    public function hired_by_company($company_id, $job_id = null) {
        $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Reports');
        
        $data['company_id'] = $company_id;
        $data['job_id'] = $job_id;
        $data['jobs'] = $CI->Reports->get_jobs_by_company($company_id);
        $data['candidates'] = $CI->Reports->get_hired_by_company($company_id, $job_id);
        $data['title'] = 'Hired Candidates';
        $data['report_type'] = 'hired';
        
        $content = $CI->parser->parse('report/candidate_detail_report', $data, true);
        $this->template->full_admin_html_view($content);
    }
    
    // Rejected candidates by company and job
    public function rejected_by_company($company_id, $job_id = null) {
        $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Reports');
        
        $data['company_id'] = $company_id;
        $data['job_id'] = $job_id;
        $data['jobs'] = $CI->Reports->get_jobs_by_company($company_id);
        $data['candidates'] = $CI->Reports->get_rejected_by_company($company_id, $job_id);
        $data['title'] = 'Rejected Candidates';
        $data['report_type'] = 'rejected';
        
        $content = $CI->parser->parse('report/candidate_detail_report', $data, true);
        $this->template->full_admin_html_view($content);
    }
    
    // Applied candidates by company and job
    public function applied_by_company($company_id, $job_id = null) {
        $CI =& get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Reports');
        
        $data['company_id'] = $company_id;
        $data['job_id'] = $job_id;
        $data['jobs'] = $CI->Reports->get_jobs_by_company($company_id);
        $data['candidates'] = $CI->Reports->get_applied_by_company($company_id, $job_id);
        $data['title'] = 'Applied Candidates';
        $data['report_type'] = 'applied';
        
        $content = $CI->parser->parse('report/candidate_detail_report', $data, true);
        $this->template->full_admin_html_view($content);
    }
    
}
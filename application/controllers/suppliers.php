<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suppliers extends Admin_Controller {
        
    public function __construct() {
        parent::__construct();

        //render template
        $this->template->write('title', 'SQIM | '.$this->user_type.' Dashboard');
        $this->template->write_view('header', 'templates/header', array('page' => 'masters'));
        $this->template->write_view('footer', 'templates/footer');
		
		$page_new = 'suppliers';
		
		//For page hits
		$this->hits($page_new);

    }
        
    public function index() {
        $this->is_super_admin();
        
        $data = array();
        $this->load->model('Supplier_model');
        $data['suppliers'] = $this->Supplier_model->get_all_suppliers_new();

        $this->template->write_view('content', 'suppliers/index', $data);
        $this->template->render();
    }
	public function supplier_export() {
        $this->is_super_admin();
        
        $data = array();
        $this->load->model('Supplier_model');
        $data['suppliers'] = $this->Supplier_model->get_all_suppliers_new();

       
			$str = $this->load->view("suppliers/supplier_export",$data,true);
			
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=supplier_export.xls");
        
        
        header("Pragma: ");
		header("Cache-Control: ");
		echo $str;
    }
    
    public function add_supplier($supplier_id = '') {
        $this->is_super_admin();
        
        $data = array();
        $this->load->model('Supplier_model');
        
        if(!empty($supplier_id)) {
            $supplier = $this->Supplier_model->get_supplier($supplier_id);
            if(empty($supplier))
                redirect(base_url().'suppliers');

            $data['supplier'] = $supplier;
        }
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $post_data['password'] = 'lge@123';
            
            if(empty($supplier_id)){
                
                $check_duplicate = $this->Supplier_model->get_duplicate_entries($post_data);
            }
            if($check_duplicate){
                $data['error'] = 'Duplicate Entry !!';
            }else{
            
                $response = $this->Supplier_model->add_supplier($post_data, $supplier_id); 
                if($response) {
                    $this->session->set_flashdata('success', 'Supplier successfully '.(($supplier_id) ? 'updated' : 'added').'.');
                    redirect(base_url().'suppliers');
                } else {
                    $data['error'] = 'Something went wrong, Please try again';
                }
            }
        }
        
        $this->template->write_view('content', 'suppliers/add_supplier', $data);
        $this->template->render();
    }
    
    public function upload_suppliers() {
        $this->is_super_admin();
        
        $data = array();
        $this->load->model('Supplier_model');
        
        if($this->input->post()) {
             
            if(!empty($_FILES['supplier_excel']['name'])) {
                $output = $this->upload_file('supplier_excel', 'suppliers', "assets/uploads/");

                if($output['status'] == 'success') {
                    $res = $this->parse_suppliers($output['file']);
                    
                    if($res) {
                        $this->session->set_flashdata('success', 'Suppliers successfully uploaded.');
                        redirect(base_url().'suppliers');
                    } else {
                        $data['error'] = 'Error while uploading excel';
                    }
                } else {
                    $data['error'] = $output['error'];
                }

            }
        }
        
        $this->template->write_view('content', 'suppliers/upload_suppliers', $data);
        $this->template->render();
    }
    
    public function view() {
        
        $this->load->model('Supplier_model');
        $user = $this->Supplier_model->get_all_inspectors_lqc();
		$data['users'] = $user;

        $this->template->write_view('content', 'suppliers/view', $data);
        $this->template->render();
    }
    
    public function view_inspector($id) {
        
        $this->load->model('Supplier_model');
        $user = $this->Supplier_model->get_inspector_lqc($id);
        /*if(empty($user)) {
            redirect(base_url().'supplier_dashboard');
        }*/
        $data['user'] = $user;

        $this->template->write_view('content', 'suppliers/view_supplier_inspector', $data);
        $this->template->render();
    }
    
    public function add_inspector($id = ''){
        
        $data = array();
        
        $this->load->model('Supplier_model');
        
        if(!empty($id)) {
            $user = $this->Supplier_model->get_inspector_lqc($id);
            // echo $this->db->last_query();exit;
            if(!$user)
                redirect(base_url().'supplier_dashboard');

            $data['user'] = $user;
            
        }
        
        if($this->input->post()) {
            $this->load->library('form_validation');

            $validate = $this->form_validation;
            //$validate->set_rules('supplier_id', 'Supplier', 'required|xss_clean');
            $validate->set_rules('name', 'Name', 'trim|required|xss_clean');
            $validate->set_rules('email', 'Email', 'trim|required|xss_clean');

            if($validate->run() === TRUE) {
                $post_data = $this->input->post();

                $exists = $this->Supplier_model->is_supplier_inspector_exists_lqc($post_data['email'], $id);
				 // echo $this->db->last_query();exit;
                if(!$exists){
                    $post_data['supplier_id'] = $this->id;
                    $user_id = $this->Supplier_model->update_supplier_inspector($post_data, $id);
                    if($user_id) {

                        $this->session->set_flashdata('success', 'Inspector successfully added.');
                        redirect(base_url().'suppliers/view');
                    } else {
                        $data['error'] = 'Something went wrong, Please try again.';
                    }

                } else {
                    $data['error'] = 'Email already exists.';
                }

            } else {
                $data['error'] = validation_errors();
            }
        }

        $this->template->write_view('content', 'suppliers/add_supplier_inspector', $data);
        $this->template->render();
    }

    public function status($supplier_id, $status) {
        $this->is_admin_user();
        $this->load->model('Supplier_model');
        
        $up_data = array();
        $up_data['is_active'] = ($status == 'active') ? 1 : 0;
        
        if($status == 'active') { 
            $password = strtoupper(random_string('alnum', 8));
            $up_data['password'] = $password;
        }
        
        
        if($this->Supplier_model->add_supplier($up_data, $supplier_id)) {
            
            if($status) {
                $supplier = $this->Supplier_model->get_supplier($supplier_id);
                $subject = "SQIM Credentials";
                $message = "Dear ".$supplier['name']." ,<br><br>
                Welcome to SQIM, you can login using your email address as username and the password is ".$password." Please change the after login.";
                
                $to = $supplier['email'];
                //$this->sendMail($to, $subject, $message);
            }
            
            $this->session->set_flashdata('success', 'Supplier marked as '.$status);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, Please try again.');
        }

        redirect(base_url().'suppliers');
    }
    
    public function sp_mappings() {
        $data = array();
        $this->load->model('Product_model');
        $data['products'] = $this->Product_model->get_all_products();
        $data['parts'] = $this->Product_model->get_all_distinct_part_name($this->product_id);
        
        //echo "<pre>"; print_r($data['parts']); exit;
        
        $this->load->model('Supplier_model');
        $data['suppliers'] = $this->Supplier_model->get_all_suppliers_new();
        
        $filters = $this->input->post() ? $this->input->post() : array() ;
        if($this->input->post()){
            $_SESSION['sp_filter'] = $filters;
			
            $data['part_nums'] = $this->Product_model->get_all_part_numbers_by_part_name($this->input->post('part_name'));
            $data['sp_mappings'] = $this->Supplier_model->get_all_sp_mappings($filters);
        }else{
            $data['part_nums'] = '';
            $data['sp_mappings'] = '';
        }
        
        //echo $this->db->last_query(); exit;

        $this->template->write_view('content', 'suppliers/sp_mappings', $data);
        $this->template->render();
    }
	public function sp_mappings_export() {
        $data = array();
        $this->load->model('Product_model');
        $this->load->model('Supplier_model');
        $filters = $_SESSION['sp_filter'];
		//print_r($filters);exit;
		$data['sp_mappings'] = $this->Supplier_model->get_all_sp_mappings($filters);
       	$str = $this->load->view("suppliers/sp_mappings_export",$data,true);
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=sp_mappings_export.xls");
        header("Pragma: ");
		header("Cache-Control: ");
		echo $str;
    }
    
    public function add_sp_mapping($sp_mapping_id = '') {
        $data = array();
        $this->load->model('Supplier_model');
        $data['suppliers'] = $this->Supplier_model->get_all_suppliers_new();
        
        $this->load->model('Product_model');
        $data['products'] = $this->Product_model->get_all_products();
        
        if(!empty($sp_mappings_id)) {
            $sp_mapping = $this->Supplier_model->get_sp_mapping($sp_mapping_id);
            if(empty($sp_mapping))
                redirect(base_url().'sp_mappings');

            $data['sp_mapping'] = $sp_mapping;
        }
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            
            $response = $this->Supplier_model->add_sp_mapping($post_data, $sp_mapping_id); 
            if($response) {
                $this->session->set_flashdata('success', 'Supplier-Part Mapping successfully '.(($sp_mapping_id) ? 'updated' : 'added').'.');
                redirect(base_url().'suppliers/sp_mappings');
            } else {
                $data['error'] = 'Something went wrong, Please try again';
            }
        }
        
        $this->template->write_view('content', 'suppliers/add_sp_mapping', $data);
        $this->template->render();
    }
    
    private function parse_suppliers($file_name) {
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file_name);
        
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $arr = $objPHPExcel->getActiveSheet()->toArray(null, true,true,true);
        
        if(empty($arr) || !isset($arr[1])) {
            return FALSE;
        }
        
        $this->load->model('Supplier_model');
        $suppliers = array();
        foreach($arr as $no => $row) {
            if($no == 1)
                continue;
            
            if(!trim($row['A']))
                continue;
            
            $temp = array();
            $temp['supplier_no']    = trim($row['A']);
            $temp['full_name']      = trim($row['B']);
            $temp['name']           = trim($row['C']);
            $temp['email']          = trim($row['D']);
            
            $cost = $this->config->item('hash_cost');
            $temp['password'] = password_hash(SALT .'lge@123', PASSWORD_BCRYPT, array('cost' => $cost));
            
            $temp['created']        = date("Y-m-d H:i:s");
            
            $exists = $this->Supplier_model->get_supplier_by_code($temp['supplier_no']);
            if($exists) {
                $this->Supplier_model->add_supplier($temp, $exists['id']);
            } else {
                $suppliers[]        = $temp;
            }
        }

        if($suppliers) {
            $this->Supplier_model->insert_suppliers($suppliers);
        }
        
        return TRUE;
    }
    
    public function inspector_status($id, $status) {
        $this->load->model('Supplier_model');
        if($this->Supplier_model->change_inspector_status($id, $status)) {
            $this->session->set_flashdata('success', 'User marked as '.$status);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, Please try again.');
        }

        redirect(base_url().'suppliers/view');
    }
}
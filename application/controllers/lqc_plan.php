<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lqc_plan extends Admin_Controller {
        
    public function __construct() {
        parent::__construct(true);

        //$this->is_supplier();
        //render template
        $this->template->write('title', 'LQC | LQC Plan Module');
        $this->template->write_view('header', 'templates/header', array('page' => 'masters'));
        $this->template->write_view('footer', 'templates/footer');
		
		$page_new = 'LQC_Plan';
		
		//For page hits
		$this->hits($page_new);

    }

    public function index() {
        $this->load->model('Product_model');
       
        $product = $this->Product_model->get_product($this->product_id);
        if(empty($product))
            redirect(base_url().'products');

        $data['product'] = $product;
        $data['parts'] = $this->Product_model->get_all_product_parts_by_supplier($this->product_id, $this->id);
        
        $checkpoints = array();
        if($this->input->get('part_no')) {
			$_SESSION['tc_part_filter'] = $this->input->get('part_no');
            $checkpoints = $this->Lqc_plan_model->get_checkpoints($this->product_id, $this->id, $this->input->get('part_no'));
        }
        
        $data['checkpoints'] =  $checkpoints;
		// echo $this->db->last_query();exit;
        $this->template->write_view('content', 'lqc_plan/index', $data);
        $this->template->render();
    }
	public function download_checkpoints() {
		$filters = $_SESSION['tc_part_filter'];
		//print_r($filters);exit;
        $this->load->model('Product_model');
        $this->load->model('Lqc_plan_model');
        
        $checkpoints = array();
		if($filters) {
			$p = $this->Product_model->get_product($this->product_id);
			//print_r($p['code']);exit;
			$proc_code = $p['code'];
			$checkpoints = $this->Lqc_plan_model->get_checkpoints($this->product_id, $this->id, $filters);
		}
        $data['checkpoints'] =  $checkpoints;
        $data['proc_code'] =  $proc_code;
		
		// echo $this->db->last_query();exit;
        $str = $this->load->view("tc_checkpoints/tc_checkpoint_list",$data,true);
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=tc_checkpoint_list.xls");
        header("Pragma: ");
		header("Cache-Control: ");
		echo $str;
    }
    
    public function add_checkpoint($checkpoint_id = '') {
        $data = array();

        $this->load->model('Lqc_plan_model');
        $data['existing_checkpoints'] = '';
        
        $data['insp_types'] = $this->Lqc_plan_model->get_distinct_insp_type();
        if(!empty($checkpoint_id)) {
            $checkpoint = $this->Lqc_plan_model->get_checkpoint($checkpoint_id);
            if(empty($checkpoint))
                redirect(base_url().'tc_checkpoints');

            $data['checkpoint'] = $checkpoint;
        }

        $this->load->model('Product_model');
        $data['parts'] = $this->Product_model->get_all_product_parts_by_supplier($this->product_id, $this->supplier_id);

        if($this->input->post()) {
            
            $this->load->library('form_validation');

            $validate = $this->form_validation;
            $validate->set_rules('checkpoint_no', 'Checkpoint No', 'trim|required|xss_clean');
            $validate->set_rules('part_id', 'Part', 'trim|required|xss_clean');

            if($validate->run() === TRUE) {
                $post_data = $this->input->post();
                $part = $this->Product_model->get_product_part($this->product_id, $post_data['part_id']);
                $post_data['product_id'] = $this->product_id;
                
                $id = !empty($checkpoint['id']) ? $checkpoint['id'] : '';
                $checkpoint_no = $this->input->post('checkpoint_no');
                
                $post_data['supplier_id'] = $this->id;
            
                $exists = $this->Lqc_plan_model->is_checkpoint_no_exists($this->product_id, $post_data['part_id'], $post_data['child_part_no'], $this->id, $checkpoint_no, $id);
                if($exists) {
                    $this->Lqc_plan_model->move_checkpoints($this->product_id, $post_data['part_id'], $post_data['child_part_no'], $this->id, $checkpoint_no);
                }
                
                $post_data['status'] = 'Pending';
                // print_r($post_data);exit;
                $checkpoint_id = $this->Lqc_plan_model->update_checkpoint($post_data, $id);
                if($checkpoint_id) {
                    $this->session->set_flashdata('success', 'Checkpoint successfully '.(($checkpoint_id) ? 'updated' : 'added').'.');
                    redirect(base_url().'tc_checkpoints?part_no='.$part['code']);
                } else {
                    $data['error'] = 'Something went wrong, Please try again';
                }

            } else {
                $data['error'] = validation_errors();
            }
            
        }

        $this->template->write_view('content', 'lqc_plan/add_checkpoint', $data);
        $this->template->render();
    }
    
    public function upload_checkpoints() {
        $data = array();
        $this->load->model('Product_model');
        
        $product = $this->Product_model->get_product($this->product_id);
        if(empty($product))
            redirect(base_url().'products');
        
        $data['product'] = $product;
        
        if($this->input->post()) {
            
            ini_set('memory_limit', '100M');
             
            if(!empty($_FILES['checkpoints_excel']['name'])) {
                $output = $this->upload_file('checkpoints_excel', 'checkpoints_excel', "assets/uploads/");

                if($output['status'] == 'success') {
                    $res = $this->parse_checkpoints($this->id, $output['file']);
                    
                    if($res) {
                        $this->session->set_flashdata('success', 'Checkpoints successfully uploaded.');
                        redirect(base_url().'TC_checkpoints');
                    } else {
                        $data['error'] = 'Error while uploading excel';
                    }
                } else {
                    $data['error'] = $output['error'];
                }

            }
        }
        
        $this->template->write_view('content', 'lqc_plan/upload_checkpoints', $data);
        $this->template->render();
    }
    
    public function delete_checkpoint($checkpoint_id) {
        $this->load->model('Lqc_plan_model');
        $checkpoint = $this->Lqc_plan_model->get_checkpoint($checkpoint_id, $this->id);
        if(empty($checkpoint))
            redirect(base_url().'checkpoints');

        $deleted = $this->Lqc_plan_model->delete_checkpoint($this->product_id, $checkpoint_id, $this->id);

        if($deleted) {
            $this->Lqc_plan_model->move_checkpoints_down($this->product_id, $checkpoint['part_id'], $checkpoint['child_part_no'], $this->id, $checkpoint['checkpoint_no']);
            $this->session->set_flashdata('success', 'Checkpoint deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
        }
        
        redirect(base_url().'tc_checkpoints?part_no='.$checkpoint['part_no']);
    }
    
    private function parse_checkpoints($supplier_id, $file_name) {
        
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file_name);
        
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $arr = $objPHPExcel->getActiveSheet()->toArray(null, true,true,true);

        if(empty($arr) || !isset($arr[1])) {
            return FALSE;
        }
        
        $this->load->model('Product_model');
        $this->load->model('Lqc_plan_model');
        
        $checkpoints = array();
        $parts = array();
        $part_no = '';
        $part_id = '';
        $tmp_insp_item = '';
        $i=0; $j=0;
        foreach($arr as $no => $row) {
            if($no == 1)
                continue;

            if(!trim($row['C']))
                continue;
            
            if(!trim($row['D']))
                continue;
            
            if(!trim($row['E']))
                continue;
            
            if(!trim($row['G']))
                continue;
            
            //echo "hi"; exit;
            /* if($org_name != trim($row['E']))
                continue; */
            
            $prod_code = trim($row['C']);
            $product = $this->Product_model->get_product_id_by_name($prod_code);

            if(empty($product))
                continue;
            
            $part_no = trim($row['D']);
            if(!array_key_exists($part_no, $parts)) {
                
                $exists = $this->Product_model->get_product_part_by_code($product['id'], $part_no);
                if(empty($exists)) {
                    continue;
                } else {
                    $part_id = $exists['id'];
                }

                $parts[$part_no] = $part_id;
            }

            $temp = array();

            $temp['product_id']         = $product['id'];
            $temp['supplier_id']        = $supplier_id;
            $temp['part_id']            = $parts[$part_no];
            $temp['child_part_no']      = trim($row['E']);
            $temp['child_part_name']    = trim($row['F']);
            $temp['mold_no']            = trim($row['G']);
            $temp['insp_item']          = trim($row['J']);
            $temp['spec']               = trim($row['K']);
            $temp['lsl']                = trim($row['L']);
            $temp['usl']                = trim($row['N']);
            $temp['tgt']                = trim($row['M']);
            $temp['unit']               = trim($row['O']);
            $temp['sample_qty']         = trim($row['Q']);
            $temp['measure_type']       = trim($row['I']);
            $temp['frequency']          = str_replace(array(' Hr', 'Hr', ' Hrs', 'Hrs'), '', trim($row['P']));
            $temp['stage']              = trim($row['H']);
            $temp['instrument']         = trim($row['R']);
            $temp['status']             = 'Pending';

            $temp['created']            = date("Y-m-d H:i:s");

            if(trim($row['S']) == 'N') {
                $temp['is_deleted']         = 1;
            } else {
                $temp['is_deleted']         = 0;
            }

            $exists_chk = $this->Lqc_plan_model->check_duplicate_checkpoint($temp);
            if($exists_chk) {
                $i++;
                $this->Lqc_plan_model->update_checkpoint($temp, $exists_chk['id']);
            } else {
                $j++;
                $checkpoints[]        = $temp;
            }

        }
        
        if(!empty($checkpoints)) {
            $this->Lqc_plan_model->insert_checkpoints($checkpoints, $product['id'], $supplier_id);
        }

        return TRUE;
    }
    
    public function checkpoint_approval_index(){
        
        $data = array();
        
        $this->load->model('Lqc_plan_model');
        $data['approval_items'] = $this->Lqc_plan_model->get_pending_checkpoints_by_product($this->product_id);
        
        $this->template->write_view('content', 'lqc_plan/checkpoint_approval_index', $data);
        $this->template->render();
    }
    
	
	
    public function checkpoint_status($checkpoint_id, $status){
        
        $data = array();
        $this->load->model('Lqc_plan_model');
        
        $update_status = $this->Lqc_plan_model->change_status($checkpoint_id, $status);
        
        if($update_status && $status == 'Approved') {
            $this->session->set_flashdata('success', 'Inspection Item successfully Approved.');
        } else {
            $this->session->set_flashdata('error', 'Inspection Item Declined.');
        }
        
        redirect(base_url().'lqc_plan/checkpoint_approval_index');
    }

	
    public function change_checkpoints_status_all($status){
        
        $data = array();
        $this->load->model('Lqc_plan_model');
        
        $update_status = $this->Lqc_plan_model->change_status_all($status,$this->product_id);
        
        if($update_status && $status == 'Approved') {
            $this->session->set_flashdata('success', 'Inspection Item successfully Approved.');
        } else {
            $this->session->set_flashdata('error', 'Inspection Item Declined.');
        }
        
        redirect(base_url().'lqc_plan/checkpoint_approval_index');
    }

    public function plans() {
        $data = array();
        $this->load->model('Lqc_plan_model');

        $plan_date = $this->input->get('plan_date') ? $this->input->get('plan_date') :  date('Y-m-d');
        $data['plan_date'] = $plan_date;
        $data['plans'] = $this->Lqc_plan_model->get_all_plans_lqc($this->product_id, $this->supplier_id, $plan_date);
// echo $this->db->last_query();exit;
        $this->template->write_view('content', 'lqc_plan/plans', $data);
        $this->template->render();
    }
    
    public function add_plan($plan_id = '') {
		
		
        $data = array();
        $this->load->model('Lqc_plan_model');
        
        if(!empty($plan_id)) {
            $plan = $this->Lqc_plan_model->get_plan_lqc($plan_id, $this->supplier_id);
            if(empty($plan))
                redirect(base_url().'lqc_plan/plans');

            $data['plan'] = $plan;
            
          
        }
        
        $this->load->model('Product_model');
        // $data['parts'] = $this->Product_model->get_all_product_parts_by_supplier($this->product_id, $this->supplier_id);
        $data['parts'] = $this->Product_model->get_all_product_parts_by_supplier_defect_code($this->product_id, $this->supplier_id);
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $post_data['supplier_id'] = $this->id;
            $post_data['product_id'] = $this->product_id;
			
			$part = $this->Product_model->get_part($post_data['part_id']);
			$post_data['part_no'] = $part['code'];
            if(empty($plan_id)){
				$plan = $this->Lqc_plan_model->get_plan_lqc_by_part($post_data['part_id'], $post_data['plan_date'], $this->supplier_id);
				// print_r( $plan);exit;
				if(!empty($plan)){
					// $this->Lqc_plan_model->delete_plan_lqc_by_part($post_data['part_id'], $post_data['plan_date'], $this->supplier_id);
					$this->session->set_flashdata('error', 'Plan already exist for Part-'.$post_data['part_no'].' Dated '.$post_data['plan_date'].', You can edit the same. ');
					redirect(base_url().'lqc_plan/plans?plan_date='.$post_data['plan_date']);
				}
			}
			
			// print_r($post_data);exit;
            $response = $this->Lqc_plan_model->update_plan($post_data, $plan_id); 
            if($response) {
                $this->session->set_flashdata('success', 'Plan successfully '.(($plan_id) ? 'updated' : 'added').'.');
                redirect(base_url().'lqc_plan/plans?plan_date='.$post_data['plan_date']);
            } else {
                $data['error'] = 'Something went wrong, Please try again';
            }
        }

        $this->template->write_view('content', 'lqc_plan/add_plan', $data);
        $this->template->render();
    }
    
    public function delete_plan($plan_id) {
        $this->load->model('Lqc_plan_model');

        $plan = $this->Lqc_plan_model->get_plan_lqc($plan_id, $this->supplier_id);
        if(empty($plan))
            redirect($_SERVER['HTTP_REFERER']);
            
        $deleted = $this->Lqc_plan_model->delete_plan_lqc($plan_id); 
        if($deleted) {
            $this->session->set_flashdata('success', 'Plan deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
        }
        
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function get_child_parts_by_part_id() {
        $data = array('child_part_nos' => array());
        
        if($this->input->post('part_id')) {
            $this->load->model('Lqc_plan_model');
            $data['child_part_nos'] = $this->Lqc_plan_model->get_child_parts_by_part_id($this->input->post('part_id'), $this->supplier_id, $this->product_id);
        }
        
        echo json_encode($data);
    }
    
    public function get_mold_no_by_child_part_no() {
        $data = array('mold_nos' => array());
        
        if($this->input->post('child_part_no') && $this->input->post('part_id')) {
            $this->load->model('Lqc_plan_model');
            $data['mold_nos'] = $this->Lqc_plan_model->get_mold_no_by_child_part_no($this->input->post('part_id'), $this->input->post('child_part_no'), $this->supplier_id, $this->product_id);
        }
        
        echo json_encode($data);
    }
	
	public function upload_production_plan($plan_date = '') {
        $data = array();
        // echo $plan_date;exit;
        $this->load->model('Lqc_plan_model');
        if(empty($plan_date)) {
            $plan_date = date('Y-m-d', strtotime('+1 day'));
        }else{
            if(strtotime($plan_date) < strtotime(date('Y-m-d'))){
                $this->session->set_flashdata('error', 'You can\'t upload production plan for past date.');
                redirect(base_url().'lqc_plan/plans');
            }
        }
        $data['plan_date'] = $plan_date;
        
        if($this->input->post()) {
            $production_plan_date = $this->input->post('plan_date') ? $this->input->post('plan_date') : $plan_date;

            if(strtotime($production_plan_date) >= strtotime(date('Y-m-d'))) {
                if(!empty($_FILES['production_plan_excel']['name'])) {
                    $output = $this->upload_file('production_plan_excel', $production_plan_date, "assets/production_plan/");

                    if($output['status'] == 'success') {
                        $res = $this->read_production_plan_excel($production_plan_date, $output['file']);
                        if($res) {
                            $data['success'] = 'Plan Uploaded Successfully.';
                            redirect(base_url().'lqc_plan/plans');
                        } else {
                            $data['error'] = 'Incorrect Format, Please check.';
                        }
                    } else {
                        $data['error'] = $output['error'];
                    }
                }
            
            } else {
                $data['error'] = 'Enter a valid date. Date can\'t be less than Today\'s date.';
            }
            
        }
        
        $this->template->write_view('content', 'lqc_plan/upload_production_plan', $data);
        $this->template->render();
    }
	
	 private function read_production_plan_excel($production_plan_date, $file_name) {
		 
		$this->load->model('Product_model');
		$this->load->model('Lqc_plan_model');
        $this->load->library('excel');
        //read file from path
		// echo $production_plan_date;exit;
        $objPHPExcel = PHPExcel_IOFactory::load($file_name);
        
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $arr = $objPHPExcel->getActiveSheet()->toArray(null, true,true,true);
        // echo count($arr[1])."<pre>";print_r($arr);
        if(empty($arr) || !isset($arr[1]) || count($arr[1]) < 4) {
            return FALSE;
        }
        // echo "sdfg";exit;
        $production_plan = array();
        $full_data = array();
		 $part_id = '';
        foreach($arr as $no => $row) {

			$content_error = array();
           
			
            if($no == 1){
				$headers = array();
				$headers = $row;
				$headers['E'] = 'Error';
                continue;
            }
			
            if(trim($row['C'])){
				//echo trim($row['C']);
				$part_exist = $this->Product_model->get_product_part_by_code($this->product_id,trim($row['C']));
			
				$part_id = !empty($part_exist) ? $part_exist['id'] : '';      
					
				//Get Plan for this Part_id for current month_year
				
				if($part_id == ''){	
					$content_error[] = 'Planned Part Number not found.'; 
				}
				else{
					
					$sp_mapping = $this->Product_model->get_sp_mapping_by_pid_sid($this->product_id,$part_id,$this->supplier_id);
					if(empty($sp_mapping))
						$content_error[] = 'Supplier-Part Mapping Not found.'; 
					
					$defect_exist = $this->Product_model->get_defect_code_for_audit($this->product_id,$part_id);
					
					if(empty($defect_exist))
						$content_error[] = "Defect Code Not Found."; 
						
				}
			}
			if(count($row) < 4)
			{   
				$content_error[] = "Appropriate data not found, less than required column inserted"; 
			}
			
			$temp = array();
			if(!empty($content_error)){
				
				$temp['SR No'] = $row['A'];
				$temp['part_name'] = $row['B'];
				$temp['part_no'] = trim($row['C']);
				$temp['lot_size'] = $row['D'];
				$temp['error']    	   = implode(",",$content_error);
				$full_data[]       	   = $temp;
			}	
			
			if(count($row) < 4)
                continue;
			
			if($part_id == '')
				continue;
			else if(empty($sp_mapping))
                continue;
					
			if(!empty($sp_mapping)) {
                $plan = array();
                $temp['product_id'] = $this->product_id;
				$temp['supplier_id'] = $this->supplier_id;
				$temp['plan_date'] = $production_plan_date;
				$temp['part_no'] = trim($row['C']);
				$temp['part_id'] = $part_id;
				$temp['lot_size'] = $row['D'];
				$temp['created'] = date("Y-m-d H:i:s");
				$production_plan[] = $temp;			
            }
            
        }
		if(!empty($full_data)){			 
				$this->create_excel($headers, $full_data, 'plan_error_file');			
		}
        if(!empty($production_plan)) {
		
            $this->Lqc_plan_model->insert_production_plan_lqc($production_plan, $production_plan_date);
            
			
			
            return TRUE;
        } else {
            return FALSE;
        }
        
    }

}
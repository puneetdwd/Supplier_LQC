<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auditer extends Admin_Controller {
        
    public function __construct() {
        parent::__construct(true);

        $page = 'inspections';
        $this->template->write_view('header', 'templates/header', array('page' => $page));
        $this->template->write_view('footer', 'templates/footer');
		
		$page_new = 'auditer';
		
		//For page hits
		$this->hits($page_new);

    }

   
    public function review_audit_lqc_defect($audit_lqc_defect_id, $audit_id = '') {
        $data = array();
        $this->load->model('Audit_model');
        $this->load->model('Product_model');

        // $audit = $this->Audit_model->get_audit_lqc($this->id, 'finished');
        if(!$audit_id) {
            $audit = $this->Audit_model->get_audit_lqc($this->id, 'finished');
        }else {
            $data['admin_edit_audit'] = $audit_id;
            $audit = $this->Audit_model->get_audit_lqc('', 'completed', '', $audit_id);
        }
		
		if(empty($audit)) {            
            echo "<div class='modal-body'>Something went wrong. Please refresh your screen.</div>";
            return;
        }
		
        $defect_code = $this->Product_model->get_defect_code_mappings_by_partid($audit['product_id'], $audit['part_id']);
		$data['audit'] = $audit;
        $data['defect_code'] = $defect_code;
		
		
        $lqc_defect_code = $this->Audit_model->get_all_audit_lqc_defect_code($audit_lqc_defect_id,$audit['id']);
		// print_r($lqc_defect_code);exit;
        $data['lqc_defect_code'] = $lqc_defect_code;
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            
                $all_results = array();
                foreach ($post_data as $key => $value) {
                    if(strpos($key, 'audit_result_') === 0) {
                        $all_results[] = $value;
                    }
              
                
                $post_data['all_values'] = null;
                $post_data['all_results'] = implode(',', $all_results);
                $post_data['result'] = (strpos($post_data['all_results'], 'NG') === false) ? 'OK' : 'NG';
            }
            
            $response = $this->Audit_model->record_checkpoint_result($post_data, $checkpoint['id'], $audit['id']);
            if($response) {
                $exists = $this->Audit_model->check_audit_complete_exists($audit['id']);
                if($exists || true) {
                    $this->Audit_model->delete_audit_complete($audit['id']);
                    $this->Audit_model->add_to_completed_audits($audit['id']);
                }
                
                
                
                $this->session->set_flashdata('success', 'Result recorded successfully');
            } else {
                $this->session->set_flashdata('error', 'Unable to update the result. Please try again.');
            }
            
            if(!$audit_id) {
               
                redirect(base_url().'auditer/finish_screen_lqc');
            }
        }
        
        echo $this->load->view('auditer/review_defect_code_modal', $data, true);
    }
	public function retest_audit_lqc_defect($audit_lqc_defect_id, $audit_id = '') {
        $data = array();
        $this->load->model('Audit_model');
        $this->load->model('Product_model');

        // $audit = $this->Audit_model->get_audit_lqc($this->id, 'finished');
        if(!$audit_id) {
            $audit = $this->Audit_model->get_audit_lqc($this->id, 'finished');
        }else {
            $data['admin_edit_audit'] = $audit_id;
            $audit = $this->Audit_model->get_audit_lqc('', 'completed', '', $audit_id);
        }
		
		if(empty($audit)) {            
            echo "<div class='modal-body'>Something went wrong. Please refresh your screen.</div>";
            return;
        }
		
        $defect_code = $this->Product_model->get_defect_code_mappings_by_partid($audit['product_id'], $audit['part_id']);
		$data['audit'] = $audit;
        $data['defect_code'] = $defect_code;
		
		
        $lqc_defect_code = $this->Audit_model->get_all_audit_lqc_defect_code($audit_lqc_defect_id,$audit['id']);
		// print_r($lqc_defect_code);exit;
        $data['lqc_defect_code'] = $lqc_defect_code;
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            
                $all_results = array();
                foreach($post_data as $key => $value) {
						if(strpos($key, 'audit_result_') === 0) {
							$all_results[] = $value;
						}
					$post_data['all_values'] = null;
					$post_data['all_results'] = implode(',', $all_results);
					$post_data['result'] = (strpos($post_data['all_results'], 'NG') === false) ? 'OK' : 'NG';
				}
            
				// $response = $this->Audit_model->record_checkpoint_result($post_data, $checkpoint['id'], $audit['id']);
			    /*  if($response) {
					$exists = $this->Audit_model->check_audit_complete_exists($audit['id']);
					if($exists || true) {
						$this->Audit_model->delete_audit_complete($audit['id']);
						$this->Audit_model->add_to_completed_audits($audit['id']);
					}
					$this->session->set_flashdata('success', 'Result recorded successfully');
				} else {
					$this->session->set_flashdata('error', 'Unable to update the result. Please try again.');
				}
             */
            if(!$audit_id) {
                redirect(base_url().'auditer/finish_screen_lqc_retest');
            }
        }
        
        echo $this->load->view('auditer/retest_defect_code_modal', $data, true);
    }
    public function review_audit_lqc_defect_old($audit_lqc_defect_id, $audit_id = '') {
        $data = array();
        $this->load->model('Audit_model');
        $this->load->model('Product_model');

        // $audit = $this->Audit_model->get_audit_lqc($this->id, 'finished');
        if(!$audit_id) {
            $audit = $this->Audit_model->get_audit_lqc($this->id, 'finished');
        }else {
            $data['admin_edit_audit'] = $audit_id;
            $audit = $this->Audit_model->get_audit_lqc('', 'completed', '', $audit_id);
        }
		
		if(empty($audit)) {            
            echo "<div class='modal-body'>Something went wrong. Please refresh your screen.</div>";
            return;
        }
		
        $defect_code = $this->Product_model->get_defect_code_mappings_by_partid($audit['product_id'], $audit['part_id']);
		$data['audit'] = $audit;
        $data['defect_code'] = $defect_code;
		
		
        $lqc_defect_code = $this->Audit_model->get_all_audit_lqc_defect_code($audit_lqc_defect_id,$audit['id']);
		// print_r($lqc_defect_code);exit;
        $data['lqc_defect_code'] = $lqc_defect_code;
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            if(!empty($checkpoint['lsl']) || !empty($checkpoint['usl'])) {
                $all_values = array();
                foreach ($post_data as $key => $value) {
                    if(strpos($key, 'audit_value_') === 0) {
                        $all_values[] = $value;
                    }
                }
                
                if(empty($all_values)) {
                    $this->session->set_flashdata('error', 'Something went wrong will storing result. Please try again');
                    redirect(base_url().'auditer/checkpoint_screen');
                }
                
                if(!isset($post_data['result']) || $post_data['result'] != 'NA') {
                    $final_result = 'OK';
                    $all_results = array();
                    foreach($all_values as $ex_val) {
                        $audit_value = (float)$ex_val;
                        
                        if(!empty($checkpoint['lsl']) && $audit_value < $checkpoint['lsl']) {
                            $all_results[] = 'NG';
                            $final_result = 'NG';
                            continue;
                        }
                        
                        if(!empty($checkpoint['usl']) && $audit_value > $checkpoint['usl']) {
                            $all_results[] = 'NG';
                            $final_result = 'NG';
                            continue;
                        }
                        
                        $all_results[] = 'OK';
                    }
                    
                    $post_data['all_values'] = implode(',', $all_values);
                    $post_data['all_results'] = implode(',', $all_results);
                    $post_data['result'] = $final_result;
                }
            } else {
                $all_results = array();
                foreach ($post_data as $key => $value) {
                    if(strpos($key, 'audit_result_') === 0) {
                        $all_results[] = $value;
                    }
                }
                
                $post_data['all_values'] = null;
                $post_data['all_results'] = implode(',', $all_results);
                $post_data['result'] = (strpos($post_data['all_results'], 'NG') === false) ? 'OK' : 'NG';
            }
            
            $response = $this->Audit_model->record_checkpoint_result($post_data, $checkpoint['id'], $audit['id']);
            if($response) {
                $exists = $this->Audit_model->check_audit_complete_exists($audit['id']);
                if($exists || true) {
                    $this->Audit_model->delete_audit_complete($audit['id']);
                    $this->Audit_model->add_to_completed_audits($audit['id']);
                }
                
                if(!$audit_id) {
                    if($post_data['result'] == 'NG' && base_url() != 'http://localhost/LQC/') {
                        $this->load->model('Product_model');
                        $phone_numbers = $this->Product_model->get_all_phone_numbers($audit['product_id']);
                        if(!empty($phone_numbers)) {
                            $to = array();
                            
                            foreach($phone_numbers as $phone_number) {
                                $to[] = $phone_number['phone_number'];
                            }
                            
                            $to = implode(',', $to);
                            
                            $sms = $audit['supplier_name']." OQC- Inspn Rslt NG\nPart No. -".$audit['part_no']."(".$audit['org_name'];
                            $sms .= ")\nDefect-".$post_data['remark'];
                            
                            $ip_address = $this->get_server_ip();
                            
                            if($ip_address == '202.154.175.50'){
                                
                                if(isset($to) && isset($sms)){
                                    $sms1= urlencode($sms);
                                    $to1 = urlencode($to);
                                    $data = array('to' => $to1, 'sms' => $sms1);
                                    $url = "http://10.101.0.80:90/SQIM/auditer/send_sms_redirect";    	

                                    $ch = curl_init();
                                            curl_setopt_array($ch, array(
                                            CURLOPT_URL => $url,
                                            CURLOPT_RETURNTRANSFER => true,
                                            CURLOPT_POSTFIELDS => $data,
                                    ));
                                    //get response
                                    $output = curl_exec($ch);
                                    $flag = true;
                                    //Print error if any
                                    if(curl_errno($ch))
                                    {
                                            $flag = false;
                                    }
                                    curl_close($ch);
                                }
                            }else{
                                $this->send_sms($to, $sms);
                            }
                        }
                        
                    }
                }
                
                $this->session->set_flashdata('success', 'Result recorded successfully');
            } else {
                $this->session->set_flashdata('error', 'Unable to update the result. Please try again.');
            }
            
            if($audit_id) {
                redirect(base_url().'auditer/finish_screen/'.$audit_id);
            } else {
                redirect(base_url().'auditer/finish_screen');
            }
        }
        
        echo $this->load->view('auditer/review_defect_code_modal', $data, true);
    }
    
    
    public function mark_as_complete_lqc() {
		
        $this->load->model('Product_model');
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit_lqc($this->id, 'finished');
        // print_r($audit);exit;
        
        $response = $this->Audit_model->change_state_lqc($audit['id'], $this->id, 'completed');
        if($response) {
			// echo $_SESSION['mark_as_remove_remaining'];
			if(isset($_SESSION['mark_as_remove_remaining'])){
				//To update audit product lot
				$s['or_lot'] = $audit['prod_lot_qty'] - $audit['remaining_prod_lot_qty']; 
				$s['remaining_prod_lot_qty'] = 0;
				$this->Audit_model->update_to_audits_lqc_remove_all($audit['id'],$s);
				/* echo $this->db->last_query();
				echo "4";exit; */
				//To update remaining_lot_history
				$remaining_lot_history_part = $this->Product_model->get_remaining_lot_history($audit['part_id']);
				$r['lqc_remaining_lot'] = $remaining_lot_history_part['lqc_remaining_lot'] + $audit['remaining_prod_lot_qty'];
				$this->Audit_model->update_to_remaining_lot_history_remove_all($remaining_lot_history_part['id'],$r);
				
				// exit;	
				$this->Audit_model->add_to_completed_audits_lqc_remove($audit['id']);
				//Send To SQIM
					$rr = $this->Audit_model->send_to_sqim_remove($audit['id']);
					if($rr){
						 // $this->Audit_model->maintain_sqim_lqc_inspection_history($audit['id']);
					}
				//Send To SQIM 
			}
			else{
				$this->Audit_model->add_to_completed_audits_lqc($audit['id']);
				//Send To SQIM
					$rr = $this->Audit_model->send_to_sqim($audit['id']);
					if($rr){
						 // $this->Audit_model->maintain_sqim_lqc_inspection_history($audit['id']);
					}
				//Send To SQIM 
			}
			$this->session->set_flashdata('success', 'Inspection successfully marked completed.');
            redirect(base_url().'auditer/register_inspection_lqc');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong. Please try again');
            redirect(base_url().'auditer/finish_screen_lqc');
        }
        
    }
    
    public function mark_as_abort_lqc($audit_id = '', $on_hold = 0) {
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit_lqc($this->id, array('registered','started', 'finished'), '', $audit_id, $on_hold);
        if(empty($audit)) {
            $this->check_inspection_lqc();

        }
        $response = $this->Audit_model->change_state_lqc($audit['id'], $this->id, 'aborted');
		// print_r($response);exit;
        if($response) {
            //$this->destroy_checkpoint_session();
			// $this->Audit_model->add_to_completed_audits_lqc_remove($audit['id']);
            $this->session->set_flashdata('success', 'Inspection successfully marked aborted.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong. Please try again');
        }
        // echo $_SERVER['HTTP_REFERER'];exit;
        redirect(base_url().'auditer/register_inspection_lqc');
    } 
     
    public function mark_as_remove_remaining($audit_id = '', $on_hold = 0) {
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit_lqc($this->id, array('registered','started'), '', $audit_id, $on_hold);
        if(empty($audit)) {
            $this->check_inspection_lqc();
        }
		$response = $this->Audit_model->change_state_lqc($audit['id'], $this->id, 'finished');
		if($response) {
        	$audit = $this->Audit_model->get_audit_lqc($this->id, 'finished');
			if($audit){
				$_SESSION['mark_as_remove_remaining'] = 'mark_as_remove_remaining';
				$this->session->set_flashdata('success', 'Remaining Lot deleted.');
				 redirect(base_url().'auditer/finish_screen_lqc');
			}	
        } else {
            $this->session->set_flashdata('error', 'Something went wrong. Please try again');
        }
			redirect(base_url().'auditer/register_inspection_lqc');
    }
	
    public function on_hold_lqc($audit_id = '') {
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit_lqc($this->id, array('registered','started', 'finished'), '', $audit_id);

        if(empty($audit)) {
            $this->check_inspection_lqc();
        }
        $response = $this->Audit_model->hold_resume_audit($audit['id']);
        // print_r($response);exit;
        if($response) {
             $this->session->set_flashdata('success', 'Inspection successfully marked on hold.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong. Please try again');
        }
        
       redirect(base_url().'auditer/register_inspection_lqc');
    }
    
    
    
    public function get_sample_qty() {
        $response = array('status' => 'error');
        if($this->input->post()) {
            $checkpoint_id = $this->input->post('checkpoint_id');
            $part_id = $this->input->post('part_id');
            $prod_lot_qty = $this->input->post('prod_lot_qty');
            
            $res = $this->create_sample_qty($checkpoint_id, $part_id, $prod_lot_qty);
            
			// echo "<pre>";print_r($res);exit;
            $sampling = $this->session->userdata('sampling');
            $sampling[$checkpoint_id] = $res;
            $this->session->set_userdata('sampling', $sampling);
            $response = array('status' => 'success', 'judgement' => $res);
        }
        
        echo json_encode($response);
    }
    
	//For LQC Inspection
	
	public function register_inspection_lqc() {
        $data = array();
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit_lqc($this->id, array('registered','started', 'finished'));
       
        if(!empty($audit)) {
            $this->check_inspection_lqc($audit);
        }
        
        $this->load->model('User_model');
        if($this->user_type == 'Supplier'){
            $user = $this->User_model->get_supplier_user($this->id);
			$supplier_id = $this->id;
        }else{
            $user = $this->User_model->get_supplier_inspector_user($this->id);
			$supplier_id = $user['supplier_id'];
        }
        
			
		if($this->input->post()) {
			$this->load->library('form_validation');

            $validate = $this->form_validation;
            $validate->set_rules('audit_date', 'Production Plan Date', 'trim|required|xss_clean');
            $validate->set_rules('part_id', 'Part', 'trim|required|xss_clean');
            $validate->set_rules('prod_lot_qty', 'Production Lot Qty', 'trim|required|xss_clean');

            if($validate->run() === TRUE) {
                $post_data = $this->input->post();
                $this->load->model('Product_model');
                $this->load->model('Audit_model');
				
				
                $part = $this->Product_model->get_product_part($this->product_id, $post_data['part_id']);
                if(empty($part)) {
                    $this->session->set_flashdata('error', 'Invalid Part');
                    redirect(base_url().'auditer/register_inspection_lqc');
                }
				
				//Start =>check for Exist Plan && Exist Defect
				$this->load->model('Lqc_plan_model');
				
				//$data['plans'] = $this->Lqc_plan_model->get_all_plans_lqc($this->product_id, $this->supplier_id, date('Y-m-d'),$post_data['part_id']);
				
				$data['plans'] = $this->Lqc_plan_model->get_all_plans_lqc_new($this->product_id, $this->supplier_id,$post_data['part_id']);
				// echo $this->db->last_query();exit;
				if(count($data['plans']) > 0) {
					
					
					$planned_prod_lot_qty =  array_sum(array_column($data['plans'], 'lot_size'));
					
					$remaining_lot_array = array();
					$remaining_lot_array['product_id'] = $this->product_id;
					$remaining_lot_array['part_id'] = $part['id'];
					$remaining_lot_array['part_no'] = $part['code'];
					$remaining_lot_array['lqc_planned_lot'] = $planned_prod_lot_qty;
					
					$remaining_lot_history_part = $this->Product_model->get_remaining_lot_history($part['id']);
					// print_r($remaining_lot_history_part);exit;
					if(empty($remaining_lot_history_part)){
						$remaining_lot_array['lqc_remaining_lot'] = $planned_prod_lot_qty - $post_data['prod_lot_qty'];
					}else{
						// $aborted = $this->Audit_model->get_aborted_lqc($part['id'],$supplier_id);
						
						if($remaining_lot_history_part['lqc_remaining_lot'] > 0){
						
							$lqc_remaining_lot = $remaining_lot_history_part['lqc_remaining_lot'] - $post_data['prod_lot_qty'];
							$remaining_lot_array['lqc_remaining_lot'] = $lqc_remaining_lot;
						
						}						
					}
				}
				else if(count($data['plans']) <= 0) {
					$this->session->set_flashdata('error', 'LQC Production Plan do not Exist for Part - '.$part["name"].'('.$part["code"].').');
                    redirect(base_url().'auditer/register_inspection_lqc');
                }
				
				if(empty($remaining_lot_history_part)){
					if($planned_prod_lot_qty < $post_data['prod_lot_qty']){
						// echo ";
						$this->session->set_flashdata('error', 'Entered Production Lot Quantity('.$post_data['prod_lot_qty'].') exceeds Planned Lot Quantity('.$planned_prod_lot_qty.') for part - '.$part["name"].'('.$part["code"].'). </br>You can inspect equal or less than Planned Lot Quantity.');
						redirect(base_url().'auditer/register_inspection_lqc');
					}
				}
				else{
					if($remaining_lot_history_part['lqc_remaining_lot'] < $post_data['prod_lot_qty']){
						$this->session->set_flashdata('error', 'Entered Production Lot Quantity('.$post_data['prod_lot_qty'].') exceeds Remaining Planned Lot Quantity('.$remaining_lot_history_part['lqc_remaining_lot'].') for part - '.$part["name"].'('.$part["code"].'). </br>You can inspect equal or less than Planned Lot Quantity.');
						redirect(base_url().'auditer/register_inspection_lqc');
					}
				}
				/* print_r($remaining_lot_history_part);
				echo $planned_prod_lot_qty;
				echo $post_data['prod_lot_qty'];
				echo $remaining_lot_history_part['lqc_remaining_lot'];
				exit; */
				
				// exit;
				//$mappings = $this->Product_model->get_defect_code_mappings();
				
				/* $data['defect_code'] = $this->Product_model->get_defect_code_mappings_by_partid($this->product_id,$post_data['part_id'], $this->supplier_id);
				
				if(count($data['defect_code']) <= 0) {
                    $this->session->set_flashdata('error', 'Defect Code for selected Part do not Exist. You can not do Inspection without Defect Code');
                    redirect(base_url().'auditer/register_inspection_lqc');
                } */
				 
				//End check for Exist Plan && Exist Defect
				
                
                $post_data['supplier_id']       = $this->supplier_id;
                $post_data['product_id']        = $this->product_id;
                $post_data['part_no']           = $part['code'];
                $post_data['part_name']         = $part['name'];
                $post_data['register_datetime'] = date('Y-m-d H:i:s');
                $post_data['auditer_id']        = $this->id;
                
                $already = $this->Audit_model->check_already_inspected_lqc($post_data);
                if($already) {
                    $this->session->set_flashdata('error', 'LQC Inspection for this Part has already been done.');
                    redirect(base_url().'auditer/register_inspection_lqc');
                }
                
                $audit_id = $this->Audit_model->update_audit_lqc($post_data, '');
                if($audit_id) {
					// print_r($post_data);exit;
					$this->Lqc_plan_model->change_lqc_plan_status($post_data, 'started');
					// echo $this->db->last_query();exit;
					
					//To maintain remainspected lot history
					$this->Product_model->update_remaining_lot_history($remaining_lot_array);
                    
					$this->session->set_flashdata('success', 'LQC Inspection successfully registered. Please review and click Start Inspection');
                    redirect(base_url().'auditer/inspection_start_screen_lqc');
                } else {
                    $data['error'] = 'Something went wrong. Please try again.';
                }
                
            } else {
                $data['error'] = validation_errors();
            }

        }
        //echo "here2 ";
        $this->load->model('Product_model');
        $data['parts'] = $this->Product_model->get_all_product_parts_by_supplier_as_per_plan($this->product_id, $this->supplier_id);
		$this->template->write('title', 'LQC | LQC Inspection | LQC Register Screen');
        $this->template->write_view('content', 'auditer/register_inspection_lqc', $data);
        $this->template->render();
    }
    private function check_inspection_lqc($audit = '') {
        $audit = ($audit) ? $audit : $this->Audit_model->get_audit_lqc($this->id, array('registered','started', 'finished'));
        // echo $this->db->last_query();exit;
        
        if(empty($audit)) {
            $this->session->set_flashdata('info', 'Please register an LQC inspection, before moving ahead');
            redirect(base_url().'auditer/register_inspection_lqc');
        } else if($audit['state'] === 'registered') {
            $this->session->set_flashdata('info', 'You have already registered an LQC inspection. Please complete it before starting a new registration.');
            redirect(base_url().'auditer/inspection_start_screen_lqc');
        } else if($audit['state'] === 'started') {
			$this->session->set_flashdata('info', 'You have one on going inspection. Please complete it.');
            redirect(base_url().'auditer/continue_inspection_lqc');
        }else if($audit['state'] === 'finished') {
            $this->session->set_flashdata('info', 'You have one LQC inspection in finished queue. Please mark it complete before proceeding ahead.');
            redirect(base_url().'auditer/finish_screen_lqc');
        }
    }
	
	public function inspection_start_screen_lqc() {
        $data = array();
        $this->load->model('Audit_model');
        $this->load->model('User_model');
        $audit = $this->Audit_model->get_audit_lqc($this->id, 'registered');
		
		if(empty($audit)) {
            $this->check_inspection_lqc();
        }
        $this->load->model('Product_model');
        
        $supplier_id = $this->user_type == 'Supplier' ? $this->id : '';
		if($this->user_type == 'Supplier Inspector') {
            $user = $this->User_model->get_supplier_inspector_user($this->id);
			$supplier_id = $user['supplier_id'];
        } 

        $defect_code = $this->Product_model->get_defect_code_mappings_by_partid($audit['product_id'], $audit['part_id']);
		$data['audit'] = $audit;
        $data['defect_code'] = $defect_code;
        
        $this->template->write('title', 'LQC | Product Inspection | Start Screen');
        $this->template->write_view('content', 'auditer/inspection_start_screen_lqc', $data);
        $this->template->render();
    }
	
    public function start_inspection_lqc() {
        
        $this->load->model('Audit_model');
        $this->load->model('User_model');
        $this->load->model('Product_model');
        $audit = $this->Audit_model->get_audit_lqc($this->id, 'registered');
		
		// print_r( $audit);exit;
		
		if(empty($audit)) {
           $this->check_inspection_lqc();
        }
			$this->Audit_model->change_state_lqc($audit['id'], $this->id, 'started');
		if(!isset($audit['remaining_prod_lot_qty'])){
			$this->Audit_model->update_prod_lot_qty($audit['id'],$audit['prod_lot_qty']);
		}
		else if($audit['remaining_prod_lot_qty'] == 0){
			// echo "completed";
			$this->session->set_flashdata('info', 'Lot has Inspected successfully.');
            redirect(base_url().'auditer/register_inspection_lqc');
		}
        
		
		$supplier_id = $this->user_type == 'Supplier' ? $this->id : '';
        if($this->user_type == 'Supplier Inspector') {
            $user = $this->User_model->get_supplier_inspector_user($this->id);
			$supplier_id = $user['supplier_id'];
        }
		
        $defect_code = $this->Product_model->get_defect_code_mappings_by_partid($audit['product_id'], $audit['part_id']);
		
	    $data['defect_code'] = $defect_code;
		$data['audit'] = $audit;
		
        $this->template->write('title', 'LQC | Product Inspection | Start Screen');
        $this->template->write_view('content', 'auditer/lqc_inspection_screen', $data);
        $this->template->render();
    }
	public function continue_inspection_lqc() {
        
        $this->load->model('Audit_model');
        $this->load->model('User_model');
        $this->load->model('Product_model');
        $audit = $this->Audit_model->get_audit_lqc($this->id, 'started');
		
		// $this->Audit_model->change_state_lqc($audit['id'], $this->id, 'started');
		if(!isset($audit['remaining_prod_lot_qty'])){
			$this->Audit_model->update_prod_lot_qty($audit['id'],$audit['prod_lot_qty']);
		}
		else if($audit['remaining_prod_lot_qty'] == 0){
			$this->Audit_model->change_state_lqc($audit['id'], $this->id, 'finished');
			$this->session->set_flashdata('info', 'Lots has Inspected successfully.');
            redirect(base_url().'auditer/register_inspection_lqc');
		}
        
		
		$supplier_id = $this->user_type == 'Supplier' ? $this->id : '';
        if($this->user_type == 'Supplier Inspector') {
            $user = $this->User_model->get_supplier_inspector_user($this->id);
			$supplier_id = $user['supplier_id'];
        }
		
        $defect_code = $this->Product_model->get_defect_code_mappings_by_partid($audit['product_id'], $audit['part_id']);
		
	    // echo "<pre>"; print_r($defect_code);exit;
		
        if($this->user_type == 'Supplier Inspector'){
			$this->load->model('supplier_model');
			$supplier = $this->supplier_model->get_inspector($this->id);
			$supplier_id = $supplier['supplier_id'];
		}
		//$this->Audit_model->change_state_lqc($audit['id'], $this->id, 'registered');//=>comment this
        
        // $this->set_checkpoint_session($audit['id']);
        
        // redirect(base_url().'auditer/lqc_inspection_screen');
		$data['defect_code'] = $defect_code;
		$data['audit'] = $audit;
		
        $this->template->write('title', 'LQC | Product Inspection | Start Screen');
        $this->template->write_view('content', 'auditer/lqc_inspection_screen', $data);
        $this->template->render();
    }
	
	public function mark_as_skip_lqc($audit_id = '') {
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit_lqc($this->id, array('registered','started', 'finished'), '', $audit_id);
		// print_r($audit);exit;
        if(empty($audit)) {
            $this->check_inspection();
        }
        
        $response = $this->Audit_model->change_state_lqc($audit['id'], $this->id, 'skiped');
        if($response) {
            $this->destroy_checkpoint_session();
            $this->session->set_flashdata('success', 'Inspection successfully marked Skipped.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong. Please try again');
        }
        
        redirect($_SERVER['HTTP_REFERER']);
    }
	public function mark_as_retest_lqc($audit_id = '') {
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->get_audit_lqc($this->id, array('registered','started', 'finished'), '', $audit_id);
		// print_r($audit);exit;
        if(empty($audit)) {
            $this->check_inspection_lqc();
        }
        
        $response = $this->Audit_model->change_state_lqc($audit['id'], $this->id, 'retest');
        if($response) {
           // $this->destroy_checkpoint_session();
            $this->session->set_flashdata('success', 'Inspection successfully marked as Retest.');
			redirect(base_url().'auditer/register_inspection_lqc');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong. Please try again');
        }
        
        redirect($_SERVER['HTTP_REFERER']);
    }
	
	public function submit_defect(){
		
		if($this->input->post('result') == 'NG'){
			if(!empty($this->input->post('defect_occured')))
				$defect_occured =  implode(',',$this->input->post('defect_occured'));
			if(!empty($this->input->post('defect_occured_ids')))
				$defect_occured_ids =  implode(',',$this->input->post('defect_occured_ids'));
		}
		$data = array('defect_code' => array());
        
        if($this->input->post('audit_id')){
            $this->load->model('Audit_model');
			if($this->input->post('result') == 'NG'){
				$data['audit_def_id'] = $this->Audit_model->update_audit_defect_code($this->input->post('audit_id'),$defect_occured, $this->input->post('serial_no'),$this->input->post('result'),$defect_occured_ids,$this->input->post('remark'));
			}	
			else if($this->input->post('result') == 'OK'){
				$data['audit_def_id'] = $this->Audit_model->update_audit_defect_code($this->input->post('audit_id'),null, $this->input->post('serial_no'),$this->input->post('result'),null,$this->input->post('remark'));
			}
        }
		if($data['audit_def_id'] > 0){
			$audit = $this->Audit_model->get_audit_lqc($this->id, array('registered','started', 'finished'), '', $this->input->post('audit_id'));
			
			if(!empty($audit)){
				// $this->Audit_model->decrement_production_quantity($audit['id'], );
				if(isset($audit['remaining_prod_lot_qty'])){
					if($audit['remaining_prod_lot_qty']  <=  $audit['prod_lot_qty']){
						$update_prod_lot_qty = $audit['remaining_prod_lot_qty'] - 1;
						$this->Audit_model->update_prod_lot_qty($audit['id'],$update_prod_lot_qty);//=>comment this
						
					}
				} 
			}
		}
        echo json_encode($data);
	}
	public function update_submit_defect() {
		// print_r($this->input->post());exit;
		if($this->input->post('result') == 'NG'){
			if(!empty($this->input->post('defect_occured')))
				$defect_occured =  implode(',',$this->input->post('defect_occured'));
			if(!empty($this->input->post('defect_occured_ids')))
				$defect_occured_ids =  implode(',',$this->input->post('defect_occured_ids'));
		}
		$data = array('audit_def_id' => array());
        
        if($this->input->post('al_id')){
            $this->load->model('Audit_model');
			if($this->input->post('result') == 'NG'){
				$data['audit_def_id'] = $this->Audit_model->review_update_audit_defect_code($defect_occured, $this->input->post('result'),$defect_occured_ids,$this->input->post('remark'),$this->input->post('al_id'));
			}	
			else if($this->input->post('result') == 'OK'){
				$data['audit_def_id'] = $this->Audit_model->review_update_audit_defect_code(null, $this->input->post('result'),null,$this->input->post('remark'),$this->input->post('al_id'));
			}
        }
		
        echo json_encode($data);
	}
	public function retest_update_submit_defect() {
		// print_r($this->input->post());
		if($this->input->post('result') == 'NG'){
			if(!empty($this->input->post('defect_occured')))
				$defect_occured =  implode(',',$this->input->post('defect_occured'));
			if(!empty($this->input->post('defect_occured_ids')))
				$defect_occured_ids =  implode(',',$this->input->post('defect_occured_ids'));
		}
		$data = array('audit_def_id' => array());
        
        if($this->input->post('al_id')){
            $this->load->model('Audit_model');
			if($this->input->post('result') == 'NG'){
				$data['audit_def_id'] = $this->Audit_model->retest_update_audit_defect_code($defect_occured, $this->input->post('result'),$defect_occured_ids,$this->input->post('remark'),$this->input->post('retest_remark'),$this->input->post('al_id'));
			}	
			else if($this->input->post('result') == 'OK'){
				$data['audit_def_id'] = $this->Audit_model->retest_update_audit_defect_code(null, $this->input->post('result'),null,$this->input->post('remark'),$this->input->post('retest_remark'),$this->input->post('al_id'));
			}
			// echo $this->db->last_query();exit;
        }
		
        echo json_encode($data);
	}
	public function check_duplicate_qrcode() {
		// print_r($this->input->post());exit;
		
		$data = array('qr_exist' => array());
        $qr_exist_a = 0;
        if($this->input->post('qr_code')){
            $this->load->model('Audit_model');
			$data['qr_exist'] = $this->Audit_model->check_duplicate_qrcode($this->input->post('qr_code'),$this->input->post('audit_id'));
			// $data['qr_exist'] = $qr_exist_a;
			// echo $data['qr_exist']."=>".$this->db->last_query();
        }
		
        echo json_encode($data);
	}
	public function part_related_qrcode() {
		$data = array('qr_exist' => array());
		$qr_exist_a = 0;
		if($this->input->post('qr_code')){
            $this->load->model('Audit_model');
			$data['qr_exist'] = $this->Audit_model->part_related_qrcode($this->input->post('qr_code'),$this->input->post('part_id'));
			// echo $this->db->last_query();
			$qr_exist_a = count($data['qr_exist']);
        }
        echo json_encode($qr_exist_a);
	}
	public function finish_screen_lqc($audit_id = '') {
        $data = array();
        $this->load->model('Audit_model');
        if(!$audit_id) {
            $audit = $this->Audit_model->get_audit_lqc($this->id, 'finished');
		} else {
            $data['admin_edit_audit'] = $audit_id;
            $audit = $this->Audit_model->get_audit_lqc('', 'completed', '', $audit_id);
        }	
		$data['audit_id'] = $audit['id'];
        $audit_lqc_defect = $this->Audit_model->get_all_audit_lqc_defect_codes($audit['id']);
        $data['audit'] = $audit;

        $data['audit_lqc_defect'] = $audit_lqc_defect;
        // $data['checkpoints_res_ok'] = $checkpoints_res_ok;
        $data['defect_OK'] = $this->Audit_model->get_count_audit_lqc_defect_by_result($audit['id'], 'OK');
        $data['defect_NG'] = $this->Audit_model->get_count_audit_lqc_defect_by_result($audit['id'], 'NG');
        // $data['checkpoints_PD'] = $this->Audit_model->get_count_audit_lqc_defect_by_result($audit['id'], null);
        
		$uri = $_SERVER['REQUEST_URI'];
		
		
		$this->template->write('title', 'LQC | LQC Inspection | Review Screen');
        $this->template->write_view('content', 'auditer/finish_screen_lqc', $data);
        $this->template->render();
        
        
    }
	public function finish_screen_lqc_retest($audit_id = '') {
        $data = array();
        $this->load->model('Audit_model');
        // echo $this->id;exit;
        if(!$audit_id) {
            $audit = $this->Audit_model->get_audit_lqc($this->id, 'finished');
			//$data['admin_edit_audit'] = $audit['id'];
        } else {
            $data['admin_edit_audit'] = $audit_id;
            $audit = $this->Audit_model->get_audit_lqc('', 'completed', '', $audit_id);
        }	
		
		/* $data['admin_edit_audit'] = $audit_id;
		$audit = $this->Audit_model->get_audit_lqc('', 'retest', '', $audit_id); */
        
		$data['audit_id'] = $audit['id'];
        // print_r($audit);exit;
        /*  if(empty($audit)) {
            $this->check_inspection_lqc();
        } */
        
        $audit_lqc_defect = $this->Audit_model->get_all_audit_lqc_defect_codes($audit['id']);
        $data['audit'] = $audit;
		
        $data['audit_lqc_defect'] = $audit_lqc_defect;
        // $data['checkpoints_res_ok'] = $checkpoints_res_ok;
        $data['defect_OK'] = $this->Audit_model->get_count_audit_lqc_defect_by_result($audit['id'], 'OK');
        $data['defect_NG'] = $this->Audit_model->get_count_audit_lqc_defect_by_result($audit['id'], 'NG');
        // $data['checkpoints_PD'] = $this->Audit_model->get_count_audit_lqc_defect_by_result($audit['id'], null);
        
		$uri = $_SERVER['REQUEST_URI'];
		$this->template->write('title', 'LQC | LQC Inspection | Retest Screen');
        $this->template->write_view('content', 'auditer/finish_screen_lqc_retest', $data);
        $this->template->render();
        
        
    }
	
	
	//For LQC Inspection
}
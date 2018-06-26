<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plans extends Admin_Controller {
        
    public function __construct() {
        parent::__construct(false, 'Supplier');
		// echo $this->product_id;exit;
        if(!$this->product_id) {
            redirect(base_url());
        }
        
        $page = 'pp';
        $sampling_configs_fns = array('configs', 'delete_config', 'update_inspection_config');
        if(in_array($this->router->fetch_method(), $sampling_configs_fns)) {
            $page = 'sampling_configs';
        }
        //echo $page;exit;
        //render template
        $this->template->write('title', 'LQC | '.$this->user_type.' Plan');
        $this->template->write_view('header', 'templates/header', array('page' => $page));
        $this->template->write_view('footer', 'templates/footer');
		//for page hits
		$page_new = 'plans';
		$this->hits($page_new);

    }
    
    public function index() {
        $data = array();
        $this->load->model('Plans_model');
        $data['production_plans'] = $this->Plans_model->get_production_plans();
        
        $this->template->write_view('content', 'plans/index', $data);
        $this->template->render();
    }
    
    public function add_production_plan($plan_date) {
        $data = array();
        $this->load->model('Plans_model');
        if(empty($plan_date)) {
            $plan_date = date('Y-m-d', strtotime('+1 day'));
        } else {
            if(strtotime($plan_date) < strtotime(date('Y-m-d'))) {
                $this->session->set_flashdata('error', 'You can\'t upload production plan for past date.');
                redirect(base_url().'sampling/view_production_plan/'.$plan_date);
            }
        }
        
        $data['plan_date'] = $plan_date;
        
        $this->load->model('Product_model');
        // $data['model_suffixs'] = $this->Product_model->get_all_suffixs($this->product_id);
		
		$data['parts'] = $this->Product_model->get_all_product_parts_by_supplier($this->product_id, $this->supplier_id);
		
		if($this->input->post()) {
            $production_plan_date = $this->input->post('plan_date') ? $this->input->post('plan_date') : $plan_date;

            if(strtotime($production_plan_date) >= strtotime(date('Y-m-d'))) {
                $post_data = $this->input->post();
                $post_data['product_id'] = $this->product_id;
                $post_data['plan_date'] = $plan_date;
                $post_data['original_lot_size'] = $post_data['lot_size'];
                $post_data['is_user_defined'] = 1;
            
				//KOmal=>multimodel
				$sel_models = $post_data['model_suffix'];
				foreach($sel_models as $sm){
					$post_data['model_suffix'] = $sm;
					$id = $this->Plans_model->update_product_plan($post_data);
				}
				//Komal=>multimodel
                //Old=>$id = $this->Plans_model->update_product_plan($post_data);
            
                if($id) {
                    $this->session->set_flashdata('success', 'Production Plan successfully '.(($reference_id) ? 'updated' : 'added').'.');
                    redirect(base_url().'sampling/view_production_plan/'.$plan_date);
                } else {
                    $data['error'] = 'Something went wrong, Please try again';
                }
            } else {
                $data['error'] = 'Enter a valid date. Date can\'t be less than Today\'s date.';
            }

        }
        
        $this->template->write_view('content', 'plans/add_production_plan', $data);
        $this->template->render();
    }
    
    public function delete_production_plan($plan_id) {
        $this->load->model('Plans_model');
        $plan = $this->Plans_model->get_production_plan_by_id($plan_id);
        if(empty($plan))
            redirect(base_url().'sampling');
        
        if(strtotime($plan['plan_date']) < strtotime(date('Y-m-d'))) {
            $this->session->set_flashdata('error', 'You can\'t delete production plan for past date.');
            redirect(base_url().'sampling/view_production_plan/'.$plan['plan_date']);
        }

        $deleted = $this->Plans_model->delete_production_plan($plan_id);

        if($deleted) {
            $this->session->set_flashdata('success', 'Record successfully deleted.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
        }
        
        redirect(base_url().'sampling/view_production_plan/'.$plan['plan_date']);
    }
    
    public function view_plan_date() {
        if($this->input->post('view_plan_date')) {
            $from = $_SERVER['HTTP_REFERER'];
            
            $date = $this->input->post('view_plan_date');
            $url = explode('/', $from);
            
            $valid_urls = array('view_production_plan', 'view_sampling_plan');
            if(!in_array($url[count($url)-2], $valid_urls)) {
                redirect($_SERVER['HTTP_REFERER']);
            }
            
            $url[count($url)-1] = $date;
            
            redirect(implode('/', $url));
        }
        
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function view_production_plan($plan_date) {
        $this->load->model('Plans_model');
        
        $production_plan = $this->Plans_model->get_production_plan($plan_date);
        /* if(empty($production_plan)) {
            $this->session->set_flashdata('error', 'Invalid Date.');
            redirect(base_url().'sampling');
        } */
        //echo "<pre>";print_r($production_plan);exit;
        $data['production_plan'] = $production_plan;

        $data['plan_date'] = $plan_date;
        
        $this->template->write_view('content', 'plans/view_production_plan', $data);
        $this->template->render();
    }
    
    public function view_sampling_plan($plan_date) {
        $this->load->model('Plans_model');
        
        $production_plan = $this->Plans_model->get_production_plan($plan_date);
        /* if(empty($production_plan)) {
            $this->session->set_flashdata('error', 'Invalid Date.');
            redirect(base_url().'sampling');
        } */
		// echo $this->db->last_query();exit;
        $data['production_plan'] = $production_plan;

        $sampling_plan          = $this->display_sampling_plan($plan_date);
        $data['sampling_plan']  = $sampling_plan['sampling'];
        $data['plan_date']      = $plan_date;
        // echo "<pre>";print_r($data);exit;
        $this->template->write_view('content', 'plans/view_sampling_plan', $data);
        $this->template->render();
    }
    
    public function edit_sampling_plan($plan_date) {
        $data = array();
        
        $sampling_plan          = $this->display_sampling_plan($plan_date, true);
        $data['sampling_plan']  = $sampling_plan['sampling'];
        $data['ids']            = $sampling_plan['ids'];
        $data['plan_date']      = $plan_date;
        
        //echo "<pre>";
       // echo "<textarea>";
        //print_r($sampling_plan);
       // echo "</textarea>";
       // exit; 
        
        $this->template->write_view('content', 'plans/edit_sampling_plan', $data);
        $this->template->render();
    }
    public function edit_regular_sampling_plan($plan_date) {
        $data = array();
        
        $sampling_plan          = $this->display_regular_sampling_plan($plan_date, false);
        $data['sampling_plan']  = $sampling_plan['sampling'];
        $data['ids']            = $sampling_plan['ids'];
        $data['plan_date']      = $plan_date;
        
        /* echo "<pre>";
       // echo "<textarea>";
        print_r($sampling_plan);
       // echo "</textarea>";
        exit;  */
        
        $this->template->write_view('content', 'plans/edit_regular_sampling_plan', $data);
        $this->template->render();
    }
    
    public function configs() {
        $data = array();
        
        $this->load->model('Inspection_model');
        $this->load->model('Product_model');
        $data['inspections'] = $this->Inspection_model->get_all_inspections_by_product($this->product_id);
        
        $data['lines'] = $this->Product_model->get_all_product_lines($this->product_id);
        $data['tools'] = $this->Product_model->get_all_tools($this->product_id);
        
        $data['model_suffixs'] = $this->Product_model->get_all_suffixs($this->product_id);
        if($this->input->post()) {
			//For multiple model Selection =>Komal
			$model_s = $this->input->post('model_suffix');
			$data['selected_model'] = $model_s;
			//For multiple model Selection =>Komal
            
            $this->load->model('Plans_model');
           /* 
			//Old Code
		   $data['configs'] = $this->Plans_model->get_configs($this->input->post('inspection_id'), $this->input->post('line'),
                $this->input->post('tool'), $this->input->post('model_suffix')
            ); */
			
            //For multiple model Selection =>Komal
			$data['configs'] = $this->Plans_model->get_configs_new(
				$this->input->post('inspection_id'), $this->input->post('line'),
                $this->input->post('tool'), $model_s
            );
			//For multiple model Selection =>Komal

        }
        
        $this->template->write_view('content', 'plans/configs', $data);
        $this->template->render();
    }   
    
    public function delete_config($inspection_id, $config_id) {
        $this->load->model('Plans_model');
        $config = $this->Plans_model->get_inspection_config_by_id($config_id, $this->product_id);
        if(empty($config)) {
            $this->session->set_flashdata('error', 'Invalid record.');
            redirect(base_url().'sampling/configs');
        }
        
        $deleted = $this->Plans_model->delete_config($config_id);
        if($deleted) {
            $this->session->set_flashdata('success', 'Config deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
        }
        
        redirect(base_url().'sampling/configs');
    }
    
    public function update_inspection_config_new($config_id = '') {
        $data = array();
        
        $this->load->model('Plans_model');
        if(!empty($config_id)) {
            $config = $this->Plans_model->get_inspection_config_by_id($config_id, $this->product_id);
            if(empty($config)) {
                $this->session->set_flashdata('error', 'Invalid record.');
                redirect(base_url().'sampling/configs');
            }
            
            $range = $this->Plans_model->get_lot_range_samples($config['id']);
            //echo "<pre>";print_r($range);exit;
            $data['inspection_config'] = $config;
            $data['config_range'] = $range;
        }
        
        $this->load->model('Inspection_model');
        $data['inspections'] = $this->Inspection_model->get_all_inspections_by_product($this->product_id);
        
        $this->load->model('Product_model');
        $data['lines'] = $this->Product_model->get_all_product_lines($this->product_id);
        $data['tools'] = $this->Product_model->get_all_tools($this->product_id);
        $data['model_suffixs'] = $this->Product_model->get_all_model_suffixs($this->product_id);
        
        $lots = $this->Plans_model->get_lot_template();
        $data['lots'] = $lots;
        
        $acceptable_qualities = $this->Plans_model->get_acceptance_qualities();
        $data['acceptable_qualities'] = $acceptable_qualities;
        
        if($this->input->post()) {
            $this->load->library('form_validation');

            $validate = $this->form_validation;
            $validate->set_rules('inspection_id', 'Inspection', 'trim|required|xss_clean');
            $validate->set_rules('inspection_type', 'Inspection', 'trim|required|xss_clean');
            $validate->set_rules('line', 'Line', 'trim|required|xss_clean');
            if($this->input->post('inspection_type') === 'Tool') {
                $validate->set_rules('tool', 'Tool', 'trim|required|xss_clean');
            } else {
                $validate->set_rules('model_suffix', 'Model.Suffix', 'trim|required|xss_clean');
            }
            
            $validate->set_rules('sampling_type', 'Sampling Type', 'trim|required|xss_clean');
            
            if($validate->run() === TRUE) {
                $post_data = $this->input->post();
                
                $type = $this->Plans_model->get_inspection_config_type($post_data['inspection_id']);
                if(!empty($type) && $type != $post_data['inspection_type']) {
                    $data['error'] = "This inspection is already marked as ".$type;
                }
                
                if(!isset($data['error'])) {
                    if($post_data['line'] == 'all') {
                        $post_data['line'] = null;
                    }
                    
                    if(isset($post_data['tool']) && $post_data['tool'] == 'all') {
                        $post_data['tool'] = null;
                    }
                    
                    if(isset($post_data['model_suffix']) && $post_data['model_suffix'] == 'all') {
                        $post_data['model_suffix'] = null;
                    } 
                    
                    if($post_data['sampling_type'] == 'User Defined' || $post_data['sampling_type'] == 'Interval') {
                        $lower_val = isset($post_data['lower_val']) ? $post_data['lower_val']   : array();
                        $higher_val = isset($post_data['higher_val']) ? $post_data['higher_val'] : array();
                        $no_of_samples = isset($post_data['no_of_samples']) ? $post_data['no_of_samples'] : array();
                        
                        if(count($lower_val) !== count($higher_val) || count($lower_val) !== count($no_of_samples)) {
                            $data['error'] = "Please fill lot range properly";
                        }
                    }
                }
                if(!isset($data['error'])) {
                    $post_data['product_id'] = $this->product_id;

                    if($post_data['sampling_type'] != 'Auto') {
                        $post_data['inspection_level'] = null;
                        $post_data['acceptable_quality'] = null;
                    } 

                    if($post_data['sampling_type'] != 'Interval') {
                        $post_data['no_of_months'] = null;
                        $post_data['no_of_times'] = null;
                    }

                    //$this->Plans_model->delete_if_exists_inspection_config($this->product_id, $post_data['inspection_id'], $model_suffix);
					//For Multiple model.suffix =>Komal
					$ms = $post_data['model_suffix'];
					$in_model = array();
					$out_model = array();
					foreach($ms as $m){
						$post_data['model_suffix'] = $m;
						$response_id = $this->Plans_model->update_inspection_config($post_data, $config_id);
						
						if($response_id) {
							$in_model[] = $m;
							if($post_data['sampling_type'] == 'User Defined' || $post_data['sampling_type'] == 'Interval') {
								$this->Plans_model->delete_lot_range_samples($response_id);
								$lot_size = array();
								foreach($lower_val as $key => $val) {
									$temp = array();
									$temp['config_id'] = $response_id;
									$temp['lower_val'] = $val;
									$temp['higher_val'] = $higher_val[$key];
									$temp['no_of_samples'] = $no_of_samples[$key];

									$lot_size[] = $temp;
								}

								$this->Plans_model->insert_lot_range_samples($lot_size, $response_id);
							}
						}
						else{
							$out_model[] = $m;
						}
                    }
					if(sizeof($ms) == sizeof($in_model)) {
					$this->session->set_flashdata('success', 'Record successfully '.(($excluded_id) ? 'updated' : 'added').'.');
					redirect(base_url().'inspections/excluded_checkpoints');
					} 
					else {
						$mm = implode(', ', $out_model);
				
						// $data['error'] = 'Record already exists for this Inspection and Model - .'.$mm;
						$ee = 'Couldnot Update the redord for Model.Suffix - '.$mm.".";
						$this->session->set_flashdata('error', $ee);
						//redirect(base_url().'sampling/configs');
					} 
					//For Multiple model.suffix =>Komal
                    /* 
					//Old Code
					$response_id = $this->Plans_model->update_inspection_config($post_data, $config_id);
                    
                    if($response_id) {
                        if($post_data['sampling_type'] == 'User Defined' || $post_data['sampling_type'] == 'Interval') {
                            $this->Plans_model->delete_lot_range_samples($response_id);
                            $lot_size = array();
                            foreach($lower_val as $key => $val) {
                                $temp = array();
                                $temp['config_id'] = $response_id;
                                $temp['lower_val'] = $val;
                                $temp['higher_val'] = $higher_val[$key];
                                $temp['no_of_samples'] = $no_of_samples[$key];

                                $lot_size[] = $temp;
                            }

                            $this->Plans_model->insert_lot_range_samples($lot_size, $response_id);
                        }
                    } */
                    
                    redirect(base_url().'sampling/configs');
                }
            } else {
                $data['error'] = validation_errors();
            }
            
        }
        
        $this->template->write_view('content', 'plans/update_inspection_config', $data);
        $this->template->render();
    }
	
	public function update_inspection_config($config_id = '') {
        $data = array();
        
        $this->load->model('Plans_model');
        if(!empty($config_id)) {
            $config = $this->Plans_model->get_inspection_config_by_id($config_id, $this->product_id);
            if(empty($config)) {
                $this->session->set_flashdata('error', 'Invalid record.');
                redirect(base_url().'sampling/configs');
            }
            
            $range = $this->Plans_model->get_lot_range_samples($config['id']);
            //echo "<pre>";print_r($range);exit;
            $data['inspection_config'] = $config;
            $data['config_range'] = $range;
        }
        
        $this->load->model('Inspection_model');
        $data['inspections'] = $this->Inspection_model->get_all_inspections_by_product($this->product_id);
        
        $this->load->model('Product_model');
        $data['lines'] = $this->Product_model->get_all_product_lines($this->product_id);
        $data['tools'] = $this->Product_model->get_all_tools($this->product_id);
        $data['model_suffixs'] = $this->Product_model->get_all_model_suffixs($this->product_id);
        
        $lots = $this->Plans_model->get_lot_template();
        $data['lots'] = $lots;
        
        $acceptable_qualities = $this->Plans_model->get_acceptance_qualities();
        $data['acceptable_qualities'] = $acceptable_qualities;
        
        if($this->input->post()) {
            $this->load->library('form_validation');

            $validate = $this->form_validation;
            $validate->set_rules('inspection_id', 'Inspection', 'trim|required|xss_clean');
            $validate->set_rules('inspection_type', 'Inspection', 'trim|required|xss_clean');
            $validate->set_rules('line', 'Line', 'trim|required|xss_clean');
            if($this->input->post('inspection_type') === 'Tool') {
                $validate->set_rules('tool', 'Tool', 'trim|required|xss_clean');
            } else {
                $validate->set_rules('model_suffix', 'Model.Suffix', 'trim|required|xss_clean');
            }
            
            $validate->set_rules('sampling_type', 'Sampling Type', 'trim|required|xss_clean');
            
            if($validate->run() === TRUE) {
                $post_data = $this->input->post();
                
                $type = $this->Plans_model->get_inspection_config_type($post_data['inspection_id']);
                if(!empty($type) && $type != $post_data['inspection_type']) {
                    $data['error'] = "This inspection is already marked as ".$type;
                }
                
                if(!isset($data['error'])) {
                    if($post_data['line'] == 'all') {
                        $post_data['line'] = null;
                    }
                    
                    if(isset($post_data['tool']) && $post_data['tool'] == 'all') {
                        $post_data['tool'] = null;
                    }
                    
                    if(isset($post_data['model_suffix']) && $post_data['model_suffix'] == 'all') {
                        $post_data['model_suffix'] = null;
                    } 
                    
                    if($post_data['sampling_type'] == 'User Defined' || $post_data['sampling_type'] == 'Interval') {
                        $lower_val = isset($post_data['lower_val']) ? $post_data['lower_val']   : array();
                        $higher_val = isset($post_data['higher_val']) ? $post_data['higher_val'] : array();
                        $no_of_samples = isset($post_data['no_of_samples']) ? $post_data['no_of_samples'] : array();
                        
                        if(count($lower_val) !== count($higher_val) || count($lower_val) !== count($no_of_samples)) {
                            $data['error'] = "Please fill lot range properly";
                        }
                    }
                }
                if(!isset($data['error'])) {
                    $post_data['product_id'] = $this->product_id;

                    if($post_data['sampling_type'] != 'Auto') {
                        $post_data['inspection_level'] = null;
                        $post_data['acceptable_quality'] = null;
                    } 

                    if($post_data['sampling_type'] != 'Interval') {
                        $post_data['no_of_months'] = null;
                        $post_data['no_of_times'] = null;
                    }

                    //$this->Plans_model->delete_if_exists_inspection_config($this->product_id, $post_data['inspection_id'], $model_suffix);
                    $response_id = $this->Plans_model->update_inspection_config($post_data, $config_id);
                    
                    if($response_id) {
                        if($post_data['sampling_type'] == 'User Defined' || $post_data['sampling_type'] == 'Interval') {
                            $this->Plans_model->delete_lot_range_samples($response_id);
                            $lot_size = array();
                            foreach($lower_val as $key => $val) {
                                $temp = array();
                                $temp['config_id'] = $response_id;
                                $temp['lower_val'] = $val;
                                $temp['higher_val'] = $higher_val[$key];
                                $temp['no_of_samples'] = $no_of_samples[$key];

                                $lot_size[] = $temp;
                            }

                            $this->Plans_model->insert_lot_range_samples($lot_size, $response_id);
                        }
                    }
                    
                    redirect(base_url().'sampling/configs');
                }
            } else {
                $data['error'] = validation_errors();
            }
            
        }
        
        $this->template->write_view('content', 'plans/update_inspection_config', $data);
        $this->template->render();
    }
    
    public function upload_production_plan($plan_date = '') {
        $data = array();
        
        $this->load->model('Plans_model');
        if(empty($plan_date)) {
            $plan_date = date('Y-m-d', strtotime('+1 day'));
        } else {
            if(strtotime($plan_date) < strtotime(date('Y-m-d'))) {
                $this->session->set_flashdata('error', 'You can\'t upload production plan for past date.');
                redirect(base_url().'sampling');
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
                            $this->create_sampling_plan($production_plan_date);
                            
                            redirect(base_url().'sampling');
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
        
        $this->template->write_view('content', 'plans/upload_production_plan', $data);
        $this->template->render();
    }
    
    public function create_sampling_plan_new($plan_date, $page = '') {
		// echo $plan_date;exit;//2018-02-22
        $allowed_date = date('Y-m-d', strtotime('-7 day'));
        if(strtotime($plan_date) < strtotime($allowed_date)) {
            $this->session->set_flashdata('error', 'Cant edit.');
            
            if($page == 'dashboard') {
                redirect(base_url());
            } else {
                redirect(base_url().'sampling/view_production_plan/'.$plan_date);
            }
        }
        // echo "123";exit;
        $data = array();
        
        $this->load->model('Plans_model');
        $production_plan = $this->Plans_model->get_production_plan($plan_date);
		//print_r($production_plan);exit;
		// Array ( [0] => Array ( [id] => 65318 [product_id] => 1 [plan_date] => 2018-02-26 [line] => L01 [tool] => [model_suffix] => OLED65C7T-T.ATRYLJL [lot_size] => 67 [original_lot_size] => 67 [is_user_defined] => 1 [created] => 2018-02-26 15:58:33 [modified] => )	)
       
	    $str = null;
        $tool_lot = 0;
        $count = 0;
        foreach($production_plan as $k => $plan) {
            $lot_size = $plan['lot_size'];//67
            if(!isset($str)) {
                $str = $plan['tool'].','.$plan['line'];
            }
            // echo $str;exit;//,L01
            $row_str = $plan['tool'].','.$plan['line'];
            if($row_str == $str) {
                $tool_lot += $lot_size;
                $count++;
            }
            // echo $tool_lot."-".$count;exit;//67-1
            if(isset($production_plan[$k+1])) {
                $next_str = $production_plan[$k+1]['tool'].','.$production_plan[$k+1]['line'];
            } else {
                $next_str = 'last';
            }
            // echo $next_str."-".$str;exit;
            if($next_str != $str) {
                $production_plan[$k]['tool_lot'] = $tool_lot;
                $production_plan[$k]['count'] = $count;
                $count = 0;
                $tool_lot = 0;
                $str = $next_str;
            }
        }
        $inspection_configs = $this->Plans_model->get_all_inspection_config($this->product_id);
        
		
		$sampling_plan = array();
        foreach($production_plan as $k => $plan) {
            $lot_size = $plan['lot_size'];
            $specific_configs = $this->Plans_model->get_specific_inspection_config($this->product_id, $plan['model_suffix'], null, $plan['line']);
            $configs = $specific_configs;

        
            $specific_configs = $this->Plans_model->get_specific_inspection_config($this->product_id, $plan['model_suffix'], null, null);
            $configs = array_merge($configs, $specific_configs);
			
            $specific_configs = $this->Plans_model->get_specific_inspection_config($this->product_id, null, null, $plan['line']);
		

		//print_r($specific_configs);
            $configs = array_merge($configs, $specific_configs);
            $configs = array_merge($configs, $inspection_configs);
			/* echo "<pre>";
			print_r($configs);exit;
 */
            $inspections_done = array();
			//print_r($configs);exit;
            foreach($configs as $config) {
                //echo "-------<br />";
                //echo "Model ".$plan['model_suffix'].'<br /> Tool '.$plan['tool'].'<br /> Line '.$plan['line'].'<br /> Inspection '.$config['inspection_id'].'<br /> Type '.$config['inspection_type'].'<br />';
                if(in_array($config['inspection_id'], $inspections_done)) {
                    continue;
                }
                
                $inspections_done[] = $config['inspection_id'];
                //echo "--> Move Ahead".'<br />';
                 // echo $config['inspection_type']."==".$plan['tool_lot'];exit;
                if($config['inspection_type'] == 'Tool' && !isset($plan['tool_lot'])) {
                    //echo "--> Don't Do inspection".'<br />';
                    $temp = array();
                    $temp['lot_id'] = $plan['id'];
                    $temp['product_id'] = $this->product_id;
                    $temp['sampling_date'] = $plan_date;
                    $temp['inspection_id'] = $config['inspection_id'];
                    $temp['config_type'] = $config['inspection_type'];
                    $temp['sampling_type'] = $config['sampling_type'];
                    $temp['line'] = $plan['line'];
                    $temp['tool'] = $plan['tool'];
                    $temp['model_suffix'] = $plan['model_suffix'];
                    $temp['group_count'] = 0;
                    $temp['lot_size'] = $lot_size;
                    $temp['original_lot_size'] = $plan['original_lot_size'];
                    $temp['no_of_samples'] = null;
                    $temp['created'] = date("Y-m-d H:i:s");
                    $temp['skipable'] = 0;
                    
                    $sampling_plan[] = $temp;
					//print_r($sampling_plan);
                    continue;
                }
                // echo "123";exit;
					//production_plan ===> Array ( [0] => Array ( [id] => 65318 [product_id] => 1 [plan_date] => 2018-02-26 [line] => L01 [tool] => [model_suffix] => OLED65C7T-T.ATRYLJL [lot_size] => 67 [original_lot_size] => 67 [is_user_defined] => 1 [created] => 2018-02-26 15:58:33 [modified] =>   ['tool_lot'] = 67  ['count'] = 0;	)	)
					
                $use_lot_size = $lot_size;
                if($config['inspection_type'] == 'Tool') {
                    $use_lot_size = $plan['tool_lot'];
                }
                
                $temp = array();
                $temp['lot_id'] = $plan['id'];
                $temp['product_id'] = $this->product_id;
                $temp['sampling_date'] = $plan_date;
                $temp['inspection_id'] = $config['inspection_id'];
                $temp['config_type'] = $config['inspection_type'];
                $temp['sampling_type'] = $config['sampling_type'];
                //$temp['inspection_name'] = $config['inspection_name'];
                $temp['line'] = $plan['line'];
                $temp['tool'] = $plan['tool'];
                $temp['model_suffix'] = $plan['model_suffix'];
                if(isset($plan['count'])) {
                    $temp['group_count'] = $plan['count'];
                } else {
                    $temp['group_count'] = 0;
                }
                $temp['lot_size'] = $lot_size;
                $temp['original_lot_size'] = $plan['original_lot_size'];
                $temp['created'] = date("Y-m-d H:i:s");
                $temp['skipable'] = 0;
                // echo $config['sampling_type'];exit;
                if($config['sampling_type'] == 'No Inspection') {
                    continue;
                }
                
                if($config['sampling_type'] == 'Auto') {
                    if(!$config['inspection_level'] || !$config['acceptable_quality']) {
                        continue;
                    }

                    $no_of_samples = $this->Plans_model->get_no_of_samples_auto($use_lot_size, $config['inspection_level'], $config['acceptable_quality']);
                    
                    if($use_lot_size == 1) {
                        $no_of_samples = 1;
                    }
                    
                    if($no_of_samples === FALSE){
                        //continue;
                    }
					else{
						$temp['no_of_samples'] = ($no_of_samples > $use_lot_size) ? $use_lot_size : $no_of_samples;
					}
				}

                if($config['sampling_type'] == 'User Defined') {
					// echo $use_lot_size;exit;
                    $no_of_samples = $this->Plans_model->get_no_of_samples($config['id'], $use_lot_size);
                    if($no_of_samples === FALSE){
                        //continue;//Komal to make "change" link visible where no_of_samples exist too
						$temp['no_of_samples'] = null;
                    }
					else{
						$temp['no_of_samples'] = ($no_of_samples > $use_lot_size) ? $use_lot_size : $no_of_samples;
					}
                }

                if($config['sampling_type'] == 'Interval') {
                    $required_times = $config['no_of_times'];
                    
                    $diff = 0;
                    if($config['no_of_months'] != 'Dialy') {
                        // echo "123";exit;
                        if($config['no_of_months'] == 'Weekly') {
                            $current_week = date('Y-\WW');
        
                            $start_date = date("Y-m-d", strtotime("{$current_week}-1")); //Returns the date of monday in week
                            $end_date = date("Y-m-d", strtotime("{$current_week}-7"));   //Returns the date of sunday in week

                        } else if($config['no_of_months'] == 'Bi-Monthly') {
                            $day = date('d');

                            if($day < 15) {
                                $start_date = date('Y-m').'-01';
                                $end_date = date('Y-m').'-15';
                            } else {
                                $start_date = date('Y-m').'-15';
                                $end_date = date('Y-m-t');
                            }

                        } else {
                            $month = $config['no_of_months'];
                            $first_date_of_current = date('Y-m').'-01';
                            $gap = $month - 1;
                           $start_date = date('Y-m-d', strtotime('-'.$gap.' month', strtotime($first_date_of_current)));
                           $end_date = date('Y-m-t');
                        }
                        //exit;
                        $exists = $this->Plans_model->check_sampling_done_interval_insp($start_date, $end_date,
                        $config['inspection_id'], $plan['model_suffix'], $plan['line']);
                        //echo $this->db->last_query();exit;
                        $completed_insp = 0;

                        foreach($exists as $exist) {
                            if($exist['completed'] >= $exist['no_of_samples']) {
                                $completed_insp++;
                            } else {
                                $diff = $exist['no_of_samples'] - $exist['completed'];
                                break;
                            }
                        }
// echo $diff;exit;
                        if($completed_insp >= $required_times) {
                            continue;
                        }

                    }
                    
                    if($diff == 0) {
                        $period_lot = $use_lot_size;

                        /* echo $start_date;
                        echo "<br />===<br />";
                        echo $end_date;
                        echo "<br />===<br />";
                        echo "<pre>";print_r($exists);
                        echo "<br />===<br />";
                        echo $period_lot;
                        exit; */
                        
                        $no_of_samples = $this->Plans_model->get_no_of_samples($config['id'], $period_lot);
                        if($no_of_samples === FALSE)
						{ 
							//continue;//Komal to make "change" link visible where no_of_samples exist too
							$temp['no_of_samples'] = null;
                        }
						else {
							$temp['no_of_samples'] = ($no_of_samples > $period_lot) ? $period_lot : $no_of_samples;
                        }
                        /* echo $this->db->last_query();
                        echo "<br />===<br />";
                        echo $start_date;
                        echo "<br />===<br />";
                        echo $end_date;
                        echo "<br />===<br />";
                        echo "<pre>";print_r($exists);
                        echo "<br />===<br />";
                        echo $period_lot;
                        echo "<br />===<br />";
                        echo "Planned => ".$planned;
                        echo "<br />===<br />";
                        echo $produced;
                        exit; */
                        
                    } else {
                        $temp['no_of_samples'] = $diff;
                    }
                    
                    if($config['no_of_months'] != 'Dialy') {
                        if($config['inspection_type'] == 'Tool') {
                            $period_lot = $this->Plans_model->get_lot_produced_in_range($start_date, date('Y-m-d'), $this->product_id, $plan['line'], '', $plan['tool']);
                        } else {
                            $period_lot = $this->Plans_model->get_lot_produced_in_range($start_date, date('Y-m-d'), $this->product_id, $plan['line'], $plan['model_suffix']);
                        }
                        
                        $start_date_month = date('Y-m-01', strtotime($start_date));
                        if($config['inspection_type'] == 'Tool') {
                            $produced = $this->Plans_model->get_lot_produced_in_range($start_date_month, date('Y-m-d'), $this->product_id, '', '', $plan['tool']);
                            
                            $planned = $this->Plans_model->get_lot_planned_in_range($start_date_month, date('Y-m-d'), $this->product_id, '', $plan['tool']);
                        } else {
                            $produced = $this->Plans_model->get_lot_produced_in_range($start_date_month, date('Y-m-d'), $this->product_id, '', $plan['model_suffix']);
                            
                            $planned = $this->Plans_model->get_lot_planned_in_range($start_date_month, date('Y-m-d'), $this->product_id, $plan['model_suffix']);
                        }
                        
                                                
                        $temp['skipable'] = ($planned - $produced) > 200 ? 1 : 0;
                    } else {
                        $temp['skipable'] = 0;
                    }
                    
                    
                }
                
                $sampling_plan[] = $temp;
            }
		/* echo "<pre>";print_r($sampling_plan);
		exit; 
 */
        }
        
        if(!empty($sampling_plan)) {
            $this->Plans_model->insert_sampling_plan($sampling_plan, $plan_date);
        }
        
        if($page == 'dashboard') {
            redirect(base_url());
        } else {
            redirect(base_url().'sampling/view_sampling_plan/'.$plan_date);
        }
    }
    
    public function create_sampling_plan2($plan_date) {
        $data = array();
        
        $this->load->model('Plans_model');
        $inspection_configs = $this->Plans_model->get_all_inspection_config($this->product_id);
        $production_plan = $this->Plans_model->get_production_plan($plan_date);
        $this->print_array($inspection_configs);
        $sampling_plan = array();
        foreach($production_plan as $plan) {
            $lot_size = $plan['lot_size'];
            $specific_configs = $this->Plans_model->get_model_specific_inspection_config($this->product_id, $plan['model_suffix']);
            
            $specific_configs = array_merge($specific_configs, $inspection_configs);

            $inspections_done = array();
            foreach($specific_configs as $config) {
                if(in_array($config['inspection_id'], $inspections_done)) {
                    continue;
                }
                $inspections_done[] = $config['inspection_id'];
                
                $temp = array();
                $temp['product_id'] = $this->product_id;
                $temp['sampling_date'] = $plan_date;
                $temp['inspection_id'] = $config['inspection_id'];
                //$temp['inspection_name'] = $config['inspection_name'];
                $temp['model_suffix'] = $plan['model_suffix'];
                $temp['created'] = date("Y-m-d H:i:s");
                
                if($config['sampling_type'] == 'No Inspection') {
                    continue;
                }
                
                if($config['sampling_type'] == 'Auto') {
                    if(!$config['inspection_level'] || !$config['acceptable_quality']) {
                        continue;
                    }

                    $no_of_samples = $this->Plans_model->get_no_of_samples_auto($lot_size, $config['inspection_level'], $config['acceptable_quality']);
                    
                    if($no_of_samples === FALSE)
                        continue;
                    
                    $temp['no_of_samples'] = ($no_of_samples > $lot_size) ? $lot_size : $no_of_samples;
                }
                
                if($config['sampling_type'] == 'User Defined' || $config['sampling_type'] == 'Interval') {
                    $no_of_samples = $this->Plans_model->get_no_of_samples($config['id'], $lot_size);
                    if($no_of_samples === FALSE)
                        continue;
                    
                    $temp['no_of_samples'] = $no_of_samples;
                }
                
                $sampling_plan[] = $temp;
            }
        }
        
        if(!empty($sampling_plan)) {
            $this->Plans_model->insert_sampling_plan($sampling_plan, $plan_date);
        }
    }
    
    public function fetch_production_plan() {
        $formatted_date = date('Ymd');
        $production_plan_date = date('Y-m-d');
        
        require_once APPPATH .'libraries/lg_oracle.php';
        $output = get_production_plan($formatted_date);
        
        if(empty($output)) {
            $this->session->set_flashdata('error', 'No Production Plan found.');
            redirect(base_url().'sampling');
        }
        
        $this->load->model('Plans_model');
        $this->Plans_model->insert_production_plan_automatic($output);
        
        $this->Plans_model->create_automatic_production_plan($production_plan_date, $formatted_date);

        $this->create_sampling_plan($production_plan_date);
    }
    
    public function production_plan($id) {
        $this->load->model('Plans_model');
        $this->load->model('Product_model');
        $product_lines = $this->Product_model->get_all_product_lines($this->product_id);
		
		if(!empty($this->input->post())){
			if(!empty($this->input->post('lot_size'))) {
				
				$update_data = array(
					'lot_size' => $this->input->post('lot_size')
				);
				$result = $this->Plans_model->update_product_plan($update_data, $id);
				$plan = $this->Plans_model->get_production_plan_by_id($id);
				if($result) {
					$response['status'] = 'success';
					$response['html'] = '<span style="font-size:15px;">'.$plan['lot_size'].'</span>';
					if($plan['lot_size'] != $plan['original_lot_size']) {
						$response['html'] .= ' <small style="text-decoration:line-through;">'.$plan['original_lot_size'].'</small>';
					}
				} else {
					$response['status'] = 'error';
				}
				
				
				echo json_encode($response);
			}
			else if($this->input->post('lot_split_adjust') == 'lot_split'){

				//Array ( [lot_split_adjust] => lot_split [lot_size] => [line_L01] => 2 [line_PL1] => 2 [line_PL2] => 3 [line_PL3] => 5 )
				
				$p = $this->input->post();
				$pp = array_slice($p,2);
				$lot = $this->Plans_model->get_production_plan_by_id($id);
				// print_r($lot);exit;
				
				$lines = array();
				$update_line = array();
				
				$add = 0;
				foreach($pp as $pl_key => $line_val){
					if(!empty($line_val)){
						$new_key = str_replace("line_","",$pl_key);
						$lines[$new_key] = $line_val;
						
						$add = $add + $line_val;
					}
				} 
				 /* print_r($lot);
					echo $lot['lot_size'];
					echo $add;exit; 
				 */	
				if($add != $lot['lot_size']){
					
					//$response['status'] = 'error';
				}
				else{
				
					$i = 0;					
					foreach($lines as $line_k => $val){
						
						//$res = $this->Plans_model->get_production_plan_by_line_model_date($line_k,$lot['model_suffix'],$lot['plan_date']);
								//Insert Array
								$update_line[$i]['product_id'] = $lot['product_id'];
								$update_line[$i]['plan_date'] = $lot['plan_date'];
								$update_line[$i]['line'] = $line_k;
								$update_line[$i]['tool'] = $lot['tool'];
								$update_line[$i]['model_suffix'] = $lot['model_suffix'];
								$update_line[$i]['lot_size'] = $val;
								$update_line[$i]['original_id'] = $lot['id'];
								$update_line[$i]['is_user_defined'] = $lot['is_user_defined'];
								
							
							$i++;
						}
					foreach($update_line as $ul){
						$result1 = $this->Plans_model->update_product_plan($ul);						
						if($result1) {
							$response['status'] = 'success';
							$response['html'] .= ' <small style="text-decoration:line-through;">'.$lot['lot_size'].'</small>';
							
						} else {
							$response['status'] = 'error';
						}
					}
					
					if($response['status'] == 'success'){
						$this->delete_production_plan($id);
					}
					//$plan = $this->Plans_model->get_production_plan_by_id($id);
				}
				/* if($response['status'] == 'success'){
					$this->session->set_flashdata('success', 'Data Successfully updated');
					redirect(base_url().'sampling/view_production_plan/'.$plan['plan_date']);
				}
				if($response['status'] == 'error'){
					$this->session->set_flashdata('error', 'Data cannot be update');
					redirect(base_url().'sampling/view_production_plan/'.$plan['plan_date']);					
				} */
				print_r($response);exit;
				echo json_encode($response); 
			}
		}
		else {
			$data['product_lines'] = $product_lines;
            $data['plan'] = $this->Plans_model->get_production_plan_by_id($id);
            echo $this->load->view('plans/production_plan_ajax', $data);
        }
        return false;
    }
    
    public function sort_inspections() {
        $data = array();
        
        $this->load->model('Inspection_model');
        
        if($this->input->post()) {
            $sort_index = $this->input->post('sort_index');
            
            foreach($sort_index as $insp_id => $index) {
                if(empty($index)) {
                    continue;
                }
                
                $up_data = array();
                $up_data['sort_index'] = $index;
                
                $this->Inspection_model->add_inspection($up_data, $insp_id);
            }
            
            $this->session->set_flashdata('success', 'Data Successfully updated');
        }
        
        $inspections = $this->Inspection_model->get_all_inspections_by_product($this->product_id);
        
        $inspections = array_chunk($inspections, ceil(count($inspections)/2));
        $data['inspection1'] = $inspections[0];
        $data['inspection2'] = $inspections[1];
        
        $this->template->write_view('content', 'plans/sort_inspections', $data);
        $this->template->render();
    }

    public function sampling_plan($id) {
        
        $this->load->model('Plans_model');
        if($this->input->post()) {
            if($this->input->post('skip')) {
                $no_of_samples = null;
                $result = $this->Plans_model->skip_sampling_plan($id);
            } else {
                $no_of_samples = $this->input->post('no_of_samples');
                if($no_of_samples > 0 ) {
                    $result = $this->Plans_model->update_sampling_plan($no_of_samples, $id);
                }
            }
            
            if($result) {
                $response['status'] = 'success';
                if($this->input->post('skip')) {
                    $response['html'] = '<small><a class="sampling-plan-'.$id.'" href="'.base_url().'sampling/sampling_plan/'.$id.'" data-target="#adjust-sampling-modal" data-toggle="modal"></br>(Update)</a></small>';
                } else {
                    $response['html'] = $no_of_samples.' <small><a class="sampling-plan-'.$id.'" href="'.base_url().'sampling/sampling_plan/'.$id.'" data-target="#adjust-sampling-modal" data-toggle="modal"></br>(Update)</a></small>';
                }
            } else {
                $response['status'] = 'error';
            }
            
            echo json_encode($response);
        } else {
            $plan = $this->Plans_model->get_sampling_plan_by_id($id);
            $planned_qty = $this->Plans_model->get_model_planned_n_produced($plan['model_suffix'], date('Y-m-01', strtotime($plan['sampling_date'])));

            $data['stats'] = $planned_qty;
            $data['plan'] = $plan;
            echo $this->load->view('plans/sampling_plan_ajax', $data);
        }

        return false;
    }
    
    public function upload_production_plan_monthly() {
        $data = array();
        
        $this->load->model('Plans_model');
        
        if($this->input->post('plan')) {

            if(!empty($_FILES['production_plan_excel']['name'])) {
                $output = $this->upload_file('production_plan_excel', time(), "assets/production_plan/");

                if($output['status'] == 'success') {
                    $res = $this->read_production_plan_monthly_excel($output['file']);
                    if($res) {
                        redirect(base_url().'sampling/production_plan_monthly');
                    } else {
                        $data['error'] = 'Incorrect Format, Please check.';
                    }
                } else {
                    $data['error'] = $output['error'];
                }
            }
            
        }
        
        $this->template->write_view('content', 'plans/upload_production_plan_monthly', $data);
        $this->template->render();
    }
    
    public function production_plan_monthly() {
        $data = array();
        
        $this->load->model('Plans_model');
        $plan_month = $this->input->post('plan_month') ? $this->input->post('plan_month') : date('Y-m').'-01';
        $data['plan_month'] = $plan_month;
        $data['plans'] = $this->Plans_model->get_production_plan_monthly($plan_month);
        
        $this->template->write_view('content', 'plans/production_plan_monthly', $data);
        $this->template->render();
    }
    
    public function add_production_plan_monthly() {
        $data = array();
        $this->load->model('Plans_model');
        
        $this->load->model('Product_model');
        $data['model_suffixs'] = $this->Product_model->get_all_suffixs($this->product_id);
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $post_data['product_id'] = $this->product_id;

            //Old=>$id = $this->Plans_model->update_production_plan_monthly($post_data);
			//KOmal=>multimodel
			$sel_models = $post_data['model_suffix'];
			// print_r($sel_models);exit;
			$in_model = array();
			$out_model = array();
			foreach($sel_models as $sm){
				$post_data['model_suffix'] = $sm;
				$id = $this->Plans_model->update_production_plan_monthly($post_data);
				// echo $this->db->last_query();exit;
				if($id){
					//echo "'20MN47A-PT.BTRMLPL"." added";exit;
					$in_model[] = $sm;
					$this->Plans_model->update_production_tool_monthly($this->input->post('plan_month'));
				}
				else{					
					$out_model[] = $sm;
				}
			}
			// print_r($in_model);exit;
			if(sizeof($sel_models) == sizeof($in_model)) {
				$this->session->set_flashdata('success', 'Production Plan successfully for selected Model.Suffix'.(($id) ? 'updated' : 'added').'.');
				redirect(base_url().'sampling/production_plan_monthly/');
			}
            else {
				$mm = implode(', ', $out_model);
                //$data['error'] = 'Something went wrong, Please try again';
				$ee = 'Record Couldnot be added for the Model - '.$mm.".";
					$this->session->set_flashdata('error', $ee);
					redirect(base_url().'sampling/production_plan_monthly');
            }
			//KOmal=>multimodel

        }
        
        $this->template->write_view('content', 'plans/add_production_plan_monthly', $data);
        $this->template->render();
    }
    
    public function delete_production_plan_monthly($id) {
        $this->load->model('Plans_model');
        $plan = $this->Plans_model->get_production_plan_monthly_by_id($id);
        if(empty($plan)) {
            $this->session->set_flashdata('error', 'Invalid record.');
            redirect(base_url().'sampling/production_plan_monthly');
        }
        
        $deleted = $this->Plans_model->delete_production_plan_monthly($id);
        if($deleted) {
            $this->session->set_flashdata('success', 'Plan deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
        }
        
        redirect(base_url().'sampling/production_plan_monthly');
    }
    
    private function read_production_plan_excel($production_plan_date, $file_name) {
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file_name);
        
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $arr = $objPHPExcel->getActiveSheet()->toArray(null, true,true,true);
        //echo "<pre>";print_r($arr);exit;
        if(empty($arr) || !isset($arr[1]) || count($arr[1]) < 5) {
            return FALSE;
        }
        
        $production_plan = array();
        foreach($arr as $no => $row) {
            if($no == 1)
                continue;
            
            if(!trim($row['A']))
                continue;

            if(count($row) < 5)
                continue;
            
            $temp = array();
            $temp['product_id'] = $this->product_id;
            $temp['plan_date'] = $production_plan_date;
            $temp['line'] = $row['C'];
            $temp['model_suffix'] = $row['D'];
            $temp['lot_size'] = $row['E'];
            $temp['original_lot_size'] = $row['E'];
            $temp['created'] = date("Y-m-d H:i:s");
            
            $production_plan[] = $temp;
        }
        
        if(!empty($production_plan)) {
            $this->load->model('Plans_model');
            $this->Plans_model->insert_production_plan($production_plan, $production_plan_date);
            
            return TRUE;
        } else {
            return FALSE;
        }
        
    }
    
    private function read_production_plan_monthly_excel($file_name) {
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file_name);
        
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $arr = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        
        //echo date_create_from_format("d M'y", "05 Feb'16")->format('Y-m-d');exit;
        if(empty($arr) || !isset($arr[1])) {
            return FALSE;
        }
        
        $headers = array();
        $production_plan = array();
        
        foreach($arr[1] as $k => $th) {
            if($k === 'A') {
                $headers[$k] = $th;
            } else {
                $headers[$k] = date_create_from_format("d M'y", '01 '.$th)->format('Y-m-d');
            }
        }
        
        foreach($arr as $no => $row) {
            if($no == 1) {
                continue;
            }
            
            if(!trim($row['A']))
                continue;
            
            $model_suffix = $row['A'];
            
            
            foreach($row as $index => $val) {
                if($index === 'A') {
                    continue;
                }
                
                if(!isset($headers[$index])) {
                    continue;
                }
                
                $temp = array();
                $temp['product_id'] = $this->product_id;
                $temp['plan_month'] = $headers[$index];
                $temp['model_suffix'] = $model_suffix;
                $temp['lot_size'] = $val;
                $temp['created'] = date("Y-m-d H:i:s");
                
                $production_plan[] = $temp;
            }
        }
        
        if(!empty($production_plan)) {
            $this->load->model('Plans_model');
            $this->Plans_model->insert_production_plan_monthly($production_plan);

            return TRUE;
        } else {
            return FALSE;
        }
        
    }
	
    //Phase : 5 =>Komal
	public function edit_production_plan_monthly($id) {
        $data = array();
        $this->load->model('Plans_model');
		$plan = $this->Plans_model->get_production_plan_monthly_by_id($id);
		if(empty($plan)) {
            $this->session->set_flashdata('error', 'Invalid record.');
            redirect(base_url().'sampling/production_plan_monthly');
        }		
        $this->load->model('Product_model');
        $data['plan']	=	$plan;
        if($this->input->post()) {
			//print_r($this->input->post());exit;
            $post_data = $this->input->post();
        	$idd = $this->Plans_model->update_production_plan_monthly_edit($post_data,$id);
			if($idd){
				
				$this->session->set_flashdata('success', 'Plan Edited successfully.');
			} else {
				$this->session->set_flashdata('error', 'Something went wrong, please try again.');
			}
			redirect(base_url().'sampling/production_plan_monthly');
			
		}
        
        $this->template->write_view('content', 'plans/edit_production_plan_monthly', $data);
        $this->template->render();
    }
	//Phase : 5 =>Komal
	public function create_sampling_plan($plan_date, $page = '') {
		// echo $plan_date;exit;//2018-02-22
        $allowed_date = date('Y-m-d', strtotime('-7 day'));
        if(strtotime($plan_date) < strtotime($allowed_date)) {
            $this->session->set_flashdata('error', 'Cant edit.');
            
            if($page == 'dashboard') {
                redirect(base_url());
            } else {
                redirect(base_url().'sampling/view_production_plan/'.$plan_date);
            }
        }
        // echo "123";exit;
        $data = array();
        
        $this->load->model('Plans_model');
        $production_plan = $this->Plans_model->get_production_plan($plan_date);
		//print_r($production_plan);exit;
		// Array ( [0] => Array ( [id] => 65318 [product_id] => 1 [plan_date] => 2018-02-26 [line] => L01 [tool] => [model_suffix] => OLED65C7T-T.ATRYLJL [lot_size] => 67 [original_lot_size] => 67 [is_user_defined] => 1 [created] => 2018-02-26 15:58:33 [modified] => )	)
       
	    $str = null;
        $tool_lot = 0;
        $count = 0;
        foreach($production_plan as $k => $plan) {
            $lot_size = $plan['lot_size'];//67
            if(!isset($str)) {
                $str = $plan['tool'].','.$plan['line'];
            }
            // echo $str;exit;//,L01
            $row_str = $plan['tool'].','.$plan['line'];
            if($row_str == $str) {
                $tool_lot += $lot_size;
                $count++;
            }
            // echo $tool_lot."-".$count;exit;//67-1
            if(isset($production_plan[$k+1])) {
                $next_str = $production_plan[$k+1]['tool'].','.$production_plan[$k+1]['line'];
            } else {
                $next_str = 'last';
            }
            // echo $next_str."-".$str;exit;
            if($next_str != $str) {
                $production_plan[$k]['tool_lot'] = $tool_lot;
                $production_plan[$k]['count'] = $count;
                $count = 0;
                $tool_lot = 0;
                $str = $next_str;
            }
        }
        $inspection_configs = $this->Plans_model->get_all_inspection_config($this->product_id);
        
		
		$sampling_plan = array();
        foreach($production_plan as $k => $plan) {
            $lot_size = $plan['lot_size'];
            $specific_configs = $this->Plans_model->get_specific_inspection_config($this->product_id, $plan['model_suffix'], null, $plan['line']);
            $configs = $specific_configs;

        
            $specific_configs = $this->Plans_model->get_specific_inspection_config($this->product_id, $plan['model_suffix'], null, null);
            $configs = array_merge($configs, $specific_configs);
			
            $specific_configs = $this->Plans_model->get_specific_inspection_config($this->product_id, null, null, $plan['line']);
		

		//print_r($specific_configs);
            $configs = array_merge($configs, $specific_configs);
            $configs = array_merge($configs, $inspection_configs);
			/* echo "<pre>";
			print_r($configs);exit;
 */
            $inspections_done = array();
			//print_r($configs);exit;
            foreach($configs as $config) {
                //echo "-------<br />";
                //echo "Model ".$plan['model_suffix'].'<br /> Tool '.$plan['tool'].'<br /> Line '.$plan['line'].'<br /> Inspection '.$config['inspection_id'].'<br /> Type '.$config['inspection_type'].'<br />';
                if(in_array($config['inspection_id'], $inspections_done)) {
                    continue;
                }
                
                $inspections_done[] = $config['inspection_id'];
                //echo "--> Move Ahead".'<br />';
                 // echo $config['inspection_type']."==".$plan['tool_lot'];exit;
                if($config['inspection_type'] == 'Tool' && !isset($plan['tool_lot'])) {
                    //echo "--> Don't Do inspection".'<br />';
                    $temp = array();
                    $temp['lot_id'] = $plan['id'];
                    $temp['product_id'] = $this->product_id;
                    $temp['sampling_date'] = $plan_date;
                    $temp['inspection_id'] = $config['inspection_id'];
                    $temp['config_type'] = $config['inspection_type'];
                    $temp['sampling_type'] = $config['sampling_type'];
                    $temp['line'] = $plan['line'];
                    $temp['tool'] = $plan['tool'];
                    $temp['model_suffix'] = $plan['model_suffix'];
                    $temp['group_count'] = 0;
                    $temp['lot_size'] = $lot_size;
                    $temp['original_lot_size'] = $plan['original_lot_size'];
                    $temp['no_of_samples'] = null;
                    $temp['created'] = date("Y-m-d H:i:s");
                    $temp['skipable'] = 0;
                    
                    $sampling_plan[] = $temp;
					//print_r($sampling_plan);
                    continue;
                }
                // echo "123";exit;
					//production_plan ===> Array ( [0] => Array ( [id] => 65318 [product_id] => 1 [plan_date] => 2018-02-26 [line] => L01 [tool] => [model_suffix] => OLED65C7T-T.ATRYLJL [lot_size] => 67 [original_lot_size] => 67 [is_user_defined] => 1 [created] => 2018-02-26 15:58:33 [modified] =>   ['tool_lot'] = 67  ['count'] = 0;	)	)
					
                $use_lot_size = $lot_size;
                if($config['inspection_type'] == 'Tool') {
                    $use_lot_size = $plan['tool_lot'];
                }
                
                $temp = array();
                $temp['lot_id'] = $plan['id'];
                $temp['product_id'] = $this->product_id;
                $temp['sampling_date'] = $plan_date;
                $temp['inspection_id'] = $config['inspection_id'];
                $temp['config_type'] = $config['inspection_type'];
                $temp['sampling_type'] = $config['sampling_type'];
                //$temp['inspection_name'] = $config['inspection_name'];
                $temp['line'] = $plan['line'];
                $temp['tool'] = $plan['tool'];
                $temp['model_suffix'] = $plan['model_suffix'];
                if(isset($plan['count'])) {
                    $temp['group_count'] = $plan['count'];
                } else {
                    $temp['group_count'] = 0;
                }
                $temp['lot_size'] = $lot_size;
                $temp['original_lot_size'] = $plan['original_lot_size'];
                $temp['created'] = date("Y-m-d H:i:s");
                $temp['skipable'] = 0;
                // echo $config['sampling_type'];exit;
                if($config['sampling_type'] == 'No Inspection') {
                    continue;
                }
                
                if($config['sampling_type'] == 'Auto') {
                    if(!$config['inspection_level'] || !$config['acceptable_quality']) {
                        continue;
                    }

                    $no_of_samples = $this->Plans_model->get_no_of_samples_auto($use_lot_size, $config['inspection_level'], $config['acceptable_quality']);
                    
                    if($use_lot_size == 1) {
                        $no_of_samples = 1;
                    }
                    
                    if($no_of_samples === FALSE)
                        continue;
                    
                    $temp['no_of_samples'] = ($no_of_samples > $use_lot_size) ? $use_lot_size : $no_of_samples;
                }

                if($config['sampling_type'] == 'User Defined') {
					// echo $use_lot_size;exit;
                    $no_of_samples = $this->Plans_model->get_no_of_samples($config['id'], $use_lot_size);
                    if($no_of_samples === FALSE)
                        continue;//Komal Uncoment It=>test
                    
                    $temp['no_of_samples'] = ($no_of_samples > $use_lot_size) ? $use_lot_size : $no_of_samples;
                }

                if($config['sampling_type'] == 'Interval') {
                    $required_times = $config['no_of_times'];
                    
                    $diff = 0;
                    if($config['no_of_months'] != 'Dialy') {
                        // echo "123";exit;
                        if($config['no_of_months'] == 'Weekly') {
                            $current_week = date('Y-\WW');
        
                            $start_date = date("Y-m-d", strtotime("{$current_week}-1")); //Returns the date of monday in week
                            $end_date = date("Y-m-d", strtotime("{$current_week}-7"));   //Returns the date of sunday in week

                        } else if($config['no_of_months'] == 'Bi-Monthly') {
                            $day = date('d');

                            if($day < 15) {
                                $start_date = date('Y-m').'-01';
                                $end_date = date('Y-m').'-15';
                            } else {
                                $start_date = date('Y-m').'-15';
                                $end_date = date('Y-m-t');
                            }

                        } else {
                            $month = $config['no_of_months'];
                            $first_date_of_current = date('Y-m').'-01';
                            $gap = $month - 1;
                           $start_date = date('Y-m-d', strtotime('-'.$gap.' month', strtotime($first_date_of_current)));
                           $end_date = date('Y-m-t');
                        }
                        //exit;
                        $exists = $this->Plans_model->check_sampling_done_interval_insp($start_date, $end_date,
                        $config['inspection_id'], $plan['model_suffix'], $plan['line']);
                        //echo $this->db->last_query();exit;
                        $completed_insp = 0;

                        foreach($exists as $exist) {
                            if($exist['completed'] >= $exist['no_of_samples']) {
                                $completed_insp++;
                            } else {
                                $diff = $exist['no_of_samples'] - $exist['completed'];
                                break;
                            }
                        }
// echo $diff;exit;
                        if($completed_insp >= $required_times) {
                            continue;
                        }

                    }
                    
                    if($diff == 0) {
                        $period_lot = $use_lot_size;

                        /* echo $start_date;
                        echo "<br />===<br />";
                        echo $end_date;
                        echo "<br />===<br />";
                        echo "<pre>";print_r($exists);
                        echo "<br />===<br />";
                        echo $period_lot;
                        exit; */
                        
                        $no_of_samples = $this->Plans_model->get_no_of_samples($config['id'], $period_lot);
                        if($no_of_samples === FALSE)
                            continue;
                        
                        /* echo $this->db->last_query();
                        echo "<br />===<br />";
                        echo $start_date;
                        echo "<br />===<br />";
                        echo $end_date;
                        echo "<br />===<br />";
                        echo "<pre>";print_r($exists);
                        echo "<br />===<br />";
                        echo $period_lot;
                        echo "<br />===<br />";
                        echo "Planned => ".$planned;
                        echo "<br />===<br />";
                        echo $produced;
                        exit; */
                        
                        $temp['no_of_samples'] = ($no_of_samples > $period_lot) ? $period_lot : $no_of_samples;
                    } else {
                        $temp['no_of_samples'] = $diff;
                    }
                    
                    if($config['no_of_months'] != 'Dialy') {
                        if($config['inspection_type'] == 'Tool') {
                            $period_lot = $this->Plans_model->get_lot_produced_in_range($start_date, date('Y-m-d'), $this->product_id, $plan['line'], '', $plan['tool']);
                        } else {
                            $period_lot = $this->Plans_model->get_lot_produced_in_range($start_date, date('Y-m-d'), $this->product_id, $plan['line'], $plan['model_suffix']);
                        }
                        
                        $start_date_month = date('Y-m-01', strtotime($start_date));
                        if($config['inspection_type'] == 'Tool') {
                            $produced = $this->Plans_model->get_lot_produced_in_range($start_date_month, date('Y-m-d'), $this->product_id, '', '', $plan['tool']);
                            
                            $planned = $this->Plans_model->get_lot_planned_in_range($start_date_month, date('Y-m-d'), $this->product_id, '', $plan['tool']);
                        } else {
                            $produced = $this->Plans_model->get_lot_produced_in_range($start_date_month, date('Y-m-d'), $this->product_id, '', $plan['model_suffix']);
                            
                            $planned = $this->Plans_model->get_lot_planned_in_range($start_date_month, date('Y-m-d'), $this->product_id, $plan['model_suffix']);
                        }
                        
                                                
                        $temp['skipable'] = ($planned - $produced) > 200 ? 1 : 0;
                    } else {
                        $temp['skipable'] = 0;
                    }
                    
                    
                }
                
                $sampling_plan[] = $temp;
            }
		
        }
        
        if(!empty($sampling_plan)) {
            $this->Plans_model->insert_sampling_plan($sampling_plan, $plan_date);
        }
        
        if($page == 'dashboard') {
            redirect(base_url());
        } else {
            redirect(base_url().'sampling/view_sampling_plan/'.$plan_date);
        }
    }
	
    public function create_sampling_plan_individual($id, $page = '') {
		// echo $plan_date;exit;//2018-02-22
        $this->load->model('Plans_model');
		
		$production_plan = $this->Plans_model->get_production_plan_by_id_new($id);
		
		$plan_date = $production_plan[0]['plan_date'];
        
		$allowed_date = date('Y-m-d', strtotime('-7 day'));
        
		if(strtotime($plan_date) < strtotime($allowed_date)) {
            $this->session->set_flashdata('error', 'Cant edit.');
            redirect(base_url().'sampling/view_production_plan/'.$plan_date);            
        }
        // echo "123";exit;
        $data = array();
        
        //$production_plan = $this->Plans_model->get_production_plan($plan_date);
		// print_r($production_plan);exit; 
		// Array ( [0] => Array ( [id] => 65318 [product_id] => 1 [plan_date] => 2018-02-26 [line] => L01 [tool] => [model_suffix] => OLED65C7T-T.ATRYLJL [lot_size] => 67 [original_lot_size] => 67 [is_user_defined] => 1 [created] => 2018-02-26 15:58:33 [modified] => )	)
       
	    $str = null;
        $tool_lot = 0;
        $count = 0;
        foreach($production_plan as $k => $plan) {
            $lot_size = $plan['lot_size'];//67
            if(!isset($str)) {
                $str = $plan['tool'].','.$plan['line'];
            }
            // echo $str;exit;//,L01
            $row_str = $plan['tool'].','.$plan['line'];
            if($row_str == $str) {
                $tool_lot += $lot_size;
                $count++;
            }
            // echo $tool_lot."-".$count;exit;//67-1
            if(isset($production_plan[$k+1])) {
                $next_str = $production_plan[$k+1]['tool'].','.$production_plan[$k+1]['line'];
            } else {
                $next_str = 'last';
            }
            // echo $next_str."-".$str;exit;
            if($next_str != $str) {
                $production_plan[$k]['tool_lot'] = $tool_lot;
                $production_plan[$k]['count'] = $count;
                $count = 0;
                $tool_lot = 0;
                $str = $next_str;
            }
        }
        $inspection_configs = $this->Plans_model->get_all_inspection_config($this->product_id);
        
		// echo "<pre>"; print_r($inspection_configs);exit;
		$sampling_plan = array();
        foreach($production_plan as $k => $plan) {
            $lot_size = $plan['lot_size'];
            $specific_configs = $this->Plans_model->get_specific_inspection_config($this->product_id, $plan['model_suffix'], null, $plan['line']);
            $configs = $specific_configs;

        
            $specific_configs = $this->Plans_model->get_specific_inspection_config($this->product_id, $plan['model_suffix'], null, null);
            $configs = array_merge($configs, $specific_configs);
			
            $specific_configs = $this->Plans_model->get_specific_inspection_config($this->product_id, null, null, $plan['line']);
		

		//print_r($specific_configs);
            $configs = array_merge($configs, $specific_configs);
            $configs = array_merge($configs, $inspection_configs);
			/* echo "<pre>";
			print_r($configs);exit;
 */
            $inspections_done = array();
			//print_r($configs);exit;
            foreach($configs as $config) {
                //echo "-------<br />";
                //echo "Model ".$plan['model_suffix'].'<br /> Tool '.$plan['tool'].'<br /> Line '.$plan['line'].'<br /> Inspection '.$config['inspection_id'].'<br /> Type '.$config['inspection_type'].'<br />';
                if(in_array($config['inspection_id'], $inspections_done)) {
                    continue;
                }
                
                $inspections_done[] = $config['inspection_id'];
                //echo "--> Move Ahead".'<br />';
                 // echo $config['inspection_type']."==".$plan['tool_lot'];exit;
                if($config['inspection_type'] == 'Tool' && !isset($plan['tool_lot'])) {
                    //echo "--> Don't Do inspection".'<br />';
                    $temp = array();
                    $temp['lot_id'] = $plan['id'];
                    $temp['product_id'] = $this->product_id;
                    $temp['sampling_date'] = $plan_date;
                    $temp['inspection_id'] = $config['inspection_id'];
                    $temp['config_type'] = $config['inspection_type'];
                    $temp['sampling_type'] = $config['sampling_type'];
                    $temp['line'] = $plan['line'];
                    $temp['tool'] = $plan['tool'];
                    $temp['model_suffix'] = $plan['model_suffix'];
                    $temp['group_count'] = 0;
                    $temp['lot_size'] = $lot_size;
                    $temp['original_lot_size'] = $plan['original_lot_size'];
                    $temp['no_of_samples'] = null;
                    $temp['created'] = date("Y-m-d H:i:s");
                    $temp['skipable'] = 0;
                    
                    $sampling_plan[] = $temp;
					//print_r($sampling_plan);
                    continue;
                }
                // echo "123";exit;
					//production_plan ===> Array ( [0] => Array ( [id] => 65318 [product_id] => 1 [plan_date] => 2018-02-26 [line] => L01 [tool] => [model_suffix] => OLED65C7T-T.ATRYLJL [lot_size] => 67 [original_lot_size] => 67 [is_user_defined] => 1 [created] => 2018-02-26 15:58:33 [modified] =>   ['tool_lot'] = 67  ['count'] = 0;	)	)
					
                $use_lot_size = $lot_size;
                if($config['inspection_type'] == 'Tool') {
                    $use_lot_size = $plan['tool_lot'];
                }
                
                $temp = array();
                $temp['lot_id'] = $plan['id'];
                $temp['product_id'] = $this->product_id;
                $temp['sampling_date'] = $plan_date;
                $temp['inspection_id'] = $config['inspection_id'];
                $temp['config_type'] = $config['inspection_type'];
                $temp['sampling_type'] = $config['sampling_type'];
                //$temp['inspection_name'] = $config['inspection_name'];
                $temp['line'] = $plan['line'];
                $temp['tool'] = $plan['tool'];
                $temp['model_suffix'] = $plan['model_suffix'];
                if(isset($plan['count'])) {
                    $temp['group_count'] = $plan['count'];
                } else {
                    $temp['group_count'] = 0;
                }
                $temp['lot_size'] = $lot_size;
                $temp['original_lot_size'] = $plan['original_lot_size'];
                $temp['created'] = date("Y-m-d H:i:s");
                $temp['skipable'] = 0;
                // echo $config['sampling_type'];exit;
                if($config['sampling_type'] == 'No Inspection') {
                    continue;
                }
                
                if($config['sampling_type'] == 'Auto') {
                    if(!$config['inspection_level'] || !$config['acceptable_quality']) {
                        continue;
                    }

                    $no_of_samples = $this->Plans_model->get_no_of_samples_auto($use_lot_size, $config['inspection_level'], $config['acceptable_quality']);
                    
                    if($use_lot_size == 1) {
                        $no_of_samples = 1;
                    }
                    
                    if($no_of_samples === FALSE)
                        continue;
                    
                    $temp['no_of_samples'] = ($no_of_samples > $use_lot_size) ? $use_lot_size : $no_of_samples;
                }

                if($config['sampling_type'] == 'User Defined') {
					// echo $use_lot_size;exit;
                    $no_of_samples = $this->Plans_model->get_no_of_samples($config['id'], $use_lot_size);
                    if($no_of_samples === FALSE)
                        continue;//Komal Uncoment It=>test
                    
                    $temp['no_of_samples'] = ($no_of_samples > $use_lot_size) ? $use_lot_size : $no_of_samples;
                }

                if($config['sampling_type'] == 'Interval') {
                    $required_times = $config['no_of_times'];
                    
                    $diff = 0;
                    if($config['no_of_months'] != 'Dialy') {
                        // echo "123";exit;
                        if($config['no_of_months'] == 'Weekly') {
                            $current_week = date('Y-\WW');
        
                            $start_date = date("Y-m-d", strtotime("{$current_week}-1")); //Returns the date of monday in week
                            $end_date = date("Y-m-d", strtotime("{$current_week}-7"));   //Returns the date of sunday in week

                        } else if($config['no_of_months'] == 'Bi-Monthly') {
                            $day = date('d');

                            if($day < 15) {
                                $start_date = date('Y-m').'-01';
                                $end_date = date('Y-m').'-15';
                            } else {
                                $start_date = date('Y-m').'-15';
                                $end_date = date('Y-m-t');
                            }

                        } else {
                            $month = $config['no_of_months'];
                            $first_date_of_current = date('Y-m').'-01';
                            $gap = $month - 1;
                           $start_date = date('Y-m-d', strtotime('-'.$gap.' month', strtotime($first_date_of_current)));
                           $end_date = date('Y-m-t');
                        }
                        //exit;
                        $exists = $this->Plans_model->check_sampling_done_interval_insp($start_date, $end_date,
                        $config['inspection_id'], $plan['model_suffix'], $plan['line']);
                        //echo $this->db->last_query();exit;
                        $completed_insp = 0;

                        foreach($exists as $exist) {
                            if($exist['completed'] >= $exist['no_of_samples']) {
                                $completed_insp++;
                            } else {
                                $diff = $exist['no_of_samples'] - $exist['completed'];
                                break;
                            }
                        }
// echo $diff;exit;
                        if($completed_insp >= $required_times) {
                            continue;
                        }

                    }
                    
                    if($diff == 0) {
                        $period_lot = $use_lot_size;

                        /* echo $start_date;
                        echo "<br />===<br />";
                        echo $end_date;
                        echo "<br />===<br />";
                        echo "<pre>";print_r($exists);
                        echo "<br />===<br />";
                        echo $period_lot;
                        exit; */
                        
                        $no_of_samples = $this->Plans_model->get_no_of_samples($config['id'], $period_lot);
                        if($no_of_samples === FALSE)
                            continue;
                        
                        /* echo $this->db->last_query();
                        echo "<br />===<br />";
                        echo $start_date;
                        echo "<br />===<br />";
                        echo $end_date;
                        echo "<br />===<br />";
                        echo "<pre>";print_r($exists);
                        echo "<br />===<br />";
                        echo $period_lot;
                        echo "<br />===<br />";
                        echo "Planned => ".$planned;
                        echo "<br />===<br />";
                        echo $produced;
                        exit; */
                        
                        $temp['no_of_samples'] = ($no_of_samples > $period_lot) ? $period_lot : $no_of_samples;
                    } else {
                        $temp['no_of_samples'] = $diff;
                    }
                    
                    if($config['no_of_months'] != 'Dialy') {
                        if($config['inspection_type'] == 'Tool') {
                            $period_lot = $this->Plans_model->get_lot_produced_in_range($start_date, date('Y-m-d'), $this->product_id, $plan['line'], '', $plan['tool']);
                        } else {
                            $period_lot = $this->Plans_model->get_lot_produced_in_range($start_date, date('Y-m-d'), $this->product_id, $plan['line'], $plan['model_suffix']);
                        }
                        
                        $start_date_month = date('Y-m-01', strtotime($start_date));
                        if($config['inspection_type'] == 'Tool') {
                            $produced = $this->Plans_model->get_lot_produced_in_range($start_date_month, date('Y-m-d'), $this->product_id, '', '', $plan['tool']);
                            
                            $planned = $this->Plans_model->get_lot_planned_in_range($start_date_month, date('Y-m-d'), $this->product_id, '', $plan['tool']);
                        } else {
                            $produced = $this->Plans_model->get_lot_produced_in_range($start_date_month, date('Y-m-d'), $this->product_id, '', $plan['model_suffix']);
                            
                            $planned = $this->Plans_model->get_lot_planned_in_range($start_date_month, date('Y-m-d'), $this->product_id, $plan['model_suffix']);
                        }
                        
                                                
                        $temp['skipable'] = ($planned - $produced) > 200 ? 1 : 0;
                    } else {
                        $temp['skipable'] = 0;
                    }
                    
                    
                }
                
                $sampling_plan[] = $temp;
            }
		
        }
        // echo "<pre>===>>";print_r($sampling_plan);exit;
        if(!empty($sampling_plan)) {
            $this->Plans_model->delete_sampling_plan_new($production_plan[0]['id'],$production_plan[0]['model_suffix'],$production_plan[0]['line'], $plan_date);
            $this->Plans_model->insert_sampling_plan_new($sampling_plan, $plan_date);
        }
        
        if($page == 'dashboard') {
            redirect(base_url());
        } else {
            redirect(base_url().'sampling/view_sampling_plan/'.$plan_date);
        }
    }
    
}
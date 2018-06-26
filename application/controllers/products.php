<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends Admin_Controller {
        
    public function __construct() {
        parent::__construct(true);
        // $sqimdb = 'sqim_new';
        //render template
        $this->template->write('title', 'LQC | '.$this->user_type.' Dashboard');
        $this->template->write_view('header', 'templates/header', array('page' => 'masters'));
        $this->template->write_view('footer', 'templates/footer');
		
		$page_new = 'products';
	
		$this->hits($page_new);
		
    }
        
    public function index() {
		// echo "1239999";
        $this->is_super_admin();
        
        $data = array();
        $this->load->model('Product_model');
        $data['products'] = $this->Product_model->get_all_products();

        $this->template->write_view('content', 'products/index', $data);
        $this->template->render();
    }
	public function product_export() {
        $this->is_super_admin();
        
        $data = array();
        $this->load->model('Product_model');
        $data['products'] = $this->Product_model->get_all_products();

			$str = $this->load->view("products/product_list",$data,true);
			
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=product_list.xls");
        
        
        header("Pragma: ");
		header("Cache-Control: ");
		echo $str;
    }
    
    public function add_product($product_id = '') {
		// echo "1245";exit;
        $this->is_super_admin();
        
        $data = array();
        $this->load->model('Product_model');
        
        if(!empty($product_id)) {
            $product = $this->Product_model->get_product($product_id);
            if(empty($product))
                redirect(base_url().'products');

            $data['product'] = $product;
        }
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            
            $response = $this->Product_model->add_product($post_data, $product_id); 
            if($response) {
                $this->session->set_flashdata('success', 'Product successfully '.(($product_id) ? 'updated' : 'added').'.');
                redirect(base_url().'products');
            } else {
                $data['error'] = 'Something went wrong, Please try again';
            }
        }
        
        $this->template->write_view('content', 'products/add_product', $data);
        $this->template->render();
    }

    public function parts() {
        $data = array();
        $this->load->model('Product_model');
        $product = $this->Product_model->get_product($this->product_id);
        if(empty($product))
            redirect(base_url().'products');

        $data['product'] = $product;
        $data['parts'] = $this->Product_model->get_all_product_parts_new($this->product_id);

        $this->template->write_view('content', 'products/parts', $data);
        $this->template->render();
    }
	public function parts_export() {
        $data = array();
        $this->load->model('Product_model');
        $product = $this->Product_model->get_product($this->product_id);
        if(empty($product))
            redirect(base_url().'products');

        $data['product'] = $product;
        $data['parts'] = $this->Product_model->get_all_product_parts($this->product_id);
			$str = $this->load->view("products/parts_list",$data,true);
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=parts_list.xls");        
        
        header("Pragma: ");
		header("Cache-Control: ");
		echo $str;
    }
    
    public function add_product_part($part_id = '') {
        $data = array();
        $this->load->model('Product_model');
        
        $product = $this->Product_model->get_product($this->product_id);
        if(empty($product))
            redirect(base_url().'products');

        $data['product'] = $product;
        
        if(!empty($part_id)) {
            $part = $this->Product_model->get_product_part($this->product_id, $part_id);
            if(empty($part))
                redirect(base_url().'products/parts');

            $data['part'] = $part;
        }
        
        if($this->input->post()) {
            $post_data = $this->input->post();
            $post_data['product_id'] = $product['id'];
			
			$res =$this->Product_model->get_product_part_by_code($post_data['product_id'],$post_data['code']);
			if(empty($res))
            {
				$response = $this->Product_model->update_product_part($post_data, $part_id); 
				if($response) {
					$this->session->set_flashdata('success', 'Product part successfully '.(($part_id) ? 'updated' : 'added').'.');
					redirect(base_url().'products/parts');
				} else {
					$data['error'] = 'Something went wrong, Please try again';
				}
			}
			else{
					$data['error'] = 'Part Number for this Product aleady exist.';				
			}
        }
        
        $this->template->write_view('content', 'products/add_product_part', $data);
        $this->template->render();
    }

    public function upload_product_parts() {
        $data = array();
        $this->load->model('Product_model');
        
        $product = $this->Product_model->get_product($this->product_id);
        if(empty($product))
            redirect(base_url().'products');
        
        $data['product'] = $product;
        
        if($this->input->post()) {
             
            if(!empty($_FILES['parts_excel']['name'])) {
                $output = $this->upload_file('parts_excel', 'product_parts', "assets/uploads/");

                if($output['status'] == 'success') {
                    $res = $this->parse_parts($this->product_id, $output['file']);
                    
                    if($res) {
                        $this->session->set_flashdata('success', 'Parts successfully uploaded.');
                        redirect(base_url().'products/parts');
                    } else {
                        $data['error'] = 'Error while uploading excel';
                    }
                } else {
                    $data['error'] = $output['error'];
                }

            }
        }
        
        $this->template->write_view('content', 'products/upload_parts', $data);
        $this->template->render();
    }
    
    public function sp_master() {
        $data = array();
        
        $this->load->model('Product_model');
        $data['products'] = $this->Product_model->get_all_products();
        
        if($this->input->post()) {
            $this->load->library('form_validation');

            $validate = $this->form_validation;
            $validate->set_rules('product_id', 'Product', 'trim|required|xss_clean');
            if($validate->run() === TRUE) {
                //$product_id = $this->input->post('product_id');
                $product_id = $this->product_id;
                if(!empty($_FILES['master_excel']['name'])) {
                    $output = $this->upload_file('master_excel', 'sp_master', "assets/masters/");
                    //echo "<pre>"; print_r($output); exit;
                    if($output['status'] == 'success') {
                        //echo "here"; exit;
                        $excel = $this->parse_sp_master($product_id, $output['file']);
                        if($excel) {
                            $this->session->set_flashdata('success', 'Master Successfully uploaded.');
                            redirect(base_url().'suppliers/sp_mappings');
                        } else {
                            $data['error'] = 'Incorrect Excel format. Please check';
                        }
                        
                    } else {
                        //echo "error"; exit;
                        $data['error'] = $output['error'];
                    }

                }
                
            } else {
                $data['error'] = validation_errors();
            }

        }
        
        $this->template->write_view('content', 'products/sp_master', $data);
        $this->template->render();
    }
    
    private function parse_sp_master($product_id, $file_name) {
        //$file_name = 'assets/masters/'.$file_name;
        
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file_name);
        
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $arr = $objPHPExcel->getActiveSheet()->toArray(null, true,true,true);
        
        if(empty($arr) || !isset($arr[2]) || count($arr[2]) < 4) {
            return FALSE;
        }
        
        $this->load->model('Product_model');
        $this->load->model('Supplier_model');
        
        $p = '';
        $part_id = '';
        
        $mappings = array();
        
        
        foreach($arr as $no => $row) {
            if($no == 1)
                continue;
            
            $row['B'] = str_replace("\n", ' ', $row['B']);
            
            if($p !== trim($row['B']) && !empty($row['B'])) {
                $p = $row['B'];
                
                $exists = $this->Product_model->get_product_part_by_code($product_id, $p);
                $part_id = !empty($exists) ? $exists['id'] : '';
            }
            
            if(empty($part_id)) {
                continue;
            }
            
            $supplier = array();
            $supplier['supplier_no'] = trim($row['D']);
            $supplier['name'] = trim($row['E']);
            
            $exists = $this->Supplier_model->get_supplier_by_code($supplier['supplier_no']);
            if(empty($exists)) {
                $supplier_id = $this->Supplier_model->add_supplier($supplier, '');
            } else {
                $supplier_id = $exists['id'];
            }
            
            $mapping = array();
            $mapping['supplier_id'] = $supplier_id;
            $mapping['product_id'] = $product_id;
            $mapping['part_id'] = $part_id;
            $mapping['created'] =  date("Y-m-d H:i:s");
            
            $mappings[] = $mapping;
        }

        if(!empty($mappings)) {
            $this->Supplier_model->insert_sp_mappings($mappings);
            $this->Supplier_model->remove_dups();
        }
        
        return TRUE;
    }
    
    public function delete_product_part($part_id) {
        $this->load->model('Product_model');

        $part = $this->Product_model->get_product_part($this->product_id, $part_id);
        if(empty($part))
            redirect(base_url().'products/parts');
            
        $deleted = $this->Product_model->update_product_part(array('is_deleted' => 1), $part_id); 
        if($deleted) {
            $this->session->set_flashdata('success', 'Product Part deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, please try again.');
        }
        
        redirect(base_url().'products/parts');
    }

    private function parse_parts($product_id, $file_name) {
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file_name);
        
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $arr = $objPHPExcel->getActiveSheet()->toArray(null, true,true,true);
        $this->load->model('Product_model');
        
        if(empty($arr) || !isset($arr[1])) {
            return FALSE;
        }
        
        $parts = array();
        foreach($arr as $no => $row) {
            if($no == 1)
                continue;
            if(!trim($row['A']))
                continue;
			
			$res =$this->Product_model->get_product_part_by_code($product_id,trim($row['A']));
			if(empty($res))
            {            
				$temp = array();
				$temp['product_id']     = $product_id;
				$temp['code']           = trim($row['A']);
				$temp['name']           = trim($row['B']);
				$temp['created']        = date("Y-m-d H:i:s");
				
				if(trim($row['C']) == 'Y'){
						$temp['is_deleted'] = 1;			
				}
				if(trim($row['C']) == 'N'){
						$temp['is_deleted'] = 0;			
				}
			}
            
            $parts[]        = $temp;
        }
		
        if(!empty($parts))
		{
			$this->Product_model->insert_parts($parts, $product_id);
		}
        return TRUE;
    }
    
    public function get_parts_by_product() {
        $data = array('parts' => array());
        
        if($this->input->post('product')) {
            $this->load->model('Product_model');
            $data['parts'] = $this->Product_model->get_all_product_parts($this->input->post('product'));
        }
        
        echo json_encode($data);
    }
	public function get_parts_id_by_part() {
        $data = array('child_parts' => array());
        //echo $this->input->post('part_no');exit;
        if($this->input->post('part_no')) {
            $this->load->model('Product_model');
            $data['child_parts'] = $this->Product_model->get_parts_id_by_part1($this->input->post('part_no'));
        }
       //echo $this->db->last_query();exit;
        echo json_encode($data);
    }
    
    public function get_all_product_parts_by_supplier() {
        $data = array('parts' => array());
        
        if($this->input->post('product') && $this->input->post('supplier_id')) {
            $this->load->model('Product_model');
            $data['parts'] = $this->Product_model->get_all_product_parts_by_supplier($this->input->post('product'), $this->input->post('supplier_id'));
        }
        
        echo json_encode($data);
    }
    
    public function get_distinct_parts_by_product() {
        $data = array('parts' => array());
        
        if($this->input->post('product')) {
            $this->load->model('Product_model');
            $data['parts'] = $this->Product_model->get_all_distinct_product_parts($this->input->post('product'));
        }
        
        echo json_encode($data);
    }
    
    public function get_part_numbers_by_part_name() {
        $data = array('parts' => array());
        
        if($this->input->post('part_name')) {
            $this->load->model('Product_model');
            $data['part_nums'] = $this->Product_model->get_all_part_numbers_by_part_names_new($this->input->post('part_name'),$this->product_id);
            // $data['part_nums'] = $this->Product_model->get_all_part_numbers_by_part_names($this->input->post('part_name'),$this->product_id);
        }
        
        echo json_encode($data);
    }
	
	//Part Defect Mapping
	
	public function pd_master() {
		// echo $sqim_db;exit;
        $data = array();
        $this->load->model('Product_model');
        $product = $this->Product_model->get_product($this->product_id);
        $data['product'] = $product;
        
        $data['parts'] = $this->Product_model->get_all_product_parts_by_supplier($this->product_id, $this->supplier_id);
		
        $this->load->model('foolproof_model');
        
        $mappings = array();
       // if($this->input->get('part_no')) {
            // $supplier_id = $this->user_type == 'Supplier' ? $this->id : '';
            
            // $mappings = $this->Product_model->get_defect_code_mappings($this->input->get('part_no'));
            $mappings = $this->Product_model->get_defect_code_mappings();
            //echo $this->db->last_query();
       // }
        
        $data['mappings'] =  $mappings;
        /* echo "<pre>";
        print_r($mappings); exit; */

        $this->template->write_view('content', 'products/pd_mappings', $data);
        $this->template->render();
    }
    
	public function upload_pd_mappings() {
        $data = array();
        $this->load->model('Product_model');
        
        $product = $this->Product_model->get_product($this->product_id);
        if(empty($product))
            redirect(base_url().'products');
        
        $data['product'] = $product;
        
		// print_r($_FILES['pd_excel']['name']);exit;
		
        if(!empty($_FILES['pd_excel']['name'])) {
             
            if(!empty($_FILES['pd_excel']['name'])) {
                $output = $this->upload_file('pd_excel', 'pd_excel', "assets/uploads/");	
				// print_r($output);
                if($output['status'] == 'success') {
                    $res = $this->parse_pd_mappings($this->product_id,$output['file']);
				 print_r($res);exit;
                    
                    if($res) {
                        $this->session->set_flashdata('success', 'Defect Code successfully uploaded.');
                        redirect(base_url().'products/pd_master');
                    } else {
                        $data['error'] = 'Error while uploading excel';
                    }
                } else {
                    $data['error'] = $output['error'];
                }

            }
        }
        
        $this->template->write_view('content', 'products/upload_pd_mappings', $data);
        $this->template->render();
    }
    
	private function parse_pd_mappings($product_id, $file_name) {
        
        ini_set('memory_limit', '10M');
        
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
        $this->load->model('Supplier_model');
        
        $defect_code = array();
		$full_data = array();
        $parts = array();
        $part_no = '';
        $part_name = array();		
        $part_id = '';
        $i=0; $j=0;
        foreach($arr as $no => $row) {
			
			$content_error = array();
            if($no == 1){
                $headers = array();
				$headers = $row;
				$headers['H'] = 'Error';
				continue;
            }
			// print_r(count($row));
			if(empty($row['A']))
				 continue;
			
			if(trim($row['C']))
			{
				$part_no = trim($row['C']);
				$part_exist = $this->Product_model->get_product_part_by_code($this->product_id,$part_no);
			
				$part_id = !empty($part_exist) ? $part_exist['id'] : '';      
					
				//Get Plan for this Part_id for current month_year
				
				if($part_id == ''){				
					
					$content_error[] = 'Part Number not found.'; 
				}
				else{
					
					$sp_mapping = $this->Product_model->get_sp_mapping_by_pid_sid($this->product_id,$part_id,$this->supplier_id);
					if(empty($sp_mapping))
						$content_error[] = 'Supplier-Part Mapping Not found.'; 
											
				}
			}
			else{
				$content_error[] = 'Part No. is blank.'; 
			}
			
			if(trim($row['D'])){
				$exists_supplier = $this->Supplier_model->get_supplier_by_code(trim($row['D']));
				 // print_r($exists_supplier);exit;
                if(empty($exists_supplier)) {
                   $content_error[] = 'Supplier Not found.'; 
				}else{
					$supplier_id = $exists_supplier['id'];
				}	
			}else{
				$content_error[] = 'Supplier Code is blank.'; 
			}
			
			
			if(!trim($row['F']))
                $content_error[] = 'Defect Code is blank.'; 
            // print_r($row);exit;
			
            
            //echo $part_name;
			//to handle defects array
			if(trim($row['B'])){
					$part_name[$i] = trim($row['B']);
			}
			//to handle defects array
            $temp = array();
            
            if(!empty($content_error)){
				
				$temp['SR No']                        = $row['A'];
				$temp['part_name']                    = $row['B'];
				$temp['part_no']                      = $row['C'];
				$temp['supplier_code']                = $row['D'];
				$temp['supplier_name']   		      = $row['E'];
				$temp['defect_description']   		  = $row['F'];
				$temp['defect_description_detail']    = $row['G'];
				$temp['error']    	   				  = implode(",",$content_error);
				$full_data[]       	   				  = $temp;
			}	
			
			
			
            if(!trim($row['C']))
                continue;
            
            if(!trim($row['D']))
                continue;
            
             
            if(!trim($row['F']))
                continue; 
			
            if($part_id == '')
				continue;
			else if(empty($exists_supplier))
                continue;
			else if(empty($sp_mapping))
                continue;
			
            
			
			if(!empty($sp_mapping)) {
                $dc = array();
                $dc['product_id']                   = $this->product_id;
				$dc['part_id']                      = $part_id;
				$dc['supplier_id']                  = $supplier_id;
				$dc['defect_description']   	    = trim($row['F']);
				$dc['defect_description_detail']    = trim($row['G']);
				$dc['created']          			= date("Y-m-d H:i:s");
				$defect_code[] 						= $dc;			
            }
            
            
			if(!empty($part_id) && !empty($supplier_id)){
				$ch_exists = $this->Product_model->check_duplicate_defect($part_id,$supplier_id);
				if(!empty($ch_exists)){
					// continue;
					$this->Product_model->delete_defect_by_part_id($part_id);
				} 
			}
			// echo $this->db->last_query();exit;
			
          
            // $defect_code[]        = $temp;
			
			$i++;
        }
// exit;        
		if(!empty($defect_code)) {
			$this->Product_model->insert_pd_mappings($defect_code, $product_id);
		}
		if(!empty($full_data)){			 
				$this->create_excel($headers, $full_data, 'defect_code_error');			
		}
		return TRUE;
    
	}
    
	public function add_pd_mapping($defect_id = '') {
        $data = array();
        $this->load->model('Product_model');
          //gives all product part with sp mapping
		  $data['parts'] = $this->Product_model->get_all_product_parts_by_supplier_new($this->product_id,$this->supplier_id);
          $data['part_nums'] = $this->Product_model->get_all_product_parts_new($this->product_id);
		
        $this->load->model('Supplier_model');
      //  $data['suppliers'] = $this->Supplier_model->get_all_suppliers_new();
        $product = $this->Product_model->get_product($this->product_id);
        if(empty($product))
            redirect(base_url().'products/pd_master');

        $data['product'] = $product;
        
        if(!empty($defect_id)) {
			
            $defect_code = $this->Product_model->get_defect_code($this->product_id, $defect_id);
			// print_r($defect_code);exit;
			 $data['part_nums'] = $this->Product_model->get_all_part_numbers_by_part_names_new($defect_code['part_name'],$this->product_id);
            if(empty($defect_code))
                redirect(base_url().'products/pd_master');

            $data['defect_code'] = $defect_code;
        }
        
        if($this->input->post()) {
            $post_data = $this->input->post();
			// print_r($post_data);exit;
            $post_data['product_id'] = $product['id'];
            $post_data['supplier_id'] = $this->id;
			$res =$this->Product_model->check_duplicate_defect($post_data['part_id'],$post_data['supplier_id']);
			/* if(empty($res))
            { */
				$response = $this->Product_model->update_defect_code($post_data, $defect_id); 
				if($response) {
					$this->session->set_flashdata('success', 'Defect code successfully '.(($defect_id) ? 'updated' : 'added').'.');
					redirect(base_url().'products/pd_master');
				} else {
					$data['error'] = 'Something went wrong, Please try again';
				}
			//}
			/* else{
					$data['error'] = 'Defect code for this Product aleady exist.';				
			} */
        }
        
        $this->template->write_view('content', 'products/add_pd_mapping', $data);
        $this->template->render();
    }

	function delete_pd_mapping($id){
		  $this->load->model('Product_model');
		$response = $this->Product_model->delete_pd_mapping($id); 
		if($response) {
			$this->session->set_flashdata('success', 'Defect code Deleted Successfully.');
			redirect(base_url().'products/pd_master');
		} else {
			$data['error'] = 'Something went wrong, Please try again';
		}	
	}
	//Part Defect Mapping
}
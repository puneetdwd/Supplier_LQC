<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* @property user_model $user */

class Qr_code_generate extends Admin_Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
       // $this->load->model('user_model', 'user');
        $this->load->library('ci_qr_code');
        $this->config->load('qr_code');
		
		//render template
        $this->template->write('title', 'LQC | QR Code Module');
        $this->template->write_view('header', 'templates/header', array('page' => 'masters'));
        $this->template->write_view('footer', 'templates/footer');
		
		$page_new = 'qr_code';
		
		//For page hits
		$this->hits($page_new);


    }

    /**
     * success_link
     * to display user info and see print link
     * @access public
     * @param user_id
     * @return
     */
    function index(){
		
		$this->load->model('Product_model');
        $this->load->model('QR_model');
        
        $supplier_id = $this->user_type == 'Supplier' ? $this->id : '';
        if($this->user_type == 'Supplier Inspector') {
            $user = $this->User_model->get_supplier_inspector_user($this->id);
			$supplier_id = $user['supplier_id'];
        }
        $data['parts'] = $this->Product_model->get_all_product_parts_by_supplier_new1($this->product_id, $this->supplier_id);
		
		 $data['qr_codes'] = $this->QR_model->get_all_qr_print();
		//print_r( $data['qr_codes']);exit;
		
		$this->template->write_view('content', 'qr_code/index', $data);
        $this->template->render();
    }
	function qr_print_count(){
		//Print History
		$this->load->model('Product_model');
        $this->load->model('QR_model');
        
        $supplier_id = $this->user_type == 'Supplier' ? $this->id : '';
        if($this->user_type == 'Supplier Inspector') {
            $user = $this->User_model->get_supplier_inspector_user($this->id);
			$supplier_id = $user['supplier_id'];
        }
        $data['parts'] = $this->Product_model->get_all_product_parts_by_supplier_new1($this->product_id, $this->supplier_id);
		
		 $data['qr_prints'] = $this->QR_model->get_qr_print_history();
		// echo "<pre>";print_r( $data['qr_prints']);exit;
		
		$this->template->write_view('content', 'qr_code/qr_print_history', $data);
        $this->template->render();
    }

 
    function number_qr_print($part_id = "", $lot_size = "")
    { 
	// echo $this->id;
		$data = array();
        $this->load->model('Product_model');
        $this->load->model('QR_model');
        
		$supplier_id = $this->user_type == 'Supplier' ? $this->id : '';
        if($this->user_type == 'Supplier Inspector') {
            $user = $this->User_model->get_supplier_inspector_user($this->id);
			$supplier_id = $user['supplier_id'];
        }
		
        $data['parts'] = $this->Product_model->get_all_product_parts_by_supplier_new1($this->product_id, $supplier_id);
		$data['part_id'] = $part_id;
		$data['lot_size'] = $lot_size;
		if(!empty($this->input->post())){
			$post_data = $this->input->post();
			
			$post_data['supplier_id'] = $supplier_id;
			$post_data['product_id'] = $this->product_id;
			if($part_id){
				$part = $this->Product_model->get_part($part_id);
				$post_data['part_id'] = $part_id;
			}
			else{
				$part = $this->Product_model->get_part($post_data['part_id']);
			}
			
			// print_r($post_data);exit;
			$data['qr_codes'] = $this->QR_model->update_qr_print($post_data);
			if(!empty($data['qr_codes'])){
				 $idd = $this->print_qr($post_data['qr_code_qty'],$data['qr_codes'],$part['code']);
			}
	     
			if(isset($idd)){
				   $this->session->set_flashdata('success', 'QR Code has been generated.');
				   redirect(base_url().'qr_code_generate/index');
			}
			else{
				   $this->session->set_flashdata('error', 'Something went wrong.');
				   redirect(base_url().'qr_code_generate/index');
			}
		}
        $this->template->write_view('content', 'qr_code/add_qr_codes', $data);
        $this->template->render();
	}
	
    function print_qr($no_qr,$id,$part_no)
    {
        $qr_code_config = array();
        $qr_code_config['cacheable'] = $this->config->item('cacheable');
        $qr_code_config['cachedir'] = $this->config->item('cachedir');
        $qr_code_config['imagedir'] = $this->config->item('imagedir');
        $qr_code_config['errorlog'] = $this->config->item('errorlog');
        $qr_code_config['ciqrcodelib'] = $this->config->item('ciqrcodelib');
        $qr_code_config['quality'] = $this->config->item('quality');
        $qr_code_config['size'] = $this->config->item('size');
        $qr_code_config['black'] = $this->config->item('black');
        $qr_code_config['white'] = $this->config->item('white');
     

		
		for($i=0; $i<$no_qr; $i++){ 
		
			$this->ci_qr_code->initialize($qr_code_config);
			$php_timestamp_date = date("Ymd_His", time());
			$image_name = $part_no.'$'.$php_timestamp_date.'$'.time().$i. ".png";
			$params['data'] = $part_no.'$'.$php_timestamp_date.'$'.time().$i;//$codeContents;
			$params['level'] = 'H';
			$params['size'] = 5;

			$params['savename'] = FCPATH . $qr_code_config['imagedir'] . $image_name;
			$this->ci_qr_code->generate($params);

			$this->data['qr_code_image_url'] = base_url() . $qr_code_config['imagedir'] . $image_name;

			$file = $params['savename'];
			$qr_c[$i] =  $image_name;
		   
		}
			  // exit;
		$data1['qr_codes'] = implode(',',$qr_c);
		$idd = $this->QR_model->update_qr_print($data1,$id);
		
		return $idd;
    }

	function view_print_view($qr_id){
		$this->load->model('QR_model');
		$data['qr'] = $this->QR_model->get_qr_print($qr_id);
		$this->template->write_view('content', 'qr_code/view_print_view', $data);
        $this->template->render();
	}
	
	function view_print_view_tablular($qr_id){
		$this->load->model('QR_model');
		$data['qr'] = $this->QR_model->get_qr_print($qr_id);
		$data['all_qr'] = count(explode(',',$data['qr']['qr_codes']));
		$qrs = "'".str_replace(',',"','",str_replace('.png','',$data['qr']['qr_codes']))."'";
		$data['qrr'] = $this->QR_model->get_qr_print_history_qrs($qrs);/* exit; */
		$data['printed_qr'] = count($data['qrr']);
		// echo $this->db->last_query();
		echo $this->load->view('qr_code/view_print_view_tablular', $data);
	}
	
	function save_print_qr_history(){
		$this->load->model('QR_model');
		if($this->input->post('qr')){
			
		
			$qr_codes = explode(',',str_replace('.png'," ",$this->input->post('qr'))); 
			// print_r($qr_codes);
			$data = array();
			$i = 0;
			$data['product_id'] =  $this->product_id;				
			$data['part_id'] =  $this->input->post('part_id');				
			$data['supplier_id'] =  $this->input->post('supplier_id');				
			$data['printed_by'] =  $this->session->userdata('name');
			foreach($qr_codes as $qrc){
				if($qrc != ''){
					$data['qr_code'] 	=  $qrc;					
					//$this->QR_model->get_qr_by_serial($qrc);
					$in[$i++] = $this->QR_model->maintain_print_count($data);
				}
			}
			// print_r($in);
			$cnt = count($in);
			// echo $cnt;exit;
			echo json_encode($in);
		}				
	}
	
	function view_print_view_direct($qr_id){
		$this->load->model('QR_model');
		$data1 = array('qr' => array());
		$str = '';
		// $data = array('str' => '');
		//echo "here";
		if($qr_id != ''){
			//echo " Inside";
			$data1['qr'] = $this->QR_model->get_qr_print($qr_id);
			$str = $this->load->view('qr_code/view_print_view_tablular1', $data1);
			
		}
		echo $str;
		//echo json_encode($data);
	}
	
	function submit_remark(){
		if($this->input->post()){
			$post_data = $this->input->post();
			$this->load->model('QR_model');
			$res= $this->QR_model->update_remark($post_data);
			if($res){
				   $this->session->set_flashdata('success', 'QR Code Reprint Remark Updated');
				   redirect(base_url().'qr_code_generate/qr_print_count');
			}
			else{
				   $this->session->set_flashdata('error', 'Something went wrong.');
				   redirect(base_url().'qr_code_generate/qr_print_count');
			}
		}
	}
	
	
	
	
}
// END qr_code_generate Controller class
/* End of file qr_code_generate.php */
/* Location: ./application/controllers/qr_code_generate.php */
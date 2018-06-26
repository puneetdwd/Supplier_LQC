<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports_cron extends Admin_Controller {

    public function __construct() {
        parent::__construct(true);
        
        //$this->template->write_view('header', 'templates/header', array('page' => 'reports'));
        //$this->template->write_view('footer', 'templates/footer');
        $this->load->model('Audit_model');
		$this->load->model('product_model');
		$this->load->model('user_model');
		
		$page_new = 'report_corn';
		
		//For page hits
		$this->hits($page_new);
    }

	public function part_inspection_report_mail() {
        $data = array();
        $sup_id = '';
        //$data['parts'] = $this->Audit_model->get_all_audit_parts('', $sup_id);
        $filters = array();
		$product_ids = $this->product_model->get_all_products();
		if(!empty($product_ids)){
			foreach($product_ids as $product_id) {
				$admins = $this->user_model->get_admin_users($product_id['id']);
				//echo "<pre>";print_r($admins);
				$filters['start_range'] = date('Y-m-d',time() - 60 * 60 * 24);
				$filters['end_range'] = date('Y-m-d',time() - 60 * 60 * 24);
				$filters['product_id'] = $product_id['id'];
				$data['audits'] = $this->Audit_model->get_completed_audits($filters, false);
				$data['yesterday'] = date('jS M, Y', strtotime(date('Y-m-d',time() - 60 * 60 * 24)));
				$mail_content = $this->load->view('cron/mail_part_inspection_report', $data,true);
				$this->load->library('email');
				foreach($admins as $admin) {
					
					$toemail = ' komal@crgroup.co.in';//$admin['email'];
					$subject = "Part Inspection - Completed Inspection Report - ".$product_id['name'];
					$this->sendMail($toemail,$subject,$mail_content);
				}
				
			}
		}
	}	
	public function lot_wise_report_mail() {
        $data = array();
        $sup_id = '';
        $data['parts'] = $this->Audit_model->get_all_audit_parts('', $sup_id);
		$product_ids = $this->product_model->get_all_products();
		$this->load->library('email');
		if(!empty($product_ids)){
			foreach($product_ids as $product_id) {
				$admins = $this->user_model->get_admin_users($product_id['id']);
					$filters = array();
					$filters['start_range'] = date('Y-m-d',time() - 60 * 60 * 24);
					$filters['end_range'] = date('Y-m-d',time() - 60 * 60 * 24);
					$filters['product_id'] = $product_id['id'];
					
					$data['audits'] = $this->Audit_model->get_consolidated_audit_report($filters, false);    
					$data['yesterday'] = date('jS M, Y', strtotime(date('Y-m-d',time() - 60 * 60 * 24)));
					$mail_content = $this->load->view('cron/mail_lot_wise_report', $data,true);
					
				foreach($admins as $admin) {			
					$toemail = ' komal@crgroup.co.in';//$admin['email'];
					$subject = "Lot wise - Completed Inspection Report - ".$product_id['name'];
					$this->sendMail($toemail,$subject,$mail_content);
					//echo $this->email->print_debugger();
				}
					
			}
		}
    }

	public function timecheck_report_mail() {
        $data = array();
        $this->load->model('Timecheck_model');
		$sup_id = '';
        $data['parts'] = $this->Audit_model->get_all_audit_parts('', $sup_id);
		$product_ids = $this->product_model->get_all_products();
		$this->load->library('email');
		if(!empty($product_ids)){
			foreach($product_ids as $product_id) {
					$admins = $this->user_model->get_admin_users($product_id['id']);
					$filters = array();			
					$filters['start_range'] = date('Y-m-d',time() - 60 * 60 * 24);
					$filters['end_range'] = date('Y-m-d',time() - 60 * 60 * 24);
					//$filters['product_id'] = $product_id['id'];
								
					$data['plans'] = $this->Timecheck_model->get_timecheck_plan_report($filters, false);
					$data['yesterday'] = date('jS M, Y', strtotime(date('Y-m-d',time() - 60 * 60 * 24)));
					
					$mail_content = $this->load->view('cron/mail_timecheck_report', $data,true);
					
				foreach($admins as $admin) {			
					$toemail = ' komal@crgroup.co.in';//$admin['email'];
					$subject = "Timecheck  - Completed Inspection Report - ".$product_id['name'];
					$this->sendMail($toemail,$subject,$mail_content);
					
				}
			}
		}
    }
	
	  
	public function timecheck_count_by_supplier_download() {
        $data = array();
        $this->load->model('TC_Checkpoint_model');
		//echo '123';exit;
        $plan_date = date('Y-m-d',time() - (60 * 60 * 24));
		//$data['yesterday'] = date('jS M, Y', strtotime($plan_date));        
		$this->load->library('email');
		$product_ids = $this->product_model->get_all_products();
		if(!empty($product_ids)){
			foreach($product_ids as $product_id) {
				$admins = $this->user_model->get_admin_users($product_id['id']);
				$data['plan_date'] = $plan_date;
				$plans = $this->TC_Checkpoint_model->get_timecheck_counts($this->product_id, $plan_date);
				//print_r($plans);exit;
				$data['plans'] = $plans;
				$data['yesterday'] = date('jS M, Y', strtotime(date('Y-m-d',time() - 60 * 60 * 24)));
								
				$mail_content = $this->load->view("cron/mail_timecheck_count_report",$data,true);
				foreach($admins as $admin) {
					$toemail = ' komal@crgroup.co.in';//$admin['email'];
					$subject = "Timecheck Count - Completed Inspection Report - ".$product_id['name'];
					$this->sendMail($toemail,$subject,$mail_content);
					//echo $this->email->print_debugger();
				}
				
			}
		}
	}
	
	function download_foolproof_report_mail(){
        $data = array();
    	    $plan_date = date('Y-m-d',time() - (60 * 60 * 24));
	
        $this->load->model('foolproof_model');
		$this->load->library('email');
		$product_ids = $this->product_model->get_all_products();
		if(!empty($product_ids)){
			foreach($product_ids as $product_id) {
				$admins = $this->user_model->get_admin_users($product_id['id']);
				$data['plan_date'] = $plan_date;
				$data['foolproofs'] = $this->foolproof_model->get_foolproof_report_mail($plan_date);
				$data['yesterday'] = date('jS M, Y', strtotime(date('Y-m-d',time() - 60 * 60 * 24)));
								
				$mail_content = $this->load->view('cron/view', $data, true);
        
				foreach($admins as $admin) {
					$toemail = ' komal@crgroup.co.in';//$admin['email'];
					$subject = "Fool-Proof - Completed Inspection Report - ".$product_id['name'];
					$this->sendMail($toemail,$subject,$mail_content);
					//echo $this->email->print_debugger();
				}
				
			}
		}
	}
		
}
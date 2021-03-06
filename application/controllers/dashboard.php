<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

    public function __construct() {
        parent::__construct(true);

        $page = $this->user_type.' Dashboard';
        $page_new = 'dashboard';
        if($this->router->fetch_method() == 'realtime') {
            $page = 'realtime';
			$page_new = 'realtime';
        }
		// echo SQIM_DB;
		/* $this->load->model('Supplier_model');
		$a = $this->Supplier_model->add_supplier();
		echo "<pre>".$this->db->last_query();
		print_r($a);
		exit; */
        //render template
        $this->template->write('title', 'LQC | '.$page);
        $this->template->write_view('header', 'templates/header', array('page' => $page));
        $this->template->write_view('footer', 'templates/footer');
		
		//For page hits
		$this->hits($page_new);
    }
 
    /* public function test() {
		echo 
	} */
    public function index() {
        if(!$this->session->userdata('dashboard_date')) {
            $this->session->set_userdata('dashboard_date', date('Y-m-d'));
        }
		if($_POST){
			$dashboard_date = $_POST['date'];
            $this->session->set_userdata('dashboard_date', $dashboard_date);
		}else{
			$dashboard_date = date('Y-m-d');
		}
		// echo $this->user_type;exit;

        if($this->user_type == 'Audit') {
            
            $data = $this->auditer_dashboard();
            //render template
            $this->template->write_view('content', 'auditer_dashboard', $data);
            $this->template->render();

        } else if($this->user_type == 'Supplier') {
			// echo "233";exit;
            $data = array();
           
            $this->load->model('Lqc_plan_model');
            $plans = $this->Lqc_plan_model->get_all_plans_lqc($this->product_id, $this->supplier_id, date('Y-m-d'));
			$data['plans'] = $plans;
			
			// SELECT * FROM `audits_lqc` where state = 'Completed' AND supplier_id = 19 AND product_id = 1

			// $compl_insp = $this->Audit_model->get_all_lqc_inspection_completed($this->product_id, $this->supplier_id);
			/* echo "<pre>";$data['compl_insp'] = $compl_insp;
            print_r($data['compl_insp']);exit; */
			
            //render template
            $this->template->write_view('content', 'supplier_dashboard', $data);
            $this->template->render();
        } else if($this->user_type == 'Supplier Inspector') {
            $data = array();
            $this->load->model('Lqc_plan_model');
            
            $plans = $this->Lqc_plan_model->get_all_plans_lqc($this->product_id, $this->supplier_id);
            // $plans = $this->Lqc_plan_model->get_all_plans_lqc($this->product_id, $this->supplier_id, date('Y-m-d'));
			// echo "<pre>";print_r($plans);exit;
			$data['plans'] = $plans;
           
            //render template
            $this->template->write_view('content', 'supplier_dashboard', $data);
            $this->template->render();

        }
    }
    
    public function show_day_progress() {
        $data = array();
        //$data['sampling_plan'] = $this->display_day_progress($this->session->userdata('dashboard_date'));
        $data['sampling_plan'] = '';
        
        echo $this->load->view('day_progress', $data, true);
    }
    
    public function export_excel() {
        $data = array();
        $data['sampling_plan'] = $this->display_day_progress($this->session->userdata('dashboard_date'));
        $data['export'] = true;
        
        $str = $this->load->view('day_progress', $data, true);
        
        header('Content-Type: application/force-download');
        header('Content-disposition: attachment; filename=Day_Progress_'.$this->session->userdata('dashboard_date').'.xls');
        // Fix for crappy IE bug in download.
        header("Pragma: ");
        header("Cache-Control: ");
        echo $str;
    }
    
    public function set_dashboard_date($date) {
        $this->session->set_userdata('dashboard_date', $date);
        
        redirect(base_url());
    }
    
    public function notification_action($id, $audit_flag = false) {
        if($audit_flag != 'direct') {
            $redirect_url = base_url();
        } else {
            $redirect_url = base_url().'dashboard/na_checkpoints';
        }
        
        $status = $this->input->get('status');
        if(empty($status)) {
            redirect($redirect_url);
        }
        
        $this->load->model('Audit_model');
        if($audit_flag != 'direct') {
            $notification = $this->Audit_model->check_notifications($this->product_id, $id);
        } else {
            $notification = $this->Audit_model->pending_checkpoints($this->product_id, $id);
        }
        
        if(!count($notification)) {
            redirect($redirect_url);
        }
        
        $notification = $notification[0];
        
        $na_approved = 0;
        if(strpos($status, 'approve') === 0) {
            $na_approved = 1;
        } else if($status == 'decline') {
            $na_approved = 2;
        }
        
        $noti_data = array();
        $noti_data['action_by'] = $this->id;
        $noti_data['action_datetime'] = date('Y-m-d H:i:s');
        
        $this->Audit_model->add_notification($noti_data, $id);
        $this->Audit_model->change_na_checkpoint_status($na_approved, $notification['audit_checkpoint_id'], $notification['audit_id']);
        
        if($status == 'approve_always') {
            $this->load->model('Inspection_model');
            $checkpoint = $this->Inspection_model->get_checkpoint_by_inspection_no($notification['inspection_id'], $notification['checkpoint_no']);
            if(!empty($checkpoint)) {

                $exists = $this->Inspection_model->excluded_checkpoint_exists($notification['inspection_id'], $notification['model_suffix'], '');
                if(!$exists) {

                    $post_data = array();
                    $post_data['model'] = $notification['model_suffix'];
                    $post_data['inspection_id'] = $notification['inspection_id'];
                    $post_data['checkpoints_nos'] = $checkpoint['id'];
                    $excluded_id = $this->Inspection_model->update_excluded_checkpoints($post_data, '');
                } else {

                    $checkpoints_nos = $exists['checkpoints_nos'];
                    $check = explode(',', $checkpoints_nos);
                    
                    if(!in_array($checkpoint['id'], $check)) {
                        $checkpoints_nos = $checkpoints_nos.','.$checkpoint['id'];
                        $post_data['checkpoints_nos'] = $checkpoints_nos;
                        
                        $excluded_id = $this->Inspection_model->update_excluded_checkpoints($post_data, $exists['id']);
                    }
                }
            } else {
                $this->session->set_flashdata('error', 'Something went wrong please try again.');
                redirect($redirect_url);
            }
        }
        
        $this->session->set_flashdata('success', 'Notification successfully updated');
        
        redirect($redirect_url);
    }
    
    public function na_checkpoints() {
        $data = array();
        
        $this->load->model('Audit_model');
        $data['audit_checkpoints'] = $this->Audit_model->pending_checkpoints($this->product_id);

        $this->template->write_view('content', 'na_checkpoints', $data);
        $this->template->render();
    }
    
    private function auditer_dashboard() {
        $data = array();
        $this->load->model('Audit_model');
        //$this->load->model('Sampling_model');
        
        $data['on_going'] = $this->Audit_model->get_audit($this->id, array('registered','started', 'finished'));
        $data['on_holds'] = $this->Audit_model->get_on_hold_audits($this->id);
        
        return $data;
    }
    
    private function admin_dashboard() {
        $data = array();
        //$this->load->model('Sampling_model');
        //$exists = $this->Sampling_model->get_dashboard_status($this->session->userdata('dashboard_date'));
        $exists = '';
        $data['exists'] = $exists;
        
        //$this->load->model('Audit_model');
        
        /*$data['on_going'] = $this->Audit_model->get_audit($this->id, array('registered','started', 'finished'));
        $data['on_holds'] = $this->Audit_model->get_on_hold_audits($this->id);
        
        $data['abort_requests'] = $this->Audit_model->pending_abort_requests($this->product_id);
        //echo $this->db->last_query();exit;
        $data['on_hold_counts'] = $this->Audit_model->user_wise_on_hold_count();*/
        
        $data['on_going'] = '';
        $data['on_holds'] = '';
        
        $data['abort_requests'] = '';
        $data['on_hold_counts'] = '';
        
        
        return $data;
    }
    
    private function dashboard() {
        $data = array();
        //$this->load->model('Audit_model');
        $data['audits'] = array();
        
        //$this->load->model('Sampling_model');
        //$data['sampling_plan'] = $this->display_day_progress(date('Y-m-d'));

        //$data['sampling_plan_yesterday'] = $this->display_day_progress(date('Y-m-d', strtotime("-1 day")));
        
        return $data;
    }

    public function view_notification($id, $audit_flag = '') {
        $this->load->model('Audit_model');
        
        if($audit_flag != 'direct') {
            $notification = $this->Audit_model->check_notifications($this->product_id, $id);
        } else {
            $notification = $this->Audit_model->pending_checkpoints($this->product_id, $id);
        }
        
        if(count($notification)) {
            $notification = $notification[0];
        }
        $data['notification'] = $notification;
        $data['audit_flag'] = $audit_flag;
        
        echo $this->load->view('notifications/view_notification_ajax', $data);
    }

    public function abort_status($audit_id, $status) {
        $this->load->model('Audit_model');
        $audit = $this->Audit_model->pending_abort_requests($this->product_id, $audit_id);
        if(empty($audit)) {
            redirect(base_url());
        }
        
        if($status == 'Approve') {
            $response = $this->Audit_model->change_state($audit['id'], $audit['auditer_id'], 'aborted');
        } else if($status == 'Reject') {
            $audit_up_data                              = array();
            $audit_up_data['abort_requested']           = 0;
            
            $response = $this->Audit_model->update_audit($audit_up_data, $audit['id']);
        }
        
        if($response) {
            $this->session->set_flashdata('success', 'Inspection '.$status.' successfully.');
        } else {
            $this->session->set_flashdata('errpr', 'Something went wrong please try again.');
        }
        
        redirect(base_url());
    }
}
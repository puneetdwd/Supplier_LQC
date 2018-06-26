<?php
class Audit_model extends CI_Model {

    function get_audit_judgement($audit_id) {
        $sql = "SELECT COUNT(ac.id) as checkpoint_count, 
        SUM(IF(ac.result = 'OK', 1, 0)) as ok_count, 
        SUM(IF(ac.result = 'NG', 1, 0)) as ng_count, 
        SUM(IF(ac.result IS NULL, 1, 0)) as pending_count 
        FROM lqc_audit_defect_code ac 
        WHERE ac.audit_id = ?";
        
        return $this->db->query($sql, array($audit_id))->row_array();
    }
	
	function get_auditid_wise_audit($audit_id){
		$sql = "SELECT s.id as sid,s.supplier_no, s.name, p.org_name, pp.code as part_no, ac.prod_lot_qty, ac.audit_date, 
                ac.created as insp_date, si.name as inspector_name
				FROM `audits_completed` ac 
				Inner Join sqim_new.suppliers s ON s.id = ac.supplier_id 
				Inner Join sqim_new.products p ON p.id = ac.product_id
                Inner Join sqim_new.product_parts pp ON pp.id = ac.part_id
                Inner Join sqim_new.supplier_inspector si ON si.id = ac.auditer_id 
				WHERE ac.audit_id = ".$audit_id;
				
		return $this->db->query($sql)->row_array();
	}
	
	function get_auditid_wise_checkpoints_data($audit_id){
		$sql = "select * from `lqc_audit_defect_code` where INSPECTION_SPEC_DETAIL_ID != 0 and audit_id = ".$audit_id;
		
		return $this->db->query($sql)->result_array();
	}

    function get_all_audit_checkpoints_res_ok($audit_id) {
        $this->db->where('audit_id', $audit_id);
        $this->db->where('result', 'OK');
        
        $this->db->order_by('checkpoint_type DESC, checkpoint_no ASC');
        
        return $this->db->get('lqc_audit_defect_code')->result_array();
    }

    function checkpoint_audited($product_id, $part_id, $checkpoint_id, $date) {
        $sql = "SELECT count(*) as count FROM audits_lqc a 
        INNER JOIN lqc_audit_defect_code ac 
        ON a.id = ac.audit_id AND ac.org_checkpoint_id = ?
        WHERE a.audit_date >= ?
        AND a.product_id = ?
        AND a.part_id = ?
        AND a.state = 'completed'";
        
        $pass_array = array($checkpoint_id, $date, $product_id, $part_id);
        
        $record = $this->db->query($sql, $pass_array);
        
        $count = 0;
        if($record->num_rows() > 0) {
            $record = $record->row_array();
            $count =  $record['count'];
        }
        
        return $count;
    }
    
    function get_completed_audits($filters, $count = false, $limit = '') {
        $pass_array = array();
         //print_r($filters);exit;
        $sql = "SELECT a.*, s.supplier_no, s.name as supplier_name,
        si.name as inspector_name, pr.name as product_name
        FROM audits_lqc a
        INNER JOIN sqim_new.suppliers s ON a.supplier_id = s.id
        INNER JOIN sqim_new.supplier_inspector si ON si.id = a.auditer_id
        INNER JOIN sqim_new.products pr ON pr.id = a.product_id
        WHERE state = 'completed'";
        
        if(!empty($filters['id'])) {
            $sql .= " AND a.id = ?";
            $pass_array[] = $filters['id'];
        }

        if(!empty($filters['start_range']) && !empty($filters['end_range'])) {
            $sql .= " AND a.audit_date BETWEEN ? AND ?";
            $pass_array[] = $filters['start_range'];
            $pass_array[] = $filters['end_range'];
        }
        
        if(!empty($filters['part_id'])) {
            $sql .= " AND a.part_id = ?";
            $pass_array[] = $filters['part_id'];
        }
        
        if(!empty($filters['part_no'])) {
            $sql .= " AND a.part_no = ?";
            $pass_array[] = $filters['part_no'];
        }
        
        if(!empty($filters['supplier_id'])) {
            $sql .= " AND a.supplier_id = ?";
            $pass_array[] = $filters['supplier_id'];
        }
        
        if($this->product_id && @$filters['product_all'] != 'all') {
            $sql .= ' AND a.product_id = ?';
            $pass_array[] = $this->product_id;
        }
        elseif($filters['product_id'] == 'all'){
			 $sql .= ' AND a.product_id = ?';
            $pass_array[] = $filters['product_id'];
		} 
		/* elseif($filters['product_id'] == 'all'){
			  $sql .= ' AND a.product_id = ?';
            $pass_array[] = $filters['product_id']; 
		} */
        $sql .= " GROUP BY a.id
        ORDER BY a.audit_date DESC, a.id DESC";
        if($count) {
            $sql = "SELECT count(id) as c FROM (".$sql.") as sub";
        } else {
            $sql .= " ".$limit;
        }
        // echo $sql;exit;
        
        //$this->db->query($sql, $pass_array)->result_array();
		//echo $this->db->last_query();exit;
        return $this->db->query($sql, $pass_array)->result_array();
    }
    function get_completed_audits_new($filters, $count = false, $limit = '') {
        $pass_array = array();
         //print_r($filters);exit;
        $sql = "SELECT a.*, ac.remark as remark, s.supplier_no, s.name as supplier_name,
        si.name as inspector_name, pr.name as product_name,
		
		acl.ok_count,acl.ng_count
        
		FROM audits_lqc a
        INNER JOIN lqc_audit_defect_code ac on a.id = ac.audit_id 
		INNER JOIN audits_completed_lqc acl on a.id = acl.audit_id 
		INNER JOIN sqim_new.suppliers s ON a.supplier_id = s.id
        INNER JOIN sqim_new.supplier_inspector si ON si.id = a.auditer_id
        INNER JOIN sqim_new.products pr ON pr.id = a.product_id
        WHERE state = 'completed'";
        
        if(!empty($filters['id'])) {
            $sql .= " AND a.id = ?";
            $pass_array[] = $filters['id'];
        }

        if(!empty($filters['start_range']) && !empty($filters['end_range'])) {
            $sql .= " AND a.audit_date BETWEEN ? AND ?";
            $pass_array[] = $filters['start_range'];
            $pass_array[] = $filters['end_range'];
        }
        
        if(!empty($filters['part_id'])) {
            $sql .= " AND a.part_id = ?";
            $pass_array[] = $filters['part_id'];
        }
        
        if(!empty($filters['part_no'])) {
            $sql .= " AND a.part_no = ?";
            $pass_array[] = $filters['part_no'];
        }
        
        if(!empty($filters['supplier_id'])) {
            $sql .= " AND a.supplier_id = ?";
            $pass_array[] = $filters['supplier_id'];
        }
        
        if($this->product_id && @$filters['product_all'] != 'all') {
            $sql .= ' AND a.product_id = ?';
            $pass_array[] = $this->product_id;
        }
        elseif($filters['product_id'] == 'all'){
			/*  $sql .= ' AND a.product_id = ?';
            $pass_array[] = $filters['product_id']; */
		} 
		/* elseif($filters['product_id'] == 'all'){
			  $sql .= ' AND a.product_id = ?';
            $pass_array[] = $filters['product_id']; 
		} */
        $sql .= " GROUP BY a.id
        ORDER BY a.audit_date DESC, a.id DESC";
        if($count) {
            $sql = "SELECT count(id) as c FROM (".$sql.") as sub";
        } else {
            $sql .= " ".$limit;
        }
        // echo $sql;exit;
        
        //$this->db->query($sql, $pass_array)->result_array();
		//echo $this->db->last_query();exit;
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function get_consolidated_audit_report($filters, $count = false, $limit = '') {
        $pass_array = array();
        $sql = "SELECT MAX(a.audit_date) as audit_date, a.supplier_id, a.part_no, a.part_name,
        s.supplier_no, s.name as supplier_name, 
        count(a.lot_no) as no_of_lots,
        SUM(IF(a.ng_count = 0, 1, 0)) as ok_lots,
        SUM(IF(a.ng_count > 0, 1, 0)) as ng_lots,
        pr.name as product_name
        FROM audits_completed a
        INNER JOIN sqim_new.suppliers s 
        ON a.supplier_id = s.id
        INNER JOIN sqim_new.products pr 
        ON pr.id = a.product_id ";
        
        $wheres = '';
        if(!empty($filters['id'])) {
            $wheres[] = "a.id = ?";
            $pass_array[] = $filters['id'];
        }

        if(!empty($filters['start_range']) && !empty($filters['end_range'])) {
            $wheres[] = " a.audit_date BETWEEN ? AND ?";
            $pass_array[] = $filters['start_range'];
            $pass_array[] = $filters['end_range'];
        }
        
        if(!empty($filters['part_id'])) {
            $wheres[] = " a.part_id = ?";
            $pass_array[] = $filters['part_id'];
        }
        
        if(!empty($filters['part_no'])) {
            $wheres[] = " a.part_no = ?";
            $pass_array[] = $filters['part_no'];
        }
        
        if(!empty($filters['supplier_id'])) {
            $wheres[] = " a.supplier_id = ?";
            $pass_array[] = $filters['supplier_id'];
        }
        
        if($this->product_id && @$filters['product_all'] != 'all') {
            $wheres[] = " a.product_id = ?";
            $pass_array[] = $this->product_id;
        }
        elseif($filters['product_id'] ){
			/* $sql .= ' AND a.product_id = ?';
            $pass_array[] = $filters['product_id']; */
		} 
        
        if(!empty($wheres)) {
            $sql .= " WHERE ".implode(' AND ', $wheres);
        }
        
        $sql .= " GROUP BY a.audit_date, a.supplier_id, a.part_id";
        
        if($count) {
            $sql = "SELECT count(*) as c FROM (".$sql.") as sub";
        } else {
            $sql .= " ".$limit;
        }
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
	function get_judgement_result($id) {
        $sql = "SELECT * 
        FROM audits_completed_LQC where audit_id = '".$id."'";
        
        return $this->db->query($sql)->result_array();
    }
    
    function get_audit($auditer_id, $state = '', $date = '', $id = '', $on_hold = 0, $product_id = '') {
        
        $sql = "SELECT a.*, p.name as product_name, p.org_name,
        s.name as supplier_name, s.supplier_no
        FROM audits_lqc a
        INNER JOIN sqim_new.products p
        ON a.product_id = p.id
        INNER JOIN sqim_new.suppliers s
        ON a.supplier_id = s.id";
        
        $wheres = array();
        $pass_array = array();
        
        if(!empty($auditer_id)) {
            $wheres[] = 'a.auditer_id = ?';
            $pass_array[] = $auditer_id;
        }
        
        if(!empty($product_id)) {
            $wheres[] = 'a.product_id = ?';
            $pass_array[] = $product_id;
        }
        
        if($on_hold !== null) {
            $wheres[] = "a.on_hold = ?";
            $pass_array[] = $on_hold;
        }
        
        if(!empty($state)) {
            if(!is_array($state)) {
                $wheres[] = "a.state = ?";
                $pass_array[] = $state;
            } else {
                $wheres[] = "a.state IN (". implode(',', array_fill(0, count($state), '?')).")";
                $pass_array = array_merge($pass_array, $state);
            }
        }
        if(!empty($date)) {
            $wheres[] = "a.audit_date = ?";
            $pass_array[] = $date;
        }
        if(!empty($id)) {
            $wheres[] = "a.id = ?";
            $pass_array[] = $id;
        }
        
        if(!empty($wheres)) {
            $sql .= " WHERE ".implode(' AND ', $wheres);
        }
        
        $sql .= " GROUP BY a.id";
        
        return $this->db->query($sql, $pass_array)->row_array();
    }

    function get_on_hold_audits($auditer_id) {
        $sql = "SELECT a.*, p.name as product_name, p.org_name,
        s.name as supplier_name, s.supplier_no
        FROM audits_lqc a
        INNER JOIN sqim_new.products p
        ON a.product_id = p.id
        INNER JOIN sqim_new.suppliers s
        ON a.supplier_id = s.id
        WHERE a.auditer_id = ?
        AND on_hold = 1
        AND a.state NOT IN ('aborted', 'completed')";
        
        $pass_array = array($auditer_id);
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function check_already_inspected($data) {
        $this->db->where('audit_date'   , $data['audit_date']);
        $this->db->where('product_id'   , $data['product_id']);
        $this->db->where('part_id'      , $data['part_id']);
        $this->db->where('supplier_id'  , $data['supplier_id']);
        $this->db->where('state !='     , 'aborted');
        
        return $this->db->count_all_results('audits_lqc');
    }
    
    function update_audit($data, $audit_id) {
        $needed_array = array('audit_date', 'auditer_id', 'supplier_id', 'product_id', 'part_id', 'part_no', 'part_name', 'prod_lot_qty', 'state', 'on_hold', 'register_datetime');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($audit_id)) {
            $data['lot_no']     = $this->generate_lot_no();
            $data['created']    = date("Y-m-d H:i:s");
            return (($this->db->insert('audits_lqc', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $audit_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('audits_lqc', $data)) ? $audit_id : False);
        }
        
    }
	function update_to_audits_lqc_remove_all($audit_id,$d) {
        
            $this->db->where('id', $audit_id);
            $data['prod_lot_qty'] = $d['or_lot'];
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('audits_lqc', $data)) ? $audit_id : False);
        
        
    }
	function update_to_remaining_lot_history_remove_all($id,$d) {
        $needed_array = array('lqc_remaining_lot');
        $data = array_intersect_key($d, array_flip($needed_array));

        
            $this->db->where('id', $id);
            // $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('remaining_lot_history', $data)) ? $id : False);
        
        
    }

    function change_state($audit_id, $auditer_id, $state) {
        $allowed_state = array('registered','aborted','started','finished','completed');
        if(!in_array($state, $allowed_state))
            return false;
        
        $this->db->where('id', $audit_id);
        $this->db->where('auditer_id', $auditer_id);
        $this->db->set('state', $state);
        $this->db->set('modified', date("Y-m-d H:i:s"));
        
        $response = $this->db->update('audits_lqc');
        return $response;
    }
    
    function hold_resume_audit($audit_id, $on_hold = 1) {
        $this->db->where('id', $audit_id);
        $this->db->set('on_hold', $on_hold);
        $this->db->set('modified', date("Y-m-d H:i:s"));
        
        return $this->db->update('audits_lqc');
    }
    
   /*  function checkpoint_audited($product_id, $part_id, $checkpoint_id, $date) {
        $sql = "SELECT count(*) as count FROM audits a 
        INNER JOIN audit_checkpoints ac
        ON a.id = ac.audit_id AND ac.org_checkpoint_id = ?
        WHERE a.audit_date >= ?
        AND a.product_id = ?
        AND a.part_id = ?
        AND a.state = 'completed'";
        
        $pass_array = array($checkpoint_id, $date, $product_id, $part_id);
        
        $record = $this->db->query($sql, $pass_array);
        
        $count = 0;
        if($record->num_rows() > 0) {
            $record = $record->row_array();
            $count =  $record['count'];
        }
        
        return $count;
    } */
    
    function create_audit_checkpoints($product_id, $part_id, $audit_id, $case, $exclude = '') {
        $this->db->where('audit_id', $audit_id);
        $this->db->delete('lqc_audit_defect_code');
        
        $pass_array = array($product_id, $part_id);
        
        $sql = "INSERT INTO lqc_audit_defect_code(`org_checkpoint_id`, `audit_id`, `checkpoint_no`, `insp_item`, `INSPECTION_SPEC_DETAIL_ID`,`MODULE_CODE`,`UPDATE_PERSON`, `insp_item2`, `spec`, `lsl`, `usl`, `tgt`, `unit`, `checkpoint_type`, `sampling_type`, `inspection_level`, `acceptable_quality`, `sampling_qty`, `created`)
        SELECT c.id, ".$audit_id." as audit_id, c.checkpoint_no, 
        c.insp_item, c.insp_item2, `INSPECTION_SPEC_DETAIL_ID`,`MODULE_CODE`,`UPDATE_PERSON`, c.spec,
        if(c.lsl IS NULL, c.lsl, c.lsl) as lsl,
        if(c.usl IS NULL, c.usl, c.usl) as usl,
        if(c.tgt IS NULL, c.tgt, c.tgt) as tgt,
        if(c.unit IS NULL, c.unit, c.unit) as unit, 
        c.checkpoint_type, ic.sampling_type, ic.inspection_level, ic.acceptable_quality, ";
        
        $sql .= $case.', ';
        
        $sql .= "'".date("Y-m-d H:i:s")."' as created
        FROM checkpoints c 
        LEFT JOIN inspection_config ic
        ON ic.checkpoint_id = c.id
        WHERE c.product_id = ?
        AND c.part_id = ?
        AND c.is_deleted = 0
        AND (c.checkpoint_type = 'LG' OR c.approved_by IS NOT NULL)";
        
        if($exclude) {
            $sql .= " AND c.id NOT IN (".$exclude.")";
            //$pass_array[] = $exclude;
        }

        $sql .= " GROUP BY c.id ORDER BY c.checkpoint_type DESC, c.checkpoint_no ASC";
        
        $this->db->query($sql, $pass_array);
        
        //echo $this->db->last_query(); exit;
        
        return TRUE;
    }
    function create_audit_checkpoints_admin_supplier($product_id, $part_id, $audit_id, $case, $exclude = '',$supplier_id) {
        $this->db->where('audit_id', $audit_id);
        $this->db->delete('lqc_audit_defect_code');
        
        $pass_array = array($product_id, $part_id,$supplier_id);
        
        /* $sql = "INSERT INTO lqc_audit_defect_code(`org_checkpoint_id`, `audit_id`, `checkpoint_no`, `insp_item`, `insp_item2`, `inspection_spec_detail_id`, `module_code`, `update_person`, `spec`,`measure_equipment`, `lsl`, `usl`, `tgt`, `unit`, `checkpoint_type`, `sampling_type`, `inspection_level`, `acceptable_quality`, `sampling_qty`, `created`)
        SELECT c.id, ".$audit_id." as audit_id, c.checkpoint_no, 
        c.insp_item, c.insp_item2, c.spec,c.measure_equipment,
        if(c.lsl IS NULL, c.lsl, c.lsl) as lsl,
        if(c.usl IS NULL, c.usl, c.usl) as usl,
        if(c.tgt IS NULL, c.tgt, c.tgt) as tgt,
        if(c.unit IS NULL, c.unit, c.unit) as unit, 
         c.checkpoint_type, ic.sampling_type, ic.inspection_level, ic.acceptable_quality, ";
        */
		$sql = "INSERT INTO lqc_audit_defect_code(`org_checkpoint_id`, `audit_id`, `checkpoint_no`, `insp_item`, `insp_item2`,`INSPECTION_SPEC_DETAIL_ID`,`MODULE_CODE`,`UPDATE_PERSON`, `spec`,`measure_equipment`, `lsl`, `usl`, `tgt`, `unit`, `checkpoint_type`, `sampling_type`, `inspection_level`, `acceptable_quality`, `sampling_qty`, `created`)
        SELECT c.id, ".$audit_id." as audit_id, c.checkpoint_no, 
        c.insp_item, c.insp_item2, `INSPECTION_SPEC_DETAIL_ID`,`MODULE_CODE`,`UPDATE_PERSON`,		
		c.spec,c.measure_equipment,
        if(c.lsl IS NULL, c.lsl, c.lsl) as lsl,
        if(c.usl IS NULL, c.usl, c.usl) as usl,
        if(c.tgt IS NULL, c.tgt, c.tgt) as tgt,
        if(c.unit IS NULL, c.unit, c.unit) as unit, 
        c.checkpoint_type, ic.sampling_type, ic.inspection_level, ic.acceptable_quality, 
		 ";
        
        $sql .= $case.', ';
        
        $sql .= "'".date("Y-m-d H:i:s")."' as created
        FROM checkpoints c 
        LEFT JOIN inspection_config ic
        ON ic.checkpoint_id = c.id
        WHERE c.product_id = ?
        AND c.part_id = ?
        AND c.is_deleted = 0
        AND ((c.checkpoint_type = 'LG' OR c.approved_by IS NOT NULL) 
		OR (c.checkpoint_type = 'Supplier' AND c.supplier_id = ?  AND c.status = 'Approved')) ";
        
		
        if($exclude) {
            $sql .= " AND c.id NOT IN (".$exclude.")";
            //$pass_array[] = $exclude;
        }

        $sql .= " GROUP BY c.id ORDER BY c.checkpoint_type DESC, c.checkpoint_no ASC";
		
		//echo $sql; exit;
        
        $this->db->query($sql, $pass_array);
        
        // echo $this->db->last_query(); exit;
        
        return TRUE;
    }
    
    function get_required_checkpoint_nos($audit_id) {
        $sql = "SELECT GROUP_CONCAT(`org_checkpoint_id` ORDER BY id) as nos,
        MAX(IF (pointer = 1, org_checkpoint_id, null)) as last
        FROM `lqc_audit_defect_code` 
        WHERE audit_id = ?";
        
        $pass_array = array($audit_id);
        
        $sql .= " GROUP BY audit_id";
        
        return $this->db->query($sql, $pass_array)->row_array();
    }
    
    function get_all_audit_lot($audit_id) {
        $this->db->where('audit_id', $audit_id);
        
        $this->db->order_by('id ASC');
        
        return $this->db->get('lqc_audit_defect_code')->result_array();
    }
    
    function get_count_checkpoint_by_result($audit_id, $result) {
        $this->db->where('audit_id', $audit_id);
        if($result) {
            $this->db->where('result', $result);
        } else {
            $this->db->where('result IS NULL');
        }
        return $this->db->count_all_results('lqc_audit_defect_code');
    }
    
    
    function check_slippage($audit_id) {        
        $this->db->where('audit_id', $audit_id);
        $this->db->where('result IS NULL');
        
        return $this->db->count_all_results('lqc_audit_defect_code');
    }
    
    function get_checkpoint($audit_id, $checkpoint_id) {
        $this->db->where('audit_id', $audit_id);
        $this->db->where('org_checkpoint_id', $checkpoint_id);
        
        return $this->db->get('lqc_audit_defect_code')->row_array();
    }
    
    function record_checkpoint_result($data, $checkpoint_id, $audit_id) {
        $needed_array = array('remark', 'all_values', 'all_results', 'result');
        $data = array_intersect_key($data, array_flip($needed_array));
        
        $this->db->where('id', $checkpoint_id);
        $this->db->where('audit_id', $audit_id);
        $data['result_datetime'] = date("Y-m-d H:i:s");
        $data['pointer'] = 1;
        $data['modified'] = date("Y-m-d H:i:s");
        
        return (($this->db->update('lqc_audit_defect_code', $data)) ? $audit_id : False);
    }
	
    function get_all_audit_parts($auditer_id = '', $supplier_id = '') {
        $sql = "SELECT DISTINCT part_no, part_name 
        FROM audits_lqc WHERE product_id = ? AND state = 'completed' ";
        
        $pass_array = array($this->product_id);
        
        if(!empty($auditer_id)) {
            $sql .= " AND auditer_id = ?";
            $pass_array[] = $auditer_id;
        }
        
        if(!empty($supplier_id)) {
            $sql .= " AND supplier_id = ?";
            $pass_array[] = $supplier_id;
        }
        
		// echo $sql;exit;
        return $this->db->query($sql, $pass_array)->result_array();
    }
	
    function get_all_audit_child_parts($part_nu = '', $supplier_id = '') {
		//echo $supplier_id.'-'.$part_nu;exit;
        $sql = 'SELECT a.part_id,a.part_no,tc.child_part_no FROM `timecheck_plans` tc inner join audits_lqc a on tc.part_id = a.part_id where a.product_id =  ?';
		
        $pass_array = array($this->product_id);
        
         if(!empty($part_nu)) {
            $sql .= ' AND a.part_no = ?';
            $pass_array[] = $part_nu;
        }
        
        if(!empty($supplier_id)) {
            $sql .= " AND a.supplier_id = ".$supplier_id;
            //$pass_array[] = $supplier_id;
        }
        $sql .= "  GROUP by a.part_id,a.part_no,tc.child_part_no";
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function add_to_completed_audits($audit_id) {
        $sql = "INSERT INTO `audits_completed`(`lot_no`, `audit_id`, `audit_date`, `auditer_id`, `supplier_id`, `product_id`, `part_id`, `part_no`, `part_name`, `prod_lot_qty`, `checkpoint_count`, `ok_count`, `ng_count`, `created`)
        SELECT a.lot_no, a.id, a.audit_date, a.auditer_id, a.supplier_id, a.product_id, a.part_id, a.part_no, a.part_name, a.prod_lot_qty,
        COUNT(ac.id) as checkpoint_count,
        SUM(IF(ac.result = 'OK', 1, 0)) as ok_count,
        SUM(IF(ac.result = 'NG', 1, 0)) as ng_count, NOW()
        FROM audits_lqc a
        LEFT JOIN lqc_audit_defect_code ac
        ON a.id = ac.audit_id
        WHERE a.id = ?";
        
        $pass_array = array($audit_id);
        return $this->db->query($sql, $pass_array);
    }
    
    function check_audit_complete_exists($audit_id) {
        $this->db->where('audit_id', $audit_id);
        
        return $this->db->count_all_results('audits_completed');
    }
    
    function delete_audit_complete($audit_id) {
        $this->db->where('audit_id', $audit_id);
        
        return $this->db->delete('audits_completed');
    }
    
    function generate_lot_no() {
        $org_name = $this->session->userdata('org_name');
        $sql = "SELECT MAX(lot_no) as lot_no
        FROM audits_lqc
        WHERE lot_no LIKE ?";
         
        $lot_start = 'S'.$org_name.date('y').date('m');
        $record = $this->db->query($sql, array($lot_start.'%'));

        $lot = 0;
        if($record->num_rows() > 0) {
            $record = $record->row_array();
            $lot =  $record['lot_no'];
        }

        if(empty($lot)) {
            return $lot_start.str_pad(1, 7, '0', STR_PAD_LEFT);
        }

        $new_lot = (int)str_replace($lot_start, '', $lot) + 1;
        return $lot_start.str_pad($new_lot, 7, '0', STR_PAD_LEFT);

    }
	
	function get_completed_admin_audits_completed($filters,$count) {
        $pass_array = array();
         //print_r($filters);exit;
        $sql = "SELECT a.*, s.supplier_no, s.name as supplier_name,
        si.name as inspector_name, pr.name as product_name
        FROM audits_lqc a
        INNER JOIN sqim_new.suppliers s ON a.supplier_id = s.id
        INNER JOIN sqim_new.supplier_inspector si ON si.id = a.auditer_id
        INNER JOIN sqim_new.products pr ON pr.id = a.product_id
        WHERE state = 'completed'";
        
        
        if(!empty($filters['inspection_date'])) {
            $sql .= " AND a.audit_date = ?";
            $pass_array[] = $filters['inspection_date'];
            
        }
        
       
        if(!empty($filters['product_id'])){
			 $sql .= ' AND a.product_id = ?';
            $pass_array[] = $filters['product_id'];
		} 
		//$sql .= " GROUP BY a.id   ORDER BY a.audit_date DESC, a.id DESC";
        if($count) {
            $sql = "SELECT count(id) as c FROM (".$sql.") as sub";
        } 
        // echo $sql;exit;
        
        //$this->db->query($sql, $pass_array)->result_array();
		//echo $this->db->last_query();exit;
        return $this->db->query($sql, $pass_array)->result_array();
    }
    
	function get_completed_admin_audits($filters,$count) {
        $pass_array = array();
         //print_r($filters);exit;
        $sql = "SELECT a.*, s.supplier_no, s.name as supplier_name,
        si.name as inspector_name, pr.name as product_name
        FROM audits_lqc a
        INNER JOIN sqim_new.suppliers s ON a.supplier_id = s.id
        INNER JOIN sqim_new.supplier_inspector si ON si.id = a.auditer_id
        INNER JOIN sqim_new.products pr ON pr.id = a.product_id
        ";
        
        
        if(!empty($filters['inspection_date'])) {
            $sql .= " AND a.audit_date = ?";
            $pass_array[] = $filters['inspection_date'];
            
        }
        
       
        if(!empty($filters['product_id'])){
			 $sql .= ' AND a.product_id = ?';
            $pass_array[] = $filters['product_id'];
		} 
		//$sql .= " GROUP BY a.id   ORDER BY a.audit_date DESC, a.id DESC";
        if($count) {
            $sql = "SELECT count(id) as c FROM (".$sql.") as sub";
        } 
        // echo $sql;exit;
        
        //$this->db->query($sql, $pass_array)->result_array();
		//echo $this->db->last_query();exit;
        return $this->db->query($sql, $pass_array)->result_array();
    }
    function update_send_to_gerp($audit_id){
		
		$this->db->where('audit_id', $audit_id);
        $this->db->set('sent_to_gerp', 1);
        
        $response = $this->db->update('audits_completed');
        return $response;
	}
	
	//For LQC Inspection
	function get_audit_lqc($auditer_id, $state = '', $date = '', $id = '', $on_hold = 0, $product_id = '') {
        
        $sql = "SELECT a.*, p.name as product_name, p.org_name,
        s.name as supplier_name, s.supplier_no
        FROM audits_lqc a
        INNER JOIN sqim_new.products p
        ON a.product_id = p.id
        INNER JOIN sqim_new.suppliers s
        ON a.supplier_id = s.id";
        
        $wheres = array();
        $pass_array = array();
        
        if(!empty($auditer_id)) {
            $wheres[] = 'a.auditer_id = ?';
            $pass_array[] = $auditer_id;
        }
        
        if(!empty($product_id)) {
            $wheres[] = 'a.product_id = ?';
            $pass_array[] = $product_id;
        }
        
        if($on_hold !== null) {
            $wheres[] = "a.on_hold = ?";
            $pass_array[] = $on_hold;
        }
        
        if(!empty($state)) {
            if(!is_array($state)) {
                $wheres[] = "a.state = ?";
                $pass_array[] = $state;
            } else {
                $wheres[] = "a.state IN (". implode(',', array_fill(0, count($state), '?')).")";
                $pass_array = array_merge($pass_array, $state);
            }
        }
        if(!empty($date)) {
            $wheres[] = "a.audit_date = ?";
            $pass_array[] = $date;
        }
        if(!empty($id)) {
            $wheres[] = "a.id = ?";
            $pass_array[] = $id;
        }
        
        if(!empty($wheres)) {
            $sql .= " WHERE ".implode(' AND ', $wheres);
        }
        
        $sql .= " GROUP BY a.id";
        
        return $this->db->query($sql, $pass_array)->row_array();
    }
	function check_already_inspected_lqc($data) {
        $this->db->where('audit_date'   , $data['audit_date']);
        $this->db->where('product_id'   , $data['product_id']);
        $this->db->where('part_id'      , $data['part_id']);
        $this->db->where('supplier_id'  , $data['supplier_id']);
        $this->db->where('state !='     , 'aborted');
        
        return $this->db->count_all_results('audits_lqc');
    }
    function update_audit_lqc($data, $audit_id) {
        $needed_array = array('audit_date', 'auditer_id', 'supplier_id', 'product_id', 'part_id', 'part_no', 'part_name', 'prod_lot_qty', 'state', 'on_hold', 'register_datetime');
        $data = array_intersect_key($data, array_flip($needed_array));
		
		
	   if(empty($audit_id)) {
			$data['lot_no']     = $this->generate_lot_no();
            $data['created']    = date("Y-m-d H:i:s");
            return (($this->db->insert('audits_lqc', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $audit_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('audits_lqc', $data)) ? $audit_id : False);
        }
        
    }
	function update_audit_defect_code($audit_id,$defect_occured,$serial_no,$result,$defect_occured_ids,$remark) {
		
       $sql = "INSERT INTO `lqc_audit_defect_code`(`defect_occured_ids`, `audit_id`, `result`, `created`,  `product_id`, `defect_occured`, `serial_no`, `remark`) VALUES ('".$defect_occured_ids."',".$audit_id.",'".$result."','".date("Y-m-d H:i:s")."',". $this->product_id.",'".$defect_occured."','".$serial_no."','".$remark."')";

     // return (($this->db->insert('lqc_audit_defect_code', $data)) ? $this->db->insert_id() : False);
	 return $this->db->query($sql) ? $this->db->insert_id() : False;
        
    }
	function review_update_audit_defect_code($defect_occured,$result,$defect_occured_ids,$remark,$id) {		
		//$sql = "UPDATE `lqc_audit_defect_code` SET `defect_occured_ids`='".$defect_occured_ids."',`result`='".$result."',`modified`='".date("Y-m-d H:i:s")."',`defect_occured`='".$defect_occured."',`remark`='".$remark."' WHERE id = ".$id;
		
		$this->db->where('id', $id);
        $this->db->set('defect_occured_ids',$defect_occured_ids);
        $this->db->set('result', $result);
        $this->db->set('defect_occured', $defect_occured);
        $this->db->set('remark', $remark);
        $this->db->set('modified', date("Y-m-d H:i:s"));
		
		return (($this->db->update('lqc_audit_defect_code')) ? $id : False);
    }
	function retest_update_audit_defect_code($defect_occured,$result,$defect_occured_ids,$remark,$retest_remark,$id) {		
		//$sql = "UPDATE `lqc_audit_defect_code` SET `defect_occured_ids`='".$defect_occured_ids."',`result`='".$result."',`modified`='".date("Y-m-d H:i:s")."',`defect_occured`='".$defect_occured."',`remark`='".$remark."' WHERE id = ".$id;
		
		$this->db->where('id', $id);
        $this->db->set('defect_occured_ids',$defect_occured_ids);
        $this->db->set('result', $result);
        $this->db->set('defect_occured', $defect_occured);
        $this->db->set('remark', $remark);
        $this->db->set('retest_remark', $retest_remark);
        $this->db->set('modified', date("Y-m-d H:i:s"));
		
		return (($this->db->update('lqc_audit_defect_code')) ? $id : False);
    }
	function change_state_lqc($audit_id, $auditer_id, $state) {
        $allowed_state = array('registered','aborted','started','finished','completed','skiped','retest');
        if(!in_array($state, $allowed_state))
            return false;
        
        $this->db->where('id', $audit_id);
        $this->db->where('auditer_id', $auditer_id);
        $this->db->set('state', $state);
        $this->db->set('modified', date("Y-m-d H:i:s"));
        
        $response = $this->db->update('audits_lqc');
        return $response;
    }
    function update_prod_lot_qty($audit_id, $update_prod_lot_qty) {
        
        $this->db->where('id', $audit_id);
        $this->db->set('remaining_prod_lot_qty', $update_prod_lot_qty);
        $this->db->set('modified', date("Y-m-d H:i:s"));
        
        $response = $this->db->update('audits_lqc');
        return $response;
    }
    function get_all_audit_lqc_defect_codes($audit_id) {
        $this->db->where('audit_id', $audit_id);
         
        return $this->db->get(' lqc_audit_defect_code')->result_array();
    }
	function get_all_audit_lqc_defect_code($lqc_dc_id , $audit_id = '') {
        $this->db->where('id', $lqc_dc_id);
		if(!empty($audit_id)){
			$this->db->where('audit_id', $audit_id);
		}
        return $this->db->get('lqc_audit_defect_code')->row_array();
    }
    function get_count_audit_lqc_defect_by_result($audit_id, $result) {
        $this->db->where('audit_id', $audit_id);
        if($result) {
            $this->db->where('result', $result);
        } else {
            $this->db->where('result IS NULL');
        }
        return $this->db->count_all_results('lqc_audit_defect_code');
    }
	 function check_duplicate_qrcode($qr_code,$audit_id) {
        $this->db->where('audit_id', $audit_id);
        $this->db->where('serial_no', $qr_code);
        
        return $this->db->count_all_results('lqc_audit_defect_code');
    }
	
	function add_to_completed_audits_lqc($audit_id) {
		// echo $audit_id;
        $sql = "INSERT INTO `audits_completed_lqc`(`lot_no`, `audit_id`, `audit_date`, `auditer_id`, `supplier_id`, `product_id`, `part_id`, `part_no`, `part_name`, `prod_lot_qty`, `tot_count`, `ok_count`, `ng_count`, `created`)
        SELECT a.lot_no, a.id, a.audit_date, a.auditer_id, a.supplier_id, a.product_id, a.part_id, a.part_no, a.part_name, a.prod_lot_qty,
        COUNT(ac.id) as defect_audit_count,
        SUM(IF(ac.result = 'OK', 1, 0)) as ok_count,
        SUM(IF(ac.result = 'NG', 1, 0)) as ng_count, NOW()
        FROM audits_lqc a
        LEFT JOIN lqc_audit_defect_code ac
        ON a.id = ac.audit_id
        WHERE a.id = ?";
        
        $pass_array = array($audit_id);
        return $this->db->query($sql, $pass_array);
    }
	function add_to_completed_audits_lqc_remove($audit_id) {
		// echo $audit_id;
        $sql = "INSERT INTO `audits_completed_lqc`(`lot_no`, `audit_id`, `audit_date`, `auditer_id`, `supplier_id`, `product_id`, `part_id`, `part_no`, `part_name`, `prod_lot_qty`, `tot_count`, `ok_count`, `ng_count`, `created`)
        SELECT a.lot_no, a.id, a.audit_date, a.auditer_id, a.supplier_id, a.product_id, a.part_id, a.part_no, a.part_name, 
        COUNT(ac.id) as prod_lot_qty,
        COUNT(ac.id) as defect_audit_count,
        SUM(IF(ac.result = 'OK', 1, 0)) as ok_count,
        SUM(IF(ac.result = 'NG', 1, 0)) as ng_count, NOW()
        FROM audits_lqc a
        LEFT JOIN lqc_audit_defect_code ac
        ON a.id = ac.audit_id
        WHERE a.id = ?";
        
        $pass_array = array($audit_id);
        return $this->db->query($sql, $pass_array);
    }
    function send_to_sqim($audit_id) {
		// echo $audit_id;
      $sql = "INSERT INTO sqim_new.`completed_lqc_inspection`(`lot_no`, `audit_id`, `audit_date`, `auditer_id`, `supplier_id`, `product_id`, `part_id`, `part_no`, `part_name`, `prod_lot_qty`, `tot_count`, `ok_count`, `ng_count`, `created`)
        SELECT a.lot_no, a.id, a.audit_date, a.auditer_id, a.supplier_id, a.product_id, a.part_id, a.part_no, a.part_name, a.prod_lot_qty,
        COUNT(ac.id) as defect_audit_count,
        SUM(IF(ac.result = 'OK', 1, 0)) as ok_count,
        SUM(IF(ac.result = 'NG', 1, 0)) as ng_count, NOW()
        FROM lqc_db.audits_lqc a
        LEFT JOIN lqc_db.lqc_audit_defect_code ac
        ON a.id = ac.audit_id
        WHERE a.id = ?";
        
        
        $pass_array = array($audit_id);
        return $this->db->query($sql, $pass_array);
    }
	function send_to_sqim_remove($audit_id) {
		// echo $audit_id;
      $sql = "INSERT INTO sqim_new.`completed_lqc_inspection`(`lot_no`, `audit_id`, `audit_date`, `auditer_id`, `supplier_id`, `product_id`, `part_id`, `part_no`, `part_name`, `prod_lot_qty`, `tot_count`, `ok_count`, `ng_count`, `created`)
        SELECT a.lot_no, a.id, a.audit_date, a.auditer_id, a.supplier_id, a.product_id, a.part_id, a.part_no, a.part_name, COUNT(ac.id) as prod_lot_qty,
        COUNT(ac.id) as defect_audit_count,
        SUM(IF(ac.result = 'OK', 1, 0)) as ok_count,
        SUM(IF(ac.result = 'NG', 1, 0)) as ng_count, NOW()
        FROM lqc_db.audits_lqc a
        LEFT JOIN lqc_db.lqc_audit_defect_code ac
        ON a.id = ac.audit_id
        WHERE a.id = ?";
        
        
        $pass_array = array($audit_id);
        return $this->db->query($sql, $pass_array);
    }
	function get_aborted_lqc($part_id,$supplier_id){
					
        $sql = "SELECT * FROM `audits_lqc` 
		where product_id = ".$this->product_id." AND state like 'aborted' AND part_id = ".$part_id ." ORDER by created desc limit 0,1";
		
		return $this->db->query($sql)->row_array();
       
    }
	function get_all_lqc_inspection_completed($product_id,$supplier_id){
					
        $sql = "SELECT * FROM `audits_lqc` 
		where product_id = ".$product_id." AND state like 'completed' AND supplier_id = ".$supplier_id;
		
		return $this->db->query($sql)->result_array();
       
    }
	
	function part_related_qrcode($qr_code,$part_id) {
		$q =  $qr_code.'.png';
        $sql = "SELECT * FROM `qr_code_print` WHERE part_id=".$part_id." AND find_in_set('".$q."',qr_codes)";
		return $this->db->query($sql)->row_array();
    }
    
	//For LQC Inspection
}
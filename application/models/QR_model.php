<?php
class QR_model extends CI_Model {

    function update_qr_print($data, $qr_id = ''){
        $needed_array = array('supplier_id','product_id', 'part_id', 'qr_code_qty','qr_codes');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($qr_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('qr_code_print', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $qr_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('qr_code_print', $data)) ? $qr_id : False);
        }
        
    }
    function maintain_print_count($data){
		// print_r($data);exit;
        $needed_array = array('qr_code','printed_by','product_id','part_id','supplier_id');
        $data = array_intersect_key($data, array_flip($needed_array));
        $data['created'] = date("Y-m-d H:i:s");
        $data['print_date'] = date("Y-m-d H:i:s");
        return (($this->db->insert('qr_print_history', $data)) ? $this->db->insert_id() : False);
    }
        
    function get_all_qr_print($supplier_id = '') {
        $pass_array = array();
        $sql = 'SELECT qr.*, s.supplier_no, s.name as supplier_name ,pp.name as part_name, pp.code as partno
        FROM qr_code_print as qr
        INNER JOIN sqim_new.suppliers s
        ON qr.supplier_id = s.id
		INNER JOIN sqim_new.product_parts pp
        ON qr.part_id = pp.id
		
		WHERE qr.product_id = '.$this->product_id;
        
        if($supplier_id) {
            $sql .= ' AND qr.supplier_id = ?';
            $pass_array = array($supplier_id);
        }
        
        return $this->db->query($sql, $pass_array)->result_array();
    }
	function get_qr_print_history($supplier_id = '') {
        $pass_array = array();
        $sql = 'SELECT qr.*, pp.name as part_name, pp.code as partno, COUNT(qr_code) as cnt_qr_code, GROUP_CONCAT(printed_by) as printed_by,GROUP_CONCAT(print_date) as print_date,GROUP_CONCAT(reprint_remark) as reprint_remark
        FROM qr_print_history as qr
		INNER JOIN sqim_new.product_parts pp
        ON qr.part_id = pp.id
		
		WHERE qr.product_id = '.$this->product_id;
        
        if($supplier_id) {
            $sql .= ' AND qr.supplier_id = ?';
            $pass_array = array($supplier_id);
        }
        
		$sql .= ' GROUP BY qr_code having count(qr_code) > 0';
		
		 // echo $sql;exit;
        return $this->db->query($sql, $pass_array)->result_array();
    }
	function get_qr_print_history_qrs($qrs) {
        $pass_array = array();
        $sql = "SELECT *  FROM `qr_print_history` qr WHERE `qr_code` IN (".$qrs.")
		
		AND qr.product_id = ".$this->product_id;
        
        // echo $sql;exit;
        return $this->db->query($sql, $pass_array)->result_array();
    }
	
    function get_qr_print($id) {
        $this->db->where('id', $id);
        return $this->db->get('qr_code_print')->row_array();
    } 
	
	function get_print_by_qr($qr) {
        $this->db->where('qr_code', $qr);
        return $this->db->get('qr_print_history')->result_array();
    }
    
	function part_related_qrcode($qr_code,$part_id) {
		// echo $qr_code;
        /* $this->db->where('part_id', $part_id);
        $this->db->like('qr_codes', $qr_code, 'both'); */
		$sql = "SELECT * FROM `qr_code_print` WHERE part_id=".$part_id." AND `qr_codes` LIKE '%".$qr_code."%'";
		// echo $sql;exit;
        return $this->db->query($sql)->row_array();
    }
    
    function delete_phone_number($id, $supplier_id = '') {
        if(!empty($id)) {
            $this->db->where('id', $id);
            
            if($supplier_id) {
                $this->db->where('supplier_id', $supplier_id);
            }
        
            $this->db->delete('phone_numbers');

            if($this->db->affected_rows() > 0) {
                return TRUE;
            }
        }

        return FALSE;
    }

	function update_remark($data = array()){
		
		$sql  = "update `qr_print_history` set print_date = '".date("Y-m-d H:i:s")."', reprint_remark = '".$data['print_remark']. "' WHERE id IN (".$data['print_idds'].")";
		return $this->db->query($sql);
	}
}

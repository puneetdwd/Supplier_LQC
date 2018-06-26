<?php
class Product_model extends CI_Model {
    function add_product($data, $product_id){
		$sqim_product_table = SQIM_DB.'.products';
        $needed_array = array('org_id', 'org_name', 'code', 'name');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($product_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert($sqim_product_table, $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $product_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update($sqim_product_table, $data)) ? $product_id : False);
        }
        
    }
        
    function get_all_products() {
		// echo $sqimdb;exit;
        $sql = 'SELECT * FROM '.SQIM_DB.'.products';
        
        return $this->db->query($sql)->result_array();
    }
	
	function get_parts_id_by_part1($part_no) {
		//$part_no = 'AAN75689719';
		$sql = "SELECT a.part_id,a.part_no,tc.child_part_no FROM `timecheck_plans` tc inner join audits a on tc.part_id = a.part_id where a.product_id = ? AND a.part_no = ? GROUP by a.part_id,a.part_no,tc.child_part_no;";
		        
        return $this->db->query($sql,array($this->product_id,$part_no))->result_array();
    }
    
	function get_part_by_part_no($part_no) {
		$sqim_part_table = SQIM_DB.'.product_parts';
		$sqim_product_table = SQIM_DB.'.products';
        $this->db->where('code', $part_no);
        $this->db->where('product_id', $this->product_id);

        return $this->db->get($sqim_part_table)->row_array();
    }
    function get_all_phone_numbers($supplier_id) {
        $this->db->where('supplier_id', $supplier_id);
        $this->db->order_by('name');
        
        return $this->db->get('phone_numbers')->result_array();
    }
    
    function get_product($id) {
		
		$sqim_product_table = SQIM_DB.'.products';
		
        $this->db->where('id', $id);

        return $this->db->get($sqim_product_table)->row_array();
    }
	
	function get_part($id) {
		$sqim_part_table = SQIM_DB.'.product_parts';
		
        $this->db->where('id', $id);

        return $this->db->get($sqim_part_table)->row_array();
    }
    
    function get_product_id_by_name($code) {
		
		$sqim_product_table = SQIM_DB.'.products';
        $this->db->where('code', $code);

        return $this->db->get($sqim_product_table)->row_array();
    }
    
    function get_part_id_by_name($code, $product_id) {
        $sqim_part_table = SQIM_DB.'.product_parts';
		
        $this->db->where('product_id', $product_id);
        $this->db->where('code', $code);
        return $this->db->get($sqim_part_table)->row_array();
    }

    function get_all_product_parts($product_id) {
        $sql = "SELECT pp.*, pp.code as part_no
        FROM ".SQIM_DB.".product_parts pp
        WHERE pp.is_deleted = 0
        AND pp.product_id = ?
		
        ORDER BY pp.name";
        
        return $this->db->query($sql, array($product_id))->result_array();
    }
	function get_all_product_parts_new($product_id) {
        $sql = "SELECT pp.*, pp.code as part_no
        FROM ".SQIM_DB.".product_parts pp
        WHERE pp.is_deleted = 0
        AND pp.product_id = ?
		 GROUP BY pp.code
        ORDER BY pp.name";
        
        return $this->db->query($sql, array($product_id))->result_array();
    }
    
    function get_all_product_parts_by_supplier($product_id, $supplier_id) {
        $sql = "SELECT pp.*, pp.code as part_no
            FROM ".SQIM_DB.".product_parts pp
            INNER JOIN ".SQIM_DB.".sp_mappings sp 
            ON sp.part_id = pp.id
            WHERE pp.is_deleted = 0
            AND pp.product_id = ?
            AND sp.supplier_id = ?
            GROUP BY pp.code
            ORDER BY pp.name";
        
        return $this->db->query($sql, array($product_id, $supplier_id))->result_array();
    }
	function get_all_product_parts_by_supplier_new($product_id, $supplier_id) {
        $sql = "SELECT pp.*, pp.code as part_no
            FROM ".SQIM_DB.".product_parts pp
            INNER JOIN ".SQIM_DB.".sp_mappings sp 
            ON sp.part_id = pp.id
            WHERE pp.is_deleted = 0
            AND pp.product_id = ?
            AND sp.supplier_id = ?
            GROUP BY pp.name
            ORDER BY pp.name";
        
        return $this->db->query($sql, array($product_id, $supplier_id))->result_array();
    }
	function get_all_product_parts_by_supplier_new1($product_id, $supplier_id) {
        $sql = "SELECT pp.*, pp.code as part_no
            FROM ".SQIM_DB.".product_parts pp
            INNER JOIN ".SQIM_DB.".sp_mappings sp 
            ON sp.part_id = pp.id
            WHERE pp.is_deleted = 0
            AND pp.product_id = ?
            AND sp.supplier_id = ?
            GROUP BY pp.code
            ORDER BY pp.name";
        
        return $this->db->query($sql, array($product_id, $supplier_id))->result_array();
    }
	
    
    function get_all_distinct_product_parts($product_id) {
        $sql = "SELECT pp.*
        FROM ".SQIM_DB.".product_parts pp
        WHERE pp.is_deleted = 0
        AND pp.product_id = ?
        GROUP BY pp.name
        ORDER BY pp.name";
        
        return $this->db->query($sql, array($product_id))->result_array();
    }
    
    function get_all_part_numbers_by_part_name($part_name) {
        $sql = "SELECT pp.*
        FROM ".SQIM_DB.".product_parts pp
        WHERE pp.is_deleted = 0
        AND pp.name = ?
        ORDER BY pp.code";
        
        return $this->db->query($sql, array($part_name))->result_array();
    }
	function get_all_part_numbers_by_part_names($part_name,$product_id) {
        $sql = "SELECT pp.*
        FROM ".SQIM_DB.".product_parts pp
        WHERE pp.is_deleted = 0
        AND pp.name = ? AND pp.product_id = ?
        ORDER BY pp.code";
        
        return $this->db->query($sql, array($part_name,$product_id))->result_array();
    }
	function get_all_part_numbers_by_part_names_new($part_name,$product_id) {
        $sql = "SELECT pp.*
        FROM ".SQIM_DB.".product_parts pp
		inner join ".SQIM_DB.".sp_mappings sp on sp.part_id = pp.id 
        WHERE pp.is_deleted = 0
        AND pp.name = ? AND pp.product_id = ? AND sp.supplier_id = ?
         group BY pp.code ORDER BY pp.code";
        
        return $this->db->query($sql, array($part_name,$product_id,$this->supplier_id))->result_array();
    }
    function all_part_numbers_by_part_names($part_name,$part_id,$product_id) {
        $sql = "SELECT pp.*
        FROM ".SQIM_DB.".product_parts pp
        WHERE pp.is_deleted = 0
        AND pp.name = ? AND pp.product_id = ?
        ORDER BY pp.code";
        
        return $this->db->query($sql, array($part_name,$product_id))->result_array();
    }
    
    function get_all_parts() {
       $sql = "SELECT pp.*, p.name as product_name, 
       p.code as product_code
       FROM ".SQIM_DB.".product_parts as pp
       INNER JOIN ".SQIM_DB.".products as p
       ON pp.product_id = p.id";
       
       return $this->db->query($sql)->result_array();
    }
    
    function get_all_distinct_part_name($product_id='', $supplier_id='') {
        $sql = "SELECT pp.*
        FROM ".SQIM_DB.".product_parts as pp";
       
        if(!empty($supplier_id)) {
            $sql .= " INNER JOIN ".SQIM_DB.".sp_mappings sp 
            ON sp.part_id = pp.id";
        }
        
        $sql .= " WHERE pp.is_deleted = 0
        AND pp.product_id = ?";
        
        $pass_array = array($product_id);
        if(!empty($supplier_id)) {
            $sql .= ' AND sp.supplier_id = ?';
            $pass_array[] = $supplier_id;
        }
        
        $sql .= " GROUP BY pp.name";
       
       return $this->db->query($sql, $pass_array)->result_array();
    }
    
    function get_product_part_by_code($product_id, $code) {
		$sqim_part_table = SQIM_DB.'.product_parts';
        $this->db->where('code', $code);
        $this->db->where('product_id', $product_id);
        $this->db->where('is_deleted', 0);
        
        return $this->db->get($sqim_part_table )->row_array();
    }
    
    function get_product_part($product_id, $id) {
        $sql = "SELECT pp.*
        FROM ".SQIM_DB.".product_parts pp
        WHERE pp.is_deleted = 0
        AND pp.product_id = ?
        AND pp.id = ?";
        
        return $this->db->query($sql, array($product_id, $id))->row_array();
    }
    
    function update_product_part($data, $part_id = ''){
		
			$sqim_part_table = SQIM_DB.'.product_parts';
			
        $needed_array = array('code', 'name', 'product_id', 'is_deleted');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($part_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert($sqim_part_table, $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $part_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update($sqim_part_table, $data)) ? $part_id : False);
        }
    }

    function insert_parts($parts, $product_id) {
        $this->db->insert_batch($sqim_part_table, $parts);
        
        $this->remove_dups_parts($product_id);
    }
    
    function remove_dups_parts($product_id) {
        $sql = "DELETE FROM ".SQIM_DB.".product_parts 
        WHERE id NOT IN (
            SELECT * FROM (
                SELECT MIN(id) 
                FROM ".SQIM_DB.".product_parts 
                WHERE product_id = ? 
                GROUP BY product_id, code, name
            ) as d
        ) AND product_id = ?";
        
        return $this->db->query($sql, array($product_id, $product_id));
    }
	
	 function get_all_parts_product_id($product_id) {
       $sql = "SELECT pp.*, p.name as product_name, 
       p.code as product_code
       FROM ".SQIM_DB.".product_parts as pp
       INNER JOIN ".SQIM_DB.".products as p
       ON pp.product_id = p.id
	   where pp.product_id = ? group by pp.name";
       
       return $this->db->query($sql,$product_id)->result_array();
    }
	function parts_foolproof_map($filters,$product_id) {
       $sql = "SELECT pp.*, p.name as product_name, 
       p.code as product_code
       FROM ".SQIM_DB.".product_parts as pp
       INNER JOIN ".SQIM_DB.".products as p
       ON pp.product_id = p.id
	   where pp.is_deleted = 0 AND pp.product_id = ? ";
       
        /* $sql .= " WHERE pp.is_deleted = 0
        AND pp.product_id = ?"; */
              
        $pass_array = array($product_id);
		
        if(!empty($filters['part_name'])) {
            $sql .= ' AND pp.name = ?';
            $pass_array[] = $filters['part_name'];
        }
		if(!empty($filters['part_id'])) {
            $sql .= ' AND pp.id = ?';
            $pass_array[] = $filters['part_id'];
        }
        
        //$sql .= " GROUP BY pp.name";
       
       return $this->db->query($sql, $pass_array)->result_array();
	   
    }
	
	//For Upload Defect Code
	function check_duplicate_defect($part_id,$supplier_id) {
        $sql = "SELECT * FROM defect_codes WHERE `is_deleted` = 0 AND part_id = ".$part_id." AND supplier_id = ".$supplier_id." AND product_id = ".$this->product_id ;
        
        return $this->db->query($sql)->result_array();
    }
	
	function get_defect_code_for_audit($product_id,$part_id) {
        $sql = "SELECT * FROM defect_codes WHERE is_deleted = 0 AND part_id = ".$part_id."  AND product_id = ".$product_id;  
        return $this->db->query($sql)->result_array();
    }
	
	function delete_defect_by_part_id($part_id) {
        $sql = "UPDATE `defect_codes` SET `is_deleted`=1 WHERE part_id = ".$part_id ." AND product_id = ".$this->product_id ;
        
        return $this->db->query($sql);
    }
	function delete_pd_mapping($defect_id) {
        $sql = "UPDATE `defect_codes` SET `is_deleted`=1 WHERE id = ".$defect_id;
        
        return $this->db->query($sql);
    }
	
	function insert_pd_mappings($defects) {
        $this->db->insert_batch('defect_codes', $defects);
        
        //$this->remove_dups_defects($this->product_id);
    }
    
    function remove_dups_defects($part_id , $product_id) {
        $sql = "DELETE FROM defect_codes 
        WHERE id NOT IN (
            SELECT * FROM (
                SELECT MIN(id) 
                FROM defect_codes 
                WHERE part_id = ? 
                GROUP BY product_id, part_id, defect_description, defect_description_detail
				
				
            ) as d
        ) AND product_id = ?";
        
        return $this->db->query($sql, array($product_id, $product_id));
    }
	function get_defect_code_mappings() {
        $sql = "SELECT dc.*,p.name as product_name, s.name as supplier_name, s.supplier_no,
        pp.name as part_name,pp.code as part_no
        FROM defect_codes dc
        INNER JOIN ".SQIM_DB.".suppliers s
        ON dc.supplier_id = s.id
        INNER JOIN ".SQIM_DB.".products p
        ON dc.product_id = p.id
        INNER JOIN ".SQIM_DB.".product_parts pp
        ON dc.part_id = pp.id
        WHERE dc.is_deleted = 0 AND dc.product_id = ".$this->product_id;
        
        return $this->db->query($sql)->result_array();
    }
	
	function get_defect_code_mappings_by_partid($product_id,$part_id,$supplier_id = '') {
        $sql = "SELECT dc.*,p.name as product_name, s.name as supplier_name, s.supplier_no,
        pp.name as part_name,pp.code as part_no
        FROM defect_codes dc
        INNER JOIN ".SQIM_DB.".suppliers s
        ON dc.supplier_id = s.id
        INNER JOIN ".SQIM_DB.".products p
        ON dc.product_id = p.id
        INNER JOIN ".SQIM_DB.".product_parts pp
        ON dc.part_id = pp.id
        WHERE dc.is_deleted = 0 AND dc.product_id = ".$product_id." AND dc.part_id = ".$part_id;
        if(!empty($supplier_id)) {
            $sql .= ' AND dc.supplier_id = '.$supplier_id;
        }
        
        return $this->db->query($sql)->result_array();
    }
	function get_defect_code($product_id, $defect_id) {
        $sql = "SELECT dc.*,p.name as product_name, s.name as supplier_name, s.supplier_no,
        pp.name as part_name,pp.code as part_no
        FROM defect_codes dc
        INNER JOIN ".SQIM_DB.".suppliers s
        ON dc.supplier_id = s.id
        INNER JOIN ".SQIM_DB.".products p
        ON dc.product_id = p.id
        INNER JOIN ".SQIM_DB.".product_parts pp
        ON dc.part_id = pp.id
        WHERE dc.id = ".$defect_id." AND dc.product_id = ".$product_id;
        
        return $this->db->query($sql)->row_array();
    }
	function update_defect_code($data, $defect_id = ''){
        $needed_array = array('part_id', 'supplier_id', 'product_id', 'defect_description', 'defect_description_detail', 'product_id');
        $data = array_intersect_key($data, array_flip($needed_array));
        // print_r($data);exit;
        if(empty($defect_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('defect_codes', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $defect_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('defect_codes', $data)) ? $defect_id : False);
        }
    }
	
	function get_all_product_parts_by_supplier_defect_code($product_id, $supplier_id) {
        $sql = "SELECT pp.*, pp.code as part_no
            FROM ".SQIM_DB.".product_parts pp
            INNER JOIN ".SQIM_DB.".sp_mappings sp 
            ON sp.part_id = pp.id
			INNER JOIN defect_codes dc 
            ON dc.part_id = pp.id
            WHERE pp.is_deleted = 0
            AND pp.product_id = ?
            AND sp.supplier_id = ?
            GROUP BY pp.code
            ORDER BY pp.name";
        
        return $this->db->query($sql, array($product_id, $supplier_id))->result_array();
    }
	function get_sp_mapping_by_pid_sid($pid,$ppid,$sid) {
        $sql = "SELECT pp.*
        FROM ".SQIM_DB.".product_parts pp
		inner join ".SQIM_DB.".sp_mappings sp on sp.part_id = pp.id 
        WHERE pp.is_deleted = 0
        AND pp.product_id = ? AND sp.supplier_id = ? AND pp.id = ?
        ORDER BY pp.code";
        
        return $this->db->query($sql, array($pid,$sid,$ppid))->result_array();
    }
	
	function get_all_product_parts_by_supplier_as_per_plan($product,$supplier){
		$sql = "SELECT * FROM `production_plans` pp 
		inner join ".SQIM_DB.".product_parts spp on pp.part_id = spp.id 
		where pp.lot_size > 0 AND pp.supplier_id = ".$supplier." AND pp.product_id = ".$product." group by spp.id";
		
		return $this->db->query($sql)->result_array();
	}
	
	function update_remaining_lot_history($data){
					
        $needed_array = array('part_id', 'part_no', 'product_id', 'lqc_planned_lot','lqc_remaining_lot');
        $data = array_intersect_key($data, array_flip($needed_array));
		$data['created'] = date("Y-m-d H:i:s");
		return (($this->db->insert('remaining_lot_history', $data)) ? $this->db->insert_id() : False);
       
    }
	function get_remaining_lot_history($part_id){
					
        $sql = "SELECT * FROM remaining_lot_history 
		where product_id = ".$this->product_id." AND part_id = ".$part_id ." ORDER by id desc limit 0,1";
		
		return $this->db->query($sql)->row_array();
       
    }
	//For Upload Defect Code
}
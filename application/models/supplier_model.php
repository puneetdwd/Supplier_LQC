<?php
class Supplier_model extends CI_Model {

    function __construct() {
        parent::__construct();
		$this->sqim_supplier = SQIM_DB.'.suppliers';
		$this->sqim_supplier_inspector = SQIM_DB.'.supplier_inspector';
        require_once APPPATH .'libraries/pass_compat/password.php';
    }
    
    function add_supplier($data, $supplier_id){
        $needed_array = array('name', 'supplier_no', 'email', 'password', 'is_active');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(!empty($data['name'])) {
            $data['name'] = ucwords(strtolower($data['name']));
        }
        
        if(!empty($data['password'])) {
            $cost = $this->config->item('hash_cost');
            $data['password'] = password_hash(SALT .$data['password'], PASSWORD_BCRYPT, array('cost' => $cost));
        } else {
            unset($data['password']);
        }
        
        if(empty($supplier_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert($this->sqim_supplier, $data)) ? $this->db->insert_id() : False);
        } else {
            //echo $supplier_id; exit;
            $this->db->where('id', $supplier_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update($this->sqim_supplier, $data)) ? $supplier_id : False);
        }
        
    }
    
    function get_all_suppliers_new(){
        $sql = "SELECT * FROM ".SQIM_DB.".suppliers ";
        
        return $this->db->query($sql)->result_array();
    }
    
    function get_duplicate_entries($data){
        $sql = "SELECT * FROM ".SQIM_DB.".suppliers WHERE name = '".$data['name']."' or supplier_no = '".$data['supplier_no']."' or email = '".$data['email']."' ";
        
        return $this->db->query($sql)->result_array();
    }
        
    function get_all_suppliers(){
		// echo $this->product_id;exit;
        $sql = "SELECT s.* FROM ".SQIM_DB.".suppliers s
                INNER JOIN sp_mappings sp ON sp.supplier_id = s.id AND sp.product_id = ".$this->product_id."
                INNER JOIN product_parts pp ON pp.id = sp.supplier_id
                WHERE s.is_active = 1
                GROUP BY sp.supplier_id";
        
        return $this->db->query($sql)->result_array();
    }
	function get_all_suppliers_1(){
		// echo $this->product_id;exit;
        $sql = "SELECT s.* FROM ".SQIM_DB.".suppliers s
                INNER JOIN ".SQIM_DB.".sp_mappings sp ON sp.supplier_id = s.id 
                INNER JOIN ".SQIM_DB.".product_parts pp ON pp.id = sp.supplier_id
                WHERE s.is_active = 1
                GROUP BY sp.supplier_id";
        
        return $this->db->query($sql)->result_array();
    }
    
    function get_supplier($id) {
        $this->db->where('id', $id);

        return $this->db->get($this->sqim_supplier)->row_array();
    }
    
    function get_inspector_lqc($id) {
		// echo $this->sqim_supplier_inspector;exit;
        $this->db->where('id', $id);

        return $this->db->get($this->sqim_supplier_inspector)->row_array();
    }
	function get_inspector($id) {
        $this->db->where('id', $id);

        return $this->db->get($this->sqim_supplier_inspector)->row_array();
    }

    function get_supplier_by_name($name) {
        $this->db->where('name', $name);
        $this->db->where('is_active', 1);
        
        return $this->db->get($this->sqim_supplier)->row_array();
    }

    function get_supplier_by_code($code) {
        $this->db->where('supplier_no', $code);
        $this->db->where('is_active', 1);
        
        return $this->db->get($this->sqim_supplier)->row_array();
    }
    
    function get_all_inspectors_lqc() {
        $sql = "SELECT * FROM ".SQIM_DB.".supplier_inspector";
        
        $pass_array = array();
        if($this->id) {
            $sql .= ' WHERE supplier_id = ?';
            $pass_array = array($this->id);
        }
        
        $users = $this->db->query($sql, $pass_array);
        return $users->result_array();
    }
    
    function is_supplier_inspector_exists_lqc($email, $id = '') {
        if(!empty($id)) {
            $this->db->where('id !=', $id);
        }

        $this->db->where('email', $email);

        return $this->db->count_all_results($this->sqim_supplier_inspector);
    }
    
    function update_supplier_inspector($data, $id = '') {
            
        $needed_array = array('supplier_id', 'name', 'password', 'email', 'is_active');
        
        $data = array_intersect_key($data, array_flip($needed_array));

        if(!empty($data['password'])) {
            $cost = $this->config->item('hash_cost');
            $data['password'] = password_hash(SALT .$data['password'], PASSWORD_BCRYPT, array('cost' => $cost));
        } else {
            unset($data['password']);
        }

        if(empty($id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert($this->sqim_supplier_inspector, $data)) ? $this->db->insert_id() : False);
            
        } else {
            $this->db->where('id', $id);
            $data['modified'] = date("Y-m-d H:i:s");
            return (($this->db->update($this->sqim_supplier_inspector, $data)) ? $id : False);
        }
    }
    
    function get_all_sp_mappings($filters) {
        $sql = "SELECT sp.*, s.name as supplier_name, s.supplier_no,
        pp.name as part_name, pp.code as part_code, p.name as product_name
        FROM sp_mappings sp
        INNER JOIN ".SQIM_DB.".suppliers s
        ON sp.supplier_id = s.id
        INNER JOIN product_parts pp
        ON sp.part_id = pp.id
        INNER JOIN products p
        ON pp.product_id = p.id";
        
        $wheres = array();
        $pass_array = array();
        
        if(!empty($filters['product_id'])) {
            $wheres[] = 'sp.product_id = ?';
            $pass_array[] = $filters['product_id'];
        }else{
            $wheres[] = 'sp.product_id = ?';
            $pass_array[] = $this->product_id;
        }
        
        if(!empty($filters['part_name'])) {
            $wheres[] = 'pp.name like ?';
            $pass_array[] = $filters['part_name'];
        }
        
        if(!empty($filters['part_id'])) {
            $wheres[] = 'sp.part_id = ?';
            $pass_array[] = $filters['part_id'];
        }
        
        if(!empty($filters['supplier_id'])) {
            $wheres[] = 'sp.supplier_id = ?';
            $pass_array[] = $filters['supplier_id'];
        }
        
        if(!empty($wheres)) {
            $sql .= " WHERE ".implode(' AND ', $wheres);
        }
        
        return $this->db->query($sql, $pass_array)->result_array();
    }

    function get_sp_mapping($sp_mapping) {
        $sql = "SELECT sp.*, s.name as supplier_name, s.supplier_no,
        pp.name as part_name
        FROM sp_mappings sp
        INNER JOIN ".SQIM_DB.".suppliers s
        ON sp.supplier_id = s.id
        INNER JOIN product_parts pp
        ON sp.part_id = pp.id
        WHERE sp.id = ?";
        
        return $this->db->query($sql, array($sp_mapping))->result_array();
    }
    
    function add_sp_mapping($data, $sp_mapping_id){
        $needed_array = array('product_id','supplier_id', 'part_id');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($sp_mapping_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('sp_mappings', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $sp_mapping_id);
            $data['modified'] = date("Y-m-d H:i:s");
            
            return (($this->db->update('sp_mappings', $data)) ? $sp_mapping_id : False);
        }
        
    }
    
    function change_status($supplier_id, $status) {
        if(!empty($supplier_id) && !empty($status)) {
            $supplier_active = ($status == 'active') ? 1 : 0;
            
            $this->db->where('id', $supplier_id);
            $this->db->set('is_active', $supplier_active);
            $this->db->update($this->sqim_supplier);

            if($this->db->affected_rows() > 0) {
                return TRUE;
            }
        }

        return FALSE;
    }
    
    function change_inspector_status($inspector_id, $status) {
        if(!empty($inspector_id) && !empty($status)) {
            $active = ($status == 'active') ? 1 : 0;
            
            $this->db->where('id', $inspector_id);
            $this->db->set('is_active', $active);
            $this->db->update($this->sqim_supplier_inspector);

            if($this->db->affected_rows() > 0) {
                return TRUE;
            }
        }

        return FALSE;
    }
    
    function insert_suppliers($suppliers) {
        $this->db->insert_batch($this->sqim_supplier, $suppliers);
        
        $this->remove_dups_suppliers();
    }
    
    function remove_dups_suppliers() {
        $sql = "DELETE FROM ".SQIM_DB.".suppliers 
        WHERE id NOT IN (
            SELECT * FROM (
                SELECT MIN(id) 
                FROM ".SQIM_DB.".suppliers 
                GROUP BY supplier_no, name
            ) as d
        )";
        
        return $this->db->query($sql, array($product_id, $product_id));
    }
    
    function insert_sp_mappings($data) {
        $this->db->insert_batch('sp_mappings', $data);
    }
    
    function remove_dups() {
        $sql = "DELETE FROM sp_mappings WHERE id NOT IN (
            SELECT * FROM (
                SELECT min(id) FROM sp_mappings GROUP BY supplier_id, part_id, product_id 
            ) as sub
        )";

        return $this->db->query($sql);
    }

    function get_supplier_products($supplier_id) {
        $sql = "SELECT DISTINCT p.id, p.name, p.org_id 
        FROM `sp_mappings` sp 
        INNER JOIN products p 
        ON sp.product_id = p.id 
        WHERE sp.supplier_id = ?";
        
        return $this->db->query($sql, array($supplier_id))->result_array();
    }
	
	//	Defect Code
	function get_sp_mapping_part_supplier($part_id,$supplier_id) {
        $sql = "SELECT * from sp_mappings where product_id = ? AND part_id = ? AND supplier_id = ? ";
        
        return $this->db->query($sql, array($this->product_id,$part_id, $supplier_id))->result_array();
    }
	
	//	Defect Code

	
}
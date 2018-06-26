<?php

class User_model extends CI_Model {

    function __construct() {
        parent::__construct();

        require_once APPPATH .'libraries/pass_compat/password.php';
    }

    function get_all_users() {
        $sql = "SELECT u.*, 
        GROUP_CONCAT(p.name ORDER BY p.name) as product_name 
        FROM users u
        LEFT JOIN sqim_new.products p
        ON FIND_IN_SET(p.id, u.product_id)";
        
        if($this->product_id) {
            $sql .= " WHERE FIND_IN_SET(".$this->product_id.", u.product_id) ";
        }
        
        $sql .= " GROUP BY u.id";
        //echo $this->product_id." ".$sql; exit;
        $users = $this->db->query($sql);
        //echo "<pre>"; print_r($users->result_array()); exit;
        return $users->result_array();
    }

    function get_user($username) {
        $sql = "SELECT u.*, 
        GROUP_CONCAT(p.name ORDER BY p.name) as product_name 
        FROM users u
        LEFT JOIN sqim_new.products p
        ON FIND_IN_SET(p.id, u.product_id)
        WHERE u.username = ?";
        
        $pass_array = array($username);
        if($this->product_id) {
            $sql .= ' AND p.id = ?';
            $pass_array[] = $this->product_id;
        }

        $sql .= " GROUP BY u.id";
        
        return $this->db->query($sql, $pass_array)->row_array();
    }
	
	function get_admin_users($product_id) {
        $sql = "SELECT u.*
        FROM users u
        where FIND_IN_SET(".$product_id.", u.product_id) and u.user_type = 'Admin' and is_active = 1 GROUP BY u.id";        
        return $this->db->query($sql, $product_id)->result_array();
    }
    
    function get_user_by_id($id) {
        $sql = "SELECT u.*, 
        GROUP_CONCAT(p.name ORDER BY p.name) as product_name 
        FROM users u
        LEFT JOIN sqim_new.products p
        ON FIND_IN_SET(p.id, u.product_id)
        WHERE u.id = ?";
        
        $pass_array = array($id);
        if($this->product_id) {
            $sql .= ' AND p.id = ?';
            $pass_array[] = $this->product_id;
        }

        $sql .= " GROUP BY u.id";
        
        return $this->db->query($sql, $pass_array)->row_array();
    }
    
    function get_supplier_user($id) {
        $sql = "SELECT * FROM sqim_new.suppliers WHERE id = ?";
        
        $pass_array = array($id);
        return $this->db->query($sql, $pass_array)->row_array();
    }
    
    function get_supplier_inspector_user($id) {
        $sql = "SELECT * FROM sqim_new.supplier_inspector WHERE id = ?";
        
        $pass_array = array($id);
        return $this->db->query($sql, $pass_array)->row_array();
    }
    
    function get_user_by_type($user_type) {
        $this->db->where('user_type', $user_type);
        
        if($this->product_id) {
            $this->db->where('product_id', $this->product_id);
        }
        
        return $this->db->get('users')->result_array();
    }

    function is_username_exists($username, $id = '') {
        if(!empty($id)) {
            $this->db->where('id !=', $id);
        }

        $this->db->where('username', $username);

        return $this->db->count_all_results('users');
    }

    function update_user($data, $user_id = '') {
        //filter unwanted fields while inserting in table.
        $needed_array = array('product_id', 'first_name', 'last_name', 'username', 'password', 'email', 'user_type', 'is_active', 'checklist_checked');
        
        
        $data = array_intersect_key($data, array_flip($needed_array));

        if(!empty($data['password'])) {
            $cost = $this->config->item('hash_cost');
            $data['password'] = password_hash(SALT .$data['password'], PASSWORD_BCRYPT, array('cost' => $cost));
        } else {
            unset($data['password']);
        }

        if(empty($user_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('users', $data)) ? $this->db->insert_id() : False);
            
        } else {
            $this->db->where('id', $user_id);
            $data['modified'] = date("Y-m-d H:i:s");
            return (($this->db->update('users', $data)) ? $user_id : False);
            
        }
    }
    
    function update_supplier_user($data, $user_id = '') {
        //filter unwanted fields while inserting in table.
        $needed_array = array('name', 'password', 'email', 'user_type', 'is_active', 'checklist_checked');
        
        
        $data = array_intersect_key($data, array_flip($needed_array));

        if(!empty($data['password'])) {
            $cost = $this->config->item('hash_cost');
            $data['password'] = password_hash(SALT .$data['password'], PASSWORD_BCRYPT, array('cost' => $cost));
        } else {
            unset($data['password']);
        }

        if(empty($user_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('sqim_new.suppliers', $data)) ? $this->db->insert_id() : False);
            
        } else {
            $this->db->where('id', $user_id);
            $data['modified'] = date("Y-m-d H:i:s");
            return (($this->db->update('sqim_new.suppliers', $data)) ? $user_id : False);
            
        }
    }
    
    function update_supplier_inspector_user($data, $user_id = '') {
        //filter unwanted fields while inserting in table.
        $needed_array = array('name', 'password', 'email', 'user_type', 'is_active', 'checklist_checked');
        
        
        $data = array_intersect_key($data, array_flip($needed_array));

        if(!empty($data['password'])) {
            $cost = $this->config->item('hash_cost');
            $data['password'] = password_hash(SALT .$data['password'], PASSWORD_BCRYPT, array('cost' => $cost));
        } else {
            unset($data['password']);
        }

        if(empty($user_id)) {
            $data['created'] = date("Y-m-d H:i:s");
            return (($this->db->insert('users', $data)) ? $this->db->insert_id() : False);
            
        } else {
            $this->db->where('id', $user_id);
            $data['modified'] = date("Y-m-d H:i:s");
            return (($this->db->update('sqim_new.supplier_inspector', $data)) ? $user_id : False);
            
        }
    }

    function login_check($username, $password, $only_check = false) {
        if (empty($username) || empty($password)) {
            return False;
        }

        $response['status'] = 'ERROR';
        $response['message'] = 'Invalid Credentials';

        $sql = "SELECT u.*, 
            GROUP_CONCAT(p.id ORDER BY p.name) as product_ids,
            GROUP_CONCAT(p.org_id ORDER BY p.name) as org_ids,
            GROUP_CONCAT(p.org_name ORDER BY p.name) as org_names,
            GROUP_CONCAT(p.name ORDER BY p.name) as product_names,
            GROUP_CONCAT(p.code ORDER BY p.code) as product_codes
            FROM users u 
            LEFT JOIN sqim_new.products p 
            ON FIND_IN_SET(p.id, u.product_id)
            WHERE u.username = ?
            GROUP BY u.id";
        
        $query = $this->db->query($sql, array($username));

        if ($query->num_rows() == 1) {
            $user = $query->row_array();
            //echo "<pre>";print_r($user);exit;
            if (password_verify(SALT .$password, $user['password'])) {
                if(!$user['is_active']) {
                    $response['message'] = 'Your acount has been deactivated.';
                } else {

                    $response['status'] = 'SUCCESS';
                    
                    if(!$only_check) {
                        $this->create_session($user);
                    }
                    return $response;
                }
            }
        }

        return $response;
    }
    
    function supplier_login_check($username, $password, $type, $only_check = false) {
        if (empty($username) || empty($password)) {
            return False;
        }

        $response['status'] = 'ERROR';
        $response['message'] = 'Invalid Credentials';

        
        if($type == 'Supplier'){
            $sql = "SELECT * FROM sqim_new.suppliers WHERE email = ? GROUP BY id";
        }else{
            $sql = "SELECT * FROM sqim_new.supplier_inspector WHERE email = ? GROUP BY id";
        }
        
        $query = $this->db->query($sql, array($username));

        if ($query->num_rows() == 1) {
            $user = $query->row_array();
            //echo "<pre>";print_r($user);exit;
            if (password_verify(SALT .$password, $user['password'])) {
                if(!$user['is_active']) {
                    $response['message'] = 'Your acount has been deactivated.';
                } else {

                    $response['status'] = 'SUCCESS';
                    if($type == 'Supplier'){
                        $user['user_type'] = 'Supplier';
                    }else{
                        $user['user_type'] = 'Supplier Inspector';
                        
                        $this->load->model('Supplier_model');
                        $supplier = $this->Supplier_model->get_supplier($user['supplier_id']);
                        
                        $user['supplier_no'] = $supplier['supplier_no'];
                    }
                    
                    if(!$only_check) {
                        $this->create_supplier_session($user);
                    }
                    return $response;
                }
            }
        }

        return $response;
    }
    
    function create_session($user) {
        $data = array(
            'is_logged_in' => True,
            'id' => $user['id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'name' => $user['first_name'].' '.$user['last_name'],
            'username' => $user['username'],
            'email' => $user['email'],
            'user_type' => $user['user_type'],
            'product_ids' => $user['product_ids'],
        );
        
        /* echo "<pre>";
        print_r($user);
        print_r($data);
        exit; */
        
        if($user['user_type'] == 'Admin' && empty($user['product_id'])) {
            $all_products = $this->db->get('sqim_new.products')->result_array();
            
            $products = array();
            $product_ids = array();
            foreach($all_products as $k => $pid) {
                if($k === 0) {
                    $data['product_id']         = $pid['id'];
                    $data['product_name']       = $pid['name'];
                    $data['org_id']             = $pid['org_id'];
                    $data['org_name']           = $pid['org_name'];
                    $data['product_code']       = $pid['code'];
                }
                
                $temp = array();
                $temp['id']             = $pid['id'];
                $temp['name']           = $pid['name'];
                $temp['org_id']         = $pid['org_id'];
                $temp['org_name']       = $pid['org_name'];
                $temp['product_code']   = $pid['code'];
                $product_ids[]          = $pid['id'];
                $products[]             = $temp;
            }

            $data['product_ids']        = implode(',', $product_ids);
            $data['is_super_admin']     = true;
        } else {
            $data['is_super_admin'] = false;
            $product_ids = explode(',', $user['product_ids']);
            $org_ids = explode(',', $user['org_ids']);
            $org_names = explode(',', $user['org_names']);
            $product_names = explode(',', $user['product_names']);
            $product_codes = explode(',', $user['product_codes']);
            $products = array();
            
            if(count($product_ids)) {
                foreach($product_ids as $k => $pid) {
                    if($k === 0) {
                        $data['product_id']     = $pid;
                        $data['product_name']   = $product_names[$k];
                        $data['org_id']   = $org_ids[$k];
                        $data['org_name']   = $org_names[$k];
                        $data['product_code'] = $product_codes[$k];
                    }
                    
                    $temp = array();
                    $temp['id'] = $pid;
                    $temp['name'] = $product_names[$k];
                    $temp['org_id'] = $org_ids[$k];
                    $temp['org_name'] = $org_names[$k];
                    $temp['product_code'] = $product_codes[$k];
                    
                    $products[] = $temp;
                }

            }
        }
        
        $data['products'] = $products;
        
        //echo "<pre>";print_r($data);exit;
        $this->session->set_userdata($data);
        
        return true;
    }
    
    function create_supplier_session($user) {
        $data = array(
            'is_logged_in'              => True,
            'id'                        => $user['id'],
            'first_name'                => $user['name'],
            'last_name'                 => '',
            'name'                      => $user['name'],
            'username'                  => $user['email'],
            'email'                     => $user['email'],
            'user_type'                 => $user['user_type'],
            
            'supplier_no'               => $user['supplier_no'],
            'supplier_name'             => $user['name'],
            'is_active'                 => $user['is_active'],
        );
        
        if($user['user_type'] == 'Supplier Inspector'){
            $data['supplier_id'] = $user['supplier_id'];
        }else{
            $data['supplier_id'] = $user['id'];
        }
        
        $query = "SELECT DISTINCT p.id, p.name, p.org_id , p.org_name, p.code
        FROM sqim_new.`sp_mappings` sp 
        INNER JOIN sqim_new.products p 
        ON sp.product_id = p.id 
        WHERE sp.supplier_id = ?";
        
        $all_products = $this->db->query($query, array($data['supplier_id']))->result_array();
        
        $products = array();
        $product_ids = array();
        
        foreach($all_products as $k => $pid) {
            if($k === 0) {
                $data['product_id']     = $pid['id'];
                $data['product_name']   = $pid['name'];
                $data['org_id']         = $pid['org_id'];
                $data['org_name']       = $pid['org_name'];
                $data['product_code']   = $pid['code'];
            }
            
            $temp                = array();
            $temp['id']          = $pid['id'];
            $temp['name']        = $pid['name'];
            $temp['org_id']      = $pid['org_id'];
            $temp['org_name']    = $pid['org_name'];
            $temp['product_code']= $pid['code'];
            $product_ids[]       = $pid['id'];
            $products[]          = $temp;
        }
        
        $data['product_ids']    = implode(',', $product_ids);
        $data['products']       = $products;

        $this->session->set_userdata($data);
        
        return true;
    }

    function login_by_email($email) {
        $sql = "SELECT u.*, 
            GROUP_CONCAT(p.id ORDER BY p.name) as product_ids,
            GROUP_CONCAT(p.name ORDER BY p.name) as product_names,
            GROUP_CONCAT(p.code ORDER BY p.code) as product_codes
            FROM users u 
            LEFT JOIN sqim_new.products p 
            ON FIND_IN_SET(p.id, u.product_id)
            WHERE u.email = ?
            GROUP BY u.id";
        
        $query = $this->db->query($sql, array($email));
        if ($query->num_rows() == 1) {
            $user = $query->row_array();            
            $this->create_session($user);

            return true;
        }
        
        return false;
    }
    
    function change_password($id, $password) {
        if(!empty($password)) {
            $cost = $this->config->item('hash_cost');
            $password = password_hash(SALT .$password, PASSWORD_BCRYPT, array('cost' => $cost));

            $this->db->where('id', $id);
            $this->db->set('password', $password);

            $this->db->update('users');

            if($this->db->affected_rows() > 0) {
                return TRUE;
            }

        }

        return False;
    }

    function change_status($id, $status) {
        if(!empty($id) && !empty($status)) {
            $user_active = ($status == 'active') ? 1 : 0;
            
            $this->db->where('id', $id);
            if($this->product_id) {
                $this->db->where('product_id', $this->product_id);
            }
            $this->db->set('is_active', $user_active);
            $this->db->update('users');

            if($this->db->affected_rows() > 0) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function reset_token($user_id, $email) {
        $token = md5($email);
        
        $this->db->where('id', $user_id);        
        $this->db->set('reset_token', $token);
        $this->db->set('reset_request_time', date('Y-m-d H:i:s'));
        
        return (($this->db->update('users')) ? $token : False);
    }
    
    public function find_user_by_token($token){
        $sql = "SELECT * FROM users WHERE reset_token = ? AND reset_request_time >  DATE_SUB( NOW(), INTERVAL 24 HOUR)" ;
        $result = $this->db->query($sql, array($token));
        return $result->row_array();
	}
	function update_user_last_login($user_id) {
        //filter unwanted fields while inserting in table.
			
			$r = $this->update_login_count($user_id);
			
			$needed_array = array('last_login','login_count');
            $data['last_login'] = date("Y-m-d H:i:s");
            $data['login_count'] = $r['login_count'] + 1;
			
			$data = array_intersect_key($data, array_flip($needed_array));
            $this->db->where('username', $user_id);
            return (($this->db->update('users', $data)) ? $user_id : False);
        
    }
	 
	public function update_login_count($user_id){
        $sql = "SELECT * FROM users WHERE username = ? " ;
        $result = $this->db->query($sql, array($user_id));
		
		
        return $result->row_array();
    }
	function update_supplier_last_login($user_id) {
        //filter unwanted fields while inserting in table.
			
			$r = $this->update_supplier_login_count($user_id);
			
			$needed_array = array('last_login','login_count');
            $data['last_login'] = date("Y-m-d H:i:s");
            $data['login_count'] = $r['login_count'] + 1;
			
			$data = array_intersect_key($data, array_flip($needed_array));
            $this->db->where('email', $user_id);
            return (($this->db->update('sqim_new.suppliers', $data)) ? $user_id : False);
        
    }
	 
	public function update_supplier_login_count($user_id){
        $sql = "SELECT * FROM sqim_new.suppliers WHERE email = ? " ;
        $result = $this->db->query($sql, array($user_id));
		
		
        return $result->row_array();
    }
	function update_supplier_inspector_last_login($user_id) {
        //filter unwanted fields while inserting in table.
			
			$r = $this->update_supplier_inspector_login_count($user_id);
			
			$needed_array = array('last_login','login_count');
            $data['last_login'] = date("Y-m-d H:i:s");
            $data['login_count'] = $r['login_count'] + 1;
			
			$data = array_intersect_key($data, array_flip($needed_array));
            $this->db->where('email', $user_id);
            return (($this->db->update('sqim_new.supplier_inspector', $data)) ? $user_id : False);
        
    }
	 
	public function update_supplier_inspector_login_count($user_id){
        $sql = "SELECT * FROM sqim_new.supplier_inspector WHERE email = ? " ;
        $result = $this->db->query($sql, array($user_id));
		
		
        return $result->row_array();
    }
	/* */
	//For page Hits
	
	function get_page_count($page_new) {
        $sql = "SELECT * FROM page_hits WHERE page_name = '".$page_new."'";
        
        return $this->db->query($sql)->row_array();
    }
	
	function update_page_count($data, $count_id = '',$hit_count = '') {
        //filter unwanted fields while inserting in table.
        $needed_array = array('page_name');
        
        
        $data = array_intersect_key($data, array_flip($needed_array));

        if(empty($count_id)) {
            $data['created'] = date("Y-m-d H:i:s");
			$data['hit_count'] = 1;
            return (($this->db->insert('page_hits', $data)) ? $this->db->insert_id() : False);
            
        } else {
			$data['hit_count'] = $hit_count + 1;
            $data['modified'] = date("Y-m-d H:i:s");
            $this->db->where('id', $count_id);
            return (($this->db->update('page_hits', $data)) ? $count_id : False);
            
        }
    }
    
    
	
	//For page Hits
}
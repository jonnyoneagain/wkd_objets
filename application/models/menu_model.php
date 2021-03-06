<?php

class Menu_model extends CI_Model {
	
	//private $ci;

	function __construct () {
        // Call the Model constructor
        	parent::__construct();
	       	//$this->ci =& get_instance();
		//$this->ci->load->model('Users_model','', TRUE); 
		// voir si logged in, c'est dans le model qui consulte la base de données'
		//$this->ci->mymodel->mymethod();  
    	}
    	
    	function get_list_menu() {
		$query = $this->db->from('menu')->get();	
    		return $query->result_array();		
	}
	
	function save_json_menu($serialized_json_menu, $user_id) {
		// save entire serialized json jstree
		//$this->db->empty_table('menu_json');
		$data = array(
			'id' => null,
			'tableau_menu' => $serialized_json_menu, 
			'date' => time(),
			'user_id' => $user_id
		);
		$this->db->insert('menu_json', $data);
	}
	
    	function get_json_menu() {
		$query = $this->db
			->from('menu_json')
			->order_by('date', 'desc')
			->limit(1)
			->get();
		return $query->row_array();
	}
	
	function empty_menu() {
		$this->db->empty_table('menu'); 
	}
	
	function save_menu_entry($menu_entry_nom, $menu_entry_page, $menu_entry_parent) {
		$data = array(
			'id' => null, 
			'alias_nom' => $menu_entry_nom,
			'alias_page' => $menu_entry_page,
			'parent' => $menu_entry_parent
		);
		$this->db->insert('menu', $data);
	}
    	
    	
}

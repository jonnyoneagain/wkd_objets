<?php

class Collection_objets_model extends CI_Model {
	
	//private $ci;

	function __construct () {
        // Call the Model constructor
        	parent::__construct();
	       	$ci = get_instance();
		//$this->ci->load->model('Users_model','', TRUE);
		//$this->ci->mymodel->mymethod();
		// un model ne devrait pas en appeler un autre, 
		// c'est toutefois envisageable
		$ci->load->helper('url');
    	}
    	
    	function count_object_collection_entries ($collection_page_id) {
    		return $this->db->from('objets')->where('pages_id', $$collection_page_id)->count_all_results();
    	}
    	
    	function count_id_entries_in_table ($nom_table, $champ, $id) {
    		return $this->db->from($nom_table)->where($champ, $id)->count_all_results();
    	}
    	
	function get_page ($nom_page, $id = null) {
		if ($this->check_if_already_exists('nom',$nom_page)) {
			
			// si on ne précise pas l'id, on prend la page la plus récente.
			if (is_null($id)) {
				$query = $this->db->from('pages')->where('nom',$nom_page)->order_by('date', 'desc')->limit(1)->get();
				// prevoir si query est vide.
			}
			else {
				$array = array(
					'nom' => $nom_page,
					'id' => $id
				);
				$query = $this->db->from('pages')->where($array)->order_by('date', 'desc')->limit(1)->get();
				//prevoir un signal si le retour de cette requête est vide.
			}
	       		return $query->row_array();
		}		
    	}
    	
    	function get_object_from_collection ($collection_page_nom, $objet_titre) {
    		$where_options_array = array(
    			'page_nom' => $collection_page_nom,
    			'titre' => $objet_titre
    		);
    		$query = $this->db->from('objets')
  				->where($where_options_array)
  				->order_by('date', 'desc')
  				->limit(1)
  				->get();
  		return $query->row_array();
    	}
    	
    	function bool_check_if_obj_title_already_exists ($collection_page_nom, $new_obj_titre) {
    		$where_options_array = array(
    			'page_nom' => $collection_page_nom,
    			'titre' => $new_obj_titre
    		);
    		$query = $this->db->from('objets')
  				//->where('page_nom', $collection_page_nom)
  				//->or_where($or_where_options)
  				->where($where_options_array)
  				->get();
  		$result_array = $query->result_array();
  	// (condition) ? instruction si vrai : instruction si faux
  		$bool = (empty($result_array)) ? false : true;
  		return $bool;
    		
    	}
    	
    	function get_collection_categorie ($collection_page_nom, $titre) {
    		$where_options_array = array(
    			'page_nom' => $collection_page_nom,
    			'categorie' => $titre
    		);
    		$query = $this->db->from('objets')
  				->where($where_options_array)
  				->order_by('date', 'desc')
  				->group_by('titre')
  				->get();
  		return $query->result_array();
    	}
    	
	function get_page_infos ($nom_page) {
			$query = $this
				->db
				->from('pages')
				->where('nom',$nom_page)
				->select('nom, bool_collection_objets, date')
				->order_by('date', 'desc')
				->limit(1)
				->get();
		
	}
    	
    	function get_list_noms_pages () { // pour feed le autocomplete list lors de la creation d'un lien
    		$query = $this->db->from('pages')->select('nom')->group_by('nom')->get();
    		return $query->result_array();
    	}
    	
    	function get_list_pages ($nom_page, $num, $offset) { // pour construire l'historique'
    		
    		$query = $this->db->where('nom', $nom_page)
    				->get('pages', $num, $offset);	
    		return $query->result_array();
  	}
  	
  	function get_collection_sommaire ($collection_page_nom) {
  		$query = $this->db->from('objets')
  				->where('page_nom', $collection_page_nom)
  				->order_by('date', 'desc')
  				->group_by('titre')
  				->get();
  		return $query->result_array();
  	}
  	
  	function get_collection_sommaire_ul ($collection_page_nom) {
  		$query = $this->db->from('objets_collections_sommaires')
  				->where('page_nom', $collection_page_nom)
  				->order_by('date', 'desc')
  				->limit(1)
  				->get();
  		return $query->row_array();
  	}
  	
  	function user_save_collection_sommaire_ul ($nom_page, $collection_sommaire_ul, $user_id) {
  		$data = array(
    			'id' => NULL,
    			'page_nom' => $nom_page,
               		'collection_sommaire_ul' => $collection_sommaire_ul,
               		'date' => time(),
               		'user_id' => $user_id
		);
		$this->db->insert('objets_collections_sommaires', $data);
		return $data;
  	}
  	
  	function get_collection_sommaire_array ($collection_page_nom) {
  		$query0 = $this->db->from('objets')
  				->where('page_nom', $collection_page_nom)
  				->select('categorie')
  				->distinct()
  				->get();
  		$out = array();
  		foreach ($query0->result_array() as $row0) {
  			$query1 = $this->db->from('objets')
	  				->where('page_nom', $collection_page_nom)
	  				->where('categorie', $row0['categorie'])
	  				//->select('titre')
	  				//->distinct()
	  				->group_by('titre')
	  				->order_by('date', 'desc')
	  				->get();
	  		foreach ($query1->result_array() as $value) { //$row0['categorie']
	  			$idx = anchor(
	  				'sync/show/'.$value['page_nom'].'/'.$row0['categorie'], $row0['categorie'], 
	  				array(
	  					'title' => $row0['categorie'],
	  					'class' => 'sommaire_collection'
	  				));
	  			$out[$idx][] = anchor(
	  				'sync/show/'.$value['page_nom'].'/'.$value['titre'], 
	  				$value['sommaire_alias'], 
	  				array(
	  					'title' => $value['contexte'], 
	  					'class' => 'sommaire_collection'
	  				));
	  		}
		}
		//print_r($out);
  		return $out;
  	}
  	
  	/*function get_collection_sommaire_parent ($collection_page_nom) {
  		$query0 = $this->db->from('objets')
  				->where('page_nom', $collection_page_nom)
  				->select('parent')
  				->distinct()
  				->get();
  		$out = array();
  		foreach ($query0->result_array() as $row0) {
  			$query1 = $this->db->from('objets')
	  				->where('page_nom', $collection_page_nom)
	  				->where('parent', $row0['parent'])
	  				->select('titre')
	  				->distinct()
	  				->order_by('date', 'desc')
	  				->get();
	  		$out[$row0['categorie']] = $query1->result_array();
		}
		//print_r($out);
  		return $out;
  	}
    */
    
	function check_if_already_exists ($field_selected, $entry) {
		return ($this->db->from('pages')->where($field_selected, $entry)->count_all_results())?TRUE:FALSE;
	}
		
	function user_save_page ($nom_page, $contenu, $bool_collection_objets, $user_id) {
		$data = array(
    			'id' => NULL,
    			'nom' => $nom_page,
               		'contenu' => $contenu,
               		'date' => time(),
               		'bool_collection_objets' => $bool_collection_objets,
               		'user_id' => $user_id
		);
		$this->db->insert('pages', $data);
		return $data;
	}
	
	function save_page ($nom_page, $contenu) { // avec new_page()
    		
    		$data = array(
    			'id' => NULL,
    			'nom' => $nom_page,
               		'contenu' => $contenu,
               		'date' => time(),
               		'user_id' => NULL,
               		'bool_collection_objets' => false
		);
		$this->db->insert('pages', $data);
		return $data;
	}
	
	function new_page ($nom_page) {
		$contenu_page_vierge = 'WIKID nouvelle page';
		$nouvelle_page = $this->save_page($nom_page, $contenu_page_vierge);
		return $nouvelle_page;
	}
}
<?php

class Collection_objets extends CI_Controller {
	
	public function __construct () {
	
		parent::__construct();	
		$this->load->model('Users_model','', TRUE);
		$this->load->model('Collection_objets_model','', TRUE);
		$this->load->helper('date');
		$this->load->helper('html');
		//$this->load->helper('regex_wikid');
	}
	
	private function _security_check_logged_in_or_exit () {
		if (!$this->Users_model->security_check_logged_in_or_die()) {
		exit;
		}
	}
	
	public function user_valide_collection_obj_sommaire () {
		$this->_security_check_logged_in_or_exit();
		
		$session_user_data = $this->Users_model->get_session_user_data();
		$current_user_id = $session_user_data['user_id'];
		$sommaire_collection_ul = $this->input->post('sommaire_collection');
		$collection_nom = $this->input->post('page_nom', true);
		//print_r($sommaire_collection); // ok
		$saved_sommaire_ul = $this->Collection_objets_model->user_save_collection_sommaire_ul($collection_nom, $sommaire_collection_ul, $current_user_id);
		echo json_encode($saved_sommaire_ul);
		
	}
	
	public function create_new_object ($collection_nom) {
		$this->_security_check_logged_in_or_exit();
	// save user's new object
		$session_user_data = $this->Users_model->get_session_user_data();
		$current_user_id = $session_user_data['user_id'];
		$new_obj_titre = $this->input->post('titre_new_object', true);
		$bool_already_exists = $this->Collection_objets_model->bool_check_if_obj_title_already_exists($collection_nom, $new_obj_titre);
		$out['success'] = ($bool_already_exists) ? false : true;
		$out['new_obj_titre'] = $new_obj_titre;
		
		echo json_encode($out);
	}
	
	public function display_new_object_template ($nom_page_sommaire) {
		$this->_security_check_logged_in_or_exit();
		$data = array(
				'objets_nom_page_sommaire' => $nom_page_sommaire,
			);
			$objets['contenu'] = $this->load->view('new_object_view', $data, true);
			$out['objet_data'] = array(
				'titre' => 'nouveau template'
			);
			$out['success'] = true;
			$out['page_nom'] = $nom_page_sommaire;
			$out['objet_contenu_html'] = $objets['contenu'];
	
			echo json_encode($out);
	}
	
	public function display_objet ($nom_page_sommaire, $objet_titre = null) {
	// nouvel objet on charge un template
		if (isset($nom_page_sommaire) and isset ($objet_titre) and $objet_titre == 'new') {
			$this->display_new_object_template($nom_page_sommaire);
			exit;
		}
	// cas ou l'on demande un objet seulement'
		if (isset($objet_titre)) {
			
		}
		$objet_array = $this->Collection_objets_model->get_object_from_collection($nom_page_sommaire, $objet_titre);
		if (!empty($objet_array)) {
			
			$objet_data = array(
				'objets_nom_page_sommaire' => $nom_page_sommaire,
				'objet_data' => $objet_array
			);
			$objets['contenu'] = $this->load->view('single_objet_view', $objet_data, true);
			$out['objet_data'] = $objet_array;
			$out['success'] = true;
		}
		else {
	// le cas par défaut, on a pas demandé d'objet ni de catégorie mais cette page est une collection d'objet, on charge un conteneur objet vide
			$objet_data = array(
				'objets_nom_page_sommaire' => $nom_page_sommaire,
				'objet_data' => null,
			);
			$objets['contenu'] = $this->load->view('single_objet_view', $objet_data, true);
			//$out['objet_data'] = null;
			$out['success'] = false;
		}
		
		
		$out['page_nom'] = $nom_page_sommaire;
		$out['objet_contenu_html'] = $objets['contenu'];
		
		echo json_encode($out);
	}
	
	/*
	
	public function edit_mode_init ($nom_page) {	
		//$this->Users_model->security_check_logged_in_or_die();	//pas intéressant le die s'execute seulement dans le model'
		$this->_security_check_logged_in_or_exit();
		// on pourrait vérifier si la dernière sauvegarde a été validée par l'utilisateur'
		//if ($this->Pages_model->check_if_user_validated('nom', $nom_page)) {// sinon charge le snapshot correspondant}
		$page_data = $this->Pages_model->get_page($nom_page);
		$data['page_nom'] = $page_data['nom'];
		$data['contenu'] = $page_data['contenu'];
		$data['tip'] = 'version validée';
		$page_user_data = $this->Users_model->get_user_data_by_id($page_data['user_id']);
		$data['page_user_login'] = $page_user_data['login'];
		$format_date = 'DATE_RSS';
		$data['human_date'] = standard_date($format_date, $page_data['date']);
		$data['bool_collection_objets'] = $page_data['bool_collection_objets'];
		$out = $this->load->view('edit_page_form_view', $data, TRUE);
		echo json_encode($out);
	}
	
	public function user_save_page () {
		$this->_security_check_logged_in_or_exit();
		
		$session_user_data = $this->Users_model->get_session_user_data();
		$current_user_id = $session_user_data['user_id'];
		//$this->input->post(null, true); // pour tout récupéerer d'un coup'
		$nom_page = $this->input->post('nom_page', true);
		$nouveau_contenu = $this->input->post('contenu_'.$nom_page);
		$bool_collection_objets = $this->input->post('collection_objets', true);
		
		// si la page existe : ne modifie pas le user si la page est identique.
		// pour indiquer si la page est le sommaire d'une collection objets on pourrait faire un model collection objets
		if ($this->Pages_model->check_if_already_exists('nom',$nom_page)) {
			$dernier_contenu = $this->Pages_model->get_page($nom_page); 
			if (($nouveau_contenu != $dernier_contenu['contenu']) or ($bool_collection_objets != $dernier_contenu['bool_collection_objets'])) {
				$saved_data = $this->Pages_model->user_save_page($nom_page, $nouveau_contenu, $bool_collection_objets, $current_user_id);
			}
			else {
				$saved_data['contenu'] = $dernier_contenu['contenu'];
			}
		}
		else {
		// si la page n'existe pas dans la table "pages" c'est une nouvelle page
			$saved_data = $this->Pages_model->user_save_page($nom_page, $nouveau_contenu, $bool_collection_objets, $current_user_id);
		}
		$out = $saved_data['contenu'];
		// give more feedback to users ? user and time etc.
		echo json_encode($out);
	}
	*/
}
	

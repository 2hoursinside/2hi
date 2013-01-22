<?php
class CountriesController extends AppController {
	
	var $name = "Countries";
	var $layout = 'admin_default';
	
	var $paginate = array(
		'limit'     => 10,
		'recursive' => -1
	);
 	
	
	function index() {
		$this->set('countries', $this->paginate());
	}
 
	function view($id = null) {
		$this->set('countries', $this->Country->read(null, $id));
	}

	
	function admin_index() {
		$countries = $this->Country->find('all', array('order' => 'Country.name ASC'));
		$this->set('countries', $countries);
	}
	
	function admin_add() {
		// Si données envoyée en POST
		if (!empty($this->data)) {
			$resultat = $this->Country->save($this->data);
			if ($resultat) {
				$this->Session->setFlash('Le pays a été ajouté.', 'growl');	
				$this->redirect(array('controller' => 'countries', 'action' => 'index')); 
			}

		}
	}
	
	
	function admin_edit($id = null) {
		
		if (!empty($id)) {
			$this->Country->id = $id;
	 
			if (empty($this->data)) {
				$this->data = $this->Country->read(null, $id);
			} else {
				$resultat = $this->Country->save($this->data);
				if ($resultat) {
					$this->Session->setFlash('Le pays a été modifié.', 'growl');	
					$this->redirect(array('controller' => 'countries', 'action' => 'index')); 
				}
			}
			
		} else {
			$this->Session->setFlash('Aucun pays trouvé.', 'growl', array('type' => 'error'));	
			$this->redirect(array('controller' => 'countries', 'action' => 'index'));
		}
		
		$this->set('data', $this->data);
	}
	
	
	
	function admin_delete($id) {
		$this->Country->delete($id);
		$this->Session->setFlash('Le pays a été supprimé.', 'growl');	
		$this->redirect(array('controller' => 'countries', 'action' => 'index'));
	}

}

?>
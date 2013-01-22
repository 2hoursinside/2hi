<?php
class RegionsController extends AppController {
	
	var $name = "Regions";
	var $layout = 'admin_default';

	var $paginate = array(
		'limit'     => 10,
		'recursive' => -1
	);
 	
	
	function index() {
		$this->set('regions', $this->paginate());
	}
 
	function view($id = null) {
		$this->set('regions', $this->Region->read(null, $id));
	}

	
	function admin_index() {
		$regions = $this->Region->find('all', array('order' => 'Region.name ASC'));
		$this->set('regions', $regions);
	}
	
	function admin_add() {
		// Si données envoyée en POST
		if (!empty($this->data)) {
			$resultat = $this->Region->save($this->data);
			if ($resultat) {
				$this->Session->setFlash('La région a été ajouté.', 'growl');	
				$this->redirect(array('controller' => 'regions', 'action' => 'index')); 
			}

		}
		$this->set('countries', $this->Region->Country->find('list'));
	}
	
	
	function admin_edit($id = null) {
		
		if (!empty($id)) {
			$this->Region->id = $id;
	 
			if (empty($this->data)) {
				$this->data = $this->Region->read(null, $id);
			} else {
				$resultat = $this->Region->save($this->data);
				if ($resultat) {
					$this->Session->setFlash('La région a été modifié.', 'growl');	
					$this->redirect(array('controller' => 'regions', 'action' => 'index')); 
				}
			}
			
		} else {
			$this->Session->setFlash('Aucune région trouvé.', 'growl', array('type' => 'error'));	
			$this->redirect(array('controller' => 'regions', 'action' => 'index'));
		}
		
		$this->set('countries', $this->Region->Country->find('list'));
		$this->set('data', $this->data);
	}
	
	
	
	function admin_delete($id) {
		$this->Region->delete($id);
		$this->Session->setFlash('La région a été supprimé.', 'growl');	
		$this->redirect(array('controller' => 'regions', 'action' => 'index'));
	}

}

?>
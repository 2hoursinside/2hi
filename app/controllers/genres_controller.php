<?php
class GenresController extends AppController {
	
	var $name = "Genres";
	var $layout = 'admin_default';

	var $paginate = array(
		'limit'     => 10,
		'recursive' => -1
	);
 	
	
	function index() {
		$this->set('genres', $this->paginate());
	}
 
	function view($id = null) {
		$this->set('genres', $this->Genre->read(null, $id));
	}

	
	function admin_index() {
		$genres = $this->Genre->find('all', array('order' => 'Genre.name ASC'));
		$this->set('genres', $genres);
	}
	
	function admin_add() {
		// Si données envoyée en POST
		if (!empty($this->data)) {
			$resultat = $this->Genre->save($this->data);
			if ($resultat) {
				$this->Session->setFlash('Le genre a été ajouté.', 'growl');	
				$this->redirect(array('controller' => 'genres', 'action' => 'index')); 
			}

		}
	}
	
	
	function admin_edit($id = null) {
		
		if (!empty($id)) {
			$this->Genre->id = $id;
	 
			if (empty($this->data)) {
				$this->data = $this->Genre->read(null, $id);
			} else {
				$resultat = $this->Genre->save($this->data);
				if ($resultat) {
					$this->Session->setFlash('Le genre a été modifié.', 'growl');	
					$this->redirect(array('controller' => 'genres', 'action' => 'index')); 
				}
			}
			
		} else {
			$this->Session->setFlash('Aucun genre trouvé.', 'growl', array('type' => 'error'));	
			$this->redirect(array('controller' => 'genres', 'action' => 'index'));
		}
		
		$this->set('data', $this->data);
	}
	
	
	
	function admin_delete($id) {
		$this->Genre->del($id);
		$this->Session->setFlash('Le genre a été supprimé.', 'growl');	
		$this->redirect(array('controller' => 'genres', 'action' => 'index'));
	}

}

?>
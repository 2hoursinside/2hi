<?php
class DaysController extends AppController {
	
	var $name = "Days";
	var $layout = "admin_default";
	
	var $paginate = array(
		'order'     => 'Day.date ASC',				  
		'limit'     => 10,
		'recursive' => -1
	);
 	
	
	function admin_many($festival_id) {
		$festival = $this->Day->Edition->Festival->findById($festival_id);
		
		$this->set('editions', $this->Day->Edition->find('list', array('conditions' => array('Edition.festival_id' => $festival_id))));
		$this->set('festival', $festival);
	}
	
	function admin_addmany() {
		if (!empty($this->data)) {
			// Enregistrement de tout le tableau	
			$festival_id = $this->data['Day']['festival_id'];
			
			foreach ($this->data['Day'] as $day) {
					$this->Day->id = null;
					$this->Day->save($day);
			}
			$this->Session->setFlash('Les jours ont été ajoutés.', 'growl');	
			$this->redirect('/admin/festivals/addartist/' . $festival_id); 
		}	
	}
	
	function admin_adds() {
		$this->layout = 'admin_default';
		
		$nombrejours = $this->data['Day']['nb'];
		$edition_id = $this->data['Day']['edition_id'];
		$festival_id = $this->data['Day']['festival_id'];
		
		$festival = $this->Day->Edition->Festival->findById($festival_id);
		$edition = $this->Day->Edition->findById($edition_id);
		
		$this->set('festival', $festival);
		$this->set('edition', $edition);
		$this->set('nb', $nombrejours);
	}
	
	
	function admin_edit($id = null) {
		
		if (!empty($id)) {
			$this->Day->id = $id;
	 
			if (empty($this->data)) {
				$this->data = $this->Day->read(null, $id);
			} else {
				$resultat = $this->Day->save($this->data);
				if ($resultat) {
					$this->Session->setFlash('Le jour a été modifié.', 'growl');	
					$this->redirect(array('controller' => 'days', 'action' => 'index')); 
				}
			}
			
		} else {
			$this->Session->setFlash('Aucun jour trouvé.', 'growl', array('type' => 'error'));	
			$this->redirect(array('controller' => 'days', 'action' => 'index'));
		}
		
		$this->set('data', $this->data);
	}
	
	
	
	function admin_delete($id) {
		$this->Day->delete($id);
		$this->Session->setFlash('Le jour a été supprimé.', 'growl');	
		$this->redirect(array('controller' => 'days', 'action' => 'index'));
	}

}

?>
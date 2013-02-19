<?php
class LivesetsController extends AppController {
	
	var $name = "Livesets";
	
	function admin_index() {
		$this->set('livesets', $this->Liveset->find('all'));
	}
	
	function admin_add() {
		$this->set('festivals', $this->Liveset->Edition->Festival->find('list', array('order' => 'Festival.name ASC')));
		$this->set('artists', $this->Liveset->Artist->find('list', array('order' => 'Artist.name ASC')));

		if (!empty($this->data)) {
			$resultat = $this->Liveset->save($this->data);
			$liveset_id = $this->Liveset->getLastInsertID();
			if ($resultat) {
				$this->redirect('/admin/livesets/chooseEdition/' . $liveset_id); 
			}
		}
	}
	
	function admin_chooseEdition($liveset_id) {
	 
	  $liveset = $this->Liveset->findById($liveset_id);
	  $this->set('liveset', $liveset);
	  
  	$this->set('editions', $this->Liveset->Edition->find('list', array('conditions' => array('Edition.festival_id' => $liveset['Liveset']['festival_id']), 'order' => 'Edition.date_start DESC')));
  	
  	$this->data = $this->Liveset->read(null, $liveset_id);
  	$this->set('data', $this->data);
  	
  }
  
  function admin_addedition() {
	 
		if (!empty($this->data)) {
		  //$liveset = $this->Liveset->findById($liveset_id);
		  
		  $this->Liveset->id = $this->data['Liveset']['id'];
      $resultat = $this->Liveset->saveField('edition_id', $this->data['Liveset']['edition_id']);
      debug($this->data);
      
			if ($resultat) {
				$this->Session->setFlash('L\'édition a été ajouté.', 'growl');	
				$this->redirect(array('controller' => 'livesets', 'action' => 'index')); 
			}
		} 
  }

	
	function admin_edit($id = null) {
		
		$this->set('festivals', $this->Liveset->Festival->find('list'));
		$this->set('artists', $this->Liveset->Artist->find('list', array('order' => 'Artist.name ASC')));
		
		if (!empty($id)) {
			$this->Liveset->id = $id;
	 
			if (empty($this->data)) {
				$this->data = $this->Liveset->read(null, $id);
			} else {
				$resultat = $this->Liveset->save($this->data);
				if ($resultat) {
					$this->redirect('/admin/livesets/chooseEdition/' . $id);  
				}
			}
			
		} 
		$this->set('data', $this->data);
	}
	
	
	
	function admin_delete($id) {
		$this->Liveset->delete($id);
		$this->Session->setFlash('Le set a été supprimé.', 'growl');	
		$this->redirect(array('controller' => 'livesets', 'action' => 'index'));
	}
	
}
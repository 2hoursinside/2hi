<?php
class SubmissionsController extends AppController {
	
	var $name = "Submissions";
	var $layout = 'none';
	
	function admin_index() {
		$submissions = $this->Submission->find('all', array('order' => 'Submission.name DESC'));
		$this->set('submissions', $submissions);
	}
	
	function add() {
		if (!empty($this->data)) {
			$resultat = $this->Submission->save($this->data);
			if ($resultat) {
				$this->Session->setFlash('La submission a été ajouté.', 'growl');	
				$this->redirect('/'); 
			}

		}
	}
	
	
	function admin_edit($id = null) {
		
		if (!empty($id)) {
			$this->Submission->id = $id;
	 
			if (empty($this->data)) {
				$this->data = $this->Submission->read(null, $id);
			} else {
				$resultat = $this->Submission->save($this->data);
				if ($resultat) {
					$this->Session->setFlash('La submission a été modifié.', 'growl');	
					$this->redirect(array('controller' => 'submissions', 'action' => 'index')); 
				}
			}
			
		} else {
			$this->Session->setFlash('Aucune submission trouvé.', 'growl', array('type' => 'error'));	
			$this->redirect(array('controller' => 'submissions', 'action' => 'index'));
		}
		
		$this->set('data', $this->data);
		$this->set('users', $this->Submission->User->find('list'));
	}
	
	
	function admin_delete($id) {
		$this->Submission->delete($id);
		$this->Session->setFlash('La submission a été supprimé.', 'growl');	
		$this->redirect(array('controller' => 'submissions', 'action' => 'index'));
	}

}

?>
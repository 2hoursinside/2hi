<?php
class InvitationsController extends AppController {
	
	var $name = "Invitations";
	
	function admin_index() {
		$invitations = $this->Invitation->find('all', array('order' => array('Invitation.created DESC')));
		$this->set('invitations', $invitations);
	}
	
	
	function admin_edit($id) {
		$this->Invitation->id = $id;
		
		if (empty($this->data)) {
			$this->data = $this->Invitation->read();
		
		} else {
			$resultat = $this->Invitation->save($this->data);
			if ($resultat) {
				$this->Session->setFlash('L\'invitation a été ajouté.', 'growl');	
				$this->redirect(array('controller' => 'invitations', 'action' => 'index')); 
			}
		}
	}
	
	
	function admin_add() {
		if (!empty($this->data)) {
			$resultat = $this->Invitation->save($this->data);
			if ($resultat) {
				$this->Session->setFlash('L\'invitation a été ajouté.', 'growl');	
				$this->redirect(array('controller' => 'invitations', 'action' => 'index')); 
			}
		}
	}
	
	function admin_adds() {
		if (!empty($this->data)) {
			
			// check les artistes
			$emails = trim($this->data['Invitation']['emails']);
			$emails = explode(",", $emails);
			
			foreach ($emails as $email) {
				$email = trim($email);
				$code = random(10);
				
				$data = array('Invitation' => array('count' => 1, 'code' => $code, 'email' => $email));
				$this->Invitation->create();
				$result = $this->Invitation->save($data);
			}
			
			if ($result) {
				$this->Session->setFlash('Les '. count($emails) . ' invitations ont été ajouté.', 'growl');	
				$this->redirect(array('controller' => 'invitations', 'action' => 'index')); 
			}
		}
	}
	
	function admin_delete($id) {
		$this->Invitation->delete($id);
		$this->Session->setFlash('L\'invitation a été supprimé.', 'growl');	
		$this->redirect(array('controller' => 'invitations', 'action' => 'index'));
	}
  
}

?>
<?php
class InvitationsController extends AppController {
	
	var $name = "Invitations";
	
	function admin_index() {
		$invitations = $this->Invitation->find('all', array('order' => array('Invitation.created DESC')));
		$this->set('invitations', $invitations);
	}
	
	function admin_send($id) {
    
		$invitation = $this->Invitation->find('first', array('conditions' => array('Invitation.id' => $id)));
		
		App::import('Vendor', 'swift/swift_required');
		
		$subject = 'Votre invitation pour 3 Jours Dehors, enfin !';
    $from = array('contact@3joursdehors.fr' => '3 Jours Dehors');
    $to = array(
     $invitation['Invitation']['email']  => $invitation['Invitation']['email'],
    );
    
    $transport = Swift_SmtpTransport::newInstance('smtp.mandrillapp.com', 587);
    $transport->setUsername('contact@3joursdehors.fr');
    $transport->setPassword('lwOdiSBxlPdaBpjkEgW1ug');
    $swift = Swift_Mailer::newInstance($transport);
    
    $message = new Swift_Message($subject);
    $message->setFrom($from);
    $message->setBody('', 'text/html');
    $message->setTo($to);
    $message->addPart('', 'text/plain');
    
    $headers = $message->getHeaders();
    $headers->addTextHeader('X-MC-GoogleAnalytics', '3joursdehors.fr');
    $headers->addTextHeader('X-MC-MergeVars', '{"invitecode": "' . $invitation['Invitation']['code'] . '"}');
    $headers->addTextHeader('X-MC-Template', 'invitation-beta');
    
    if ($recipients = $swift->send($message, $failures)) {
      $this->Invitation->id = $id;
      $this->Invitation->saveField('sent', 1);
    } 
		
		$this->Session->setFlash('L\'invitation a été envoyé.', 'growl');	
		$this->redirect(array('controller' => 'invitations', 'action' => 'index'));
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
			
			// check les emails
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
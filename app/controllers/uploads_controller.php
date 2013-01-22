<?php
class UploadsController extends AppController {
	
	var $name = "Uploads";
	var $layout = "none";
	
	function add() {
		$this->layout = 'none';
		
		if (!$this->data['Upload']['name']) { //check for uploaded file in $this->data
			$message = 'Vous devez choisir une image.';
			$this->set('error', 1);
			
		} else {
			$resultat = $this->Upload->save($this->data);
			if ($resultat) {
				$upload = $this->Upload->find('first', array('order' => array('Upload.id DESC')));
				$message = 'Le fichier a été uploadé.';
				$this->set(compact('upload'));
				$this->set('error', 0);
			} else {
				$message = 'Extension invalide. (uniquement .png, .jpg, .gif et .pdf)';
				$this->set('error', 1);
			}

		}
		$this->set(compact('message'));
	} 
	
}
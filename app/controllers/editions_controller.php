<?php
class EditionsController extends AppController {
	
	var $name = "Editions";
	var $layout = 'admin_default';
	
  function updateEditionUser($edition_id, $user_id, $field, $value){
    $data['Edition']['id'];
  }
  
  function admin_index() {
		$festivals = $this->Edition->Festival->find('list', array('order' => 'Festival.name ASC'));
		//$festivals = $this->Festival->find('all', array('conditions' => array('Festival.isFamous' => 1)));
		$this->set('festivals', $festivals);
	}
	
	function admin_temp() {
		$festival_id = $this->data['Edition']['festival_id'];
		$this->redirect(array('controller' => 'editions', 'action' => 'liste', $festival_id));
	}
	
	function admin_liste($festival_id) {		
		$liste_editions = $this->Edition->find('all', array('conditions' => array('festival_id =' => $festival_id), 'order' => 'Edition.date_start DESC'));
		
		$festival = $this->Edition->Festival->findById($festival_id);
		$this->set('festival', $festival);
		$this->set('editions', $liste_editions);
		$this->set('show_id', $festival_id);
		
	}
	
	function admin_add() {
		// Si données envoyée en POST
		if (!empty($this->data)) {
			
			$resultat = $this->Edition->save($this->data);
			if ($resultat) {
				$this->Session->setFlash('L\'édition a été ajouté.', 'growl');	
				$this->redirect('/admin/days/many/' . $this->data['Edition']['festival_id']); 
			}

		}
		$this->set('genres', $this->Artist->Genre->find('list'));
		$this->set('countries', $this->Artist->Country->find('list'));
	}
	
	function admin_preadd($festival_id) {
		
		$festival = $this->Edition->Festival->findById($festival_id);
		$this->set('festival', $festival);
	}
	
	
	function admin_edit($id) {
		$this->Edition->id = $id;
		
		$edition = $this->Edition->find('first', array('conditions' => array('Edition.id' => $id), 'contain' => array('Festival')));
		$this->set('edition', $edition);
		
		if (empty($this->data)) {
			$this->data = $this->Edition->read();
		} else {
			if ($this->Edition->save( $this->data )) {
				$this->Session->setFlash('L\'édition a été modifié.', 'growl');	
				$this->redirect('/admin/editions/liste/' . $this->data['Edition']['festival_id']); 
			}
		}
	}
  
    
  function getGenreEdition($id = null){
    if(!empty($id)){
      $edition = $this->Edition->find('first', array('conditions' => array('Edition.id' => $id), 'contain' => array('Artist')));
      App::import('Controller', 'Artists');
      $Artists = new ArtistsController;
      $Artists->ConstructClasses();
      
      if(!empty($edition['Artist'])){
        $edition['Genre'] = array();
      
        foreach($edition['Artist'] as $artist)
        {
          $genres = $Artists->getGenreArtist($artist['id']);
          foreach($genres as $genre)
          {
            if(!isset($edition['Genre'][$genre['name']]))
              $edition['Genre'][$genre['name']] = 1;
            else
              $edition['Genre'][$genre['name']] ++;
          }
        }
        arsort($edition['Genre']);
        $genre_keys = array_keys($edition['Genre']);
        $edition_genres = array();
        for ($i = 0; $i <= 4; $i++){
          $edition_genres[] = array_shift($genre_keys);
        }
        return($edition_genres);
      }else{
        return(array());
      }
    }else{
      $this->Session->setFlash('Aucune editions trouvées.', 'growl', array('type' => 'error'));	
			$this->redirect(array('controller' => 'editions', 'action' => 'index'));
    }
  }
	
}
?>
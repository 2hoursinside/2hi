<?php
class EditionsController extends AppController {
	
	var $name = "Editions";
	var $layout = 'admin_default';
	
  function updateEditionUser($edition_id, $user_id, $field, $value){
    $data['Edition']['id'];
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
<?php
class FestivalsController extends AppController {
	
	var $name = "Festivals";

	var $paginate = array (
		'order'     => 'Festival.name ASC',
		'recursive' => -1
	);
 	
	function index() {
		$festivals = $this->Festival->find('all', array('contain' => false, 'order' => 'isFamous DESC'));
		//$festivals = $this->Festival->find('all', array('conditions' => array('Festival.isFamous' => 1)));
		$this->set('festivals', $festivals);
	}
	
	function index2() {		
		debug($this->params['named']);
		
		// tableau de conditions pour la future requête
		if(!empty($this->params['named'])) {
			
			$conditions_genres = array();
			$conditions = array();
			
			foreach($this->params['named'] as $key => $arg) {
				switch($key) {
					case 'genre':
						//$genre = $this->Festival->Genre->findByUrl($args[1]);
						//$conditions_genres[] = 'Genre.id = ' . $genre['Genre']['id'];
					  	// FUCK CAKE AND HIS HABTM RELATIONS
						break;
					case 'country':
						$country = $this->Festival->Country->findByLocale($arg);
						$conditions[] = 'Festival.country_id = ' . $country['Country']['id'];
					
					default:
						break;				
				}
			}	
			
			$conditions_after = $conditions;
			$conditions_before = $conditions;
			$conditions_after[] = 'Edition.date_end >= CURDATE()';
			$conditions_before[] = 'Edition.date_end < CURDATE()';
			
			
			$editions_before = $this->Festival->Edition->find('all', array(
							'conditions' => $conditions_before, 
							'contain' => array('Festival' => array('Country', 'Genre')),
							'order' => 'Edition.date_end DESC'
			));
			
			$editions_after = $this->Festival->Edition->find('all', array(
							'conditions' => $conditions_after, 
							'contain' => array('Festival' => array('Country', 'Genre')),
							'order' => 'Edition.date_end ASC'
			));
			
			debug($conditions_after);
			debug($conditions_genres);
			
		} else {
		

			$editions_before = $this->Festival->Edition->find('all', array(
							'conditions' => array('Edition.date_end < CURDATE()'), 
							'contain' => array('Festival' => array('Country', 'Genre')),
							'order' => 'Edition.date_end DESC'
			));
			
			$editions_after = $this->Festival->Edition->find('all', array(
							'conditions' => array('Edition.date_end >= CURDATE()'), 
							'contain' => array('Festival' => array('Country', 'Genre')),
							'order' => 'Edition.date_end ASC'
			));
		
		}
		
		$this->set('genres', $this->Festival->Genre->find('all', array('contain' => false)));
		
		$this->set('editions_before', $editions_before);
		$this->set('editions_after', $editions_after);
	}
 
	function display($url) {
		$this->layout = 'default';
		$festival = $this->Festival->find('first', array('conditions' => array('Festival.url' => $url), 'contain' => array('Country', 'Region', 'Department', 'City', 'Genre'))); 
		$user = $this->Festival->Edition->User->find('first', array('contain' => array('Artist'), 'conditions' => array('id' => $this->Auth->user('id'))));
		
		$editions = $this->Festival->Edition->find('all',array(
					'conditions' => array('Edition.festival_id' => $festival['Festival']['id']), 										   
					'contain' => array(
							'Artist' => array('order' => 'Artist.familiarity DESC'), 
							'Day' => array('Artist' => array('order' => 'Artist.familiarity DESC')), 
							'User'
					), 
					'order' => 'Edition.date_start DESC'
		));
		
		// Photos
		App::import('Vendor', 'phpflickr/phpflickr');
		$f = new phpFlickr('896eac422575839cf320ca17d85d4a34', '9a472b222b2a1053', false);
		$photos = $f->photos_search(array("tags" => $festival['Festival']['name'] . '+2012', 'per_page' => 16));
    $this->set(compact('photos'));
		
		// ils y vont
		
		
		// Festival similaire
		/*
    $same_festival = $this->Festival->find('first', array(
      'conditions' => array(
        'NOT' => array('Festival.id' => $festival['Festival']['id'])
      ),
      'joins' => array(
        array('table' => 'festivals_genres',
            'alias' => 'fg',
            'type' => 'LEFT',
            'conditions' => 'fg.festival_id = Festival.id'
        )
      ),
      'contain' => array('Genre', 'Country', 'Department', 'City', 'Edition'),
      'fields'=> array('Festival.*, Country.locale, Department.name, City.name, COUNT(fg.`genre_id`) as nb_genre'),
      'group' => 'festival_id',
      'order' => 'nb_genre DESC'
    ));
    if (empty($same_festival))
      $same_festival = $this->Festival->find('first', array('conditions' => array('NOT' => array('Festival.id' => $festival['Festival']['id']))));
    
    if (isset($same_festival['Edition']) AND is_array($same_festival['Edition']) AND !empty($same_festival['Edition'][0])) {
      $last_edition = 0;
      $last_date = new DateTime('1970-01-01');
      foreach ($same_festival['Edition'] AS $key => $edition) {
        if ($last_date < new DateTime($edition['date_start'])) {
          $last_edition = $key;
          $last_date = new DateTime($edition['date_start']);
        }
      }
      $same_festival['Edition'] = $same_festival['Edition'][$last_edition];
    }
    */
    
    // Festival proche
    /*
    $nearest_festival = $this->Festival->find('first', array('conditions' => array('Festival.city_id' => $festival['City']['id'], 'NOT' => array( 'Festival.id' => $festival['Festival']['id']))));
		
    if (empty($nearest_festival)) {
      $nearest_festival = $this->Festival->find('first',array('conditions' => array('Festival.department_id' => $festival['Department']['id'], 'NOT' => array( 'Festival.id' => $festival['Festival']['id']))));
			
      if (empty($nearest_festival)) {
        $nearest_festival = $this->Festival->find('first',array('conditions' => array('Festival.region_id' => $festival['Region']['id'], 'NOT' => array( 'Festival.id' => $festival['Festival']['id']))));
				
        if (empty($nearest_festival)) {
          $nearest_festival = $this->Festival->find('first',array('conditions' => array('Festival.country_id' => $festival['Country']['id'], 'NOT' => array( 'Festival.id' => $festival['Festival']['id']))));
          
					if (empty($nearest_festival))
            $nearest_festival = $this->Festival->find('first',array('conditions' => array('NOT' => array( 'Festival.id' => $festival['Festival']['id']))));
        }
      }
    }
    
    if (isset($nearest_festival['Edition']) AND is_array($nearest_festival['Edition']) AND !empty($nearest_festival['Edition'][0])) {
      $last_edition = 0;
      $last_date = new DateTime('1970-01-01');
			
      foreach ($nearest_festival['Edition'] AS $key => $edition) {
        if ($last_date < new DateTime($edition['date_start'])) {
          $last_edition = $key;
          $last_date = new DateTime($edition['date_start']);
        }
      }
      $nearest_festival['Edition'] = $nearest_festival['Edition'][$last_edition];
    }
    */
    
    $fest_affinity = $this->getAffinityByFestival($festival['Festival']['id']);
    $this->set('fest_affinity', $fest_affinity);
    
    
		$this->set('editions', $editions);
		$this->set('festival', $festival);
		$this->set('user', $user);
    //$this->set('same_festival', $same_festival);
    //$this->set('nearest_festival', $nearest_festival);
	}
	
	function genre($genre_url) {
		$this->layout = 'default';
		$genre = $this->Festival->Genre->findByUrl($genre_url);
		$festivals = $this->Festival->Genre->find('all', array('conditions' => array('Genre.id' => $genre['Genre']['id'])));
		$this->set('festivals', $festivals);
		$this->render('index');
	}
	
  function getAffinityByFestival($id = null){
    $current_user_id = $this->Auth->user('id');
    $current_user = $this->Festival->Edition->User->find('first', array('conditions' => 'User.id = '.$current_user_id));
    
    $criteres = array(
      'favorite_artist' => 0.1,
      'liked_artist' => 0.05,
      'similar_artist' => 0.4,
      'seen' => 0.033
    );
    
    // on donne l'affinité pour un festival donné
    if (!empty($id)){
    
      // on choisit la dernière édition
      $current_festival = $this->Festival->find('first', array('conditions' => 'Festival.id = '.$id, 'contain' => array('Edition' => array('order' => 'date_start DESC'))));
      
      if(!empty($current_festival['Edition'])){
      
        // on trouve le nombre d'artistes total pour la derniere edition du festival
        $req_all_artists = '
          SELECT COUNT(ae.artist_id) as nb_artists
          FROM `artists_editions` ae 
          LEFT JOIN `editions` e ON(e.`id` = ae.`edition_id`)
          LEFT JOIN `festivals` f ON(f.`id` = e.`festival_id`)
          WHERE f.id = '.$id.'
          AND e.id = '.$current_festival['Edition'][0]['id'];
        $res_all_artists = $this->Festival->query($req_all_artists);
        $nb_total_artists = $res_all_artists[0][0]['nb_artists'];
  
        // on trouve le nombre d'artistes similaires pour la derniere edition du festival
        $req_similar_artists = '
          SELECT COUNT(ae.`artist_id`) as nb_similar
          FROM `artists_editions` ae 
          LEFT JOIN `editions` e ON(e.`id` = ae.`edition_id`)
          WHERE e.`date_end` > NOW()
          AND e.id = '.$current_festival['Edition'][0]['id'].'
          AND ae.`artist_id` IN
          (
           SELECT ag.`artist_id`
           FROM `artists_genres` ag
           WHERE ag.`genre_id` IN 
           (
            SELECT ag2.`genre_id`
            FROM `artists_users` au2 
            LEFT JOIN `artists_genres` ag2 ON( ag2.`artist_id` = au2.`artist_id`)
            WHERE au2.`user_id` = '.$current_user_id.'
            AND ag2.`genre_id` IS NOT NULL
            GROUP BY ag2.`genre_id`
           )
          )';
        $res_similar_artists = $this->Festival->query($req_similar_artists);
        $nb_similar_artists = $res_similar_artists[0][0]['nb_similar'];      
  
        // Requete 3 (recuperation des artistes likés présents)
        $req_liked_artists = '
          SELECT COUNT(au.`artist_id`) AS nb_liked
          FROM `artists_editions` ae 
          LEFT JOIN `artists_users` au ON(au.`artist_id` = ae.`artist_id`)
          LEFT JOIN `editions` e ON(e.`id` = ae.`edition_id`)
          WHERE au.`user_id` = '.$current_user_id.'
          AND e.`date_end` > NOW()
          AND ae.`edition_id` = '.$current_festival['Edition'][0]['id'];
        $res_liked_artists = $this->Festival->query($req_liked_artists);
        $nb_liked_artists = $res_liked_artists[0][0]['nb_liked'];
  
        // Requete 4 (recuperation des artistes favoris présents)
        $req_favorite_artists = '
          SELECT COUNT(au.`artist_id`) AS nb_favorite
          FROM `artists_editions` ae 
          LEFT JOIN `artists_users` au ON(au.`artist_id` = ae.`artist_id`)
          LEFT JOIN `editions` e ON(e.`id` = ae.`edition_id`)
          WHERE au.`user_id` = '.$current_user_id.'
          AND au.`favorite` = 1
          AND e.`date_end` > NOW()
          AND ae.`edition_id` = '.$current_festival['Edition'][0]['id'];
        $res_favorite_artists = $this->Festival->query($req_favorite_artists);
        $nb_favorite_artists = $res_favorite_artists[0][0]['nb_favorite'];
  
        // Requete 5 (recupere les festivals que l'utilisateur à déjà vu)
        $req_seen_editions = '
          SELECT COUNT(eu.edition_id) as nb_seen
          FROM editions_users eu
          LEFT JOIN editions e ON (e.id = eu.edition_id)
          LEFT JOIN festivals f ON(f.id = e.festival_id)
          WHERE eu.user_id = '.$current_user_id.'
          AND f.id = '.$current_festival['Festival']['id'].'
          AND eu.type = 3';
        $res_seen_editions = $this->Festival->query($req_seen_editions);
        $nb_seen_editions = $res_seen_editions[0][0]['nb_seen'];
  
        $affinity = 0;
  
        if ((int)($nb_total_artists) > 0)
          $affinity += ((int)($nb_similar_artists) / (int)($nb_total_artists)) * (float)($criteres['similar_artist']);
  
        if ((int)($nb_liked_artists) > 6) {
          $affinity += 6 * (float)($criteres['liked_artist']);
        } elseif ((int)($nb_liked_artists) > 0) {
          $affinity += ((int)($nb_liked_artists)) * (float)($criteres['liked_artist']);
        }
  
        if ((int)($nb_favorite_artists) > 3) {
          $affinity += 3 * (float)($criteres['favorite_artist']);
        } elseif ((int)($nb_favorite_artists) > 0) {
          $affinity += ((int)($nb_favorite_artists)) * (float)($criteres['favorite_artist']);
        }
          
        if ($nb_seen_editions > 3){
          $affinity += 3 * (float)($criteres['seen']);
        } else {
          $affinity += (int)($nb_seen_editions) * (float)($criteres['seen']);
        }
  
        if($affinity > 1)
          $affinity = 1;
  
        return $affinity;
      } else {
        return 0;
      }

    // on donne l'affinité pour tous les festivals  
    } else {
      // on charge tous les festivals et leurs editions
      $current_festival = $this->Festival->find('all', array('contain' => array('Edition' => array('order' => 'date_start DESC'))));
      debug($current_festival); die();
    }
  }
	
	function admin_addartists() {		
	
	}
	
	function admin_addartist($festival_id) {
	
		$festival = $this->Festival->findById($festival_id);
		$this->set('festival', $festival);
		
		$editions = $this->Festival->Edition->find('list', array('conditions' => array('Edition.festival_id' => $festival_id), 'order' => 'Edition.date_start DESC'));
		
		$days = $this->Festival->Edition->Day->find('list', array('conditions' => 'Day.edition_id IN (' . implode(",", array_keys($editions)) . ')', 'order' => 'Day.date DESC', 'contain' => false));
		$this->set('editions', $this->Festival->Edition->find('list', array('conditions' => array('Edition.festival_id' => $festival_id))));
		$this->set('days', $days);	
		
		
		if (!empty($this->data)) {
			
			// check les artistes
			$artists = trim($this->data['Festival']['artists']);
			$artists = explode(",", $artists);
			$artists_id = array();
			
			foreach ($artists as $artist) {
				$artist = trim($artist);
				
				if (!empty($artist)) {
					$tmp_artist = $this->Festival->Edition->Artist->find('first', array(
						'contain' => false,
						'conditions' => 'Artist.name LIKE "' . $artist . '" OR Artist.other_names LIKE "%' . $artist . '" OR Artist.url LIKE "' . formatUrl($artist, false) . '"'
					));
					
					if (!empty($tmp_artist)) {
						$artists_id[] = $tmp_artist['Artist']['id'];
					} else {
						App::import('Controller', 'Artists');
						$Artists = new ArtistsController;
						$Artists->ConstructClasses();
						$last_id = $Artists->createFromLineup($artist);
						$artists_id[] = $last_id;
					}
				}

			}
			
			// enregistre dans artists_editions
			$result = $this->Festival->Edition->habtmAdd('Artist', $this->data['Festival']['edition_id'], $artists_id); 
			
			// enregistre dans artists_days si un jour est précisé
			if ($this->data['Festival']['day_id'] != 0 && $result) {
				$this->Festival->Edition->Day->habtmAdd('Artist', $this->data['Festival']['day_id'], $artists_id);				
			}
			
			if ($result) {
				$this->Session->setFlash('Les artistes ont été ajoutés.');	
				$this->redirect('/admin/festivals/addartist/' . $this->data['Festival']['festival_id']);
				//$this->redirect(array('controller' => 'festivals', 'action' => 'addartist', $this->data['Festival']['id'])); 
			}

		}
	}
	
	
	function admin_index() {
		$festivals = $this->Festival->find('all', array('contain' => array('Edition', 'Region', 'Country'), 'order' => array('Festival.isFamous' => 'DESC', 'Festival.name' => 'ASC')));
		//$festivals = $this->Festival->find('all', array('conditions' => array('Festival.isFamous' => 1), 'order' => 'Festival.name ASC'));
		$this->set('festivals', $festivals);
	}
	
	function admin_import() {
		$this->autoRender = false;
		$filename = TMP . DS . 'festivals.csv';
 		$handle = fopen($filename, "r");
 		
 		// read the 1st row as headings
 		$header = fgetcsv($handle, 0, ';');
 		
		// create a message container
		$return = array(
			'messages' => array(),
			'errors' => array(),
		);

 		// read each data row in the file
 		while (($row = fgetcsv($handle, 0, ';')) !== FALSE) {
 			$data = array();

 			// for each header field 
 			foreach ($header as $k => $head) {
				
 				// get the data field from Model.field
 				if (strpos($head,'.')!== false) {
					
	 				$h = explode('.',$head);
	 				
					if ($h[1] === 'name') {
						$data[$h[0]]['url'] = formatUrl(ucfirst(strtolower($row[$k])), true);
						$row[$k] = utf8_encode(ucfirst(strtolower($row[$k])));
						$data['Festival']['bio'] = array(
								'fre' => '',
								'eng' => ''
						);
						
					} elseif ($h[1] === 'city_id') {
						$row[$k] = utf8_encode(str_replace('ST', 'SAINT', str_replace(' ', '-', $row[$k])));
						$city = $this->Festival->City->find('first', array('contain' => false, 'conditions' => array('City.name_upper' => $row[$k])));
						$row[$k] = $city['City']['id'];
					
					} elseif ($h[1] === 'department_id') {
						$row[$k] = utf8_encode(str_replace('-', ' ', $row[$k]));
						$department = $this->Festival->Department->find('first', array('contain' => false, 'conditions' => 'Department.name LIKE "%' . $row[$k] . '%"'));
						$row[$k] = $department['Department']['id'];
						
					} elseif ($h[1] === 'region_id') {
						$row[$k] = utf8_encode(str_replace('-', ' ', $row[$k]));
						$region = $this->Festival->Region->find('first', array('contain' => false, 'conditions' => 'Region.name LIKE "%' . $row[$k] . '%"'));
						$row[$k] = $region['Region']['id'];
						$data[$h[0]]['country_id'] = 1;
					
					} elseif ($h[1] === 'country_id') {
						//$row[$k] = 1;
					}
					
					$data[$h[0]][$h[1]] = (isset($row[$k])) ? $row[$k] : '';
					
				/* get the data field from field
				} else {
					echo 'leprout';
	 				$data['Festival'][$head] = (isset($row[$k])) ? $row[$k] : ''; */
				}
 			}

			// see if we have an id 			
 			$id = isset($data['Festival']['id']) ? $data['Festival']['id'] : 0;
			
			debug($data);
			
			// we have an id, so we update
 			if ($id)
	 			$this->id = $id;
			else
	 			$this->Festival->create();
			
 			// validate the row
			$this->set($data);
			if (!$this->Festival->validates()) {
				$this->_flash('warning');
				$return['errors'][] = __(sprintf('Festival ' . $data['Festival']['name'] . ' for Row %d failed to validate.',$i), true);
			}

 			// save the row
			if (!$error && !$this->Festival->save($data)) {
				$return['errors'][] = __(sprintf('Festival ' . $data['Festival']['name'] . ' for Row %d failed to save.',$i), true);
			}

 			// success message!
			if (!$error) {
				$return['messages'][] = __(sprintf('Festival ' . $data['Festival']['name'] . ' for Row %d was saved.',$i), true);
			}
			
 		}
 		
 		// close the file
 		fclose($handle);
 		
 		// return the messages
 		return $return;
	}
	
	
	function admin_check($festival_id, $check) {
		$this->Festival->id = $festival_id;
		$this->Festival->saveField('must_be_checked', $check);
		$this->redirect(array('controller' => 'festivals', 'action' => 'index'));
	}
	
	function admin_add() {
		// Si données envoyée en POST
		if (!empty($this->data)) {

			$this->data['Festival']['url'] = formatUrl($this->data['Festival']['name'], false);
			
			if (!empty($this->data['Festival']['postal_code'])) {
				$city = $this->Festival->City->find('first', array('conditions' => array('City.postal_code' => $this->data['Festival']['postal_code'])));
				if (!empty($city)) {
					$this->data['Festival']['city_id'] = $city['City']['id'];
				}
			}
			
			$resultat = $this->Festival->save($this->data);
			if ($resultat) {
				$this->Session->setFlash('Le festival a été ajouté.', 'growl');	
				$this->redirect(array('controller' => 'festivals', 'action' => 'index')); 
			}
		}
		$this->set('regions', $this->Festival->Region->find('list'));
		$this->set('departments', $this->Festival->Department->find('list', array('order' => 'name ASC')));
		$this->set('countries', $this->Festival->Country->find('list'));
		//$this->set('cities', $this->Festival->City->find('list'));
		$this->set('genres', $this->Festival->Genre->find('list'));
	}
	
	
	function admin_edit($id = null) {
		
		if (!empty($id)) {
			$this->Festival->id = $id;
			
			// Langues à éditer
			$locales = array_values(Configure::read('Config.languages'));
			$this->Festival->locale = $locales;
	 
			if (empty($this->data)) {
				$this->data = $this->Festival->read(null, $id);
			} else {
				
				if (!empty($this->data['Festival']['postal_code'])) {
					$city = $this->Festival->City->find('first', array('conditions' => array('City.postal_code' => $this->data['Festival']['postal_code'])));
					if (!empty($city)) {
						$this->data['Festival']['city_id'] = $city['City']['id'];
					}
				}			
				$resultat = $this->Festival->save($this->data);
				if ($resultat) {
					$this->Session->setFlash('Le festivale a été modifié.', 'growl');	
					$this->redirect(array('controller' => 'festivals', 'action' => 'index')); 
				}
			}
			
		} else {
			$this->Session->setFlash('Aucun festival trouvé.', 'growl', array('type' => 'error'));	
			$this->redirect(array('controller' => 'festivals', 'action' => 'index'));
		}
		
		$this->set('regions', $this->Festival->Region->find('list'));
		$this->set('departments', $this->Festival->Department->find('list'));
		$this->set('countries', $this->Festival->Country->find('list'));
		//$this->set('cities', $this->Festival->City->find('list'));
		$this->set('genres', $this->Festival->Genre->find('list'));
		$this->set('data', $this->data);
	}

	function admin_delete($id) {
		$this->Festival->delete($id);
		$this->Session->setFlash('Le festival a été supprimé.', 'growl');	
		$this->redirect(array('controller' => 'festivals', 'action' => 'index'));
	}

  function admin_build_genres($id = null){
    // si id présent, on met a jour le festival donné
    if (!empty($id)) {
      $festival = $this->Festival->find('first', array(
        'fields' => array('Festival.*', 'Edition.*'),
        'joins' => array(
          array(
            'table' => 'editions',
            'alias' => 'Edition',
            'type' => 'LEFT',
            'conditions' => array(
              'Edition.festival_id = Festival.id',
            )
          ),
        ),
        'conditions' => array('Festival.id' => $id), 
        'contain' => false,
        'order' => 'Edition.date_start DESC')
      );
      
      if (!empty($festival['Edition'])) {
        App::import('Controller', 'Editions');
        $Edition = new EditionsController;
        $Edition->ConstructClasses();
        $genres = $Edition->getGenreEdition($festival['Edition']['id']);
        
        if (!empty($genres)) {
          foreach($genres as $genre){
            $genre_object = $this->Festival->Genre->find('first', array('conditions' => array('Genre.name LIKE "'.$genre.'"'), 'contain' => false));
            $this->Festival->Genre->habtmAdd('Festival', $genre_object['Genre']['id'], $id);
          }
        }
      } else {
        $this->Session->setFlash('Aucune editions trouvée.', 'growl', array('type' => 'error'));	
        $this->redirect(array('controller' => 'festivals', 'action' => 'index'));
      }
      
    // on met a jour tous les festivals
    } else {
      $festivals = $this->Festival->find('all', array('contain' => false));
      
      if (!empty($festivals)) {
        foreach($festivals as $festival){
        
          $current_festival = $this->Festival->find('first', array(
            'fields' => array('Festival.*', 'Edition.*'),
            'joins' => array(
              array(
                'table' => 'editions',
                'alias' => 'Edition',
                'type' => 'LEFT',
                'conditions' => array(
                  'Edition.festival_id = Festival.id',
                )
              ),
            ), 
            'contain' => false,
            'conditions' => array('Festival.id' => $festival['Festival']['id']),
            'order' => 'Edition.date_start DESC'
          ));
          
          if (isset($current_festival['Edition']['id'])) {
          
            App::import('Controller', 'Editions');
            $Edition = new EditionsController;
            $Edition->ConstructClasses();
            $genres = $Edition->getGenreEdition($current_festival['Edition']['id']);
            
            if (!empty($genres)) {
              foreach($genres as $genre) {
                $genre_object = $this->Festival->Genre->find('first', array('conditions' => array('Genre.name LIKE "'.$genre.'"'), 'contain' => false));
                $this->Festival->Genre->habtmAdd('Festival', $genre_object['Genre']['id'], $festival['Festival']['id']);
              }
            }
          }
        }
      } else {
        $this->Session->setFlash('Aucun festival trouvé.', 'growl', array('type' => 'error'));	
        $this->redirect(array('controller' => 'festivals', 'action' => 'index'));
      }
    }
  }
}

?>
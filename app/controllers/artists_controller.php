<?php
class ArtistsController extends AppController {
	
	var $name = "Artists";
	var $layout = 'default';
	
	var $paginate = array(
		'order'     => 'Artist.name ASC',
	);
	
	
	
	function home() {
		// tests
		//$this->Artist->habtmAdd('User', 92, array(32,12));
		//$this->Artist->User->habtmAdd('Artist', 32, 92);
	}
	
	
	// use maybe later, in a future version
	function home_v2() {
		$artists = $this->Artist->find('all', array('conditions' => array('Artist.home' => 1), 'contain' => 0));	
		$genres = $this->Artist->Genre->find('all', array('contain' => 0));
		$this->set('artists', $artists);
		$this->set('genres', $genres);
		$this->layout = 'default';
		$this->render('home_v2');
	}
	
	
	function index() {
		$this->layout = 'default';
		
		// artistes qui tournent
		$this->Artist->bindModel(array(
    'hasOne' => array(
        'ArtistsEdition',
        'FilterEdition' => array(
            'className' => 'Edition',
            'foreignKey' => false,
            'conditions' => array('FilterEdition.id = ArtistsEdition.edition_id')
    ))));
		
    $artists_mainstream = $this->Artist->find('all', array(
            'fields' => array('Artist.*, COUNT(Artist.id) as nbeditions'),
            'conditions' => array('YEAR(FilterEdition.date_start) = 2013'), 
            'limit' => 20,
            'order' => 'nbeditions DESC',
            'group' => 'Artist.name'
    ));
    
    $this->Artist->bindModel(array(
    'hasOne' => array(
        'ArtistsEdition',
        'FilterEdition' => array(
            'className' => 'Edition',
            'foreignKey' => false,
            'conditions' => array('FilterEdition.id = ArtistsEdition.edition_id')
    ))));
    
    $artists_superstars = $this->Artist->find('all', array(
            'fields' => array('Artist.*, COUNT(Artist.id) as nbeditions'),
            'conditions' => array('YEAR(FilterEdition.date_start) = 2013 AND Artist.familiarity > 0.85'), 
            'limit' => 20,
            'order' => 'nbeditions DESC',
            'group' => 'Artist.name'
    ));
    
    $this->Artist->bindModel(array(
    'hasOne' => array(
        'ArtistsEdition',
        'FilterEdition' => array(
            'className' => 'Edition',
            'foreignKey' => false,
            'conditions' => array('FilterEdition.id = ArtistsEdition.edition_id')
    ))));
    
    $artists_hype = $this->Artist->find('all', array(
            'fields' => array('Artist.*, COUNT(Artist.id) as nbeditions'),
            'conditions' => array('YEAR(FilterEdition.date_start) = 2013 AND Artist.hotttnesss > 0.65'), 
            'limit' => 20,
            'order' => 'nbeditions DESC',
            'group' => 'Artist.name'
    ));
    
    $this->Artist->bindModel(array(
    'hasOne' => array(
        'ArtistsEdition',
        'FilterEdition' => array(
            'className' => 'Edition',
            'foreignKey' => false,
            'conditions' => array('FilterEdition.id = ArtistsEdition.edition_id')
    ))));
    
    $artists_new = $this->Artist->find('all', array(
            'fields' => array('Artist.*, COUNT(Artist.id) as nbeditions'),
            'conditions' => array('YEAR(FilterEdition.date_start) = 2013 AND Artist.hotttnesss > 0.65'), 
            'limit' => 20,
            'order' => 'nbeditions DESC',
            'group' => 'Artist.name'
    ));
    
    $this->set('artists_new', $artists_new);
		$this->set('artists_hype', $artists_hype);
		$this->set('artists_mainstream', $artists_mainstream);
		$this->set('artists_superstars', $artists_superstars);
		
		// les découvertes
	}
	
	
	function display($url) {
		$this->layout = 'default';
		$must_update = false; // doit updater des champs de l'artiste ou pas
		
		$date_now = date('Y-m-d', time());
		$artist = $this->Artist->find('first', array('conditions' => array('Artist.url' => $url), 'contain' => array('Country', 'Genre')));
		$user = $this->Artist->User->find('first', array('contain' => array('Artist'), 'conditions' => array('id' => $this->Auth->user('id'))));
		
		// éditions à venir
		$coming_editions = $this->Artist->find('first', array('conditions' => array('Artist.url' => $url), 'contain' => array('Edition' => array('Artist', 'order' => 'Edition.date_start ASC', 'conditions' => array('Edition.date_end >' => $date_now), 'Festival' => array('Country', 'Region', 'Department', 'City', 'Genre')) )));
		$coming_editions = $coming_editions['Edition'];
		
		// éditions passées
		$past_editions = $this->Artist->find('first', array('conditions' => array('Artist.url' => $url), 'contain' => array('Edition' => array('Artist', 'order' => 'Edition.date_start DESC', 'conditions' => array('Edition.date_end <' => $date_now), 'Festival' => array('Country', 'Region', 'Department', 'City', 'Genre')) )));
		$past_editions = $past_editions['Edition'];
		
		$this->Artist->id = $artist['Artist']['id'];
		App::import('Core', 'HttpSocket');
		$HttpSocket = new HttpSocket();
		
		$artist_infos = json_decode($HttpSocket->get('http://developer.echonest.com/api/v4/artist/profile?api_key=7CVRJ3W1SLKTU0LYP&name=' . $artist['Artist']['name'] . '&bucket=hotttnesss'));
		// TODO : rajouter &bucket=twitter&bucket=songs&bucket=urls&bucket=images QUAND CETTE PUTAIN D'API MARCHERA
		if ($artist_infos->response->status->code === 0) {
			if ($artist['Artist']['hotttnesss'] == 0) {
				$this->Artist->set('hotttnesss', $artist_infos->response->artist->hotttnesss);
				$must_update = true;
			}
			$artist_infos = $artist_infos->response->artist; 
		}
		
		$artist_familiarity = json_decode($HttpSocket->get('http://developer.echonest.com/api/v4/artist/profile?api_key=7CVRJ3W1SLKTU0LYP&name=' . $artist['Artist']['name'] . '&bucket=familiarity'));
		if ($artist_familiarity->response->status->code === 0) {
			if ($artist['Artist']['familiarity'] == 0) {
				$this->Artist->set('familiarity', $artist_familiarity->response->artist->familiarity);
				$must_update = true;
			}
		}
		
		// Similaires
		$similar_artists_echonest = json_decode($HttpSocket->get('http://developer.echonest.com/api/v4/artist/similar?api_key=7CVRJ3W1SLKTU0LYP&name=' . $artist['Artist']['name'] . '&results=5'));
		if ($similar_artists_echonest->response->status->code === 0) {
			$similar_artists_echonest =  $similar_artists_echonest->response->artists;
			$similar_artists = array();
			
			foreach($similar_artists_echonest as $j => $tmp_artist) {
				if (is_object($tmp_artist)) $tmp_artist = get_object_vars($tmp_artist);
					$tjd_artist = $this->Artist->find('first', array(
						'contain' => false,
						'conditions' => 'Artist.name LIKE "' . $tmp_artist['name'] . '" OR Artist.other_names LIKE "%' . $tmp_artist['name'] . '" OR Artist.url LIKE "' . formatUrl($tmp_artist['name'], false) . '"'
					));
					
					if (!empty($tjd_artist)) {
						$similar_artists[] = $tjd_artist;
					} 
			}
		}

		// Genres
		if (empty($artist['Genre'])) {
			$artist_genres = json_decode($HttpSocket->get('http://developer.echonest.com/api/v4/artist/profile?api_key=7CVRJ3W1SLKTU0LYP&name=' . $artist['Artist']['name'] . '&bucket=terms'));  
			
			if ($artist_genres->response->status->code === 0) {
				$artist_genres = $artist_genres->response->artist; 
				$genres = $this->createAndLinkGenres($artist_genres->terms, $artist['Artist']['id']);
				if (!empty($genres)) $artist['Genre'] = $genres;
			} 
			
		}
		
		/* Chanson populaire
		if (!empty($artist_infos)) {
			$artist_song = json_decode($HttpSocket->get('http://developer.echonest.com/api/v4/song/search', 'api_key=7CVRJ3W1SLKTU0LYP&artist_id=' . $artist_infos->id . '&sort=song_hotttnesss-desc&results=1')); 
			$artist_song = $artist_song->response->songs;
		} else {
			$artist_song = '';
		}
		*/
		
		// Twitter 
		if (!empty($artist_infos)) {
			$artist_twitter = json_decode($HttpSocket->get('http://developer.echonest.com/api/v4/artist/twitter', 'api_key=7CVRJ3W1SLKTU0LYP&id=' . $artist_infos->id)); 
			$artist_twitter = $artist_twitter->response;
		} else {
			$artist_twitter = '';
		}
		
		// URL 
		if (!empty($artist_infos)) {
			$artist_urls = json_decode($HttpSocket->get('http://developer.echonest.com/api/v4/artist/urls', 'api_key=7CVRJ3W1SLKTU0LYP&id=' . $artist_infos->id)); 
			$artist_urls = $artist_urls->response->urls;
		} else {
			$artist_urls = '';
		}
		
		if ($must_update) {
			$this->Artist->save();
		}
		
		// TODO : Artistes Similaires + Enregistrer les infos en BDD pour ne pas avoir à faire les requêtes (prévoir un délai d'expiration pour re-maj)
		
		if (!empty($artist_song))
			$sxml = simplexml_load_file('http://gdata.youtube.com/feeds/api/videos?q=' . $artist['Artist']['name'] . ' ' . $artist_song[0]->title . '&v=2&max-results=1');
		else 
			$sxml = simplexml_load_file('http://gdata.youtube.com/feeds/api/videos?q=' . $artist['Artist']['name'] . '&v=2&max-results=1');
		
		$videourl = $sxml->entry->id;
		$videoname = $sxml->entry->title;
		// tag:youtube.com,2008:video:VwzRLgJorYQ
		$pieces = explode(":", $videourl);
		$videourl = $pieces[(count($pieces) -1)];
		
		$this->set(compact('user', 'videourl', 'videoname', 'past_editions', 'coming_editions', 'lineups', 'artist_infos', 'similar_artists', 'artist', 'artist_song', 'artist_twitter', 'artist_urls'));
	}
	
  
  function getGenreArtist($id = null){
    //TODO : verifier les genres desactivés
    //on test si l'id est bien renseigné
    if(!empty($id)){
      $this->Artist->id = $id;
      $artist = $this->Artist->find('first', array('conditions' => array('Artist.id' => $id), 'contain' => array('Country', 'Genre')));

/*      
      $terms = $this->Artist->Genre->find('all', array(
        'fields' => array('Genre.*'),
        'contain' => false,
        'conditions' => array('Artist.id' => $id, 'Genre.disabled = 0'),
        'joins' => array(
          array(
            'table' => 'artists_genres',
            'alias' => 'ArtistsGenre',
            'type' => 'LEFT',
            'conditions' => array(
              'Genre.id = ArtistsGenre.genre_id',
            )
          ),
          array(
            'table' => 'artists',
            'alias' => 'Artist',
            'type' => 'LEFT',
            'conditions' => array(
              'Artist.id = ArtistsGenre.artist_id',
            )
          )
        )
      ));
      debug($terms);
      die();
*/

      // on test si aucun genre n'a été enregistré pour cet artiste
      if (empty($artist['Genre'])){
        App::import('Core', 'HttpSocket');
  	    $HttpSocket = new HttpSocket();
        $artist_genres = json_decode($HttpSocket->get('http://developer.echonest.com/api/v4/artist/profile?api_key=7CVRJ3W1SLKTU0LYP&name='.$artist['Artist']['name'].'&bucket=terms')); 
        if ($artist_genres->response->status->code === 0){
  				$artist_genres = $artist_genres->response->artist; 
  				$genres = $this->createAndLinkGenres($artist_genres->terms, $artist['Artist']['id']);
  				if (!empty($genres)) $artist['Genre'] = $genres;
        }
      }
      
      return $artist['Genre'];
    } else {
      $this->Session->setFlash('Aucun artiste trouvé.', 'growl', array('type' => 'error'));	
			$this->redirect(array('controller' => 'artists', 'action' => 'index'));
    }
  }
  
  function regenerateGenreArtist(){
      $artists = $this->Artist->find('all', array('contain' => array('Genre')));
      
      foreach($artists as $artist){
        App::import('Core', 'HttpSocket');
  	    $HttpSocket = new HttpSocket();
        $artist_genres = json_decode($HttpSocket->get('http://developer.echonest.com/api/v4/artist/profile?api_key=7CVRJ3W1SLKTU0LYP&name='.$artist['Artist']['name'].'&bucket=terms')); 
        if ($artist_genres->response->status->code === 0){
  				$artist_genres = $artist_genres->response->artist; 
  				$genres = $this->createAndLinkGenres($artist_genres->terms, $artist['Artist']['id']);
  				if (!empty($genres)) $artist['Genre'] = $genres;
        }
      }
  }
	
  function createFromLastfm($data) {
		$this->Artist->create();
		$this->Artist->set($data);
		$this->Artist->save();
		return $this->Artist->getLastInsertID();
	}
  
	function createFromFb($data) {
		$this->Artist->create();
		$this->Artist->set($data);
		$this->Artist->save();
		return $this->Artist->getLastInsertID();
	}
	
	function createFromLineup($artist) {
		$this->Artist->create();
		
		App::import('Core', 'HttpSocket');
		$HttpSocket = new HttpSocket();
		
		$artist_hotttnesss = json_decode($HttpSocket->get('http://developer.echonest.com/api/v4/artist/profile?api_key=7CVRJ3W1SLKTU0LYP&name=' . $artist . '&bucket=hotttnesss'));
		$artist_hotttnesss = $artist_hotttnesss->response->artist->hotttnesss;
		
		$artist_familiarity = json_decode($HttpSocket->get('http://developer.echonest.com/api/v4/artist/profile?api_key=7CVRJ3W1SLKTU0LYP&name=' . $artist . '&bucket=familiarity'));
		$artist_familiarity = $artist_familiarity->response->artist->familiarity;
		
		$this->Artist->set(array(
			'name' => $artist,
			'url' => formatUrl($artist, false),
			'hotttnesss' => $artist_hotttnesss,
			'familiarity' => $artist_familiarity,
			'bio' => array(
				'fre' => '',
				'eng' => ''
			)
		));
		$this->Artist->save();
		return $this->Artist->getLastInsertID();
	}
	
	
	// création des genres si inconnus
	// ajout des genres à l'artist passé en paramètre
	// RETURN tableau ['Genre'] = array([0] => ...) 
	function createAndLinkGenres($genres, $artist_id) {
		$new_genres = array();
		$genres_to_link = array();
		
		foreach($genres as $j => $genre) {
			if ($j > 4) break;
			if (is_object($genre)) $genre = get_object_vars($genre);
			$genre_name = str_replace('-', ' ', ucfirst(strtolower($genre['name'])));
			$genre_url = formatUrl($genre['name'], false);
			$tmp_genre = $this->Artist->Genre->find('first', array('contain' => false, 'conditions' => 'Genre.name LIKE "' . $genre_name . '"'));
			
			if (empty($tmp_genre)) {
				$this->Artist->Genre->create();
				$this->Artist->Genre->set(array(
					'name' => $genre_name,
					'url' => $genre_url
				));
				$result = $this->Artist->Genre->save();
				$genre_id = $this->Artist->Genre->getLastInsertID();
			} else {
				$result = true;
				$genre_id = $tmp_genre['Genre']['id'];
			}
			
			// ajout à l'artiste
			if ($result) {
				//debug($genre_id);
				$genres_to_link[] = $genre_id;
				$new_genres[] = array('name' => $genre_name, 'url' => $genre_url);
			}
		}
		
		if (!empty($genres_to_link)) { $this->Artist->habtmAdd('Genre', $artist_id, $genres_to_link); }
		return $new_genres;
	}
	
	function getEchonestInfos($name) {
		return json_decode($HttpSocket->get('http://developer.echonest.com/api/v4/artist/profile?api_key=7CVRJ3W1SLKTU0LYP&name=' . $name . '&bucket=familiarity&bucket=urls'));
	}
	
	function admin_index($lettre = null) {
		if (empty($lettre)) $lettre = 'a';
		if ($lettre == 9) 
			$artists = $this->Artist->find('all', array('contain' => array('Genre', 'Biographies'), 'order' => 'Artist.name ASC', 'limit' => 100));
		else
			$artists = $this->Artist->find('all', array('contain' => array('Genre', 'Biographies'), 'conditions' => array('Artist.name LIKE' => $lettre . '%')));
		$this->set('artists', $artists);
	}
	
	function admin_add() {
		// Si données envoyée en POST
		if (!empty($this->data)) {
			$this->data['Artist']['url'] = formatUrl($this->data['Artist']['name'], false);
			
			$resultat = $this->Artist->save($this->data);
			if ($resultat) {
				$this->Session->setFlash('L\'artiste a été ajouté.', 'growl');	
				$this->redirect(array('controller' => 'artists', 'action' => 'index')); 
			}

		}
		$this->set('genres', $this->Artist->Genre->find('list'));
		$this->set('countries', $this->Artist->Country->find('list'));
	}
	
	
	function admin_edit($id = null) {
		
		if (!empty($id)) {
			$this->Artist->id = $id;
			
			// Langues à éditer
			$locales = array_values(Configure::read('Config.languages'));
			$this->Artist->locale = $locales;
	 
			if (empty($this->data)) {
				$this->data = $this->Artist->read(null, $id);
			} else {
				$resultat = $this->Artist->save($this->data);
				if ($resultat) {
					$this->Session->setFlash('L\'artiste a été modifié.', 'growl');	
					$this->redirect(array('controller' => 'artists', 'action' => 'index')); 
				}
			}
			
		} else {
			$this->Session->setFlash('Aucun artiste trouvé.', 'growl', array('type' => 'error'));	
			$this->redirect(array('controller' => 'artists', 'action' => 'index'));
		}
		
		$this->set('data', $this->data);
		$this->set('genres', $this->Artist->Genre->find('list'));
		$this->set('countries', $this->Artist->Country->find('list'));
	}
	
	
	
	function admin_delete($id) {
		$this->Artist->delete($id);
		$this->Session->setFlash('L\'artiste a été supprimé.', 'growl');	
		$this->redirect(array('controller' => 'artists', 'action' => 'index'));
	}

}

?>
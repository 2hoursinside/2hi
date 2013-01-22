<?php
class UsersController extends AppController {
  
	var $name = "Users";
	
	function login() {
    $this->layout = 'default_login';
		
		if ($this->Auth->user('id')) {
			$this->redirect('/');
		}
	}
	
	function logout() {
		$this->Session->setFlash('Vous êtes maintenant déconnecté.', 'growl');	
  	$this->Auth->logout();
		$this->Session->destroy();
		$this->redirect('/');
	}
	
	
  function afterFacebookLogin(){
/*    App::import('Lib', 'Facebook.FB');
		$Facebook = new FB();
		$user_me = $Facebook->api('/me?fields=id,first_name,last_name,birthday,hometown,email');
    $user_hometown = $Facebook->api('/'.$user_me['hometown']['id'].'?fields=id,name,location,category');
*/
//var_dump($this->Auth->user('id'));
//die();

//    $this->User->id = $this->Auth->user('id')
//    $this->User->set(array('name' => 'Gaetan));
//    save
  }
	
	function register() {
		$this->layout = 'default_login';
		
		if (!isset($this->data)) {
			if (isset ($this->params['url']['code']) && !empty($this->params['url']['code'])) {
				$this->data['User']['invitation_code'] = $this->params['url']['code'];																														 
			}
			
		} else {
			
			// vient d'arriver de /login pour compléter
			if (empty($this->data['User']['gender'])) {
				
			// validation
			} else {
				
				App::import('Core', 'sanitize');
				$clean_login = Sanitize::clean($this->data['User']['login']);
				$clean_email = Sanitize::clean($this->data['User']['email']);
				$clean_password = Sanitize::clean($this->data['User']['password_confirm']);
				$clean_invitation_code = Sanitize::clean($this->data['User']['invitation_code']);
				$errors = array();
				
				// mot de passe check
				if (empty($this->data['User']['password'])) { 
					$errors[] = 'Vous devez renseigner un mot de passe.';
				} else {
					if (empty($clean_password) || $this->data['User']['password'] != $this->Auth->password($clean_password)) { 
						$errors[] = 'Les mots de passe ne correspondent pas.';
					}
				}
				
				if (empty($this->data['User']['email'])) $errors[] = 'Vous devez rentrer votre email.';
				if (empty($this->data['User']['login'])) $errors[] = 'Vous devez choisir un login.';
				if (empty($this->data['User']['postal_code'])) $errors[] = 'Vous devez renseigner votre code postal.';
				
				// invitation check
				if (empty($this->data['User']['invitation_code'])) { 
					$errors[] = 'Vous devez renseigner un code d\'invitation.';
				} else {
					$invitation = $this->User->Invitation->find('first', array('conditions' => array('Invitation.code' => $clean_invitation_code)));
					if (empty($invitation)) { 
						$errors[] = 'Code d\'invitation invalide.';
					} else { 
					
						// il faut que l'invitation soit publique et qu'il y en reste OU que l'invitation soit unique et que l'email corresponde
						if ($invitation['Invitation']['count'] == 0) {
							$errors[] = 'Plus d\'invitation disponible avec ce code.';
						}
						
						if ($invitation['Invitation']['email'] != '' && $invitation['Invitation']['email'] != $clean_email) {
							$errors[] = 'L\'adresse mail que vous avez indiquée ne correspond pas avec celle à laquelle l\'invitation a été envoyé.';
						}
					}
				}
				
				// login unique 
				$login = $this->User->find('count', array('conditions' => array('User.login' => $clean_login)));
				if ($login === 1) 
					$errors[] = 'Ce login est déjà utilisé.';
				
				if (!empty($errors)) {
					$this->set(compact('errors'));
					
				} else {
					$postal_code = $this->data['User']['postal_code'];
					$city = $this->User->City->field('id', array('postal_code' => $postal_code));
					$this->data['User']['city_id'] = $city;
					//$this->data['User']['facebook_id'] = $this->Connect->user('id');
					
					$this->User->Invitation->id = $invitation['Invitation']['id'];
					$countmoinsun = $invitation['Invitation']['count'] - 1;
					$this->User->Invitation->saveField('count', $countmoinsun);
					
					$this->data['User']['invitation_id'] = $invitation['Invitation']['id'];
					
					$result = $this->User->save($this->data);
					if ($result) { 
						$this->Auth->login($this->data);
						$this->redirect('/');
					}
					
				}
			}
			
		}
	}
  
	
	function home() {
		if ($this->Auth->user('id')) {
			$user = $this->User->find('first', array('contain' => array('Artist' => array('ArtistsUser')), 'conditions' => array('id' => $this->Auth->user('id'))));
			$this->User->ArtistsUser->bindModel(array('belongsTo' => array('Artist')));
			
			// reco de festivals
			if (!empty($user['Artist'])) {
				$favorites = $this->User->ArtistsUser->find('count', array('conditions' => array('ArtistsUser.user_id' => $user['User']['id'], 'ArtistsUser.favorite' => 1), 'contain' => array('Artist')));
				$this->set(compact('user', 'favorites'));
				$this->findfest();
				$this->render('profil_recommandations');
			
			// page de bienvenue
			} else {
				$this->layout = 'default';
				$this->render('welcome');
			}
		} else {
			$this->layout = 'landing';
			$this->render('empty');
		}
	}
	
	
	function importFromFb() {
		App::import('Lib', 'Facebook.FB');
		$Facebook = new FB();
	
  	$user_fb_bands = $Facebook->api('/me/music?fields=description,name,picture,category');
 	 	//$user_fb_listens = $Facebook->api('/me/music.listens?fields=id');
		
		
		$user = $this->User->find('first', array(
			'conditions' => array('User.id' => $this->Auth->user('id')), 
			'contain' => array('Artist')
		));
		
		//$this->autoRender = false;

		// Construit 3 tableaux 
		// un contenant tous les id des artistes likés sur 3JD 
		// un contenant tous les facebook_id des artistes likés sur 3JD
		// un contenant tous les futurs artistes à liker sur 3JD
		$artists_to_like = array();
		$artists_ids = array();
		$artists_facebook_ids = array();
		
		if (!empty($user['Artist'])) {
			foreach($user['Artist'] as $tmp_artist) {
				$artists_ids[] = $tmp_artist['id'];
				if (!empty($tmp_artist['facebook_id'])) $artists_facebook_ids[] = $tmp_artist['facebook_id'];
			}
		}
		
		if (!empty($user_fb_bands['data'])) {
			foreach($user_fb_bands['data'] as $band) {
				
				// si l'utilisateur ne like pas encore l'artiste sur 3JD
				// ou que ce n'est pas un groupe
				if(!in_array($band['id'], $artists_facebook_ids) && $band['category'] == 'Musician/band') {
				
					// cherche l'artiste avec son fb_id
					$artist = $this->User->Artist->find('first', array('conditions' => array('Artist.facebook_id' => $band['id']), 'contain' => false));
					
					// cherche l'artiste avec son nom si pas de fb_id
					if (empty($artist)) {
						$artist = $this->User->Artist->find('first', array('conditions' => 'Artist.name LIKE "%' . $band['name'] . '%" OR Artist.other_names LIKE "%' . $band['name'] . '%"', 'contain' => false));
											
						// ajoute le fb_id et la relation
						if (!empty($artist)) {
							$artists_to_like[] = $artist['Artist']['id'];
							$this->User->Artist->id = $artist['Artist']['id'];
							$this->User->Artist->saveField('facebook_id', $band['id']);
							$this->User->Artist->saveField('fb_picture', $band['picture']);
							
						// ajoute l'artiste à la bdd si non trouvé et le like
						} else {
							
							$data = array(
								'name' => $band['name'],
								'facebook_id' => $band['id'],
								'fb_picture' => $band['picture'],
								'url' => formatUrl($band['name'], false),
								'bio' => array(
									'fre' => '',
									'eng' => ''
								)
							);
							
							App::import('Controller', 'Artists');
							$Artists = new ArtistsController;
							$Artists->ConstructClasses();
							$last_id = $Artists->createFromFb($data);
							$artists_to_like[] = $last_id;
						}
					
					// l'artiste a son fb_id rempli mais il ne le like pas encore
					} else {
						$artists_to_like[] = $artist['Artist']['id'];
					}
					
				}
				
			}
		}
		
		// ajoute les artistes à liker à l'user
		if (!empty($artists_to_like)) {
			//$this->User->Artist->Behaviors->disable('Translate');
			foreach($artists_to_like as $artist) {
				$this->User->Artist->habtmAdd('User', $artist, $user['User']['id']);
				//$this->User->Artist->habtmAdd('Artist', $user['User']['id'], $artist);
			}	
		}
		
		$this->redirect('/profil/' . $user['User']['login'] . '/artists');
	}
	
	function importFromLastfm() {
    //on recupere l'utilisateur et la liste de ses artistes liké
		$user_id = $this->Auth->user('id');
    $user = $this->User->find('first', array(
			'conditions' => array('User.id' => $this->Auth->user('id')), 
			'contain' => array('Artist')
		));

    //on interoge les 100 premiers artistes du compte lastfm de l'utilisateur
    $user_lastfm = $user['User']['lastfm'];
    $user_lastfm_artists = $this->Lastfm->get('library.getArtists', array('user' => $user_lastfm, 'limit' => 100, 'api_key' => '397f769265e2296c99cd685300d1c775'), false, false);
    $user_lastfm_artists = $user_lastfm_artists['artists'];
		
		$this->autoRender = false;

		$artists_to_like = array();
    $artists_to_favorite = array();
		$artists_ids = array();
		$artists_allreadyliked_names = array();
    $artists_allreadyfavorite_ids = array();
    
    //on rempli un tableau avec les noms des artistes deja liké et un avec les id des artistes deja favoris
    if (!empty($user['Artist'])) {
			foreach($user['Artist'] as $tmp_artist) {
				$artists_ids[] = $tmp_artist['id'];
				if (!empty($tmp_artist['name'])) $artists_allreadyliked_names[] = $tmp_artist['name'];
        if ($tmp_artist['ArtistsUser']['favorite'] == 1) $artists_allreadyfavorite_ids[] = $tmp_artist['id'];
			}
		}

    //s'il y a des artistes dans la liste lastfm
		if (isset($user_lastfm_artists['artist'])) {
		  $nb_favorite = 0;
			foreach($user_lastfm_artists['artist'] as $band) {
        // cherche si l'artiste existe deja sur 3jd
        $artist = $this->User->Artist->find('first', array('conditions' => 'Artist.name LIKE "%' . $band['name'] . '%" OR Artist.other_names LIKE "%' . $band['name'] . '%"', 'contain' => false));
					
				// si l'utilisateur ne like pas encore l'artiste sur 3JD
				if(!in_array($band['name'], $artists_allreadyliked_names)) {
								
						// s'il n'existe pas, on l'ajoute à la bdd
						if (empty($artist)) {							
							$data = array(
								'name' => $band['name'],
								'url' => formatUrl($band['name'], false),
                //'fb_picture' => preg_replace('/64\/([0-9]*)\.jpg/', '50/$1.jpg', $band['image'][1]['#text']),
                'fb_picture' => preg_replace($band['image'][1]['#text']),
								'bio' => array(
									'fre' => '',
									'eng' => ''
								)
							);
              App::import('Controller', 'Artists');
							$Artists = new ArtistsController;
							$Artists->ConstructClasses();
							$last_id = $Artists->createFromLastfm($data);
              //on l'ajoute à la liste des artistes à liker
							$artists_to_like[] = $last_id;
						}else{
						  //s'il existe deja, on se contente de l'ajouter à la liste d'artiste à liker
						  $artists_to_like[] = $artist['Artist']['id'];
              //et d'ajouter son image
              if(empty($artist['Artist']['fb_picture'])){
                $this->User->Artist->id = $artist['Artist']['id'];
                //$this->User->Artist->saveField('fb_picture', preg_replace('/64\/([0-9]*)\.jpg/', '50/$1.jpg', $band['image'][1]['#text']));
                $this->User->Artist->saveField('fb_picture', $band['image'][1]['#text']);
              }
						}
            if($nb_favorite < 15){
            $nb_favorite ++;
            $artists_to_favorite[] = $artist['Artist']['id'];
          }
				}else{
				  //s'il le like déjà, on se contente de l'ajouter à la liste de favoris
          if($nb_favorite < 15){
            $nb_favorite ++;
            $artists_to_favorite[] = $artist['Artist']['id'];
          }
          //et d'ajouter son image
          if(empty($artist['Artist']['fb_picture'])){
            $this->User->Artist->id = $artist['Artist']['id'];
            //$this->User->Artist->saveField('fb_picture', preg_replace('/64\/([0-9]*)\.jpg/', '50/$1.jpg', $band['image'][1]['#text']));
            $this->User->Artist->saveField('fb_picture', $band['image'][1]['#text']);
          }
				}
			}
		}
		// ajoute les artistes à liker à l'user
		if (!empty($artists_to_like)) {
			foreach($artists_to_like as $artist) {
        $artistuser = $this->User->ArtistsUser->find('first', array('conditions' => array('artist_id = '.$artist, 'user_id = '.$user_id)));
        if(empty($artistuser))
				  $this->User->Artist->habtmAdd('User', $artist, $user['User']['id']);
			}	
		}
    //on ajoute les favoris
    if(empty($artists_allreadyfavorite_ids) AND !empty($artists_to_favorite)){
      foreach($artists_to_favorite as $artist) {
        $artistuser = $this->User->ArtistsUser->find('first', array('conditions' => array('artist_id = '.$artist, 'user_id = '.$user_id)));
				$this->User->ArtistsUser->id = $artistuser['ArtistsUser']['id'];
        $this->User->ArtistsUser->saveField('favorite', 1);
			}
    }
		$this->redirect('/profil/' . $user['User']['login'] . '/artists');
	}
  
	function profil($cat) {
		
		App::import('Core', 'sanitize');
		$user = $this->User->find('count', array('contain' => false, 'conditions' => array('login' => Sanitize::clean($this->params['login']))));

		if ($user != 0) {
			
			switch($cat) {
			
			case 'accueil':
				$user = $this->User->find('first', array('contain' => array('Artist' => array('order' => 'Artist.name ASC'), 'City'), 'conditions' => array('login' => Sanitize::clean($this->params['login']))));
				$date_now = date('Y-m-d', time());
				
				// éditions à venir
				$this->User->EditionsUser->bindModel(array('belongsTo' => array('Edition')));
				$go_editions = $this->User->EditionsUser->find('all', array('conditions' => array('EditionsUser.user_id' => $user['User']['id'], 'EditionsUser.type' => 1), 'contain' => array('Edition' => array('Artist' => array('order' => array('Artist.familiarity DESC'), 'limit' => 10), 'Festival' => array('Country', 'Region', 'Department', 'City', 'Genre'))), 'order' => 'Edition.date_start ASC'));
				
				// maybe editions
				$this->User->EditionsUser->bindModel(array('belongsTo' => array('Edition')));
				$want_editions = $this->User->EditionsUser->find('all', array('conditions' => array('EditionsUser.user_id' => $user['User']['id'], 'EditionsUser.type' => 2), 'contain' => array('Edition' => array('Artist' => array('order' => array('Artist.familiarity DESC'), 'limit' => 10), 'Festival' => array('Country', 'Region', 'Department', 'City', 'Genre'))), 'order' => 'Edition.date_start ASC'));
				
				// éditions passées
				$this->User->EditionsUser->bindModel(array('belongsTo' => array('Edition')));
				$seen_editions = $this->User->EditionsUser->find('all', array('conditions' => array('EditionsUser.user_id' => $user['User']['id'], 'EditionsUser.type' => 3), 'contain' => array('Edition' => array('Artist' => array('order' => array('Artist.familiarity DESC'), 'limit' => 10), 'Festival' => array('Country', 'Region', 'Department', 'City', 'Genre'))), 'order' => 'Edition.date_start DESC'));
				
				$this->set(compact('user', 'go_editions', 'want_editions', 'seen_editions'));
				$this->render('profil');
				break;
			
			case 'artists':
				if ($this->Auth->user('login') === $this->params['login']) {
					$user = $this->User->find('first', array('contain' => false, 'conditions' => array('login' => $this->Auth->user('login'))));
					
					// favorites
					$this->User->ArtistsUser->bindModel(array('belongsTo' => array('Artist')));
					$favorites = $this->User->ArtistsUser->find('all', array('conditions' => array('ArtistsUser.user_id' => $user['User']['id'], 'ArtistsUser.favorite' => 1), 'contain' => array('Artist' => array('order' => 'Artist.name ASC'))));
					
					$artists = $this->User->ArtistsUser->find('all', array('conditions' => array('ArtistsUser.user_id' => $user['User']['id']), 'contain' => array('Artist' => array('order' => 'Artist.name ASC'))));
					if (!empty($artists)) {
						$user_artists_ids = '';
						foreach ($artists as $x => $artist) {
							if ($x != 0) $user_artists_ids .= ', ';
							$user_artists_ids .= $artist['Artist']['id'];
						}
					}
					
					if (empty($artists))
						$popular_artists = $this->User->Artist->find('all', array('conditions' => array('Artist.familiarity > 0.8'), 'order' => 'RAND()', 'limit' => 10));
					else
						$popular_artists = $this->User->Artist->find('all', array('conditions' => array('Artist.familiarity > 0.8 AND Artist.id NOT IN (' . $user_artists_ids . ')'), 'order' => 'RAND()', 'limit' => 10));
					
					$similar_artists = $this->findSimilarArtist();
					
					$this->set(compact('user', 'artists', 'favorites', 'popular_artists', 'similar_artists'));
					$this->render('profil_artists');
					
				} else {
					$this->redirect('/profil/' . $this->params['login']);
				}
				break;
				
		  case 'concerts':
		    if ($this->Auth->user('login') === $this->params['login']) {
		      $user = $this->User->find('first', array('contain' => false, 'conditions' => array('login' => $this->Auth->user('login'))));
		      
		      $this->User->EditionsUser->bindModel(array('belongsTo' => array('Edition')));
		      $seen_editions = $this->User->EditionsUser->find('all', array('conditions' => array('EditionsUser.user_id' => $user['User']['id'], 'EditionsUser.type' => 3), 'contain' => array('Edition' => array('Festival' => array('Country'))), 'order' => 'Edition.date_start DESC'));
				
		      $this->set(compact('user', 'seen_editions'));
		      $this->render('profil_concerts');
		      
		    } else {
  		    $this->redirect('/profil/' . $this->params['login']);
		    }
		    break;
		    
			
			case 'parametres':
				if ($this->Auth->user('login') === $this->params['login']) {
					$user = $this->User->find('first', array('contain' => array('City'), 'conditions' => array('login' => $this->Auth->user('login'))));
					$this->set(compact('user'));
					$this->User->id = $user['User']['id'];
					
					if (empty($this->data)) {
						$this->data = $this->User->read();
					} else {
						
						// Si les mots de passes correspondent
						if (!isset($this->data['User']['password']) || ($this->data['User']['password'] == $this->Auth->password($this->data['User']['password_confirm']) )) {
						
							$fields = array('first_name', 'last_name', 'gender', 'birth_date', 'twitter', 'lastfm', 'soundcloud', 'hypem');
							if (!empty($this->data['User']['password_confirm'])) {
								$fields[] = 'password';
							}
							
							if (!empty($this->data['User']['postal_code'])) {
								$city = $this->User->City->find('first', array('conditions' => array('City.postal_code' => $this->data['User']['postal_code'])));
								if (!empty($city)) {
									$this->data['User']['city_id'] = $city['City']['id'];
									$fields[] = 'city_id';
								}
							}
						
							// Sauvegarde des champs du tableau fields
							if ($this->User->save($this->data, true, $fields)) {
								$this->set('result', '<span class="result saved">Informations sauvegardées.</span>');
							} else {
								$this->set('result', '<span class="result error">Oops, il y a eu un problème lors de la sauvegarde.</span>');
							}
						
						// Sinon message d'erreur sur les mots de passe
						} else {
							$this->Session->setFlash('Oops, les mots de passes ne correspondent pas.', 'growl', array('type' => 'error'));	
						}
					}
					$this->render('profil_parametres');
				} else {
					$this->redirect('/profil/' . $this->params['login']);
				}
				
				break;
			
			default:
				break;
				
			}
			
		} else {
			$this->redirect($this->Session->read('Temp.referer'));
		}
	}
  
  function findSimilarArtist($nb_artist2find = 10, $prop_tab = array()){
    //reset de la recursivité
    $recursive = false;
    $nb_recursive = 0;
    $user_id = $this->Auth->user('id');
    
    //recupere 5 artistes au hasard parmis les artiste likés
    $artists_rand = $this->User->ArtistsUser->find('all', array('limit' => 5, 'conditions' => array('ArtistsUser.user_id' => $user_id), 'contain' => array('Artist' => array('order' => 'RAND()'))));

    if(!empty($artists_rand))
    {
      App::import('Core', 'HttpSocket');
      $HttpSocket = new HttpSocket();
      //contruit la requete api pour chercher 10 artistes similaires a partir des 5 random
      $url = 'http://developer.echonest.com/api/v4/artist/similar?api_key=7CVRJ3W1SLKTU0LYP';
      foreach($artists_rand as $artist)
      {
        $url .= '&name='.$artist['Artist']['name'];
      }
      $url .= '&results=';
      $url .= (int)($nb_artist2find);

      $similar_artists_echonest = json_decode($HttpSocket->get($url));
      if ($similar_artists_echonest->response->status->code === 0) {
  			$similar_artists_echonest =  $similar_artists_echonest->response->artists;
  			
        //pour chaque artiste similaire, on verifie s'il existe deja en bdd
  			foreach($similar_artists_echonest as $j => $tmp_artist) {
  				if (is_object($tmp_artist)) $tmp_artist = get_object_vars($tmp_artist);
  					$tjd_artist = $this->User->Artist->find('first', array(
  						'contain' => false,
  						'conditions' => 'Artist.name LIKE "' . $tmp_artist['name'] . '" OR Artist.other_names LIKE "%' . $tmp_artist['name'] . '" OR Artist.url LIKE "' . formatUrl($tmp_artist['name'], false) . '"'
  					));
  					
            //s'il existe, on verifie s'il est liké
  					if (!empty($tjd_artist)) {
              $is_liked = $this->User->Artist->find('first', array(
    						'contain' => false,
                'joins' => array(
                  array(
                    'table' => 'artists_users',
                    'alias' => 'ArtistsUser',
                    'type' => 'LEFT',
                    'conditions' => array(
                      'ArtistsUser.artist_id = Artist.id',
                    )
                  ),
                ),
    						'conditions' => array(
                  'Artist.id ='.$tjd_artist['Artist']['id'],
                  'ArtistsUser.user_id ='.$user_id
                ),
    					));
              //l'artiste est déjà liké, il faudra reboucler
              if(!empty($is_liked))
              {
                $recursive = true;
                $nb_recursive ++;
              //l'artiste n'est pas liké, on l'ajoute aux propositions
              }else{
                $prop_tab[] = $tjd_artist;
              }
            //s'il n'existe pas, on l'ajoute en bdd
  					}else{
              $hotttnesss = 0;
              $familiarity = 0;
              
              $new_artist_infos = json_decode($HttpSocket->get('http://developer.echonest.com/api/v4/artist/profile?api_key=7CVRJ3W1SLKTU0LYP&name='.$tmp_artist['name'].'&bucket=hotttnesss'));
              if($new_artist_infos->response->status->code === 0){
                $hotttnesss = $new_artist_infos->response->artist->hotttnesss;
              }
              $new_artist_infos = json_decode($HttpSocket->get('http://developer.echonest.com/api/v4/artist/profile?api_key=7CVRJ3W1SLKTU0LYP&name='.$tmp_artist['name'].'&bucket=familiarity'));
              if($new_artist_infos->response->status->code === 0){
                $familiarity = $new_artist_infos->response->artist->familiarity;
              }
              $this->User->Artist->create();
              $this->User->Artist->set(array(
          			'name' => $tmp_artist['name'],
          			'url' => formatUrl($tmp_artist['name'], false),
          			'hotttnesss' => $hotttnesss,
          			'familiarity' => $familiarity,
          			'bio' => array(
          				'fre' => '',
          				'eng' => ''
          			)
          		));
          		$this->User->Artist->save();
              //puis on l'ajoute aux propositions
              $tjd_artist = $this->User->Artist->find('first', array(
    						'contain' => false,
    						'conditions' => 'Artist.name LIKE "' . $tmp_artist['name'] . '" OR Artist.other_names LIKE "%' . $tmp_artist['name'] . '" OR Artist.url LIKE "' . formatUrl($tmp_artist['name'], false) . '"'
    					));
              $prop_tab[] = $tjd_artist;
            }
  			}
  		}
    }
    //on test la recursivité
    if($recursive AND $nb_recursive > 0){
      return $this->findSimilarArtist($nb_recursive, $prop_tab);
    }else{
      return($prop_tab);
    }
  }
  
  function lastfmAuth(){
    $this->Lastfm->init('6f2655486dc5112cfc948bc248628e59', 'f8f90d32adfdc3a4d0bb867417d4ad42');
    $this->Lastfm->authorize('url de retour');
  }
  
  function lastfmGetArtist($lastfm){
    $lastfm_artists = $this->Lastfm->get('library.getArtists', array('user' => $lastfm, 'api_key' => '397f769265e2296c99cd685300d1c775'), false, false);
    return $lastfm_artists;
  }
  
  function findfest(){
    $user_id = $this->Auth->user('id');
    
    $user = $this->User->find('first', array('conditions' => 'user.id = '.$user_id));
    $user_lastfm = $user['User']['lastfm'];
    $lastfm_artists = $this->lastfmGetArtist($user_lastfm);
		//debug($lastfm_artists);
    
    $criteres = array(
      'favorite_artist' => 0.1,
      'liked_artist' => 0.05,
      'similar_artist' => 0.4,
      'seen' => 0.033
    );
    
    //Requete 1 (recuperation du nombre d'artistes présents)
/*
    $req_all_artists = '
      SELECT ae.`edition_id`, e.`date_start`, e.`festival_id`, e.`date_end`, e.`price`, f.`name`, f.`url`, f.`capacity`, f.`photo_r`, f.`city_id`, f.`department_id`, f.`region_id`, f.`country_id`, COUNT(ae.`artist_id`) as nb_artist
      FROM `artists_editions` ae 
      LEFT JOIN `editions` e ON(e.`id` = ae.`edition_id`)
      LEFT JOIN `festivals` f ON(f.`id` = e.`festival_id`)
      GROUP BY ae.`edition_id`
      HAVING e.`date_start` > NOW()';
*/
    $res_editions = $this->User->Edition->find('all', array(
      'fields' => array(
        'Edition.*', 'Festival.*', 'City.*', 'Department.*', 'Region.*', 'Country.*',
        'COUNT(ArtistsEdition.`artist_id`) as nb_artist'
      ),
      'contain' => array('Artist' => array('order' => 'Artist.familiarity DESC')),
      'joins' => array(
        array(
          'table' => 'artists_editions',
          'alias' => 'ArtistsEdition',
          'type' => 'LEFT',
          'conditions' => array(
            'ArtistsEdition.edition_id = Edition.id',
          )
        ),
        array(
          'table' => 'festivals',
          'alias' => 'Festival',
          'type' => 'LEFT',
          'conditions' => array(
            'Edition.festival_id = Festival.id',
          )
        ),
        array(
          'table' => 'cities',
          'alias' => 'City',
          'type' => 'LEFT',
          'conditions' => array(
            'City.id = Festival.city_id',
          )
        ),
        array(
          'table' => 'departments',
          'alias' => 'Department',
          'type' => 'LEFT',
          'conditions' => array(
            'Department.id = Festival.department_id',
          )
        ),
        array(
          'table' => 'regions',
          'alias' => 'Region',
          'type' => 'LEFT',
          'conditions' => array(
            'Region.id = Festival.region_id',
          )
        ),
        array(
          'table' => 'countries',
          'alias' => 'Country',
          'type' => 'LEFT',
          'conditions' => array(
            'Country.id = Festival.country_id',
          )
        ),
      ),
      'group' => 'Edition.id',
      'conditions' => array('Edition.date_end > NOW()'),
   ));
   
    //Requete 2 (recuperation des artistes similaires présents)
    $req_similar_artists = '
      SELECT ae.`edition_id`, e.`date_start`, COUNT(ae.`artist_id`) as nb_similar
      FROM `artists_editions` ae 
      LEFT JOIN `editions` e ON(e.`id` = ae.`edition_id`)
      WHERE e.`date_end` > NOW()
      AND ae.`artist_id` IN
      (
       SELECT ag.`artist_id`
       FROM `artists_genres` ag
       WHERE ag.`genre_id` IN 
       (
        SELECT ag2.`genre_id`
        FROM `artists_users` au2 
        LEFT JOIN `artists_genres` ag2 ON( ag2.`artist_id` = au2.`artist_id`)
        WHERE au2.`user_id` = '.$user_id.'
        AND ag2.`genre_id` IS NOT NULL
        GROUP BY ag2.`genre_id`
       )
      )
      GROUP BY ae.`edition_id`';

    //Requete 3 (recuperation des artistes likés présents)
/*
    $req_liked_artists = '
      SELECT ae.`edition_id`, au.`user_id`, COUNT(au.`artist_id`) AS nb_liked
      FROM `artists_editions` ae 
      LEFT JOIN `artists_users` au ON(au.`artist_id` = ae.`artist_id`)
      LEFT JOIN `editions` e ON(e.`id` = ae.`edition_id`)
      WHERE au.`user_id` = '.$user_id.'
      AND e.`date_start` > NOW()
      GROUP BY ae.`edition_id`';
*/    
    $res_liked_artists = $this->User->Edition->find('all', array(
      'fields' => array(
        'Edition.id',
        'COUNT(ArtistsUser.artist_id) AS nb_liked'
      ),
      'joins' => array(
        array(
          'table' => 'artists_editions',
          'alias' => 'ArtistsEdition',
          'type' => 'LEFT',
          'conditions' => array(
            'ArtistsEdition.edition_id = Edition.id',
          )
        ),
        array(
          'table' => 'artists_users',
          'alias' => 'ArtistsUser',
          'type' => 'LEFT',
          'conditions' => array(
            'ArtistsUser.artist_id = ArtistsEdition.artist_id',
          )
        ),
      ),
			'contain' => false,
      'group' => 'Edition.id',
      'conditions' => array(
        'Edition.date_end > NOW()',
        'ArtistsUser.user_id = '.$user_id,
      ),
   ));
      
    //Requete 4 (recuperation des artistes favoris présents)
/*
    $req_favorite_artists = '
      SELECT ae.`edition_id`, au.`user_id`, COUNT(au.`artist_id`) AS nb_favorite
      FROM `artists_editions` ae 
      LEFT JOIN `artists_users` au ON(au.`artist_id` = ae.`artist_id`)
      LEFT JOIN `editions` e ON(e.`id` = ae.`edition_id`)
      WHERE au.`user_id` = '.$user_id.'
      AND au.`favorite` = 1
      AND e.`date_start` > NOW()
      GROUP BY ae.`edition_id`';
*/   
   $res_favorite_artists = $this->User->Edition->find('all', array(
      'fields' => array(
        'Edition.id',
        'COUNT(ArtistsUser.artist_id) AS nb_favorite'
      ),
      'joins' => array(
        array(
          'table' => 'artists_editions',
          'alias' => 'ArtistsEdition',
          'type' => 'LEFT',
          'conditions' => array(
            'ArtistsEdition.edition_id = Edition.id',
          )
        ),
        array(
          'table' => 'artists_users',
          'alias' => 'ArtistsUser',
          'type' => 'LEFT',
          'conditions' => array(
            'ArtistsUser.artist_id = ArtistsEdition.artist_id',
          )
        ),
      ),
			'contain' => false,
      'group' => 'Edition.id',
      'conditions' => array(
        'Edition.date_end > NOW()',
        'ArtistsUser.user_id = '.$user_id,
        'ArtistsUser.favorite = 1'
      ),
   ));
      
    //Requete 5 (recupere les festivals que l'utilisateur à déjà vu)
/*
    $req_seen_editions = '
      SELECT f.`id`
      FROM `festivals` f
      WHERE f.`id` IN
        (SELECT e.`festival_id`
        FROM `editions_users` eu 
        LEFT JOIN `editions` e ON(e.`id` = eu.`edition_id`)
        WHERE eu.`type` = 3
        AND eu.`user_id` = '.$user_id.')';
        ----------
    SELECT COUNT(eu.type) as nb_vue
    FROM `editions_users` eu 
    LEFT JOIN `editions` e ON(e.`id` = eu.`edition_id`)
    WHERE eu.`type` = 3
    AND eu.`user_id` = 34
    GROUP BY e.festival_id

    $dbo2 = $this->User->Edition->getDataSource();
    $sousRequete = $dbo2->buildStatement(
      array(
        'fields' => array('Edition.festival_id'),
        'table' => $dbo2->fullTableName($this->User->Edition),
        'alias' => 'Edition',
        'joins' => array(
          array(
            'table' => 'editions_users',
            'alias' => 'EditionsUser',
            'type' => 'LEFT',
            'conditions' => array(
              'EditionsUser.edition_id = Edition.id',
            )
          ),
        ),
        'conditions' => array(
          'EditionsUser.type = 3',
          'EditionsUser.user_id = '.$user_id
        ),
        'group' => null,
        'order' => null,
        'limit' => null,
      ),
      $this->Utilisateur
    );
    $sousRequete = 'Festival.id IN ('.$sousRequete.') ';
    $expressionSousRequete = $dbo2->expression($sousRequete);
    $conditions[] = $expressionSousRequete;
    
    $res_seen_editions = $this->User->Edition->Festival->find('all', compact('conditions'));
*/
    $req_seen_editions = '
      SELECT e.festival_id, COUNT(eu.type) as nb_seen 
      FROM `editions_users` eu 
      LEFT JOIN `editions` e ON(e.`id` = eu.`edition_id`) 
      WHERE eu.`type` = 3 
      AND eu.`user_id` = '.$user_id.' 
      GROUP BY e.festival_id';
    $res_seen_editions = $this->User->query($req_seen_editions);
    //Requete 6 (localisation de l'utilisateur)
/*
    $req_user_location = '
      SELECT c.`id` as city, d.`id` as department, r.`id` as region, ct.`id` as country
      FROM `users` u
      LEFT JOIN `cities` c ON(c.`id` = u.`city_id`) 
      LEFT JOIN `departments` d ON(d.`code` LIKE c.`department`)
      LEFT JOIN `regions` r ON(r.`id` = d.`region_id`)
      LEFT JOIN `countries` ct ON(ct.`id` = r.`country_id`)
      WHERE u.`id` = '.$user_id;
*/      
    $res_user_location = $this->User->find('first', array(
      'fields' => array('User.*', 'City.*', 'Department.*', 'Region.*', 'Country.*'),
      'joins' => array(
          array(
            'table' => 'cities',
            'alias' => 'City',
            'type' => 'LEFT',
            'conditions' => array(
              'City.id = User.city_id',
            )
          ),
          array(
            'table' => 'departments',
            'alias' => 'Department',
            'type' => 'LEFT',
            'conditions' => array(
              'Department.code = City.department',
            )
          ),
          array(
            'table' => 'regions',
            'alias' => 'Region',
            'type' => 'LEFT',
            'conditions' => array(
              'Department.region_id = Region.id',
            )
          ),
          array(
            'table' => 'countries',
            'alias' => 'Country',
            'type' => 'LEFT',
            'conditions' => array(
              'Region.country_id = Country.id',
            )
          ),
        ),
			'contain' => false,
      'conditions' => array(
        'User.id = '.$user_id,
      ),
    ));

    $res_similar_artists = $this->User->query($req_similar_artists);
 
    foreach($res_editions as &$edition){
      foreach($res_similar_artists as $artist){
        if($edition['Edition']['id'] == $artist['ae']['edition_id']){
          if(isset($artist[0]['nb_similar']) AND !empty($artist[0]['nb_similar']))
            $edition[0]['similar_artist'] = $artist[0]['nb_similar'];
        }
      }
      foreach($res_liked_artists as $artist){
        if($edition['Edition']['id'] == $artist['Edition']['id']){
          if(isset($artist[0]['nb_liked']) AND !empty($artist[0]['nb_liked']))
            $edition[0]['liked_artist'] = $artist[0]['nb_liked'];
        }
      }
      foreach($res_favorite_artists as $artist){
        if($edition['Edition']['id'] == $artist['Edition']['id']){
          if(isset($artist[0]['nb_favorite']) AND !empty($artist[0]['nb_favorite']))
            $edition[0]['favorite_artist'] = $artist[0]['nb_favorite'];
        }
      }
    }
  
    $seen_tab = array();
    foreach($res_seen_editions as $seen_edition)
      $seen_tab[] = array('festival_id' => $seen_edition['e']['festival_id'], 'nb_seen' => $seen_edition[0]['nb_seen']);

    foreach($res_editions as &$fields){
      if(!isset($fields['Festival']['capacity']))
        $fields['Festival']['capacity'] = 0;
      
      $fest_genre = $this->User->Edition->Festival->find('first', array(
        'contain' => array('Genre'),
        'conditions' => array('Festival.id ='.$fields['Festival']['id'])
      ));
      $fields['Festival']['Genre'] = $fest_genre['Genre'];
      //$fields['Edition']['Genre'] = $Editions->getGenreEdition($fields['Edition']['id']);
        
      $distance = 99;
      if(isset($res_user_location['City']['id']) AND !empty($res_user_location['City']['id']))
        if($res_user_location['City']['id'] == $fields['City']['id'])
          $distance = 1;
      elseif(isset($res_user_location['Department']['id']) AND !empty($res_user_location['Department']['id']))
        if($res_user_location['Department']['id'] == $fields['Department']['id'])
          $distance = 2;
      elseif(isset($res_user_location['Region']['id']) AND !empty($res_user_location['Region']['id']))
        if($res_user_location['Region']['id'] == $fields['Region']['id'])
          $distance = 3;
      elseif(isset($res_user_location['Country']['id']) AND !empty($res_user_location['Country']['id']))
        if($res_user_location['Country']['id'] == $fields['Country']['id'])
          $distance = 4;
      $fields[0]['distance'] = $distance;
      
      $affinity = 0;
      if(!isset($fields[0]['nb_artist']))
        $fields[0]['nb_artist'] = 0;
        
      if(!isset($fields[0]['similar_artist']))
        $fields[0]['similar_artist'] = 0;
        
      if(!isset($fields[0]['liked_artist']))
        $fields[0]['liked_artist'] = 0;
        
      if(!isset($fields[0]['favorite_artist']))
        $fields[0]['favorite_artist'] = 0;

      if((int)($fields[0]['nb_artist']) != 0)
        $affinity += ((int)($fields[0]['similar_artist']) / (int)($fields[0]['nb_artist'])) * (float)($criteres['similar_artist']);
        
      if((int)($fields[0]['liked_artist']) > 6){
        $affinity += 6 * (float)($criteres['liked_artist']);
      }elseif((int)($fields[0]['liked_artist']) > 0){
        $affinity += ((int)($fields[0]['liked_artist'])) * (float)($criteres['liked_artist']);
      }

      if((int)($fields[0]['favorite_artist']) > 3){
        $affinity += 3 * (float)($criteres['favorite_artist']);
      }elseif((int)($fields[0]['favorite_artist']) > 0){
        $affinity += ((int)($fields[0]['favorite_artist'])) * (float)($criteres['favorite_artist']);
      }

      foreach($seen_tab as $fest){
        if($fest['festival_id'] == $fields['Festival']['id']){
          if($fest['nb_seen'] > 3){
            $affinity += 3 * (float)($criteres['seen']);
          }else{
            $affinity += (int)($fest['nb_seen']) * (float)($criteres['seen']);
          }
        }
      }
      
      if($affinity > 1)
        $affinity = 1;
      
      $fields[0]['affinity'] = $affinity;
    }
//debug($res_editions);
    //tri des festivals
    if(isset($this->data) AND isset($this->data['User']['sortby']))
      $sortby = $this->data['User']['sortby'];
    else
      $sortby = 'affinity';
      
    $sorted_fest = $this->sortRecommandations($res_editions, $sortby);
    if(!$sorted_fest)
      return $res_editions;
    else
      $this->set('recommanded_fest', $sorted_fest);
  }
  
  function sortRecommandations($res_editions, $sortby)
  {
    function sort_by_affinity($a,$b){
      return $a[0]['affinity']<$b[0]['affinity'];
    }
    function sort_by_pop($a,$b){
      return $a['Festival']['capacity']<$b['Festival']['capacity'];
    }
    function sort_by_price($a,$b){
      return $a['Edition']['price']>$b['Edition']['price'];
    }
    function sort_by_dist($a,$b){
      return $a[0]['distance']>$b[0]['distance'];
    }
    if(isset($res_editions) AND isset($sortby))
    {
      usort($res_editions, 'sort_by_'.$sortby);
        return $res_editions;
    }
    else
      return false;
  }
	
  function getEditionGenre()
  {
    $this->User->Edition->getGenreEdition();
  }
  
	function editArtist() {
		$this->layout = 'none';
		Configure::write('debug', 0);
		
		if ($this->RequestHandler->isAjax()) {
			$artist_id = $this->params['form']['name'];
			$action = $this->params['form']['rel'];
			
			switch ($action) {
				case 'rm-artist':
					$rmResult = $this->User->ArtistsUser->deleteAll(array('artist_id' => $artist_id, 'user_id' => $this->Auth->user('id')));
					if ($rmResult) {
						$result = array('id' => $artist_id, 'rel' => 'add-artist', 'classToAdd' => 'like', 'classToRemove' => 'delete');
					}
					break;
				
				case 'add-artist':
					$data = array('user_id' => $this->Auth->user('id'), 'artist_id' => $artist_id, 'favorite' => 0);
					$addResult = $this->User->ArtistsUser->save($data);
					if ($addResult)
						$result = array('id' => $artist_id, 'rel' => 'rm-artist', 'classToAdd' => 'delete', 'classToRemove' => 'like');
					break;
				
				case 'add-new':
					$artist = $this->User->Artist->find('first', array('conditions' => 'Artist.name LIKE "%' . $artist_id . '%" OR Artist.other_names LIKE "%' . $artist_id . '%"', 'contain' => false));
					if (!empty($artist)) {
						$id = $artist['Artist']['id'];
						$artist_url = $artist['Artist']['url'];
					} else {
						$data = array(
							'name' => $artist_id,
							'url' => formatUrl($artist_id, false),
							'bio' => array(
								'fre' => '',
								'eng' => ''
							)
						);
						$this->User->Artist->create();
						$this->User->Artist->set($data);
						$this->User->Artist->save();
						$id = $this->User->Artist->getLastInsertID();
						$artist_url = formatUrl($artist_id, false);
					}
					
					$findResult = $this->User->ArtistsUser->find('first', array('conditions' => array('artist_id' => $id, 'user_id' => $this->Auth->user('id'))));
					
					$addResult = false;
					if (empty($findResult)) {
						$data = array('user_id' => $this->Auth->user('id'), 'artist_id' => $id, 'favorite' => 0);
						$addResult = $this->User->ArtistsUser->save($data);
					} else {
						$result = array('alert' => 'Vous avez déjà ajouté cet artiste.');
					}
					
										
					if ($addResult)
						App::import('Helper', 'Html');
       	 		$html = new HtmlHelper();
						
						$text = "<div class='edit-artist' id='art". $id ."'>";
						if (!empty($artist['Artist']['fb_picture']))
							$text .= $html->image($artist['Artist']['fb_picture'], array('class' => 'ppic'));
						else 
							$text .= $html->image('/img/artist/profilepics/default.jpg', array('class' => 'ppic'));

						$text .= '<div class="add-fav">' .  $html->link('', '#', array('rel' => 'add-fav', 'class' => 'tooltip-top', 'title' => 'Ajouter aux favoris', 'escape' => false, 'onclick' => 'return false;', 'name' => $id)) . '</div>';
						$text .= '<div class="rm-artist">' .  $html->link('', '#', array('rel' => 'rm-artist', 'class' => 'tooltip-top', 'title' => 'Supprimer', 'escape' => false, 'onclick' => 'return false;', 'name' => $id)) . '</div>';
						$text .= '<div class="edit-artist-name">' . $html->link($artist_id, '/artist/' .$artist_url) . '</div>';
						$text .=  '</div>';
						
						$result = array('id' => $id, 'html' => $text);
					break;
				
				case 'add-fav':
					// vérifie qu'il y a moins de 15 favoris
					$favorites = $this->User->ArtistsUser->find('all', array('conditions' => array('favorite' => 1, 'user_id' => $this->Auth->user('id'))));
					if (count($favorites) < 15) {
						$findResult = $this->User->ArtistsUser->find('first', array('conditions' => array('artist_id' => $artist_id, 'user_id' => $this->Auth->user('id'))));
						
						// il existe déjà mais pas en favori
						if (!empty($findResult)) {
							$this->User->ArtistsUser->id = $findResult['ArtistsUser']['id'];
							$updateResult = $this->User->ArtistsUser->saveField('favorite', 1);
							if ($updateResult)
								$result = array('id' => $artist_id, 'rel' => 'rm-fav', 'classToAdd' => 'delete', 'classToRemove' => 'star', 'eltToHide' => 'add'. $artist_id);
								
						// il n'existe pas encore
						} else {
							$data = array('user_id' => $this->Auth->user('id'), 'artist_id' => $artist_id, 'favorite' => 1);
							$addResult = $this->User->ArtistsUser->save($data);
							if ($addResult)
								$result = array('id' => $artist_id, 'classToAdd' => 'delete', 'rel' => 'rm-fav', 'classToRemove' => 'star', 'eltToHide' => 'add'. $artist_id);
						}
					} else {
						$result = array('alert' => 'Vous ne pouvez avoir que 15 favoris au maximum.');
					}
					break;
				
				case 'rm-fav':
					$findResult = $this->User->ArtistsUser->find('first', array('conditions' => array('artist_id' => $artist_id, 'user_id' => $this->Auth->user('id'))));
					if (!empty($findResult)) {
						$this->User->ArtistsUser->id = $findResult['ArtistsUser']['id'];
						//$result = $findResult;
						$updateResult = $this->User->ArtistsUser->saveField('favorite', 0);
						if ($updateResult)
							$result = array('id' => $artist_id, 'rel' => 'add-fav', 'classToAdd' => 'star', 'classToRemove' => 'delete', 'eltToShow' => 'add'. $artist_id);
					}
					break;
				
				default:
					break;
			}
			
		}
		
		$result = json_encode($result);
		$this->set(compact('result'));
		$this->render('add_edition');
	}
  
	// TODO : add security
	// Vérifier la date de l'édition pour autoriser les actions
	function addEdition() {
		$this->layout = 'none';
		Configure::write('debug', 0);
		
		if ($this->RequestHandler->isAjax()) {
			$type = $this->params['form']['rel'];
			$edition_id = $this->params['form']['name'];
			$dom_id = $this->params['form']['id'];
			
			// si déjà une relation avec l'édition : on la supprime
			// cas 1 : il like un truc pour la 1e fois, pas de relation, ça passe pas dedans
			// cas 2 : qqch d'autre est déjà en relation, il faut supprimer de toute façon avant de rajouter
			// cas 3 : il veut supprimer une relation (elles sont uniques donc on peut supprimer sans connaitre le type)
			$findResult = $this->User->EditionsUser->find('first', array('fields' => array('EditionsUser.user_id'), 'conditions' => array('edition_id' => $edition_id, 'user_id' => $this->Auth->user('id'))));
			if (!empty($findResult))
				$rmResult = $this->User->EditionsUser->deleteAll(array('edition_id' => $edition_id, 'user_id' => $this->Auth->user('id')));
			else
				$rmResult = true;
			
			switch ($type) {
				case 'go':
					$data = array('user_id' => $this->Auth->user('id'), 'edition_id' => $edition_id, 'type' => 1);
					if ($rmResult && $this->User->EditionsUser->save($data))
						$result = array('classToAdd' => 'delete', 'classToRemove' => 'next', 'eltToHide' => 'want'. $edition_id,'rel' => 'nogo', 'id' => $dom_id, 'name' => $edition_id);
					break;
				case 'want': 
					$data = array('user_id' => $this->Auth->user('id'), 'edition_id' => $edition_id, 'type' => 2);
					if ($this->User->EditionsUser->save($data))
						$result = array('classToAdd' => 'delete', 'classToRemove' => 'like', 'eltToHide' => 'go'. $edition_id, 'rel' => 'nowant', 'id' => $dom_id, 'name' => $edition_id);
					break;
				case 'seen':
					$data = array('user_id' => $this->Auth->user('id'), 'edition_id' => $edition_id, 'type' => 3);
					if ($this->User->EditionsUser->save($data))
						$result = array('classToAdd' => 'delete', 'classToRemove' => 'save', 'rel' => 'noseen', 'id' => $dom_id, 'name' => $edition_id);
					break;
				case 'nogo':
					if ($rmResult)
						$result = array('classToRemove' => 'delete', 'classToAdd' => 'next', 'eltToShow' => 'want'. $edition_id, 'rel' => 'go', 'id' => $dom_id, 'name' => $edition_id);
					break;
				case 'nowant': 
					if ($rmResult)
						$result = array('classToRemove' => 'delete', 'classToAdd' => 'like', 'eltToShow' => 'go'. $edition_id, 'rel' => 'want', 'id' => $dom_id, 'name' => $edition_id);
					break;
				case 'noseen':
					if ($rmResult)
						$result = array('classToRemove' => 'delete', 'classToAdd' => 'save', 'rel' => 'seen', 'id' => $dom_id, 'name' => $edition_id);
					break;
			}
			
			$result = json_encode($result);
			$this->set(compact('result'));
			$this->render('add_edition');
		}
	}
	
	function admin_menu() {
	}
	
	function admin_index() {
		$users = $this->User->find('all');
		$this->set('users', $users);
	}
	
	function admin_add() {
		// Si données envoyée en POST
		if (!empty($this->data)) {
			
			// Si les mots de passes correspondent
			if ($this->data['User']['password'] == $this->Auth->password($this->data['User']['password_confirm'])) {
				$resultat = $this->User->save($this->data);
				if ($resultat) {
					$this->Session->setFlash('L\'utilisateur a été ajouté.', 'growl');	
					$this->redirect(array('controller' => 'users', 'action' => 'index')); 
				}
			} else {
				$this->Session->setFlash('Les mots de passes ne correspondent pas.', 'growl', array('type' => 'error'));	
				$this->redirect(array('controller' => 'users', 'action' => 'add'));
			}
		}
	}
	
	
	function admin_edit($id) {
		$this->User->id = $id;
		
		// Si aucun données envoyées en POST, affichage de l'edit
		if (empty($this->data)) {
			$this->data = $this->User->read();
		
		// Sinon enregistrement
		} else {
			
			// Si le mot de passe est vide => pas de sauvegarde du mot de passe
			if (empty($this->data['User']['password_confirm'])) {
				$fields = array('id', 'login', 'name', 'role');
			} else {
				$fields = array('id', 'login', 'password', 'name', 'role');
			}
			
			// Si les mots de passes correspondent
			if ($this->data['User']['password'] == $this->Auth->password($this->data['User']['password_confirm'])) {
			
				// Sauvegarde des champs du tableau fields
				if ($this->User->save( $this->data, true, $fields )) {
					$this->Session->setFlash('L\'utilisateur a été modifié.', 'growl');	
					$this->redirect(array('controller' => 'users', 'action' => 'index'));
				} else {
					$this->Session->setFlash('Problème de sauvegarde.', 'growl', array('type' => 'error'));	
					$this->redirect(array('controller' => 'users', 'action' => 'index'));
				}
			
			// Sinon message d'erreur sur les mots de passe
			} else {
				$this->Session->setFlash('Les mots de passes ne correspondent pas.', 'growl', array('type' => 'error'));	
				$this->redirect(array('controller' => 'users', 'action' => 'add'));
			}
		}
		
        $this->set(compact('id'));
	}
	
	
	function admin_delete($id) {
		$this->User->delete($id);
		$this->Session->setFlash('L\'utilisateur a été supprimé.', 'growl');	
		$this->redirect(array('controller' => 'users', 'action' => 'index'));
	}
	
	
	function cleanArtistsUser() {
		$this->User->ArtistsUser->bindModel(array('belongsTo' => array('Artist')));
		$artists = $this->User->ArtistsUser->find('all', array('contain' => array('Artist' => array('order' => 'Artist.name ASC'))));
		
		foreach ($artists as $artist) {
			if ($artist['Artist']['name'] == '') {
				$result = $this->User->ArtistsUser->deleteAll(array('artist_id' => $artist['ArtistsUser']['artist_id']));
				if ($result) echo $artist['ArtistsUser']['artist_id'] . ' removed. ';
			}
		}
		debug($artists);
		$this->autoRender = false;
	}
  
  function cleanEditionsUser() {
    /*
		$this->User->EditionsUser->bindModel(array('belongsTo' => array('Edition')));
		$editions = $this->User->EditionsUser->find('all', array('contain' => array('Edition' => array('order' => 'Edition.id ASC'))));
		
		foreach ($editions as $edition) {
			if ($editions['Artist']['name'] == '') {
				$result = $this->User->ArtistsUser->deleteAll(array('artist_id' => $artist['ArtistsUser']['artist_id']));
				if ($result) echo $artist['ArtistsUser']['artist_id'] . ' removed. ';
			}
		}
		debug($artists);
		$this->autoRender = false;
    */
	}

  function backoffice(){
    $req_top50_artist = '
    SELECT a.id, a.name, COUNT(au.user_id) as liked 
    FROM artists a 
    LEFT JOIN artists_users au ON(au.artist_id = a.id) 
    GROUP BY a.id 
    ORDER BY liked DESC
    LIMIT 0, 50
    ';
    
    $req_top10_city = '
    SELECT ct.id, ct.name, COUNT(u.id) as nb_user 
    FROM cities ct
    LEFT JOIN users u ON (u.city_id = ct.id)
    WHERE u.id IS NOT NULL
    GROUP BY ct.id
    ORDER BY nb_user DESC
    LIMIT 0, 10
    ';
    
    $req_top50_age = '
    SELECT (YEAR(CURRENT_DATE)-YEAR(u.birth_date)) - (RIGHT(CURRENT_DATE,5)<RIGHT(u.birth_date,5)) AS age, COUNT(u.id) as nb_user
    FROM users u
    GROUP BY age
    HAVING age IS NOT NULL
    AND age > 0
    ORDER BY nb_user DESC
    LIMIT 0, 50
    ';
    
    $req_rep_gender = '
    SELECT u.gender, COUNT(u.id) as nb_user
    FROM users u
    GROUP BY u.gender
    ORDER BY nb_user DESC
    ';
    
    $res_top50_artist = $this->User->query($req_top50_artist);
    $res_top10_city = $this->User->query($req_top10_city);
    $res_top50_age = $this->User->query($req_top50_age);
    $res_rep_gender = $this->User->query($req_rep_gender);
    
    $this->set('top50_artist', $res_top50_artist);
    $this->set('top10_city', $res_top10_city);
    $this->set('top50_age', $res_top50_age);
    $this->set('rep_gender', $res_rep_gender);
  }

}

?>
<?php
class AppController extends Controller {
	
	
	var $helpers = array ('Html', 'Session', 'Text', 'Js', 'Form', 'I18n', 'Facebook.Facebook' => array('locale' => 'fr_FR'));
	var $components = array(
		'Auth', 
		'Facebook.Connect',
		'Session',
		'RequestHandler',
    'Lastfm'
	);
 
	function beforeFilter() {
			
			$this->set('user', $this->Auth->user());
			$this->set('facebook_user', $this->Connect->user());
			$this->Session->write('Temp.referer', $this->referer());

    	if (isset($this->Auth)) {
				$this->Auth->userModel = 'User';
				$this->Auth->fields = array('username' => 'login', 'password' => 'password');
				$this->Auth->userScope = array('User.disabled' => 0);
				$this->Auth->loginAction = '/login';
				$this->Auth->loginRedirect = $this->params['url'];
				$this->Auth->loginError = "Identifiant ou mot de passe incorrects.";
				$this->Auth->logoutRedirect = '/';
				$this->Auth->authError = "Vous n'avez pas accès à cette page.";
				$this->Auth->autoRedirect = true;
				$this->Auth->authorize = 'controller';
	 
				if($this->params['action'] == 'landing' || $this->params['action'] === 'home' || $this->params['action'] === 'register') {
					$this->Auth->allow();
				}
    	}
			
			if ($this->RequestHandler->isAjax() ) {
				Configure::write('debug', 0);
			}
			
			$role = $this->Auth->user('role');
			$this->set(compact('role'));
			
			if ($role === 'admin') {
				$admin = true;
				$this->set(compact('admin'));
			} else {
				$admin = false;
				$this->set(compact('admin'));
			}
			
			if ($this->Auth->user() && isset($this->params['login'])) {
				$isMyProfile = $this->Auth->user('login') === $this->params['login'];
				$this->set(compact('isMyProfile'));
			}
	}

	function isAuthorized() {
			$role = $this->Auth->user('role');
			
			if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin' && $role !== 'admin') {
				return false;
			} else {
				return true;
			}
	}
	
	function beforeFacebookSave(){
    return false; // dont't create accounts with, we need login and pw
  }
	
	function afterFacebookLogin(){
    $this->redirect('/users/importFromFb');
}
	
	function beforeRender() {
		if(isset($this->params['prefix']) && $this->params['prefix'] == 'admin') {
			// paramètres spécifiques à l'admin
			$this->layout = 'admin_default';
			
			$this->set('admin', $this->Auth->user('role') === 'admin');
			$this->set('name', $this->Auth->user('first_name'));
		}
	}
	
	function admin_build_search_index() {
		$this->autoRender = false;
		$model =& $this->{$this->modelClass};
	 
		if (!isset($model->Behaviors->Searchable)) {
			echo "Erreur : le modèle {$model->alias} n'est pas lié au SearchableBehavior.";
			exit;
		}
	 
		$data = $model->find('all');
	 
		foreach($data as $row) {
			$model->set($row);
	 
			$model->Behaviors->Searchable->Search->saveIndex(
				$model->alias,
				$model->id,
				$model->buildIndex()
			);
		}
	 
		echo "L'index des données du modèle {$model->alias} a été créé.";
	}

	function admin_delete_search_index() {
		$this->autoRender = false;
		$model =& $this->{$this->modelClass};
	 
		if (!isset($model->Behaviors->Searchable)) {
			echo "Erreur : le modèle {$model->alias} n'est pas lié au SearchableBehavior.";
			exit;
		}
	 
		$model->Behaviors->Searchable->Search->deleteAll(array(
			'model' => $model->alias
		));
	 
		echo "L'index des données du modèle {$model->alias} a été supprimé.";
	}

	function admin_rebuild_search_index() {
		$this->admin_delete_search_index();
		$this->admin_build_search_index();
	}

}




?>
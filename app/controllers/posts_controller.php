<?php
class PostsController extends AppController {
	
	var $name = "Posts";
	
	function index() {
  	$user = $this->Post->Artist->User->find('first', array('contain' => array('Artist' => array('ArtistsUser')), 'conditions' => array('id' => $this->Auth->user('id'))));
		$this->Post->Artist->ArtistsUser->bindModel(array('belongsTo' => array('Artist')));
		
		// sibdear
		if (!empty($user['Artist'])) {
			$favorites = $this->Post->Artist->ArtistsUser->find('count', array('conditions' => array('ArtistsUser.user_id' => $user['User']['id'], 'ArtistsUser.favorite' => 1), 'contain' => array('Artist')));
			$this->set(compact('user', 'favorites'));
	  }
	  
	  $this->set('posts', $this->Post->find('all', array('conditions' => array('Post.published' => 1))));
	  
	}
	
	
	function admin_add() {
		$this->set('festivals', $this->Post->Festival->find('list'));

		if (!empty($this->data)) {
		  $this->data['Post']['url'] = formatUrl($this->data['Post']['name']);
			$resultat = $this->Post->save($this->data);
			if ($resultat) {
				$this->Session->setFlash('La news a été ajouté.', 'growl');	
				$this->redirect(array('controller' => 'posts', 'action' => 'index')); 
			}
		}
	}
	
	function admin_index() {
		$this->set('posts', $this->Post->find('all'));
	}
	
	function admin_publish($post_id, $status) {
		$this->Post->id = $post_id;
		$this->Post->saveField('published', $status);
		$this->redirect(array('controller' => 'posts', 'action' => 'index'));
	}
	
	function admin_edit($id = null) {
		
		$this->set('festivals', $this->Post->Festival->find('list'));
		$this->set('post', $this->Post->find('first', array('conditions' => array('Post.id' => $id), 'contain' => array('Artist', 'Festival'))));

		if (!empty($id)) {
			$this->Post->id = $id;
	 
			if (empty($this->data)) {
				$this->data = $this->Post->read(null, $id);
			} else {
				$resultat = $this->Post->save($this->data);
				if ($resultat) {
					$this->Session->setFlash('La news a été modifié.', 'growl');	
					$this->redirect(array('controller' => 'posts', 'action' => 'index')); 
				}
			}
			
		} else {
			$this->Session->setFlash('Aucune news trouvée.', 'growl', array('type' => 'error'));	
			$this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}
		$this->set('data', $this->data);
	}
	
	
	
	function admin_delete($id) {
		$this->Post->del($id);
		$this->Session->setFlash('La news a été supprimé.', 'growl');	
		$this->redirect(array('controller' => 'posts', 'action' => 'index'));
	}
	
}
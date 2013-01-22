<?php
class Actualite extends AppModel {
	
	var $name = 'Actualite';
	
	
	var $actsAs = array(
		'Translate' => array(
			'title' => 'Titres',
			'text' => 'Contenus'
		)
	);


	var $validate = array(
		'title' => array(
			'rule' => array('minLength', 5),
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Doit être renseigné.'
		),
		'text' => array(
			'rule' => array('minLength', 3),
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Doit être renseigné.'
		)
	);

	
}
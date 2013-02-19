<?php
class User extends AppModel {
  
	var $name = 'User';
	var $actsAs = array('ExtendAssociations'); 
	var $hasAndBelongsToMany = array('Artist', 'Edition');
  var $belongsTo = array('City', 'Invitation');
  var $hasMany = array('Submission');
  var $displayField = 'login';
  
	//TODO: ajouter les bonnes expression régulière pour chaque champs.	
	var $validate = array(
		'login' => array(
			'rule' => array('custom', '/^[a-z0-9]{0,19}$/i'),
			'allowEmpty' => false,
			'message' => 'Obligatoire. Ne doit contenir que des chiffres et/ou des lettres et etre unique.'
		),
		'email' => array(
			'rule' => array('minLength', 3),
			'allowEmpty' => false,
			'message' => 'Obligatoire. Veuillez entrer une adresse e-mail valide.'
		),
    'password' => array(
			'rule' => array('minLength', 6),
			'allowEmpty' => false,
			'message' => 'Obligatoire. Minimum 6 caractères.',
		),
		'password_confirm' => array(
			'rule' => array('minLength', 6),
			'allowEmpty' => false,
			'message' => 'Obligatoire. Minimum 6 caractères.',
		),
    'birth_date' => array(
			'rule' => array('minLength', 2),
			'allowEmpty' => true,
			'message' => 'Obligatoire.'
		),
		'postal_code' => array(
			'rule' => array('minLength', 5),
			'allowEmpty' => false,
			'message' => 'Obligatoire. Doit comporter 5 chiffres.'
		)
	);
}
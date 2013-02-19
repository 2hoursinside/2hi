<?php
class Artist extends AppModel {
	
	var $name = 'Artist';
	var $hasMany = array('Liveset');
	var $hasAndBelongsToMany = array('Genre', 'Edition', 'Day', 'User', 'Post');
	var $belongsTo = array('Country');
	
	var $actsAs = array(
		'Translate' => array(
			'bio' => 'Biographies'
		), 
		'Search.Searchable' => array(
			'fields' => array('name')
		),
		'ExtendAssociations'
	);

}
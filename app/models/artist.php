<?php
class Artist extends AppModel {
	
	var $name = 'Artist';
	var $hasAndBelongsToMany = array('Genre', 'Edition', 'Day', 'User');
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
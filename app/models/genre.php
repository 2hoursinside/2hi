<?php
class Genre extends AppModel {
	
	var $name = 'Genre';
	var $hasAndBelongsToMany = array('Artist', 'Festival');
  var $actsAs = array('ExtendAssociations');
  
	var $order = 'Genre.name ASC';
}